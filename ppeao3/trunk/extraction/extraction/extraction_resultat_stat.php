<?php 
//*****************************************
// extraction_resultat_stat.php
//*****************************************
// Created by Yann Laurent
// 2009-07-01 : creation
//*****************************************
// Ce programme gere l'affichage des resultats et l'export vers un fichier csv pour le calcul des stats
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
$file = $_SERVER["DOCUMENT_ROOT"]."/temp/testExtractionStat.xml";
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
	// On recupere les param�tres
	if (isset($_GET['log'])) {
		if ($_GET['log'] == "false") {
			$EcrireLogComp = false;// Ecrire dans le fichier de log compl�mentaire. 
			$InputLog = "<input type=\"hidden\" name=\"logsupp\" id=\"logsupp\" />";
		} else {
			$InputLog = "<input type=\"hidden\" name=\"logsupp\" id=\"logsupp\" checked=\"checked\" />";
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
// on teste � quelle zone l'utilisateur a acc�s
	if (userHasAccess($userID,$zone)) {
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "Debut du traitement Resultat de l'extraction des peches artisanales pour filiere ".$typeAction,$pasdefichier);
		}

	if ($typeAction == "peuplement") {
		// On precharge les valeurs par d�faut :
		$_SESSION['listeQualite'] = '1,3,5';
		$_SESSION['listeProtocole'] = '1'; // Oui / non
		
	}
	// Dans tous les autres cas, toutes les autres valeurs auront �t� valid�s avant.
	
?>
		
		<div id="resumeChoix">
			<?php echo "<b>Statistiques en cours</b> = ".$typeAction.""; ?>
			<form id="navigation" action="/extraction/extraction/extraction_filieres_art.php">
			<input type ="submit" value="changer de filiere" />
			<?php echo $InputLog; ?>
			</form>
			<br/>
			<?php
				// Phase pr�liminaire : on verifie que par hasard, des nouvelles selections n'ont pas
				// ete faires par l'utilisateur juste avant de cliquer sur resultat.
				// Si c'est le cas, les variables de sessions ne seront pas a jour.

				
				if (isset($_GET['Col'])) {
					$valeurAMJ = AnaylseVarSession($_GET['Col']);
					$_SESSION['listeColonne'] = $valeurAMJ;
				} 
				$exportFichier = false;
				if (isset($_GET['exf'])) {
					if ($_GET['exf'] =="y") {
						$exportFichier = true;
					} 	
				}	
				$SQLPays 	= "";
				$SQLSysteme	= "";
				$SQLSecteur	= "";
				$SQLEngin	= "";
				$SQLGTEngin = "";
				$SQLPeEnquete = "";;
				$SQLEspeces	= "";
				$SQLFamille = "";
				$SQLdateDebut = ""; // format annee/mois
				$SQLdateFin = ""; // format annee/mois
				// Donn�es pour la selection 
				$typeSelection = "";
				$typePeche = "";
				$typeStatistiques = "";
				$listeGTEngin = "";
				$compteurItem = 0;
				$restSupp = "";
				// Pour construire le bandeau avec la s�lection
				$listeSelection ="";
				$resultatLecture = "";
				$labelSelection = "";
				$locSelection = AfficherSelection($file); 
				echo $locSelection."<br/>";
				AfficherDonnees($file,$typeAction);
				echo "<br/><b>Restriction(s) suppl&eacute;mentaire(s)</b> : ".$restSupp."<br/>";
				echo "<b>".$labelSelection."(s) s&eacute;lectionn&eacute;(e)s</b> = ".$compteurItem;								
				?>

		<br/>
		<div id="resultfiliere"> 
		<?php 
			echo $resultatLecture; 
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "-----------------------------------------------------------------",$pasdefichier);
				WriteCompLog ($logComp, "Fin Calcul  ".$typeAction,$pasdefichier);
				WriteCompLog ($logComp, "-----------------------------------------------------------------",$pasdefichier);
			}
		?>
		</div>
		<?php if (!($exportFichier)) {	?>
		<div id="exportFic2">
			<input type="button" id="validation" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','1','','y')" value="Exporter en fichier"/>
			<input type="hidden" id="ExpFic" checked="checked"/></div>			
		</div>
		<?php } else {

		echo "<br/>";
		}	?>

		
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