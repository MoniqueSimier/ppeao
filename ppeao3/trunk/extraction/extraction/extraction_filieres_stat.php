<?php 
//*****************************************
// extraction_filiere.php
//*****************************************
// Created by Yann Laurent
// 2009-06-24 : creation
//*****************************************
// Ce programme gere le choix des filieres et lance les traitements adequats
//*****************************************
// Param�tres en entr�e
// aucun pour l'instant.
// Param�tres en sortie
// aucun pour l'instant.
//*****************************************

// definit a quelle section appartient la page
$section="consulter";
$subsection="";
// code commun � toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
$zone=0; // zone libre (voir table admin_zones)
Global $debugLog;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php 
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<script src="/js/ajaxExtraction.js" type="text/javascript" charset="iso-8859-15"></script>
	<title>ppeao::statistiques::afficher r&eacute;sultats</title>
</head>
<body>
<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
if (isset($_SESSION['s_ppeao_user_id'])){ 
	$userID = $_SESSION['s_ppeao_user_id'];
} else {
	$userID=null;
}
// Fichier de s�lection � analyser
// Soit un fichier issu d'ubne variable de session envoy� par la selection
// Soit depuis un fichier pr�sent dans le repertoire /temp pass� en param�tre &xml=
if (isset($_GET["xml"])) {
	$filename =  $_GET["xml"].".xml";
	$_SESSION['fichier_xml']=$_GET["xml"];
}else {
	$filename = "ER";
	$_SESSION['fichier_xml'] = "";
}
if (isset($_GET["gselec"])) {
	$gardeSelection =  $_GET["gselec"];
}else {
	$gardeSelection = "";
}
$file=$_SERVER["DOCUMENT_ROOT"]."/temp/".$filename;
if (!(file_exists($file)) ) {
	$dirTemp = $_SERVER["DOCUMENT_ROOT"]."/temp/".$userID;
	$resultatDir = creeDirTemp($dirTemp);
	if (strpos("erreur",$resultatDir) === false ){
		$file = $dirTemp."/tempStat.xml";
	} else {
		echo "erreur a la creation du repertoire temporaire ".$dirTemp." arret du traitement.<br/>";
		exit;
	}
	//$file = $_SERVER["DOCUMENT_ROOT"]."/temp/tempExtractionArt.xml";
	$fileopen=fopen($file,'w');
	fwrite($fileopen,$_SESSION["selection_xml"]);
	rewind($fileopen);
}
// fin de modification par Olivier
?>
<div id="main_container" class="home">
	<h1>consulter des donn�es : statistiques de p&ecirc;ches</h1>
    <h2>choix compl&eacute;mentaires</h2>
    <?php
	// on teste � quelle zone l'utilisateur a acc�s
	if (userHasAccess($userID,$zone)) {
?>
		<p class="hint_text">Vous pouvez choisir les fili&egrave;res pour finaliser l'exportation des donn&eacute;es sous forme fichier ou d'affichage &agrave; l'&eacute;cran. </p>
        <span id="affLog">
			<form id="formExtraction" method="get" action="extraction_filieres_exp.php">
			g&eacute;n&eacute;rer un fichier de log compl&eacute;mentaire <input type="checkbox" name="logsupp" id="logsupp" checked="checked"/><br/>
			</form></span>
		<div id="resumeChoix">
			<?php 
				// On recupere les param�tres
				if (isset($_GET['logsupp'])) {
					if ($_GET['logsupp'] == "false") {
						$EcrireLogComp = false;// Ecrire dans le fichier de log compl�mentaire. 
						echo "<input type=\"hidden\" name=\"logsupp\" id=\"logsupp\" />";
					} else {
						echo "<input type=\"hidden\" name=\"logsupp\" id=\"logsupp\" checked=\"checked\" />";
						$EcrireLogComp = true;
					}
				} else {
					echo "erreur, il manque le parametre log <br/>";
					exit;
				}
				// On r�cup�re les valeurs des param�tres pour les fichiers log
				$dirLog = GetParam("repLogExtr",$PathFicConf);
				$nomLogLien = "/".$dirLog; // pour cr�er le lien au fichier dans le cr ecran
				$dirLog = $_SERVER["DOCUMENT_ROOT"]."/".$dirLog;
				$fileLogComp = GetParam("nomFicLogExtr",$PathFicConf);
				$logComp="";
				$nomLogLien="";
				ouvreFichierLog($dirLog,$fileLogComp);
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "#",$pasdefichier);
					WriteCompLog ($logComp, "#",$pasdefichier);
					WriteCompLog ($logComp, "*-#####################################################",$pasdefichier);
					WriteCompLog ($logComp, "*- DEBUT EXTRACTION STATISTIQUES ".date('y\-m\-d\-His'),$pasdefichier);
					WriteCompLog ($logComp, "*-#####################################################",$pasdefichier);
					WriteCompLog ($logComp, "#",$pasdefichier);
					WriteCompLog ($logComp, "#",$pasdefichier);
				}			
				// Si on change de fili�re, on remet tous � blanc
				if (!($gardeSelection == "y")) { 
					$_SESSION['listeColonne'] = ""; // tableau nomTable / NomChamp des champs comple � afficher
					$_SESSION['listetablesynth'] = ""; // Gestion de la table de synth�se a extraire
					$_SESSION['pasderesultat'] = false; // indicateur global si pas de resultat
					$_SESSION['listeDocPays'] = ""; //liste contenant les ID des documents pays a mettre en zip
					$_SESSION['listeDocSys'] = ""; //liste contenant les ID des documents systeme a mettre en zip
					$_SESSION['listeDocSect'] = ""; //liste contenant les ID des documents secteur a mettre en zip
					unset($_SESSION['listeRegroup']); // Liste des regroupements
					unset($_SESSION['libelleTable']); // Pour recuperer les noms des tables
				}
				// Variables pour construire les SQL	
				$SQLPays 	= "";
				$SQLSysteme	= "";
				$SQLSecteur	= "";
				$SQLAgg		= "";
				$SQLEngin	= "";
				$SQLGTEngin = "";
				$SQLCampagne = "";
				$SQLEspeces	= "";
				$SQLFamille = "";
				$SQLdateDebut = ""; // format annee/mois
				$SQLdateFin = ""; // format annee/mois
				// Donn�es pour la selection 
				$typeSelection = "";
				$typePeche = "";
				$typeStatistiques = "";
				$listeEnquete = ""; // contiendra soit la 
				$listeGTEngin = "";
				$compteurItem = 0;
				// Pour construire le bandeau avec la s�lection
				$listeSelection ="";
				$resultatLecture = "";
				$labelSelection = "";
				$locSelection = AfficherSelection($file,""); 

				echo "<span class=\"showHide\">
