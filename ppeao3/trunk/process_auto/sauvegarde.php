<?
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


// Attention, gérer les fichiers UNIX !!!
// Mettre les noms des fichiers dans un fichier texte

// Variable de test
$pasdetraitement = true;

// Include standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';


// Pour l'instant ici pour du test ******************************
// connexion a BD_PECHE											*
$user="devppeao";                   // Le nom d'utilisateur 	*
$passwd="2devppe!!";                // Le mot de passe 			*
$host= "localhost";  				// L'hôte					*
$bdd = "Bourlaye";  				//							*
$pathBin = "c:\\\"Program files\"\\PostgreSQL\\8.3\\bin";//		*
$pathBackup =  "C:\\save_base";		//							*
$backupName = "sauvegarde.sql";		//							*
// FIN TEST *****************************************************

// Connexion à la BD pour maj des logs

if (!$connectPPEAO) { 
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Erreur de connection à la base de données pour maj des logs</div>" ;; exit;
	}
logWriteTo(4,"notice","**- Debut lancement sauvegarde portage automatique.","","","0");

// Paramètres  de sauvegarde
if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine complète de traitement automatique (saute cette etape)
	
	$continueBackup = true;
	$continueDump = true;
	
	// Avant exécution du dump, on teste si un fichier de sauvegarde existe déjà. Si oui, on l'archive
	// *********************************************
	$source = $pathBackup."\\".$backupName;
	$cible = $pathBackup."\\backup\\".date('y\-m\-d\-His').$backupName;
	if (file_exists($source)) {
		// test existence répertoire backup. Si n'existe pas le créer.
		if (! file_exists($pathBackup."\\backup")) {
			if (! mkdir($pathBackup."\\backup") ) {
				$continueBackup = false;
				$messageGen = "- Erreur de création du répertoire d'archive du dump";
				logWriteTo(4,"error","Erreur de creation du repertoire d'archive du dump dans sauvegarde.php","","","0");
			}
		}
	
		if ($continueBackup) { 
			if (! copy($source,$cible) ){
				$messageGen = "- Erreur archivage de ".$source." dans ".$cible;
				logWriteTo(4,"error","Erreur archivage de ".$source." dans ".$cible,"","","0");
			} else {
				$messageGen = "- Archivage fichier backup dans ".$cible;
				logWriteTo(4,"notice","Archivage fichier backup dans ".$cible,"","","0");
			}
		}
	} else {
		logWriteTo(4,"error"," pas d'archivage fichier backup","","","1"); 
	}
	// test de l'existence du répertoire de sauvegarde. Si n'existe pas, le créer.
	if (! file_exists($pathBackup)) {
		if (! mkdir($pathBackup)) {
			$continueDump = false;
			$messageGen = "- Erreur de création du répertoire de sauvegarde";
			logWriteTo(4,"error","Erreur de creation du répertoire d'archive dans sauvegarde.php","","","0");
		}
	}
	
	// Si le répertoire de dump existe ou a été correctement créé alors on peut continuer !
	if ($continueDump) {
		logWriteTo(4,"notice","Execution de la commande pg_dump.",$pathBin."\\pg_dump -U ".$user." -c -d -f ".$pathBackup."\\".$backupName." ".$bdd,"","0");
		// La commande pour lancer la création du sql est la commande postgre pg_dump, qui ne peut être exécutée qu'en ligne de commande.
		// La saisie du mot de passe pour l'instant est obligatoire.
		// *********************************************
		$command = $pathBin."\\pg_dump -U ".$user." -c -d -f ".$pathBackup."\\".$backupName." ".$bdd;
		exec($command);
		
		
		// On teste l'existence du fichier dans le répertoire seul indicateur de la réussite de la sauvegarde
		if (file_exists($pathBackup."\\".$backupName)) {
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde ex&eacute;cut&eacute;e avec succ&egrave;s dans ".$pathBackup." ".$messageGen."</div>" ;
			logWriteTo(4,"notice","Sauvegarde executee avec succes dans ".$pathBackup." ".$messageGen,"","","0");
		} else {
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur : pas de fichier de sauvegarde cr&eacute;&eacute; ".$messageGen."</div>" ;
			logWriteTo(4,"error","Sauvegarde en erreur : pas de fichier de sauvegarde cree ".$messageGen,"","","0");
		}
	} else {
			echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Sauvegarde en erreur : pas de fichier de sauvegarde cr&eacute;&eacute; ".$messageGen."</div>" ;
			logWriteTo(4,"error","Sauvegarde en erreur : pas de fichier de sauvegarde cree ".$messageGen,"","","0");
	}


} else {
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">En Test Etape de sauvegarde non ex&eacute;cut&eacute;e (var pasdetraitement = true)</div>" ;
	logWriteTo(4,"error","En Test Etape de sauvegarde non executee (var pasdetraitement = true)","","","0");
}
exit;

?>
