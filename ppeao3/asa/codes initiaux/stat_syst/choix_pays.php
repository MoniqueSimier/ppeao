<?PHP

$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
$host= "vmppeao.mpl.ird.fr";	// L'h�te (ordinateur sur lequel le SGBD est install�) 
$bdd = "bourlaye_rec";


$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}
//pg_free_result($result);
$query = "select distinct nom from ref_pays";


//$rq_pos_id=0; //position dans le SQL de la cl� de la liste d�roulante idem dans ValideLd2.php et ValideLd3.php
//$rq_pos_val=1; //position dans le SQL de la valeur de la liste d�roulante idem dans ValideLd2.php et ValideLd3.php 

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