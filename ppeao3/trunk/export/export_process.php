<?php 
//*****************************************
// export_process.php
//*****************************************
// Created by Yann Laurent
// 2008-12-08 : creation
//*****************************************
// Ce programme lance l'export 
// Le résultat du traitement est envoyé à portage_auto.php dans deux div qui seront insérés dans le div général 
// avec une icone de bonne ou mauvaise exécution (dans div id="xxxxxxx_img") et l'explication
// de l'erreur dans div id = "xxxxx_txt"
//*****************************************
// Paramètres en entrée


// Paramètres en sortie
// La liste des différences par table est affichée à l'écran et est stockée dans un fichier


// Attention l'activation de l'ecriture dans la table des logs peut amener a des performances catastrophiques (la table peut rapidement etre enorme
// Privilegier plutot l'ecriture dans le fichier log complémentaire


session_start();
// Variable qui permet d'identifier si le traitement est lancé
$pasdetraitement = true;
$pasdefichier = false;
$cptAjoutMaj = 0; // pour compatibilite

$debugAff = false; // variable globale pour lancer le programme en mode debug
// Variables de traitement
$ErreurProcess = false; // Flag si erreur process
$affichageDetail = false; // Pour afficher ou non le detail des traitements à l'écran
// Includes standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/export/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/export/config.php';
// ***** Recuperation des parameters en entree 

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

// On récupère le type d'action. Le même programme gère la comparaison et la mise à jour de données
if (isset($_GET['action'])) {
	$typeAction = $_GET['action'];
	switch($typeAction){
		case "copPPEAO":
			$nomFenetre = "copiePPEAO";
			$nomAction = "Copie des donnees depuis la base PPEAO (postgreSQL) de reference.";
			$numFen = 3;
			break;	
		case "copAC":
			$nomFenetre = "copieACCESS";
			$nomAction = "Copie des donnees depuis la base ACCESS de reference.";
			$numFen = 4;
			break;

	}

} else { 
	$nomFenetre = "copieACCESS";
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Il manque le parametre action. Contactez votre admin PPEAO</div>" ;
	exit;
}

$ExecSQL = "n";
$nomBDSource = ""; 
$nomBDCible = ""; 
$allScriptSQL = "";
// Pour la gestion des timeout liés à l'utilisation d'AJAX.
// Parfois le temps de traitement d'une table est trop long.
// On doit interrompre le traitement, envoyer un message au javascript pour lui
// dire de relancer le process avec le nom de la table en cours et le numero
// de l'enregistrement en cours de lecture.
// comparaison.php est alors rappelé avec des paramètres.

// On récupère ici les paramètres de timeout.

$tableEnCours = "";
$IDEnCours = 0;

if (isset($_GET['table'])) {
	$tableEnCours = $_GET['table'];

}  
if (isset($_GET['numenreg'])) {
	// Est-ce que l'ID est un num ?
	$ListeTableIDPasNum = GetParam("listeTableIDPasNum",$PathFicConf);
	$testTtypeID = strpos($ListeTableIDPasNum ,$tableEnCours);
	if ($testTtypeID === false) {
		// L'ID est bien un numérique
		$IDEnCours = intval($_GET['numenreg']);
	} else {
		// L'ID est une chaine
		$IDEnCours = "'".$_GET['numenreg']."'";
	}
}
if (isset($_GET['numproc'])) {
	$numProcess = $_GET['numproc'];
}
if (isset($_GET['log'])) {

	if ($_GET['log'] == "false") {
		$EcrireLogComp = false;// Ecrire dans le fichier de log complémentaire. Attention, cela prend de la ressource !
	} else {
		$EcrireLogComp = true;
	}
}

if (isset($_GET['tp'])) {
	$typePeche =$_GET['tp'];
} else {
	echo "erreur pas de parametre tp <br/>";
	exit;
}



// Deux variables pour stocker les tables / ID en cours de lecture pour être capable de les renvoyer si pb de timeout detecte
$tableEnLecture = "";
$IDEnLecture = 0 ;
$ArretTimeOut = false;
$dumpTable = false;

