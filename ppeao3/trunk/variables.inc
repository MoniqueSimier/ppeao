<?php 

// variable globale pour les niveaux de log : 0 pour "production" et 1 pour "d�boguage" (plus de d�tails dans les logs)
$debug = 1;

$messageGen = "";


// variable indiquant o� archiver les journaux
$logArchivePath='/work/journal_archives/';

// variable indiquant au bout de combien d'enregistrements il faut d�clencher l'autoarchivage du journal
$logAutoArchiveEntriesNumber='1000';

// variable definissant le delai (en annees) avant que des donnees soient considerees comme "historiques"
// ex. : 3 signifie que toute campagne ou periode d'enquete dont l'annee est anterieure a (annee courante-3)
// sera consideree comme historique
$delai_butoir=3;

?>