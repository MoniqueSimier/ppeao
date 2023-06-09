<?php
// ce fichier contient des options de configuration pour l'�dition des tables


//**************************************************************	
// liste des tables utilis�es par l'application 
//**************************************************************
// "tablename"=>array("label","table")
// tablename = nom de la table utilis� dans les URL
// label = nom de la table pour affichage dans les pages HTML
// table = nom de la table dans la base de donn�es
// id_col = nom de la colonne stockant les valeurs � utiliser comme identifiant unique
// noms_col = nom de la colonne � utiliser comme "nom" de la valeur (ex: "libelle" pour ref_ordre et "nom" pour ref_pays)
// on a remplac� le tableau original par un tableau extrait de la BDD afin de permettre plus de souplesse


$tablesSql='SELECT * FROM admin_dictionary_tables adt
			LEFT JOIN admin_dictionary_domains add ON adt.domain_id=add.domain_id 
			LEFT JOIN admin_dictionary_type_tables adtt ON adtt.type_table_id=adt.type_table_id 
			ORDER BY adtt.type_table_nom, add.domaine_nom, adt.handle';
$tablesResult=pg_query($connectPPEAO,$tablesSql) or die('erreur dans la requete : '.$tablesSql. pg_last_error());
$tables=pg_fetch_all($tablesResult);
$tablesDefinitions='';

foreach ($tables as $table) {
	// truc bizarre : une valeur TRUE/FALSE retourn�e de pgsql est stock�e comme t/f... on compense...
	if ($table["selector"]=="t") {$table["selector"]=true;} else {$table["selector"]=false;}
	
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
	"zone"=>$table["zone_id"],
	"cascade_foreign_key"=>$table["cascade_foreign_key"],
	"editable"=>$table["editable"]
	);
}

// nombre maximal de valeurs des cl�s �trang�res � afficher dans les menus d�roulants <select> des filtres des tables de r�sultats
// si le nombre est superieur a cette valeur, on affiche un simple champ <input>
$maxForeignKeyMenuLength=1000;

// cascades de selecteurs a utiliser pour la suppression de campagnes ou de periodes d'enquete
$suppression_cascades=array(
	"exp"=>array("pays","systeme","annee_debut"),
	"art"=>array("pays","systeme","secteur","agglomeration","annee_debut")
	);


?>