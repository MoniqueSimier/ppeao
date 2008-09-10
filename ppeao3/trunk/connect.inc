<?php

//effectue la connexion avec la base postgres
// LOCAL
$connectPPEAO = pg_connect("port=5432 dbname=devppeao user=devppeao password=2devppe!!") or die('Connexion impossible a BD_PPEAO : ' . pg_last_error());


// REMOTE OTOLITHE.COM
//$connectPPEAO = pg_connect("port=5432 dbname=otoli2_ppeao user=otoli2 password=ZeldaTactics7")or die('Connexion impossible : ' . pg_last_error());

// REMOTE IRD
//$connectPPEAO = pg_connect("host=vmppeao-l3.mpl.ird.fr port=5432 dbname=devppeao user=devppeao password=2devppe!!")or die('Connexion impossible : ' . pg_last_error());

//**** Compatibilite lot 2 *** TEST
$user="devppeao";                   // Le nom d'utilisateur 	
$passwd="2devppe!!";                // Le mot de passe 			
$host= "localhost";  				// L'hôte  
$bdd="BD_PECHE_01";
$db_default="BD_PECHE_01";

//**** IRD
//$user="devppeao";                   // Le nom d'utilisateur 	
//$passwd="2devppe!!";                // Le mot de passe 			
//$host= "vmppeao-l3.mpl.ird.fr";  	 // L'hôte  
//$db_default="bdpeche";

?>
