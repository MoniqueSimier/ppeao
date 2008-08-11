<?php 
//*****************************************
// comparaison.php
//*****************************************
// Created by Yann Laurent
// 2008-07-01 : creation
//*****************************************
// Ce programme lance la comparaison des deux bases BD_PPEAO et BD_PECHE
// En fonction du param�tre d'entr�e, ce programme renvoie juste un compte rendu (avec un fichier contenant les scripts) soit 
// ex�cute les scripts de mise � jour.
// Le r�sultat du traitement est envoy� � portage_auto.php dans deux div qui seront ins�r�s dans le div g�n�ral (id="comparaison")
// avec une icone de bonne ou mauvaise ex�cution (dans div id="comparaison_img") et l'explication
// de l'erreur dans div id = "comparaison_txt"
//*****************************************
// Param�tres en entr�e
// comp : contient le type d'action 
// comp = 'comp' : on lance une comparaison sans maj
// comp = 'maj'  : on lance une comparaison avec maj

// Param�tres en sortie
// La liste des diff�rences par table est affich�e � l'�cran et est stock�e dans un fichier

// Mettre les noms des fichiers dans un fichier texte

// Variable de test
$pasdetraitement = false;
$pasdefichier = false; // Variable de test pour linux. 

// Includes standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';


// On r�cup�re le type d'action. Le m�me programme g�re la comparaison et la mise � jour de donn�es
if (isset($_GET['action'])) {
	$typeAction = $_GET['action'];
	if ( $typeAction == "comp" ) {
	// La comparaison se fait BD_PPEAO vers BD_PECHE
		$BDSource = "connectPPEAO";
		$BDCible = "connectBDPECHE";
		$nomBDSource = "BD_PPEAO";
		$nomBDCible = "BD_PECHE";
		$nomFenetre = "comparaison";
		$nomAction = "comparaison";
	} else {
	// La mise � jour se fait de BD_PECHE dans BD_PPEAO
		$BDSource = "connectBDPECHE";
		$BDCible = "connectPPEAO";
		$nomBDSource = "BD_PECHE";
		$nomBDCible = "BD_PPEAO";
		$nomFenetre = "copieScientifique";
		$nomAction = "mise a jour donnees scientifiques";
	}
} else { 
	$nomFenetre = "comparaison";
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Il manque le parametre action. Contactez votre admin PPEAO</div>" ;
	exit;
}


// Variables de traitements
$CRexecution = ""; 	// Variable contenant le r�sultat du traitement
$cptChampTotal = 0;	// Lecture d'une table, nombre d'enregistrements lus total
$cptChampDiff = 0; 	// Lecture d'une table, nombre d'enregistrements diff�rents
$cptChampEq = 0;	// Lecture d'une table, nombre d'enregistrements identiques
$cptChampVide = 0;	// Lecture d'une table, nombre d'enregistrements vide
$cptTableTotal = 0;	// Nombre global de tables lues
$cptTableDiff = 0;	// Nombre global de tables diff�rentes entre BD_PECHE et BD_PPEAO
$cptTableEq = 0;	// Nombre global de tables identiques entre BD_PECHE et BD_PPEAO
$cptTableVide = 0;	// Nombre global de tables vides dans BD_PECHE 
$cptTableLignesVides = 0; // Nombre global de tables avec des enreg manquants dans BD_PECHE 
$scriptSQL = "";	// Stockage du script SQL � ex�cuter pour cr�er ou maj les donn�es
$logComp="";

