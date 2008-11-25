<?php

//effectue la connexion avec la base postgres
// LOCAL
//$connectPPEAO = pg_connect("port=5432 dbname=devppeao user=devppeao password=2devppe!!") or die('Connexion impossible a BD_PPEAO : ' . pg_last_error());


// REMOTE OTOLITHE.COM
//$connectPPEAO = pg_connect("port=5432 dbname=otoli2_ppeao user=otoli2 password=ZeldaTactics7")or die('Connexion impossible : ' . pg_last_error());

// REMOTE IRD
$connectPPEAO = pg_connect("host=vmppeao-l3.mpl.ird.fr port=5432 dbname=ppeao_test user=devppeao password=2devppe!!")or die('Connexion impossible : ' . pg_last_error());

//**** Compatibilite lot 2 *** A mettre a jour avec nouvelle valeur connexion $connectPPEAO
$user="devppeao";                   // Le nom d'utilisateur 	
$passwd="2devppe!!";                // Le mot de passe 			
$host= "vmppeao-l3.mpl.ird.fr";  	 // L'hôte  
// base utilisée par les extractions
$db_default="ppeao_test";
// base utilisée par le portage
$bdd="BD_Peche";

?>
