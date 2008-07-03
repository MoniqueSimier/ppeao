<?php

//effectue la connexion avec la base postgres
// LOCAL
$connectPPEAO = pg_connect("port=5432 dbname=ppeao user=devppeao password=2devppe!!") or die('Connexion impossible a BD_PPEAO : ' . pg_last_error());


// REMOTE OTOLITHE.COM
//$connectPPEAO = pg_connect("port=5432 dbname=otoli2_ppeao user=otoli2 password=ZeldaTactics7")or die('Connexion impossible : ' . pg_last_error());

// REMOTE IRD
//$connectPPEAO = pg_connect("host=vmppeao.mpl.ird.fr port=5432 dbname=atlas user=devppeao password=2devppe!!")or die('Connexion impossible : ' . pg_last_error());


?>
