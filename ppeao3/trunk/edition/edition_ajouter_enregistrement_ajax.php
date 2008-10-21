<?php

// script appelé par la fonction javascript showNewLevel
// 
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';



global $tablesDefinitions;

//debug sleep(1);

// la table concernée
$editTable=$_GET["editTable"];
$level=$_GET["level"];


// on compile les informations sur les colonnes de la table $editTable
$cDetails=getTableColumnsDetails($connectPPEAO,$tablesDefinitions[$editTable]["table"]);
// la liste des colonnes concernées
$theHeads=array_keys($cDetails);

$theForm='<h1>Ajouter un nouvel enregistrement dans la table "'.iconv('ISO-8859-15','UTF-8',$tablesDefinitions[$editTable]["label"]).'"</h1>';

// on insère le formulaire d'ajout d'un nouvel enregistrement...
$theForm.='<form id="add_record_'.$level.'_form">';
$theForm.='<ul id="add_record_'.$level.'_ul">';
	foreach ($theHeads as $oneHead) {
		$theForm.='<li class="small">';
		$theForm.='<p>'.$oneHead.': </p>';
		$theForm.=iconv('ISO-8859-15','UTF-8',makeField($cDetails,$editTable,$oneHead,'','add',''));
		$theForm.='</li>';	
	}
$theForm.='</ul>';
$theForm.='</form>';

echo($theForm);
?>