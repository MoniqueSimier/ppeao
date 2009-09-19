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
Global $debugLog;
// declaration variables
$peupActive = "";
$envActive = "";
$NtActive = "";
$bioActive = "";
$trophActive = "";
session_start();
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
// On recupere les paramètres
if (isset($_GET['log'])) {
	if ($_GET['log'] == "false") {
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

if (isset($_GET['action'])) {
	$typeAction = $_GET['action'];
} else {
	echo "erreur, il manque le parametre action <br/>";
	exit;
}
if (isset($_GET['ts'])) {
	$typeStatistiques = $_GET['ts'];
} else {
	echo "erreur, il manque le parametre type de statistiques<br/>";
	exit;
}
if (isset($_GET['tab'])) {
	$numTab = $_GET['tab'];
} else {
	$numTab = "1";
}
if (isset($_GET['chgA'])) {
	$changtAction = $_GET['chgA'];
} else {
	$changtAction = "n";
}
if (isset($_GET['Col'])) {
	$ListeColRecues = $_GET['Col'];
} else {
	$ListeColRecues = "";
}
if (isset($_GET['TEC'])) {
	$codeTableEnCours = $_GET['TEC'];
} else {
	$codeTableEnCours = "py";
}
if (isset($_GET['Esp'])) {
	$listeEsp = $_GET['Esp'];
} else {
	$listeEsp = "";
}
// On analyse les nouvelles colonnes recues si on vient du tab 4
if (!($ListeColRecues =="")) {
	$colRecues = explode (",",$ListeColRecues);
	$NumColR = count($colRecues) - 1;
	for ($cptCR=0 ; $cptCR<=$NumColR;$cptCR++) {
		// On extrait la valeur brute table.champ sauf dans le cas ou on la valeur XtoutX ou XpasttX
		if (!($colRecues[$cptCR] == "XtoutX") && !($colRecues[$cptCR] == "XpasttX")) { 
			$valTest = substr($colRecues[$cptCR],0,-2);
			if ($EcrireLogComp && $debugLog) {
				WriteCompLog ($logComp, "DEBUG : ".$colRecues[$cptCR]." - ".$valTest,$pasdefichier);
			}

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
// Préchargement des valeurs par défaut
// car on a changé de filiere ou on commence
// Attention aux valeurs poisson / non_poisson (non_poisson = 1 correspond a la selection des especes qui ne sont pas des poissons
if ($changtAction == "y") {
	$_SESSION['listeCatTrop'] ="";
	$_SESSION['listeCatEco'] = "";
	$_SESSION['listeColonne'] = "";
	$_SESSION['listeEspeces'] = "";

} else {

	$_SESSION['listeEspeces'] = $listeEsp;
}
// On n'affiche pas de sélection de données liées aux especes pour l'environnement
if ($EcrireLogComp ) {
	WriteCompLog ($logComp, "Selection de la filere ".$typeAction,$pasdefichier);
}	
$tab1 = "";
$tab2 = "";
$tab3 = "";
$genActive="";
$colActive="";
$espActive="";
switch ($numTab) {
	case "1":
		$genActive=" visible";
		$tab1 = " active";
		break;
	case "2":
		$colActive=" visible";
		$tab2 = " active";
		break;
	case "3":
		$espActive=" visible";
		$tab3 = " active";
		break;
}

?>

<form id="filiere" >
<?php // construit les differentes onglets du tableau ?>
<div id="menuTab">
<a href="#" class="<?php echo $tab1;?>" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','1','<?php echo $codeTableEnCours;?>','n','','','')">G&eacute;n&eacute;ral</a>|
<a href="#" class="<?php echo $tab2;?>" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','2','<?php echo $codeTableEnCours;?>','n','','','')">Colonnes</a>|
<a href="#" class="<?php echo $tab3;?>" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','3','<?php echo $codeTableEnCours;?>','n','','','')">Esp&egrave;ces</a>
</div>
<?php // Les differents div correspondant aux choix disponibles par onglet ?>


<?php // l'onglet qui gere la selection des categories ecologiques ?>
<div id="general" class="cateco<?php echo $genActive;?>">
Selections generales
</div>
<?php // l'onglet qui gere la selection des colonnes complémentaires ?>
<div id="colonnes" class="colonnes<?php echo $colActive;?>">
<?php echo AfficheColonnes($typeStatistiques,$typeAction,$codeTableEnCours,$numTab); ?>
</div>
<?php // l'onglet qui gere les espèces ?>
<div id="especes" class="especes<?php echo $espActive;?>">
<?php echo AfficheEspeces($_SESSION['SQLEspeces'],$listeEsp,$changtAction,,$typeStatistiques,$typeAction,$numTab,""); ?>
</div>
</form>




