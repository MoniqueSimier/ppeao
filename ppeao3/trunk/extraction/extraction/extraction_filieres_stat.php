<?php 
//*****************************************
// extraction_filiere.php
//*****************************************
// Created by Yann Laurent
// 2009-06-24 : creation
//*****************************************
// Ce programme gere le choix des filieres et lance les traitements adequats
//*****************************************
// Paramètres en entrée
// aucun pour l'instant.
// Paramètres en sortie
// aucun pour l'instant.
//*****************************************

// definit a quelle section appartient la page
$section="consulter";
$subsection="";
// code commun à toutes les pages (demarrage de session, doctype etc.)
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


// Fichier à analyser
//
// modification par Olivier le 25/09/09 pour utiliser directement la selection passee par selection.php
//$file = $_SERVER["DOCUMENT_ROOT"]."/temp/testExtractionArt.xml";
// on cree un fichier temporaire pour y stocker le contenu de la selection au format XML
// si on a passe un fichier dans l'url on l'utilise
// passer le fichier comme suit dans l'url: &xml=fichier.xml sachant que le fichier doit etre dans le dossier "temp"
// a la racine du site 
if (isset($_GET["xml"])) {
	$filename =  $_GET["xml"].".xml";
	$_SESSION['fichier_xml']=$_GET["xml"];
}else {
	$filename = "ER";
	$_SESSION['fichier_xml'] = "";
}
$file=$_SERVER["DOCUMENT_ROOT"]."/temp/".$filename;
if (!(file_exists($file)) ) {
	$file = tempnam(sys_get_temp_dir(), 'xmlfile');
	$fileopen=fopen($file,'w');
	fwrite($fileopen,$_SESSION["selection_xml"]);
	rewind($fileopen);
}
// fin de modification par Olivier
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
?>


<div id="main_container" class="home">
	<h1>consulter des données : statistiques de p&ecirc;ches</h1>
    <h2>choix compl&eacute;mentaires</h2>
    <?php
	if (isset($_SESSION['s_ppeao_user_id'])){ 
		$userID = $_SESSION['s_ppeao_user_id'];
	} else {
		$userID=null;
	}
	// on teste à quelle zone l'utilisateur a accès
	if (userHasAccess($userID,$zone)) {

?>

		<p class="hint_text">Vous pouvez choisir les fili&egrave;res pour finaliser l'exportation des donn&eacute;es sous forme fichier ou d'affichage &agrave; l'&eacute;cran. </p>
        <span id="affLog">
			<form id="formExtraction" method="get" action="extraction_filieres_exp.php">
			g&eacute;n&eacute;rer un fichier de log compl&eacute;mentaire <input type="checkbox" name="logsupp" id="logsupp" checked="checked"/><br/>
			</form></span>
		<div id="resumeChoix">
			<?php 
				// On recupere les paramètres
				if (isset($_GET['logsupp'])) {
					if ($_GET['logsupp'] == "false") {
						$EcrireLogComp = false;// Ecrire dans le fichier de log complémentaire. 
						echo "<input type=\"hidden\" name=\"logsupp\" id=\"logsupp\" />";
					} else {
						echo "<input type=\"hidden\" name=\"logsupp\" id=\"logsupp\" checked=\"checked\" />";
						$EcrireLogComp = true;
					}
				} else {
					echo "erreur, il manque le parametre log <br/>";
					exit;
				}
				// On récupère les valeurs des paramètres pour les fichiers log
				$dirLog = GetParam("repLogExtr",$PathFicConf);
				$nomLogLien = "/".$dirLog; // pour créer le lien au fichier dans le cr ecran
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
				// Si on change de filière, on remet tous à blanc

				$_SESSION['listeColonne'] = ""; // tableau nomTable / NomChamp des champs comple à afficher
				$_SESSION['listetablesynth'] = ""; // Gestion de la table de synthèse a extraire
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
				// Données pour la selection 
				$typeSelection = "";
				$typePeche = "";
				$typeStatistiques = "";
				$listeEnquete = ""; // contiendra soit la 
				$listeGTEngin = "";
				$compteurItem = 0;
				// Pour construire le bandeau avec la sélection
				$listeSelection ="";
				$resultatLecture = "";
				$labelSelection = "";
				$locSelection = AfficherSelection($file,""); 
				echo "<b>votre s&eacute;lection correspond &agrave; :</b> ".$locSelection."<br/>";
				AfficherDonnees($file,"");
				echo "<b>".$labelSelection." s&eacute;lectionn&eacute;(e)s</b> = ".$compteurItem;
				if (!($_SESSION["selection_url"] =="")) {
					echo" <span id=\"changeSel\"><a href=\"".$_SESSION["selection_url"]."\" >changer la sélection</a></span>";
				}
				if (!( $typeSelection == "statistiques")) {
					echo "<br/><br/><b>Erreur dans le fichier XML en entr&eacute;e. Il ne s'agit pas d'une s&eacute;lection de donn&eacute;es de p&ecirc;che artisanale.</b><br/>.";
					exit;
				}				
			?>
		</div>
		<br/>
		<div id="runProcess"><b>Choix type statistique &agrave; extraire :</b>&nbsp;
			<a href="#" onClick="runFilieresStat('<?php echo $typeStatistiques ?>','globale','1','','n','','','')">statistiques globales</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresStat('<?php echo $typeStatistiques ?>','GT','1','','n','','','')">statistiques par grand type</a>&nbsp;
		</ul>
		</div>
		<div id="resultfiliere"></div>
		<div id="exportFic"></div>
		
<?php
// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}
?>
</div> <!-- end div id="main_container"-->

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
