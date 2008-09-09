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
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';

// Recuperation des parametres (nom repertoire, nom fichiers etc..) depuis le fichier de parametres
$dirLog = GetParam("repLogAuto",$PathFicConf);
$dirLog = $_SERVER["DOCUMENT_ROOT"]."/".$dirLog;
$pathBackup = GetParam("repBackupFicRep",$PathFicConf);
$pathBackup = $_SERVER["DOCUMENT_ROOT"]."/".$pathBackup;
$backupName = GetParam("repBackupFicNom",$PathFicConf);
$pathBin = GetParam("repPGDump",$PathFicConf); // pour windows

// On remet à zéro le fichier reverse SQL. On le fait ici même si on n'utilise pas le fichier ici
// car il sera plus difficile d'identifier dans comparaison.php le premier appel de ce programme
$ficRevSQL = OpenFileReverseSQL ("ecras",$dirLog,$pasdefichier);
CloseFileReverseSQL ($ficRevSQL,$pasdefichier);

// Connexion à la BD pour maj des logs

if (!$connectPPEAO) { 
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Erreur de connection à la base de donn&eacute;es pour maj des logs</div>" ;; exit;
	}
logWriteTo(4,"notice","**- Debut lancement sauvegarde portage automatique.","","","0");

// Paramètres  de sauvegarde
if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine complète de traitement automatique (saute cette etape)

	$continueDump = true;
	
	// Avant exécution du dump, on teste si un fichier de sauvegarde existe déjà. Si oui, on l'archive
	// *********************************************
	//$source = $pathBackup."\\".$backupName;
	$cible = $pathBackup."/".date('y\-m\-d\-His')."-".$backupName;
	$source = $pathBackup."/".$backupName;
	if (file_exists($source)) {
		// copie du fichier deja existant en le renomant.
		if (! rename($source,$cible) ){
			$messageGen = "- Erreur archivage de ".$source." dans ".$cible;
			logWriteTo(4,"error","Erreur archivage de ".$source." en ".$cible,"","","0");
		} else {
			$messageGen = "- Archivage fichier existant dans ".$cible;
			logWriteTo(4,"notice","Archivage fichier existant dans ".$cible,"","","0");
		}
	} else {
		logWriteTo(4,"notice","Pas de copie de fichier existant","","","1"); 
		// test de l'existence du répertoire de sauvegarde. Si n'existe pas, le créer.
		if (! file_exists($pathBackup)) {
			if (! mkdir($pathBackup)) {
				$continueDump = false;
				$messageGen = "- Erreur de cr&eacute;ation du r&eacute;pertoire de sauvegarde ".$pathBackup;
				logWriteTo(4,"error","Erreur de creation du repertoire d'archive dans sauvegarde.php ".$pathBackup,"","","0");
			}
		}		
	}

	
	// Si le répertoire de dump existe ou a été correctement créé alors on peut continuer !
	if ($continueDump) {
		//logWriteTo(4,"notice","Execution de la commande pg_dump.",$pathBin."\\pg_dump -U ".$user." -c -d -f ".$pathBackup."\\".$backupName." ".$bdd,"","0");
		logWriteTo(4,"notice","Execution de la commande pg_dump.",$pathBin."pg_dump -U ".$user."  -d -Fc ".$pathBackup."/".$backupName." ".$bdd,"","0");
		// La commande pour lancer la création du sql est la commande postgre pg_dump, qui ne peut être exécutée qu'en ligne de commande.
		// La saisie du mot de passe pour l'instant est obligatoire.
		// *********************************************
		//$command = $pathBin."\\pg_dump -U ".$user." -c -d -f ".$pathBackup."\\".$backupName." ".$bdd;
		$command =  $pathBin."pg_dump -U ".$user." -c -d -f ".$pathBackup."/".$backupName." ".$bdd;
		exec($command);

		
		// On teste l'existence du fichier dans le répertoire seul indicateur de la réussite de la sauvegarde
		//if (file_exists($pathBackup."\\".$backupName)) {
		if (file_exists($pathBackup."/".$backupName)) {
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde ex&eacute;cut&eacute;e avec succ&egrave;s dans ".$pathBackup."/".$backupName." ".$messageGen."</div>" ;
			logWriteTo(4,"notice","**- Fin Sauvegarde : executee avec succes dans ".$pathBackup."/".$backupName." ".$messageGen,"","","0");
		} else {
			if (isset($_SESSION['s_status_process_auto'])) { $_SESSION['s_status_process_auto'] = "ko"; }
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur : pas de fichier de sauvegarde cr&eacute;&eacute; ".$messageGen."</div>" ;
			logWriteTo(4,"error","**- Fin Sauvegarde : en erreur : pas de fichier de sauvegarde cree ".$messageGen,"","","0");
		}
	} else {
			if (isset($_SESSION['s_status_process_auto'])) { $_SESSION['s_status_process_auto'] = "ko"; }
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur : pas de fichier de sauvegarde cr&eacute;&eacute; ".$messageGen."</div>" ;
			logWriteTo(4,"error","**- Fin Sauvegarde : en erreur : pas de fichier de sauvegarde cree ".$messageGen,"","","0");
	}


} else {
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">En Test Etape de sauvegarde non ex&eacute;cut&eacute;e (var pasdetraitement = true)</div>" ;
	logWriteTo(4,"error","**- En Test Etape de sauvegarde non executee (var pasdetraitement = true)","","","0");
}



exit;

?>
