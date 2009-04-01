<?php

// parametres de connexion a la base de donnees
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';


//debug sleep (10);

// on recupere l'action de maintenance a realiser
$action=$_GET["action"];
// on suppose que l'action a ete realisee avec succes
$success=true;
$operation="";
$erreurSQL="";
switch ($action) {
	case 'sequences_ref_param':
		$operation="de mise-&agrave;-jour des s&eacute;quences des tables de r&eacute;f&eacute;rence et de param&eacute;trage";

		// on sélectionne les sequences, leurs tables et leurs colonnes
		// pour les tables de ref (type_table_id=2) et de param (type_table_id=3)
		$sql='	SELECT ads.sequence_name, ads.column_name, ads.table_db
				FROM admin_sequences ads, admin_dictionary_tables addt 
				WHERE (ads.table_db=addt.table_db) AND (addt.type_table_id=2 OR addt.type_table_id=3)';
		$result=pg_query($connectPPEAO,$sql) or  die('erreur dans la requete : '.$sql. pg_last_error());;
		$seqArray=pg_fetch_all($result);
		//debug 			echo('<pre>');print_r($seqArray);echo('</pre>');
		
		// on boucle sur chaque sequence `
		foreach ($seqArray as $seq) {
			// on recupere la plus grande valeur de la colonne correspondant a la sequence
			$sqlMax='	SELECT max('.$seq["column_name"].') as maxval
						FROM '.$seq["table_db"].'
						';
			//debug 			echo($sqlMax);
			$resultMax=pg_query($connectPPEAO,$sqlMax);
			$maxArray=pg_fetch_row($resultMax);
			$maxVal=$maxArray[0];
			
			// on met a jour la valeur maximale de la sequence concernee
			$sqlUpdate='SELECT pg_catalog.setval(\''.$seq["sequence_name"].'\','.$maxVal.',true);';
			if ($resultUpdate=pg_query($connectPPEAO,$sqlUpdate)) {$ok=true;} else {$success=false;}			
			
		}
		
		
	break;
	case 'sequences_donnees':
		$operation="de mise-&agrave;-jour des s&eacute;quences des tables de donn&eacute;es";
		// on sélectionne les sequences, leurs tables et leurs colonnes
		// pour les tables de donnees (type_table_id=4)
		$sql='	SELECT ads.sequence_name, ads.column_name, ads.table_db 
				FROM admin_sequences ads, admin_dictionary_tables addt 
				WHERE (ads.table_db=addt.table_db) AND (addt.type_table_id=4)';
		$result=pg_query($connectPPEAO,$sql);
		$seqArray=pg_fetch_all($result);
		//debug 		echo('<pre>');print_r($seqArray);echo('</pre>');
		
		// on boucle sur chaque sequence `
		foreach ($seqArray as $seq) {
			// on recupere la plus grande valeur de la colonne correspondant a la sequence
			$sqlMax='	SELECT max('.$seq["column_name"].') as maxval
						FROM '.$seq["table_db"].'
						';
			$resultMax=pg_query($connectPPEAO,$sqlMax);
			$maxArray=pg_fetch_row($resultMax);
			$maxVal=$maxArray[0];
			
			// on met a jour la valeur maximale de la sequence concernee
			$sqlUpdate='SELECT pg_catalog.setval(\''.$seq["sequence_name"].'\','.$maxVal.',true);';
			if ($resultUpdate=pg_query($connectPPEAO,$sqlUpdate)) {$ok=true;} else {$success=false;}
		}

	break;
	case 'vacuum':
		$sql='VACUUM ANALYZE';
		if ($result=pg_query($connectPPEAO,$sql)) {$success=true;} else {$success=false;}
		$operation="&quot;VACUUM ANALYSE&quot;";
	break;
	case 'reindex':
		$sql='REINDEX DATABASE '.$base_principale.'';
		if ($result=pg_query($connectPPEAO,$sql)) {$success=true;} else {$success=false;}
		$operation="de r&eacute;indexation de la base";
	break;
	case 'disable_trigger':
		$operation=" de suppression des contraintes";
		$connectBDPECHE =pg_connect ("host=".$host." dbname=".$base_portage." user=".$user." password=".$passwd);
		if ($connectBDPECHE) {$success=true;} else {$success=false;$erreurSQL ="(erreur = erreur connexion bdpeche)";}
		if ($success) {
			$sql="ALTER TABLE ref_categorie_ecologique DISABLE TRIGGER ALL;
				ALTER TABLE ref_categorie_trophique DISABLE TRIGGER ALL;
				ALTER TABLE ref_espece DISABLE TRIGGER ALL;
				ALTER TABLE ref_famille DISABLE TRIGGER ALL;
				ALTER TABLE ref_ordre DISABLE TRIGGER ALL;
				ALTER TABLE ref_origine_kb DISABLE TRIGGER ALL;
				ALTER TABLE ref_pays DISABLE TRIGGER ALL;
				ALTER TABLE ref_secteur DISABLE TRIGGER ALL;
				ALTER TABLE ref_systeme DISABLE TRIGGER ALL;
				ALTER TABLE exp_contenu DISABLE TRIGGER ALL;
				ALTER TABLE exp_debris DISABLE TRIGGER ALL;
				ALTER TABLE exp_engin DISABLE TRIGGER ALL;
				ALTER TABLE exp_force_courant DISABLE TRIGGER ALL;
				ALTER TABLE exp_position DISABLE TRIGGER ALL;
				ALTER TABLE exp_qualite DISABLE TRIGGER ALL;
				ALTER TABLE exp_remplissage DISABLE TRIGGER ALL;
				ALTER TABLE exp_sediment DISABLE TRIGGER ALL;
				ALTER TABLE exp_sens_courant DISABLE TRIGGER ALL;
				ALTER TABLE exp_sexe DISABLE TRIGGER ALL;
				ALTER TABLE exp_stade DISABLE TRIGGER ALL;
				ALTER TABLE exp_station DISABLE TRIGGER ALL;
				ALTER TABLE exp_vegetation DISABLE TRIGGER ALL;
				ALTER TABLE exp_contenu DISABLE TRIGGER ALL;
				ALTER TABLE exp_debris DISABLE TRIGGER ALL;
				ALTER TABLE exp_engin DISABLE TRIGGER ALL;
				ALTER TABLE exp_force_courant DISABLE TRIGGER ALL;
				ALTER TABLE exp_position DISABLE TRIGGER ALL;
				ALTER TABLE exp_qualite DISABLE TRIGGER ALL;
				ALTER TABLE exp_remplissage DISABLE TRIGGER ALL;
				ALTER TABLE exp_sediment DISABLE TRIGGER ALL;
				ALTER TABLE exp_sens_courant DISABLE TRIGGER ALL;
				ALTER TABLE exp_sexe DISABLE TRIGGER ALL;
				ALTER TABLE exp_stade DISABLE TRIGGER ALL;
				ALTER TABLE exp_station DISABLE TRIGGER ALL;
				ALTER TABLE exp_vegetation DISABLE TRIGGER ALL;
				ALTER TABLE art_agglomeration DISABLE TRIGGER ALL;
				ALTER TABLE art_categorie_socio_professionnelle DISABLE TRIGGER ALL;
				ALTER TABLE art_etat_ciel DISABLE TRIGGER ALL;
				ALTER TABLE art_grand_type_engin DISABLE TRIGGER ALL;
				ALTER TABLE art_millieu DISABLE TRIGGER ALL;
				ALTER TABLE art_type_activite DISABLE TRIGGER ALL;
				ALTER TABLE art_type_agglomeration DISABLE TRIGGER ALL;
				ALTER TABLE art_type_engin DISABLE TRIGGER ALL;
				ALTER TABLE art_type_sortie DISABLE TRIGGER ALL;
				ALTER TABLE art_vent DISABLE TRIGGER ALL;
				ALTER TABLE exp_biologie DISABLE TRIGGER ALL;
				ALTER TABLE exp_campagne DISABLE TRIGGER ALL;
				ALTER TABLE exp_coup_peche DISABLE TRIGGER ALL;
				ALTER TABLE exp_environnement DISABLE TRIGGER ALL;
				ALTER TABLE exp_fraction DISABLE TRIGGER ALL;
				ALTER TABLE exp_trophique DISABLE TRIGGER ALL;
				ALTER TABLE art_activite DISABLE TRIGGER ALL;
				ALTER TABLE art_debarquement DISABLE TRIGGER ALL;
				ALTER TABLE art_engin_activite DISABLE TRIGGER ALL;
				ALTER TABLE art_engin_peche DISABLE TRIGGER ALL;
				ALTER TABLE art_fraction DISABLE TRIGGER ALL;
				ALTER TABLE art_lieu_de_peche DISABLE TRIGGER ALL;
				ALTER TABLE art_poisson_mesure DISABLE TRIGGER ALL;
				ALTER TABLE art_unite_peche DISABLE TRIGGER ALL;
				ALTER TABLE art_debarquement_rec DISABLE TRIGGER ALL;
				ALTER TABLE art_fraction_rec DISABLE TRIGGER ALL;
				ALTER TABLE art_stat_gt DISABLE TRIGGER ALL;
				ALTER TABLE art_stat_gt_sp DISABLE TRIGGER ALL;
				ALTER TABLE art_stat_sp DISABLE TRIGGER ALL;
				ALTER TABLE art_stat_totale DISABLE TRIGGER ALL;
				ALTER TABLE art_taille_gt_sp DISABLE TRIGGER ALL;
				ALTER TABLE art_taille_sp DISABLE TRIGGER ALL;";
			$result = pg_query($connectBDPECHE,$sql);
			$erreurSQL = pg_last_error($connectBDPECHE);
			$erreurSQL = "(<b>erreur </b>= ".$erreurSQL.")";
			if (!$result) {
				$success=false;
			} else {
				$success=true;
			}
		}// fin du if ($success)
	break;
	case 'enable_trigger':
			$operation=" d''activation des contraintes";
			$connectBDPECHE =pg_connect ("host=".$host." dbname=".$base_portage." user=".$user." password=".$passwd);
			if ($connectBDPECHE) {$success=true;} else {$success=false;$erreurSQL ="(erreur = erreur connexion bdpeche)";}
			if ($success) {
				$sql="
					ALTER TABLE ref_categorie_ecologique ENABLE  TRIGGER ALL;
					ALTER TABLE ref_categorie_trophique ENABLE  TRIGGER ALL;
					ALTER TABLE ref_espece ENABLE  TRIGGER ALL;
					ALTER TABLE ref_famille ENABLE  TRIGGER ALL;
					ALTER TABLE ref_ordre ENABLE  TRIGGER ALL;
					ALTER TABLE ref_origine_kb ENABLE  TRIGGER ALL;
					ALTER TABLE ref_pays ENABLE  TRIGGER ALL;
					ALTER TABLE ref_secteur ENABLE  TRIGGER ALL;
					ALTER TABLE ref_systeme ENABLE  TRIGGER ALL;
					ALTER TABLE exp_contenu ENABLE  TRIGGER ALL;
					ALTER TABLE exp_debris ENABLE  TRIGGER ALL;
					ALTER TABLE exp_engin ENABLE  TRIGGER ALL;
					ALTER TABLE exp_force_courant ENABLE  TRIGGER ALL;
					ALTER TABLE exp_position ENABLE  TRIGGER ALL;
					ALTER TABLE exp_qualite ENABLE  TRIGGER ALL;
					ALTER TABLE exp_remplissage ENABLE  TRIGGER ALL;
					ALTER TABLE exp_sediment ENABLE  TRIGGER ALL;
					ALTER TABLE exp_sens_courant ENABLE  TRIGGER ALL;
					ALTER TABLE exp_sexe ENABLE  TRIGGER ALL;
					ALTER TABLE exp_stade ENABLE  TRIGGER ALL;
					ALTER TABLE exp_station ENABLE  TRIGGER ALL;
					ALTER TABLE exp_vegetation ENABLE  TRIGGER ALL;
					ALTER TABLE exp_contenu ENABLE  TRIGGER ALL;
					ALTER TABLE exp_debris ENABLE  TRIGGER ALL;
					ALTER TABLE exp_engin ENABLE  TRIGGER ALL;
					ALTER TABLE exp_force_courant ENABLE  TRIGGER ALL;
					ALTER TABLE exp_position ENABLE  TRIGGER ALL;
					ALTER TABLE exp_qualite ENABLE  TRIGGER ALL;
					ALTER TABLE exp_remplissage ENABLE  TRIGGER ALL;
					ALTER TABLE exp_sediment ENABLE  TRIGGER ALL;
					ALTER TABLE exp_sens_courant ENABLE  TRIGGER ALL;
					ALTER TABLE exp_sexe ENABLE  TRIGGER ALL;
					ALTER TABLE exp_stade ENABLE  TRIGGER ALL;
					ALTER TABLE exp_station ENABLE  TRIGGER ALL;
					ALTER TABLE exp_vegetation ENABLE  TRIGGER ALL;
					ALTER TABLE art_agglomeration ENABLE  TRIGGER ALL;
					ALTER TABLE art_categorie_socio_professionnelle ENABLE  TRIGGER ALL;
					ALTER TABLE art_etat_ciel ENABLE  TRIGGER ALL;
					ALTER TABLE art_grand_type_engin ENABLE  TRIGGER ALL;
					ALTER TABLE art_millieu ENABLE  TRIGGER ALL;
					ALTER TABLE art_type_activite ENABLE  TRIGGER ALL;
					ALTER TABLE art_type_agglomeration ENABLE  TRIGGER ALL;
					ALTER TABLE art_type_engin ENABLE  TRIGGER ALL;
					ALTER TABLE art_type_sortie ENABLE  TRIGGER ALL;
					ALTER TABLE art_vent ENABLE  TRIGGER ALL;
					ALTER TABLE exp_biologie ENABLE  TRIGGER ALL;
					ALTER TABLE exp_campagne ENABLE  TRIGGER ALL;
					ALTER TABLE exp_coup_peche ENABLE  TRIGGER ALL;
					ALTER TABLE exp_environnement ENABLE  TRIGGER ALL;
					ALTER TABLE exp_fraction ENABLE  TRIGGER ALL;
					ALTER TABLE exp_trophique ENABLE  TRIGGER ALL;
					ALTER TABLE art_activite ENABLE  TRIGGER ALL;
					ALTER TABLE art_debarquement ENABLE  TRIGGER ALL;
					ALTER TABLE art_engin_activite ENABLE  TRIGGER ALL;
					ALTER TABLE art_engin_peche ENABLE  TRIGGER ALL;
					ALTER TABLE art_fraction ENABLE  TRIGGER ALL;
					ALTER TABLE art_lieu_de_peche ENABLE  TRIGGER ALL;
					ALTER TABLE art_poisson_mesure ENABLE  TRIGGER ALL;
					ALTER TABLE art_unite_peche ENABLE  TRIGGER ALL;
					ALTER TABLE art_debarquement_rec ENABLE  TRIGGER ALL;
					ALTER TABLE art_fraction_rec ENABLE  TRIGGER ALL;
					ALTER TABLE art_stat_gt ENABLE  TRIGGER ALL;
					ALTER TABLE art_stat_gt_sp ENABLE  TRIGGER ALL;
					ALTER TABLE art_stat_sp ENABLE  TRIGGER ALL;
					ALTER TABLE art_stat_totale ENABLE  TRIGGER ALL;
					ALTER TABLE art_taille_gt_sp ENABLE  TRIGGER ALL;
					ALTER TABLE art_taille_sp ENABLE  TRIGGER ALL;";
				$result = pg_query($connectBDPECHE,$sql);
				$erreurSQL = pg_last_error($connectBDPECHE);
				$erreurSQL = "(<b>erreur </b> = ".$erreurSQL.")";
				if (!$result) {
					$success=false;
				} else {
					$success=true;
				}
			}
		break;
	default: 
		break;
	
}


// le début du message de fin de traitement
$message='<h2>maintenance de la base</h2>';

// on indique si l'operation a eu lieu avec succes ou pas
if ($success) {
	$message.='<p>l\'op&eacute;ration '.$operation.' a &eacute;t&eacute; r&eacute;alis&eacute;e avec succ&egrave;s.</p>';
}
	else {
		$message.='<p>une erreur est survenue lors de l\'op&eacute;ration de '.$operation.' '.$erreurSQL.'.</p>';

	}
echo($message);

?>