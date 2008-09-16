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
	global $tablesDefinitions;
	

	// on récupère la hiérarchie à afficher
	$theHierarchy=$tableSelectors[$hierarchyLabel];
	//debug	print_r(	$theHierarchy);

//on commence le formulaire
	echo('<form id="form_'.$hierarchyLabel.'" name="form_'.$hierarchyLabel.'" action="/edition/edition_selector.php" method="get">');
		echo('<input name="type" id="type" type="hidden" value="reference" />');
		echo('<input name="hierarchy" id="hierarchy" type="hidden" value="'.$hierarchyLabel.'" />');
	// on commence le SELECT
	echo('<select name="targetTable" id="select_'.$hierarchyLabel.'" onchange="javascript:form_'.$hierarchyLabel.'.submit();" size="1">');
	// la première OPTION ne sert à rien...
	echo('<option value="choose">- choisir une table -</option>');
	
	// on construit la liste d'OPTIONs avec comme value le pointeur de la table à éditer et comme texte son label
	foreach ($theHierarchy as $theTable) {
		echo('<option value="'.$theTable.'">'.$tablesDefinitions[$theTable]["label"].'</option>');
	}
	
	// on termine le SELECT
	echo('</select>');
	// on termine le form
	echo('</form>');
	
}


//******************************************************************************
// takes a URL and builds a selector according to the URL parameters
// used to rebuild the selector after a selection has been submitted, or when coming back to the selector page
function createSelector($page) {
	// $page: la page sur laquelle le sélecteur est affiché
	// (par exemple, sur la page "editor.php" on affiche le lien pour afficher/masquer le sélecteur)

	global $tableSelectors;
	global $tablesDefinitions;
	global $selectorCascades;
	
	
	$thisHierarchy=$_GET["hierarchy"];
	$targetTable=$_GET["targetTable"];
	$thisLevel=$_GET["level"];
	$selectedParentValues=$_GET[$parentTable];
	$tablesList=explode(",",$selectorCascades[$targetTable]);
	$whereClause=NULL;



// le titre
echo('<h1 class="selector"><a href="/edition.php">&eacute;dition des donn&eacute;es</a>');
switch ($theType) {
	case "reference" : $theTypeString=" de r&eacute;f&eacute;rence"; $theSelectorType="tableSelectors";
	break;
	case "codage" : $theTypeString=" de codage"; $theSelectorType="tableSelectors";
	break;
	default: $theTypeString="";
	break;
		}
echo(' : table '.$theTypeString.' "'.$tablesDefinitions[$targetTable]["label"].'" <span class="showHide"><a href="" id="showHideSelect"></a></span></h1>');

	
// le sélecteur	
echo('<div id="selector_content">');
	// on regarde si la table choisie nécessite une cascade
	echo('<form id="selector_form">');
	//debug	print_r($selectorCascades);
	
	if (array_key_exists($targetTable,$selectorCascades)) 	{
		// si oui, on récupere la liste des tables de la cascade passées dans l'URL
			//debug		echo($targetTable." : cascade : ".$selectorCascades[$targetTable].'<br />');
			// on crée le tableau avec la liste des tables de la cascade
			$theTables=split(",",$selectorCascades[$targetTable]);
			}
		else {
			// sinon, on utilise directement la table
			//debug echo($targetTable." : pas de cascade");
			// on crée le tableau avec seulement la table
			$theTables=array($targetTable);
			;}
		//debug		print_r($theTables);
	// end if (array_key_exists)
	// on boucle dans le tableau $theTables pour insérer le(s) SELECT
	// on initialise le niveau du premier SELECT (utilisé pour construire les ID des DIV)
	$level=1;
	foreach ($theTables as $oneTable) {
		$selectedValues=array();
		$selectedValues=$_GET[$oneTable];
		$selectedParentValues=array();
		if (isset($_GET[$parentTable])) {$selectedParentValues=$_GET[$parentTable];}
		
		
		// on construit la clause SQL permettant de filtrer les valeurs
		// en fonction de celles sélectionnées dans les SELECT précédents
		if ($level>1 && !empty($selectedParentValues)) {
			// on récupère la liste des valeurs sélectionnées de la table du niveau précédent
		
			$theList='\'';
			$theList.=implode($_GET[$parentTable],"','");
			$theList.='\'';
			
			$whereClause=' AND '.$tablesDefinitions[$parentTable]["table"].'_id IN ('.$theList.') ';
			
			} else {$whereClause=NULL;}
		// le DIV contenant le SELECT
		echo('<div id="level_'.$level.'" class="level_div">');
		
		// on construit le SELECT
		createTableSelect($oneTable,$selectedValues,$level,$whereClause);
		echo('</div>');
		$level++;
		$parentTable=$oneTable;
	}
	echo('</form>');

	// le div pour l'affichage de l'aide
	echo('<div id="select_hints" class="hints"><span class="hint_label">aide : </span><span class="hint_text">vous pouvez s&eacute;lectionner plusieurs valeurs en cliquant tout en tenant la touche &quot;CTRL&quot; (Windows, Linux) ou &quot;CMD&quot; (Mac) enfonc&eacute;e.</span></div>');



echo('</div>'); // end div id=selector_content


} // end function

