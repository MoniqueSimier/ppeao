<?php 
//*****************************************
// comparaison.php
//*****************************************
// Created by Yann Laurent
// 2008-07-01 : creation
//*****************************************
// Ce programme lance la comparaison des deux bases BD_PPEAO et BD_PECHE
// En fonction du paramètre d'entrée, ce programme renvoie juste un compte rendu (avec un fichier contenant les scripts) soit 
// exécute les scripts de mise à jour.
// Le résultat du traitement est envoyé à portage_auto.php dans deux div qui seront insérés dans le div général (id="comparaison")
// avec une icone de bonne ou mauvaise exécution (dans div id="comparaison_img") et l'explication
// de l'erreur dans div id = "comparaison_txt"
//*****************************************
// Paramètres en entrée
// comp : contient le type d'action 
// comp = 'comp' : on lance une comparaison sans maj
// comp = 'maj'  : on lance une comparaison avec maj

// Paramètres en sortie
// La liste des différences par table est affichée à l'écran et est stockée dans un fichier

// Mettre les noms des fichiers dans un fichier texte
session_start();

// Variable de test (en fonctionnement production, les deux variables sont false)
$pasdetraitement = false;
$pasdefichier = false; // Variable de test pour linux. 


// Variables de traitement
$ErreurProcess = false; // Flag si erreur process

// Includes standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';

// ***** Recuperation des parameters en entree 
// On récupère le type d'action. Le même programme gère la comparaison et la mise à jour de données
if (isset($_GET['action'])) {
	$typeAction = $_GET['action'];
	if ( $typeAction == "comp" ) {
	// La comparaison se fait BD_PPEAO vers BD_PECHE
	// Pour plus de clarté, dans la comparaison, la base de reference est la base PPEAO (=  source)
	// Dans le cas de la mise a jour, la base de reference est la base BD_PECHE (ou s'exécutent les mises à jour)
	// En résumé, la base source est la base de reference, et la base cible est la base à comparer ou à mettre à jour
		$BDSource = "connectPPEAO";
		$BDCible = "connectBDPECHE";
		//$BDSource = "connectBDPECHE"; // test
		//$BDCible = "connectPPEAO"; // test
		$nomFenetre = "comparaison";
		$nomAction = "comparaison";
	} else {
	// La mise à jour se fait de BD_PECHE dans BD_PPEAO
		$BDSource = "connectBDPECHE";
		$BDCible = "connectPPEAO";
		if ( $typeAction == "majsc" ) {
			$nomFenetre = "copieScientifique";
			$nomAction = "mise a jour donnees scientifiques";
		} else {
			$nomFenetre = "copieRecomp";
			$nomAction = "mise a jour donnees recomponsees";		
		}
	}
} else { 
	$nomFenetre = "comparaison";
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Il manque le parametre action. Contactez votre admin PPEAO</div>" ;
	exit;
}
$nomBDSource = ""; 
$nomBDCible = ""; 
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
 
// Deux variables pour stocker les tables / ID en cours de lecture pour être capable de les renvoyer si pb de timeout detecte
$tableEnLecture = "";
$IDEnLecture = 0 ;
$ArretTimeOut = false;
$dumpTable = false;

// Pour test...
// temps maximal d'exécution du script autorisé par le serveur
$max_time = ini_get('max_execution_time');
// 30 secondes par défaut:
if ($max_time == '') $max_time = 60;
// on prend 10% du temps maximal comme marge de sécurité
$ourtime = ceil(0.9*$max_time);
// fin test

// ***** Test si arret processus lié à l'exécution du traitement précédent 	
// Si le traitement précédent a échoué, arrêt du traitement

if (isset($_SESSION['s_status_process_auto'])) {
	if ($_SESSION['s_status_process_auto'] == 'ko') {
		logWriteTo(4,"error","**- ARRET du traitement ".$nomAction." car le processus precedent est en erreur.","","","0");
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\"> ARRET du traitement car le processus precedent est en erreur</div>" ;
		exit;
	}
}


