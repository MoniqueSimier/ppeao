<?php
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';

$liste_campagnes=$_GET["campagnes"];
$campagnes_ids=explode(',',$liste_campagnes);
$liste_enquetes=$_GET["enquetes"];
$enquetes_ids=explode(',',$liste_enquetes);

$pays=$_GET["pays"];

// on cherche la liste des systemes pour lesquels existent des campagnes et des systemes
// en tenant compte de l'éventuelle preselection via les especes et les familles

//debug echo('<pre>');print_r($_GET);echo('</pre>');


// on recupere la liste des pays correspondant aux campagnes et enquetes correspondant a la selection precedente
	$sql_systemes='	SELECT DISTINCT ref_systeme.id, ref_systeme.libelle 
				FROM ref_systeme
				WHERE TRUE';
	// si on a choisi des valeurs de pays
	if (!empty($pays)) {
	$sql_systemes.=' AND ref_systeme.ref_pays_id IN (\''.arrayToList($pays,'\',\'','\'').')';
	}
	$sql_systemes.=' AND ref_systeme.id IN ';
	$sql_systemes.=' (SELECT DISTINCT exp_campagne.ref_systeme_id FROM exp_campagne WHERE TRUE ';
		// si on a deja filtre les campagnes (par especes ou familles)
		if (!empty($campagnes_ids)) {
		$sql_systemes.=' AND exp_campagne.id IN (\''.arrayToList($campagnes_ids,'\',\'','\'').')';
		}
	$sql_systemes.=') ';
		$sql_systemes.=' OR ref_systeme.id IN (
		SELECT DISTINCT art_agglomeration.ref_secteur_id 
		FROM art_agglomeration 
		WHERE art_agglomeration.id IN (
			SELECT DISTINCT art_periode_enquete.art_agglomeration_id 
			FROM art_periode_enquete 
			WHERE TRUE ';
		// si on a deja filtre les enquetes (par especes ou familles
			if (!empty($enquetes_ids)) {$sql_systemes.=' AND art_periode_enquete.id IN( 
												\''.arrayToList($enquetes_ids,'\',\'','\'').')';}
	$sql_systemes.=('))');
	
	//debug	echo($sql_systemes);
	
	$result_systemes=pg_query($connectPPEAO,$sql_systemes) or die('erreur dans la requete : '.$sql_systemes. pg_last_error());
	$array_systemes=pg_fetch_all($result_systemes);
	pg_free_result($result_systemes);
	
	
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