//******************************************************************************
// insère un SELECT listant les valeurs d'une table
function createTableSelect($theTable,$selectedValues,$level,$whereClause) {
// $theTable : la table à utiliser (correspondance avec les tables de la base définie dans edition_config.inc)
// $selectedValues : les valeurs à sélectionner dans le SELECT
// $level : le "niveau" du SELECT (pour les cascades)
// $whereClause : la clause SQL additionnelle pour filtrer les OPTION du SELECT en fonction des sélections précédentes
	
	global $tablesDefinitions;
	global $connectPPEAO; // la connexion a utiliser (on travaille avec deux bases : BD_PECHE et BD_PPEAO)
	
	//debug	print_r($selectedValues);
	// le nom de la table
	echo('<p>'.$tablesDefinitions[$theTable]["label"].'</p>');
	// le SELECT avec les valeurs de la table
	//le SELECT accepte-t-il les sélections multiples
	//debug 
	$isMultiple='multiple="multiple"';
	
	

		// si la table parent a au moins une valeur sélectionnée (whereClause non vide) ou qu'on est au premier niveau, on affiche le sélect
		if (!empty($whereClause) || $level==1) {
		
		
			// on détermine si il existe des valeurs du nouveau SELECT correspondant aux sélections précédentes
			// on construit la requête SQL
			$columnsToSelect=$tablesDefinitions[$theTable]["id_col"].' as value , '.$tablesDefinitions[$theTable]["noms_col"].' as text';
			$valuesSql='	SELECT DISTINCT '.$columnsToSelect.' FROM '.$tablesDefinitions[$theTable]["table"].'
							WHERE TRUE '.$whereClause.' 
							ORDER BY '.$tablesDefinitions[$theTable]["noms_col"].'
						';

			//debug 				echo($valuesSql);

			$valuesResult=pg_query($connectPPEAO,$valuesSql) or die('erreur dans la requete : '.$valuesSql. pg_last_error());
			$valuesTable=pg_fetch_all($valuesResult);

			//debug			print_r($valuesTable);

			if (!empty($valuesTable)) {
			echo('<div id="select_'.$level.'" name="select_'.$level.'">');
			echo('<select id="'.$theTable.'" name="'.$theTable.'[]" size="10" '.$isMultiple.' onchange="javascript:showNewLevel(\''.($level+1).'\',\''.$theTable.'\');" class="level_select">');
			// on cronstruit la liste des OPTION
			foreach ($valuesTable as $value) {
				// on détermine les OPTION à sélectionner
				if (@in_array($value["value"], $selectedValues)) {$selected='selected="selected"';} else {$selected='';}
				// on affiche l'OPTION
				echo('<option value="'.$value["value"].'" '.$selected.'>'.$value["text"].'</option>');
			}
			echo('</select>');
			
			// les boutons permettant de sélectionner/désélectionner toutes les valeurs du SELECT
			echo('<p id="selectlink__'.$level.'" class="select_link">s&eacute;lectionner ');
				echo('<a href="#" onclick="javascript:toggleSelect(\''.$level.'\',\''.$theTable.'\',\'all\');" class="link_button">tout</a> ');echo(' <a href="#" onclick="javascript:toggleSelect(\''.$level.'\',\''.$theTable.'\',\'none\');"  class="link_button">rien</a>');
			echo('</p>');
			
			// le lien permettant d'éditer la table ou les valeurs sélectionnées
			echo('<p id="editlink_'.$level.'" class="edit_link">');
			
			// on prépare l'URL du lien
			$theUrl=replaceQueryParam ($_SERVER["QUERY_STRING"],'editTable',$theTable);
			echo('<a id="edita_'.$level.'" class="link_button" href="edition_table.php?'.$theUrl.'">');
				// si aucune valeur du SELECT n'est sélectionnée, on met un lien "éditer la table"
				if (empty($selectedValues)) {
					echo('&eacute;diter la table');
					}
				// si une ou plusieurs valeurs sont sélectionnées, on met un lien "éditer la sélection" et on adapte l'URL
				else {
					echo('&eacute;diter la s&eacute;lection');
				}
			echo('</a>');
			
			echo('</p>');
			echo('</div>');
			}
	
			} // end if (!empty($valuesTable))
			else {
			echo('<div id="select_'.$level.'" name="select_'.$level.'"></div>');	
			}
	
	
	
	
}

?>