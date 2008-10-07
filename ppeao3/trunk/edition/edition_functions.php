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
	$theType=$_GET["type"];
	$editTable=$_GET["editTable"];



// le titre
echo('<h1 class="selector"><a href="/edition.php">&eacute;dition des donn&eacute;es</a>');
switch ($theType) {
	case "reference" : $theTypeString=" de r&eacute;f&eacute;rence"; $theSelectorType="tableSelectors";
	break;
	case "parametrage" : $theTypeString=" de param&eacute;trage"; $theSelectorType="tableSelectors";
	break;
	default: $theTypeString="";
	break;
		}
if ($page=='edition') {$theSelectedTable=$tablesDefinitions[$editTable]["label"];} else {$theSelectedTable=$tablesDefinitions[$targetTable]["label"];;}
echo(' : table '.$theTypeString.' "'.$theSelectedTable.'" <span class="showHide"><a href="" id="showHideSelect"></a></span></h1>');

	
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
// affiche un champ de formulaire permettant d'éditer un champ d'une table
function makeField($cDetails,$table,$column,$value,$action,$theUrl) {
// $cDetails : tableau retourné par la fonction getTableColumnsDetails()
// table : la table concernée (identifiant de la table dans la variable $tablesDefinitions de edition_config.inc)
// $column : la colonne concernée
// $value : la valeur du champ de la colonne concernée
// $action : 'display=xxx'/'edit=xxx' pour créer un champ afichable/éditable de l'enregistrement xxx, 'filter' pour un champ de filtre, 'add' pour l'ajout d'un nouvel enregistrement
// $theUrl : l'URL à utiliser pour les champs de tri de type SELECT ()

// la connection à la base
global $connectPPEAO;
global $tablesDefinitions;

// la longueur (et longueur max) par défaut des champs INPUT de type TEXT
$defaultTextInputLength=15;
$defaultTextInputMaxLength=30;
// nombre de rows par défaut des <textarea>
$defaultTextRows=5;



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
		$theId='a_'.$column;
	break;
}// end switch $action

// variable dans laquelle on stocke ce qui doit être affiché
$theField='';

