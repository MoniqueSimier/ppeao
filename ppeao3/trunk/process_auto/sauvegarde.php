<?
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


// Attention, g�rer les fichiers UNIX !!!
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
$host= "localhost";  				// L'h�te					*
$bdd = "Bourlaye";  				//							*
$pathBin = "c:\\\"Program files\"\\PostgreSQL\\8.3\\bin";//		*
$pathBackup =  "C:\\save_base";		//							*
$backupName = "sauvegarde.sql";		//							*
// FIN TEST *****************************************************

// Connexion � la BD pour maj des logs

if (!$connectPPEAO) { 
	echo "<div id=\"sauvegarde_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"sauvegarde_txt\">Erreur de connection � la base de donn�es pour maj des logs</div>" ;; exit;
	}
logWriteTo(4,"notice","**- Debut lancement sauvegarde portage automatique.","","","0");

// Param�tres  de sauvegarde
if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine compl�te de traitement automatique (saute cette etape)
	
	$continueBackup = true;
	$continueDump = true;
	
	// Avant ex�cution du dump, on teste si un fichier de sauvegarde existe d�j�. Si oui, on l'archive
	// *********************************************
	$source = $pathBackup."\\".$backupName;
	$cible = $pathBackup."\\backup\\".date('y\-m\-d\-His').$backupName;
	if (file_exists($source)) {
		// test existence r�pertoire backup. Si n'existe pas le cr�er.
		if (! file_exists($pathBackup."\\backup")) {
			if (! mkdir($pathBackup."\\backup") ) {
				$continueBackup = false;
				$messageGen = "- Erreur de cr�ation du r�pertoire d'archive du dump";
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
	// test de l'existence du r�pertoire de sauvegarde. Si n'existe pas, le cr�er.
	if (! file_exists($pathBackup)) {
		if (! mkdir($pathBackup)) {
			$continueDump = false;
			$messageGen = "- Erreur de cr�ation du r�pertoire de sauvegarde";
			logWriteTo(4,"error","Erreur de creation du r�pertoire d'archive dans sauvegarde.php","","","0");
		}
	}
	
	// Si le r�pertoire de dump existe ou a �t� correctement cr�� alors on peut continuer !
	if ($continueDump) {
		logWriteTo(4,"notice","Execution de la commande pg_dump.",$pathBin."\\pg_dump -U ".$user." -c -d -f ".$pathBackup."\\".$backupName." ".$bdd,"","0");
		// La commande pour lancer la cr�ation du sql est la commande postgre pg_dump, qui ne peut �tre ex�cut�e qu'en ligne de commande.
		// La saisie du mot de passe pour l'instant est obligatoire.
		// *********************************************
		$command = $pathBin."\\pg_dump -U ".$user." -c -d -f ".$pathBackup."\\".$backupName." ".$bdd;
		exec($command);
		
		
		// On teste l'existence du fichier dans le r�pertoire seul indicateur de la r�ussite de la sauvegarde
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
