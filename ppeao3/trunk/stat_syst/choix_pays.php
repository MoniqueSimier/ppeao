<?php 
// YL 17-Sept-2008 - call to connect.inc instead replacing variables wihtin the code
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';


$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}
//pg_free_result($result);
$query = "select distinct nom from ref_pays";


//$rq_pos_id=0; //position dans le SQL de la clé de la liste déroulante idem dans ValideLd2.php et ValideLd3.php
//$rq_pos_val=1; //position dans le SQL de la valeur de la liste déroulante idem dans ValideLd2.php et ValideLd3.php 

$result = pg_query($connection, $query);

$retour = '<select name="Liste1" id="Liste1" size="1" onchange="Valide_pays(this[this.selectedIndex].value);">';
$retour .= '<option selected value="">Choix du pays</option>';
if (pg_num_rows($result) != 0) {
	while ($row = pg_fetch_row($result)){
		//$retour .= '<option value="'. $row[$rq_pos_id] .'">'. $row[$rq_pos_val] .'</option>';
		$retour .= '<option value="'. $row[0] .'">'. $row[0] .'</option>';
		}
		$retour .= '</select>';
} else {
	$retour = '<input id="size" type="text" size="10" value="Aucune valeur" disabled>';
}
pg_free_result($result);
pg_close($connection);
echo $retour;
?>