<?php

//***************************************************************************************************
//construit un formulaire pour choisir une table de référence d'une hiérarchie à éditer
function buildTableSelect($hierarchyLabel,$selected)
// cette fonction construit une liste d'OPTIONS pour un SELECT
// $hierarchyLabel : le nom de la hiérarchie à construire (liste de tables de référence)
// $selected : si renseigné, permet d'indiquer quelle OPTIOn devrait être présélectionnée
// les valeurs de ces deux variables proviennent du tableau $hierarchySelectors défini dans edition_functions.php
{
	global $tableSelectors;
	

//on commence le formulaire
	echo('<form id="form_'.$hierarchyLabel.'" name="form_'.$hierarchyLabel.'" action="/edition/edition_selector.php" method="get">');
		echo('<input name="type" id="type" type="hidden" value="reference" />');
		echo('<input name="hierarchy" id="hierarchy" type="hidden" value="'.$hierarchyLabel.'" />');
	// on commence le SELECT
	echo('<select name="table" id="select_'.$hierarchyLabel.'" onchange="javascript:form_'.$hierarchyLabel.'.submit();" size="1">');
	// la première OPTION ne sert à rien...
	echo('<option value="choose">- choisir une table -</option>');
	
	// on récupère la hiérarchie à afficher
	$theHierarchy=$tableSelectors[$hierarchyLabel];
	// on construit la liste d'OPTIONs avec comme value le pointeur de la table à éditer et comme texte son label
	foreach ($theHierarchy as $key=>$value) {
		echo('<option value="'.$key.'">'.$value["label"].'</option>');
	}
	
	// on termine le SELECT
	echo('</select>');
	// on termine le form
	echo('</form>');
	
}




?>