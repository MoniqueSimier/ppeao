<?PHP

$user="devppeao";
$passwd="2devppe!!";
$host= "vmppeao.mpl.ird.fr";
$bdd = "bourlaye_rec";

$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}


$pays_retour =''; 
$systeme_retour =''; 
$secteur_retour ='';

if (isset($_GET['pays'])) {$pays_retour = $_GET['pays'];}
if (isset($_GET['systeme'])) {$systeme_retour =  $_GET['systeme'];}
if (isset($_GET['secteur'])) {$secteur_retour =  $_GET['secteur'];}

if (($pays_retour!='')&&($systeme_retour!='')&&($secteur_retour!='')) {
	$query = "select distinct annee from art_debarquement";
	//print ("!!!".$query);
	$rq_pos_id=0;
	$rq_pos_val=1;
	$result = pg_query($connection, $query);
	$retour = '<select name="Liste4" id="Liste4" size="1" onchange="Valide_annee(this[this.selectedIndex].value);">';
	$retour .= '<option selected value="">Année d\'etude</option>';

	if (pg_num_rows($result) != 0) {
		while ($row = pg_fetch_row($result)) {
			$retour .= '<option value="'. $row[$rq_pos_id] .'">'. $row[0] .'</option>';
		}
		$retour .= '</select>';
	} else {
		$retour = '<input id="Liste4" type="text" size="10" value="Aucune valeur" disabled>';
	}
	pg_free_result($result);
	pg_close($connection);
}else{
	$retour = '<select name="Liste4" id="Liste4" size="1" disabled><option>année</option></select>';
}	
echo $retour
?>