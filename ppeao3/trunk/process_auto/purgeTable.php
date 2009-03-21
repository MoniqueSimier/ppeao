<?php 
//*****************************************
// purgeTable.php
//*****************************************
// Created by Yann Laurent
// 2008-09-09 : creation
//*****************************************
// Ce programme lance les purges dans la base de données source (donnees artisanales / scientifiques).

//*****************************************
// Paramètres en entrée
// Paramètres en sortie
// aucun


// Mettre les noms des fichiers dans un fichier texte
session_start();

// Pour test !
//$_SESSION['s_status_process_auto'] = 'ko' ;
//$_SESSION['s_status_restauration'] = "no";

// Variable de test (en fonctionnement production, les deux variables sont false)
$pasdetraitement = true;
$pasdefichier = false; // Variable de test pour linux. Meme valeur que dans comparaison.php
$affichageDetail = false; // Pour afficher ou non le detail des traitements à l'écran
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
// ***** Test si arret processus lié à l'exécution du traitement précédent 	
// Si le traitement précédent a échoué, arrêt du traitement

//if (isset($_SESSION['s_status_process_auto'])) {
//	if ($_SESSION['s_status_process_auto'] == 'ko') {
//		logWriteTo(7,"error","**- ARRET du traitement de nettoyage des donn&eacute;es car le processus //precedent est en erreur.","","","0");
//		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div //id=\"".$nomFenetre."_txt\"> ARRET du traitement car le processus precedent est en erreur</div>" ;
//		exit;
//	}
//}
if (isset($_GET['exec'])) {
	if ($_GET['exec'] == "false") {
		$pasdetraitement =  true;
		$Labelpasdetraitement ="non";
	} else {
		$pasdetraitement =  false;
		$Labelpasdetraitement ="oui";
	}
} 

if (isset($_GET['table'])) {
	$tableEnCours = $_GET['table'];
} else {
	$tableEnCours = "";
}
if (isset($_GET['log'])) {

	if ($_GET['log'] == "false") {
		$EcrireLogComp = false;// Ecrire dans le fichier de log complémentaire. Attention, cela prend de la ressource !
	} else {
		$EcrireLogComp = true;
	}
}
$dirLog = GetParam("repLogAuto",$PathFicConf);
$nomLogLien = "/".$dirLog; // pour créer le lien au fichier dans le cr ecran
$dirLog = $_SERVER["DOCUMENT_ROOT"]."/".$dirLog;
$fileLogComp = GetParam("nomFicLogSupp",$PathFicConf);
//	Controle fichiers
//	Resultat de la comparaison
	if ($EcrireLogComp ) {
		$nomFicLogComp = $dirLog."/".date('y\-m\-d')."-".$fileLogComp;
		$nomLogLien = $nomLogLien."/".date('y\-m\-d')."-".$fileLogComp;
		$logComp = fopen($nomFicLogComp , "a+");
		if (! $logComp ) {
			$messageGen = " erreur de cr&eacute;ation du fichier de log";
			logWriteTo(7,"error","Erreur de creation du fichier de log ".$dirLog."/".date('y\-m\-d')."-".$fileLogComp." dans comparaison.php","","","0");
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
			exit;		
		}
	}
// Pour test...
// temps maximal d'exécution du script autorisé par le serveur
$max_time = ini_get('max_execution_time');
// 30 secondes par défaut:
if ($max_time == '') $max_time = 60;
// pour test
$max_time = 30;
// on prend 10% du temps maximal comme marge de sécurité
$ourtime = ceil(0.9*$max_time);
// fin test
$ArretTimeOut = false;
// Connexion à la BD pour maj des logs

if (!$connectPPEAO) { 
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connexion à la base de donn&eacute;es pour maj des logs</div>" ; 
	exit;
	}
logWriteTo(7,"notice","**- Debut lancement purge base portage automatique.","","","0");

