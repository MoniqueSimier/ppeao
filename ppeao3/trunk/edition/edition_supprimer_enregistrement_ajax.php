<?php
session_start();

// script appel� par la fonction javascript showNewLevel
// 
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';



global $tablesDefinitions;

//debug sleep(10);

// la table concern�e
$table=$_GET["table"];
// l'enregistrement concern�
$record=$_GET["record"];
$level=$_GET["level"];



// on d�termine les enregistrements affect�s par la suppression de l'enregistrement (on se limite au premier niveau de cascade)
$affectedTables=countPrimaryKeyReferencedRows($connectPPEAO, $table, '', $record);

$theMessage.='<div align="center"><h1>supprimer l&#x27;enregistrement &quot;'.$record.'&quot;</h1></div>';

// si on ne trouve aucun enregistrement affect�
if (empty($affectedTables)) {
	$theMessage.="<p>Cet enregistrement n&#x27;est pas utilis&eacute; comme cl&eacute; &eacute;trang&egrave;re par d&#x27;autres tables, le supprimer n&#x27;entra&icirc;nera pas de suppression en cascade.</p>";
}

// si on trouve au moins un enregistrement affect�
else {
	$theMessage.="<p>Cet enregistrement est utilis&eacute; comme cl&eacute; &eacute;trang&egrave;re par d&#x27;autres tables, le supprimer entra&icirc;nera la suppression d&#x27;autres enregistrements&nbsp;:</p>";
	$theMessage.='<ul>';
	foreach($affectedTables as $key=>$value) {
		$theMessage.='<li>';
		$theMessage.='table&nbsp; "'.$key.'" : '.count($value).' enregistrements';
		$theMessage.='</li>';
	}
	$theMessage.='</ul>';

$theMessage.='note : la suppression des enregistrements des tables mentionn&eacute;es ci-dessus pourra entra&icirc;ner &agrave; son tour la suppression d&#x27;autres enregistrements.';
}

echo($theMessage);
?>