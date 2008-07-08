<?
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

// Attention, gérer les fichiers UNIX !!!
// Mettre les noms des fichiers dans un fichier texte

// Variable de test
$pasdetraitement = false;

// Includes standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';
// Pour l'instant ici pour du test ******************************
// connexion a BD_PECHE											*
$user="devppeao";                   // Le nom d'utilisateur 	*
$passwd="2devppe!!";                // Le mot de passe 			*
$host= "localhost";  				// L'hôte  					*
$bd_peche="postgres";				// Nom BD					*
$dirLog = $_SERVER["DOCUMENT_ROOT"]."\log";		//				*
$fileLogComp = "ResultatsComparaison.txt";		//				*
// FIN TEST *****************************************************

if (isset($_GET['action'])) {
	$typeAction = $_GET['action'];
	if ( $typeAction == "comp" ) {
		$nomFenetre = "comparaison";
		$nomAction = "comparaison";
	} else {
		$nomFenetre = "copieScientifique";
		$nomAction = "mise a jour donnees scientifiques";
	}
} else { 
	$nomFenetre = "comparaison";
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Il manque le parametre action. Contactez votre admin PPEAO</div>" ;
	exit;
}


// Variables de traitements
$CRexecution = ""; 	// Variable contenant le résultat du traitement
$cptChampTotal = 0;	// Lecture d'une table, nombre d'enregistrements lus total
$cptChampDiff = 0; 	// Lecture d'une table, nombre d'enregistrements différents
$cptChampEq = 0;	// Lecture d'une table, nombre d'enregistrements identiques
$cptChampVide = 0;	// Lecture d'une table, nombre d'enregistrements vide
$cptTableTotal = 0;	// Nombre global de tables lues
$cptTableDiff = 0;	// Nombre global de tables différentes entre BD_PECHE et BD_PPEAO
$cptTableEq = 0;	// Nombre global de tables identiques entre BD_PECHE et BD_PPEAO
$cptTableVide = 0;	// Nombre global de tables vides dans BD_PECHE 
$cptTableLignesVides = 0; // Nombre global de tables avec des enreg manquants dans BD_PECHE 
$scriptSQL = "";	// Stockage du script SQL à exécuter pour créer ou maj les données


if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine complète de traitement automatique (saute cette etape)
// Traitements préliminaires : 
// *********************************************
//	Contrôle des répertoires et fichiers log
// 		Controle répertoire
	if (! file_exists($dirLog)) {
		if (! mkdir($dirLog) ) {
			$messageGen = " erreur de création du répertoire de log";
			logWriteTo(4,"error","Erreur de creation du repertoire de log dans comparaison.php","","","0");
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
			exit;
		}
	}
//	Controle fichiers
//	Resultat de la comparaison
	$logComp = fopen($dirLog."\\ResultatsComparaison.log", "a+");
	if (! $logComp ) {
		$messageGen = " erreur de création du fichier de log";
		logWriteTo(4,"error","Erreur de creation du fichier de log dans comparaison.php","","","0");
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
		exit;		
	}
