<?php
// ce fichier contient des options de configuration pour l'édition des tables

// la liste des hiérarchies de tables de références

$scientifiqueHierarchie=Array("stations","peche","biologie");
$experimentaleHierarchie=Array("agglomeration","unite","activite","debarquement","sortie");

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


// correspondance entre les noms des tables sur la page de sélection des tables à éditer et le nom des tables dans la base
// (pour ne pas faire apparaître les noms des tables en dur dans le code)
$tableSelectors=Array(
	"geographie"=>Array(
		"pays"=>array("label"=>"pays","table"=>"ref_pays"),
		"systeme"=>array("label"=>"syst&egrave;mes", "table"=>"ref_systeme"),
		"secteur"=>array("label"=>"secteurs","table"=>"ref_secteur")
	),
	
	"especes"=>Array(
		"ecologique"=>array("label"=>"cat&eacute;gories &eacute;cologiques", "table"=>"ref_categorie_ecologique"),
		"trophique"=>array("label"=>"cat&eacute;gories trophiques", "table"=>"ref_categorie_trophique"),
		"originekb"=>array("label"=>"origines KB", "table"=>"ref_origine_kb"),
		"ordre"=>array("label"=>"ordres", "table"=>"ref_ordre"),
	 	"famille"=>array("label"=>"familles", "table"=>"ref_famille"),
		"espece"=>array("label"=>"esp&egrave;ces", "table"=>"ref_espece")
	),

	"stations"=>Array(
		"station"=>array("label"=>"stations", "table"=>"exp_station"),
		"position"=>array("label"=>"positions", "table"=>"exp_position"),
		"debris"=>array("label"=>"d&eacute;bris", "table"=>"exp_debris"),
		"sediment"=>array("label"=>"s&eacute;diment", "table"=>"exp_sediment"),
		"vegetation"=>array("label"=>"v&eacute;g&eacute;tation", "table"=>"exp_vegetation")
	),
	
	"peche"=>Array(
		"engins"=>array("label"=>"engins","table"=>"exp_engin"),
		"qualite_coup"=>array("label"=>"qualit&eacute; du coup","table"=>"exp_qualite"),
		"sens_courant"=>array("label"=>"sens du courant","table"=>"exp_sens_courant"),
		"force_courant"=>array("label"=>"force du courant","table"=>"exp_force_courant")
		),
	
	"biologie"=>Array(
		"sexe"=>array("label"=>"sexe","table"=>"exp_sexe"),
		"stade"=>array("label"=>"stades de maturit&eacute;","table"=>"exp_stade"),
		"contenu"=>array("label"=>"contenus stomacaux","table"=>"exp_contenu"),
		"remplissage"=>array("label"=>"taux de remplissage stomacal","table"=>"exp_remplissage"),
		),
	
	"agglomeration"=>Array(
		"type_agglomeration"=>array("label"=>"types d&#x27;agglom&eacute;rations","table"=>"art_type_agglomeration"),
		"agglomeration"=>array("label"=>"agglom&eacute;rations","table"=>"art_agglomeration"),
		),
	
	"unite"=>Array(
		"csp"=>array("label"=>"cat&eacute;gories socio-professionnelles","table"=>"art_categorie_socio_professionnelle"),
		"grand_type_engin"=>array("label"=>"grands types d&#x27;engins","table"=>"art_grand_type_engin"),
		"type_engin"=>array("label"=>"types d&#x27;engins","table"=>"art_type_engin"),
		),
	
	"activite"=>Array(
		"type_activite"=>array("label"=>"type d&#x27;activit&eacute;","table"=>"art_type_engin"),
		),
	
	"debarquement"=>Array(
		"etat_ciel"=>array("label"=>"&eacute;tat du ciel","table"=>"art_etat_ciel"),
		"vent"=>array("label"=>"vent","table"=>"art_vent"),
		),
	
	"sortie"=>Array(
		"type_sortie"=>array("label"=>"types de sorties","table"=>"art_type_sortie"),
		"milieu"=>array("label"=>"milieux","table"=>"art_milieu"),
		)
	
	);
	
	
// variable stockant la liste des tables de "niveau 2"
// c'est à dire accessibles uniquement via une cascade de préselections
?>