// Paramètres  de sauvegarde
if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine complète de traitement automatique (saute cette etape)
	logWriteTo(7,"notice","**- Debut lancement purge table (portage automatique)","","","0");
	if ($EcrireLogComp ) {
		WriteCompLog ($logComp, "*******************************************************",$pasdefichier);
		WriteCompLog ($logComp, "*- DEBUT lancement purge table (portage automatique)",$pasdefichier);
		WriteCompLog ($logComp, "*******************************************************",$pasdefichier);
	}
	// Connexion aux deux bases de données pour comparaison.
	// **********************************************************
	// Pas besoin de se connecter à la base PPEAO, c'est deja fait dans l'include
	
	$connectBDPECHE =pg_connect ("host=".$host." dbname=".$bd_peche." user=".$user." password=".$passwd);
	if (!$connectBDPECHE) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connexion a la base de donn&eacute;es ".$bd_peche."</div>" ; exit;
		}


	// Etape 1 de la purge : suppression de la base de sauvegarde
	// **********************************************************
	if (isset($_SESSION['s_status_process_auto'])) { // Devrait toujours etre vrai mais bon....
		$BDBackup = GetParam("backupNomBD",$PathFicConf);
		$BDBackupPortage = $BDBackup."Portage";
		if ($_SESSION['s_status_process_auto'] == 'ko' && $_SESSION['s_status_restauration'] == "yes") {
			$CRexecution = "Lancement de la restauration des bases.<br/>";
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp,"Lancement de la restauration des bases.",$pasdefichier);
			}
			// Si le processus est en erreur, on fait une restauration des bases avant la suppression des 
			// sauvegardes. 
			$ErreurProcess = RestoreBD($CRexecution,$connectPPEAO,$base_principale,$BDBackup,$host,$user,$passwd,$port);
			if (! $ErreurProcess) {
				$connectBDPECHE =pg_connect ("host=".$host." dbname=".$bd_peche." user=".$user." password=".$passwd);
				$ErreurProcess = RestoreBD($CRexecution,$connectBDPECHE,$bd_peche,$BDBackupPortage,$hostname,$username,$password,$port);
			}
		
		} else {
			// Traitement normal : suppression des bases de sauvegardes + purge base de portage
			$BDBackup = GetParam("backupNomBD",$PathFicConf);
			$BDBackupPortage = $BDBackup."Portage";
			$createBDSQL = "drop database ".$BDBackup;
			$createBDResult = pg_query($connectPPEAO,$createBDSQL) or die('erreur dans la requete : '.pg_last_error());
			if ($createBDResult) {
				$CRexecution = "Base de sauvegarde ".$BDBackup." supprim&eacute;e.<br/>";
				logWriteTo(7,"notice","Base de sauvegarde ".$BDBackup." supprimee.","","","0");
			} else {
				$CRexecution = "Erreur suppression ".$BDBackup.".<br/>";
				logWriteTo(7,"error","erreur suppression de la base de donnee de sauvegarde ".$BDBackup,"","","0");
			}
			$connectBDPECHE =pg_connect ("host=".$host." dbname=".$bd_peche." user=".$user." password=".$passwd);
			$createBDSQL = "drop database ".$BDBackupPortage;
			$createBDResult = pg_query($connectBDPECHE,$createBDSQL) or die('erreur dans la requete : '.pg_last_error());
			if ($createBDResult) {
				$CRexecution .= "Base de sauvegarde ".$BDBackupPortage." supprim&eacute;e.<br/>";
				logWriteTo(7,"notice","Base de sauvegarde ".$BDBackupPortage." supprimee.","","","0");
			} else {
				$CRexecution .= "Erreur suppression ".$BDBackupPortage.".<br/>";
				logWriteTo(7,"error","erreur suppression de la base de donnee de sauvegarde ".$BDBackupPortage,"","","0");
			}
		
			// Etape 2 de la purge : nettoyage des fichiers de paramétrage et de référence dans la base bdpeche
			// **********************************************************
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp,"Lancement purge base portage.",$pasdefichier);
			}
			$ListeTableAVider = GetParam("listeTableAViderParam",$PathFicConf); 
			if ($_SESSION['s_status_process_auto'] == 'ok') {
				//$ListeTableAVider = ""; // TEST a recuperer du fichier sinon
				$tables = explode(",",$ListeTableAVider);
				$nbTables = count($tables) - 1;
				logWriteTo(7,"notice"," Nb tables = ".$nbTables ,"","","1");
				// Début du traitement de suppression par table.
				// *********************************************
				$start_while=timer(); // début du chronométrage du for
				// Etape 1 on enleve les contraintes sur les tables
				for ($cpt = 0; $cpt <= $nbTables; $cpt++) {
					$scriptDelete = "ALTER TABLE ".$tables[$cpt]." DISABLE TRIGGER ALL; ";
						$RunQErreur = runQuery($scriptDelete,$connectBDPECHE);
						if ( $RunQErreur){
							
						} else {
							$ErreurProcess = true;
							if ($EcrireLogComp ) {
							WriteCompLog ($logComp,"Erreur suppression Trigger ".$tables[$cpt],$pasdefichier);
		}
							if ($affichageDetail) {
								$CRexecution = $CRexecution."Erreur suppression Trigger ".$tables[$cpt]." <br/> "; }
						}
				}
				// Etape 2: on vide les tables
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
							if ($EcrireLogComp ) {
								WriteCompLog ($logComp,$tables[$cpt]." videe ",$pasdefichier);
							}
							if ($affichageDetail) {
								$CRexecution = $CRexecution." ".$tables[$cpt]." videe <br/> "; }
							
						} else {
							if ($EcrireLogComp ) {
								WriteCompLog ($logComp,"Erreur vidage ".$tables[$cpt],$pasdefichier);
							}
							if ($affichageDetail) {
								$CRexecution = $CRexecution." Erreur vidage ".$tables[$cpt]." <br/> "; 
							}
							$ErreurProcess = true;
							
						} //fin du if ( $RunQErreur)
					} // fin du if ((!$tableEnCours == "" && $tableEnCours == $tables[$cpt]) || $tableEnCours == "")
				}
				// Etape 3 on rajoute les contraintes sur les tables
				for ($cpt = 0; $cpt <= $nbTables; $cpt++) {
					$scriptDelete = "ALTER TABLE ".$tables[$cpt]." ENABLE TRIGGER ALL; ";
					$RunQErreur = runQuery($scriptDelete,$connectBDPECHE);
					if ( $RunQErreur){
						
					} else {
						$ErreurProcess = true;
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp,"Erreur rajout Trigger ".$tables[$cpt],$pasdefichier);
						}
						if ($affichageDetail) {
							$CRexecution = $CRexecution."Erreur rajout Trigger ".$tables[$cpt]." <br/> "; 
						}
					}
				}	
			} else {
				//Lecompte rendu est temporaire car ici systematiquement le process est mis en erreur pour eviter les purges
				//$CRexecution = $CRexecution."Le process etait en erreur, on ne purge pas la base portage.<br/>";
				$CRexecution = $CRexecution."Pas de purge car portage pas encore valid&eacute;.<br/>";
			} // fin du if ($_SESSION['s_status_process_auto'] == 'ok')
		} //fin du if ($_SESSION['s_status_process_auto'] == 'ko' && $_SESSION['s_status_restauration'] == "yes"))
	} // fin du if (isset($_SESSION['s_status_process_auto']))
	
	if (!$ArretTimeOut) {
		if ($ErreurProcess) {
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp,"Erreur dans le nettoyage des donnees.",$pasdefichier);
			}
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur dans le nettoyage des donn&eacute;es. </div><div id=\"".$nomFenetre."_chk\">Exec= ".$Labelpasdetraitement."</div>";
			echo"<div id=\"vertical_slide8\">".$CRexecution."</div>";
		} else {	
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp,"Nettoyage execute avec succes.",$pasdefichier);
			}		
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Nettoyage ex&eacute;cut&eacute; avec succ&egrave;s.</div><div id=\"purge_chk\">Exec= ".$Labelpasdetraitement."</div>";
			echo"<div id=\"vertical_slide8\">Un compte rendu plus d&eacute;taill&eacute; est disponible dans le fichier de log : <a href=\"".$nomLogLien."\" target=\"log\">".$nomLogLien."</a><br/>".$CRexecution."</div>"; 
		}
	
	} else { // End for statement ($ArretTimeOut)
	// Le traitement est relancé pour cause de timeout, on met a jour le(s) log(s)
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp,"Interruption gestion timeout pour la table ".$tableEnLecture." et Id = ".$IDEnLecture,$pasdefichier);
		}
		logWriteTo(7,"notice","Interruption gestion timeout pour la table ".$tableEnLecture." et Id = ".$IDEnLecture,"","","0");
		// test
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/dep.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Nettoyage de la table ".$_SESSION['s_cpt_table_total']." sur ".$nbTables." <br/>(relance pour eviter Timeout : execution en ".$delai." time maxi = ".$max_time.") </div>";
		echo "<form id=\"formtest\"> 
		<input id=\"nomtable\" 	type=\"hidden\" value=\"".$tableEnLecture."\"/>
		</form>";
	}
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp,"*---------------------------------------------",$pasdefichier);
			WriteCompLog ($logComp,"*- FIN TRAITEMENT ",$pasdefichier);
			WriteCompLog ($logComp,"*---------------------------------------------",$pasdefichier);
			logWriteTo(7,"notice","*-- Log plus complet disponible dans <a href=\"".$nomLogLien."\" target=\"log\">".$nomFicLogComp."</a>","","","0");
		}	
} else {
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Etape de purge non ex&eacute;cut&eacute;e par choix de l'utilisateur</div><div id=\"".$nomFenetre."_chk\">Exec= ".$Labelpasdetraitement."</div>";
	logWriteTo(7,"error","**- Etape de purge non executee par choix de l'utilisateur","","","0");
}

exit;

?>
