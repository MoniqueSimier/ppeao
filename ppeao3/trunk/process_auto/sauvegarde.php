<?php 
//*****************************************
// Sauvegarde.php
//*****************************************
// Created by Yann Laurent
// 2008-07-01 : creation
//*****************************************
// Ce programme lance la creation d'un script SQL de sauvegarde pour la base PPEAO de r�f�rence.
// La bonne ex�cution du programme est control�e au final par l'existence du fichier de sauvegarde dans le r�pertoire
// On affiche comme resultat de l'ex�cution de ce programme dans deux div (qui eux-m�me vont s'ins�rer dans le div principal dont
// l'ID est "sauvegarde" avec une icone de bonne ou mauvaise ex�cution (dans div id="sauvegarde_img") et l'explication
// de l'erreur dans div id = "sauvegarde_txt"
//*****************************************
// Param�tres en entr�e
// Param�tres en sortie
// aucun


// Mettre les noms des fichiers dans un fichier texte
session_start();
$_SESSION['s_status_process_auto'] = 'ok';
set_time_limit(0);
// Variable de test (en fonctionnement production, les deux variables sont false)
$pasdetraitement = true;
$pasdefichier = false; // Variable de test pour linux. Meme valeur que dans comparaison.php

// Include standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';

// On identifie si le traitement est ex�cutable ou non
if (isset($_GET['exec'])) {
	if ($_GET['exec'] == "false") {
		$pasdetraitement =  true;
		$Labelpasdetraitement ="non";
	} else {
		$pasdetraitement =  false;
		$Labelpasdetraitement ="oui";
	}
} 

// Recuperation des parametres (nom repertoire, nom fichiers etc..) depuis le fichier de parametres
$dirLog = GetParam("backupNomBD",$PathFicConf);
$dirLog = $_SERVER["DOCUMENT_ROOT"]."/".$dirLog;
// Variables pour la sauvegarde de la base de reference (PPEAO)
$BDsource = $base_principale;// 
$BDBackup = GetParam("backupNomBD",$PathFicConf);
// Variables pour la sauvegarde de la base de reference (BDPECHE)
$BDsourcePortage = $bd_peche;// 
$BDBackupPortage = $BDBackup."portage";

// Flags pour savoir si on a cree les backups pour savoir si on doit supprimer une base (un des process en erreur)
$backupRefCree = false;
$backupPortCree = false;
// On remet � z�ro le fichier reverse SQL. On le fait ici m�me si on n'utilise pas le fichier ici
// car il sera plus difficile d'identifier dans comparaison.php le premier appel de ce programme
$ficRevSQL = OpenFileReverseSQL ("ecras",$dirLog,$pasdefichier);
CloseFileReverseSQL ($ficRevSQL,$pasdefichier);

// Connexion � la BD pour maj des logs
if (!$connectPPEAO) { 
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Erreur de connexion � la base de donn&eacute;es pour maj des logs</div>" ;; exit;
	}
logWriteTo(7,"notice","**- Debut lancement sauvegarde portage automatique.","","","0");

