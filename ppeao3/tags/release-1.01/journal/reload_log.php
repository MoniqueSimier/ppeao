<?php 
// script appel� via Ajax par la fonction reloadLog() et permettant de rafraichir l'affichage du journal une fois que celui-ci  a �t� archiv� et vid�

include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';

echo(logDisplayFull('','','',"","",""));


?>