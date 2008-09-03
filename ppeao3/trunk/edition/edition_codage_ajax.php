<?php

// script appelé par la fonction javascript showCodageTablesSelect
// affiche un SELECT contenant la liste des tables de codage correspondant au domaine choisi

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';

$theDomaine=$_GET["domaine"];
buildTableSelect($theDomaine,"")

?>