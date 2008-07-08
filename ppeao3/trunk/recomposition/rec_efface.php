<?
// Mis à jour Yann LAURENT, 01-07-2008
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';

$bdd = $_GET['base'];
print("<br/>travail sur la base : ".$bdd);

//////////////////////////////////////////////////////////////////////////////////////////////
//                                   EFFACEMENT DES DONNEES DE                              //
//                                     ART_DEBARQUEMENT_REC ET                              //
//                                      DE ART_FRACTION_REC                                 //
//////////////////////////////////////////////////////////////////////////////////////////////
$traitement1 = "pasok";
$traitement2 = "pasok";
$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) {  echo "pas de connection"; exit;}

$query = "delete from art_fraction_rec;";

$result = pg_exec($connection, $query);
if (!$result) {  
	echo "Attention Une erreur s'est produite lors de la suppression de art_fraction_rec "; print($query);  exit;
} else {
	$traitement1 = "ok";
}

pg_close();

$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) {  echo "Attention pas de connection"; exit;}

$query = "delete from art_debarquement_rec;";

$result = pg_exec($connection, $query);
if (!$result) {
  	echo "<br/>Attention une erreur s'est produite lors de la suppression de rt_debarquement_rec "; print($query);  
} else {
	$traitement2 = "ok";
	
}
if ( $traitement1 == "ok" &&  $traitement2 == "ok" ) {
	echo "<br/>les donn&eacute;es ont &eacute;t&eacute; supprim&eacute;es avec succ&egrave;s.";
}
pg_close();


?>