if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine compl�te de traitement automatique (saute cette etape)
// Traitements pr�liminaires : 
// *********************************************
//	Contr�le des r�pertoires et fichiers log
// 		Controle r�pertoire
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
		$logComp = fopen($dirLog."/ResultatsComparaison.log", "a+");
		if (! $logComp ) {
			$messageGen = " erreur de cr&eacute;ation du fichier de log";
			logWriteTo(4,"error","Erreur de creation du fichier de log dans comparaison.php","","","0");
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
			exit;		
		}
	//	Si en comparaison, on peut g�n�rer le SQL
		$SQLComp = fopen($dirLog."/Comparaison.sql", "a+");
		if (! $SQLComp ) {
			$messageGen = " erreur de cr&eacute;ation du fichier SQL contenant les scripts";
			logWriteTo(4,"error","Erreur de creation du fichier de SQL dans comparaison.php","","","0");
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
			exit;		
		}	
		// Gestion des SQL pour la restauration des fichiers
		$ficRevSQL = OpenFileReverseSQL ("ajout",$dirLog);
	}	
	// R�cup�ration des tables � comparer
	// *********************************************
	// listes ci-dessous pour les tests...
	if ($typeAction == "comp") {

	//$listTable="ref_categorie_ecologique,ref_categorie_trophique,ref_espece,ref_famille,ref_ordre,ref_origine_kb,ref_pays,ref_secteur,ref_systeme"; 
		$listTable="ref_categorie_ecologique"; //TEST
	} else {
	// Donn�es scientifiques � mettre � jour
	//$listTableRef="exp_biologie,exp_campagne,exp_coup_peche,exp_environnement,exp_fraction,exp_trophique"
		$listTable="exp_biologie"; //TEST
	}

	// Connexion aux deux bases de donn�es pour comparaison.
	// *********************************************
	$connectBDPECHE =pg_connect ("host=".$host." dbname=".$bd_peche." user=".$user." password=".$passwd);
	if (!$connectBDPECHE) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connection a la base de donn&eacute;es BD_PECHE</div>" ; exit;
		}
		
	// Test de ma connexion � la BD 
	if (!$connectPPEAO) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connection a la base de donn&eacute;es BD_PPEAO pour maj des logs</div>" ; exit;
		}
	
	logWriteTo(4,"notice","**- Debut lancement ".$nomAction." (portage automatique)","","","0");
	logWriteTo(4,"notice","**- source : ".$nomBDSource." cible : ".$nomBDCible,"","","0");
	WriteCompLog ($logComp, "*******************************************************",$pasdefichier);
	WriteCompLog ($logComp, "*- DEBUT lancement ".$nomAction." (portage automatique)",$pasdefichier);
	WriteCompLog ($logComp, "*- source : ".$nomBDSource." cible : ".$nomBDCible,$pasdefichier);
	WriteCompLog ($logComp, "*******************************************************",$pasdefichier);
	// Param�tres  de comparaison.
	// *********************************************
	// Lancement de la comparaison. On met � jour la variable contenuDiv avec le r�sultat de la comparaison.
	// On met � jour le fichier de log sp�cifique avec plus de d�tails.

	$tables = explode(",",$listTable);
	$nbTables = count($tables) - 1;
	logWriteTo(4,"notice"," Nb tables = ".$nbTables ,"","","1");
	// D�but du traitement de comparaison par table.
	// *********************************************
	for ($cpt = 0; $cpt <= $nbTables; $cpt++) {
		// Reinitialisation des compteurs
		$cptChampTotal = 0;
		$cptChampDiff = 0;
		$cptChampEq = 0;
		$cptChampVide = 0;
		$tableVide = false;
		$cptTableTotal++;
		// Reinitialisation variable pour creation SQL
		$where="";
		$alias="";
    	logWriteTo(4,"notice","*-- Comparaison de la table ".$tables[$cpt],"","","0");

		// Construction des requetes SQL
		$continueControle = true ;
		
		if ($typeAction == "comp") {
		// Test si la table dans la BD cible (BD_PECHE dans le cas de la comparaison, BD_PPEAO dans le cas
		// de la mise � jour) n'est pas vide. 
		// Si c'est le cas, pas la peine de continuer la comparaison (ce n'est valable que dans le cas de la comparaison !)
			$testCibleReadSql = " select * from ".$tables[$cpt] ;
			$testCibleReadResult = pg_query(${$BDCible},$testCibleReadSql) or die('erreur dans la requete : '.pg_last_error());
			if (pg_num_rows($testCibleReadResult) == 0) {
				logWriteTo(4,"notice","table ".$tables[$cpt]." dans ".$nomBDcible." vide","","","0");
				$continueControle = false;
			}
			pg_free_result($testCibleReadResult);				
		}

		if ($continueControle) {
			// On peut continuer la comparaison, on sait qu'on a des enregs dans la base cible.
			// Pour la mise � jour on passera toujours ici..
			// ************************************************
			// Lecture de la table $tables[$cpt] dans la base source (BD_PPEAO dans le cas de la comparaison, 
			// BD_PECHE dans le cas de la mise � jour)
			logWriteTo(4,"notice",$cpt." lecture table ".$nomBDSource." ".$tables[$cpt]," select * from ".$tables[$cpt],"","1");
			$compReadSql = " select * from ".$tables[$cpt] ;
			$compReadResult = pg_query(${$BDSource},$compReadSql) or die('erreur dans la requete : '.pg_last_error());
			if (pg_num_rows($compReadResult) == 0) {
			// La table dans BD_PPEAO est vide
				logWriteTo(4,"notice","table de reference ".$tables[$cpt]." dans ".$nomBDSource." vide","","","0");
				$tableVide = true;

			} else {
				// La table dans BD_PPEAO n'est pas vide
				logWriteTo(4,"notice",$cpt." ".$tables[$cpt]." nombre lignes = ".pg_num_rows($compReadResult)," ","","1");
				// On va balayer tous les enreg (ligne) de la table control�e
				while ($compRow = pg_fetch_row($compReadResult)) {
					$where = "where id = '".$compRow[0]."'" ;
					// comparaison avec l'enreg dans l'autre DB
					logWriteTo(4,"notice",$cpt." lecture table ".$nomBDCible." ".$tables[$cpt]," select * from ".$tables[$cpt]." where id = '".$compRow[0]."'","","1");
					$compCibleReadSql = " select * from ".$tables[$cpt]." where id = '".$compRow[0]."'" ; //
					$compCibleReadResult = pg_query(${$BDCible},$compCibleReadSql) or die('erreur dans la requete : '.pg_last_error());
					$compCibleRow = pg_fetch_row($compCibleReadResult); // une seule ligne en retour, pas besoin de faire une boucle
					
					if (pg_num_rows($compCibleReadResult) == 0) {
						// L'enregistrement n'existe pas dans BD_PECHE
						$cptChampVide++ ;
						WriteCompLog ($logComp," DIFF ".$tables[$cpt]." l'enreg id = ".$compRow[0]." n'existe pas dans ".$nomBDCible.".",$pasdefichier);
						$scriptSQL = GetSQL('insert',  $tables[$cpt], $where, $compRow,${$BDSource},$nomBDSource);
						$scriptDeleteSQL = GetSQL('delete',  $tables[$cpt], $where, $compRow,${$BDSource},$nomBDSource);
						
						if ($typeAction == "maj") {
						// Cr�ation de l'enreg dans BD_PPPEAO
							$RunQErreur = runQuery($scriptSQL,${$BDCible});
							if ( $RunQErreur){
							 	WriteFileReverseSQL($ficRevSQL,$scriptDeleteSQL,$pasdefichier);
							} else {
								// traitement d'erreur ? On arr�te ou seulement avertissement ?
							
							}
							//WriteCompSQL ($SQLComp,$scriptSQL.";"); // Pour test
						} else {
						// On g�n�re un fichier de mise � jour utilisable
							WriteCompSQL ($SQLComp,$scriptSQL.";",$pasdefichier);
						}
						
					} else {
					// On balaye tous les champs � comparer en ignorant les cl�s primaires id.
						$enregDiff = false;
						// On commence a 1, on evite le champs ID
						for ($cpt1 = 1; $cpt1 <= pg_num_rows($compCibleReadResult); $cpt1++) {
							// Comparaison
							if ($compCibleRow[$cpt1] == $compRow[$cpt1]) {
								// identique
								$cptChampEq++ ;
								//logWriteTo(4,"notice","id = ".$compRow[0]." enreg identique pour ".$listeChamp[$cpt1],"","","1");
								
							}
							 else {
								// diff�rent
								//logWriteTo(4,"notice","id = ".$compRow[0]." enreg different pour ".$listeChamp[$cpt1],"","","1");
								$cptChampDiff++ ;
								$enregDiff = true;
								WriteCompLog ($logComp," DIFF ".$tables[$cpt]." l'enreg id = ".$compRow[0]." est different (ref= ".$compRow[$cpt1]." dans ".$nomBDCible." = ".$compCibleRow[$cpt1].")",$pasdefichier);
							}
						} // end for ($cpt1 = 0; $cpt1 <= $nbChamp; $cpt1++)
						
						if 	($enregDiff) {
							// On lance ici la mise � jour globale de l'enregistrement	
							$scriptSQL = GetSQL('update',  $tables[$cpt], $where, $alias,${$BDSource},$nomBDSource);
							$scriptDeleteSQL = GetSQL('update',  $tables[$cpt], $where, $compCibleRow,${$BDSource},$nomBDSource);
							if ($typeAction == "maj") {
							// Maj de l'enreg dans BD_PPPEAO
								$RunQErreur = runQuery($scriptSQL,${$BDCible});
								if ( $RunQErreur){
									WriteFileReverseSQL($ficRevSQL,$scriptDeleteSQL,$pasdefichier);
								} else {
									// traitement d'erreur ? On arr�te ou seulement avertissement ?
								
								}

								//WriteCompSQL ($SQLComp,$scriptSQL.";");// pour test
							} else {
							// On g�n�re un fichier de mise � jour utilisable
								WriteCompSQL ($SQLComp,$scriptSQL.";",$pasdefichier);
							}
						}
						
											
					} // end if (pg_num_rows($compPecheReadResult) == 0)
				pg_free_result($compCibleReadResult);
				} // end while ($compRow = pg_fetch_row($compReadResult))

			} // end if(pg_num_rows($compReadResult) == 0) table de ref vide ?
			// Lib�re le requete sur BD_PECHE
			pg_free_result($compReadResult);
		} // end if ($continueControle) 

		// On fait le bilan sur la table.
		// sortie ecran et sortie fichier
		// A ameliorer, cas d'une table avec champs manquants mais pas de champs diff�rentes, CR foireux
		
		if ($cptChampVide > 0) {
			$cptTableLignesVides++;
			$CRexecution = $CRexecution." ".$tables[$cpt]." avec donn&eacute;es manquantes |";
			WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." avec donnees manquants",$pasdefichier);
		}		
		
		if ($cptChampDiff > 0) {
			$cptTableDiff++;
			$CRexecution = $CRexecution." ".$tables[$cpt]." avec donn&eacute;es diff&eacute;rents |";
			WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." avec donnees differents",$pasdefichier);
		} else {
			if ($tableVide) {
				$cptTableVide++;
				$CRexecution = $CRexecution." ".$tables[$cpt]." vide |";
				WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." vide",$pasdefichier);
			} else {
				$CRexecution = $CRexecution." ".$tables[$cpt]." identique |";			
				WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." identique",$pasdefichier);
			}
		}	
			
		// pour des raisons de timeout, relancer la requete Http ? Comment stocker les valeurs intermediaires (compteurs ?)
		
		
	} // end for ($cpt = 0; $cpt <= $nbTables; $cpt++)

	// Fin de traitement : affichage des r�sultats.
	// *********************************************
	
	logWriteTo(4,"notice","**- Compte rendu traitement ".$nomAction,"","","0");
	logWriteTo(4,"notice","*-- Nombre total de tables lues = ".$cptTableTotal,$cptTableTotal,"","0");
	logWriteTo(4,"notice","*-- Nombre de tables avec des donnees differences = ".$cptTableDiff,$cptTableDiff,"","0");
	logWriteTo(4,"notice","*-- Nombre de tables avec des donnees manquantes = ".$cptTableLignesVides,$cptTableLignesVides,"","0");
	logWriteTo(4,"notice","*-- Nombre de tables de references vides = ".$cptTableVide,$cptTableVide,"","0");
	
	WriteCompLog ($logComp,"******************************************",$pasdefichier);
	WriteCompLog ($logComp,"* Compte rendu traitement ".$nomAction,$pasdefichier);
	WriteCompLog ($logComp,"******************************************",$pasdefichier);
	WriteCompLog ($logComp,"* Nombre total de tables lues = ".$cptTableTotal,$pasdefichier);
	WriteCompLog ($logComp,"* Nombre de tables avec des donnees differences = ".$cptTableDiff,$pasdefichier);
	WriteCompLog ($logComp,"* Nombre de tables avec des donnees manquantes = ".$cptTableLignesVides,$pasdefichier);
	WriteCompLog ($logComp,"* Nombre de tables de references vides = ".$cptTableVide,$pasdefichier);
	WriteCompLog ($logComp,"*---------------------------------------------",$pasdefichier);
	WriteCompLog ($logComp,"*- FIN TRAITEMENT ".$nomAction,$pasdefichier);
	WriteCompLog ($logComp,"*---------------------------------------------",$pasdefichier);
	// On g�re un compte rendu
	if ($cptTableDiff == 0 && $cptTableVide == 0) {
			// Pas de diff�rences
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." ex&eacute;cut&eacute;e avec succ&egrave;s et toutes les tables sont identiques .".$CRexecution." ".$messageGen."</div>" ;	
	} else {
		// Diff�rences
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/dep.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." ex&eacute;cut&eacute;e avec succ&egrave;s mais des tables sont diff&eacute;rentes et/ou vides.<br/>".$CRexecution." ".$messageGen."</div>" ;	
	
	// Fin de traitement : Fermeture base de donn�es et fichier log/SQL	
	// *********************************************	
		if (! $pasdefichier) {
			fclose($logComp);
			fclose($SQLComp);
			
		}
		CloseFileReverseSQL($ficRevSQL,$pasdefichier);
	}
	
	
} else {
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">En Test Etape de ".$nomAction." non ex&eacute;cut&eacute;e (var pasdetraitement = true)</div>" ;
} // end if (! $pasdetraitement )
exit;



?>
