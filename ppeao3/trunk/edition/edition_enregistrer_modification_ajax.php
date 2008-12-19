<?php
session_start();
// script appel� par la fonction javascript showNewLevel
// 
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';



global $tablesDefinitions;

//debug sleep(50);

// la table concern�e
$editTable=$_GET["editTable"];
// la colonne concern�e
$editColumn=$_GET["editColumn"];
// l'enregistrement concern� (son ID)
$editRecord=$_GET["editRecord"];
// l'action � effectuer 
$editAction=$_GET["editAction"];
// la nouvelle valeur saisie
$newValue=$_GET["newValue"];
//debug echo('XXX '.$newValue.' XXX');
// la valeur actuelle du champ
// on r�cup�re la valeur du champ dans la base de donn�es pour �viter les probl�mes d'encodage...
$sql='	SELECT '.$editColumn.' FROM '.$tablesDefinitions[$editTable]["table"].' 
		WHERE '.$tablesDefinitions[$editTable]["id_col"].'=\''.$editRecord.'\' ';
$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
$values=pg_fetch_all($result);
pg_free_result($result);
$oldValue=htmlspecialchars($values[0][$editColumn]);

//debug echo('<pre>');print_r($_GET);echo('</pre>');



// on compile les informations sur les colonnes de la table $editTable
$cDetails=getTableColumnsDetails($connectPPEAO,$tablesDefinitions[$editTable]["table"]);
$cDetail=$cDetails[$editColumn];
// avant de d�marrer, on "bricole" les infos sur la colonne pour traiter certains cas particuliers
// cas d'une colonne stockant un mot de passe
if ($cDetail["column_name"]=="user_password") {
	$cDetail["data_type"]="password";
	// on utilise les deux premiers caract�res du username comme "salt"
	$userSql='SELECT user_name FROM admin_users WHERE user_id='.$editRecord;
	$userResult=pg_query($connectPPEAO,$userSql) or die('erreur dans la requete : '.$userSql. pg_last_error());
	$userArray=pg_fetch_row($userResult);
	$username=$userArray[0];
	pg_free_result($userResult);
	$salt=substr($username,0,2);
	//on encrypte le mot de passe soumis avant de le stocker
	$newValue=crypt($newValue,$salt);
}

// on "nettoie" la valeur saisie
// si la valeur doit �tre un r�el, on commence par convertir une �ventuelle saisie au format d�cimal "," au lieu de "."
if ($cDetail["data_type"]=='real') {
	$newValue=str_replace(',','.',$newValue);
}
// si la valeur doit �tre un booleen, on commence par convertir la saisie en true/false
if ($cDetail["data_type"]=='boolean') {
	if ($newValue=='oui' || $newValue=='t') {$newValue="t";} else {$newValue="f";}
}



// on teste la validit� de la valeur saisie
$validityCheck=checkValidity($cDetails,$tablesDefinitions[$editTable]["table"],$editColumn,$newValue);

//debug echo('<pre>');print_r($validityCheck);echo('</pre>');


// si la valeur saisie est valide, on ex�cute la requ�te SQL
if ($validityCheck["validity"]) {

	// si la requ�te s'est bien pass�e, on retourne "valid"
	// note : en cas de valeur pass�e NULL, postgres n'accepte pas SET champ='NULL' si champ est de type INTEGER
	// alors que l'on peut faire SET champ='200'... d'o� le test qui suit
	
	if ($newValue=='NULL' || my_empty($newValue)) {$newValueSet='NULL';} else {$newValueSet='\''.$newValue.'\'';}
		$saveSql=' UPDATE '.$tablesDefinitions[$editTable]["table"].'
				SET '.$editColumn.'='.$newValueSet.' WHERE '.$tablesDefinitions[$editTable]["id_col"].'=\''.$editRecord.'\'';
	if ($saveResult=@pg_query($connectPPEAO,$saveSql)) {		
		pg_free_result($saveResult);
		$valid='valid';
		
		// on construit le SQL inverse pour une �ventuelle annulation de la modification
		$undoSql='UPDATE '.$tablesDefinitions[$editTable]["table"].'
					SET '.$editColumn.'=\''.$oldValue.'\' WHERE '.$tablesDefinitions[$editTable]["id_col"].'=\''.$editRecord.'\'';
		// on inscrit la requ�te effectu�e dans le log
		logWriteTo(1,'notice','&eacute;dition r&eacute;ussie de la valeur de "'.$editColumn.'" de l\'enregistrement "'.$editRecord.'" dans la table "'.$editTable.'".',$saveSql,$undoSql,0);
	} // end if $saveResult
	// si la requ�te d'enregistrement n'a pas pu �tre effectu�e, on retourne "invalid" et un message d'erreur
	else {
		$valid="invalid";
		$errorMessage="Erreur dans l'enregistrement, consultez le journal";
		// on inscrit la requ�te non effectu�e dans le log
		logWriteTo(1,'error','erreur SQL : '.pg_last_error(),$saveSql,'',0);
	} // end else $saveResult
} // end if checkValidity

else {
	$valid="invalid";
	$errorMessage=$validityCheck["errorMessage"];
	
}


// si la valeur soumise est valide, on fait l'update sur la base et on remet le champ en mode display avec la nouvelle valeur
if ($valid=='valid') {
	
	// on r�cup�re la nouvelle valeur dans la base (moins performant mais plus s�r)
	// on construit la requ�te SQL pour obtenir le nombre total d'enregistrements dans la table
	$newValueSql='	SELECT '.$editColumn.' FROM '.$tablesDefinitions[$editTable]["table"].'
					WHERE  '.$tablesDefinitions[$editTable]["id_col"].'=\''.$editRecord.'\'';
	$newValueResult=pg_query($connectPPEAO,$newValueSql) or die('erreur dans la requete : '.$newValueSql. pg_last_error());
	$newValueRow=pg_fetch_row($newValueResult);
	$theNewValue=$newValueRow[0];
	 /* Lib�ration du r�sultat */ 
	 pg_free_result($newValueResult);
	
	//$theNewValue=iconv("ISO-8859-15", "UTF-8",$theNewValue);
	
	$response='<!--[CDATA['.makeField($cDetails,$editTable,$editColumn,$theNewValue,'display='.$editRecord,'','').']]-->';
;}
//sinon on retourne un message explicant l'erreur et on invite l'utilisateur � recommencer
else {
// on compose la r�ponse
$response='<!--[CDATA[<span>erreur : '.$errorMessage.'.</span>]]-->';
}


// on commence � cr�er la r�ponse en XML
$theXml='<?xml version="1.0"?>';
// on indique dans un attribut de la r�ponse le statut de la valeur soumise
$theXml.='<response valid="'.$valid.'">';
// on ins�re le contenu de la r�ponse
$theXml.='<responseContent>';

//$theXml.='<responseContent value="'.$validityCheck["valeur"].'">';
$theXml.=iconv('ISO-8859-15','UTF-8',$response);
$theXml.='</responseContent>';

$theXml.='</response>';

// outputting the XML response
//header("Content-Type: text/xml;");
header("Content-Type: text/xml; charset=utf-8", true);
echo($theXml);

?>