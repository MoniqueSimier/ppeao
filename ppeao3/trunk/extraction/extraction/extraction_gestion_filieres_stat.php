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
if (isset($_GET['synth'])) {
	$listeSynth = $_GET['synth'];
} else {
	$listeSynth = "";
}
// Gestion des regroupements
if (isset($_GET['RegEC'])) {
	$RegEncours = intval($_GET['RegEC']) + 1;
} else {
	$RegEncours = "";
}
if (isset($_GET['nvReg'])) {
	$CreerReg = $_GET['nvReg'];
} else {
	$CreerReg = "";
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
	$_SESSION['listetablesynth'] ="";
	$_SESSION['listeEspeces'] = "";

} else {
	$_SESSION['listetablesynth'] = $listeSynth;
	$_SESSION['listeEspeces'] = $listeEsp;
}
// On n'affiche pas de sélection de données liées aux especes pour l'environnement
if ($EcrireLogComp ) {
	WriteCompLog ($logComp, "Selection de la filere ".$typeAction,$pasdefichier);
}	
$tab1 = "";
$tab2 = "";
$tab3 = "";
$tab4 = "";
$genActive="";
$colActive="";
$espActive="";
$regActive="";
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
	case "4":
		$regActive=" visible";
		$tab4 = " active";
		break;
}
$valsynth1 = "";
$valsynth2 = "";
$valsynth3 = "";
$valsynth4 = "";
$valsynth5 = "";
$valsynth6 = "";
switch ($_SESSION['listetablesynth']) {
	case "cap_tot" : 	$valsynth1 = "checked=\"checked\""; break;
	case "cap_sp" : 	$valsynth2 = "checked=\"checked\""; break;
	case "dft_sp" : 	$valsynth3 = "checked=\"checked\""; break;
	case "cap_GT" : 	$valsynth4 = "checked=\"checked\""; break;
	case "cap_GT_sp" : 	$valsynth5 = "checked=\"checked\""; break;
	case "dft_sp_sp" : 	$valsynth6 = "checked=\"checked\""; break;
	default  :  $valsynth1 = "checked=\"checked\""; break;
}

?>

<form id="filiere" >
<?php // construit les differentes onglets du tableau ?>
<div id="menuTab">
<a href="#" class="<?php echo $tab1;?>" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','1','<?php echo $codeTableEnCours;?>','n','','','','')">choix tables synth&egrave;ses</a>|
<a href="#" class="<?php echo $tab2;?>" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','2','<?php echo $codeTableEnCours;?>','n','','','','')">colonnes</a>|
<a href="#" class="<?php echo $tab3;?>" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','3','<?php echo $codeTableEnCours;?>','n','','','','')">esp&egrave;ces</a>|
<a href="#" class="<?php echo $tab4;?>" onClick="runFilieresStat('<?php echo $typeStatistiques;?>','<?php echo $typeAction;?>','4','<?php echo $codeTableEnCours;?>','n','','','','')">regroupement Esp&egrave;ces</a>
</div>
<?php // Les differents div correspondant aux choix disponibles par onglet ?>
<?php // l'onglet qui gere la selection des categories ecologiques ?>
<div id="general" class="cateco<?php echo $genActive;?>" >
	<span class="sscriteresgen<?php echo $ClassEnv;?>">choisir la table de synth&egrave;se :<br/></span>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="synthese1" type="radio" name="synthese" value="cap_tot"  <?php echo $valsynth1;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">r&eacute;sultats globaux</span><br/>    
 	<?php if ($typeAction == "globale") { ?>   
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="synthese2" type="radio" name="synthese" value="cap_sp"  <?php echo $valsynth2;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">r&eacute;sultats par esp&egrave;ces</span><br/>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="synthese3" type="radio" name="synthese" value="dft_sp"  <?php echo $valsynth3;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">structure en taille des esp&egrave;ces</span><br/>
    <?php } else { ?>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="synthese1" type="radio" name="synthese" value="cap_GT"  <?php echo $valsynth4;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">r&eacute;sultats globaux par GT</span><br/>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="synthese2" type="radio" name="synthese" value="cap_GT_sp"  <?php echo $valsynth5;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">r&eacute;sultats par esp&egrave;ces et par GT</span><br/>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="synthese3" type="radio" name="synthese" value="dft_sp_sp"  <?php echo $valsynth6;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">structure en taille des esp&egrave;ces par GT</span>
    <?php } ?>
    
</div>
<?php // l'onglet qui gere la selection des colonnes complémentaires ?>
<div id="colonnes" class="colonnes<?php echo $colActive;?>">
<?php echo AfficheColonnes($typeStatistiques,$typeAction,$codeTableEnCours,$numTab,$_SESSION['listeColonne']); ?>
</div>
<?php // l'onglet qui gere les espèces ?>
<div id="especes" class="especes<?php echo $espActive;?>">
<?php echo AfficheEspeces($_SESSION['SQLEspeces'],$listeEsp,$changtAction,$typeStatistiques,$typeAction,$numTab,""); ?>
</div>
<?php // l'onglet qui gere les regroupements ?>
<div id="regroupesp" class="regroupesp<?php echo $regActive;?>">
<?php 
echo AfficheRegroupEsp($typeStatistiques,$typeAction,$numTab,$_SESSION['SQLEspeces'],$_SESSION['ListeRegroupEsp'],$RegEncours,$CreerReg); ?>
</div>
</form>




