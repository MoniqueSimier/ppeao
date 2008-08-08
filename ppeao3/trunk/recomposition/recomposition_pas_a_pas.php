<?php
getTime();

//***********************************************
// Création Yann LAURENT, 01-07-2008
// A partir du fichier php initial du lot 2 PPEAO
//***********************************************

// YL 15/07/2008 on remplace les messages direct par une variable qu'on affiche ou non en fin de traitement
$messageProcess = "Debut programme <br/>" ;
//print "GEt ==== ".print_r($_GET);
//print "<br/>";


//echo "debut programme";
// Variables pour affichage ou non des messages
if (isset($_GET['aff'])) {
	$afficherMessage = $_GET['aff'] ;
} else {
	$afficherMessage = "0" ;
}

set_time_limit(300000);


$nb_enr = $_GET['nb_enr'];
$bdd = $_GET['base'];
$to = $_GET['adresse'];
if($bdd==""){
	$bdd=$db_default;
}

$messageProcess .= "<br/>travail sur la base : ".$bdd ;
//print("<br/>travail sur la base : ".$bdd);



include_once("create_array_coef_esp.php");
include_once("create_array_info_deb.php");
include_once("create_array_tailles.php");

include_once("calcul_Wdft_Ndft.php");

include_once ("recomposition_info_deb.php");
include_once("comparaison_WT_WFdbq.php");



////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                //
//                            TRAITEMENT DES FRACTIONS NON DEBARQUEES                             //
//                                           Fndbq                                                //
//                                                                                                //
////////////////////////////////////////////////////////////////////////////////////////////////////

include_once("create_array_info_non_deb.php");

include_once("recomposition_info_non_deb.php");
//remise à zéro du pointeur

include_once("create_new_fraction.php");


include_once("insert_results.php");

pg_close();

//envoie mail confirm
// To
//$to = 'fauchier@mpl.ird.fr';
// Subject
$subject = 'Base de données'.$_GET['base'];
// Message
$msg = 'Fin du taitement de recomposition des données';
// Headers
$headers = 'From: base_PPEAO'."\r\n";
$headers .= "\r\n";
// Function mail()
 mail($to, $subject, $msg, $headers);

print_debug(getTime()."ms");

?>