$theDetails=$cDetails[$column];
/*debug	
echo('<pre>');
	print_r($theDetails);
	echo('</pre>');*/
	// on teste si la colonne concernée a une contrainte de type clé primaire, clé étrangère ou énumération
	$keyConstraint=FALSE;
	if (isset($theDetails["constraints"]) && !empty($theDetails["constraints"])) {
		$constraintsToCheck=array('PRIMARY KEY','ENUM','FOREIGN KEY');
		// on teste le type de contrainte
		
		foreach($theDetails["constraints"] as $theConstraint) {
			if (in_array($theConstraint["constraint_type"],$constraintsToCheck)){
				$constraint=$theConstraint["constraint_type"];
				$keyConstraint=TRUE;
			}
		} // end foreach
		
		
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
						$theField='<div class="filter>"<select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" onchange="javascript:filterTable(\''.$theUrl.'\');">';
							// on ajoute une valeur "vide"
								$theField.='<option value="" '.$selected.'>-</option>';
							foreach($theOptions as $theOption) {
								// on selectionne eventuellement l'option correspondant à la valeur courante du champ
								if ($theOption==$value) {$selected='selected="selected"';} else {$selected='';}
								$theField.='<option value='.$theOption.' '.$selected.'>'.$theOption.'</option>';
								}
						$theField.='</select>';
					break;
					case 'display' : $theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'">'.$value.'</div>';
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
					// on recupere la valeurs de la clé etrangere -> on utilise la table indiquée dans $cDetails
					$theFtable=$theConstraint["references_table"];
					$theFtableAlias=getTableAliasFromName($theFtable);
					$theFKeys=$tablesDefinitions[$theFtableAlias]["id_col"];
					$theFValues=$tablesDefinitions[$theFtableAlias]["noms_col"];

					
					// dans le cas où une valeur de la clé étrangère est définie
					if (!empty($value)) {
					$sqlFValue='SELECT '.$theFValues.'
								FROM '.$theFtable.'
								WHERE '.$theFKeys.'=\''.$value.'\' 
								ORDER BY '.$theFValues;
																
					$resultFvalue=pg_query($connectPPEAO,$sqlFValue) or die('erreur dans la requete : '.$sqlFValue. pg_last_error());
					$fValue=pg_fetch_all($resultFvalue);
					pg_free_result($resultFvalue);
					
					// la valeur à afficher
					$theDisplayValue=$fValue[0][$theFValues];
					} // end if !empty($value)
					// si la valeur de la clé étrangère est NULL
					else {
						$theDisplayValue='';
					}
					$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="cliquer pour &eacute;diter cette valeur" onclick="javascript:makeEditable(\''.$table.'\',\''.$column.'\',\''.addSlashes($value).'\',\''.$editRow.'\',\'edit\');">'.$theDisplayValue.'</div>';
					break;

					case 'add':
					case 'edit': 
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

						if ($action=='filter')
							{$onAction='onchange="javascript:filterTable(\''.$theUrl.'\');"';} 
							else {
							$onAction='';}
						$theField='<div class="filter"><select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" '.$onAction.'>';
						// on ajoute une valeur "vide"
						$theField.='<option value="" '.$selected.'>-</option>';
						foreach ($fKeys as $fKey) {
							if ($fKey[$theFKeys]==$value) {$selected='selected="selected"';} else {$selected='';}
							$theField.='<option value='.$fKey[$theFKeys].' '.$selected.'>'.$fKey[$theFValues].'</option>';
						}
						$theField.='</select></div>';
					break;

				}
				;
			break;
		
		}// end switch constraint_type
	}
	// si la colonne n'a pas de contrainte
	else {
		//debug		echo('**pas de contrainte sur '.$theDetails["column_name"].' ('.$value.')->'.$action.'<br>');
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

			case 'display' : if (empty($value)) {$value="";} $theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="cliquez pour &eacute;diter cette valeur" onclick="javascript:makeEditable(\''.$table.'\',\''.$column.'\',\''.addSlashes($value).'\',\''.$editRow.'\',\'edit\');">'.$value.'</div>';
			break;

			case 'filter': 	$theField='<div class="filter"><input type="text" title="saisissez une valeur puis appuyez sur la touche ENTR&Eacute;E" id="'.$theId.'" name="'.$theId.'" value="'.$value.'" class="'.$theClass.'" size="'.$length.'" maxlength="'.$maxLength.'" onchange="javascript:filterTable(\''.$theUrl.'\');"> </input></div>';
			break;
			
			case 'add':
			case 'edit' :
				// pour l'édition, on doit prendre en compte la longueur du champ et si il est de type TEXT
				// type text : on affiche une <textarea> sans limite de taille
				if ($theDetails["data_type"]=='text') {$theType='textarea';$theMaxLength='';}
				// autres types avec un character_maximum_length > valeur par défaut : on affiche une <textarea> avec limite de taille 
				if ($theDetails["character_maximum_length"]>$defaultTextInputMaxLength) {
					$theType='textarea';$theMaxLength=$theDetails["character_maximum_length"];
				}
				// autres types avec un character_maximum_length <= valeur par défaut : on affiche un <inpu type=text>
				if ($theDetails["character_maximum_length"]<=$defaultTextInputMaxLength) {
					$theType='input';$theMaxLength=$theDetails["character_maximum_length"];
				}

				// on affiche une <textarea>
				if ($theType=='textarea') {

					// si on a une longueur maximale autorisée pour la <textarea>, on ajoute le javascript de controle
					// (il est impossible de limiter le contenu d'une <textarea> en HTML)
					if (!empty($theMaxLength)) {
						$args='$(\''.$theId.'\'),$(\''.$theId.'_counter\'),'.$theMaxLength.'';
						$theLengthLimitation='onKeyDown="fieldTextLimiter('.$args.')" onKeyUp="fieldTextLimiter('.$args.')"  onFocus="fieldTextLimiter('.$args.')" onBlur="fieldTextLimiter('.$args.')"';
						$textRows=round($theMaxLength/$defaultTextInputMaxLength)+1;}
					else {$theLengthLimitation='';$textRows=$defaultTextRows;}
					
					$theField='<textarea id="'.$theId.'" name="'.$theId.'" 
					cols="'.$defaultTextInputMaxLength.'" rows="'.$textRows.'" '.$theLengthLimitation.'  '.$onAction.'  class="'.$theClass.'">'.stripSlashes($value).'</textarea>
					<p id="'.$theId.'_counter" class="small"></p>';
					
					
					//debug
					
					

				} // end if textarea

				// on affiche un <input>
				if ($theType=='input') {
					$theField='<input title="" type="text" id="'.$theId.'" name="'.$theId.'" value="'.stripSlashes($value).'"  class="'.$theClass.'" size="'.$theMaxLength.'" maxlength="'.$theMaxLength.'"  '.$onAction.'></input>';
				} // end if input
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
if (is_null($value) && $cDetail["is_nullable"]!='YES') {
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
		$uniqueSql='SELECT count('.$column.') FROM '.$table.' WHERE '.$column.'=\''.$value.'\'';
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
	// on teste la compatibilité entre les types de données
	switch ($cDetail["data_type"]) {

		// entier (on n'utilise pas is_int() car même si le script retourne "7", PHP considère que c'est une variable string)
		case 'integer': if (intval($value)!=$value) {$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur doit &ecirc;tre un entier',"valeur"=>$value);}
		break;

		// réel
		case 'real': 
			if (!is_numeric($value)) {$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur doit &ecirc;tre un nombre',"valeur"=>$value);}
		break;

		//note : on ne teste pas la longueur des chaines pour les champs text et character varying,
		//puisque cette contrainte est appliquée à la saisie

	}// end switch

	} // end else (valeur unique)	
} // end else null

return $validityCheck;

}
?>