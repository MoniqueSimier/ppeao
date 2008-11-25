<?php

// parametres de connexion a la base de donnees
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';


//debug sleep (10);

// on recupere l'action de maintenance a realiser
$action=$_GET["action"];
// on suppose que l'action a ete realisee avec succes
$success=true;

switch ($action) {
	case 'sequences_ref_param':
		$operation="de mise-&agrave;-jour des s&eacute;quences des tables de r&eacute;f&eacute;rence et de param&eacute;trage";

		// on sélectionne les sequences, leurs tables et leurs colonnes
		// pour les tables de ref (type_table_id=2) et de param (type_table_id=3)
		$sql='	SELECT ads.sequence_name, ads.column_name, addt.table_db 
				FROM admin_sequences ads, admin_dictionary_tables addt 
				WHERE (ads.table_id=addt.dico_id) AND (addt.type_table_id=2 OR addt.type_table_id=3)';
		$result=pg_query($connectPPEAO,$sql);
		$seqArray=pg_fetch_all($result);
		//debug 		echo('<pre>');print_r($seqArray);echo('</pre>');
		
		// on boucle sur chaque sequence `
		foreach ($seqArray as $seq) {
			// on recupere la plus grande valeur de la colonne correspondant a la sequence
			$sqlMax='	SELECT max('.$seq["column_name"].') as maxval
						FROM '.$seq["table_db"].'
						WHERE true';
			$resultMax=pg_query($connectPPEAO,$sqlMax);
			$maxArray=pg_fetch_row($resultMax);
			$maxVal=$maxArray[0];
			
			// on met a jour la valeur maximale de la sequence concernee
			$sqlUpdate='SELECT pg_catalog.setval(\''.$seq["sequence_name"].'\','.$maxVal.',true);';
			if ($resultUpdate=pg_query($connectPPEAO,$sqlUpdate)) {$ok=true;} else {$success=false;}
			//debug 			echo('<pre>');print_r($maxArray);echo('</pre>');
			
			
		}
		
		
	break;
	case 'sequences_donnees':
		$operation="de mise-&agrave;-jour des s&eacute;quences des tables de donn&eacute;es";
		// on sélectionne les sequences, leurs tables et leurs colonnes
		// pour les tables de donnees (type_table_id=4)
		$sql='	SELECT ads.sequence_name, ads.column_name, addt.table_db 
				FROM admin_sequences ads, admin_dictionary_tables addt 
				WHERE (ads.table_id=addt.dico_id) AND (addt.type_table_id=4)';
		$result=pg_query($connectPPEAO,$sql);
		$seqArray=pg_fetch_all($result);
		//debug 		echo('<pre>');print_r($seqArray);echo('</pre>');
		
		// on boucle sur chaque sequence `
		foreach ($seqArray as $seq) {
			// on recupere la plus grande valeur de la colonne correspondant a la sequence
			$sqlMax='	SELECT max('.$seq["column_name"].') as maxval
						FROM '.$seq["table_db"].'
						WHERE true';
			$resultMax=pg_query($connectPPEAO,$sqlMax);
			$maxArray=pg_fetch_row($resultMax);
			$maxVal=$maxArray[0];
			
			// on met a jour la valeur maximale de la sequence concernee
			$sqlUpdate='SELECT pg_catalog.setval(\''.$seq["sequence_name"].'\','.$maxVal.',true);';
			if ($resultUpdate=pg_query($connectPPEAO,$sqlUpdate)) {$ok=true;} else {$success=false;}
			//debug 			echo('<pre>');print_r($maxArray);echo('</pre>');

	break;
	case 'vacuum':
		$sql='VACUUM ANALYZE';
		if ($result=pg_query($connectPPEAO,$sql)) {$success=true;} else {$success=false;}
		$operation="&quot;VACUUM ANALYSE&quot;";
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
		$message.='<p>une erreur est survenue lors de l\'op&eacute;ration de '.$operation.'.</p>';

	}
echo($message);

?>