// Pour test...
// temps maximal d'exécution du script autorisé par le serveur
$max_time = ini_get('max_execution_time');
// 30 secondes par défaut:
if ($max_time == '') $max_time = 30;
// on prend 10% du temps maximal comme marge de sécurité
$ourtime = ceil(0.9*$max_time);
// fin test

// ***** Test si arret processus lié à l'exécution du traitement précédent 	
// Si le traitement précédent a échoué, arrêt du traitement

if (isset($_SESSION['s_status_export'])) {
	if ($_SESSION['s_status_export'] == 'ko') {
		logWriteTo(8,"error","**- ARRET du traitement ".$nomAction." car le processus precedent est en erreur.","","","0");
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\"> ARRET du traitement car le processus precedent est en erreur</div>" ;
		exit;
	}
}


// ***** Variables de traitements
$CRexecution = ""; 			// Variable contenant le résultat du traitement
$cptChampTotal = 0;			// Lecture d'une table, nombre d'enregistrements lus total
$cptTableTotal = 0;			// Nombre global de tables lues
$cptTableVide = 0;			// Nombre global de tables vides dans cible 
$cptTableSourceVide = 0;	// Nombre global de tables vides dans source 
$cptSQLErreur = 0 ;			// Nombre d'erreur lors de la mise a jour de la table
$scriptSQL = "";			// Stockage du script SQL à exécuter pour créer ou maj les données
$logComp="";
$TotalLignesFichier = 0; 	// compteur pour gerer la taille des fichiers SQL
$cptMajACCtoACC = 0;

// On récupère les valeurs des paramètres pour les fichiers log
$dirLog = GetParam("repLogAccess",$PathFicConfAccess);
$nomLogLien = "/".$dirLog; // pour créer le lien au fichier dans le cr ecran
$dirLog = $_SERVER["DOCUMENT_ROOT"]."/".$dirLog;
$fileLogComp = GetParam("nomFicLogSuppAc",$PathFicConfAccess);



// Initialisation si on demarre un nouveau traitement
if ($tableEnCours == "") {
	$_SESSION['s_CR_export'] = "";
	$_SESSION['s_cpt_exp_champ_total'] = 0;
	$_SESSION['s_cpt_exp_table_vide'] = 0;
	$_SESSION['s_cpt_exp_table_manquant'] = 0; 
	$_SESSION['s_cpt_exp_erreurs_sql'] = 0; 
}

// ***** Debut du traitement

