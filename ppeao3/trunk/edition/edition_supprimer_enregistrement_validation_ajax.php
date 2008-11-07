<?php
session_start();

// script appelé par la fonction javascript showNewLevel
// 
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_PPEAO.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';

//debug sleep (50);

global $tablesDefinitions;

//debug sleep(1);


// la table concernée
$table=$_GET["table"];
$record=$_GET["record"];
$primaryKey=getTablePrimaryKey($connectPPEAO,$table);
$key=$primaryKey["column"];

$theMessage='<h1 align="center">supprimer l&#x27;enregistrement &quot;'.$record.'&quot;</h1>';

	// on prépare le calcul du nombre total d'enregistrements supprimés
	// d'abord on calcule le nombre total d'enregistrements dans les tables utilisateur
	$beforeSql='select sum(n_live_tup) from pg_catalog.pg_stat_user_tables';
	$beforeResult=pg_query($connectPPEAO,$beforeSql);
	$beforeArray=pg_fetch_all($beforeResult);
	$before=$beforeArray[0]["sum"];
	pg_free_result($beforeResult);
	
	$deleteSql='DELETE FROM '.$table.' WHERE '.$key.'=\''.$record.'\'';
	// si la suppression a bien eu lieu
	if($deleteResult=pg_query($connectPPEAO,$deleteSql)) {
	// on renvoie un message positif
	$theMessage.='<p>enregistrement "'.$record.'" supprim&eacute; de la table "'.$table.'.</p>';
	// et on fait un VACUUM ANALYZE de la base
	$vacuumSql='VACUUM ANALYZE';
	$vacuumResult=pg_query($connectPPEAO,$vacuumSql);
	pg_free_result($vacuumResult);
	
	// maintenant on recalcule le nombre total d'enregistrements dans les tables utilisateur
	$afterSql='select sum(n_live_tup) from pg_catalog.pg_stat_user_tables';
	$afterResult=pg_query($connectPPEAO,$afterSql);
	$afterArray=pg_fetch_all($afterResult);
	$after=$afterArray[0]["sum"];
	pg_free_result($afterResult);
	
	// on a donc supprimé $before-$after enregistrements
	$deletedRows=$before-$after;
	
	// on le signale
	$theMessage.='<p>'.$deletedRows.' enregistrement(s) supprim&eacute;(s) au total.</p>';
	
	// on inscrit la suppression dans le journal
	logWriteTo(1,'notice','enregistrement "'.$record.'" supprim&eacute; de la table "'.$table.'"',$deleteSql,'',0);
	}
	
	else {
		$theMessage.= '<p>Une erreur est survenue lors de la suppression de l\'enregistrement "'.$record.'" de la table "'.$table.'" :</p><p> '.pg_last_error().'</p>';
	}
	
	pg_free_result($deleteResult);

echo($theMessage);


?>