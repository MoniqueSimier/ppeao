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

$zone=6; // zone extraction (voir table admin_zones)
?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php 
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<script src="/js/ajaxExtraction.js" type="text/javascript" charset="iso-8859-15"></script>
	<title>ppeao::extraire des donn&eacute;es</title>

</head>

<body>


<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';


// modification par Olivier le 25/09/09 pour utiliser directement la selection passee par selection.php
//$file = $_SERVER["DOCUMENT_ROOT"]."/temp/testExtractionArt.xml";
// on cree un fichier temporaire pour y stocker le contenu de la selection au format XML
// si on a passe un fichier dans l'url on l'utilise
// passer le fichier comme suit dans l'url: &xml=fichier.xml sachant que le fichier doit etre dans le dossier "temp"
// a la racine du site 
if (@fopen($_SERVER["DOCUMENT_ROOT"].'/temp/'.$_GET["xml"])) {
	$file=$_SERVER["DOCUMENT_ROOT"].'/temp/'.$_GET["xml"];
}
// sinon on cree un fichier avec la variable de session
else{
$file = tempnam(sys_get_temp_dir(), 'xmlfile');
$fileopen=fopen($file,'w');
fwrite($fileopen,$_SESSION["selection_xml"]);
rewind($fileopen);}
// fin de modification par Olivier


include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
?>

<div id="main_container" class="home">
	<h1>Extraction p&ecirc;che exp&egrave;rimentale : choix fili&egrave;res</h1>
	<br/>
	<p>Cette section permet de tester l'export des donn&eacute;es apr&egrave;s la s&eacute;lection.</p>
    <?php
	if (isset($_SESSION['s_ppeao_user_id'])){ 
		$userID = $_SESSION['s_ppeao_user_id'];
	} else {
		$userID=null;
	}
	// on teste � quelle zone l'utilisateur a acc�s
	if (userHasAccess($userID,$zone)) {

?>
		<br/>
		<p>Vous pouvez choisir les fili&egrave;res pour finaliser l'exportation des donn&eacute;es sous forme fichier ou d'affichage &agrave; l'&eacute;cran. </p><br/>
			<form id="formExtraction" method="get" action="extraction_filieres_exp.php">
			G&eacute;n&eacute;rer un fichier de log compl&eacute;mentaire <input type="checkbox" name="logsupp" id="logsupp" checked="checked"/><br/><br/>
			</form>
		<div id="resumeChoix">
			<?php 
			
				// Si on change de fili�re, on remet tous � blanc
				$_SESSION['listeQualite'] = '';
				$_SESSION['listeProtocole'] = ''; // Oui / non
				$_SESSION['listeEspeces'] = '';	// Liste des esp�ces selectionn�es
				$_SESSION['listeCatEco'] = ''; 	// Liste des categories ecologiques selectionn�es
				$_SESSION['listeCatTrop'] = ''; // Liste des categories trophiques selectionn�es
				$_SESSION['listeColonne'] = ''; // tableau nomTable / NomChamp des champs comple � afficher
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
				echo $locSelection."<br/>";
				AfficherDonnees($file,"");
				echo "<b>".$labelSelection." selectionnes</b> = ".$compteurItem;
				if (!( $typePeche == "experimentale")) {
					echo "<br/><br/><b>Erreur dans le fichier XML en entr&eacute;e. Il ne s'agit pas d'une s&eacute;lection de donn&eacute;es de p&ecirc;che exp&eacute;rimentale.</b><br/>.";
					exit;
				}				
			?>
		</div>
		<br/>
		<div id="runProcess"><b>Choix de la fili&egrave;re :</b>&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','peuplement','1','','n','','','')">peuplement</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','environnement','1','','n','','','')">environnement</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','NtPt','1','','n','','','')">Nt/Pt</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','biologie','1','','n','','','')">biologie</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresExp('<?php echo $typePeche ?>','trophique','1','','n','','','')">trophique</a>
		</ul>
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
