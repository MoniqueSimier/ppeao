<?php 
//*****************************************
// Sauvegarde.php
//*****************************************
// Created by Yann Laurent
// 2008-07-01 : creation
//*****************************************
// Ce programme lance la creation d'un script SQL de sauvegarde pour la base PPEAO de référence.
// La bonne exécution du programme est controlée au final par l'existence du fichier de sauvegarde dans le répertoire
// On affiche comme resultat de l'exécution de ce programme dans deux div (qui eux-même vont s'insérer dans le div principal dont
// l'ID est "sauvegarde" avec une icone de bonne ou mauvaise exécution (dans div id="sauvegarde_img") et l'explication
// de l'erreur dans div id = "sauvegarde_txt"
//*****************************************
// Paramètres en entrée
// Paramètres en sortie
// aucun


// Mettre les noms des fichiers dans un fichier texte
session_start();
$_SESSION['s_status_process_auto'] = 'ok';
// Variable de test (en fonctionnement production, les deux variables sont false)
$pasdetraitement = true;
$pasdefichier = false; // Variable de test pour linux. Meme valeur que dans comparaison.php

// Include standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';

// On identifie si le traitement est exécutable ou non
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
$BDsource = "devppeao";// Pour test a virer apres
$BDBackup = GetParam("backupNomBD",$PathFicConf);

// On remet à zéro le fichier reverse SQL. On le fait ici même si on n'utilise pas le fichier ici
// car il sera plus difficile d'identifier dans comparaison.php le premier appel de ce programme
$ficRevSQL = OpenFileReverseSQL ("ecras",$dirLog,$pasdefichier);
CloseFileReverseSQL ($ficRevSQL,$pasdefichier);

// Connexion à la BD pour maj des logs

if (!$connectPPEAO) { 
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Erreur de connection à la base de donn&eacute;es pour maj des logs</div>" ;; exit;
	}
logWriteTo(7,"notice","**- Debut lancement sauvegarde portage automatique.","","","0");

// Paramètres  de sauvegarde
if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine complète de traitement automatique (saute cette etape)

	$continueDump = false;
	
	// Avant exécution, on teste si une BD existe déjà avec le même nom
	// si existe : erreur (cela veut dire que la purge lors du traitement précédent ne s'est pas correctement faite
	// *********************************************
	$lev=error_reporting (8); //Pour eviter les avertissements si la base n'existe pas.
	$connectTest = pg_connect ("host=".$host." dbname=".$BDBackup." user=".$user." password=".$passwd);
	if ($connectTest) { 
		$messageGen="La base de sauvegarde existe deja !";
	} else {
		$continueDump = true;
	}
	error_reporting ($lev); // retour au avertissements par defaut
	// Si la base n'existe pas, alors on peut continuer
	if ($continueDump) {

		logWriteTo(7,"notice","Sauvegarde de la base de donnee","","","0");

		
		$createBDSQL = "create database ".$BDBackup." with template ".$BDsource;
		$createBDResult = pg_query($connectPPEAO,$createBDSQL) or die('erreur dans la requete : '.pg_last_error());
		if (!$createBDResult) {
			// erreur
			// On met globalement le process en erreur
			if (isset($_SESSION['s_status_process_auto'])) { $_SESSION['s_status_process_auto'] = "ko"; }
			// message d'erreur
			logWriteTo(7,"error","**- Fin Sauvegarde : en erreur : erreur dans la creation de la base de sauvegarde ".$BDBackup,"","","0");			
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur : erreur dans la creation de la base de sauvegarde ".$BDBackup."</div><div id=\"comparaison_chk\">Exec= ".$Labelpasdetraitement."</div>" ;

		} else {
			// Sauvegarde OK ==> message
			logWriteTo(7,"notice","**- Fin Sauvegarde : base copiee dans ".$BDBackup,"","","0");
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde ex&eacute;cut&eacute;e avec succ&egrave;s : base copiee dans ".$BDBackup."</div><div id=\"comparaison_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
			
		}

	} else {
			// La base de sauvegarde existe déjà ==> erreur
			if (isset($_SESSION['s_status_process_auto'])) { $_SESSION['s_status_process_auto'] = "ko"; }
			logWriteTo(7,"error","**- Fin Sauvegarde : en erreur : ".$messageGen,"","","0");			
				echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur </div><div id=\"comparaison_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
				//echo"<div class=\"marginCR\">Compte Rendu&nbsp;<a id=\"v_slidein1\" href=\"#\"> Afficher </a>|<a id=\"v_slideout1\" href=\"#\"> Fermer </a>| <strong>status</strong>: <span id=\"vertical_status1\">open</span></div>";
				echo"<div id=\"vertical_slide1\">".$messageGen."</div>";

	}

} else {
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Etape de sauvegarde non ex&eacute;cut&eacute;e par choix de l'utilisateur</div><div id=\"sauvegarde_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
	logWriteTo(7,"error","**- En Test Etape de sauvegarde non executee par choix de l'utilisateur","","","0");
}



exit;

?>
