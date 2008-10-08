<?php

// script appelé par la fonction javascript showNewLevel
// 

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
//debug sleep (50);

global $tablesDefinitions;

//debug sleep(1);

// la table concernée
$table=$_GET["table"];
$level=$_GET["level"];


// on compile les informations sur les colonnes de la table $editTable
$cDetails=getTableColumnsDetails($connectPPEAO,$tablesDefinitions[$table]["table"]);
// la liste des colonnes concernées
$theColumns=array_keys($cDetails);
// on suppose que toutes les valeurs sont valides
$valid='valid';
// on teste les valeurs passées dans l'URL pour chaque colonne
foreach ($theColumns as $oneColumn) {
	
	$cDetail=$cDetails[$oneColumn];
	$newValue=$_GET["add_record_".$level.'_'.$oneColumn];

	// on "nettoie" la valeur saisie
	// si la valeur doit être un réel, on commence par convertir une éventuelle saisie au format décimal "," au lieu de "."
	if ($cDetail["data_type"]=='real') {
		$newValue=str_replace(',','.',$newValue);
	}
	// on teste la validité de la valeur saisie
	// si le champ est géré par une séquence
	if (getTableColumnSequence($connectPPEAO,$tablesDefinitions[$table]["table"],$oneColumn)) {
		
		$validityCheck[$oneColumn]=array("validity"=>1,"errorMessage"=>"","sequence"=>"sequence");
	}
	else {
		// on compile la liste des colonnes dont il faudra passer la valeur lors de l'INSERT (toutes sauf celles avec une séquence)
		if (!is_null($newValue) && $newValue!='') {$theInsertKeys[$oneColumn]=$oneColumn;
		// on compile un tableau des nouvelles valeurs
		$newValues[$oneColumn]='\''.$newValue.'\'';
		}
	// sinon on fait un test normal
	$validityCheck[$oneColumn]=checkValidity($cDetails,$tablesDefinitions[$table]["table"],$oneColumn,$newValue);
	// si la valeur n'est pas valide, on invalide l'ensemble de la saisie
	if (!$validityCheck[$oneColumn]["validity"]) {$valid='invalid';}
	}

} // end foreach $theColumns

//debugecho('<pre>');print_r($validityCheck);echo('</pre>');

// si toutes les valeurs sont valides, on fait l'INSERT sur la base...
if ($valid=='valid') {
	$addSql='	INSERT INTO '.$tablesDefinitions[$table]["table"].'
				('.arrayToList($theInsertKeys,',','').')
				VALUES ('.arrayToList($newValues,',','').')';
	//debug 	echo($addSql);
	if($addResult=pg_query($connectPPEAO,$addSql)) {
	// et on renvoie un message positif
	$message='<!--[CDATA[Enregistrement ajout&eacute; dans la table '.$table.']]-->';}
	else {
		$message= '<!--[CDATA[Une erreur est survenue lors de l\'enregistrement la table '.$table.' : '.pg_last_error().']]-->';
		$valid='invalid';
	}
}
else {$message='';}


// on commence à créer la réponse en XML
$theXml='<?xml version="1.0"?>';
// on indique dans un attribut de la réponse le statut global de la saisie
$theXml.='<response validity="'.$valid.'" table="'.$table.'">';

//on crée un élément XML pour chaque colonne
foreach ($theColumns as $oneColumn) { 

	// si on a affaire à une colonne à sequence, on le signale
	if ($validityCheck[$oneColumn]["sequence"]=="sequence") {$sequence='sequence="sequence"';} else {$sequence='sequence="no"';}

	// on insère le contenu de la réponse pour cette colonne
	$theXml.='<responseContent key="'.$oneColumn.'" valid="'.$validityCheck[$oneColumn]["validity"].'" '.$sequence.'>';
	
	// on compose la réponse selon que la valeur est valide ou pas
	if (!$validityCheck[$oneColumn]["validity"]) {
		$error='<!--[CDATA[<span>erreur : '.$validityCheck[$oneColumn]["errorMessage"].'.</span>]]-->';
		$response=iconv('ISO-8859-15','UTF-8',$error);
	}
	else {
		$response='';
	}
	$theXml.=$response;
	$theXml.='</responseContent>';
}

$theXml.='</response>';

// outputting the XML response
header('Content-Type: text/xml');
echo($theXml);


?>