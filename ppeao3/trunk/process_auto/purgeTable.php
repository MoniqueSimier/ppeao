<?php 
//*****************************************
// purgeTable.php
//*****************************************
// Created by Yann Laurent
// 2008-09-09 : creation
//*****************************************
// Ce programme lance les purges dans la base de donn�es source (donnees artisanales / scientifiques).

//*****************************************
// Param�tres en entr�e
// Param�tres en sortie
// aucun


// Mettre les noms des fichiers dans un fichier texte
session_start();
$_SESSION['s_status_process_auto'] = 'ok';
// Variable de test (en fonctionnement production, les deux variables sont false)
$pasdetraitement = false;
$pasdefichier = false; // Variable de test pour linux. Meme valeur que dans comparaison.php

// Include standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';


// Variables
$nomFenetre="purge";
$ErreurProcess = false ; // Flag pour le succes du traitement
$CRexecution = ""; // compte rendu de traitement
if (isset($_GET['table'])) {
	$tableEnCours = $_GET['table'];
} else {
	$tableEnCours = "";
}

// Pour test...
// temps maximal d'ex�cution du script autoris� par le serveur
$max_time = ini_get('max_execution_time');
// 30 secondes par d�faut:
if ($max_time == '') $max_time = 60;
// pour test
$max_time = 30;
// on prend 10% du temps maximal comme marge de s�curit�
$ourtime = ceil(0.9*$max_time);
// fin test
$ArretTimeOut = false;
// Connexion � la BD pour maj des logs

if (!$connectPPEAO) { 
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connection � la base de donn&eacute;es pour maj des logs</div>" ; 
	exit;
	}
logWriteTo(4,"notice","**- Debut lancement sauvegarde portage automatique.","","","0");

// Param�tres  de sauvegarde
if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine compl�te de traitement automatique (saute cette etape)

	// Connexion aux deux bases de donn�es pour comparaison.
	// **********************************************************
	// Pas besoin de se connecter � la base PPEAO, c'est deja fait dans l'include
	
	$connectBDPECHE =pg_connect ("host=".$host." dbname=".$bd_peche." user=".$user." password=".$passwd);
	if (!$connectBDPECHE) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connection a la base de donn&eacute;es ".$bd_peche."</div>" ; exit;
		}


	// Etape 1 de la purge : suppression des fichier de sauvegarde
	// **********************************************************
	$pathBackup = GetParam("repBackupFicRep",$PathFicConf);
	$pathBackup = $_SERVER["DOCUMENT_ROOT"]."/".$pathBackup;
	$backupName = GetParam("repBackupFicNom",$PathFicConf);
	$FicASupp =  $pathBackup."/".$backupName;
	
	if (file_exists($FicASupp)) {
		if (unlink ($FicASupp) ) {
			$CRexecution = $CRexecution." Suppression du fichier ".$FicASupp." - ";
		} else {
			$ErreurProcess = true;
			$CRexecution = $CRexecution." Erreur suppression fichier ".$FicASupp." - ";
		};
	} else {
		$CRexecution = $CRexecution." Pas de fichier de sauvegarde a supprimer - ";
	}
	


	// Etape 2 de la purge : nettoyage des fichiers de param�trage et de r�f�rence dans la base bdpeche
	// **********************************************************
	$ListeTableAVider = GetParam("listeTableAViderParam",$PathFicConf); 
	$ListeTableAVider = "ref_espece"; // TEST
	
	$tables = explode(",",$ListeTableAVider);
	$nbTables = count($tables) - 1;
	logWriteTo(4,"notice"," Nb tables = ".$nbTables ,"","","1");
	// D�but du traitement de suppression par table.
	// *********************************************
	$start_while=timer(); // d�but du chronom�trage du for
	for ($cpt = 0; $cpt <= $nbTables; $cpt++) {
		if ((!$tableEnCours == "" && $tableEnCours == $tables[$cpt]) || $tableEnCours == "") {
			$tableEnLecture = $tables[$cpt] ;
			
			// Gestion du timeout
			$ourtime = (int)number_format(timer()-$start_while,7);
			$seuiltemps= ceil(0.9*$max_time);
			// On prend un peu de marge par rapport au temps max.
			if ($ourtime >= $seuiltemps) {
				$delai=number_format(timer() - $start_while,7);
				$ArretTimeOut =true;
				break;
			}
	
			$scriptDelete = "delete from ".$tables[$cpt];
			$RunQErreur = runQuery($scriptDelete,$connectBDPECHE);
			if ( $RunQErreur){
				$CRexecution = $CRexecution." ".$tables[$cpt]." videe - ";
			} else {
				$ErreurProcess = true;
				$CRexecution = $CRexecution." Erreur vidage ".$tables[$cpt]." - ";
	
			}
		}	
	}
	
	
	if (!$ArretTimeOut) {
		if ($ErreurProcess) {
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur dans le nettoyage des donn&eacute;es. <br/>".$CRexecution;
		} else {			
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Nettoyage ex&eacute;cut&eacute;e avec succ&egrave;s .<br/>".$CRexecution; 
		}
	
	} else { // End for statement ($ArretTimeOut)
	// Le traitement est relanc� pour cause de timeout, on met a jour le(s) log(s)
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp,"Interruption gestion timeout pour la table ".$tableEnLecture." et Id = ".$IDEnLecture,$pasdefichier);
		}
		logWriteTo(4,"notice","Interruption gestion timeout pour la table ".$tableEnLecture." et Id = ".$IDEnLecture,"","","0");
		// test
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/dep.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Nettoyage de la table ".$_SESSION['s_cpt_table_total']." sur ".$nbTables." <br/>(relance pour eviter Timeout : execution en ".$delai." time maxi = ".$max_time.") </div>";
		echo "<form id=\"formtest\"> 
		<input id=\"nomtable\" 	type=\"hidden\" value=\"".$tableEnLecture."\"/>
		</form>";
	}
	
} else {
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">En Test Etape de sauvegarde non ex&eacute;cut&eacute;e (var pasdetraitement = true)</div>" ;
	logWriteTo(4,"error","**- En Test Etape de sauvegarde non executee (var pasdetraitement = true)","","","0");
}

exit;

?>
