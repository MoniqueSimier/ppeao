<?php
session_start();

// script appel� via Ajax par la fonction javascript sendRecordToSave() (fichier edition.js)
// permettant de valider les valeurs saisies lors de l'ajout d'un nouvel enregistrement
// 
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_ppeao.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';


global $tablesDefinitions;


// la table concern�e
$table=$_GET["table"];
$level=$_GET["level"];

	//debug 		echo('<pre>');print_r($_GET);echo('</pre>');


// on compile les informations sur les colonnes de la table $editTable
$cDetails=getTableColumnsDetails($connectPPEAO,$tablesDefinitions[$table]["table"]);
// la liste des colonnes concern�es
$theColumns=array_keys($cDetails);
// on suppose que toutes les valeurs sont valides
$valid='valid';
// on teste les valeurs pass�es dans l'URL pour chaque colonne
foreach ($theColumns as $oneColumn) {
	
	$cDetail=$cDetails[$oneColumn];
	$newValue=$_GET["add_record_".$level.'_'.$oneColumn];
	
	
	// cas d'une colonne stockant le mot de passe d'un nouvel utilisateur
	// on stocke la valeur ENCRYPTEE au moyen du user name
	if ($oneColumn=="user_password") {
		$cDetail["data_type"]="password";
		// on utilise les deux premiers caract�res du username comme "salt"
		$username=$_GET["add_record_".$level.'_user_name'];
		$salt=substr($username,0,2);
		//on encrypte le mot de passe soumis avant de le stocker
		$newValue=crypt($newValue,$salt);
	}
	
	
	// si la valeur pass�e est NULL 
	if (is_null($newValue) || my_empty($newValue) || $newValue=='NULL') {
		// et que l'on a affaire a une cle etrangere
		$reference=getTableColumnForeignReference($connectPPEAO,$tablesDefinitions[$table]["table"],$oneColumn);
		//debug 		echo($oneColumn.'<pre>');print_r($reference);echo('</pre>');
		
		if ($reference["is_foreign"]) {
			//debug			echo("XXXXXXXX");
			// alors on essaie de voir si la cle referencee a un enregistrement
			// dont l'id est 0 et le label "aucun" ou "aucune"
			if ($cDetail["data_type"]=='integer') {$clause='0';} else {$clause='\'0\'';}
			$refTable=$reference["references_table"];
			$refAlias=getTableAliasFromName($refTable);
			$refId=$reference["references_field"];
			$refLabel=$tablesDefinitions[$refAlias]["noms_col"];
			$sql='SELECT count(*) FROM '.$refTable.' WHERE '.$refId.'='.$clause.' AND ('.$refLabel.'=\'aucun\' OR '.$refLabel.'=\'aucune\')';
			//debug			echo($sql);
			$result=@pg_query($connectPPEAO,$sql);
			$array=@pg_fetch_array($result);
			@pg_free_result($result);
			// si on a un tel enregistrement, alors on le passe a la place de la valeur NULL
			if (!empty($array) && $array[0]>0) {
				$newValue=0;
			} // end if (!empty($array) && $array[0]>0) 
			
		} // end if ($reference["is_foreign"])
	} // end if (is_null($newValue) || my_empty($newValue))
	
	
	// on "nettoie" la valeur saisie
	// si la valeur doit �tre un r�el, on commence par convertir une �ventuelle saisie au format d�cimal "," au lieu de "."
	if ($cDetail["data_type"]=='real') {
		$newValue=str_replace(',','.',$newValue);
	}
	// on "echappe" les caracteres speciaux
	$newValue=addslashes($newValue);
	// on teste la validit� de la valeur saisie
	// si le champ est g�r� par une s�quence
	if (getTableColumnSequence($connectPPEAO,$tablesDefinitions[$table]["table"],$oneColumn)) {
		
		$validityCheck[$oneColumn]=array("validity"=>1,"errorMessage"=>"","sequence"=>"sequence");
	}
	else {
		// on compile la liste des colonnes dont il faudra passer la valeur lors de l'INSERT (toutes sauf celles avec une s�quence)
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


// si toutes les valeurs sont valides, on fait l'INSERT sur la base...
if ($valid=='valid') {
	$addSql='	INSERT INTO '.$tablesDefinitions[$table]["table"].'
				('.arrayToList($theInsertKeys,',','').')
				VALUES ('.arrayToList($newValues,',','').')';
	//debug 	echo($addSql);
	if($addResult=pg_query($connectPPEAO,$addSql)) {
	// et on renvoie un message positif
	$message='<!--[CDATA[Enregistrement ajout&eacute; dans la table '.$table.']]-->';
	
	// on inscrit l'ajout dans le journal
	logWriteTo(1,'notice','ajout r&eacute;ussi d\'un enregistrement dans la table "'.$tablesDefinitions[$table]["table"].'"',$addSql,'',0);
	}
	
	else {
		$message= '<!--[CDATA[Une erreur est survenue lors de l\'enregistrement la table '.$table.' : '.pg_last_error().']]-->';
		$valid='invalid';
	}
}
else {$message='';}



// on commence � cr�er la r�ponse en XML
$theXml='<?xml version="1.0"?>';
// on indique dans un attribut de la r�ponse le statut global de la saisie
$theXml.='<response validity="'.$valid.'" table="'.$table.'">';

//on cr�e un �l�ment XML pour chaque colonne
foreach ($theColumns as $oneColumn) { 

	// si on a affaire � une colonne � sequence, on le signale
	if ($validityCheck[$oneColumn]["sequence"]=="sequence") {$sequence='sequence="sequence"';} else {$sequence='sequence="no"';}

	// on ins�re le contenu de la r�ponse pour cette colonne
	$theXml.='<responseContent key="'.$oneColumn.'" valid="'.$validityCheck[$oneColumn]["validity"].'" '.$sequence.'>';
	
	// on compose la r�ponse selon que la valeur est valide ou pas
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