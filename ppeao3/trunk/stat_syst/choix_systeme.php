<?php 
// YL 17-Sept-2008 - call to connect.inc instead replacing variables wihtin the code
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';

$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}





$pays_retour ='';

if (isset($_GET['pays'])) {$pays_retour = $_GET['pays'];}
//print ("!!!".$query);
if ($pays_retour!='') {
	$query = "select distinct ref_systeme.libelle from ref_pays, ref_systeme where ref_pays.nom='".$pays_retour."' and ref_pays.id = ref_systeme.ref_pays_id";
	//print ("!!!".$query);
	//$rq_pos_id=0;
	//$rq_pos_val=1;
	$result = pg_query($connection, $query);
	$retour = '<select name="Liste2" id="Liste2" size="1" onchange="Valide_systeme(this[this.selectedIndex].value);">';
	$retour .= '<option selected value="">Choix du système</option>';

	if (pg_num_rows($result) != 0) {
		while ($row = pg_fetch_row($result)) {
			$retour .= '<option value="'. $row[0] .'">'. $row[0] .'</option>';
		}
		$retour .= '</select>';
	} else {
		$retour = '<input id="Liste2" type="text" size="10" value="Aucune valeur" disabled>';
	}
	pg_free_result($result);
	pg_close($connection);
}else{
	$retour = '<select name="Liste2" id="Liste2" size="1" disabled><option>systeme</option></select>';
}	
echo $retour
?>