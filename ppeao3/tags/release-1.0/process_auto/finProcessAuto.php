<?php 
//*****************************************
// finProcessAuto.php
//*****************************************
// Created by Yann Laurent
// 2008-08-26 : creation
//*****************************************
// Ce programme termine le process automatique.
//*****************************************
// Param�tres en entr�e
// aucun

// Param�tres en sortie
// aucun

// Mettre les noms des fichiers dans un fichier texte
session_start();

$nomFenetre = "portageOK";
// Si le traitement pr�c�dent a �chou�, arr�t du traitement

if (isset($_SESSION['s_status_process_auto'])) {
	if ($_SESSION['s_status_process_auto'] == 'ko') {
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Fin du processus automatique en erreur</div>" ;
	} else {
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Fin avec succes du processus automatique.</div>" ;
	}
}


 // end if (! $pasdetraitement )


exit;



?>
