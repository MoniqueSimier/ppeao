<?php
// ce fichier contient des options de configuration pour l'édition des tables

//**************************************************************
// construction des sélecteurs de la page "edition.php" et "étiquettes" des domaines et tables
//**************************************************************
// utilisé pour construire les deux sélecteurs servant à sélectionner les domaines des tables de codage
// et donnant la liste des sous-domaines
$scientifiqueHierarchie=Array("stations","peche","biologie");
$artisanaleHierarchie=Array("agglomeration","unite","activite","debarquement","sortie");

// la liste des domaines de premier niveau, avec leur nom complet et leur type
$domainesListe=Array(
	"geographie"=>array("label"=>"g&eacute;ographie","type"=>"reference"),
	"especes"=>array("label"=>"esp&egrave;ces","type"=>"reference"),
	"stations"=>array("label"=>"stations","type"=>"codage"),
	"peche"=>array("label"=>"p&ecirc;che","type"=>"codage"),
	"biologie"=>array("label"=>"biologie","type"=>"codage"),
	"agglomeration"=>array("label"=>"agglom&eacute;rations","type"=>"codage"),
	"unite"=>array("label"=>"unit&eacute;s de p&ecirc;che","type"=>"codage"),
	"activite"=>array("label"=>"activit&eacute;s","type"=>"codage"),
	"debarquement"=>array("label"=>"d&eacute;barquements","type"=>"codage"),
	"sortie"=>array("label"=>"sorties de p&ecirc;che","type"=>"codage"),
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
$tablesDefinitions=Array(
		"pays"=>array("label"=>"pays","table"=>"ref_pays","id_col"=>"id","noms_col"=>"nom"),
		"systeme"=>array("label"=>"syst&egrave;mes", "table"=>"ref_systeme","id_col"=>"id","noms_col"=>"libelle"),
		"secteur"=>array("label"=>"secteurs","table"=>"ref_secteur", "id_col"=>"id","noms_col"=>"nom"),
		"ecologique"=>array("label"=>"cat&eacute;gories &eacute;cologiques", "table"=>"ref_categorie_ecologique" ,"id_col"=>"id","noms_col"=>"libelle"),
		"trophique"=>array("label"=>"cat&eacute;gories trophiques", "table"=>"ref_categorie_trophique", "id_col"=>"id","noms_col"=>"libelle"),
		"originekb"=>array("label"=>"origines KB", "table"=>"ref_origine_kb", "id_col"=>"id","noms_col"=>"libelle"),
		"ordre"=>array("label"=>"ordres", "table"=>"ref_ordre", "id_col"=>"id","noms_col"=>"libelle"),
	 	"famille"=>array("label"=>"familles", "table"=>"ref_famille", "id_col"=>"id","noms_col"=>"libelle"),
		"espece"=>array("label"=>"esp&egrave;ces", "table"=>"ref_espece", "id_col"=>"id","noms_col"=>"libelle"),
		"station"=>array("label"=>"stations", "table"=>"exp_station", "id_col"=>"id","noms_col"=>"nom"),
		"position"=>array("label"=>"positions", "table"=>"exp_position", "id_col"=>"id","noms_col"=>"libelle"),
		"debris"=>array("label"=>"d&eacute;bris", "table"=>"exp_debris", "id_col"=>"id","noms_col"=>"libelle"),
		"sediment"=>array("label"=>"s&eacute;diment", "table"=>"exp_sediment", "id_col"=>"id","noms_col"=>"libelle"),
		"vegetation"=>array("label"=>"v&eacute;g&eacute;tation", "table"=>"exp_vegetation", "id_col"=>"id","noms_col"=>"libelle"),
		"engins"=>array("label"=>"engins","table"=>"exp_engin", "id_col"=>"id","noms_col"=>"libelle"),
		"qualite_coup"=>array("label"=>"qualit&eacute; du coup","table"=>"exp_qualite", "id_col"=>"id","noms_col"=>"libelle"),
		"sens_courant"=>array("label"=>"sens du courant","table"=>"exp_sens_courant", "id_col"=>"id","noms_col"=>"libelle"),
		"force_courant"=>array("label"=>"force du courant","table"=>"exp_force_courant", "id_col"=>"id","noms_col"=>"libelle"),
		"sexe"=>array("label"=>"sexe","table"=>"exp_sexe", "id_col"=>"id","noms_col"=>"libelle"),
		"stade"=>array("label"=>"stades de maturit&eacute;","table"=>"exp_stade", "id_col"=>"id","noms_col"=>"libelle"),
		"contenu"=>array("label"=>"contenus stomacaux","table"=>"exp_contenu", "id_col"=>"id","noms_col"=>"libelle"),
		"remplissage"=>array("label"=>"taux de remplissage stomacal","table"=>"exp_remplissage", "id_col"=>"id","noms_col"=>"libelle"),
		"type_agglomeration"=>array("label"=>"types d&#x27;agglom&eacute;rations","table"=>"art_type_agglomeration", "id_col"=>"id","noms_col"=>"libelle"),
		"agglomeration"=>array("label"=>"agglom&eacute;rations","table"=>"art_agglomeration", "id_col"=>"id","noms_col"=>"nom"),	
		"csp"=>array("label"=>"cat&eacute;gories socio-professionnelles","table"=>"art_categorie_socio_professionnelle", "id_col"=>"id","noms_col"=>"libelle"),
		"grand_type_engin"=>array("label"=>"grands types d&#x27;engins","table"=>"art_grand_type_engin", "id_col"=>"id","noms_col"=>"libelle"),
		"type_engin"=>array("label"=>"types d&#x27;engins","table"=>"art_type_engin", "id_col"=>"id","noms_col"=>"libelle"),
		"type_activite"=>array("label"=>"type d&#x27;activit&eacute;","table"=>"art_type_activite", "id_col"=>"id","noms_col"=>"raison"),
		"etat_ciel"=>array("label"=>"&eacute;tat du ciel","table"=>"art_etat_ciel", "id_col"=>"id","noms_col"=>"libelle"),
		"vent"=>array("label"=>"vent","table"=>"art_vent", "id_col"=>"id","noms_col"=>"libelle"),	
		"type_sortie"=>array("label"=>"types de sorties","table"=>"art_type_sortie", "id_col"=>"id","noms_col"=>"libelle"),
		"milieu"=>array("label"=>"milieux","table"=>"art_millieu", "id_col"=>"id","noms_col"=>"libelle"),	
	);	


//**************************************************************
// construction des sélecteurs de la page "edition_selector.php"
//**************************************************************

// cette variable contient la liste des cascades pour la sélection des tables
// par exemple, si l'on veut éditer la table "espece" il faudra d'abord sélectionner des valeurs dans
// les tables ordre puis famille
$selectorCascades=Array(	
"pays"=>"pays,systeme,secteur,station",
"systeme"=>"pays,systeme,secteur,station",
"secteur"=>"pays,systeme,secteur,station",
"station"=>"pays,systeme,secteur,station",
"ordre"=>"ordre,famille,espece",
"famille"=>"ordre,famille,espece",
"espece"=>"ordre,famille,espece",
"ecologique"=>"ecologique,espece",
"trophique"=>"trophique,espece",
"originekb"=>"originekb,espece",
"grand_type_engin"=>"grand_type_engin,type_engin",
"type_engin"=>"grand_type_engintype_engin",
"type_agglomeration"=>"type_agglomeration,agglomeration",
"agglomeration"=>"type_agglomeration,agglomeration")
?>