<?php
session_start();

// script appelé par la fonction javascript updateEditSelects
// 
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_PPEAO.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';


global $tablesDefinitions;


$theCascade=$_GET["theCascade"];
$theLevel=$_GET["theLevel"];
$parentTable=$_GET["theTable"];
$parentKey=$_GET["theKey"];
$parentValue=$_GET["theKeyValue"];


$cascade=explode(',',$theCascade);

// le select que l'on doit rafraichir et filtrer
$thisTableAlias=$cascade[$theLevel+1];
//debug echo('XXXXXXX'.$thisTableAlias);

// la table correspondante
$theTable=$tablesDefinitions[$thisTableAlias]["table"];


//debug echo('xxxxxx'.$theTable);

// on determine la colonne qui utilise la table et la colonne passées en parametres comme cle etrangere
$cd=getTableConstraintDetails($connectPPEAO,$theTable);

//debug echo('<pre>');print_r($cd);echo('</pre>');


foreach($cd as $c) {
	if ($c["references_table"]==$parentTable && $c["references_field"]==$parentKey) {$theForeignKey=$c["column_name"];}
}


// on construit la requete pour recuperer les valeurs du nouveau select 
// filtrees en fonction de la valeur choisie dans le select precedent

$theKeys=$tablesDefinitions[$thisTableAlias]["id_col"];
$theLabels=$tablesDefinitions[$thisTableAlias]["noms_col"];


$sql=	'SELECT '.$theKeys.' as val, '.$theLabels.' as lab
		FROM '.$theTable.'
		WHERE '.$theForeignKey.'=\''.$parentValue.'\'
		ORDER BY '.$theLabels.'';

//debug echo($sql);

$result=pg_query($connectPPEAO,$sql) or die();
$resultArray=pg_fetch_all($result);
pg_free_result($result);
			

//debug echo('<pre>');print_r($resultArray);echo('</pre>');

// on construit la liste des options à retourner 
// si il n'y a pas de résultats
if (empty($resultArray)) {
	$options='<option value="NULL">- pas de '.iconv('ISO-8859-15','UTF-8',$tablesDefinitions[$thisTableAlias]["label"]).' -</option>';
// si il y a des résultats
} else {
	$options='<option value="NULL">- choisir '.iconv('ISO-8859-15','UTF-8',$tablesDefinitions[$thisTableAlias]["label"]).' -</option>';
	foreach($resultArray as $option) {
		$options.='<option value="'.$option["val"].'">'.$option["lab"].'</option>';
		}
}
//debug echo($options);

echo($options);

?>