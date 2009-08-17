<?php
session_start();

 
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';



global $tablesDefinitions;
global $suppression_cascades;
global $connectPPEAO;




//debug sleep(10);
// quelle action est en cours?
$action=$_GET["action"];
// le domaine concerne
$domaine=$_GET["domaine"];
// l'unite concernee
$unite=$_GET["unite"];
// le niveau de dialogue modal
$level=$_GET["level"];

// si l'on veut supprimer une campagne
if ($domaine=="exp") {

// on recupere des informations sur l'unite a supprimer
$sql="SELECT DISTINCT c.id, c.numero_campagne, c.date_debut, c.date_fin, c.libelle as campagne, c.ref_systeme_id, s.libelle as systeme, s.ref_pays_id, p.nom as pays  FROM exp_campagne c, ref_systeme s, ref_pays p WHERE c.id='$unite' AND s.id=c.ref_systeme_id AND p.id=s.ref_pays_id";
$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
$resultArray=pg_fetch_all($result);
$lUnite=$resultArray[0];
pg_free_result($result);

// on recupere le nombre d'enregistrements affectes dans les diverses tables dependantes

// coup de peche
$sqlCoups='SELECT DISTINCT id,exp_environnement_id FROM exp_coup_peche WHERE exp_campagne_id=\''.$lUnite["id"].'\'';
$resultCoups=pg_query($connectPPEAO,$sqlCoups) or die('erreur dans la requete : '.$sqlCoups. pg_last_error());
$coups=pg_fetch_all($resultCoups);
pg_free_result($resultCoups);
if (!empty($coups)) {$coupsNombre=count($coups);} else {$coupsNombre=0;}

// environnement
// on extrait les listes d'id des enregistrements lies aux coups de peche a supprimer
foreach ($coups as $coup) {
	$enviro[]=$coup["exp_environnement_id"];
	$coups_id[]=$coup["id"];
}
// la liste des environnements lies aux coups de peche a supprimer
$enviroListe='\''.arrayToList($enviro,'\',\'','\'');
$sqlEnviro='SELECT DISTINCT id FROM exp_environnement WHERE id IN ('.$enviroListe.')';
$resultEnviro=pg_query($connectPPEAO,$sqlEnviro) or die('erreur dans la requete : '.$sqlEnviro. pg_last_error());
$enviro=pg_fetch_all($resultEnviro);
pg_free_result($resultEnviro);
if (!empty($enviro)) {$enviroNombre=count($enviro);} else {$enviroNombre=0;}

// fractions
// la liste des id des coups de peche a supprimer
$coupsListe='\''.arrayToList($coups_id,'\',\'','\'');
$sqlFraction='SELECT DISTINCT id FROM exp_fraction WHERE exp_coup_peche_id IN ('.$coupsListe.')';
$resultFraction=pg_query($connectPPEAO,$sqlFraction) or die('erreur dans la requete : '.$sqlFraction. pg_last_error());
$fractions=pg_fetch_all($resultFraction);
pg_free_result($resultFraction);
if (!empty($fractions)) {$fractionNombre=count($fractions);} else {$fractionNombre=0;}


// biologie
// on extrait les listes d'id des fractions lies aux coups de peche a supprimer
foreach ($fractions as $fraction) {
	$fractions_id[]=$fraction["id"];
}
// la liste des id des fraction a supprimer
$fractionsListe='\''.arrayToList($fractions_id,'\',\'','\'');
$sqlBio='SELECT DISTINCT id FROM exp_biologie WHERE exp_fraction_id IN ('.$fractionsListe.')';
$resultBio=pg_query($connectPPEAO,$sqlBio) or die('erreur dans la requete : '.$sqlBio. pg_last_error());
$biologies=pg_fetch_all($resultBio);
pg_free_result($resultBio);
$biologieNombre=count($biologies);
if (!empty($biologies)) {$biologieNombre=count($biologies);} else {$biologieNombre=0;}

// trophique
// on extrait les listes d'id des biologie lies aux coups de peche a supprimer
foreach ($biologies as $biologie) {
	$biologies_id[]=$biologie["id"];
}
// la liste des id des biologies a supprimer
$biologiesListe='\''.arrayToList($biologies_id,'\',\'','\'');
$sqlTrophique='SELECT DISTINCT id FROM exp_trophique WHERE exp_biologie_id IN ('.$biologiesListe.')';
$resultTrophique=pg_query($connectPPEAO,$sqlTrophique) or die('erreur dans la requete : '.$sqlTrophique. pg_last_error());
$trophiques=pg_fetch_all($resultTrophique);
pg_free_result($resultTrophique);
$trophiqueNombre=count($trophiques);
if (!empty($trophiques)) {$trophiqueNombre=count($trophiques);} else {$trophiqueNombre=0;}
// on extrait les listes d'id des trophiques lies aux coups de peche a supprimer
foreach ($trophiques as $trophique) {
	$trophiques_id[]=$trophique["id"];
}
// la liste des id des trophiques a supprimer
$trophiquesListe='\''.arrayToList($trophiques_id,'\',\'','\'');

// on compose le message a afficher et on realise les actions a faire
$theMessage="<div>";
// si on en est au stade de la confirmation de la suppression
if ($action=="ask") {
	$theMessage.='<h1 align="center" id="delete_title">supprimer la campagne suivante?</h1>';
	$theMessage.='<h2>'.$lUnite["campagne"].'</h2>';
	$theMessage.='<h3>pays : '.$lUnite["pays"].'</h3>';
	$theMessage.='<h3>systeme : '.$lUnite["systeme"].'</h3>';
	$theMessage.='<h3>num&eacute;ro : '.$lUnite["numero_campagne"].'</h3>';
	$theMessage.='<br /><h2>cela supprimera &eacute;galement : </h2>';
	$theMessage.='<ul>';
	$theMessage.='<li>'.$coupsNombre.' coup(s) de p&ecirc;che</li>';
	$theMessage.='<li>'.$enviroNombre.' environnement(s)</li>';
	$theMessage.='<li>'.$biologieNombre.' enregistrement(s) de biologie</li>';
	$theMessage.='<li>'.$fractionNombre.' fraction(s)</li>';
	$theMessage.='<li>'.$trophiqueNombre.' enregistrement(s) trophique(s)</li>';

} // fin de if $action==ask
// si on en est au stade de la suppression effective
if ($action=="delete") {

// on realise les divers DELETE selon les listes d'id obtenues plus haut

// trophique
$sqlDelete='DELETE FROM exp_trophique WHERE id IN ('.$trophiquesListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);
// biologie
$sqlDelete='DELETE FROM exp_biologie WHERE id IN ('.$biologiesListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);
// fraction
$sqlDelete='DELETE FROM exp_fraction WHERE id IN ('.$fractionsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);
// environnement
$sqlDelete='DELETE FROM exp_fraction WHERE id IN ('.$enviroListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);
// coups de peche
$sqlDelete='DELETE FROM exp_coup_peche WHERE id IN ('.$coupsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);
// campagne
$sqlDelete='DELETE FROM exp_campagne WHERE id=\''.$lUnite["id"].'\'';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);


$theMessage.='<h1 align="center" id="delete_title">suppression de la campagne :</h1>';
	$theMessage.='<h2>'.$lUnite["campagne"].'</h2>';
	$theMessage.='<h3>pays : '.$lUnite["pays"].'</h3>';
	$theMessage.='<h3>systeme : '.$lUnite["systeme"].'</h3>';
	$theMessage.='<h3>num&eacute;ro : '.$lUnite["numero_campagne"].'</h3>';
	$theMessage.='<br /><h2>enregistrements d&eacute;pendants supprim&eacute;s : </h2>';
	$theMessage.='<ul>';
	$theMessage.='<li>'.$coupsNombre.' coup(s) de p&ecirc;che</li>';
	$theMessage.='<li>'.$enviroNombre.' environnement(s)</li>';
	$theMessage.='<li>'.$biologieNombre.' enregistrement(s) de biologie</li>';
	$theMessage.='<li>'.$fractionNombre.' fraction(s)</li>';
	$theMessage.='<li>'.$trophiqueNombre.' enregistrement(s) trophique(s)</li>';

} // fin de if ($action=="delete")

$theMessage.='</ul></div>';


} // fin de if $domaine==exp


// on renvoie le message de résultat
echo($theMessage);
?>