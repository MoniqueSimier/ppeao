<?php 
// YL 17-Sept-2008 - call to connect.inc instead replacing variables wihtin the code
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';

$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}



$pays_retour =''; 
$systeme_retour =''; 
$secteur_retour ='';

if (isset($_GET['pays'])) {$pays_retour = $_GET['pays'];}
if (isset($_GET['systeme'])) {$systeme_retour =  $_GET['systeme'];}
if (isset($_GET['secteur'])) {$secteur_retour =  $_GET['secteur'];}
//print ("!!! pays = ".$pays_retour."  systeme= ".$systeme_retour."  secteur= ".$secteur_retour);
if (($pays_retour!='')&&($systeme_retour!='')&&($secteur_retour!='')) {
	//if ($secteur_retour!=''){
	$query="Select distinct art_agglomeration.nom from ref_secteur, art_agglomeration  
	where  ref_secteur.nom ='".$secteur_retour."' 
	and ref_secteur.id = art_agglomeration.ref_secteur_id";
	
	//$rq_pos_id=0;
	//$rq_pos_val=1;
	$result = pg_query($connection, $query);
	$retour = '<select name="Liste6" id="Liste6" size="1" onchange="Valide_agglo(this[this.selectedIndex].value);">';
	$retour .= '<option selected value="">Choix du village</option>';

	if (pg_num_rows($result) != 0) {
		while ($row = pg_fetch_row($result)) {
			$retour .= '<option value="'. $row[0] .'">'. $row[0] .'</option>';
		}
		$retour .= '</select>';
		
		//rajout input text
		$retour .= '&nbsp; effort : <INPUT type="text" size="5" name="effort" >';
		
		
	} else if($secteur_retour == 'aucun')	//cas particulier, secteur non renseigné
		{
		$retour .= '<option value="aucun">non rensigné</option>';
		$retour .= '</select>&nbsp; effort : <INPUT type="text" size="5" name="effort" >';
		}
	else 
		{
		$retour = '<input id="Liste6" type="text" size="10" value="Aucune valeur" disabled>';
		}
	pg_free_result($result);
	pg_close($connection);
}else{
	$retour = '<select name="Liste6" id="Liste6" size="1" disabled><option>Village</option></select>';
}	
echo $retour
?> 