//	Si en comparaison, on peut générer le SQL
	$SQLComp = fopen($dirLog."\\Comparaison.sql", "a+");
	if (! $SQLComp ) {
		$messageGen = " erreur de création du fichier SQL contenant les scripts";
		logWriteTo(4,"error","Erreur de creation du fichier de SQL dans comparaison.php","","","0");
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ERREUR .".$messageGen."</div>" ;
		exit;		
	}	
	

	
	// Récupération des tables à comparer
	// *********************************************
	// listes ci-dessous pour les tests...
	if ($typeAction == "comp") {

	$listTable="ref_categorie_ecologique,ref_categorie_trophique,ref_espece,ref_famille,ref_ordre,ref_origine_kb,ref_pays,ref_secteur,ref_systeme"; 
		//$listTable="ref_categorie_ecologique"; //TEST
		// Correspond aux différents champs à comparer par table
		// Attention, mettre en premier systématique la clé primaire.
		$listChampTable="id;libelle,id;libelle,id;libelle,id;libelle,id;libelle,id;libelle,id;nom,id;nom,id;libelle";
		// $listChampTable="id;libelle"; //TEST	
	} else {
	// Données scientifiques à mettre à jour
	//$listTableRef="exp_biologie,exp_campagne,exp_coup_peche,exp_environnement,exp_fraction,exp_trophique"
	//$listChampTable="id;libelle"
	
	
		$listTable="exp_biologie"; //TEST
		$listChampTable="id;memo"; //TEST
	}

	// Connexion aux deux bases de données pour comparaison.
	// *********************************************
	$connectBDPECHE =pg_connect ("host=".$host." dbname=".$bd_peche." user=".$user." password=".$passwd);
	if (!$connectBDPECHE) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connection a la base de donn&eacute;es pour maj des logs</div>" ; exit;
		}
		
	// Test de ma connexion à la BD 
	if (!$connectPPEAO) { 
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur de connection a la base de donn&eacute;es pour maj des logs</div>" ; exit;
		}
	
	logWriteTo(4,"notice","**- Debut lancement ".$nomAction." dans portage automatique.","","","0");
	WriteCompLog ($logComp, "Debut lancement ".$nomAction." dans portage automatique.");
	
	// Paramètres  de comparaison.
	// *********************************************
	// Lancement de la comparaison. On met à jour la variable contenuDiv avec le résultat de la comparaison.
	// On met à jour le fichier de log spécifique avec plus de détails.

	$tables = explode(",",$listTable);
	$tablesChamp = explode(",",$listChampTable);
	$nbTables = count($tables) - 1;
	logWriteTo(4,"notice"," Nb tables = ".$nbTables ,"","","1");
	// Début du traitement de comparaison par table.
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
		// Récupération des champs à comparer
		$listeChamp = explode(";",$tablesChamp[$cpt]);
		$nbChamp = count($listeChamp) - 1;
		// Construction des arguments du select a partir des différents champs
		$listeChampsSQL = $tablesChamp[$cpt];
		$listeChampsSQL = str_replace(";",",",$listeChampsSQL);
		
		logWriteTo(4,"notice",$cpt." nbChamp =".$nbChamp,"","","1");
		// Construction des requetes SQL
		// Test si la table dans BD_PECHE n'est pas vide. Si c'est le cas, pas la peine de continuer la comparaison
		$testPecheReadSql = " select ".$listeChampsSQL." from ".$tables[$cpt] ;
		$testPecheReadResult = pg_query($connectBDPECHE,$testPecheReadSql) or die('erreur dans la requete : '.pg_last_error());
		if (pg_num_rows($testPecheReadResult) == 0) {
				logWriteTo(4,"notice","table ".$tables[$cpt]." dans BD_PECHE vide","","","0");
				$tableVide = true;
		} else {
			// On peut continuer la comparaison, on sait qu'on a des enregs dans BD peches.
			// Lecture de la table dans BD_PPEAO
			logWriteTo(4,"notice",$cpt." lecture table BD_PPEAO ".$tables[$cpt]," select ".$listeChampsSQL." from ".$tables[$cpt],"","1");
			$compReadSql = " select ".$listeChampsSQL." from ".$tables[$cpt] ;
			$compReadResult = pg_query($connectPPEAO,$compReadSql) or die('erreur dans la requete : '.pg_last_error());
			if (pg_num_rows($compReadResult) == 0) {
				logWriteTo(4,"notice","table de reference ".$tables[$cpt]." dans BD_PPEAO vide","","","0");

			} else {
				
				logWriteTo(4,"notice",$cpt." nombre lignes = ".pg_num_rows($compReadResult)," ","","1");
				// On va balayer tous les enreg de la table controlée
				while ($compRow = pg_fetch_row($compReadResult)) {
					// comparaison avec l'enreg dans l'autre DB
					logWriteTo(4,"notice",$cpt." lecture table BD_PECHE ".$tables[$cpt]," select ".$listeChampsSQL." from ".$tables[$cpt]." where id = '".$compRow[0]."'","","1");
					$compPecheReadSql = " select ".$listeChampsSQL." from ".$tables[$cpt]." where id = '".$compRow[0]."'" ; //
					$compPecheReadResult = pg_query($connectBDPECHE,$compPecheReadSql) or die('erreur dans la requete : '.pg_last_error());
					$compPecheRow = pg_fetch_row($compPecheReadResult); // une seule ligne en retour, pas besoin de faire une boucle
					
					if (pg_num_rows($compPecheReadResult) == 0) {
					// L'enregistrement n'existe pas dans BD_PECHE
						$cptChampVide++ ;
						WriteCompLog ($logComp," DIFF ".$tables[$cpt]." l'enreg id = ".$compRow[0]." n'existe pas dans BD_PECHE.");
						$scriptSQL = GetSQL('insert',  $tables[$cpt], $where, $alias);
						if ($typeAction == "maj") {
						// Création de l'enreg dans BD_PECHE
						
						} else {
						// On génère un fichier de mise à jour utilisable
							WriteCompSQL ($SQLComp,$scriptSQL);
						}
						
					} else {
					// On balaye tous les champs à comparer en ignorant les clés primaires id.
						for ($cpt1 = 0; $cpt1 <= $nbChamp; $cpt1++) {
						logWriteTo(4,"notice","id = ".$compRow[0]." comparaison de ".$listeChamp[$cpt1],"","","1");				
							if ($listeChamp[$cpt1] == "id") {
								continue;
							} else {
								// Comparaison
								if ($compPecheRow[$cpt1] == $compRow[$cpt1]) {
									// identique
									$cptChampEq++ ;
									//logWriteTo(4,"notice","id = ".$compRow[0]." enreg identique pour ".$listeChamp[$cpt1],"","","1");
									
								}
								 else {
									// différent
									//logWriteTo(4,"notice","id = ".$compRow[0]." enreg different pour ".$listeChamp[$cpt1],"","","1");
									$cptChampDiff++ ;
									WriteCompLog ($logComp," DIFF ".$tables[$cpt]." l'enreg id = ".$compRow[0]." est different (ref= ".$compRow[$cpt1]." dans bd_peche= ".$compPecheRow[$cpt1].")");
									$scriptSQL = GetSQL('update',  $tables[$cpt], $where, $alias);
									if ($typeAction == "maj") {
									// Maj de l'enreg dans BD_PECHE
									
									} else {
									// On génère un fichier de mise à jour utilisable
										WriteCompSQL ($SQLComp,$scriptSQL);
									}
								}
							}
		
						} // end for ($cpt1 = 0; $cpt1 <= $nbChamp; $cpt1++)
											
					} // end if (pg_num_rows($compPecheReadResult) == 0)
				pg_free_result($compPecheReadResult);
				} // end while ($compRow = pg_fetch_row($compReadResult))

			} // end if(pg_num_rows($compReadResult) == 0) table de ref vide ?
			// Libère le requete sur BD_PECHE
			pg_free_result($compReadResult);
		} // end if (pg_num_rows($testPecheReadResult) == 0) table peche vide ?
		// Libère le requete sur BD_PPEAO
		pg_free_result($testPecheReadResult);
		// On fait le bilan sur la table.
		// sortie ecran et sortie fichier
		
		if ($cptChampVide > 0) {
			$cptTableLignesVides++;
			$CRexecution = $CRexecution." ".$tables[$cpt]." avec donn&eacute;es manquantes |";
			WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." avec donnees manquants");
		}		
		
		if ($cptChampDiff > 0) {
			$cptTableDiff++;
			$CRexecution = $CRexecution." ".$tables[$cpt]." avec donn&eacute;es diff&eacute;rents |";
			WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." avec donnees differents");
		} else {
			if ($tableVide) {
				$cptTableVide++;
				$CRexecution = $CRexecution." ".$tables[$cpt]." vide |";
				WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." vide");
			} else {
				WriteCompLog ($logComp,"TABLE ".$tables[$cpt]." identique");
				$CRexecution = $CRexecution." ".$tables[$cpt]." identique |";
			}
		}	
			
		// pour des raisons de timeout, relancer la requete Http ? Comment stocker les valeurs intermediaires (compteurs ?)
		
		
	} // end for ($cpt = 0; $cpt <= $nbTables; $cpt++)

	// Fin de traitement : affichage des résultats.
	// *********************************************
	
	logWriteTo(4,"notice","**- Compte rendu traitement ".$nomAction,"","","0");
	WriteCompLog ($logComp,"Compte rendu traitement ".$nomAction);
	logWriteTo(4,"notice","*-- Nombre total de tables lues = ".$cptTableTotal,$cptTableTotal,"","0");
	WriteCompLog ($logComp,"Nombre total de tables lues = ".$cptTableTotal);
	logWriteTo(4,"notice","*-- Nombre de tables avec des donnees differences = ".$cptTableDiff,$cptTableDiff,"","0");
	WriteCompLog ($logComp,"Nombre de tables avec des donnees differences = ".$cptTableDiff);
	logWriteTo(4,"notice","*-- Nombre de tables avec des donnees manquantes = ".$cptTableLignesVides,$cptTableLignesVides,"","0");
	WriteCompLog ($logComp,"Nombre de tables avec des donnees manquantes = ".$cptTableLignesVides);
	logWriteTo(4,"notice","*-- Nombre de tables vides = ".$cptTableVide,$cptTableVide,"","0");
	WriteCompLog ($logComp,"Nombre de tables vides = ".$cptTableVide);
	// On gére un compte rendu
	if ($cptTableDiff == 0 && $cptTableVide == 0) {
			// Pas de différences
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." ex&eacute;cut&eacute;e avec succ&egrave;s et toutes les tables sont identiques .".$CRexecution." ".$messageGen."</div>" ;	
	} else {
		// Différences
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/dep.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." ex&eacute;cut&eacute;e avec succ&egrave;s mais des tables sont diff&eacute;rentes et/ou vides.<br/>".$CRexecution." ".$messageGen."</div>" ;	
	
	// Fin de traitement : Fermeture base de données et fichier log	
	// *********************************************	

	
	}
	
	
} else {
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">En Test Etape de ".$nomAction." non ex&eacute;cut&eacute;e (var pasdetraitement = true)</div>" ;
} // end if (! $pasdetraitement )
exit;


?>
