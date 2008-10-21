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
	global $tablesDefinitions;
	

	// on r�cup�re la hi�rarchie � afficher
	$theHierarchy=$tableSelectors[$hierarchyLabel];
	//debug	print_r(	$theHierarchy);

//on commence le formulaire
	echo('<form id="form_'.$hierarchyLabel.'" name="form_'.$hierarchyLabel.'" action="/edition/edition_selector.php" method="get">');
		echo('<input name="type" id="type" type="hidden" value="reference" />');
		echo('<input name="hierarchy" id="hierarchy" type="hidden" value="'.$hierarchyLabel.'" />');
	// on commence le SELECT
	echo('<select name="targetTable" id="select_'.$hierarchyLabel.'" onchange="javascript:form_'.$hierarchyLabel.'.submit();" size="1">');
	// la premi�re OPTION ne sert � rien...
	echo('<option value="choose">- choisir une table -</option>');
	
	// on construit la liste d'OPTIONs avec comme value le pointeur de la table � �diter et comme texte son label
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
	// $page: la page sur laquelle le s�lecteur est affich�
	// (par exemple, sur la page "editor.php" on affiche le lien pour afficher/masquer le s�lecteur)

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

	
// le s�lecteur	
echo('<div id="selector_content">');
	// on regarde si la table choisie n�cessite une cascade
	echo('<form id="selector_form">');
	//debug	print_r($selectorCascades);
	
	if (array_key_exists($targetTable,$selectorCascades)) 	{
		// si oui, on r�cupere la liste des tables de la cascade pass�es dans l'URL
			//debug		echo($targetTable." : cascade : ".$selectorCascades[$targetTable].'<br />');
			// on cr�e le tableau avec la liste des tables de la cascade
			$theTables=split(",",$selectorCascades[$targetTable]);
			}
		else {
			// sinon, on utilise directement la table
			//debug echo($targetTable." : pas de cascade");
			// on cr�e le tableau avec seulement la table
			$theTables=array($targetTable);
			;}
		//debug		print_r($theTables);
	// end if (array_key_exists)
	// on boucle dans le tableau $theTables pour ins�rer le(s) SELECT
	// on initialise le niveau du premier SELECT (utilis� pour construire les ID des DIV)
	$level=1;
	foreach ($theTables as $oneTable) {
		$selectedValues=array();
		$selectedValues=$_GET[$oneTable];
		$selectedParentValues=array();
		if (isset($_GET[$parentTable])) {$selectedParentValues=$_GET[$parentTable];}
		
		
		// on construit la clause SQL permettant de filtrer les valeurs
		// en fonction de celles s�lectionn�es dans les SELECT pr�c�dents
		if ($level>1 && !empty($selectedParentValues)) {
			// on r�cup�re la liste des valeurs s�lectionn�es de la table du niveau pr�c�dent
		
			$theList='\'';
			$theList.=implode($_GET[$parentTable],"','");
			$theList.='\'';
			
			$whereClause=' AND '.$tablesDefinitions[$parentTable]["id_col"].' IN ('.$theList.') ';
			
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
// ins�re un SELECT listant les valeurs d'une table
function createTableSelect($theTable,$selectedValues,$level,$whereClause) {
// $theTable : la table � utiliser (correspondance avec les tables de la base d�finie dans edition_config.inc)
// $selectedValues : les valeurs � s�lectionner dans le SELECT
// $level : le "niveau" du SELECT (pour les cascades)
// $whereClause : la clause SQL additionnelle pour filtrer les OPTION du SELECT en fonction des s�lections pr�c�dentes
	
	global $tablesDefinitions;
	global $connectPPEAO; // la connexion a utiliser (on travaille avec deux bases : BD_PECHE et BD_PPEAO)
	
	//debug	print_r($selectedValues);
	// le nom de la table
	echo('<p>'.$tablesDefinitions[$theTable]["label"].'</p>');
	// le SELECT avec les valeurs de la table
	//le SELECT accepte-t-il les s�lections multiples
	//debug 
	$isMultiple='multiple="multiple"';
	
	

		// si la table parent a au moins une valeur s�lectionn�e (whereClause non vide) ou qu'on est au premier niveau, on affiche le s�lect
		if (!empty($whereClause) || $level==1) {
		
		
			// on d�termine si il existe des valeurs du nouveau SELECT correspondant aux s�lections pr�c�dentes
			// on construit la requ�te SQL
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
				// on d�termine les OPTION � s�lectionner
				if (@in_array($value["value"], $selectedValues)) {$selected='selected="selected"';} else {$selected='';}
				// on affiche l'OPTION
				echo('<option value="'.$value["value"].'" '.$selected.'>'.$value["text"].'</option>');
			}
			echo('</select>');
			
			// les boutons permettant de s�lectionner/d�s�lectionner toutes les valeurs du SELECT
			echo('<p id="selectlink__'.$level.'" class="select_link">s&eacute;lectionner ');
				echo('<a href="#" onclick="javascript:toggleSelect(\''.$level.'\',\''.$theTable.'\',\'all\');" class="link_button">tout</a> ');echo(' <a href="#" onclick="javascript:toggleSelect(\''.$level.'\',\''.$theTable.'\',\'none\');"  class="link_button">rien</a>');
			echo('</p>');
			
			// le lien permettant d'�diter la table ou les valeurs s�lectionn�es
			echo('<p id="editlink_'.$level.'" class="edit_link">');
			
			// on pr�pare l'URL du lien
			$theUrl=replaceQueryParam ($_SERVER["QUERY_STRING"],'editTable',$theTable);
			echo('<a id="edita_'.$level.'" class="link_button" href="edition_table.php?'.$theUrl.'">');
				// si aucune valeur du SELECT n'est s�lectionn�e, on met un lien "�diter la table"
				if (empty($selectedValues)) {
					echo('&eacute;diter la table');
					}
				// si une ou plusieurs valeurs sont s�lectionn�es, on met un lien "�diter la s�lection" et on adapte l'URL
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
// affiche un champ de formulaire permettant d'�diter un champ d'une table
function makeField($cDetails,$table,$column,$value,$action,$theUrl) {
// $cDetails : tableau retourn� par la fonction getTableColumnsDetails()
// table : la table concern�e (identifiant de la table dans la variable $tablesDefinitions de edition_config.inc)
// $column : la colonne concern�e
// $value : la valeur du champ de la colonne concern�e
// $action : 'display=xxx'/'edit=xxx' pour cr�er un champ afichable/�ditable de l'enregistrement xxx, 'filter' pour un champ de filtre, 'add' pour l'ajout d'un nouvel enregistrement
// $theUrl : l'URL � utiliser pour les champs de tri de type SELECT ()

// la connection � la base
global $connectPPEAO;
global $tablesDefinitions;

// la longueur (et longueur max) par d�faut des champs INPUT de type TEXT
$defaultTextInputLength=15;
$defaultTextInputMaxLength=30;
// nombre de rows par d�faut des <textarea>
$defaultTextRows=5;



if (substringBefore($action,'=')=='edit') {$editRow=substringAfter($action,'=');$action='edit';}
if (substringBefore($action,'=')=='display') {$editRow=substringAfter($action,'=');$action='display';}


// valeur � utiliser comme ID, NAME et CLASS des champs de formulaire, selon que l'on �dite ou filtre
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

// variable dans laquelle on stocke ce qui doit �tre affich�
$theField='';

$theDetails=$cDetails[$column];
/*debug	
echo('<pre>');
	print_r($theDetails);
	echo('</pre>');*/
	// on teste si la colonne concern�e a une contrainte de type cl� primaire, cl� �trang�re ou �num�ration
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
		
			// cas d'une cl� primaire
			case 'PRIMARY KEY' : 
				// les cl�s primaires ne sont pas �ditables mais filtrables
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
						 // on modifie la classe du champ non �ditable
						$theClass='non_editable_field';
						$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="les cl&eacute;s primaires ne sont pas &eacute;ditables">'.$value.'</div>';
					break;
						
					// si on ajoute un nouvel enregistrement
					case 'add' :
					// si il existe une s�quence sur la cl�, on g�n�re automatiquement la valeur et le champ n'est pas �ditable
					$ifSequence=getTableColumnSequence($connectPPEAO,$tablesDefinitions[$table]["table"],$column);
					echo($ifSequence);
					if ($ifSequence) {
						$theClass='non_editable_field';
						$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="valeur d�termin�e par une s&eacute;quence automatique">(auto)</div>';
					}
					else {
					// sinon, on ins�re un champ input
					
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
			
			// cas d'une �num�ration : on construit un <SELECT> avec les valeurs de l'�num�ration
			case 'ENUM':
				$theOptions=explode(",", $theConstraint["check_clause"]);
				
				switch ($action) {
						case 'filter':
						$theField='<div class="filter>"<select id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" onchange="javascript:filterTable(\''.$theUrl.'\');">';
							// on ajoute une valeur "vide"
								$theField.='<option value="" '.$selected.'>-</option>';
							foreach($theOptions as $theOption) {
								// on selectionne eventuellement l'option correspondant � la valeur courante du champ
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
							// on selectionne eventuellement l'option correspondant � la valeur courante du champ
							if ($theOption==$value) {$selected='selected="selected"';} else {$selected='';}
							$theField.='<option value='.$theOption.' '.$selected.'>'.$theOption.'</option>';
							}
					$theField.='</select></div>';
					break;
					
				}// end switch $action
				;
			break;
			
			// cas d'une cl� �trang�re : on construit un <SELECT> avec les valeurs de la table/colonne r�f�renc�e
			case 'FOREIGN KEY':
				
				switch ($action) {
					
					case 'display':
					// on recupere la valeurs de la cl� etrangere -> on utilise la table indiqu�e dans $cDetails
					$theFtable=$theConstraint["references_table"];
					$theFtableAlias=getTableAliasFromName($theFtable);
					$theFKeys=$tablesDefinitions[$theFtableAlias]["id_col"];
					$theFValues=$tablesDefinitions[$theFtableAlias]["noms_col"];

					
					// dans le cas o� une valeur de la cl� �trang�re est d�finie
					if (!empty($value)) {
					$sqlFValue='SELECT '.$theFValues.'
								FROM '.$theFtable.'
								WHERE '.$theFKeys.'=\''.$value.'\' 
								ORDER BY '.$theFValues;
																
					$resultFvalue=pg_query($connectPPEAO,$sqlFValue) or die('erreur dans la requete : '.$sqlFValue. pg_last_error());
					$fValue=pg_fetch_all($resultFvalue);
					pg_free_result($resultFvalue);
					
					// la valeur � afficher
					$theDisplayValue=$fValue[0][$theFValues];
					} // end if !empty($value)
					// si la valeur de la cl� �trang�re est NULL
					else {
						$theDisplayValue='';
					}
					$theField='<div id="'.$theId.'" name="'.$theId.'" class="'.$theClass.'" title="cliquer pour &eacute;diter cette valeur" onclick="javascript:makeEditable(\''.$table.'\',\''.$column.'\',\''.addSlashes($value).'\',\''.$editRow.'\',\'edit\');">'.$theDisplayValue.'</div>';
					break;

					case 'add':
					case 'edit': 
					case 'filter':
						// on recupere les valeurs de la cl� etrangere -> on utilise la table indiqu�e dans $cDetails
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
						// on ajoute une valeur "vide" si on est en �dition ou ajout (cl� secondaire JAMAIS NULL)
						if ($action=='filter') {$theField.='<option value="" '.$selected.'>-</option>';}
						foreach ($fKeys as $fKey) {
							if ($fKey[$theFKeys]==$value) {$selected='selected="selected"';} else {$selected='';}
							// selon que l'on a pass� la valeur directement ou depuis la base
							$theValue=$fKey[$theFValues];
							//if (true) {$theEncodedValue=iconv('ISO-8859-15','UTF-8',$fKey[$theFValues]);} else {$theEncodedValue=$fKey[$theFValues];}
							$theField.='<option value='.$fKey[$theFKeys].' '.$selected.'>'.$theValue.'</option>';
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
				// pour l'�dition, on doit prendre en compte la longueur du champ et si il est de type TEXT
				// type text : on affiche une <textarea> sans limite de taille
				if ($theDetails["data_type"]=='text') {$theType='textarea';$theMaxLength='';} 
				// autres types avec un character_maximum_length > valeur par d�faut : on affiche une <textarea> avec limite de taille 
				else {
					if ($theDetails["character_maximum_length"]>$defaultTextInputMaxLength) {
						$theType='textarea';$theMaxLength=$theDetails["character_maximum_length"];
					}
					// autres types avec un character_maximum_length <= valeur par d�faut : on affiche un <inpu type=text>
					if ($theDetails["character_maximum_length"]<=$defaultTextInputMaxLength) {
						$theType='input';$theMaxLength=$theDetails["character_maximum_length"];
					}
				} // end else $theDetails["data_type"]=='text'

				// on affiche une <textarea>
				if ($theType=='textarea') {

					// si on a une longueur maximale autoris�e pour la <textarea>, on ajoute le javascript de controle
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
// permet de v�rifier si une valeur est compatible avec un champ de la base de donn�e
function checkValidity($cDetails,$table,$column,$value) {
// $cDetails : tableau retourn� par la fonction getTableColumnsDetails()
// $column : la colonne concern�e
// $value : la valeur dont on veut tester la validit�

global $connectPPEAO;

// on stocke les informations sur la colonne concern�e
$cDetail=$cDetails[$column];

// on suppose que la valeur est valide
$validityCheck=array("validity"=>1, "errorMessage"=>'',"valeur"=>$value);

// on commence les v�rifications
// si la valeur saisie est "null" et que la colonne ne le permet pas
if ((is_null($value) || $value=='') && $cDetail["is_nullable"]!='YES') {
	$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur ne peut pas �tre vide',"valeur"=>$value);	
} // end if null
else {
	// on v�rifie si la valeur doit �tre unique
	// on commence par supposer que la valeur ne doit pas �tre unique
	$mustBeUnique=FALSE;
	if (!empty($cDetail["constraints"])) {
		foreach ($cDetail["constraints"] as $constraint) {
			if ($constraint["constraint_type"]=='UNIQUE' || $constraint["constraint_type"]=='PRIMARY KEY') {$mustBeUnique=TRUE;}
		}// end foreach $cDetail["constraints"]
	} // end if (!empty($cDetail["constraints"]))
	// si la valeur doit �tre unique, on recherche dans la table si une valeur �gale � celle saisie existe d�j�
	if ($mustBeUnique) {
		// on suppose que la valeur n'existe pas d�j� dans la base
		$isUnique=TRUE;
		$uniqueSql='SELECT count('.$column.') FROM '.$table.' WHERE lower('.$column.')=\''.strtolower($value).'\'';
		$uniqueResult=pg_query($connectPPEAO,$uniqueSql) or die('erreur dans la requete : '.$uniqueSql. pg_last_error());
		$uniqueRow=pg_fetch_row($uniqueResult);
		$uniqueCount=$uniqueRow[0];
		 /* Lib�ration du r�sultat */ 
		 pg_free_result($uniqueResult);
		// si il existe au moins une valeur �gale dans la table, la valeur n'est pas valide
		if ($uniqueCount>0) {
			$isUnique=FALSE;
		}	
	}
	
	
	if ($mustBeUnique && !$isUnique) {
		$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur existe d&eacute;j&agrave; dans la table et doit &ecirc;tre unique',"valeur"=>$value);
	}
	else {
		// on ne traite que le cas o� la valeur n'est pas vide
		if (!is_null($value) && $value!='') {
	// on teste la compatibilit� entre les types de donn�es
	switch ($cDetail["data_type"]) {

		// entier (on n'utilise pas is_int() car m�me si le script retourne "7", PHP consid�re que c'est une variable string)
		case 'integer': if (intval($value)!=$value) {$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur doit &ecirc;tre un entier',"valeur"=>$value);}
		break;

		// r�el
		case 'real': 
			if (!is_numeric($value)) {$validityCheck=array("validity"=>0, "errorMessage"=>'cette valeur doit &ecirc;tre un nombre',"valeur"=>$value);}
		break;

		//note : on ne teste pas la longueur des chaines pour les champs text et character varying,
		//puisque cette contrainte est appliqu�e � la saisie

	}// end switch
}
	} // end else (valeur unique)	
} // end else null

return $validityCheck;

}
?>