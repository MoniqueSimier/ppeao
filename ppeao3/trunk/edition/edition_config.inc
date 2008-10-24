<?php
// ce fichier contient des options de configuration pour l'édition des tables


//**************************************************************	
// liste des tables utilisées par l'application 
//**************************************************************
// "tablename"=>array("label","table")
// tablename = nom de la table utilisé dans les URL
// label = nom de la table pour affichage dans les pages HTML
// table = nom de la table dans la base de données
// id_col = nom de la colonne stockant les valeurs à utiliser comme identifiant unique
// noms_col = nom de la colonne à utiliser comme "nom" de la valeur (ex: "libelle" pour ref_ordre et "nom" pour ref_pays)
// on a remplacé le tableau original par un tableau extrait de la BDD afin de permettre plus de souplesse


$tablesSql='SELECT * FROM admin_dictionary_tables adt
			LEFT JOIN admin_dictionary_domains add ON adt.domain_id=add.domain_id 
			LEFT JOIN admin_dictionary_type_tables adtt ON adtt.type_table_id=adt.type_table_id 
			ORDER BY adtt.type_table_nom, add.domaine_nom, adt.handle';
$tablesResult=pg_query($connectPPEAO,$tablesSql) or die('erreur dans la requete : '.$tablesSql. pg_last_error());
$tables=pg_fetch_all($tablesResult);
$tablesDefinitions='';

foreach ($tables as $table) {
	// truc bizarre : une valeur TRUE/FALSE retournée de pgsql est stockée comme t/f... on compense...
	if ($table["selector"]==t) {$table["selector"]=true;} else {$table["selector"]=false;}
	
	$tablesDefinitions[$table["handle"]]=
	array("label"=>$table["label"],
	"table"=>$table["table_db"],
	"id_col"=>$table["id_col"],
	"noms_col"=>$table["noms_col"],
	"type_table_nom"=>$table["type_table_nom"],
	"type_table_description"=>$table["type_table_description"],
	"domaine_nom"=>$table["domaine_nom"],
	"domaine_description"=>$table["domaine_description"],
	"selector_cascade"=>$table["selector_cascade"],
	"selector"=>$table["selector"],
	"zone"=>$table["zone_id"]
	);
}
?>