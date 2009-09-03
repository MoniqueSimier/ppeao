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


// Fichier � analyser
$file = $_SERVER["DOCUMENT_ROOT"]."/temp/testExtractionStat.xml";

include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
?>

<div id="main_container" class="home">
	<h1>Statistiques de peches</h1>
	<br/>
	<p>Cette section permet de tester le calcul des statistiques de peches</p>
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
				if (!( $typeSelection == "statistiques")) {
					echo "<br/><br/><b>Erreur dans le fichier XML en entr&eacute;e. Il ne s'agit pas d'une s&eacute;lection de donn&eacute;es de p&ecirc;che artisanale.</b><br/>.";
					exit;
				}				
			?>
		</div>
		<br/>
		<div id="runProcess"><b>Choix type statistique &agrave; extraire :</b>&nbsp;
			<a href="#" onClick="runFilieresStat('<?php echo $typeStatistiques ?>','globale','1','','n','')">statistiques globales</a>&nbsp;-&nbsp;
			<a href="#" onClick="runFilieresStat('<?php echo $typeStatistiques ?>','GT','1','','n','')">statistiques par Grand Type</a>&nbsp;
		</ul>
		</div>
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