if (! $pasdetraitement ) { // Permet de sauter cette étape (choix de l'utilisateur ou debug)

// Traitements préliminaires : 
// *********************************************
//	Contrôle des répertoires et fichiers log
// 		Controle répertoire
	if (! $pasdefichier) { // Pour test sur serveur linux
		if (! file_exists($dirLog)) {
			if (! mkdir($dirLog) ) {
				$messageGen = " erreur de cr&eacute;ation du r&eacute;pertoire de log";
				logWriteTo(8,"error","Erreur de creation du repertoire de log dans comparaison.php","","","0");
				echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
				exit;
			}
		}
	//	Controle fichiers
	//	Resultat de la comparaison
		if ($EcrireLogComp ) {
			$nomFicLogComp = $dirLog."/".date('y\-m\-d')."-".$fileLogComp;
			$nomLogLien = $nomLogLien."/".date('y\-m\-d')."-".$fileLogComp;
			$logComp = fopen($nomFicLogComp , "a+");
			if (! $logComp ) {
				$messageGen = " erreur de cr&eacute;ation du fichier de log";
				logWriteTo(8,"error","Erreur de creation du fichier de log ".$dirLog."/".date('y\-m\-d')."-".$fileLogComp." dans comparaison.php","","","0");
				echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
				exit;		
			}
		}
	}	
	// Récupération des tables à comparer
	// *********************************************
	// listes ci-dessous pour les tests...
	switch($typeAction){
		case "copAC":
			// Copie entre bases ACCESS (reference et travail)
			$nomAction 	= "Copie param de la base ACCESS de ref";
			switch ($typePeche) { 
				case "exp":
					$listTable 	= GetParam("listeTableRefExpACCESS",$PathFicConfAccess);
					break;
				case "art":
					$listTable 	= GetParam("listeTableRefArtACCESS",$PathFicConfAccess);
					break;	
			}

			 break;
		case "copPPEAO":
			// Copie entre base postgres de ref (PPEAO) et base ACCESS de travail
			$nomAction = "Copie param de la base POSTGRESQL (PPEAO) de ref";
			switch ($typePeche) { 
				case "exp":
					$listTable = GetParam("listeTableRefExpPPEAO",$PathFicConfAccess);
					break;
				case "art":
					$listTable = GetParam("listeTableRefArtPPEAO",$PathFicConfAccess);
					break;	
			}
			break;
	}
	$NbrTableAlire = substr_count($listTable,",");
	if ($NbrTableAlire == 0) {
		$NbrTableAlire = 1;
	} else {
		$NbrTableAlire += 1;
	}
	// Récupération des paramètres de connexion aux bases ACCESS
	// Note: le nom de la connexion ODBC doit etre le meme que le nom du fichier .mdb
	switch ($typePeche) { 
		case "exp":
			$BDACCESS = GetParam("nomBDRefExp",$PathFicConfAccess);
			$nomBDSource = "Base ACCESS de ref";
			$BDSource = "connectAccess";
			$BDCible = "connectAccessTravail";
			$nomPeche = "peches experimentales";
			break;
		case "art":
			$BDACCESS = GetParam("nomBDRefArt",$PathFicConfAccess);
			$nomBDSource = "Base POSTGRESQL de ref (PPEAO)";
			$BDSource = "connectPPEAO";
			$BDCible = "connectAccessTravail";
			$nomPeche = "peches artisanales";
			break;	
	}
	$nomAction = $nomAction." ".$nomPeche;
	$BDACCESSTravail = $BDACCESS."_travail";	
	$nomBDCible = "Base ACCESS de travail";
	// Test de la connexion à la BD de ref (pour log entre autre)
	if (!$connectPPEAO) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connexion a la base de donn&eacute;es BD_PPEAO pour maj des logs</div>" ; exit;
	}
	// Test connexion base de travail
	$connectAccess = odbc_connect($BDACCESS,'','',SQL_CUR_USE_ODBC);
	if (!$connectAccess) {
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "Erreur de la connection à la base ACCESS de reference ".$BDACCESS,$pasdefichier);
		}
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connexion a la base ACCESS de reference ".$BDACCESS."</div>" ; exit;
	} 
	$connectAccessTravail = odbc_connect($BDACCESSTravail,'','');
	if (!$connectAccessTravail) {
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "Erreur de la connection à la base ACCESS de travail".$BDACCESSTravail,$pasdefichier);
		}
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connexion a la base ACCESS de travail ".$BDACCESSTravail."</div>" ; exit;
	}
	
	// Initialisation des logs
	if ($tableEnCours == "") {
		logWriteTo(8,"notice","**- Debut lancement ".$nomAction." (copie)","","","0");
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "*******************************************************",$pasdefichier);
			WriteCompLog ($logComp, "*- DEBUT lancement ".$nomAction." (copie)",$pasdefichier);
			WriteCompLog ($logComp, "*------------------------------------------------------",$pasdefichier);
		}
	} else {
		logWriteTo(8,"notice","**- Relance traitement pour la table ".$tableEnCours." a partir de l'enreg ID = ".$IDEnCours." (gestion TIEMOUT AJAX)","","","0");
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "Relance traitement pour la table ".$tableEnCours." a partir de l'enreg ID = ".$IDEnCours." (gestion TIEMOUT AJAX)",$pasdefichier);
		}
	}
	// Paramètres  de comparaison.
	// *********************************************
	// Lancement de la comparaison. On met à jour la variable contenuDiv avec le résultat de la comparaison.
	// On met à jour le fichier de log spécifique avec plus de détails.

	$tables = explode(",",$listTable);
	$nbTables = count($tables) - 1;
	// Début du traitement de comparaison par table.
	// *********************************************


	// *************************************************
	// Traitement de comparaison
	// *************************************************
	if (!$ArretTimeOut ) {
	
		$start_while=timer(); // début du chronométrage du for
		for ($cpt = 0; $cpt <= $nbTables; $cpt++) {
			// controle de la table en cours si besoin (gestion TIMEOUT)
			if ((!$tableEnCours == "" && $tableEnCours == $tables[$cpt]) || $tableEnCours == "") {
			
			// Reinitialisation des compteurs
			$cptChampTotal = 0;
			$cptChampDiff = 0;
			$cptChampVide = 0;
			$cptSQLErreur = 0 ;
			$cptMajACCtoACC = 0;
			$cptMajPOSTtoACC = 0;
			$tableVide = false;
			$tableSourceVide = false;
			$dumpTable = false;
			$STOPtrt = false;
			if ($tableEnCours == "") {
				$cptTableTotal++;
				$ErreurProcess = false;
				$_SESSION['s_cpt_exp_champ_total'] 	= 0;
				$_SESSION['s_erreur_process'] 		= false;
			} else {
				// on reinitialise les valeurs avec les variables de session mise à jour lors du traitement précédent
				$CRexecution 	= $_SESSION['s_CR_export'];
				$cptChampTotal 	= $_SESSION['s_cpt_champ_total'];
				$cptTableVide	= $_SESSION['s_cpt_table_vide'];
				$cptTableLignesVides = $_SESSION['s_cpt_table_manquant']; 
				$ErreurProcess 	= $_SESSION['s_erreur_process'];
				// On reinitialise pour eviter de compter deux fois les memes donnees
				$_SESSION['s_CR_export'] 	= "";
				$_SESSION['s_cpt_champ_total'] 	= 0;
				$_SESSION['s_cpt_table_vide'] 	= 0;
			}
			// Reinitialisation variable pour creation SQL
			$where="";
			$alias="";
			$continueControle = true;
			// Pour construire une requete compatible ACCESS et POSTGRESQL
			switch($typeAction){
					case "copAC":
						$nomTableEC = $tables[$cpt];
						break; 
					case "copPPEAO":
						$ficXMLDef = $_SERVER["DOCUMENT_ROOT"]."/conf/AccessConv".$typePeche.".xml";
						if (! file_exists($ficXMLDef )) {
						$CRexecution .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Le fichier ".$ficXMLDef." n existe pas..<br/>";
							$continueControle = false;
							$erreurProcess = true;
						} else {
							$nomTableEC = getTableNamePostGRE($ficXMLDef,$tables[$cpt]);
						}
						break;
				}
			// Gestion TIMEOUT
			$tableEnLecture = $tables[$cpt]; // On garde ici le nom de la table ACCESS pour le timeout, on le teste en tout debut de boucle			
			
			if ($continueControle) {
				// ************************************************
				// Gestion TIMEOUT : on reprend la ou on s'etait arrete
				// Comme on trie par ID, on ne va pas en perdre en route
				if ($tableEnCours == "") {
					$condWhere = "";
				} else {
					$condWhere = " where id > ".$IDEnCours; // attention, pour la table access on ne peut pas se baser sur l'id...
				}
				$orderBy = " ";
				// Lecture de la table $tables[$cpt] dans la base source (BD_PPEAO dans le cas de la comparaison, 
				// BD_PECHE dans le cas de la mise à jour)
				
				// Compteur 
				//echo "table en cours ".$nomTableEC."<br/>";
				$compReadSqlC = "select count(*) from ".$nomTableEC;
				switch($typeAction){
					case "copAC":
						$compReadResultC = odbc_exec($connectAccess,$compReadSqlC);
						$erreurSQL = odbc_errormsg($connectAccess); //
						break; 
					case "copPPEAO":
						$compReadResultC = pg_query($connectPPEAO,$compReadSqlC);
						$erreurSQL = pg_last_error($connectPPEAO);
						break;
				}
				if ( !$compReadResultC ) { 
					$CRexecution .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur access table ".$nomTableEC." pour ".$nomBDSource." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
					$erreurProcess = true;
					$continueControle = false ;
				} else {
					switch($typeAction){
						case "copAC":
							$compRowC = odbc_fetch_row($compReadResultC);
							$totalLignes = odbc_result($compReadResultC,1); 
							odbc_free_result($compReadResultC);
							break; 
						case "copPPEAO":
							$compRowC = pg_fetch_row($compReadResultC); 
							$totalLignes = $compRowC[0];
							pg_free_result($compReadResultC);
							break;
					}
				}
				//echo "total lignes dans ".$nomTableEC." = ".$totalLignes."<br/>";
				// ***************************************
				// Lecture de la table dans la base source
				// ***************************************
				$compReadSql = " select * from ".$nomTableEC.$condWhere.$orderBy;
				switch($typeAction){
					case "copAC":
						$compReadResult = odbc_exec($connectAccess,$compReadSql);
						$erreurSQL = odbc_errormsg($connectAccess); //
						break; 
					case "copPPEAO":
						$compReadResult = pg_query($connectPPEAO,$compReadSql);
						$erreurSQL = pg_last_error($connectPPEAO);
						break;
				}
				if ( !$compReadResultC ) { 
				$CRexecution .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur access table ".$nomTableEC." pour ".$nomBDSource." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
					$erreurProcess = true;
					$continueControle = false ;
				} 
				// *****************************************************
				// analyse de la requete, génération et exécution du SQL
				// *****************************************************
				if ($continueControle) {
				if ($totalLignes  == 0) {
					// La table dans bdppeao est vide
					if ($EcrireLogComp ) { WriteCompLog ($logComp,"Table de reference ".$nomTableEC." dans ".$nomBDSource." vide",$pasdefichier);}
					$tableSourceVide = true;
				} else {
					// On va balayer tous les enreg (ligne) de la table controlée
					switch($typeAction){
						case "copAC":
							$compRow = array();
							// found on php.net, il y a un pb avec odbc_fetch_row.
							// D'ou ce $bug_workaround
							$bug_workaround=0;
							
							$row_num = 1; 
							
							while ($compRow = odbc_fetch_array($compReadResult)) {
								// Gestion du timeout
								$ourtime = (int)number_format(timer()-$start_while,7);
								$seuiltemps= ceil(0.9*$max_time);
								// On prend un peu de marge par rapport au temps max.
								if ($ourtime >= ceil(0.9*$max_time)) {
									if ($EcrireLogComp ) { WriteCompLog ($logComp,"TIMEOUT: break",$pasdefichier);}
									$delai=number_format(timer() - $start_while,7);
									$ArretTimeOut =true;
									break;
								}
								//echo "<br/>lecture ".$compRow[0]." - ".$compRow[1];
								$scriptSQL = GetSQLACCESS('insert',  $nomTableEC, $where, $compRow,"ACCESS",$tables[$cpt],$connectAccessTravail,$connectPPEAO,$typePeche);
								$testPos = strpos($scriptSQL,"*-ERREUR*-" );
								//echo $scriptSQL."<br/>";
								if ($testPos === false){
									$execSQLResult = odbc_exec($connectAccessTravail,$scriptSQL);
									$erreurSQL = odbc_errormsg($connectAccessTravail); //
									if (! $execSQLResult) {
										echo "erreur dans ".$scriptSQL."<br/>";
										$cptSQLErreur ++;
									} else {
										$cptMajACCtoACC ++;
									}								
								} else {
									// Erreur dans l'execution de la creation du script, en general lie a une erreur dans le fichier de definition du dico de la table
									$CRexecution .= str_replace("*-ERREUR*-","*- ERREUR : ",$scriptSQL);
									$STOPtrt = true;
									// On arrete le traitement sur cette table, pas la peine de continuer...
									break;
								}
							} // end while (odbc_fetch_row($compReadResult))
							break;
						case "copPPEAO":
						//echo $tables[$cpt]."<br/>";
							while ($compRow = pg_fetch_array($compReadResult)) {
								// Gestion du timeout
								$ourtime = (int)number_format(timer()-$start_while,7);
								$seuiltemps= ceil(0.9*$max_time);
								// On prend un peu de marge par rapport au temps max.
								if ($ourtime >= ceil(0.9*$max_time)) {
									if ($EcrireLogComp ) { WriteCompLog ($logComp,"TIMEOUT: break",$pasdefichier);}
									$delai=number_format(timer() - $start_while,7);
									$ArretTimeOut =true;
									break;
								}
								$scriptSQL = GetSQLACCESS('insert',  $nomTableEC, $where, $compRow,"POSTGRE",$tables[$cpt],$connectAccessTravail,$connectPPEAO,$typePeche);
								//echo $scriptSQL."<br/>";
								//if ($EcrireLogComp ) {
								//	WriteCompLog ($logComp,$scriptSQL,$pasdefichier);
								//}
								$testPos = strpos($scriptSQL[0],"*-ERREUR*-" );
								if ($testPos === false){
									// Il semble que le connecteur ODBC n'aime pas les scripts SQL avec plusieurs instructions. Donc, on eclate le scripts et on execute les instructions une a une.
									// Pour gagner du temps, on separe les deux comportements, une seule instruction, plus d'une instruction
									if (strpos($scriptSQL,"#;#" ) === false ) {
										// Execution de l'instruction unique
										$execSQLResult = odbc_exec($connectAccessTravail,$scriptSQL);
										$erreurSQL = odbc_errormsg($connectAccessTravail); //
										if (! $execSQLResult) {
											if ($EcrireLogComp ) {
														WriteCompLog ($logComp,"ERREUR ".$tables[$cpt]."erreur dans ".$scriptSQL." (erreur complete =".$erreurSQL.")",$pasdefichier);
											} else {
												echo "erreur dans ".$scriptSQL." (erreur complete =".$erreurSQL.")<br/>";
											}
											$ErreurProcess = true;
											$cptSQLErreur ++;
										} else {
											$cptMajPOSTtoACC++;
										}									

									} else {
										// Execution de toutes les instructions
										$InstructionSQL = explode("#;#",$scriptSQL);
										$nbInstructions = count($InstructionSQL) - 1;
										for ($cptIns = 0; $cptIns <= $nbInstructions; $cptIns++) {
											if ($InstructionSQL[$cptIns] == ""){
												if ($EcrireLogComp ) {
														WriteCompLog ($logComp,"SQL vide pour table ".$tables[$cpt],$pasdefichier);
												} else {
													echo "SQL vide pour table ".$tables[$cpt]."<br/>";
												}
											} else {
											//echo $InstructionSQL[$cptIns]."<br/>";
												$execSQLResult = odbc_exec($connectAccessTravail,$InstructionSQL[$cptIns]);
												$erreurSQL = odbc_errormsg($connectAccessTravail); //
												if (! $execSQLResult) {
													if ($EcrireLogComp ) {
														WriteCompLog ($logComp,"ERREUR TABLE ".$tables[$cpt]." : pour instruction ".$InstructionSQL[$cptIns],$pasdefichier);																									
														WriteCompLog ($logComp,"COMPLEMENT ERREUR erreur complete =".$erreurSQL,$pasdefichier);
														
													} else {
														echo "erreur dans ".$InstructionSQL[$cptIns]." (erreur complete =".$erreurSQL.")<br/>";
													}
													$ErreurProcess = true;
													$cptSQLErreur ++;
												} else {
													$cptMajPOSTtoACC++;
												}
											odbc_free_result($execSQLResult);											
											}
										
										}
									}
																	
								} else {
									// Erreur dans l'execution de la creation du script, en general lie a une erreur dans le fichier de definition du dico de la table
									$CRexecution .= str_replace("*-ERREUR*-","*- ERREUR : ",$scriptSQL);
									$STOPtrt = true;
									// On arrete le traitement sur cette table, pas la peine de continuer...
									break;
								}
	
							} // end while ($compRow = pg_fetch_row($compReadResult))
							break;
						}
					// Controle si sortie par timeout ou 
					if ($ArretTimeOut || $STOPtrt) {
						// on sort de la la boucle for
						break;
					}
					// TIMEOUT, reinitialisation des variables EnCours
					$IDEnCours = 0;
					$tableEnCours = "";
				} // end if($totalLignes  == 0)
				} // end if(!$erreurprocess)
				
				// Libère la requete sur bdppeao
				switch($typeAction){
					case "copAC":
						odbc_free_result($compReadResult);
						break; 
					case "copPPEAO":
						pg_free_result($compReadResult);
						break;
				}

			} // end if ($continueControle) 
			if (!$ArretTimeOut && $continueControle) {
				// On aura deux comptes-rendus selon si c'est une comparaison ou une mise à jour
				// Dans le cas de la comparaison, on indique les différents cas trouvés.
				// Dans le cas de la maj, on n'indique juste le type de maj
				$CRexecution = $CRexecution."*- ".$tables[$cpt]." : ";
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." : ".$nomAction,$pasdefichier);
					//WriteCompLog ($logComp,"TEST champvide = ".$cptChampVide." champDiff ".$cptChampDiff." tableVide ".$tableVide,$pasdefichier);
				}
				if ($tableSourceVide) {
					$cptTableSourceVide++;
					$CRexecution = $CRexecution." <img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;".$tables[$cpt]." source vide -";
					if ($EcrireLogComp ) {
						WriteCompLog ($logComp," Cette table source est vide.",$pasdefichier);
					}
				} else {
					
					if ($ErreurProcess) {
						// On garde en memoire l'erreur pour cette table pour le refleter sur le traitement global
						if (!$_SESSION['s_erreur_process']){
							$_SESSION['s_erreur_process'] = $ErreurProcess;
						}
						$CRexecution = $CRexecution." <img src=\"/assets/warning.gif\" alt=\"Avertissement\"/> ".$cptSQLErreur." erreurs de traitement - ";
						if ($EcrireLogComp ) {			
								WriteCompLog ($logComp,"   - ATTENTION ".$cptSQLErreur." erreurs de traitement.",$pasdefichier);
						}
					} else {
						switch($typeAction){
							case "copAC":
								$CRexecution = $CRexecution." Ajout de ".$cptMajACCtoACC." donnees";
								break; 
							case "copPPEAO":
								$CRexecution = $CRexecution." Ajout de ".$cptMajPOSTtoACC." donnees";
								break;
						}
					}
				} 
				$CRexecution = $CRexecution." <br/>" ;
			} // End for statement if ((!$ArretTimeOut) 
			
			} // End for statement if ((!$tableEnCours == "" && tableEnCours == $tables[$cpt]) || $tableEnCours == "")
		} // End for statement for ($cpt = 0; $cpt <= $nbTables; $cpt++)
	} // End if (!$ArretTimeOut)

	// Fin de traitement : affichage des résultats.
	// *********************************************
	// On faire le decompte total
	// Les valeurs sur les champs sont stockees dans le cas ou le process est relancé pour cause de time out.
	$_SESSION['s_CR_processAuto'] 	= $_SESSION['s_CR_export'].$CRexecution;
	$_SESSION['s_cpt_champ_total'] 	+= 	$cptChampTotal;// Lecture d'une table, nombre d'enregistrements lus total
	$_SESSION['s_cpt_table_total']	+=	$cptTableTotal; 	// Nombre global de tables lues
	$_SESSION['s_cpt_table_source_vide']+=	$cptTableSourceVide;// Nombre global de tables vides dans cible
	$_SESSION['s_cpt_erreurs_sql']	+= $cptSQLErreur; //
	if (!$_SESSION['s_erreur_process']){
		$_SESSION['s_erreur_process'] = $ErreurProcess;
	}

	// Include qui gère à la fois les compte-rendus à l'écran et la mise à jour des logs avec les ditCR.
	include $_SERVER["DOCUMENT_ROOT"].'/export/exportCR.php';


	// Fin de traitement : Fermeture base de données et fichier log/SQL	
	// *********************************************	
	if (! $pasdefichier) {
		if ($EcrireLogComp ) {
			fclose($logComp);
		}
	}
} else {
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Etape de ".$nomAction." non ex&eacute;cut&eacute;e par choix de l'utilisateur</div><div id=\"".$nomFenetre."_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
} // end if (! $pasdetraitement )

exit;

?>
