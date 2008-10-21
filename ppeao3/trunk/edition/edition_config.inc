<?php
// ce fichier contient des options de configuration pour l'édition des tables

//**************************************************************
// construction des sélecteurs de la page "edition.php" et "étiquettes" des domaines et tables
//**************************************************************
// utilisé pour construire les deux sélecteurs servant à sélectionner les domaines des tables de paramétrage
// et donnant la liste des sous-domaines
$scientifiqueHierarchie=Array("stations","peche","biologie");
$artisanaleHierarchie=Array("agglomeration","unite","activite","debarquement","sortie");

// la liste des domaines de premier niveau, avec leur nom complet et leur type
$domainesListe=Array(
	"geographie"=>array("label"=>"g&eacute;ographie","type"=>"reference"),
	"especes"=>array("label"=>"esp&egrave;ces","type"=>"reference"),
	"stations"=>array("label"=>"stations","type"=>"parametrage"),
	"peche"=>array("label"=>"p&ecirc;che","type"=>"parametrage"),
	"biologie"=>array("label"=>"biologie","type"=>"parametrage"),
	"agglomeration"=>array("label"=>"agglom&eacute;rations","type"=>"parametrage"),
	"unite"=>array("label"=>"unit&eacute;s de p&ecirc;che","type"=>"parametrage"),
	"activite"=>array("label"=>"activit&eacute;s","type"=>"parametrage"),
	"debarquement"=>array("label"=>"d&eacute;barquements","type"=>"parametrage"),
	"sortie"=>array("label"=>"sorties de p&ecirc;che","type"=>"parametrage"),
	);


// liste des tables appartenant à chaque domaine et sous-domaine
// utilisé pour construire les SELECT de la page edition.php
$tableSelectors=Array(
	"geographie"=>Array("pays","systeme","secteur"),
	
	"especes"=>Array("ecologique","trophique","originekb","ordre","famille","espece"),

	"stations"=>Array("station","position","debris","sediment","vegetation"),
	
	"peche"=>Array("engins","qualite_coup","sens_courant","force_courant"),
	
	"biologie"=>Array("sexe","stade","contenu","remplissage"),
	
	"agglomeration"=>Array("type_agglomeration","agglomeration"),
	
	"unite"=>Array("csp","grand_type_engin","type_engin"),
	
	"activite"=>Array("type_activite"),
	
	"debarquement"=>Array("etat_ciel","vent"),
	
	"sortie"=>Array("type_sortie","milieu")
	
	);
	
	
// liste des tables utilisées par l'application : 
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
			ORDER BY adt.handle';
$tablesResult=pg_query($connectPPEAO,$tablesSql) or die('erreur dans la requete : '.$tablesSql. pg_last_error());
$tables=pg_fetch_all($tablesResult);
$tablesDefinitions='';

foreach ($tables as $table) {
	$tablesDefinitions[$table["handle"]]=array("label"=>$table["label"],"table"=>$table["table_db"],"id_col"=>$table["id_col"],"noms_col"=>$table["noms_col"]);
}


//**************************************************************
// construction des sélecteurs de la page "edition_selector.php"
//**************************************************************

// cette variable contient la liste des cascades pour la sélection des tables
// par exemple, si l'on veut éditer la table "espece" il faudra d'abord sélectionner des valeurs dans
// les tables ordre puis famille
$selectorCascades=Array(	
"pays"=>"pays,systeme,secteur",
"systeme"=>"systeme,secteur",
"secteur"=>"secteur",
"station"=>"pays,systeme,secteur,station",
"ordre"=>"ordre,famille,espece",
"famille"=>"famille,espece",
"espece"=>"espece",
"ecologique"=>"ecologique,espece",
"trophique"=>"trophique,espece",
"originekb"=>"originekb,espece",
"grand_type_engin"=>"grand_type_engin,type_engin",
"type_engin"=>"type_engin",
"type_agglomeration"=>"type_agglomeration,agglomeration",
"agglomeration"=>"pays,systeme,secteur,agglomeration",
"lieu_peche"=>"pays,systeme,secteur,lieu_peche")
?>