// Param�tres  de sauvegarde
if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine compl�te de traitement automatique (saute cette etape)

	$continueDump = false;
	// *********************************************
	// Avant ex�cution, on teste si une BD existe d�j� avec le m�me nom
	// si existe : erreur (cela veut dire que la purge lors du traitement pr�c�dent ne s'est pas correctement faite
	// L'action est faite pour les 2 bases, backup pour la reference, backup pour le portage
	// *********************************************
	$lev=error_reporting (8); //Pour eviter les avertissements si la base n'existe pas.
	$connectTest = pg_connect ("host=".$host." dbname=".$BDBackup." user=".$user." password=".$passwd);
	if ($connectTest) { 
		$messageGen="La base de sauvegarde de la base de reference <b>".$BDBackup."</b> sur  <b>".$host."</b> existe deja !<br/>";
	} else {
		pg_close($connectTest);
		$continueDump = true;
	}
	if ($continueDump) {
		// On test si la base de sauvegarde de la base de portage
		$connectTest2 = pg_connect ("host=".$host." dbname=".$BDBackupPortage." user=".$user." password=".$passwd);
		if ($connectTest2) { 
			$messageGen.="La base de sauvegarde de la base de portage <b>".$BDBackupPortage."</b> sur <b>".$host."</b> existe deja !<br/>";
			$continueDump = false;
			pg_close($connectTest2);
		}
	}
	error_reporting ($lev); // retour au avertissements par defaut
	// Si les base n'existeny pas, alors on peut continuer
	// *********************************************
	// Etape 1 : sauvegarde de la base de reference
	// *********************************************
	if ($continueDump) {
		logWriteTo(7,"notice","Sauvegarde de la base de donnee","","","0");
		$createBDSQL = "create database \"".$BDBackup."\" with template \"".$BDsource."\"";
		$createBDResult = pg_query($connectPPEAO,$createBDSQL);
		$erreurQuery = pg_last_error();
		if (!$createBDResult) {
			// erreur
			// On met globalement le process en erreur
			if (isset($_SESSION['s_status_process_auto'])) { $_SESSION['s_status_process_auto'] = "ko"; }
			// message d'erreur
			logWriteTo(7,"error","**- Fin Sauvegarde : en erreur : erreur dans la creation de la base de sauvegarde ".$BDBackup." erreur = ".$erreurQuery,"","","0");			
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur : erreur dans la creation de la base de sauvegarde ".$BDBackup."</div><div id=\"sauvegarde_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
			echo"<div id=\"vertical_slide1\"> erreur = ".$erreurQuery."</div>";

		} else { // else du if (!$createBDResult)
			// Sauvegarde de la base de reference OK ==> message
			logWriteTo(7,"notice","Etape 1 Sauvegarde : base ".$BDsource." copiee dans ".$BDBackup,"","","0");
			$messageTRT = "Sauvegarde ex&eacute;cut&eacute;e avec succ&egrave;s : base ".$BDsource." copiee dans ".$BDBackup."<br/>";
			$backupRefCree = true;

			// On peut continer avec la base de portage
			$connectBDPECHE =pg_connect ("host=".$host." dbname=".$bd_peche." user=".$user." password=".$passwd);
			if (!$connectBDPECHE) { 
				echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Erreur de connexion a la base de donn&eacute;es ".$bd_peche."</div><div id=\"sauvegarde_chk\">Exec= ".$Labelpasdetraitement."</div>" ; exit;
			} else {
				$createBDPortSQL = "create database \"".$BDBackupPortage."\" with template \"".$BDsourcePortage."\"";
				$createBDPortResult = pg_query($connectBDPECHE,$createBDPortSQL);
				$erreurQueryPort = pg_last_error();
				if (!$createBDPortResult) {
					// erreur
					// On met globalement le process en erreur
					if (isset($_SESSION['s_status_process_auto'])) { $_SESSION['s_status_process_auto'] = "ko"; }
					// message d'erreur
					logWriteTo(7,"error","**- Fin Sauvegarde : en erreur : erreur dans la creation de la base de sauvegarde portage".$BDBackupPortage." erreur = ".$erreurQueryPort,"","","0");			
					echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur : erreur dans la creation de la base de sauvegarde pour portage ".$BDBackupPortage." </div><div id=\"sauvegarde_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
					echo"<div id=\"vertical_slide1\">Erreur = ".$erreurQueryPort."</div>";
		
				} else {
					// Sauvegarde OK ==> message
					logWriteTo(7,"notice","Etape 2 Sauvegarde : base ".$BDsourcePortage." copiee dans ".$BDBackupPortage,"","","0");					
					logWriteTo(7,"notice","**- Fin Sauvegarde succes du traitement","","","0");
					$messageTRT .= "Sauvegarde ex&eacute;cut&eacute;e avec succ&egrave;s : base ".$BDsourcePortage." copiee dans ".$BDBackupPortage."<br/>";
					echo "<div id=\"sauvegarde_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde ex&eacute;cut&eacute;e avec succ&egrave;s </div><div id=\"sauvegarde_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
					echo"<div id=\"vertical_slide1\">".$messageTRT."</div>";
					$backupPortCree = true;
				} // fin du if (!$createBDPortResult)
			} // fin du if (!$connectBDPECHE)
		} // fin du if (!$createBDResult)
	} else {
			// Unes des bases de sauvegarde existe d�j� ==> erreur
			if (isset($_SESSION['s_status_process_auto'])) { $_SESSION['s_status_process_auto'] = "ko"; }
			logWriteTo(7,"error","**- Fin Sauvegarde : en erreur : ".$messageGen,"","","0");			
				echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur </div><div id=\"comparaison_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
				echo"<div id=\"vertical_slide1\">".$messageGen."</div>";

	} // fin du if ($continueDump)

} else { // else du if (! $pasdetraitement )
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Etape de sauvegarde non ex&eacute;cut&eacute;e par choix de l'utilisateur</div><div id=\"sauvegarde_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
	logWriteTo(7,"error","**- En Test Etape de sauvegarde non executee par choix de l'utilisateur","","","0");
} // fin du if (! $pasdetraitement )
set_time_limit(ini_get('max_execution_time')); // on remet le timer normal
if (!$backupPortCree || !$backupRefCree) {
	if ($backupRefCree) {
	// Ca veut dire que le process de cr�ation de la base de sauvegarde pour le portage a echou�
		$dropBDSQL = "drop database ".$BDBackup;
		$dropBDResult = pg_query($connectPPEAO,$dropBDSQL) or die('erreur dans la requete : '.pg_last_error());
		if ($createBDResult) {
			logWriteTo(7,"notice","Erreur dans le process ==> Base de sauvegarde ".$BDBackup." supprimee.","","","0");
			
		} else {
			logWriteTo(7,"error","erreur suppression de la base de donnee de sauvegarde ".$BDBackup,"","","0");
		}
		logWriteTo(7,"notice","**- Fin Sauvegarde *****************","","","0");
	}
}
exit;

?>
