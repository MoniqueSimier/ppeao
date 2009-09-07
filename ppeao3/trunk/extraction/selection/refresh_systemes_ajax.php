<?php
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/selection/selection_functions.php';

$liste_campagnes=$_GET["campagnes"];
$campagnes_ids=explode(',',$liste_campagnes);
$liste_enquetes=$_GET["enquetes"];
$enquetes_ids=explode(',',$liste_enquetes);

$pays=$_GET["pays"];

// on cherche la liste des systemes pour lesquels existent des campagnes et des systemes
// en tenant compte de l'éventuelle preselection via les especes, les familles et les pays

	
	$array_systemes=listSelectSystemes($pays,$campagnes_ids,$enquetes_ids);
	
	// on genere la liste des <options> pour raffraichir le <select>
	$options='';
	// si il n'y a pas de valeurs correspondantes, on renvoie un message
	if (empty($array_systemes)) {
		$options='<option value="">aucun syst&egrave;me</option>';
	}  // end if empty
	else {
		foreach ($array_systemes as $systeme) {
		$options.='<option value="'.$systeme["id"].'">'.$systeme["libelle"].'</option>';
		}
	} // end else


echo($options);

?>