// ***** Variables de traitements
$CRexecution = ""; 			// Variable contenant le résultat du traitement
$cptChampTotal = 0;			// Lecture d'une table, nombre d'enregistrements lus total
$cptChampDiff = 0; 			// Lecture d'une table, nombre d'enregistrements différents
$cptChampEq = 0;			// Lecture d'une table, nombre d'enregistrements identiques
$cptChampVide = 0;			// Lecture d'une table, nombre d'enregistrements vide
$cptTableTotal = 0;			// Nombre global de tables lues
$cptTableDiff = 0;			// Nombre global de tables différentes entre reference et cible
$cptTableEq = 0;			// Nombre global de tables identiques entre reference et cible
$cptTableVide = 0;			// Nombre global de tables vides dans cible 
$cptTableSourceVide = 0;	// Nombre global de tables vides dans source 
$cptTableLignesVidesDiff =0;// Nombre global de tables avec des enreg manquants ou diffenrets dans cible
$cptTableLignesVides = 0; 	// Nombre global de tables avec des enreg manquants dans cible
$cptSQLErreur = 0 ;			// Nombre d'erreur lors de la mise a jour de la table
$scriptSQL = "";			// Stockage du script SQL à exécuter pour créer ou maj les données
$logComp="";
$TotalLignesFichier = 0; 	// compteur pour gerer la taille des fichiers SQL
$SeuilLignesFichier = 5000; // constante contenant le nombre max de lignes par fichier


// *** Pour info, les variable de session utilisées pour stocker les valeurs
//$_SESSION['s_cpt_champ_total'] // Lecture d'une table, nombre d'enregistrements lus total
//$_SESSION['s_cpt_champ_diff']// Lecture d'une table, nombre d'enregistrements différents
//$_SESSION['s_cpt_champ_egal']// Lecture d'une table, nombre d'enregistrements identiques
//$_SESSION['s_cpt_champ_vide']// Lecture d'une table, nombre d'enregistrements vide
//$_SESSION['s_cpt_table_total']// Nombre global de tables lues
//$_SESSION['s_cpt_table_diff']// Nombre global de tables différentes entre reference et cible
//$_SESSION['s_cpt_table_egal']// Nombre global de tables identiques entre reference et cible
//$_SESSION['s_cpt_table_vide']// Nombre global de tables vides dans cible 
//$_SESSION['s_cpt_table_manquant']// Nombre global de tables avec des enreg manquants dans cible
//$_SESSION['s_cpt_table_diff_manquant'] // Nombre global de tables avec des enreg differents et manquants dans cible
//$_SESSION['s_cpt_lignes_fic_sql']// Nombre global de lignes mises dans le fichier SQL
//$_SESSION['s_cpt_erreurs_sql']// Nombre d'erreur lors de la mise a jour de la table

// On récupère les valeurs des paramètres pour les fichiers log
$dirLog = GetParam("repLogAuto",$PathFicConf);
$dirLog = $_SERVER["DOCUMENT_ROOT"]."/".$dirLog;
$fileLogComp = GetParam("nomFicLogSupp",$PathFicConf);



// Initialisation si on demarre un nouveau traitement
if ($tableEnCours == "") {
	$_SESSION['s_CR_processAuto'] = "";
	$_SESSION['s_cpt_champ_total'] = 0;
	$_SESSION['s_cpt_champ_diff'] = 0;
	$_SESSION['s_cpt_champ_egal'] = 0;
	$_SESSION['s_cpt_champ_vide'] = 0;	
	$_SESSION['s_cpt_table_diff'] = 0;
	$_SESSION['s_cpt_table_diff_manquant'] = 0;
	$_SESSION['s_cpt_table_egal'] = 0;
	$_SESSION['s_cpt_table_vide'] = 0;
	$_SESSION['s_cpt_table_manquant'] = 0; 
	$_SESSION['s_cpt_erreurs_sql'] = 0; 
}

// ***** Debut du traitement

if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine complète de traitement automatique (saute cette etape)

