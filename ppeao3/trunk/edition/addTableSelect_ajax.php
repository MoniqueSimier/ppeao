<?php

// script appel� par la fonction javascript showNewLevel
// 

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';


global $selectorCascades;
global $tablesDefinitions;

$thisHierarchy=$_GET["hierarchy"];
$targetTable=$_GET["targetTable"];
$thisLevel=$_GET["level"];

$tablesList=explode(",",$selectorCascades[$targetTable]);

$thisTable=$tablesList[$thisLevel-1];
$parentTable=$tablesList[$thisLevel-2];
$selectedValues='';


// on construit la clause SQL pour filtrer ls valeurs de la nouvelle table en fonction de celles de la table pr�c�dente
$theList='\'';
$theList.=implode($_GET[$parentTable],"','");
$theList.='\'';
$whereClause=' AND '.$tablesDefinitions[$parentTable]["table"].'_id IN ('.$theList.') ';

// on g�n�re le SELECT
createTableSelect($thisTable,$selectedValues,$thisLevel,$whereClause);

?>