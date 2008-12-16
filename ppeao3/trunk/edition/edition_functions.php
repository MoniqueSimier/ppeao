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


//***************************************************************************************************
//construit une liste de lien permettant d'éditer les valeurs des tables d'un type donné
function buildTableList($typeTableNom)
// $typeTable : le type de table (admin_dictionary_type_tables)
{
	global $tablesDefinitions;
	
	$tableList='';
	$previousDomain='';
	foreach ($tablesDefinitions as $handle=>$table) {
		if ($table["type_table_nom"]==$typeTableNom) {
		//debug
		if ($table["domaine_nom"]!=$previousDomain) {
			$domain='</ul><h2>'.$table["domaine_description"].'</h2>';
			$domain.='<ul>';
			$previousDomain=$table["domaine_nom"];
			} 
		else {
			$domain='';}
		
		if ($table["selector"] && !empty($table["selector_cascade"])) {
			$href='/edition/edition_selector.php?selector=yes&targetTable='.$handle;
		} // end if $table["selector"]
		else {
			$href='/edition/edition_table.php?selector=no&editTable='.$handle;
		} // end else $table["selector"]
		
		$list.=$domain.'<li><a href="'.$href.'">'.$table["label"].'</a></li>';
		
		} // end if  $table["type_table_nom"]==$typeTableNom
	} // end foreach
	if (!empty($list)) {
		echo($list);
	}
	

	
}


//******************************************************************************
// takes a URL and builds a selector according to the URL parameters
// used to rebuild the selector after a selection has been submitted, or when coming back to the selector page
function createSelector($page) {
	// $page: la page sur laquelle le sélecteur est affiché
	// (par exemple, sur la page "edition_table.php" on peut afficher le lien pour afficher/masquer le sélecteur)

	global $tablesDefinitions;	
		
	// la table sélectionnée dans la liste de la page précédente
	$targetTable=$_GET["targetTable"];
	// la table réellement éditée
	$editTable=$_GET["editTable"];
	$thisTable=$tablesDefinitions[$targetTable];
	$thisLevel=$_GET["level"];
	$selectedParentValues=$_GET[$parentTable];
	$whereClause=NULL;
	
	



// le titre
echo('<h1 class="selector">g&eacute;rer les '.$thisTable["type_table_description"].'&nbsp;: '.$thisTable["domaine_description"].' <span class="showHide"><a href="" id="showHideSelect"></a></span></h1>');

	
// le sélecteur	
echo('<div id="selector_content">');
	// on regarde si la table choisie nécessite une cascade
	echo('<form id="selector_form">');
	//debug	print_r($selectorCascades);
	
	if ($thisTable["selector"]) 	{
		// si oui, on récupere la liste des tables de la cascade passées dans l'URL
			//debug		echo($targetTable." : cascade : ".$selectorCascades[$targetTable].'<br />');
			// on crée le tableau avec la liste des tables de la cascade
			$theTables=split(",",$thisTable["selector_cascade"]);
		
			}
		else {
			// sinon, on utilise directement la table
			//debug echo($targetTable." : pas de cascade");
			// on crée le tableau avec seulement la table
			$theTables=array($editTable);
			;}
		//debug		print_r($theTables);
	// end if (array_key_exists)
	// on boucle dans le tableau $theTables pour insérer le(s) SELECT
	// on initialise le niveau du premier SELECT (utilisé pour construire les ID des DIV)
	$level=1;
	foreach ($theTables as $oneTable) {
		$selectedValues=array();
		$selectedValues=$_GET[$oneTable];
		//debug 		echo('<pre>');print_r($selectedValues);echo('</pre>');
		
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
			
			//debug			echo($whereClause);
			
			} else {$whereClause=NULL;}
		// le DIV contenant le SELECT
		echo('<div id="level_'.$level.'" class="level_div">');
		
		// on construit le SELECT
		echo(createTableSelect($oneTable,$selectedValues,$level,$whereClause));
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
	$theSelect='<p>'.htmlentities($tablesDefinitions[$theTable]["label"]).'</p>';
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
			$theSelect.='<div id="select_'.$level.'" name="select_'.$level.'">';
			$theSelect.='<select id="'.$theTable.'" name="'.$theTable.'[]" size="10" '.$isMultiple.' onchange="javascript:showNewLevel(\''.($level+1).'\',\''.$theTable.'\');" class="level_select">';
			// on cronstruit la liste des OPTION
			foreach ($valuesTable as $value) {
				// on détermine les OPTION à sélectionner
				if (@in_array($value["value"], $selectedValues)) {$selected='selected="selected"';} else {$selected='';}
				// on affiche l'OPTION
				$theSelect.='<option value="'.$value["value"].'" '.$selected.'>'.$value["text"].'</option>';
			}
			$theSelect.='</select>';
			
			// les boutons permettant de sélectionner/désélectionner toutes les valeurs du SELECT
			// desactives car source de confusion
			/*echo('<p id="selectlink__'.$level.'" class="select_link">s&eacute;lectionner ');
				echo('<a href="#" onclick="javascript:toggleSelect(\''.$level.'\',\''.$theTable.'\',\'all\');" class="link_button">tout</a> ');echo(' <a href="#" onclick="javascript:toggleSelect(\''.$level.'\',\''.$theTable.'\',\'none\');"  class="link_button">rien</a>');
			echo('</p>');*/
			
			// le lien permettant d'éditer la table ou les valeurs sélectionnées
			$theSelect.='<p id="editlink_'.$level.'" class="edit_link">';
			
			// on prépare l'URL du lien
			$theUrl=replaceQueryParam ($_SERVER["QUERY_STRING"],'editTable',$theTable);
			$theSelect.='<a id="edita_'.$level.'" class="link_button" href="edition_table.php?'.$theUrl.'">';
				// si aucune valeur du SELECT n'est sélectionnée, on met un lien "éditer la table"
				if (empty($selectedValues)) {
					$theSelect.='&eacute;diter la table';
					}
				// si une ou plusieurs valeurs sont sélectionnées, on met un lien "éditer la sélection" et on adapte l'URL
				else {
					$theSelect.='&eacute;diter la s&eacute;lection';
				}
			$theSelect.='</a>';
			$theSelect.='</p>';
			// lien pour ajouter un enregistrement
			$theSelect.='<p id="addlink_'.$level.'" class="edit_link">';
			$theSelect.='<a id="ajouter_'.$level.'" class="link_button" href="#" onclick="modalDialogAddRecord(1,\''.$theTable.'\')">';
			$theSelect.='ajouter un enregistrement';
			$theSelect.='</a>';
			$theSelect.='</p>';
			$theSelect.='</div>';
			}
	
			} // end if (!empty($valuesTable))
			else {
			$theSelect.='<div id="select_'.$level.'" name="select_'.$level.'"></div>';	
			}
			
			return $theSelect;
	
}


