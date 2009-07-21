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
Global $debugLog;
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


// Fichier � analyser
$file = $_SERVER["DOCUMENT_ROOT"]."/temp/testExtractionArt.xml";
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
?>

<div id="main_container" class="home">
	<h1>Extraction p&ecirc;che artisanale : choix fili&egrave;res</h1>
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
					WriteCompLog ($logComp, "*- DEBUT EXTRACTION PECHES ARTISANALES ".date('y\-m\-d\-His'),$pasdefichier);
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
				echo $locSelection."<br/>";
				AfficherDonnees($file,"");
				echo "<b>".$labelSelection." selectionnes</b> = ".$compteurItem;
				if (!( $typePeche == "artisanale")) {
					echo "<br/><br/><b>Erreur dans le fichier XML en entr&eacute;e. Il ne s'agit pas d'une s&eacute;lection de donn&eacute;es de p&ecirc;che artisanale.</b><br/>.";
					exit;
				}				
			?>
		</div>
		<br/>
		<div id="runProcess"><b>Choix de la fili&egrave;re :</b>&nbsp;
			<a href="#" onClick="runFilieresArt('<?php echo $typePeche ?>','activite','1','','n')">activit&eacute;</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresArt('<?php echo $typePeche ?>','capture','1','','n')">captures totales</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresArt('<?php echo $typePeche ?>','NtPt','1','','n')">Nt/Pt</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresArt('<?php echo $typePeche ?>','structure','1','','n')">structure de taille</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresArt('<?php echo $typePeche ?>','engin','1','','n')">engins de p&ecirc;he</a>
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
