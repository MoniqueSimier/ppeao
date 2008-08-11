<?php 
//*****************************************
// processAuto.php
//*****************************************
// Created by Yann Laurent
// 2008-07-15 : creation
//*****************************************
// Ce programme lance les processus automatiques de recomposition de données


// Variable de test
$pasdetraitement = false;

// Includes standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';


$traitRecompOk = false;
$CRexecution = "";


if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine complète de traitement automatique (saute cette etape)



	// le processus de recomposition des données
	include $_SERVER["DOCUMENT_ROOT"].'/recomposition/recomposition.php';
	$messageinfo = "";
	$query = "select count(id) FROM art_debarquement_rec";
	$result = pg_query($connectPPEAO, $query);
	$row= pg_fetch_row($result);
	$nb_deja_rec = $row[0];
	if ($nb_deja_rec == 0){
		$messageinfo = " pas d'enqu&ecirc;te recompos&eacute;e. ";
	} else {
		$traitRecompOk = true;
		$messageinfo = $nb_deja_rec . " enqu&ecirc;te(s) recompos&eacute;e(s). ";
	}

	if ($traitRecompOk) {
				// Traitement OK
			echo "<div id=\"processAuto_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"processAuto_txt\">Processus de recomposition des donn&eacute;es ex&eacute;cut&eacute; avec succ&egrave;s : ".$messageinfo."</div>" ;	
		} else {
			// Erreur dans le traitement
			echo "<div id=\"processAuto_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"processAuto_txt\">Erreur dans le processus de recomposition des donn&eacute;es (".$messageinfo.")</div>" ;
	}
	// Le processus de recalcul des stats



} else {
	echo "<div id=\"processAuto_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"processAuto_txt\">En Test Etape du process automatique des donn&eacute;es non ex&eacute;cut&eacute;e (var pasdetraitement = true)</div>" ;
} // end if (! $pasdetraitement )
exit;

?>