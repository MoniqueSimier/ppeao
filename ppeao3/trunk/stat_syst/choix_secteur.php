<?php 
$user="devppeao";
$passwd="2devppe!!";
$host= "vmppeao.mpl.ird.fr";
$bdd = "bourlaye_rec";

$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}



$pays_retour =''; 
$systeme_retour =''; 	

if (isset($_GET['pays'])) {$pays_retour = $_GET['pays'];}
if (isset($_GET['systeme'])) {$systeme_retour =  $_GET['systeme'];}

if (($pays_retour!='')&&($systeme_retour!='')) {
	$query="Select distinct ref_secteur.nom from ref_pays, ref_systeme, ref_secteur 
	where ref_pays.nom='".$pays_retour."' and ref_systeme.libelle='".$systeme_retour."' 
	 and ref_pays.id = ref_systeme.ref_pays_id and ref_systeme.id = ref_secteur.ref_systeme_id";
	 //print ($query);
	//print ("!!! ld1 = ".$pays_retour."  ld2= ".$systeme_retour);
	//$rq_pos_id=0;
	//$rq_pos_val=1;
	$result = pg_query($connection, $query);
	$retour = '<select name="Liste3" id="Liste3" size="1" onchange="Valide_secteur(this[this.selectedIndex].value);">';
	$retour .= '<option selected value="">Choix du secteur</option>';

	if (pg_num_rows($result) != 0) {
		// on peux choisir de ne pas le renseigner
		$retour .= '<option value="aucun">ne pas renseigner</option>';
		// sinon
		while ($row = pg_fetch_row($result)) {
			$retour .= '<option value="'. $row[0] .'">'. $row[0] .'</option>';
		}
		$retour .= '</select>';
	} else {
		$retour = '<input id="Liste3" type="text" size="10" value="Aucune valeur" disabled>';
	}
	pg_free_result($result);
	pg_close($connection);
}else{
	$retour = '<select name="Liste3" id="Liste3" size="1" disabled><option>Secteur</option></select>';
}	
echo $retour
?> 

