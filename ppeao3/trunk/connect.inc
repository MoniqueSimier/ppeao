<?php 
// script de configuration de la connexion aux bases de données
// les variables qui suivent sont celles qui peuvent être modifiées en fonction des paramètres de connexion

// serveur sur lequel sont situées les bases de données (hostname)
$hostname='vmppeao-l3.mpl.ird.fr';
// port de connexion au serveur de bases de données
$port='5432';
// nom de la base de données principale PPEAO
$base_principale='ppeao_test';
// nom de l'utilisateur pour la connexion à la base principale
$username='devppeao';
// mot de passe de l'utilisateur pour la connexion à la base principale
$password='2devppe!!';
// nom de la base de données depuis laquelle se fait le portage
$base_portage='BD_Peche';


// ********* NE RIEN MODIFIER CI-DESSOUS *****************


//**** effectue la connexion à la base PPEAO principale ****
$connectPPEAO = pg_connect("host=$hostname port=$port dbname=$base_principale user=$username password=$password") or die('Connexion impossible a la base : ' . pg_last_error());


//**** Compatibilite lot 2 *** 
$user=$username;		// Le nom d'utilisateur
$passwd=$password;		// Le mot de passe
$host= $hostname;  		 // L'hôte  
// base utilisée par les extractions
$db_default=$base_principale;
// base utilisée par le portage
$bdd=$base_portage;

?>