//******************************************************************************
// retourne l'alias dans la variable de config $tablesDefinitions de la table $tableName (nom dans la base)
function getTableAliasFromName($tableName) {
	global $tablesDefinitions;
	$tableAlias='';
	foreach ($tablesDefinitions as $key=>$value) {
		if ($value["table"]==$tableName) {$tableAlias=$key;}
	}
	return $tableAlias;
	
}

//******************************************************************************
// retourne le nom dans la base d'une table connue par son alias dans la variable de config $tablesDefinitions
function getTableNameFromAlias($tableAlias) {
	global $tablesDefinitions;
	$tableName='';
	foreach ($tablesDefinitions as $key=>$value) {
		if ($key==$tableAlias) {$tableName=$value["table"];}
	}
	return $tableName;
	
}


//******************************************************************************
// affiche un champ de formulaire permettant d'éditer un champ d'une table
function makeField($cDetails,$table,$column,$value,$action,$theUrl) {
// $cDetails : tableau retourné par la fonction getTableColumnsDetails()
// table : la table concernée (identifiant de la table dans la variable $tablesDefinitions de edition_config.inc)
// $column : la colonne concernée
// $value : la valeur du champ de la colonne concernée
// $action : 'display=xxx'/'edit=xxx' pour créer un champ affichable/éditable de l'enregistrement xxx, 'filter' pour un champ de filtre, 'add' pour l'ajout d'un nouvel enregistrement
// $theUrl : l'URL à utiliser pour les champs de tri de type SELECT ()

// la connection à la base
global $connectPPEAO;
global $tablesDefinitions;

// nombre maximal de valeurs des clés étrangères à afficher 
global $maxForeignKeyMenuLength;

// la longueur (et longueur max) par défaut des champs INPUT de type TEXT
$defaultTextInputLength=15;
$defaultTextInputMaxLength=30;
// nombre de rows par défaut des <textarea>
$defaultTextRows=3;



if (substringBefore($action,'=')=='edit') {$editRow=substringAfter($action,'=');$action='edit';}
if (substringBefore($action,'=')=='display') {$editRow=substringAfter($action,'=');$action='display';}


// valeur à utiliser comme ID, NAME et CLASS des champs de formulaire, selon que l'on édite ou filtre
switch ($action) {
	case 'filter': $theClass='filter_field';
		$theId='f_'.$column;
	break;
	case 'edit': $theClass='edit_field';
		$theId='e_'.$column.'_'.$editRow;
	break;
	case 'display': $theClass='editable_field';
		$theId='d_'.$column.'_'.$editRow;
	break;
	case 'add': $theClass='add_field';
		$theId='add_record_'.$_GET["level"].'_'.$column;
	break;
}// end switch $action

// variable dans laquelle on stocke ce qui doit être affiché
$theField='';

$theDetails=$cDetails[$column];

// avant de démarrer, on "bricole" les infos sur la colonne pour traiter certains cas particuliers
// cas d'une colonne stockant un mot de passe
if ($theDetails["column_name"]=="user_password") {
	$theDetails["data_type"]="password";
}

// on teste si la colonne concernée a une contrainte de type clé primaire, clé étrangère ou énumération (ou plusieurs...)
$keyConstraint=FALSE;
if (isset($theDetails["constraints"]) && !empty($theDetails["constraints"])) {
	$constraintsToCheck=array('PRIMARY KEY','ENUM','FOREIGN KEY');
	// on teste le type de contraintes
	foreach($theDetails["constraints"] as $oneConstraint) {
		if (in_array($oneConstraint["constraint_type"],$constraintsToCheck)){
			$theConstraints[$oneConstraint["constraint_type"]]=$oneConstraint;
			$keyConstraint=TRUE;
		}
	} // end foreach		
	// si l'on a plusieurs contraintes de type primary, foreign ou enum (cas d'une colonne primary ET foreign)
	if (isset($theConstraints) && !empty($theConstraints)) {
		// on prioritise la clé étrangère, pour le cas des tables de jointure
		if (isset($theConstraints["FOREIGN KEY"])) {$theConstraint=$theConstraints["FOREIGN KEY"]; $constraint=$theConstraint["constraint_type"];} else {if (isset($theConstraints["PRIMARY KEY"])) {$theConstraint=$theConstraints["PRIMARY KEY"];$constraint=$theConstraint["constraint_type"];} else {$theConstraint=current($theConstraints);$constraint=$theConstraint["constraint_type"];}}
	}
	
	
} // end if (isset($theDetails["constraints"]) 
	
	if ($keyConstraint) {
		
		
		switch ($constraint) {
		
			// cas d'une clé primaire
			case 'PRIMARY KEY' : 
				// les clés primaires ne sont pas éditables mais filtrables
				switch ($action) {
					
					case 'filter':
						if (!empty($theDetails["character_maximum_length"])) {
							$maxLength=$theDetails["character_maximum_length"];}
						else {
							$length=$defaultTextInputLength;
							$maxLength=$defaultTextInputLength;
							}
						$theField='<div class="filter"><input type="text"  title="saisissez une valeur puis appuyez sur la touche ENTR&Eacute;E" id="'.$theId.'" id="'.$theId.'" name="'.$theId.'" value="'.$value.'" class="'.$theClass.'" size="'.$length.'" maxlength="'.$maxLength.'" onchange="javascript:filterTable(\''.$theUrl.'\')"></input></div>';
					break;

					case 'display':
						 // on modifie la classe du champ non éditable
						$theClass='non_editable_field';
						$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="les cl&eacute;s primaires ne sont pas &eacute;ditables">'.$value.'</div>';
					break;
						
					// si on ajoute un nouvel enregistrement
					case 'add' :
					// si il existe une séquence sur la clé, on génère automatiquement la valeur et le champ n'est pas éditable
					$ifSequence=getTableColumnSequence($connectPPEAO,$tablesDefinitions[$table]["table"],$column);
					if ($ifSequence) {
						$theClass='non_editable_field';
						$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="valeur déterminée par une s&eacute;quence automatique">(auto)</div>';
					}
					else {
					// sinon, on insère un champ input
					
					if ($theDetails["character_maximum_length"]>=$defaultTextInputMaxLength) {
						$theMaxLength=$theDetails["character_maximum_length"];
						$theSize=$defaultTextInputMaxLength;
					}
					else {
						if (!empty($theDetails["character_maximum_length"])) {
							$theSize=$theDetails["character_maximum_length"];
							$theMaxLength=$theDetails["character_maximum_length"];
							}
						else {
							$theSize=$defaultTextInputMaxLength;
							$theMaxSizeLength=255;
							}
					}
					
					$theField='<input id="'.$theId.'" name="'.$theId.'" size="'.$theSize.'" maxlength="'.$theMaxLength.'" class="'.$theClass.'" value="" />';}
					break;

				} // end switch $action
				;
			break; // end case 'PRIMARY KEY'
			
			// cas d'une énumération : on construit un <SELECT> avec les valeurs de l'énumération
			case 'ENUM':
				$theOptions=explode(",", $theConstraint["check_clause"]);
				
				switch ($action) {
						case 'filter':
						$theField='<div class="filter"<select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" onchange="javascript:filterTable(\''.$theUrl.'\');">';
							// on ajoute une valeur "vide"
								$theField.='<option value="" '.$selected.'>-</option>';
							foreach($theOptions as $theOption) {
								// on selectionne eventuellement l'option correspondant à la valeur courante du champ
								if ($theOption==$value) {$selected='selected="selected"';} else {$selected='';}
								$theField.='<option value='.$theOption.' '.$selected.'>'.$theOption.'</option>';
								}
						$theField.='</select></div>';
					break;
					case 'display' : $theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" onclick="javascript:makeEditable(\''.$table.'\',\''.$column.'\',\''.$editRow.'\',\'edit\');">'.$value.'</div>';
					break;
					case 'add':
					case 'edit': $theField='<select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'">';
						// on ajoute une valeur "vide"
							$theField.='<option value="" '.$selected.'>-</option>';
						foreach($theOptions as $theOption) {
							// on selectionne eventuellement l'option correspondant à la valeur courante du champ
							if ($theOption==$value) {$selected='selected="selected"';} else {$selected='';}
							$theField.='<option value='.$theOption.' '.$selected.'>'.$theOption.'</option>';
							}
					$theField.='</select></div>';
					break;
					
				}// end switch $action
				;
			break;
			
			// cas d'une clé étrangère : on construit un <SELECT> avec les valeurs de la table/colonne référencée
			case 'FOREIGN KEY':
	
				
				switch ($action) {
					
					case 'display':	
					// dans le cas où une valeur de la clé étrangère est définie
					if (!empty($value)) {
					// la table référencée par la contrainte
					$theFtable=$theConstraint["references_table"];
					// l'alias de la table a partir de son nom dans la base
					$theFtableAlias=getTableAliasFromName($theFtable);
										
					// on teste si on doit afficher la valeur de la clé étrangère en utilisant une cascade ou pas
					if ($tablesDefinitions[$theFtableAlias]["cascade_foreign_key"]=='t' && !empty($tablesDefinitions[$theFtableAlias]["selector_cascade"])) {

					// oui, alors on construit la valeur à afficher en utilisant les éléments de la cascade
					// par exemple "pays/systeme/secteur" pour une valeur du secteur
					// on commence par la fin, i.e. la cle elle-meme, puis on remonte a travers ses parents
					$cascade=array_reverse(explode(',',$tablesDefinitions[$theFtableAlias]["selector_cascade"]));
					// on boucle à travers la cascade en commençant par la fin
					$i=0;
					foreach($cascade as $fkey) {
					if ($i==0) {
					$theFKeys=$tablesDefinitions[$theFtableAlias]["id_col"];
					$theFValues=$tablesDefinitions[$theFtableAlias]["noms_col"];

					$sqlFValue='SELECT '.$theFValues.'
								FROM '.$theFtable.'
								WHERE '.$theFKeys.'=\''.$value.'\' 
								ORDER BY '.$theFValues;
																
					$resultFvalue=pg_query($connectPPEAO,$sqlFValue) or die('erreur dans la requete : '.$sqlFValue. pg_last_error());
					$fValue=pg_fetch_all($resultFvalue);
					pg_free_result($resultFvalue);
					
					// la valeur à afficher
					$theDisplayValue=$fValue[0][$theFValues];
					// on met à jour la valeur de la table et de la clé filles 
					$childTable=$tablesDefinitions[$fkey]["table"];
					$childValue=$value;
					$childPrimaryKey=$theFKeys;
					}
					
					else {
						$thisTable=$tablesDefinitions[$fkey]["table"];
						$thisPrimaryKey=$tablesDefinitions[$fkey]["id_col"];
						$thisPrimaryValue=$tablesDefinitions[$fkey]["noms_col"];
						
						// on determine le nom de la colonne referencant la colonne dans la table fille
						$cd=getTableConstraintDetails($connectPPEAO,$childTable);
						foreach($cd as $c) {
							if ($c["references_table"]==$thisTable && 
							$c["references_field"]==$thisPrimaryKey) {
								$childForeignKey=$c["column_name"];
								} // end if
							} // end foreach $cd
						
						
						$sql="SELECT $thisTable.$thisPrimaryKey, $thisTable.$thisPrimaryValue FROM $thisTable, $childTable WHERE $childTable.$childForeignKey=$thisTable.$thisPrimaryKey AND $childTable.$childPrimaryKey=$childValue";
						//debug		echo($sql);
						$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
						$resultArray=pg_fetch_all($result);
						pg_free_result($result);									
						
						$thisValue=$resultArray[0][$thisPrimaryKey];
						
						$theDisplayValue='<span class="grey">'.$resultArray[0][$thisPrimaryValue].'/</span>'.$theDisplayValue;
																
					// on met à jour la valeur de la table et de la clé filles
					$childTable=$thisTable;
					$childValue=$thisValue;
					$childForeignKey=$thisPrimaryKey;	
					}

					$i++;
					}
					
						
					} // fin de si on utilise une cascade pour l'affichage
					
					else {
					// on n'utilise pas la cascade
					// on recupere la valeurs de la clé etrangere

					$theFKeys=$tablesDefinitions[$theFtableAlias]["id_col"];
					$theFValues=$tablesDefinitions[$theFtableAlias]["noms_col"];

					$sqlFValue='SELECT '.$theFValues.'
								FROM '.$theFtable.'
								WHERE '.$theFKeys.'=\''.$value.'\' 
								ORDER BY '.$theFValues;
																
					$resultFvalue=pg_query($connectPPEAO,$sqlFValue) or die('erreur dans la requete : '.$sqlFValue. pg_last_error());
					$fValue=pg_fetch_all($resultFvalue);
					pg_free_result($resultFvalue);
					
					// la valeur à afficher
					$theDisplayValue=$fValue[0][$theFValues];
					} // end si on n'utilise pas la cascade
					
					
					
					} // end if !empty($value)
					
					// dans le cas où on n'a pas de valeur pour la clé étrangère
					else {
						$theDisplayValue='';
					}
					$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="cliquer pour &eacute;diter cette valeur" onclick="javascript:makeEditable(\''.$table.'\',\''.$column.'\',\''.$editRow.'\',\'edit\');">'.$theDisplayValue.'</div>';
					break;

					case 'filter':
						// on recupere les valeurs de la clé etrangere -> on utilise la table indiquée dans $cDetails
						$theFtable=$theConstraint["references_table"];
						$theFtableAlias=getTableAliasFromName($theFtable);
						$theFKeys=$tablesDefinitions[$theFtableAlias]["id_col"];
						$theFValues=$tablesDefinitions[$theFtableAlias]["noms_col"];

						$sqlFkey='SELECT '.$theFKeys.', '.$theFValues.'
									FROM '.$theFtable.'
									WHERE TRUE
									ORDER BY '.$theFValues;
						$resultFkey=pg_query($connectPPEAO,$sqlFkey) or die('erreur dans la requete : '.$sqlFkey. pg_last_error());
						$fKeys=pg_fetch_all($resultFkey);
						pg_free_result($resultFkey);
						
						// on compte le nombre de valeurs
						$valueNumber=count($fKeys);

						if ($action=='filter')
							{$onAction='onchange="javascript:filterTable(\''.$theUrl.'\');"';} 
							else {
							$onAction='';}
						
						// si le nombre de valeurs de la clé est trop grand (), on n'affiche pas de champ de filtre
						// note : le plan etait d'afficher un champ <input> comme pour les autres champs,
						// mais cela pose le probleme de filtrer une cle etrangere sur la valeur de son "libelle"
						// et non de son "id"
						if ($valueNumber>$maxForeignKeyMenuLength) {
							$theField='';
						}
						// sinon on affiche un menu <select>
						else {
						$theField='<div class="filter"><select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" '.$onAction.'>';
						// on ajoute une valeur "vide" si on est en édition ou ajout (clé secondaire JAMAIS NULL)
						//if ($action=='filter') {$theField.='<option value="" '.$selected.'>-</option>';}
						$theField.='<option value="NULL" '.$selected.'>-</option>';
						foreach ($fKeys as $fKey) {
							if ($fKey[$theFKeys]==$value) {$selected='selected="selected"';} else {$selected='';}
							// selon que l'on a passé la valeur directement ou depuis la base
							$theValue=$fKey[$theFValues];
							//if (true) {$theEncodedValue=iconv('ISO-8859-15','UTF-8',$fKey[$theFValues]);} else {$theEncodedValue=$fKey[$theFValues];}
							$theField.='<option value='.$fKey[$theFKeys].' '.$selected.'>'.$theValue.'</option>';
						}
						$theField.='</select></div>';
					
					}
					break; // end case  filter
					
					case 'add':
					case 'edit':
						// la table référencée par la contrainte
					$theFtable=$theConstraint["references_table"];
					// l'alias de la table a partir de son nom dans la base
					$theFtableAlias=getTableAliasFromName($theFtable);
										
					// on teste si on doit afficher la valeur de la clé étrangère en utilisant une cascade ou pas
					if ($tablesDefinitions[$theFtableAlias]["cascade_foreign_key"]=='t' && !empty($tablesDefinitions[$theFtableAlias]["selector_cascade"])) {

					// oui, alors on construit la valeur à afficher en utilisant les éléments de la cascade
					// par exemple "pays/systeme/secteur" pour une valeur du secteur
					// on commence par la fin, i.e. la cle elle-meme, puis on remonte a travers ses parents
					$cascade=array_reverse(explode(',',$tablesDefinitions[$theFtableAlias]["selector_cascade"]));
					// on boucle à travers la cascade en commençant par la fin
					$i=0;
					foreach($cascade as $fkey) {
					if ($i==0) {
					$theFKeys=$tablesDefinitions[$theFtableAlias]["id_col"];
					$theFValues=$tablesDefinitions[$theFtableAlias]["noms_col"];

					// si on n'a pas de valeur de la clé ($value), on ne met rien
					if (empty($value)) {$theDisplayValue='' ;} 
					// sinon, on récupère cette valeur
					else {
					$sqlFValue='SELECT '.$theFValues.'
								FROM '.$theFtable.'
								WHERE '.$theFKeys.'=\''.$value.'\' 
								ORDER BY '.$theFValues.'
								LIMIT 1';									
					$resultFvalue=pg_query($connectPPEAO,$sqlFValue) or die('erreur dans la requete : '.$sqlFValue. pg_last_error());
					$fValue=pg_fetch_all($resultFvalue);
					pg_free_result($resultFvalue);
					
					// la valeur à afficher
					$theDisplayValue=$fValue[0][$theFValues];
					}
					// on met à jour la valeur de la table et de la clé filles 
					$childTable=$tablesDefinitions[$fkey]["table"];
					$childValue=$value;
					$childPrimaryKey=$theFKeys;
					$theCascadeValues[$i]=array(
					"thisTable"=>$childTable,
					"thisKeyName"=>$childPrimaryKey,
					"thisKeyValue"=>$childValue,
					"thisLabelName"=>$theFValues,
					"thisLabelValue"=>$fValue[0][$theFValues],
					"childTable"=>'',
					"childForeignKey"=>'');
					}
					
					else {
						$thisTable=$tablesDefinitions[$fkey]["table"];
						$thisPrimaryKey=$tablesDefinitions[$fkey]["id_col"];
						$thisPrimaryValue=$tablesDefinitions[$fkey]["noms_col"];
						
						// on determine le nom de la colonne referençant la colonne dans la table fille
						$cd=getTableConstraintDetails($connectPPEAO,$childTable);
						foreach($cd as $c) {
							if ($c["references_table"]==$thisTable && 
							$c["references_field"]==$thisPrimaryKey) {
								$childForeignKey=$c["column_name"];
								} // end if
							} // end foreach $cd
						
						// si on n'a pas de valeur de la clé ($value), on ne met rien
					if (empty($value)) {$thisValue='' ;} else {
						$sql="SELECT $thisTable.$thisPrimaryKey, $thisTable.$thisPrimaryValue FROM $thisTable, $childTable WHERE $childTable.$childForeignKey=$thisTable.$thisPrimaryKey AND $childTable.$childPrimaryKey=$childValue";
						//debug		echo($sql);
						$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
						$resultArray=pg_fetch_all($result);
						pg_free_result($result);									
						
						$thisValue=$resultArray[0][$thisPrimaryKey];}
																						
					// on met à jour la valeur de la table et de la clé filles
					$theCascadeValues[$i]=array(
						"thisTable"=>$thisTable,
						"thisKeyName"=>$thisPrimaryKey,
						"thisKeyValue"=>$thisValue,
						"thisLabelName"=>$thisPrimaryValue,
						"thisLabelValue"=>$resultArray[0][$thisPrimaryValue],
						"childTable"=>$childTable,
						"childForeignKey"=>$childForeignKey
					);
					$childTable=$thisTable;
					$childValue=$thisValue;
					$childForeignKey=$thisPrimaryKey;					
					}

					$i++;
					} // end foreach $cascade as $fkey
					
					// on a maintenant un tableau $theCascadeValues contenant les différents niveaux de la cascade et leurs valeurs
					// on le renverse pour commencer par le haut de la cascade :
					$theCascadeValues=array_reverse($theCascadeValues);
					
					// le span contenant la cascade
					$theField='<span id="'.$theId.'_foreign_key_cascade">';	
					
					// et on le parcourt pour construire les select en cascade					
					$i=0;
					foreach ($theCascadeValues as $cv) {
						
					//debug 						echo('<pre>');print_r($cv);echo('</pre>');

						if ($i==0) {
							// si on est a la premiere ligne du tableau, pas besoin de filtrer
							// on recupere les valeurs de la cle pour construire le SELECT
							$sql='	SELECT '.$cv["thisKeyName"].' as val,
							 				'.$cv["thisLabelName"].' as lab
									FROM '.$cv["thisTable"].'
									ORDER BY '.$cv["thisLabelName"].'';
							$result=pg_query($connectPPEAO,$sql) or die();
							$resultArray=pg_fetch_all($result);
							pg_free_result($result);									
							
						// on insère le comportement onchange si on n'est pas à la dernière ligne du tableau
						if ($i!=(count($theCascadeValues)-1)) {
							$onchange=' onchange="updateEditSelects(\''.$theId.'\',\''.$i.'\',\''.$cv["thisTable"].'\',\''.$cv["thisKeyName"].'\',\''.$tablesDefinitions[$theFtableAlias]["selector_cascade"].'\');"';
							// les valeurs de ces selects ne doivent pas être sauvées
							$id=' id="'.$theId.'_select_'.$i.'"';
							$name=' name="'.$theId.'_select_'.$i.'"';
						} else // si on est a la fin du tableau
						{
							$onchange='';
							// on insère l'id et le name du select dont on veut sauver la valeur
							$id=' id="'.$theId.'" ';
							$name=' name="'.$theId.'" ';
							
						}	// fin de else if ($i!=(count($theCascadeValues)-1))
							
							$theField.='<select '.$id.' '.$name.'	'. $onchange.' class="'.$theClass.'">';
							
								// on insère la première ligne "vide" si on n'a pas de valeur de la clé ($value)
								if (empty($value)) {
									$theField.='<option value="NULL">- choisir '.$tablesDefinitions[getTableAliasFromName($cv["thisTable"])]["label"].' -</option>';
								}
								// si on n'est pas en mode "ajouter", on insere le select avec ses valeurs 
																foreach($resultArray as $line) {
									if ($line["val"]==$cv["thisKeyValue"]) {$selected='selected="selected"';}  else {$selected='';}
									$theField.='<option value="'.$line["val"].'" '.$selected.'>'.$line["lab"].'</option>';
								}
							$theField.='</select>';
						// si on est a l'avant dernière ligne du tableau, on ferme le span contenant les parents
						if ($i==(count($theCascadeValues)-2)) {
							$theField.='</span>';}
						} // fin de if $i==0
						else {
							
							if ($action!='add') {
							// pour les niveaux suivants, on ajoute le filtrage
							// on recupere les valeurs de la cle pour construire le SELECT
							$sql='	SELECT '.$cv["thisKeyName"].' as val,
							 				'.$cv["thisLabelName"].' as lab
									FROM '.$cv["thisTable"].'
									WHERE '.$theCascadeValues[$i-1]["childForeignKey"].'=\''.$theCascadeValues[$i-1]["thisKeyValue"].'\'
									ORDER BY '.$cv["thisLabelName"].'';
							
							//debug			echo($sql.'<br>');
							
							$result=pg_query($connectPPEAO,$sql) or die();
							$resultArray=pg_fetch_all($result);
							pg_free_result($result);									
							} // end if action !=add 
							// on insère le comportement onchange si on n'est pas à la dernière ligne du tableau
						if ($i!=(count($theCascadeValues)-1)) {
							$onchange=' onchange="updateEditSelects(\''.$theId.'\',\''.$i.'\',\''.$cv["thisTable"].'\',\''.$cv["thisKeyName"].'\',\''.$tablesDefinitions[$theFtableAlias]["selector_cascade"].'\');"';
							// les valeurs de ces selects ne doivent pas être sauvées
							$id='';
						} else {
							$onchange='';
							// on insère l'id du select dont on veut sauver la valeur
							$id=' id="'.$theId.'" ';
						}		
							
							$theField.='<select '.$id.' name="'.$theId.'" '.$onchange.'  class="'.$theClass.'">';
								
								// on insère la première ligne "vide" si on n'a pas de valeur de la clé ($value)
								if (empty($value)) {
									$theField.='<option value="NULL">- choisir '.$tablesDefinition[$cv["thisTable"]]["label"].' -</option>';
								}
								// si on n'est pas en mode "ajouter", on insere le select avec ses valeurs 
								if ($action!='add') {
								
								foreach($resultArray as $line) {
									if ($line["val"]==$cv["thisKeyValue"]) {$selected='selected="selected"';} else {$selected='';}
									$theField.='<option value="'.$line["val"].'"'.$selected.'>'.$line["lab"].'</option>';
								}}
							$theField.='</select>';
							// si on est a l'avant dernière ligne du tableau, on ferme le span contenant les parents
						if ($i==(count($theCascadeValues)-2)) {
							$theField.='</span>';}
						}
					$i++;
					}
					
						
					} // fin de si on utilise une cascade pour l'affichage
					else {
						// on recupere les valeurs de la clé etrangere -> on utilise la table indiquée dans $cDetails
						$theFtable=$theConstraint["references_table"];
						$theFtableAlias=getTableAliasFromName($theFtable);
						$theFKeys=$tablesDefinitions[$theFtableAlias]["id_col"];
						$theFValues=$tablesDefinitions[$theFtableAlias]["noms_col"];

						$sqlFkey='SELECT '.$theFKeys.', '.$theFValues.'
									FROM '.$theFtable.'
									WHERE TRUE
									ORDER BY '.$theFValues;
						$resultFkey=pg_query($connectPPEAO,$sqlFkey) or die('erreur dans la requete : '.$sqlFkey. pg_last_error());
						$fKeys=pg_fetch_all($resultFkey);
						pg_free_result($resultFkey);

						if ($action=='filter')
							{$onAction='onchange="javascript:filterTable(\''.$theUrl.'\');"';} 
							else {
							$onAction='';}
						$theField='<div class="filter"><select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" '.$onAction.'>';
						// on ajoute une valeur "vide" si on est en édition ou ajout (clé secondaire JAMAIS NULL)
						//if ($action=='filter') {$theField.='<option value="" '.$selected.'>-</option>';}
						$theField.='<option value="NULL" '.$selected.'>-</option>';
						foreach ($fKeys as $fKey) {
							if ($fKey[$theFKeys]==$value) {$selected='selected="selected"';} else {$selected='';}
							// selon que l'on a passé la valeur directement ou depuis la base
							$theValue=$fKey[$theFValues];
							//if (true) {$theEncodedValue=iconv('ISO-8859-15','UTF-8',$fKey[$theFValues]);} else {$theEncodedValue=$fKey[$theFValues];}
							$theField.='<option value='.$fKey[$theFKeys].' '.$selected.'>'.$theValue.'</option>';
						}
						$theField.='</select></div>';
					}
					break; // end case add edit

				}
				;
			break;
		
		}// end switch constraint_type
	}
	// si la colonne n'a pas de contrainte
	else {
		// si c'est pour le filtre
		if ($action=='filter') {
			if (!empty($theDetails["character_maximum_length"])) {
				$maxLength=$theDetails["character_maximum_length"];
			}
			else {
				$length=$defaultTextInputLength;
				$maxLength=$defaultTextInputMaxLength;
			}
		} // end if $action==filter
				
		switch ($action) {

			case 'display' : 
								
				// il faut tenir compte de deux cas particuliers : les BOOLEAN et les DATE
				switch ($theDetails["data_type"]) {
				// les booleens
				case 'boolean':
				if (empty($value)) {$value='f';};
				if ($value=='t' || $value=='oui' || $value=="true") {$value='oui';} else {$value='non';};
				$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="cliquez pour &eacute;diter cette valeur" onclick="makeEditable(\''.$table.'\',\''.$column.'\',\''.$editRow.'\',\'edit\');">'.$value.'</div>';
				break;
				
				// cas d'un mot de passe (data_type défini "à la main", n'existe pas sous postgresql)
				case 'password' :
				// si on n'a pas défini de mot de passe, on propose d'en créer un
				if (empty($value)) {$value="";}				
				// sinon, on propose d'en créer un nouveau
				else {$value="changer le mot de passe";};
				// dans tous les cas, on crée un nouveau mot de passe, donc on passe une valeur vide au javascript
				$valueJS="";
				$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="cliquez pour d&eacute;finir un nouveau mot de passe" onclick="makeEditable(\''.$table.'\',\''.$column.'\',\''.$editRow.'\',\'edit\');">'.$value.'</div>';
				break;
				
				// le cas générique : on ne fait rien à la valeur
				default:
				// on encode d'éventuels sauts de ligne pour javascript
				$valueJS=preg_replace("/\r?\n/", "\\n", addslashes($value));
				$valueJS=htmlspecialchars($valueJS);
				//debug 
				if (empty($value)) {$value="";} $theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="cliquez pour &eacute;diter cette valeur" onclick="makeEditable(\''.$table.'\',\''.$column.'\',\''.$editRow.'\',\'edit\');">'.nl2br($value).'</div>';
				
				// end debug
				
				break;
				} // end switch $theDetails["data type"]
			
			break; // end case 'display'

			case 'filter': 	
			// il faut tenir compte de deux cas particuliers : les BOOLEAN et les DATE
				switch ($theDetails["data_type"]) {
					// les booleens
				case 'boolean':
				$theField='<div class="filter"><select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'"  onchange="javascript:filterTable(\''.$theUrl.'\');">';
					$theField.='<option value="" selected="selected">-</option>';
					$theField.='<option value="t">oui</option>';
					$theField.='<option value="f">non</option>';
					$theField.='</select></div>';
				break;
				default:
			$theField='<div class="filter"><input type="text" title="saisissez une valeur puis appuyez sur la touche ENTR&Eacute;E" id="'.$theId.'" name="'.$theId.'" value="'.$value.'" class="'.$theClass.'" size="'.$length.'" maxlength="'.$maxLength.'" onchange="javascript:filterTable(\''.$theUrl.'\');"> </input></div>';
				break;
				}
			break;
			
			case 'add':
			case 'edit' :
				
				// il faut tenir compte de deux cas particuliers : les BOOLEAN et les DATE
				switch ($theDetails["data_type"]) {
				// les booleens
				case 'boolean':
					$theField='<select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'">';
					
					// dans le cas ou aucune valeur n'est spécifiée, on récupère la valeur par défaut
					if (empty($value)) {$value=$theDetails["column_default"];};
					
					if ($value=='oui' || $value=='t' || $value=='true') {$ouiSelected='selected="selected"'; $nonSelected='';} else {$nonSelected='selected="selected"'; $ouiSelected='';}
					
					
					
					$theField.='<option value="t" '.$ouiSelected.'>oui</option>';
					$theField.='<option value="f" '.$nonSelected.'>non</option>';
					$theField.='</select>';
				break;
				
				// les dates
				case 'date':
					$theField='<input title="" type="text" id="'.$theId.'" name="'.$theId.'" value="'.stripSlashes($value).'"  class="'.$theClass.'" size="10" maxlength="10"  '.$onAction.'></input>';
				break;
				
				default:				
					// pour l'édition, on doit prendre en compte la longueur du champ et si il est de type TEXT
					// type text : on affiche une <textarea> sans limite de taille
						if ($theDetails["data_type"]=='text') {$theType='textarea';$theMaxLength='';} 
						// autres types avec un character_maximum_length > valeur par défaut : on affiche une <textarea> avec limite de taille 
						else {
							if ($theDetails["character_maximum_length"]>$defaultTextInputMaxLength) {
							$theType='textarea';$theMaxLength=$theDetails["character_maximum_length"];
							}
						// autres types avec un character_maximum_length <= valeur par défaut : on affiche un <inpu type=text>
							if ($theDetails["character_maximum_length"]<=$defaultTextInputMaxLength) {
						$theType='input';$theMaxLength=$theDetails["character_maximum_length"];
							}
						} // end else $theDetails["data_type"]=='text'

						// on affiche une <textarea>
							if ($theType=='textarea') {

								// si on a une longueur maximale autorisée pour la <textarea>, on ajoute le javascript de controle
									// (il est impossible de limiter le contenu d'une <textarea> en HTML)
										if (!empty($theMaxLength)) {
											$args='$(\''.$theId.'\'),$(\''.$theId.'_counter\'),'.$theMaxLength.'';
											$theLengthLimitation='onKeyDown="fieldTextLimiter('.$args.')" onKeyUp="fieldTextLimiter('.$args.')"  onFocus="fieldTextLimiter('.$args.')" onBlur="fieldTextLimiter('.$args.')"';
											//$textRows=round($theMaxLength/$defaultTextInputMaxLength)+1;
											$textRows=$defaultTextRows;
											}
											else {$theLengthLimitation='';$textRows=$defaultTextRows;}
											$theField='<textarea id="'.$theId.'" name="'.$theId.'" 
					cols="'.$defaultTextInputMaxLength.'" rows="'.$textRows.'" '.$theLengthLimitation.'  '.$onAction.'  class="'.$theClass.'">'.stripSlashes($value).'</textarea><p id="'.$theId.'_counter" class="small"></p>';
							} // end if textarea

							// on affiche un <input>
							if ($theType=='input') {
							$theField='<input title="" type="text" id="'.$theId.'" name="'.$theId.'" value="'.stripSlashes($value).'"  class="'.$theClass.'" size="'.$theMaxLength.'" maxlength="'.$theMaxLength.'"  '.$onAction.'></input>';
							} // end if input
				break; // end default:
				} //end switch 'data_type'
			break;
			
		} // end switch $action
			
		}

return $theField;

}

//******************************************************************************
// permet de vérifier si une valeur est compatible avec un champ de la base de donnée
function checkValidity($cDetails,$table,$column,$value) {
// $cDetails : tableau retourné par la fonction getTableColumnsDetails()
// $column : la colonne concernée
// $value : la valeur dont on veut tester la validité

global $connectPPEAO;

// on stocke les informations sur la colonne concernée
$cDetail=$cDetails[$column];

// on suppose que la valeur est valide
$validityCheck=array("validity"=>1, "errorMessage"=>'',"valeur"=>$value);

// on commence les vérifications
// si la valeur saisie est "null" et que la colonne ne le permet pas
if ((is_null($value) || $value=='') && $cDetail["is_nullable"]!='YES') {
	$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur ne peut pas être vide',"valeur"=>$value);	
} // end if null
else {
	// on vérifie si la valeur doit être unique
	// on commence par supposer que la valeur ne doit pas être unique
	$mustBeUnique=FALSE;
	if (!empty($cDetail["constraints"])) {
		foreach ($cDetail["constraints"] as $constraint) {
			if ($constraint["constraint_type"]=='UNIQUE' || $constraint["constraint_type"]=='PRIMARY KEY') {$mustBeUnique=TRUE;}
		}// end foreach $cDetail["constraints"]
	} // end if (!empty($cDetail["constraints"]))
	// si la valeur doit être unique, on recherche dans la table si une valeur égale à celle saisie existe déjà
	if ($mustBeUnique) {
		// on suppose que la valeur n'existe pas déjà dans la base
		$isUnique=TRUE;
		switch ($cDetail["data_type"]) {
		//si la colonne est un nombre
		case 'integer':
		case 'real':
		$uniqueSql='SELECT count('.$column.') FROM '.$table.' WHERE '.$column.'=\''.$value.'\'';
		break;
		//sinon, on teste sur la valeur lowercase (pour éviter d'avoir des ID du type AAA et aaa)
		default:
		$uniqueSql='SELECT count('.$column.') FROM '.$table.' WHERE lower('.$column.')=\''.strtolower($value).'\'';
		break;
		}
		$uniqueResult=pg_query($connectPPEAO,$uniqueSql) or die('erreur dans la requete : '.$uniqueSql. pg_last_error());
		$uniqueRow=pg_fetch_row($uniqueResult);
		$uniqueCount=$uniqueRow[0];
		 /* Libération du résultat */ 
		 pg_free_result($uniqueResult);
		// si il existe au moins une valeur égale dans la table, la valeur n'est pas valide
		if ($uniqueCount>0) {
			$isUnique=FALSE;
		}	
	}
	
	
	if ($mustBeUnique && !$isUnique) {
		$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur existe d&eacute;j&agrave; dans la table et doit &ecirc;tre unique',"valeur"=>$value);
	}
	else {
		// on ne traite que le cas où la valeur n'est pas vide
		if (!is_null($value) && $value!='') {
	// on teste la compatibilité entre les types de données
	switch ($cDetail["data_type"]) {

		// entier (on n'utilise pas is_int() car même si le script retourne "7", PHP considère que c'est une variable string)
		case 'integer': if (intval($value)!=$value) {$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur doit &ecirc;tre un entier',"valeur"=>$value);}
		break;

		// réel
		case 'real': 
			if (!is_numeric($value)) {$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur doit &ecirc;tre un nombre',"valeur"=>$value);}
		break;
		
		// booleen
		case 'boolean':
			if ($value!='t' && $value!='f') {$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur doit &ecirc;tre oui ou non',"valeur"=>$value);}
		break;
		
		//date (format AAAA-mm-jj)
		case 'date':
			$theDate=explode("-",$value);
			if (!checkdate($theDate[1],$theDate[2],$theDate[0])) {$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur doit &ecirc;tre une date au format aaaa-mm-jj',"valeur"=>$value);}
		break;
		

		//note : on ne teste pas la longueur des chaines pour les champs text et character varying,
		//puisque cette contrainte est appliquée à la saisie

	}// end switch
}
	} // end else (valeur unique)	
} // end else null

return $validityCheck;

}
?>