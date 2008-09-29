<?php 
// script used to archive (as a CSV file) and delete the log

include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';

session_start();

logDelete("");


?>