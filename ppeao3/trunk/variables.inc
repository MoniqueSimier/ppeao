<?php 

// variable globale pour les niveaux de log : 0 pour "production" et 1 pour "déboguage" (plus de détails dans les logs)
$debug = 1;

$messageGen = "";


// variable indiquant où archiver les journaux
$logArchivePath='/journal/archives/';
// variable indiquant au bout de combien d'enregistrements il faut déclencher l'autoarchivage du journal
$logAutoArchiveEntriesNumber='1000';

?>