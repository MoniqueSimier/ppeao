<?php

//***************************************************************************************************
//construit un formulaire pour choisir une table de r�f�rence d'une hi�rarchie � �diter
function buildTableSelect($hierarchyLabel,$selected)
// cette fonction construit une liste d'OPTIONS pour un SELECT
// $hierarchyLabel : le nom de la hi�rarchie � construire (liste de tables de r�f�rence)
// $selected : si renseign�, permet d'indiquer quelle OPTIOn devrait �tre pr�s�lectionn�e
// les valeurs de ces deux variables proviennent du tableau $hierarchySelectors d�fini dans edition_functions.php
{
	global $tableSelectors;
	

//on commence le formulaire
	echo('<form id="form_'.$hierarchyLabel.'" name="form_'.$hierarchyLabel.'" action="/edition/edition_selector.php" method="get">');
		echo('<input name="type" id="type" type="hidden" value="reference" />');
		echo('<input name="hierarchy" id="hierarchy" type="hidden" value="'.$hierarchyLabel.'" />');
	// on commence le SELECT
	echo('<select name="table" id="select_'.$hierarchyLabel.'" onchange="javascript:form_'.$hierarchyLabel.'.submit();" size="1">');
	// la premi�re OPTION ne sert � rien...
	echo('<option value="choose">- choisir une table -</option>');
	
	// on r�cup�re la hi�rarchie � afficher
	$theHierarchy=$tableSelectors[$hierarchyLabel];
	// on construit la liste d'OPTIONs avec comme value le pointeur de la table � �diter et comme texte son label
	foreach ($theHierarchy as $key=>$value) {
		echo('<option value="'.$key.'">'.$value["label"].'</option>');
	}
	
	// on termine le SELECT
	echo('</select>');
	// on termine le form
	echo('</form>');
	
}




?>