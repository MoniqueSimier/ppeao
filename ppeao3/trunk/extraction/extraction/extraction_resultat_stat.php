<?php 
//*****************************************
// extraction_resultat_stat.php
//*****************************************
// Created by Yann Laurent
// 2009-07-01 : creation
//*****************************************
// Ce programme gere l'affichage des resultats et l'export vers un fichier csv pour le calcul des stats
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
if ($_SESSION['fichier_xml'] == "" ) {
	$inputXML = "";
	$filename = "ER";	
}else {
	$inputXML = "?xml=".$_SESSION['fichier_xml'];
	$filename =  $_SESSION['fichier_xml'].".xml";
}
$file=$_SERVER["DOCUMENT_ROOT"]."/temp/".$filename;
if (!(file_exists($file)) ) {
	$file = tempnam(sys_get_temp_dir(), 'xmlfile');
	//$file = $_SERVER["DOCUMENT_ROOT"]."/temp/tempExtractionExp.xml";
	$fileopen=fopen($file,'w');
	fwrite($fileopen,$_SESSION["selection_xml"]);
	rewind($fileopen);
}
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
	// On recupere les paramètres
	if (isset($_GET['log'])) {
		if ($_GET['log'] == "false") {
			$EcrireLogComp = false;// Ecrire dans le fichier de log complémentaire. 
		} else {
			if ($inputXML =="") {
				$InputLog = "?logsupp=true";
			}else {
				$InputLog = "&logsupp=true";
			}
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

	if (isset($_GET['action'])) {
		$typeAction = $_GET['action'];
	} else {
		echo "erreur, il manque le parametre action <br/>";
		exit;
	}	
?>

<div id="main_container" class="home">
	<h1>consulter des données : statistiques de p&ecirc;ches</h1>
	<h2>affichage du r&eacute;sultat</h2>
	<p class="hint_text">cette section affiche les r&eacute;sultats pour la s&eacute;lection sous forme de tableaux pagin&eacute;s ou de fichiers exportables</p>
    <br/>
    <?php
	if (isset($_SESSION['s_ppeao_user_id'])){ 
		$userID = $_SESSION['s_ppeao_user_id'];
	} else {
		$userID=null;
	}
// on teste à quelle zone l'utilisateur a accès
	if (userHasAccess($userID,$zone)) {
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "Debut du traitement Resultat de l'extraction des statistiques pour filiere ".$typeAction,$pasdefichier);
		}
	
?>
		
		<div id="resumeChoix">
			<?php
				// Phase préliminaire : on verifie que par hasard, des nouvelles selections n'ont pas
				// ete faires par l'utilisateur juste avant de cliquer sur resultat.
				// Si c'est le cas, les variables de sessions ne seront pas a jour.

				if (isset($_GET['Esp'])) {
					$valeurAMJ = AnaylseVarSession($_GET['Esp']);
					$_SESSION['listeEspeces'] = $valeurAMJ;
				} 					
				if (isset($_GET['Col'])) {
					$ListeColRecues = $_GET['Col'];
				} else {
					$ListeColRecues = "";
				}			
				if (!($ListeColRecues =="")) {
					$colRecues = explode (",",$ListeColRecues);
					$NumColR = count($colRecues) - 1;
					for ($cptCR=0 ; $cptCR<=$NumColR;$cptCR++) {
						// On extrait la valeur brute table.champ sauf dans le cas ou on la valeur XtoutX ou XpasttX
						if (!($colRecues[$cptCR] == "XtoutX") && !($colRecues[$cptCR] == "XpasttX")) { 
							$valTest = substr($colRecues[$cptCR],0,-2);
							// Deux cas de figures : soit le champ a déjà été séléectionné : on le met à jour
							// Sinon on l'ajoute avec sa valeur complete (table.nom-X ou -N)
							// On a besoin de cette info pour cocher ou décocher le champ
							if (strpos($_SESSION['listeColonne'],$valTest) === false ){
								// Cette valeur n'est pas disponible dans la liste : on l'ajoute
								if ($_SESSION['listeColonne'] == "") {
									$_SESSION['listeColonne'] = $colRecues[$cptCR] ;
								} else {
									$_SESSION['listeColonne'] .= ",".$colRecues[$cptCR];
								}		
							} else {
								// La valeur est disponible, on la met à jour
								if (strpos($_SESSION['listeColonne'],$colRecues[$cptCR]) === false) {
									// on doit mettre à jour la valeur
									if (strpos($colRecues[$cptCR],"-X") === false) {
										$oldVal = $valTest."-X";
									} else {
										$oldVal = $valTest."-N";
									}
									$newVal = $colRecues[$cptCR];
									$_SESSION['listeColonne'] = str_replace($oldVal,$newVal,$_SESSION['listeColonne']);
								}
							}					
						} else {
							//$valTest = $colRecues[$cptCR];
							$_SESSION['listeColonne'] = $colRecues[$cptCR];
						}
				
					}
				}
				$exportFichier = false;
				if (isset($_GET['exf'])) {
					if ($_GET['exf'] =="y") {
						$exportFichier = true;
					} 	
				}	
				if (isset($_GET['synth'])) {
					$_SESSION['listetablesynth'] = $_GET['synth'];
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
				// Données pour la selection 
				$typeSelection = "";
				$typePeche = "";
				$typeStatistiques = "";
				$listeGTEngin = "";
				$compteurItem = 0;
				$restSupp = "";
				// Pour construire le bandeau avec la sélection
				$listeSelection ="";
				$resultatLecture = "";
				$labelSelection = "";
				$locSelection = AfficherSelection($file); 
				echo "<b>votre s&eacute;lection correspond &agrave; :</b> ".$locSelection;
				if (!($_SESSION["selection_url"] =="")) {
					echo" <span id=\"changeSel\"><a href=\"".$_SESSION["selection_url"]."\" >changer la sélection</a></span>";
				} 
				echo "<div id=\"filEncours\"><span id=\"filEncoursTit\">fili&egrave;re en cours : </span><span id=\"filEncoursText\">".$typeAction."</span>"; 
				echo "<span id=\"changeSel\"><a href=\"/extraction/extraction/extraction_filieres_stat.php".$inputXML.$InputLog."\" >changer de fili&egrave;re</a></span></div>";
				AfficherDonnees($file,$typeAction);
				echo "<b>restriction(s) suppl&eacute;mentaire(s)</b> : ".$restSupp."<br/>";
				switch ($_SESSION['listetablesynth']) {
					case "cap_tot" : 	$labelSynthese = "r&eacute;sultats globaux"; break;
					case "cap_sp" : 	$labelSynthese = "r&eacute;sultats par esp&egrave;ces"; break;
					case "dft_sp" : 	$labelSynthese = "structure en taille des esp&egrave;ces"; break;
					case "cap_GT" : 	$labelSynthese = "r&eacute;sultats globaux par GT"; break;
					case "cap_GT_sp" : 	$labelSynthese = "r&eacute;sultats par esp&egrave;ces et par GT"; break;
					case "dft_sp_sp" : 	$labelSynthese = "structure en taille des esp&egrave;ces par GT"; break;
					default: $labelSynthese = "inconnu";break;
				}
				echo "<b>table de synthèse en cours </b> : ".$labelSynthese." (".$_SESSION['listetablesynth'].")<br/>";
				echo "<b>".$labelSelection." s&eacute;lectionn&eacute;(e)s</b> = ".$compteurItem;								
				?>

		<br/>
		<div id="resultfiliere"> 
		<?php 
			echo $resultatLecture; 
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "-----------------------------------------------------------------",$pasdefichier);
				WriteCompLog ($logComp, "Fin statistiques  ".$typeAction,$pasdefichier);
				WriteCompLog ($logComp, "-----------------------------------------------------------------",$pasdefichier);
			}
		?>
		</div>
		<?php if (!($exportFichier)) {	?>
		<div id="exportFic2">
			<input type="button" id="validation" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','1','','y','','')" value="Exporter en fichier"/>
			<input type="hidden" id="ExpFic" checked="checked"/></div>			
		</div>
		<?php } else {

		echo "<br/>";
		}	?>

		
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