<a id=\"selection_precedente_toggle\" href=\"#\" title=\"afficher ou masquer la selection\" onclick=\"javascript:toggleSelection();\">[afficher/modifier/masquer la s&eacute;lection]</a></span>";
				echo "<div id=\"selection_precedente\">".$locSelection;
				if (!($_SESSION["selection_url"] =="")) {
					echo" <span id=\"changeSel\"><a href=\"".$_SESSION["selection_url"]."\" >modifier la s&eacute;lection en cours...</a></span>";
				}
				echo "</div>";
				AfficherDonnees($file,"");
				echo "<div id=\"sel_compteur\"><p><b>votre s&eacute;lection correspond &agrave; : </b></p><ul><li>".$compteurItem." ".$labelSelection."</li></ul></div>";
				
				if (!( $typeSelection == "statistiques")) {
					echo "<br/><br/><b>Erreur dans le fichier XML en entr&eacute;e. Il ne s'agit pas d'une s&eacute;lection de donn&eacute;es de p&ecirc;che artisanale.</b><br/>.";
					exit;
				}				
			?>
		</div>
		<br/>
		<div id="runProcess">
        <?php if ($_SESSION['pasderesultat']) {
			echo "La s&eacute;lection n'a pas retourn&eacute; de r&eacute;sultats.<br/>";
		} else { ?>
        <b>Choix type statistique &agrave; extraire :</b>&nbsp;
			<a href="#" onClick="runFilieresStat('<?php echo $typeStatistiques ?>','globale','1','','n','','','')">statistiques globales</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresStat('<?php echo $typeStatistiques ?>','GT','1','','n','','','')">statistiques par grand type</a>&nbsp;
		</ul>
        <?php } ?>
		</div>
		<div id="resultfiliere"></div>
		<div id="exportFic"></div>
        <script type="text/javascript" charset="utf-8">
			var mySlider = new Fx.Slide('selection_precedente', {duration: 500});
			mySlider.hide();
			// affiche ou masque le DIV contenant la selection precedente
			function toggleSelection() {
				mySlider.toggle() //toggle the slider up and down.
			}
		</script>		
<?php
// note : on termine la boucle testant si l'utilisateur a acc�s � la page demand�e

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas acc�s ou n'est pas connect�, on affiche un message l'invitant � contacter un administrateur pour obtenir l'acc�s
else {userAccessDenied($zone);}
?>
</div> <!-- end div id="main_container"-->

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
