<?php

// script appelé par la fonction javascript showNewLevel
// 

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';


global $tablesDefinitions;

//debug sleep(1);

// la table concernée
$editTable=$_GET["editTable"];
// la colonne concernée
$editColumn=$_GET["editColumn"];
// l'enregistrement concerné (son ID)
$editRecord=$_GET["editRecord"];
// l'enregistrement concerné (sa valeur)
$editValue=$_GET["editValue"];
// l'action à effectuer 
$editAction=$_GET["editAction"];

//debug echo('<pre>');print_r($_GET);echo('</pre>');



// on compile les informations sur les colonnes de la table $editTable
$cDetails=getTableColumnsDetails($connectPPEAO,$tablesDefinitions[$editTable]["table"]);

//debug echo('<pre>');print_r($cDetails);echo('</pre>');

// selon l'action requise, on réagit différemment

switch ($editAction) {
	// on veut rendre le champ editable
	case 'edit':
	// le DIV contenant la zone d'édition
	$theField.='<div id="edit_'.$editColumn.'_'.$editRecord.'" name="edit_'.$editColumn.'_'.$editRecord.'" class="edit_field_container small">';
	// on ajoute le champ éditable
	$theField.=makeField($cDetails,$editTable,$editColumn,$editValue,'edit='.$editRecord,'');

	// on ajoute les boutons "OK/ANNULER"
	$theField.='<div id="edit_buttons_'.$editColumn.'_'.$editRecord.'" name="edit_buttons_'.$editColumn.'_'.$editRecord.'" class="small edit_buttons">';
		$theField.='<a href="javascript:saveChange(\''.$editTable.'\',\''.$editColumn.'\',\''.$editValue.'\',\''.$editRecord.'\',\'cancel\');"" class="edit_button" title="enregistrer la modification">enregistrer</a>';
		$theField.='<a href="javascript:makeEditable(\''.$editTable.'\',\''.$editColumn.'\',\''.$editValue.'\',\''.$editRecord.'\',\'cancel\');" class="edit_button" title="annuler la modification">annuler</a>';

	$theField.='</div>';
	break;
	
	// on veut annuler les modifications faites au champ
	case 'cancel':
		$theField=makeField($cDetails,$editTable,$editColumn,$editValue,'display='.$editRecord,'');
	break;
	
	case 'save':
	break;

} // end switch $action


echo($theField);

?>