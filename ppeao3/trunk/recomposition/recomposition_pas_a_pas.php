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
$nb_enr = $_GET['nb_enr'];
$bdd = $_GET['base'];
$to = $_GET['adresse'];
if($bdd==""){
	$bdd=$db_default;
}
$messageProcess .= "<br/>travail sur la base : ".$bdd ;
$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) {  echo "Non connecté"; exit;}
include_once ("functions_recomposition_generique.inc.php");
include_once ("functions_query.inc.php");

//Créationdes variables
include_once("create_array_coef_esp.php");
include_once("create_array_info_deb.php");
include_once("create_array_tailles.php");
include_once("create_array_info_non_deb.php");


//calcul et ajout des Wdft et Ndft pour chaque fraction dans le tableau recapitulatif $info_deb       
$info_deb=calcul_Wdft_Ndft_par_fraction($info_deb,$FT,$coef_esp);
	
// TRAITEMENT DES FRACTIONS DEBARQUEES Et compraison des poids               //
include_once ("recomposition_info_deb.php");
//print_debug($info_deb);
//  TRAITEMENT DES FRACTIONS NON DEBARQUEES                            Fndbq                               Et nex fractions                 //
//include_once("recomposition_info_non_deb.php");
//INSERT
insert_values_recompose($info_deb,$afficherMessage,$nb_enr);

pg_close();

/*
//envoi mail
//$to = 'f@.ird.fr';
// Subject
$subject = 'Base de données'.$_GET['base'];
// Message
$msg = 'Fin du taitement de recomposition des données';
// Headers
$headers = 'From: base_PPEAO'."\r\n";
$headers .= "\r\n";
// Function mail()
mail($to, $subject, $msg, $headers);
*/
print_debug(getTime()."ms");

?>