<?php
////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                //
//                              INSERTION DES DONNEES RESULTATS                                   //
//                  CONTENUES DANS $info_deb DANS LA BASE DE DONNEES PPEAO                        //                                  //
//                      (tables art_debarquement_rec et art_fraction_rec)                         //
//                                                                                                //
////////////////////////////////////////////////////////////////////////////////////////////////////


reset($info_deb);
$numero2 = 0;
print_debug("\n\n\n**********************************\nINSERTION DES DATAS\n****************************************\n\n\n");
while (list($key, $val) = each($info_deb)){
	$numero2 = $numero2+1;
	// Remplacement print par $messageProcess YL 15.07.2008
	// print ("Insertion de l'enquête ".$numero2 . " sur ".$nb_enr ."<br/>");
	//$messageProcess.="Insertion de l'enqu&ecirc;te ".$numero2 . " sur ".$nb_enr ."<br/>";
	
	$messageProcess.="<br/><b>Recomposisiton de l'enqu&ecirc;te ".$numero2 . " sur ".$nb_enr ."</b><br/><br/>";
	
	$Wti =0;
	while (list($key2, $val2) = each($val)){
		$fr_deb =$key2;
		$Wti += $info_deb[$key][$key2][8];
	}

	
	//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) {  echo "pas de connection "; exit;}

	$query2 = "insert into art_debarquement_rec ( id, poids_total, art_debarquement_id ) 
	values ('rec_".$key."', ".$Wti.", ".$key.");";
	print_debug($query2);

	// Modification YL 15/07/2008 pour eviter les warning affichés à l'écran erreur ==> dans le log
	 //if($Wti!=0)$result2 = pg_exec($connection, $query2); // Ancienne ajout données. 
	// nouvelle insertion données en utilisant la fonction runQuery
	if($Wti!=0) {
		$messageProcess .= "".$query2."<br/>";
		$RunQErreur = runQuery($query2,$connection);
		if ( $RunQErreur){
			
		} else {
			
			$messageProcess.="<font color='blue'>Pb insertion de cette requête</font><br/>";
			// traitement d'erreur ? On arrête ou seulement avertissement ?
		
		}
	
	}

	//pg_close();

	reset($val);
	while (list($key2, $val2) = each($val)){
	//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//	if (!$connection) {  echo "pas de connection"; exit;}

		$query = "insert into art_fraction_rec ( id, poids , nbre_poissons, ref_espece_id ) 
		values ('".$key2."', ".$info_deb[$key][$key2][8].", ".$info_deb[$key][$key2][9].", '".$info_deb[$key][$key2][7]."');";
		print_debug($query);
		
		$messageProcess .= "".$query."<br/>";

		// Modification YL 15/07/2008 pour eviter les warning affichés à l'écran erreur ==> dans le log
		//$result = pg_exec($connection, $query);
		// Ancienne ajout données. 
		// nouvelle insertion données en utilisant la fonction runQuery
		$RunQErreur = runQuery($query,$connection);
		
		
		if ( $RunQErreur){
			
		} else {
			$messageProcess.="<font color='blue'>Pb insertion de cette requête</font><br/>";
			// traitement d'erreur ? On arrête ou seulement avertissement ?
		
		}

		
	} // fin while (list($key2, $val2) = each($val))
} // fin (list($key, $val) = each($info_deb))

// Ajout YL 15.07.2008 afficher le message en fin de traitement si demandé
if ($afficherMessage == "1") {
	echo $messageProcess ;
}
?>