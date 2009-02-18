<?php 
//*****************************************
// finExport.php
//*****************************************
// Created by Yann Laurent
// 2008-12-08 : creation
//*****************************************
// Ce programme termine l'export.
//*****************************************
// Paramètres en entrée
// aucun

// Paramètres en sortie
// aucun

// Mettre les noms des fichiers dans un fichier texte
session_start();

$nomFenetre = "exportOK";
// Si le traitement précédent a échoué, arrêt du traitement

if (isset($_SESSION['s_status_process_auto'])) {
	if ($_SESSION['s_status_process_auto'] == 'ko') {
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Fin du processus automatique en erreur</div>" ;
	} else {
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Fin avec succes du processus automatique.</div>" ;
	}
} else {
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Fin du processus automatique en erreur</div>" ;
}


 // end if (! $pasdetraitement )


exit;



?>
