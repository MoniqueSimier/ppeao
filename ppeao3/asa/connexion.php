<?php
$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
$host= "vmppeao.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
//$host= "localhost";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
//if($bdd=="") $bdd= "bd_peche";
if($bdd=="") $bdd= "peche_exp_27_09";

$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}

?>