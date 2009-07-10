<?php 
//*****************************************
// extraction_resultat.php
//*****************************************
// Created by Yann Laurent
// 2009-07-01 : creation
//*****************************************
// Ce programme gere l'affichage des resultats et l'export vers un fichier csv
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


// Fichier à analyser
$file = $_SERVER["DOCUMENT_ROOT"]."/temp/testExtraction.xml";

include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
	// On recupere les paramètres
	if (isset($_GET['log'])) {
		if ($_GET['log'] == "false") {
			$EcrireLogComp = false;// Ecrire dans le fichier de log complémentaire. 
		} else {
			$EcrireLogComp = true;
		}
	} else {
		echo "erreur, il manque le parametre log <br/>";
		exit;
	}
	if (isset($_GET['action'])) {
		$typeAction = $_GET['action'];
	} else {
		echo "erreur, il manque le parametre action <br/>";
		exit;
	}	

?>

<div id="main_container" class="home">
	<h1>Extraction : resultat pour <?php echo $typeAction;?></h1>
	<br/>
	<p>Cette section affiche les resultats de la selection</p>
	<br/>
    <?php
	if (isset($_SESSION['s_ppeao_user_id'])){ 
		$userID = $_SESSION['s_ppeao_user_id'];
	} else {
		$userID=null;
	}
// on teste à quelle zone l'utilisateur a accès
	if (userHasAccess($userID,$zone)) {
	if ($typeAction == "peuplement") {
		// On precharge les valeurs par défaut :
		$_SESSION['listeQualite'] = '1,3,5';
		$_SESSION['listeProtocole'] = '1'; // Oui / non
	}
	// Dans tous les autres cas, toutes les autres valeurs auront été validés avant.
	
?>
		
		<div id="resumeChoix">
			<?php echo "<b>Filiere en cours</b> = ".$typeAction.""; ?>
			<form id="navigation" action="/extraction/extraction/extraction_filieres.php">
			<input type ="submit" value="changer de filiere" />
			</form>
			<br/>
			<?php
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
				// Données pour la selection 
				$typeSelection = "";
				$typePeche = "";
				$typeStatistiques = "";
				$listeEnquete = ""; // contiendra soit la 
				$listeGTEngin = "";
				$compteurItem = 0;
				$restSupp = "";
				// Pour construire le bandeau avec la sélection
				$listeSelection ="";
				$resultatLecture = "";
				$labelSelection = "";
				$locSelection = AfficherSelection($file); 
				echo $locSelection."<br/>";
				AfficherDonnees($file,$typeAction);
				echo "<br/><b>Restriction(s) suppl&eacute;mentaire(s)</b> : ".$restSupp."<br/>";
				echo "<b>".$labelSelection."(s) s&eacute;lectionn&eacute;(e)s</b> = ".$compteurItem;								
				
			?>
		</div>
		<br/>
		<div id="resultfiliere"> 
		<?php 
			switch ($typeAction) {
				case "peuplement" :
					 echo $resultatLecture; 
					break;
				default : 
				echo" resultats pour les autres traitements...";
					break;
			}
		?>
		</div>
		<?php // for test include $_SERVER["DOCUMENT_ROOT"].'/export/export_access.php'; 
		
		?>
		
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