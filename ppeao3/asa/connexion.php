<?php
$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
$host= "vmppeao.mpl.ird.fr";	// L'h�te (ordinateur sur lequel le SGBD est install�) 
//$host= "localhost";	// L'h�te (ordinateur sur lequel le SGBD est install�) 
//if($bdd=="") $bdd= "bd_peche";
if($bdd=="") $bdd= "peche_exp_27_09";

$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}

?>