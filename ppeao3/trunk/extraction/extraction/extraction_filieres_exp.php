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
	<title>ppeao::extraire des donn&eacute;es::fili&egrave;res</title>
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
$file=$_SERVER["DOCUMENT_ROOT"]."/temp/".$filename;
if (!(file_exists($file)) ) {
	$dirTemp = $_SERVER["DOCUMENT_ROOT"]."/temp/".$userID;
	$resultatDir = creeDirTemp($dirTemp);
	if (strpos("erreur",$resultatDir) === false ){
		$file = $dirTemp."/tempExp.xml";
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
	<h1>consulter des donn�es : extraction des p&ecirc;ches exp&eacute;rimentales</h1>
    <h2>choix des fili&egrave;res</h2>
    <?php

	// on teste � quelle zone l'utilisateur a acc�s
	if (userHasAccess($userID,$zone)) {

?>

		<p class="hint_text">vous pouvez choisir les fili&egrave;res pour finaliser l'exportation des donn&eacute;es sous forme fichier ou d'affichage &agrave; l'&eacute;cran. </p>
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
					WriteCompLog ($logComp, "*- DEBUT EXTRACTION PECHES EXPERIMENTALES ".date('y\-m\-d\-His'),$pasdefichier);
					WriteCompLog ($logComp, "*-#####################################################",$pasdefichier);
					WriteCompLog ($logComp, "#",$pasdefichier);
					WriteCompLog ($logComp, "#",$pasdefichier);
				}
			
				// Si on change de fili�re, on remet tous � blanc
				$_SESSION['listeQualite'] = '';
				$_SESSION['listeProtocole'] = ''; // Oui / non
				$_SESSION['listeEspeces'] = '';	// Liste des esp�ces selectionn�es
				$_SESSION['listeCatEco'] = ''; 	// Liste des categories ecologiques selectionn�es
				$_SESSION['listeCatTrop'] = ''; // Liste des categories trophiques selectionn�es
				$_SESSION['listeColonne'] = ''; // tableau nomTable / NomChamp des champs comple � afficher
				$_SESSION['pasderesultat'] = false; // indicateur global si pas de resultat
				// Variables pour construire les SQL	
				$SQLPays 	= "";
				$SQLSysteme	= "";
				$SQLSecteur	= "";
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
				echo "<b>votre s&eacute;lection correspond &agrave; :</b> ".$locSelection."<br/>";
				AfficherDonnees($file,"");
				echo "<b>".$labelSelection." s&eacute;lectionn&eacute;(e)s</b> = ".$compteurItem;
				if (!($_SESSION["selection_url"] =="")) {
					echo" <span id=\"changeSel\"><a href=\"".$_SESSION["selection_url"]."\" >changer la s�lection</a></span>";
				}				
				if (!( $typePeche == "experimentale")) {
					echo "<br/><br/><b>Erreur dans le fichier XML en entr&eacute;e. Il ne s'agit pas d'une s&eacute;lection de donn&eacute;es de p&ecirc;che exp&eacute;rimentale.</b><br/>.";
					exit;
				}				
			?>
		</div>
		<br/>
		<div id="runProcess">        
        <?php if ($_SESSION['pasderesultat']) {
			echo "La s&eacute;lection n'a pas retourn&eacute; de r&eacute;sultats.<br/>";
		} else { ?>
			<b>choix de la fili&egrave;re :</b>&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','peuplement','1','','n','','','','')">peuplement</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','environnement','1','','n','','','','')">environnement</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','NtPt','1','','n','','','','')">Nt/Pt</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','biologie','1','','n','','','','')">biologie</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','trophique','1','','n','','','','')">trophique</a>
		</ul>
        <?php } ?>
		</div>
		<br/>
		<div id="resultfiliere"></div>
		<div id="exportFic"></div>
		
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
