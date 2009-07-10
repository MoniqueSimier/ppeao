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

// declaration variables
$peupActive = "";
$envActive = "";
$NtActive = "";
$bioActive = "";
$trophActive = "";
session_start();
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/extraction/extraction_xml.php';
// On recupere les param�tres
if (isset($_GET['log'])) {

	if ($_GET['log'] == "false") {
		$EcrireLogComp = false;// Ecrire dans le fichier de log compl�mentaire. 
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
if (isset($_GET['tp'])) {
	$typePeche = $_GET['tp'];
} else {
	echo "erreur, il manque le parametre type de peche<br/>";
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
if (isset($_GET['qual'])) {
	$listeQual = $_GET['qual'];
} else {
	$listeQual = "";
}
if (isset($_GET['rest'])) {
	$listeRest = $_GET['rest'];
} else {
	$listeRest = "";
}
if (isset($_GET['CE'])) {
	$listeCE = $_GET['CE'];
} else {
	$listeCE = "";
}
if (isset($_GET['CT'])) {
	$listeCT = $_GET['CT'];
} else {
	$listeCT = "";
}
if (isset($_GET['TEC'])) {
	$codeTableEnCours = $_GET['TEC'];
} else {
	$codeTableEnCours = "py";
}
if (isset($_GET['Col'])) {
	$ListeColRecues = $_GET['Col'];
} else {
	$ListeColRecues = "";
}
// On analyse les nouvelles colonnes recues si on vient du tab 4
if (!($ListeColRecues =="")) {
	$colRecues = explode (",",$ListeColRecues);
	$NumColR = count($colRecues) - 1;
	for ($cptCR=0 ; $cptCR<=$NumColR;$cptCR++) {
		if (strpos($_SESSION['listeColonne'],$colRecues[$cptCR]) === false) {
			// Cette valeur n'est pas disponible dans la liste : on l'ajouter
			if ($_SESSION['listeColonne'] == "") {
				$_SESSION['listeColonne'] = $colRecues[$cptCR] ;
			} else {
				$_SESSION['listeColonne'] .= ",".$colRecues[$cptCR];
			}
		} 
	
	}
}
// Pr�chargement des valeurs par d�faut
// car on a chang� de filiere ou on commence
if ($changtAction == "y") {
	$_SESSION['listeCatTrop'] ="";
	$_SESSION['listeCatEco'] = "";
	$_SESSION['listeColonne'] = "";
	switch ($typeAction) {
		// On ne g�re pas peuplement car on va directement � la page de r�sultat quand on clique dessus.
		case "environnement":
				// On pr�charge les valeurs par defaut
				$_SESSION['listeQualite'] = '1,3,5';
				$_SESSION['listeProtocole'] = '1';
			break;
		case "NtPt":
				// On pr�charge les valeurs par defaut
				$_SESSION['listeQualite'] = '1,3,5';
				$_SESSION['listeProtocole'] = '1';
			break;
		case "biologie":
				// On pr�charge les valeurs par defaut
				$_SESSION['listeQualite'] = '1,3,5';
				$_SESSION['listeProtocole'] = '1';
			break;
		case "trophique":
				// On pr�charge les valeurs par defaut
				$_SESSION['listeQualite'] = '1,2,3,4,5';
				$_SESSION['listeProtocole'] = '0';
			break;
	}
} else {
	$_SESSION['listeQualite'] = $listeQual;
	$_SESSION['listeProtocole'] = $listeRest;
	if (!($_SESSION['listeCatEco'] == $listeCE) && !($listeCE =="")) {
		$_SESSION['listeCatEco'] = $listeCE;
	}
	$_SESSION['listeCatTrop'] = $listeCT;
}
	
$tab1 = "";
$tab2 = "";
$tab3 = "";
$tab4 = "";
$cgActive="";
$ceActive="";
$ctActive="";
$colActive="";
switch ($numTab) {
	case "1":
		$cgActive=" visible";
		$tab1 = " active";
		break;
	case "2":
		$ceActive=" visible";
		$tab2 = " active";
		break;
	case "3":
		$ctActive=" visible";
		$tab3 = " active";
		break;
	case "4":
		$colActive=" visible";
		$tab4 = " active";
		break;
}
// Gestion des valeurs d�j� saisies ou valeurs par d�faut


if (strpos($_SESSION['listeQualite'],"1")  === false ) {$valQual1 =""; } else {$valQual1 = "checked=\"checked\"";}
if (strpos($_SESSION['listeQualite'],"2")  === false ) {$valQual2 =""; } else {$valQual2 = "checked=\"checked\"";}
if (strpos($_SESSION['listeQualite'],"3")  === false ) {$valQual3 =""; } else {$valQual3 = "checked=\"checked\"";}
if (strpos($_SESSION['listeQualite'],"4")  === false)  {$valQual4 =""; } else {$valQual4 = "checked=\"checked\"";}
if (strpos($_SESSION['listeQualite'],"5")  === false ) {$valQual5 =""; } else {$valQual5 = "checked=\"checked\"";}
if (strpos($_SESSION['listeProtocole'],"1")  === false ) {$valProt1 =""; } else {$valProt1 = "checked=\"checked\"";}
if (strpos($_SESSION['listeProtocole'],"0")  === false ) {$valProt2 =""; } else {$valProt2 = "checked=\"checked\"";}
?>

<form id="filiere" >
<?php // construit les differentes onglets du tableau ?>
<div id="menuTab">
<a href="#" class="<?php echo $tab1;?>" onClick="runFilieres('<?php echo $typePeche;?>','<?php echo $typeAction;?>','1','<?php echo $codeTableEnCours;?>','n')">Crit&egrave;res g&eacute;n&eacute;raux</a>|
<a href="#" class="<?php echo $tab2;?>" onClick="runFilieres('<?php echo $typePeche;?>','<?php echo $typeAction; ?>','2','<?php echo $codeTableEnCours;?>','n')">Cat&eacute;gories &eacute;cologiques</a>|
<a href="#" class="<?php echo $tab3;?>" onClick="runFilieres('<?php echo $typePeche;?>','<?php echo $typeAction;?>','3','<?php echo $codeTableEnCours;?>','n')">Cat&eacute;gories trophiques</a>|
<a href="#" class="<?php echo $tab4;?>" onClick="runFilieres('<?php echo $typePeche;?>','<?php echo $typeAction;?>','4','<?php echo $codeTableEnCours;?>','n')">Colonnes</a>
</div>
<?php // Les differents div correspondant aux choix disponibles par onglet ?>
<div id="criteresgen" class="criteresgen<?php echo $cgActive;?>">
	Choix de la qualit&eacute; du coup de p&ecirc;che :
	<input id="qualiteCP1" type="checkbox" name="qualiteCP" value="1"  <?php echo $valQual1;?>/>1
	<input id="qualiteCP2" type="checkbox" name="qualiteCP" value="2"  <?php echo $valQual2;?>/>2
	<input id="qualiteCP3" type="checkbox" name="qualiteCP" value="3"  <?php echo $valQual3;?>/>3
	<input id="qualiteCP4" type="checkbox" name="qualiteCP" value="4"  <?php echo $valQual4;?>/>4
	<input id="qualiteCP5" type="checkbox" name="qualiteCP" value="5"  <?php echo $valQual5;?>/>5
	<br/>
	Restreindre aux coups du protocole:
	<input id="restreindre1" type="radio" name="restreindre" value="1"  <?php echo $valProt1;?>/> oui
	<input id="restreindre2" type="radio" name="restreindre" value="0"  <?php echo $valProt2;?>/> non
<br/>
</div>
<?php // l'onglet qui gere la selection des categories ecologiques ?>
<div id="cateco" class="cateco<?php echo $ceActive;?>">
<?php echo AfficheCategories("Ecologiques",$typeAction,$_SESSION['listeCatEco']); ?>
</div>
<?php // l'onglet qui gere la selection des categories trophiques ?>
<div id="cattroph" class="cattroph<?php echo $ctActive;?>">
<?php echo AfficheCategories("Trophiques",$typeAction,$_SESSION['listeCatTrop']); ?>
</div>
<?php // l'onglet qui gere la selection des colonnes compl�mentaires ?>
<div id="colonnes" class="colonnes<?php echo $colActive;?>">
<?php echo AfficheColonnes($typePeche,$typeAction,$codeTableEnCours,$numTab); ?>
</div>
<?php // Permet d'obtenir le r�sultat ?>
<div id="voiresultat"><input type="button" id="validation" onClick="runFilieres('<?php echo $typePeche;?>','<?php echo $typeAction;?>','1','<?php echo $codeTableEnCours;?>','y')">" value="Voir les r&eacute;sultats"/></div>

</form>