// Traitements préliminaires : 
// *********************************************
//	Contrôle des répertoires et fichiers log
// 		Controle répertoire
	if (! $pasdefichier) { // Pour test sur serveur linux
		if (! file_exists($dirLog)) {
			if (! mkdir($dirLog) ) {
				$messageGen = " erreur de cr&eacute;ation du r&eacute;pertoire de log";
				logWriteTo(4,"error","Erreur de creation du repertoire de log dans comparaison.php","","","0");
				echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
				exit;
			}
		}
	//	Controle fichiers
	//	Resultat de la comparaison
		if ($EcrireLogComp ) {
			$nomFicLogComp = $dirLog."/".date('y\-m\-d')."-".$fileLogComp;
			$logComp = fopen($nomFicLogComp , "a+");
			if (! $logComp ) {
				$messageGen = " erreur de cr&eacute;ation du fichier de log";
				logWriteTo(4,"error","Erreur de creation du fichier de log ".$dirLog."/".date('y\-m\-d')."-".$fileLogComp." dans comparaison.php","","","0");
				echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
				exit;		
			}
		}
	//	Si en comparaison, on peut générer le SQL
		$numfic = str_pad($_SESSION['s_num_encours_fichier_SQL'], 3, "0", STR_PAD_LEFT);
		$SQLComp = fopen($dirLog."/".date('y\-m\-d').$typeAction."-".$numfic.".sql", "a+");
		if (! $SQLComp ) {
			$messageGen = " erreur de cr&eacute;ation du fichier SQL contenant les scripts";
			logWriteTo(4,"error","Erreur de creation du fichier de SQL dans comparaison.php","","","0");
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
			exit;		
		}	
		// Gestion des SQL pour la restauration des fichiers
		$ficRevSQL = OpenFileReverseSQL ("ajout",$dirLog,$pasdefichier);
	}	
	// Récupération des tables à comparer
	// *********************************************
	// listes ci-dessous pour les tests...
	switch($typeAction){
		case "comp":
			// Comparaison
			$listTable = GetParam("listeTableComp",$PathFicConf);
			//$listTable="ref_pays"; //TEST
			 break;
		case "majsc":
			// Données scientifiques à mettre à jour
			$listTable = GetParam("listeTableMajsc",$PathFicConf);
			//$listTable="exp_campagne"; //TEST
			 break;
		case "majrec":
		// Données recomposées à mettre à jour
			$listTable = GetParam("listeTableMajrec",$PathFicConf);
//"art_debarquement_rec,art_fraction_rec,art_stat_gt,art_stat_gt_sp,art_stat_sp,art_stat_totale,art_taille_gt_sp,art_taille_sp,art_activite,art_debarquement,art_engin_activite,art_engin_peche,art_fraction,art_lieu_de_peche,art_poisson_mesure,art_unite_peche"
			//$listTable="art_debarquement"; //TEST
			 break;
	}

	// Connexion aux deux bases de données pour comparaison.
	// *********************************************
	// Pas besoin de se connecter à la base PPEAO, c'est deja fait dans l'include
	
	$connectBDPECHE =pg_connect ("host=".$host." dbname=".$bd_peche." user=".$user." password=".$passwd);
	if (!$connectBDPECHE) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connection a la base de donn&eacute;es ".$bd_peche."</div>" ; exit;
		}
		
	// Test de la connexion à la BD 
	if (!$connectPPEAO) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connection a la base de donn&eacute;es BD_PPEAO pour maj des logs</div>" ; exit;
	}
	
	// Gestion des noms des BD
	$nomBDSource = pg_dbname($$BDSource);
	$nomBDCible = pg_dbname($$BDCible);
	
	// Initialisation des logs
	if ($tableEnCours == "") {
		logWriteTo(4,"notice","**- Debut lancement ".$nomAction." (portage automatique)","","","0");
		logWriteTo(4,"notice","**- source : ".$nomBDSource." cible : ".$nomBDCible,"","","0");
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "*******************************************************",$pasdefichier);
			WriteCompLog ($logComp, "*- DEBUT lancement ".$nomAction." (portage automatique)",$pasdefichier);
			WriteCompLog ($logComp, "*- source : ".$nomBDSource." cible : ".$nomBDCible,$pasdefichier);
			WriteCompLog ($logComp, "*******************************************************",$pasdefichier);
		}
	} else {
		logWriteTo(4,"notice","**- Relance traitement pour la table ".$tableEnCours." a partir de l'enreg ID = ".$IDEnCours." (gestion TIEMOUT AJAX)","","","0");
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
	logWriteTo(4,"notice"," Nb tables = ".$nbTables ,"","","1");
	// Début du traitement de comparaison par table.
	// *********************************************
	$start_while=timer(); // début du chronométrage du for
	for ($cpt = 0; $cpt <= $nbTables; $cpt++) {
		// controle de la table en cours si besoin (gestion TIMEOUT)
		if ((!$tableEnCours == "" && $tableEnCours == $tables[$cpt]) || $tableEnCours == "") {
			
		// Reinitialisation des compteurs
		$cptChampTotal = 0;
		$cptChampDiff = 0;
		$cptChampEq = 0;
		$cptChampVide = 0;
		$cptSQLErreur = 0 ;
		$tableVide = false;
		$tableSourceVide = false;
		$dumpTable = false;
		if ($tableEnCours == "") {
			$cptTableTotal++;
			$_SESSION['s_cpt_champ_total'] 	= 0;
			$_SESSION['s_cpt_champ_diff']	= 0;
			$_SESSION['s_cpt_champ_egal']	= 0;
			$_SESSION['s_cpt_champ_vide']	= 0;
			$_SESSION['s_en_erreur'] = false;
			$_SESSION['s_cpt_erreurs_sql'] = 0;	
		} else {
			// on reinitialise les valeurs avec les variables de session mise à jour lors du traitement précédent
			$CRexecution = $_SESSION['s_CR_processAuto'];
			$cptChampTotal 	= $_SESSION['s_cpt_champ_total'];
			$cptChampDiff	= $_SESSION['s_cpt_champ_diff'];
			$cptChampEq		= $_SESSION['s_cpt_champ_egal'];
			$cptChampVide	= $_SESSION['s_cpt_champ_vide'];	
			$cptTableDiff	= $_SESSION['s_cpt_table_diff'];
			$cptTableLignesVidesDiff = $_SESSION['s_cpt_table_diff_manquant'];
			$cptTableEq		= $_SESSION['s_cpt_table_egal'];
			$cptTableVide	= $_SESSION['s_cpt_table_vide'];
			$cptTableLignesVides = $_SESSION['s_cpt_table_manquant']; 
			$cptSQLErreur	= $_SESSION['s_cpt_erreurs_sql'] ; 
			$ErreurProcess = $_SESSION['s_erreur_process'];
			// On reinitialise pour eviter de compter deux fois les memes donnees
			$_SESSION['s_CR_processAuto'] = "";
			$_SESSION['s_cpt_champ_total'] = 0;
			$_SESSION['s_cpt_champ_diff'] = 0;
			$_SESSION['s_cpt_champ_egal'] = 0;
			$_SESSION['s_cpt_champ_vide'] = 0;	
			$_SESSION['s_cpt_table_diff'] = 0;
			$_SESSION['s_cpt_table_diff_manquant'] = 0;
			$_SESSION['s_cpt_table_egal'] = 0;
			$_SESSION['s_cpt_table_vide'] = 0;
			$_SESSION['s_cpt_table_manquant'] = 0; 
			$_SESSION['s_cpt_erreurs_sql'] = 0; 
		
		}
		// Reinitialisation variable pour creation SQL
		$where="";
		$alias="";
    	logWriteTo(4,"notice","*-- Comparaison de la table ".$tables[$cpt],"","","0");

		// Gestion TIMEOUT
		$tableEnLecture = $tables[$cpt];

		// Construction des requetes SQL
		$continueControle = true ;
		
		if ($typeAction == "comp") {
		// Test si la table dans la BD cible (BD_PECHE dans le cas de la comparaison, BD_PPEAO dans le cas
		// de la mise à jour) n'est pas vide. 
		// Si c'est le cas, pas la peine de continuer la comparaison (ce n'est valable que dans le cas de la comparaison !)
		// On va lancer un dump complet de la table
			$testCibleReadSql = " select * from ".$tables[$cpt] ;
			$testCibleReadResult = pg_query(${$BDCible},$testCibleReadSql) or die('erreur dans la requete : '.pg_last_error());
			if (pg_num_rows($testCibleReadResult) == 0) {
				logWriteTo(4,"notice","table ".$tables[$cpt]." dans ".$nomBDCible." vide","","","0");
				$dumpTable = true;
			}
			// ==> faire un dump de la table source
			pg_free_result($testCibleReadResult);				
		}
		
		
		// Ce test est obsolete, on le laisse pour des tests
		if ($continueControle) {
			// On peut continuer la comparaison, on sait qu'on a des enregs dans la base cible.
			// Pour la mise à jour on passera toujours ici..
			// ************************************************

			// Gestion TIMEOUT : on reprend la ou on s'etait arrete
			// Comme on trie par ID, on ne va pas en perdre en route
			if ($tableEnCours == "") {
				$condWhere = "";
			} else {
				$condWhere = " where id > ".$IDEnCours;
			}
			// Lecture de la table $tables[$cpt] dans la base source (BD_PPEAO dans le cas de la comparaison, 
			// BD_PECHE dans le cas de la mise à jour)
			logWriteTo(4,"notice",$cpt." lecture table ".$nomBDSource." ".$tables[$cpt]," select * from ".$tables[$cpt].$condWhere. " order by id ASC","","1");
						
			
			$compReadSql = " select * from ".$tables[$cpt].$condWhere. " order by id ASC";
			$compReadResult = pg_query(${$BDSource},$compReadSql) or die('erreur dans la requete : '.pg_last_error());
			if (pg_num_rows($compReadResult) == 0) {
			// La table dans BD_PPEAO est vide
				logWriteTo(4,"notice","Table de reference ".$tables[$cpt]." dans ".$nomBDSource." vide","","","0");
				if ($EcrireLogComp ) { WriteCompLog ($logComp,"Table de reference ".$tables[$cpt]." dans ".$nomBDSource." vide",$pasdefichier);}
				$tableSourceVide = true;

			} else {
				// La table dans la base source (de référence) n'est pas vide
				logWriteTo(4,"notice",$cpt." ".$tables[$cpt]." nombre lignes = ".pg_num_rows($compReadResult)." dans ".$nomBDSource," ","","1");
				// On va balayer tous les enreg (ligne) de la table controlée
				while ($compRow = pg_fetch_row($compReadResult) ) {
					// Controle sur le nombre de ligne deja mise dans le fichier,
					// Creation d'un nouveau fichier si nécessaire
					if ($_SESSION['s_cpt_lignes_fic_sql'] > $SeuilLignesFichier ) {
						// Pour eviter le time out, on prend 3 secondes de marge
						if ( ceil(0.9*$max_time) - $ourtime < 2) {
							if ($EcrireLogComp ) { WriteCompLog ($logComp,"TIMEOUT: break pour cause de creation fichier",$pasdefichier);}
							$delai=number_format(timer() - $start_while,7);
							$ArretTimeOut =true;
							break;
						}
						if ($EcrireLogComp ) { WriteCompLog ($logComp," Changement de fichier. Creation d'un nouveau",$pasdefichier);}
						fclose($SQLComp);
						$_SESSION['s_num_encours_fichier_SQL'] ++;
						$_SESSION['s_cpt_lignes_fic_sql'] = 0;
						$numfic = str_pad($_SESSION['s_num_encours_fichier_SQL'], 3, "0", STR_PAD_LEFT);
						$SQLComp = fopen($dirLog."/".date('y\-m\-d').$typeAction."-".$numfic.".sql", "a+");
						if (! $SQLComp ) {
							$messageGen = " erreur de cr&eacute;ation du fichier SQL contenant les scripts";
							logWriteTo(4,"error","Erreur de creation du fichier de SQL dans comparaison.php","","","0");
							echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
							exit;		
						}	
					}
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
					// Attention, l'ID n'est pas toujours en position 1 (donc 0 dans le tableau des donnees en sortie du pg_fetch_row
					$ListeTableIdpasRang0 = "art_type_activite";
					$ListeTablepasRang0ID = "3";
					$testTtypeID = strpos($ListeTableIdpasRang0 ,$tables[$cpt]);
					if ($testTtypeID === false) {
						$RangId = 0; 
					} else {
						$RangId = 2; /// pour l'instant qu'une table, on code un peu a la husarde...
					}					
					if (! $dumpTable) {
						$IDEnLecture = $compRow[$RangId] ;
						if ($testTtypeID === false) {
							// L'ID est bien un numérique
							$where = "where id = ".intval($compRow[$RangId]) ; 
						} else {
							// L'ID est une chaine
							$where = "where id = '".$compRow[$RangId]."'" ;
						}

						// comparaison avec l'enreg dans l'autre DB
						logWriteTo(4,"notice",$cpt." lecture table ".$nomBDCible." ".$tables[$cpt]," select * from ".$tables[$cpt]." where id = '".$compRow[$RangId]."'","","1");
						$compCibleReadSql = " select * from ".$tables[$cpt]." where id = '".$compRow[$RangId]."'" ; //
						$compCibleReadResult = pg_query(${$BDCible},$compCibleReadSql) or die('erreur dans la requete : '.pg_last_error());
						$compCibleRow = pg_fetch_row($compCibleReadResult); // une seule ligne en retour, pas besoin de faire une boucle
						
						if (pg_num_rows($compCibleReadResult) == 0) {
							// L'enregistrement n'existe pas dans la base cible
							$cptChampVide++ ;
							logWriteTo(4,"notice","id = ".$compRow[$RangId]." enreg manquant dans base cible","","","1");
							if ($EcrireLogComp ) { WriteCompLog ($logComp," MANQUANT ".$tables[$cpt]." l'enreg id = ".$compRow[$RangId]." n'existe pas dans ".$nomBDCible.".",$pasdefichier);}
							$scriptSQL = GetSQL('insert',  $tables[$cpt], $where, $compRow,${$BDSource},$nomBDSource);
							
							
							if ($typeAction == "majsc" || $typeAction == "majrec") {
							// Création de l'enreg dans BD_PPPEAO
								$RunQErreur = runQuery($scriptSQL,${$BDCible});
								if ( $RunQErreur){
									$scriptDeleteSQL = GetSQL('delete',  $tables[$cpt], $where, $compRow,${$BDSource},$nomBDSource);
									WriteFileReverseSQL($ficRevSQL,$scriptDeleteSQL,$pasdefichier);
								} else {
									// traitement d'erreur ? On arrête ou seulement avertissement ?
									$cptSQLErreur ++;
									$ErreurProcess = true;
									if ($EcrireLogComp ) { WriteCompLog ($logComp," Erreur sur le script ".$scriptSQL." consulter les logs pour l'erreur complete.",$pasdefichier);}
								}
								//WriteCompSQL ($SQLComp,$scriptSQL.";"); // Pour test
							} else {
							// On génère un fichier de mise à jour utilisable
								WriteCompSQL ($SQLComp,$scriptSQL.";",$pasdefichier);
								$_SESSION['s_cpt_lignes_fic_sql'] ++;
								$ErreurProcess = true;
							}
							
						} else {
						// On balaye tous les champs à comparer en ignorant les clés primaires id.
							$enregDiff = false;
							// On commence a 1, on evite le champs ID
							for ($cpt1 = 1; $cpt1 <= pg_num_rows($compCibleReadResult); $cpt1++) {
								// Comparaison
								if ($compCibleRow[$cpt1] == $compRow[$cpt1]) {
									// identique
									$cptChampEq++ ;
									//logWriteTo(4,"notice","id = ".$compRow[0]." enreg identique ","","","1");
									
								}
								 else {
									// différent
									logWriteTo(4,"notice","id = ".$compRow[$RangId]." enreg different ","","","1");
									$cptChampDiff++ ;
									$enregDiff = true;
									logWriteTo(4,"notice"," DIFF ".$tables[$cpt]." l'enreg id = ".$compRow[$RangId]." est different (ref= ".$compRow[$cpt1]." dans ".$nomBDCible." = ".$compCibleRow[$cpt1].")","","","1");
									if ($EcrireLogComp ) {WriteCompLog ($logComp," DIFF ".$tables[$cpt]." l'enreg id = ".$compRow[$RangId]." est different (ref= ".$compRow[$cpt1]." dans ".$nomBDCible." = ".$compCibleRow[$cpt1].")",$pasdefichier);}
								}
							} // end for ($cpt1 = 0; $cpt1 <= $nbChamp; $cpt1++)
							
							if 	($enregDiff) {
								// On lance ici la mise à jour globale de l'enregistrement	
								$scriptSQL = GetSQL('update',  $tables[$cpt], $where, $compCibleRow,${$BDSource},$nomBDSource);
								
								if ($typeAction == "majsc" || $typeAction == "majrec") {
								// Maj de l'enreg dans BD_PPPEAO
									$RunQErreur = runQuery($scriptSQL,${$BDCible});
									if ( $RunQErreur){
										$scriptDeleteSQL = GetSQL('update',  $tables[$cpt], $where, $compRow,${$BDSource},$nomBDSource);
										WriteFileReverseSQL($ficRevSQL,$scriptDeleteSQL,$pasdefichier);
									} else {
										// traitement d'erreur ? On arrête ou seulement avertissement ?
										$cptSQLErreur ++;
										$ErreurProcess = true;
										if ($EcrireLogComp ) { WriteCompLog ($logComp," Erreur sur le script ".$scriptSQL." consulter les logs pour l'erreur complete.",$pasdefichier);}
									}
								} else {
								// On génère un fichier de mise à jour utilisable
									WriteCompSQL ($SQLComp,$scriptSQL.";",$pasdefichier);
									$_SESSION['s_cpt_lignes_fic_sql'] ++;
									$ErreurProcess = true;
								}
							}
						} // end if (pg_num_rows($compPecheReadResult) == 0)
					pg_free_result($compCibleReadResult);
					// *** fin du traitement de comparaison des tables
					} else { // fin du if (! $dumpTable)
						// On fait un dump bourrin de la table
						$tableVide = true;
						logWriteTo(4,"notice","id = ".$compRow[$RangId]." enreg manquant dans base cible","","","1");
						if ($EcrireLogComp ) { WriteCompLog ($logComp," TOUT MANQUANT ".$tables[$cpt]." l'enreg id = ".$compRow[$RangId]." n'existe pas dans ".$nomBDCible.".",$pasdefichier);}
						$scriptSQL = GetSQL('insert',  $tables[$cpt], $where, $compRow,${$BDSource},$nomBDSource);
						if ($typeAction == "majsc" || $typeAction == "majrec") {
						// Création de l'enreg dans BD_PPPEAO
							$RunQErreur = runQuery($scriptSQL,${$BDCible});
							if ( $RunQErreur){
								$scriptDeleteSQL = GetSQL('delete',  $tables[$cpt], $where, $compRow,${$BDSource},$nomBDSource);
								WriteFileReverseSQL($ficRevSQL,$scriptDeleteSQL,$pasdefichier);
							} else {
								// traitement d'erreur ? On arrête ou seulement avertissement ?
								$cptSQLErreur ++;
								$ErreurProcess = true;
								if ($EcrireLogComp ) { WriteCompLog ($logComp," Erreur sur le script ".$scriptSQL." consulter les logs pour l'erreur complete.",$pasdefichier);}
							}
							//WriteCompSQL ($SQLComp,$scriptSQL.";"); // Pour test
						} else {
						// On génère un fichier de mise à jour utilisable
							WriteCompSQL ($SQLComp,$scriptSQL.";",$pasdefichier);
							$_SESSION['s_cpt_lignes_fic_sql'] ++;
							$ErreurProcess = true;
						}					
					}
				} // end while ($compRow = pg_fetch_row($compReadResult))
				// Controle si sortie par timeout ou 
				if ($ArretTimeOut) {
					// on sort de la la boucle for
					break;
				}
				// TIMEOUT, reinitialisation des variables EnCours
				$IDEnCours = 0;
				$tableEnCours = "";
			} // end if(pg_num_rows($compReadResult) == 0) table de ref vide ?
			// Libère le requete sur BD_PECHE
			pg_free_result($compReadResult);
		} // end if ($continueControle) 

		// On fait le bilan sur la table.
		// sortie ecran et sortie fichier

		if (!$ArretTimeOut) {
			// On aura deux comptes-rendus selon si c'est une comparaison ou une mise à jour
			// Dans le cas de la comparaison, on indique les différents cas trouvés.
			// Dans le cas de la maj, on n'indique juste le type de maj
			$CRexecution = $CRexecution." *-".$tables[$cpt]." : ";
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
				
					
				// Cas d'une table ou il manque des données
				if ($cptChampVide > 0) {
					if ($cptChampDiff == 0) {
						$cptTableLignesVides++; 
					} else {
						$cptTableLignesVidesDiff++;
					}
					if ($typeAction == "comp") {
						$CRexecution = $CRexecution." <img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;".$cptChampVide." donn&eacute;es manquantes - ";
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp,"   - donnees manquantes = ".$cptChampVide.". ",$pasdefichier);
						}
					} else {
						$CRexecution = $CRexecution." ".$cptChampVide." donn&eacute;es ajout&eacute;es |";
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp,"   - donnees ajoutees = ".$cptChampVide.". ",$pasdefichier);
						}
					}
				}	
				// Cas d'enregistrements différents	
				if ($cptChampDiff > 0) {
					if ($cptChampVide == 0) {
						$cptTableDiff++;
					}
					if ($typeAction == "comp") {
						$CRexecution = $CRexecution." <img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;".$cptChampDiff." donn&eacute;es diff&eacute;rentes - ";
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp,"   - donnees differentes = ".$cptChampDiff." ",$pasdefichier);
						}
					} else {
						$CRexecution = $CRexecution." ".$cptChampDiff." donn&eacute;es modifi&eacute;es -";
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp,"   - donnees modifiees = ".$cptChampDiff." ",$pasdefichier);
						}					
					
					}
				} else {
				//	Cas de la table vide
					if ($tableVide) {
						$cptTableVide++;
						$CRexecution = $CRexecution." <img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>".$tables[$cpt]." vide ==> dump total de la table depuis la base cible (voir fichier sql).-";
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp," Cette table est vide ==> dump total de la table depuis la base cible.",$pasdefichier);
						}
					} else {
						if ($cptChampVide == 0) {
							$cptTableEq ++;
							$CRexecution = $CRexecution." identique -";
							if ($EcrireLogComp ) {			
								WriteCompLog ($logComp,"   -->  identique",$pasdefichier);
							}
						}
					}
				} // End for statement if ($cptChampDiff > 0)
				
				if ($ErreurProcess) {
					if ($typeAction == "comp"){
					
					} else {
						$CRexecution = $CRexecution." <img src=\"/assets/warning.gif\" alt=\"Avertissement\"/> ".$cptSQLErreur." erreurs de traitement - ";
						if ($EcrireLogComp ) {			
								WriteCompLog ($logComp,"   - ATTENTION ".$cptSQLErreur." erreurs de traitement.",$pasdefichier);
						}
					}
				}
			} 
			$CRexecution = $CRexecution." -* <br/>" ;
		} // End for statement if ((!$ArretTimeOut)
		
		} // End for statement if ((!$tableEnCours == "" && tableEnCours == $tables[$cpt]) || $tableEnCours == "")
	} // End for statement for ($cpt = 0; $cpt <= $nbTables; $cpt++)


	// Fin de traitement : affichage des résultats.
	// *********************************************
	// On faire le decompte total
	// Les valeurs sur les champs sont stockees dans le cas ou le process est relancé pour cause de time out.
	$_SESSION['s_CR_processAuto'] 	= $_SESSION['s_CR_processAuto'].$CRexecution;
	$_SESSION['s_cpt_champ_total'] 	+= 	$cptChampTotal;// Lecture d'une table, nombre d'enregistrements lus total
	$_SESSION['s_cpt_champ_diff']	+=	$cptChampDiff;// Lecture d'une table, nombre d'enregistrements différents
	$_SESSION['s_cpt_champ_egal']	+=	$cptChampEq;// Lecture d'une table, nombre d'enregistrements identiques
	$_SESSION['s_cpt_champ_vide']	+=	$cptChampVide;// Lecture d'une table, nombre d'enregistrements vide
	$_SESSION['s_cpt_table_total']	+=	$cptTableTotal; 	// Nombre global de tables lues
	$_SESSION['s_cpt_table_diff']	+=	$cptTableDiff;// Nombre global de tables différentes entre reference et cible
	$_SESSION['s_cpt_table_diff_manquant']+=$cptTableLignesVidesDiff; // Nombre global de tables avec des enreg differents et manquants dans cible
	$_SESSION['s_cpt_table_egal']	+=	$cptTableEq;// Nombre global de tables identiques entre reference et cible
	$_SESSION['s_cpt_table_vide']	+=	$cptTableVide;// Nombre global de tables vides dans cible
	$_SESSION['s_cpt_table_source_vide']+=	$cptTableSourceVide;// Nombre global de tables vides dans cible
	$_SESSION['s_cpt_table_manquant']	+=	$cptTableLignesVides;// Nombre global de tables avec des enreg manquants dans cible 
	$_SESSION['s_cpt_erreurs_sql']	+= $cptSQLErreur; //
	if (!$_SESSION['s_erreur_process']){
		$_SESSION['s_erreur_process'] = $ErreurProcess;
	}

	// Include qui gère à la fois les compte-rendus à l'écran et la mise à jour des logs avec les ditCR.
	include $_SERVER["DOCUMENT_ROOT"].'/process_auto/gestionCR.php';


	// Fin de traitement : Fermeture base de données et fichier log/SQL	
	// *********************************************	
	if (! $pasdefichier) {
		if ($EcrireLogComp ) {
			fclose($logComp);
		}
		fclose($SQLComp);
		
	}
	CloseFileReverseSQL($ficRevSQL,$pasdefichier);
		
	
} else {
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">En Test Etape de ".$nomAction." non ex&eacute;cut&eacute;e (var pasdetraitement = true)</div>" ;
	logWriteTo(4,"error","**- En Test Etape de ".$nomAction." non executee (var pasdetraitement = true)","","","0");
} // end if (! $pasdetraitement )



exit;



?>
