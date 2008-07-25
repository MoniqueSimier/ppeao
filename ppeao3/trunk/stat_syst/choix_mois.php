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
$annee_retour ='';

if (isset($_GET['pays'])) {$pays_retour = $_GET['pays'];}
if (isset($_GET['systeme'])) {$systeme_retour =  $_GET['systeme'];}
if (isset($_GET['secteur'])) {$secteur_retour =  $_GET['secteur'];}
if (isset($_GET['annee'])) {$annee_retour =  $_GET['annee'];}

if (($pays_retour!='')&&($systeme_retour!='')&&($secteur_retour!='')&&($annee_retour!='')) {
	$query = "select distinct mois from art_debarquement ";
	//print ("!!!".$query);
	$rq_pos_id=0;
	$rq_pos_val=1;
	$result = pg_query($connection, $query);
	$retour = '<select name="Liste5" id="Liste5" size="1" onchange="Valide_mois(this[this.selectedIndex].value);">';
	$retour .= '<option selected value="">Mois de l\'étude</option>';

	if (pg_num_rows($result) != 0) {
		// on peux choisir de ne pas le renseigner
		//$retour .= '<option value="aucun">ne pas renseigner</option>';
		// sinon
		while ($row = pg_fetch_row($result)) {
			$retour .= '<option value="'. $row[$rq_pos_id] .'">'. $row[0] .'</option>';
		}
		$retour .= '</select>';
	} else {
		$retour = '<input id="Liste5" type="text" size="10" value="Aucune valeur" disabled>';
	}
	pg_free_result($result);
	pg_close($connection);
}else{
	$retour = '<select name="Liste5" id="Liste5" size="1" disabled><option>Mois</option></select>';
}	
echo $retour
?>