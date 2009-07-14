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
if (isset($_GET['pois'])) {
	$listePois = $_GET['pois'];
} else {
	$listePois = "";
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
// On  recontruit complement la variable de session avec ce qui a ete saisie
if (!($ListeColRecues =="")) {
	$_SESSION['listeColonne'] = "";
	$colRecues = explode (",",$ListeColRecues);
	$NumColR = count($colRecues) - 1;
	for ($cptCR=0 ; $cptCR<=$NumColR;$cptCR++) {
		//if (strpos($_SESSION['listeColonne'],$colRecues[$cptCR]) === false) {
			// Cette valeur n'est pas disponible dans la liste : on l'ajouter
			if ($_SESSION['listeColonne'] == "") {
				$_SESSION['listeColonne'] = $colRecues[$cptCR] ;
			} else {
				$_SESSION['listeColonne'] .= ",".$colRecues[$cptCR];
			}
		//} 
	
	}
}
// Préchargement des valeurs par défaut
// car on a changé de filiere ou on commence
// Attention aux valeurs poisson / non_poisson (non_poisson = 1 correspond a la selection des especes qui ne sont pas des poissons
if ($changtAction == "y") {
	$_SESSION['listeCatTrop'] ="";
	$_SESSION['listeCatEco'] = "";
	$_SESSION['listeColonne'] = "";
	switch ($typeAction) {
		// On ne gère pas peuplement car on va directement à la page de résultat quand on clique dessus.
		case "activite":
				// On précharge les valeurs par defaut
				$_SESSION['listePoisson'] = "";;
			break;
		case "activite":
				// On précharge les valeurs par defaut
				$_SESSION['listePoisson'] = "";;
			break;
		case "NtPt":
				// On précharge les valeurs par defaut
				$_SESSION['listePoisson'] = "0,np";
			break;
		case "biologie":
				// On précharge les valeurs par defaut
				$_SESSION['listePoisson'] = "0,np";
			break;
		case "trophique":
				// On précharge les valeurs par defaut
				$_SESSION['listePoisson'] = "0,np";
			break;
	}
} else {
	$_SESSION['listePoisson'] = $listePois;
	if (!($_SESSION['listeCatEco'] == $listeCE) && !($listeCE =="")) {
		$_SESSION['listeCatEco'] = $listeCE;
	}
	$_SESSION['listeCatTrop'] = $listeCT;
}
// On n'affiche pas de sélection de données liées aux especes pour l'environnement
	
$tab1 = "";
$tab2 = "";
$tab3 = "";
$tab4 = "";
$cgActive="";
$ceActive="";
$ctActive="";
$colActive="";
$ClassEnv = "";
switch ($numTab) {
	case "1":
		if ($typeAction == "activite") {
			$cgActive="";
			$ClassEnv = "";
		} else {
			$cgActive=" visible";
			$ClassEnv = " visible";
		}
		$tab1 = " active";
		
		
		break;
	case "2":
		if (!($typeAction == "activite")) {
			$ceActive=" visible";
		}
		$tab2 = " active";
		break;
	case "3":
		if (!($typeAction == "activite")) {
			$ctActive=" visible";
		}
		
		$tab3 = " active";
		break;
	case "4":
		$colActive=" visible";
		$tab4 = " active";
		break;
}
// Gestion des valeurs déjà saisies ou valeurs par défaut

if (strpos($_SESSION['listePoisson'],"0")  === false ) {$valPois1 =""; } else {$valPois1 = "checked=\"checked\"";}
if (strpos($_SESSION['listePoisson'],"pp")  === false ) {$valPois2 =""; } else {$valPois2 = "checked=\"checked\"";}
if (strpos($_SESSION['listePoisson'],"1")  === false ) {$valPois3 =""; } else {$valPois3 = "checked=\"checked\"";}
if (strpos($_SESSION['listePoisson'],"np")  === false ) {$valPois4 =""; } else {$valPois4 = "checked=\"checked\"";}
?>

<form id="filiere" >
<?php // construit les differentes onglets du tableau ?>
<div id="menuTab">
<a href="#" class="<?php echo $tab1;?>" onClick="runFilieresArt('<?php echo $typePeche;?>','<?php echo $typeAction;?>','1','<?php echo $codeTableEnCours;?>','n')">Crit&egrave;res g&eacute;n&eacute;raux</a>|
<a href="#" class="<?php echo $tab2;?>" onClick="runFilieresArt('<?php echo $typePeche;?>','<?php echo $typeAction; ?>','2','<?php echo $codeTableEnCours;?>','n')">Cat&eacute;gories &eacute;cologiques</a>|
<a href="#" class="<?php echo $tab3;?>" onClick="runFilieresArt('<?php echo $typePeche;?>','<?php echo $typeAction;?>','3','<?php echo $codeTableEnCours;?>','n')">Cat&eacute;gories trophiques</a>|
<a href="#" class="<?php echo $tab4;?>" onClick="runFilieresArt('<?php echo $typePeche;?>','<?php echo $typeAction;?>','4','<?php echo $codeTableEnCours;?>','n')">Colonnes</a>
</div>
<?php // Les differents div correspondant aux choix disponibles par onglet ?>
<div id="criteresgen" class="criteresgen<?php echo $cgActive;?>">
	<span class="sscriteresgen<?php echo $ClassEnv;?>">Choisir les poissons / non poissons :<br/></span>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="poisson1" type="radio" name="poissons" value="0"  <?php echo $valPois1;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">Inclure les poissons</span>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="poisson2" type="radio" name="poissons" value="pp"  <?php echo $valPois2;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">Ne pas inclure les poissons<br/></span>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="poisson3" type="radio" name="poissons1" value="1"  <?php echo $valPois3;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">Inclure les non poissons</span>
	<input class="sscriteresgen<?php echo $ClassEnv;?>" id="poisson4" type="radio" name="poissons1" value="np"  <?php echo $valPois4;?>/> <span class="sscriteresgen<?php echo $ClassEnv;?>">Ne pas inclure les non poissons</span>
</div>
<?php // l'onglet qui gere la selection des categories ecologiques ?>
<div id="cateco" class="cateco<?php echo $ceActive;?>">
<?php echo AfficheCategories("Ecologiques",$typeAction,$_SESSION['listeCatEco'],$changtAction); ?>
</div>
<?php // l'onglet qui gere la selection des categories trophiques ?>
<div id="cattroph" class="cattroph<?php echo $ctActive;?>">
<?php echo AfficheCategories("Trophiques",$typeAction,$_SESSION['listeCatTrop'],$changtAction); ?>
</div>
<?php // l'onglet qui gere la selection des colonnes complémentaires ?>
<div id="colonnes" class="colonnes<?php echo $colActive;?>">
<?php echo AfficheColonnes($typePeche,$typeAction,$codeTableEnCours,$numTab); ?>
</div>
<?php // Permet d'obtenir le résultat ?>
<div id="voiresultat"><input type="button" id="validation" onClick="runFilieresArt('<?php echo $typePeche;?>','<?php echo $typeAction;?>','1','<?php echo $codeTableEnCours;?>','y')" value="Voir les r&eacute;sultats"/>
<input type="checkbox" id="ExpFic" />Exporter sous forme de fichier
</div>

</form>
