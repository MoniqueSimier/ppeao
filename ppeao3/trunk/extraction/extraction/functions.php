<?php 
//*****************************************
// functions.php
//*****************************************
// Created by Yann Laurent
// 2009-06-30 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilisées dans l'extraction des données
//*****************************************

// Definition d'un paramètre global
$PathFicConf = $_SERVER["DOCUMENT_ROOT"]."/conf/extraction.txt" ;//*	
$pasdefichier = false; // residu du portage que j'aurai du virer depuis longtemps
// Variables qui seront globales dans les fonctions
$ListeTable = "";
$TableATester = "";
$Filiere = "";
$TypePecheEnCours="";
$StatEnCours="";
$NomTableEnCours="";
$NumChampDef = 0;
$NumChampFac = 0;
$ListeTableInput = "";


//*********************************************************************
// ajouterAuWhere : test et ajoute
function  ajouterAuWhere($WhereEncours,$CodeAajouter) {
	if (strpos($WhereEncours,$CodeAajouter) === false ) {
		if ($WhereEncours == "" ) {
			$WhereEncours = $CodeAajouter;
		} else {
			$WhereEncours .= " and ".$CodeAajouter;
		}
	}
	return $WhereEncours;
}
//*********************************************************************
// ajoutAuTableSel : test et ajoute
function  ajoutAuTableSel($ListeTableSel,$TNomLongTable,$CondAAjouter) {
	if (strpos($ListeTableSel,$TNomLongTable) === false ) {
		$ListeTableSel .= $CondAAjouter;
	} 
	return $ListeTableSel;
}
//*********************************************************************
// TestSQLAucun : test si le SQL contient aucun/aucune, si oui, renvoie blanc, sinon renvoie le SQL sans la derniere virgule
function TestSQLAucun($SQLATester) {
	$SQLARenvoyer = "";
	if (!($SQLATester == "")) {
		if (strpos("aucun",$SQLATester) === false ) {
			$SQLARenvoyer 	= substr($SQLATester,0,- 1); // pour enlever la virgule surnumeraire;
		} 
	}
	return $SQLARenvoyer;
}

//*********************************************************************
// AfficherSelection : Fonction d'affichage de la selection
function AfficherSelection($file) {
// Cette fonction est la fonction qui analyse le ficher de sélection et qui affiche la dite selection
// Elle permet aussi de remplir les variables SQL* qui contient la traduction en liste de variables de la sélection 
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $file : le fichier de paramétrage qui contient la sélection issue de l'etape précédente
//*********************************************************************
// En sortie : 
// La fonction renvoie $listeSelection
//*********************************************************************
	// Données pour la selection 
	global $typeSelection ;
	global $typePeche;
	global $typeStatistiques;

	global $listeGTEngin;
	// Pour construire le bandeau avec la sélection
	global $listeSelection;
	// Pour construire les SQL
	global $SQLPays 	;
	global $SQLSysteme	;
	global $SQLSecteur	;
	global $SQLAgg		;
	global $SQLEngin	;
	global $SQLGTEngin ;
	global $SQLCampagne ;
	global $SQLPeEnquete; // liste des enquetes
	global $SQLEspeces	;
	global $SQLFamille ;
	global $SQLdateDebut ; // format annee/mois
	global $SQLdateFin ; // format annee/mois
	global $listeDocPays; // liste des docs pour le pays
	global $listeDocSyst; // liste des docs pour le systeme
	global $listeDocSect; // liste des docs pour le secteur
	global $connectPPEAO;	

	
	// Appel à la fonction de création et d'initialisation du parseur
	if (!(list($xml_parser, $fp) = new_xml_parser($file))){ 
		die("Impossible d'ouvrir le document XML"); 
	}
	// Traitement de la ressource XML
	
	while ($data = fread($fp, 4096)){
	
		if (!xml_parse($xml_parser, $data, feof($fp))){
			die(sprintf("Erreur XML : %s à la ligne %d<br/>",
			xml_error_string(xml_get_error_code($xml_parser)),
			xml_get_current_line_number($xml_parser)));
		   }
	}
	
	// Libération de la ressource associée au parser
	xml_parser_free($xml_parser);
	// On colle tous en variable de session, comme ca, pas de pb...
	$_SESSION['typeSelection'] = $typeSelection;
	$_SESSION['typePeche'] = $typePeche;
	$_SESSION['typeStatistiques'] = $typeStatistiques;
	$_SESSION['SQLdateDebut'] = $SQLdateDebut;
	$_SESSION['SQLdateFin'] = $SQLdateFin;
	$SQLPays 		= TestSQLAucun($SQLPays);
	$SQLSysteme 	= TestSQLAucun($SQLSysteme);
	$SQLSecteur 	= TestSQLAucun($SQLSecteur);
	$SQLAgg 		= TestSQLAucun($SQLAgg);
	$SQLEngin 		= TestSQLAucun($SQLEngin);
	$SQLGTEngin 	= TestSQLAucun($SQLGTEngin);
	$SQLCampagne 	= TestSQLAucun($SQLCampagne);
	$SQLEspeces 	= TestSQLAucun($SQLEspeces);
	$SQLFamille 	= TestSQLAucun($SQLFamille);
	$SQLPeEnquete 	= TestSQLAucun($SQLPeEnquete);
	$listeDocPays 	= TestSQLAucun($listeDocPays); 
	$listeDocSyst	= TestSQLAucun($listeDocSyst); 
	$listeDocSect	= TestSQLAucun($listeDocSect); 	
	$_SESSION['SQLPays'] = $SQLPays;
	$_SESSION['SQLSysteme'] = $SQLSysteme;
	$_SESSION['SQLSecteur'] = $SQLSecteur;
	$_SESSION['SQLAgg'] = $SQLAgg;
	$_SESSION['SQLEngin'] = $SQLEngin;
	$_SESSION['SQLGTEngin'] = $SQLGTEngin;
	$_SESSION['SQLCampagne'] = $SQLCampagne;
	$_SESSION['SQLFamille'] = $SQLFamille;
	$_SESSION['SQLPeEnquete'] = $SQLPeEnquete;
	$_SESSION['listeDocPays'] = $listeDocPays; //liste contenant les ID des documents pays a mettre en zip
	$_SESSION['listeDocSys'] = $listeDocSyst; //liste contenant les ID des documents systeme a mettre en zip
	$_SESSION['listeDocSect'] = $listeDocSect; //liste contenant les ID des documents secteur a mettre en zip

	// On ajoute dans la liste des especes les ID venant des especes selectionnees.
	// Au moins c'est fait ici, on n'a plus a se poser de questions et le faire 100 fois apres
	$listEspFamille = "";
	if (!($SQLFamille =="")) {
		$SQLfam = "select id from ref_espece where ref_famille_id in (".$SQLFamille.")";	
		$SQLfamResult = pg_query($connectPPEAO,$SQLfam);
		$erreurSQL = pg_last_error($connectPPEAO);
		if ( !$SQLfamResult ) {
			echo "erreur execution SQL pour ".$SQLfam." erreur complete = ".$erreurSQL."<br/>";
		//erreur
		} else { 
			if (pg_num_rows($SQLfamResult) == 0) {
				// Erreur
				echo "pas d'especes trouv&eacute;es dont le famille_id est ".$SQLFamille."<br/>" ;
			} else { 
				
				while ($famRow = pg_fetch_row($SQLfamResult) ) {
					if ($listEspFamille == "") {
						$listEspFamille = "'".$famRow[0]."'";
					} else {
						$listEspFamille .= ",'".$famRow[0]."'";
					}
				}
			}
		}
		if ($SQLEspeces == "") {
			$SQLEspeces = $listEspFamille;
		} else {
			$SQLEspeces .= ",".$listEspFamille;
		}
		
		pg_free_result($SQLfamResult);		
	} else  {
		$listeSelection = str_replace("<b>familles</b> :","<b>familles</b> : toutes",$listeSelection);
	}	
	$_SESSION['SQLEspeces'] = $SQLEspeces;
	if ($SQLEspeces=="") {
		$listeSelection = str_replace("<b>especes</b> :","<b>especes</b> : toutes",$listeSelection);
		// On va reconstruire cette liste plus tard dans la fonction afficherdonnees
	}
	return $listeSelection;

}
//*********************************************************************
// AfficherDonnees : ajoute un enreg dans la table temporaire
function AjoutEnreg($regroupDeb,$debIDPrec,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$finalRow,$typeStatistiques,$effort){
// Cette fonction permet d'ajouter les lignes du tableau temporaire regroupDeb dans la table temp_extraction
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $regroupDeb : le tableau contenant les lignes a ajouter
//*********************************************************************
// En sortie : 
// La fonction ne renvoie rien. Mais la variable $resultatLecture est mise à jour pour un affichage dans le script qui appelle
// cette fonction. 
//*********************************************************************
	global $EcrireLogComp;
	global $pasdefichier;
	global $logComp;
	global $connectPPEAO;
	global $cptTempExt;
	$debugLog = false;
	$LocPasErreur = true;
	$NbRegDeb = count($regroupDeb);
	if ($NbRegDeb >= 1 ) {
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : mise à jour de la table TEMP_EXTRACTION",$pasdefichier);
		}
		for ($cptRg=1 ; $cptRg<=$NbRegDeb;$cptRg++) {
			//
			$cptTempExt ++;
			$ColonneTE = "id";
			$ValuesTE = $cptTempExt;
			$ColonneTE .= ",key1";
			$ValuesTE .= ",'".$debIDPrec."'";
			$ColonneTE .= ",key2";
			$ValuesTE .= ",'".$regroupDeb[$cptRg][1]."'";	
			$ColonneTE .= ",key3";
			$ValuesTE .= ",'".$regroupDeb[$cptRg][2]."'";
			$ColonneTE .= ",key4";
			$ValuesTE .= ",'".$regroupDeb[$cptRg][6]."'";			
	// Analyse de la ligne, on remplace l'espece par le regroupement et les valeurs poids et nombre par les valeurs agrégées
			$nbrRow = count($finalRow)-1;
			$ligneResultat = "";
			for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
				if ($cptRow<> $posESPID && $cptRow<> $posESPNom && $cptRow<> $posStat1 && $cptRow<> $posStat2 && $cptRow<> $posStat3 ){
					$ligneResultat .= "&#&".$finalRow[$cptRow];
				} else {
					switch ($cptRow) {
					case $posESPID :
						$ligneResultat .= "&#&".$regroupDeb[$cptRg][1];
						break;
					case $posESPNom :
						$ligneResultat .= "&#&".$regroupDeb[$cptRg][2];
						break;
					case $posStat1 :
						$ligneResultat .= "&#&".$regroupDeb[$cptRg][3];
						break;
					case $posStat2 :
						$ligneResultat .= "&#&".$regroupDeb[$cptRg][4];
						break;
					case $posStat3 :
						$ligneResultat .= "&#&".$regroupDeb[$cptRg][5];
						break;
	
					}
				}
			}
			if ($typeStatistiques == "generales") {
				// Pour les statistiques generales, on effectue le calcul de l'effort total pour la strate correspondante
				if (floatval($regroupDeb[$cptRg][4]) <> 0 ) {
					$EffortTotal = floatval($regroupDeb[$cptRg][5]) / floatval($regroupDeb[$cptRg][4]) * $effort;
					//echo $regroupDeb[$cptRg][6]." - ".floatval($regroupDeb[$cptRg][5])." - ".floatval($regroupDeb[$cptRg][4])." - ".$effort." - ".$EffortTotal."<br/>";
					$ligneResultat .= "&#&".$EffortTotal;
				} else {
					$ligneResultat .= "&#&Erreur Calcul";
				}
				
			}
			$ColonneTE .= ",valeur_ligne";
			$ligneResultat = str_replace("'","''",$ligneResultat);
			$ValuesTE .= ",'".$ligneResultat."'";									
			$ColonneTE .= ",date_creation";
			$ValuesTE .= ",'".date("Y-m-d")."'";
			$SQLInsert = "insert into temp_extraction (".$ColonneTE.") values (".$ValuesTE.")";
			if ($EcrireLogComp && $debugLog) {
					WriteCompLog ($logComp, "DEBUG : ".$SQLInsert,$pasdefichier);
				}
			//echo $SQLInsert."<br/>";
			$SQLInsertresult = pg_query($connectPPEAO,$SQLInsert);
			$erreurSQL = pg_last_error($connectPPEAO);
			if ( !$SQLInsertresult ) { 
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "ERREUR : Erreur insert temp_extraction sql = ".SQLInsertresult."(erreur complete = ".$erreurSQL.")",$pasdefichier);
				}
				$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur Erreur insertion dans temp_extraction - sql = ".$SQLInsertresult." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
				$LocPasErreur = false;
			} else {
				if ($EcrireLogComp && $debugLog) {
					WriteCompLog ($logComp, "DEBUG : ajout dans temp_extraction ".$regroupDeb[$cptRg][1]." ".$regroupDeb[$cptRg][2]." ".$regroupDeb[$cptRg][3]." ",$pasdefichier);
				}
			}
			pg_free_result($SQLInsertresult);
			
		} // fin for ($cptRg=1 ; $cptRg<=$NbRegDeb;$cptRg++)
	} else {
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : tableau temp vide ==> pas mise à jour de la table TEMP_EXTRACTION",$pasdefichier);
		}
	}
	return $LocPasErreur;

}

//*********************************************************************
// AfficherDonnees : Fonction d'extraction qui affiche les données
function AfficherDonnees($file,$typeAction){
// Cette fonction est la fonction principale de l'extraction qui permet de compter les resultats mais aussi de les afficher
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $file : le fichier de paramétrage qui contient la sélection issue de l'etape précédente
// $typeAction : la filere en cours
//*********************************************************************
// En sortie : 
// La fonction ne renvoie rien. Mais la variable $resultatLecture est mise à jour pour un affichage dans le script qui appelle
// cette fonction. 
//*********************************************************************
	$debugLog = false;
	$debugAff=false;
	$start_while=timer(); 		// début du chronométrage du for
	if ($debugAff) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "debut AfficherDonnees :".$debugTimer."<br/>";
	}
	// Il faut s'assurer qu'au moins une fois la fonction qui remplit ces variables de session a été lancée 
	$typeSelection 	= $_SESSION['typeSelection'];
	$typePeche		= $_SESSION['typePeche'];
	$typeStatistiques = $_SESSION['typeStatistiques'];
	$SQLPays 		= $_SESSION['SQLPays'];
	$SQLSysteme		= $_SESSION['SQLSysteme'];
	$SQLSecteur		= $_SESSION['SQLSecteur'];
	$SQLAgg			= $_SESSION['SQLAgg'];
	$SQLEngin		= $_SESSION['SQLEngin'];
	$SQLGTEngin 	= $_SESSION['SQLGTEngin'];
	$SQLCampagne 	= $_SESSION['SQLCampagne'];
	$SQLPeEnquete 	= $_SESSION['SQLPeEnquete']; // liste des enquetes	
	$SQLEspeces		= $_SESSION['SQLEspeces'];
	$SQLFamille 	= $_SESSION['SQLFamille'];
	$SQLdateDebut 	= $_SESSION['SQLdateDebut']; // format annee/mois
	$SQLdateFin 	= $_SESSION['SQLdateFin']; // format annee/mois
	$listeDocPays 	= $_SESSION['listeDocPays']; // liste des docs pour le pays
	$listeDocSyst 	= $_SESSION['listeDocSys'] ; // liste des docs pour le systeme
	$listeDocSect 	= $_SESSION['listeDocSect']; // liste des docs pour le secteur
	set_time_limit(0);
	// Attention, le cas des especes est un peu particulier.
	// On utilise 2 variables de session : 
	// SQLEspeces contient les données venant de la sélection (ie si lors de l'étape précédente, on a sélectionné des especes ou familles
	// listeEspeces contient la sélection des especes venant des filières. Elle est au maximum égale a SQLEspeces.
	// La référence pour le SQL final doit etre ListeEspeces.

	$listeChamps = "";
	global $connectPPEAO;
	global $resultatLecture;
	global $divExportFic;
	global $compteurItem;
	global $restSupp;
	global $labelSelection;
	global $CRexecution;
	global $erreurProcess;
	global $exportFichier;
	global $EcrireLogComp;
	global $pasdefichier;
	global $logComp;
	global $codeTableEnCours;
	global $SelectionPourFic;
	// pour recuperer les listes des selections par colonne
	global $listeChampsSel;
	global $ListeTableSel;
	global $AjoutWhere;
	global $LeftOuterJoin;
	global $listeDocURL;
	if (!($typeAction == "")) {
		$divExportFic = "<div id=\"exportFic\"><input type=\"button\" id=\"validation\" onClick=\"runFilieresArt('".$typePeche."?>','".$typeAction."','1','".$codeTableEnCours."','y',,'','')\" value=\"Voir les r&eacute;sultats\"/>
	<input type=\"checkbox\" id=\"ExpFic\" />Exporter sous forme de fichier</div>";
	}
	// Cas de la navigation a l'interieur d'une pagination :
	// On ne regenere pas le fichier...	
	if (isset($_GET['dejf'])) {
		if ($_GET['dejf'] =="y") {
			$fichierDejaCree = true;
		} else {
			$fichierDejaCree = false;
		}
	} else {
		$fichierDejaCree = false;
	} 		
		
	if ($exportFichier ) {
		// On recupère les info pour creer le fichier d'export 
		$nomLogLien = "/work/extraction";
		$dirLog = $_SERVER["DOCUMENT_ROOT"].$nomLogLien;
		
		// On fait tous les tests associés
		if (! file_exists($dirLog)) {
			if (! mkdir($dirLog) ) {
				$resultatLecture .= " erreur de cr&eacute;ation du r&eacute;pertoire d'export des fichiers";
				exit;
			}
		}
		//	Controle fichiers
		// Pour les statistiques, il n'y a pas un seul fichier de données mais 6
		// On cree directement le zip avec tous les fichiers.
		if ($typeSelection == "statistiques") {
			$ajoutNomStat = "_".$typeStatistiques;
		} else {
			$ajoutNomStat = "";
		}
		$nomFicExport = $dirLog."/".date('y\-m\-d-H-i')."_".$typeSelection."_".$typeAction.".txt";
		$nomFicExportSel = $dirLog."/".date('y\-m\-d-H-i')."_".$typeSelection."_".$typeAction."-Selection.txt";
		if (!($_SESSION['listeRegroup'] == "")) {
			$nomFicExportReg = $dirLog."/".date('y\-m\-d-H-i')."_".$typeSelection."_".$typeAction.$ajoutNomStat."-Regroupement.txt";
		}
		$nomFicExpLien = $nomLogLien."/".date('y\-m\-d-H-i')."_".$typeSelection."_".$typeAction.$ajoutNomStat.".txt";
		// On ne cree le fichier que si il n'a pas deja ete rempli !
		if (!($fichierDejaCree)) {
			if (!($typeSelection == "statistiques")) {
				$ExpComp = fopen($nomFicExport , "w+");
				if (! $ExpComp ) {
					$resultatLecture .= " erreur de cr&eacute;ation du fichier export ".$nomFicExpLien;
					exit;		
				}
			}
			$ExpCompSel = fopen($nomFicExportSel , "w+");
			if (! $ExpCompSel ) {
				$resultatLecture .= " erreur de cr&eacute;ation du fichier export contenant la selection";
				exit;		
			}
			if (!($_SESSION['listeRegroup'] == "")) {
				$ExpCompReg = fopen($nomFicExportReg , "w+");
				if (! $ExpCompReg ) {
					$ExpReg .= " erreur de cr&eacute;ation du fichier export contenant la definition des regroupements ";
					exit;		
				}
			}
		}
		// Gestion du fichier d'archive
		$zipFilename = $_SERVER["DOCUMENT_ROOT"]."/work/extraction/extraction_".$typeAction.$ajoutNomStat."_".date('y\-m\-d-H-i').".zip";
		$zipFilelien = "/work/extraction/extraction_".$typeAction.$ajoutNomStat."_".date('y\-m\-d-H-i').".zip";
		if (!($fichierDejaCree)) {
			if (file_exists($zipFilename)) {
				// pas forcement necessaire, verifier que le x+ vide le fichier
				unlink($zipFilename);
			}
			$theZipFile=new zip_file($zipFilename);	
			//setting the zip options: write to disk, do not recurse directories, do not store path and do not compress
			$theZipFile->set_options(array('inmemory' => 0, 'recurse' => 0, 'storepaths' => 0, 'method'=>0));			
		}
		
		$resultatLecture .= "<span class=\"infozip\">Le fichier de donn&eacute;es (Zip) est disponible au t&eacute;l&eacute;chargement <a href=\"".$zipFilelien."\" class=\"lienReg\" target=\"export\"/>ici</a>.</span><br/>";
	} 	
	// Analyse des paramètres communs
	if ($SQLSecteur == "") {
		$WhereSect = "";
	} else {
		$WhereSect = "se.id in (".$SQLSecteur.") and";
	}		
	if ($SQLSysteme == "") {
		$WhereSyst = "";
		// Ici on doit traiter du cas d'une sélection restrictive des pays
	} else {
		$WhereSyst = "sy.id in (".$SQLSysteme.") and";
	}	
	$LabCatEco = "";
	$LabCatTrop = "";
	$LabCatPois = "";
	$LeftOuterJoin = "";
	$ConstIDunique = ""; // Va contenir la définition pour la construction de l'ID unique de ligne. contient une valeur differente selon type peche / filiere
	// Analyse des categories trophiques / ecologiques / poisson-non poisson
	// Analyse des categories ecologiques sélectionnées par l'utilisateur (selection restreinte depuis la filiere)
	if (!($_SESSION['listeCatEco'] == "")) {
		$compCatEcoSQL = "";
		$CatEcoNull = false;
		$LabCatEco = " restreint aux cat&eacute;gories &eacute;cologiques : ";
		$champSel = explode(",",$_SESSION['listeCatEco']);
		$nbrSel = count($champSel)-1;
		$valCatE = "";
		for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
			// Traitement special pour la valeur null
			if ($champSel[$cptSel] == "null") {
				$CatEcoNull = true;
			} else {
				if ($valCatE == "") {
					$valCatE = "'".$champSel[$cptSel]."'";
				} else {
					$valCatE .= ",'".$champSel[$cptSel]."'";
				}
			}
			$LabCatEco .= $champSel[$cptSel]." ";
		}
		if (!($valCatE=="")){
			$compCatEcoSQL =" esp.ref_categorie_ecologique_id in (".$valCatE.") "; // 
		}
		// Si a choisi de selectionner les categories null, il faut l'expliciter
		if ($CatEcoNull) {
			if ($compCatEcoSQL==""){
				$compCatEcoSQL = "esp.ref_categorie_ecologique_id is null";
			} else  {
				$compCatEcoSQL = "(".$compCatEcoSQL." or esp.ref_categorie_ecologique_id is null)";
			}
		}
	} else {
		$compCatEcoSQL = "";
		$LabCatEco = " - toutes les cat&eacute;gories &eacute;cologiques ";
	}
	// Analyse des categories trophiques sélectionnées par l'utilisateur (selection restreinte  depuis la filiere)
	if (!($_SESSION['listeCatTrop'] == "")) {
		$compCatTropSQL = "";
		$CatTropNull = false;
		$LabCatTrop = " restreint aux cat&eacute;gories trophiques : ";
		$champSel = explode(",",$_SESSION['listeCatTrop']);
		$nbrSel = count($champSel)-1;
		$valCatT = "";
		for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
			// Traitement special pour la valeur null
			if ($champSel[$cptSel] == "null") {
				$CatTropNull = true;
			} else {
				if ($valCatT == "") {
					$valCatT = "'".$champSel[$cptSel]."'";
				} else {
					$valCatT .= ",'".$champSel[$cptSel]."'";
				}
			}
			$LabCatTrop .= $champSel[$cptSel]." ";
		}
		if (!($valCatT=="")){
			$compCatTropSQL =" esp.ref_categorie_trophique_id in (".$valCatT.")"; // Pas and a la fin, c'est le dernier SQL
		}
		// Si a choisi de selectionner les categories null, il faut l'expliciter
		if ($CatTropNull) {
			if ($compCatTropSQL == ""){
				$compCatTropSQL = "esp.ref_categorie_trophique_id is null";
			} else  {
				$compCatTropSQL = "(".$compCatTropSQL." or esp.ref_categorie_trophique_id is null)";
			}
		}
	} else {
		$compCatTropSQL = "";
			$LabCatTrop = " toutes les cat&eacute;gories trophiques ";
	}
	// Analyse du type poisson non poisson sélectionné par l'utilisateur (selection restreinte depuis la filiere)
	if (!($_SESSION['listePoisson'] == "")) {
		$champSel = explode(",",$_SESSION['listePoisson']);
		$nbrSel = count($champSel)-1;
		$valPoisson = "";
		$LabCatPois= "";
		for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
			switch ($champSel[$cptSel]) {
				case "0" : 
					if ($valPoisson == "") {
						$valPoisson = $champSel[$cptSel];
					} else {
						$valPoisson .= ",".$champSel[$cptSel];
					}
					$LabCatPois .= "  poissons inclus";
					break;
				case "1" : 
					if ($valPoisson == "") {
						$valPoisson = $champSel[$cptSel];
					} else {
						$valPoisson .= ",".$champSel[$cptSel];
					}
					$LabCatPois .= " 'non poissons' inclus";
					break;
				case "pp" :
					$LabCatPois .=" poissons exclus";
					break;
				case "np":
					$LabCatPois .= " 'non poissons' exclus";
					break;	
			}
		}
		$compPoisSQL =" fam.non_poisson in (".$valPoisson.") ";
	} else {
		if (!($typeAction =="environnement") && !($typeAction =="activite") && !($typeAction =="capture")){
			$LabCatPois = " tous les poissons ";
		}
	} // fin du if (!($_SESSION['listePoisson'] == ""))
	// *******************************
	// Debut du traitement principal *	
	// *******************************
	$builQuery = false; // il a l'air de rien celui-la, mais ce flag est super important pour créer le SQL final qui sera executé.
	switch ($typeSelection) {
		// #####################################################################################
		// EXTRACTION
		// #####################################################################################
		case "extraction" :
			switch ($typePeche) {
				// *********************************************************************************
				// PECHE EXPERIMENTALE
				// *********************************************************************************
				case "experimentale" :
					// ********** ANALYSE DES SELECTIONS DE L'UTILISATEUR
					// ********** si aucune selection de campagne, alors on aura pas de resultat
					if ($SQLCampagne == "") {
						$_SESSION['pasderesultat'] = true;
						return "pas de resultat <br/>";
					}
					// ==> construction des SQL correspondant - traitement des cas particuliers
					// On controle que des sélections ont été faites pour les espèces / familles
					if ($SQLEngin == "") {
						$WhereEngin = "";
						// Ici on doit traiter du cas d'une sélection restrictive des pays
					} else {
						$WhereEngin = "cph.exp_engin_id in (".$SQLEngin.") and";
					}							
					// Prise en compte des sélections complémentaires
					$compSQL = "";
					if 	(!($_SESSION['listeQualite'] =="")) {
						$compSQL =" cph.exp_qualite_id in (".$_SESSION['listeQualite'].") ";
						$restSupp = " qualit&eacute; limit&eacute;e à =".$_SESSION['listeQualite'];
					}
					if (!($_SESSION['listeProtocole'] == "")) {
						switch ($_SESSION['listeProtocole']) {
						case "0" : $restSupp .= " - non restreint aux coups du protocoles ";
									break;
						case "1" : $restSupp .= " - restreint aux coups du protocoles ";
									if ($compSQL == "") {
										$compSQL =" cph.protocole = 1";
									} else {
										$compSQL .=" and cph.protocole = 1";
									}
									break;
						}
					}
					// Les selections ci-dessous ne sont valables que pour les filieres autres que l'environnement
					if ($typeAction =="environnement" ||  $typeAction =="peuplement"){
						$compCatEcoSQL = "";
						$compCatTropSQL ="";
						if ($typeAction =="environnement") {
							$compPoisSQL ="";				
						}
						$restSupp .= " - ".$LabCatPois." ";
					} 	else {
						// Maj du libelle de la selection en tete avec les restriction CatEco CatTroph et poisson
						$restSupp .= " - ".$LabCatEco." - ".$LabCatTrop." - ".$LabCatPois." ";
		
					}
					// ********** Gestion de l'affichage des colonnes sélectionnées 
					$listeChampsSel = "";
					$ListeTableSel = "";
					$WhereSel = "";
					$AjoutWhere = "";
					// Analyse de la liste des colonnes venant des sélections précédentes, ajout de ces colonnes au fichier
					analyseColonne($typePeche,$typeAction,"");	
					// Analyse des différents composants du where et ajout des and quand nécessaire
					// C'est un peu le bronx pour construire ces SQL, mais pas le choix. On doit pouvoir optimiser...
					if ($compSQL == "" ) {
						$WhereSel = $compCatEcoSQL;
					} else {
						if ($compCatEcoSQL == "") {
							$WhereSel = $compSQL;
						} else {
							$WhereSel = $compSQL." and ".$compCatEcoSQL;
						}
					}
					// Gestion des categories trophiques...
					if (!($compCatTropSQL == "" )) {
						if ($WhereSel == "" ) {
							$WhereSel = $compCatTropSQL;
						} else {
							$WhereSel = $WhereSel." and ".$compCatTropSQL;
						}
					}
					// Enfin on ajoute les noms des nouveaux champs à lire depuis les colonnes
					if ($WhereSel == "" ) {
						$WhereSel = $AjoutWhere;
					} else {
						if (!($AjoutWhere == "")) {
							$WhereSel = $WhereSel." and ".$AjoutWhere;
						}
					}
					// Cas particulier d'aucun sélection des espèces : 
					// On reconstruit cette liste pour l'ensemble de la sélection car on va en avoir besoin
					// pour les catégories trophiques/ecologiques
					if ($SQLEspeces == "") {
						// On reconstruit la liste des especes de la sélection.
						$SQLEsp = "select esp.id from ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_station as stat,exp_campagne as cpg,exp_coup_peche as cph,exp_fraction as fra,ref_espece as esp
								where cpg.id = cph.exp_campagne_id and
								stat.id = cph.exp_station_id and
								sy.id = cpg.ref_systeme_id and
								".$WhereSyst."
								py.id = sy.ref_pays_id and
								se.id = stat.ref_secteur_id and
								fra.exp_coup_peche_id = cph.id and
								esp.id = fra.ref_espece_id and
								".$WhereSect."
								cpg.id in (".$SQLCampagne.")";
						$SQLEspResult = pg_query($connectPPEAO,$SQLEsp);
						$erreurSQL = pg_last_error($connectPPEAO);
						if ( !$SQLEspResult ) { 
							$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query ".$SQLEsp." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
							if ($EcrireLogComp) {
								WriteCompLog ($logComp, "ERREUR : echec construction liste espece query sql = ".$SQLEsp." (erreur complete = ".$erreurSQL,$pasdefichier);
							}
							$erreurProcess = true;
							return ("erreur SQL especes");
						} else {
							
							if (pg_num_rows($SQLEspResult) == 0) {
							// Erreur
								$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>pas de coup de peche dispo vide...<br/>";
								if ($EcrireLogComp) {
									WriteCompLog ($logComp, "Warning :  pas de coupe de peches trouves pour remplir la liste des especes.",$pasdefichier);
								}
							} else {
								while ($EspRow = pg_fetch_row($SQLEspResult) ) {
									if (strpos($SQLEspeces,$EspRow[0]) === false ) {
										$SQLEspeces .= "'".$EspRow[0]."',";	
									}
								}		
							}
							pg_free_result($SQLEspResult);
						}
						$SQLEspeces	= substr($SQLEspeces,0,- 1); // pour enlever la virgule surnumeraire;
						$_SESSION['SQLEspeces'] = $SQLEspeces; // ca va servir pour la suite....
					}

					// Si malgré tout, toujours pas d'especes dispo, ben tant pis....
					if ($SQLEspeces == "") {
						$WhereEsp = "";
					} else {
						// Enfin on verifie qu'il n'y a pas eu de restriction supplémentaires
						if (!($_SESSION['listeEspeces'] == "")) {
							$TempSQLEspeces = $SQLEspeces;
							$SQLEspeces = "";
							$EspecesSele = explode (",",$_SESSION['listeEspeces']);
							$NumEsp = count($EspecesSele) - 1;
							for ($cptES=0 ; $cptES<=$NumEsp;$cptES++) {
								
								if (strpos($TempSQLEspeces,$EspecesSele[$cptES]) === false ){
					
								} else {
									// La valeur est disponible, on la met à jour
									if ($SQLEspeces == "" ) {
										$SQLEspeces = "'".$EspecesSele[$cptES]."'";
									} else {
										$SQLEspeces .= ",'".$EspecesSele[$cptES]."'";
									}
								}
							}
						} 
						if (!($SQLEspeces == "" )) {
							$WhereEsp = "fra.ref_espece_id in (".$SQLEspeces.") and";
						} else {
							if ($EcrireLogComp ) {
								WriteCompLog ($logComp, "ERREUR SQLEspeces est encore vide.",$pasdefichier);
							}
						}
					}
					// ********** PREPARATION DU SQL
					// Definition de tout ce qui est commun aux peches expérimentales
					$listeChampsCom = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom, stat.id, stat.nom, cpg.date_debut, cpg.numero_campagne, cph.date_cp, cph.heure_debut, cph.numero_coup, cph.protocole, cph.exp_qualite_id,xqua.libelle, cph.exp_engin_id, xeng.libelle";
					$ListeTableCom = "ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_campagne as cpg,exp_coup_peche as cph,exp_qualite as xqua,exp_engin as xeng";
					$WhereCom = "cpg.id = cph.exp_campagne_id and
									stat.id = cph.exp_station_id and
									sy.id = cpg.ref_systeme_id and
									".$WhereSyst."
									py.id = sy.ref_pays_id and
									se.id = stat.ref_secteur_id and
									".$WhereSect."
									cpg.id in (".$SQLCampagne.") and
									xqua.id = cph.exp_qualite_id and
									".$WhereEngin."
									xeng.id = cph.exp_engin_id ";
					$OrderCom = "order by py.id asc,sy.id asc,cph.numero_coup asc";
					// ********** CONSTRUCTION DES SQL DEFINITIFS PAR FILIERE
					switch ($typeAction) {
						case "peuplement" :
							$labelSelection = "donn&eacute;e(s) de peuplement ";	
							// On n'extrait que des donnéees de fraction
							// Il n'y aucune selection de colonnes supplémentaires
							// On prend tous les poissons (pas de différence poisson/non poisson
							$listeChampsSpec = ",esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,fra.nombre_total ,fra.poids_total";
							$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam"; 
							$WhereSpec = " and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
								esp.id = fra.ref_espece_id and
								fam.id = esp.ref_famille_id and ".$compPoisSQL;
							$OrderCom .= ",esp.id asc";
							$valueCount = "fra.id" ; // pour gerer la pagination	
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",exp_station as stat,ref_espece as esp";
							} else {	
								if (strpos($LeftOuterJoin,"exp_station as stat") === false ) {
									$LeftOuterJoin = ",exp_station as stat ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"ref_espece as esp") === false ) {
									$LeftOuterJoin = ",ref_espece as esp ".$LeftOuterJoin;
								}
							}
							break;
						case "environnement" :
							$labelSelection = "donn&eacute;e(s) d'environnement ";
							// On n'extrait que des donnéees environnements
							// Pas de données poisson
							$listeChampsSpec = "";
							$ListeTableSpec = " "; 
							$WhereSpec = " 	and env.id = cph.exp_environnement_id ";
							$valueCount = "cph.id" ; // pour gerer la pagination						
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",exp_station as stat,exp_environnement as env";
							} else {
								if (strpos($LeftOuterJoin,"exp_station as stat") === false ) {
									$LeftOuterJoin = ",exp_station as stat ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"exp_environnement as env") === false ) {
									$LeftOuterJoin = ",exp_environnement as env ".$LeftOuterJoin;
								}
							}
							break;
						case "NtPt" :
							$labelSelection = "donn&eacute;e(s) NtPt ";
							// C'est un mixte entre les données peuplements et environnement + des selections de colonnes
							$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id";
							$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam";
							$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
								esp.id = fra.ref_espece_id and
								fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and ".$compPoisSQL;
							$OrderCom .= ",esp.id asc";
							$valueCount = "cph.id" ; // pour gerer la pagination						
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",exp_station as stat,exp_environnement as env,ref_espece as esp";
							} else {
								if (strpos($LeftOuterJoin,"exp_station as stat") === false ) {
									$LeftOuterJoin = ",exp_station as stat ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"ref_espece as esp") === false ) {
									$LeftOuterJoin = ",ref_espece as esp ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"exp_environnement as env") === false ) {
									$LeftOuterJoin = ",exp_environnement as env ".$LeftOuterJoin;
								}
							}
							break;
						case "biologie" :
							$labelSelection = "donn&eacute;e(s) biologique(s) ";
							// Construction de la liste d'individus
							// ATTENTION !!!!!! Si la liste ci-dessous est modifiée, il faut imperativement modifier la requete pour calculer le 
							// le coefficient d'extrapolation apres l'execution de la requete 
							$listeChampsSpec = ",fra.id, fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,bio.longueur,bio.id";
							$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam";
							$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp." 
								esp.id = fra.ref_espece_id and
								fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and
								bio.exp_fraction_id = fra.id and ".$compPoisSQL;
							$OrderCom .= ",esp.id asc,fra.id asc, esp.id asc ";
							$valueCount = "fra.id" ; // pour gerer la pagination						
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",exp_station as stat,exp_environnement as env,exp_biologie as bio,ref_espece as esp";
							} else {
								if (strpos($LeftOuterJoin,"exp_station as stat") === false ) {
									$LeftOuterJoin = ",exp_station as stat ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"exp_biologie as bio") === false ) {
									$LeftOuterJoin = ",exp_biologie as bio ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"ref_espece as esp") === false ) {
									$LeftOuterJoin = ",ref_espece as esp ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"exp_environnement as env") === false ) {
									$LeftOuterJoin = ",exp_environnement as env ".$LeftOuterJoin;
								}
							}
							break;	
						case "trophique" :
							// Construction de la liste d'individus
							$labelSelection = "donn&eacute;e(s) trophique(s) ";
							$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,bio.longueur,bio.id,trop.exp_contenu_id,cont.libelle,bio.exp_remplissage_id";
							$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_trophique as trop, exp_contenu as cont";
							$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."  
								esp.id = fra.ref_espece_id and
								fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and
								bio.exp_fraction_id = fra.id and 
								trop.exp_biologie_id = bio.id 	and
								cont.id = trop.exp_contenu_id and ".$compPoisSQL;	
							$OrderCom .= ",esp.id asc";
							$valueCount = "bio.id" ; // pour gerer la pagination
							$builQuery = true;	
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",exp_station as stat,exp_environnement as env,exp_biologie as bio,ref_espece as esp";
							} else {
								if (strpos($LeftOuterJoin,"exp_station as stat") === false ) {
									$LeftOuterJoin = ",exp_station as stat ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"exp_biologie as bio") === false ) {
									$LeftOuterJoin = ",exp_biologie as bio ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"ref_espece as esp") === false ) {
									$LeftOuterJoin = ",ref_espece as esp ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"exp_environnement as env") === false ) {
									$LeftOuterJoin = ",exp_environnement as env ".$LeftOuterJoin;
								}
							}
							break;
						default	:	
							$labelSelection = "coup(s) de p&ecirc;ches ";
							$SQLfinal = "select py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom, cpg.date_debut, cpg.id,cpg.numero_campagne, cph.date_cp, cph.heure_debut, cph.id,cph.numero_coup, cph.protocole from ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_campagne as cpg,exp_coup_peche as cph,exp_station as stat
									where cpg.id = cph.exp_campagne_id and
									stat.id = cph.exp_station_id and
									sy.id = cpg.ref_systeme_id and
									".$WhereSyst."
									py.id = sy.ref_pays_id and
									se.id = stat.ref_secteur_id and
									".$WhereSect." ".$WhereEngin."
									cpg.id in (".$SQLCampagne.")";
							$SQLcountfinal = "select count(cpg.id) from ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_campagne as cpg,exp_coup_peche as cph,exp_station as stat
									where cpg.id = cph.exp_campagne_id and
									stat.id = cph.exp_station_id and
									sy.id = cpg.ref_systeme_id and
									".$WhereSyst."
									py.id = sy.ref_pays_id and
									se.id = stat.ref_secteur_id and
									".$WhereSect." ".$WhereEngin."
									cpg.id in (".$SQLCampagne.") "; // Pour gerer la pagination
							break;
					}
					break;
				// ********** FIN TRAITEMENT PECHE EXPERIMENTALE
				// *
				// *********************************************************************************
				// PECHE ARTISANALE
				// *********************************************************************************		
				case "artisanale" :
					// ********** DEBUT TRAITEMENT PECHE ARTISANALE
					// ********** Gestion de l'affichage des colonnes sélectionnées 
					if ($SQLPeEnquete == "") {
						$_SESSION['pasderesultat'] = true;
						return "pas de resultat <br/>";
					}
					if ($debugAff) {
						$debugTimer = number_format(timer()-$start_while,4);
						echo "debut traitement donnees artisanales :".$debugTimer."<br/>";
					}		
					$posDEBID = 0 ; 	//Pour gestion regroupement
					$posESPID = 0 ; 	//Pour gestion regroupement
					$posPoids = 0 ; 	//Pour gestion regroupement
					$posNbre = 0 ; 		//Pour gestion regroupement
					$listeChampsSel = "";
					$ListeTableSel = "";
					$WhereSel = "";
					$compSQL = "";
					if ($SQLAgg == "") {
						$WhereAgg = "";
					} else {
						$WhereAgg = "agg.id in (".$SQLAgg.") and";
					}
					$WherePeEnq = "penq.id in (".$SQLPeEnquete.") and ";
					// Grand type engin
					if (!($_SESSION['SQLGTEngin'] == "")) {
						$LabGTE = " - restreint aux grands types engin : ";
						$champSel = explode(",",$_SESSION['SQLGTEngin']);
						$nbrSel = count($champSel)-1;
						$valGTE= "";
						for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
							if ($valGTE == "") {
								$valGTE = "'".$champSel[$cptSel]."'";
							} else {
								$valGTE .= ",'".$champSel[$cptSel]."'";
							}
							$LabGTE .= $champSel[$cptSel]." ";
						}
						$compGTESQL ="gte.id in (".$valGTE.") and ";
					} else {
						$compGTESQL = "";
						$LabGTE = " - toutes les grands types engin ";
					}
					// Les selections ci-dessous ne sont valables que pour les filieres autres que l'environnement
					switch ($typeAction) {
						case "activite" :
							$compCatEcoSQL = "";
							$compCatTropSQL ="";
							$compPoisSQL ="";
							$compGTESQL = "";
						break;
						case "capture":
							$compCatEcoSQL = "";
							$compCatTropSQL ="";
							$compPoisSQL ="";
							break;
						default :
							$restSupp .= " - ".$LabCatEco." - ".$LabCatTrop." - ".$LabCatPois." - ".$LabGTE ;
						break;
					}
					$AjoutWhere = "";
					// Analyse de la liste des colonnes venant des sélections précédentes, ajout de ces colonnes au fichier
					analyseColonne($typePeche,$typeAction,"");			
					// Analyse des différents composants du where et ajout des and quand nécessaire
					// C'est un peu le bronx pour construire ces SQL, mais pas le choix. On doit pouvoir optimiser...
					if ($compSQL == "" ) {
						$WhereSel = $compCatEcoSQL;
					} else {
						if ($compCatEcoSQL == "") {
							$WhereSel = $compSQL;
						} else {
							$WhereSel = $compSQL." and ".$compCatEcoSQL;
						}
					}
					// Gestion des categories trophiques...
					if (!($compCatTropSQL == "" )) {
						if ($WhereSel == "" ) {
							$WhereSel = $compCatTropSQL;
						} else {
							$WhereSel = $WhereSel." and ".$compCatTropSQL;
						}
					}
					// Enfin on ajoute les noms des nouveaux champs à lire depuis les colonnes
					if ($WhereSel == "" ) {
						$WhereSel = $AjoutWhere;
					} else {
						if (!($AjoutWhere == "")) {
							$WhereSel = $WhereSel." and ".$AjoutWhere;
						}
					}
					
					// Cas particulier d'aucun sélection des espèces : 
					// On reconstruit cette liste pour l'ensemble de la sélection car on va en avoir besoin
					// pour les catégories trophiques/ecologiques
					$ajouteTable ="";
					if ($SQLEspeces == "") {
						if (!($compGTESQL == "")) {
							$ajouteTable =",art_grand_type_engin as gte"; // On ajoute la selection du GT
					}
					$SQLEsp = "select distinct(afra.ref_espece_id) from art_debarquement as deb,art_fraction as afra,art_agglomeration as agg,			
						art_periode_enquete as penq".$ajouteTable."
						where ".$WhereAgg." ".$WherePeEnq." ".$compGTESQL."
						deb.art_agglomeration_id = agg.id and
						deb.mois = penq.mois and 
						deb.annee = penq.annee and
						deb.art_agglomeration_id = penq.art_agglomeration_id and 
						afra.art_debarquement_id = deb.id ";
						$SQLEspeces = RecupereEspeces($SQLEsp);
						$_SESSION['SQLEspeces'] = $SQLEspeces; // ca va servir pour la suite..
			
					}
					// Si malgré tout, toujours pas d'especes dispo, ben tant pis....
					if ($SQLEspeces == "") {
						$WhereEsp = "";
					} else {
						// Enfin on verifie qu'il n'y a pas eu de restriction supplémentaires
						if (!($_SESSION['listeEspeces'] == "")) {
							$TempSQLEspeces = $SQLEspeces;
							$SQLEspeces = "";
							$EspecesSele = explode (",",$_SESSION['listeEspeces']);
							$NumEsp = count($EspecesSele) - 1;
							for ($cptES=0 ; $cptES<=$NumEsp;$cptES++) {
								if (strpos($TempSQLEspeces,$EspecesSele[$cptES]) === false ){
					
								} else {
									// La valeur est disponible, on la met à jour
									if ($SQLEspeces == "" ) {
										$SQLEspeces = "'".$EspecesSele[$cptES]."'";
									} else {
										$SQLEspeces .= ",'".$EspecesSele[$cptES]."'";
									}
								}
							}
						} 
						if (!($SQLEspeces == "")) {
							$WhereEsp = "afra.ref_espece_id in (".$SQLEspeces.") and ";
						} else {
							if ($EcrireLogComp ) {
								WriteCompLog ($logComp, "ERREUR SQL especes encor vide ",$pasdefichier);
							}
						}
					}
					// ********** PREPARATION DU SQL
					// Definition de tout ce qui est commun aux peches expérimentales
					// Il va y avoir moins de données communes que pour les peches exp car certaines dependent de la filiere acti ou deb 
					// Donc on cree des variables generales selon qu'on va traiter activite ou debarquement
					// Définition des SQL de base pour les activites (art_activite)
					$listeChampsArt = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.id,se.nom, act.art_agglomeration_id, agg.nom, act.annee, act.mois, act.date_activite, act.id,upec.id";
					$ListeTableArt = "ref_pays as py,ref_systeme as sy,ref_secteur as se,art_periode_enquete as penq";
		
					$WhereArt = "	py.id = sy.ref_pays_id and
									sy.id = se.ref_systeme_id and
									se.id = agg.ref_secteur_id and
									".$WhereSyst." ".$WhereAgg." ".$WhereSect." ".$WherePeEnq." 
									act.art_agglomeration_id = agg.id and
									act.mois = penq.mois and 
									act.annee = penq.annee and
									act.art_agglomeration_id = penq.art_agglomeration_id and
									upec.id = act.art_unite_peche_id";			
					$OrderArt = "order by py.id asc, sy.id asc, agg.nom, act.annee asc,act.mois asc,act.id asc";
					// Définition des SQL de base pour les débarquements (art_debarquement)
					$listeChampsDeb = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom,se.id, deb.art_agglomeration_id, agg.nom, deb.annee, deb.mois, deb.id, deb.date_debarquement";
					$ListeTableDeb = "ref_pays as py,ref_systeme as sy,ref_secteur as se,art_periode_enquete as penq";
				
					$WhereDeb = "	py.id = sy.ref_pays_id and
									sy.id = se.ref_systeme_id and
									se.id = agg.ref_secteur_id and
									".$WhereSyst." ".$WhereAgg." ".$WhereSect." ".$WherePeEnq."
									gte.id = deb.art_grand_type_engin_id and
									".$compGTESQL."
									deb.art_agglomeration_id = agg.id and
									deb.mois = penq.mois and 
									deb.annee = penq.annee and
									deb.art_agglomeration_id = penq.art_agglomeration_id and
									upec.id = deb.art_unite_peche_id";
					$OrderDeb = "order by py.id asc, sy.id asc, agg.nom, deb.annee asc,deb.mois asc,deb.id asc";
					// ********** CONSTRUCTION DES SQL DEFINITIFS PAR FILIERE
					switch ($typeAction) {
						case "activite" :
							// On considere les données d'activité. On commence par mettre à jour les variables communes *com
							$listeChampsCom = $listeChampsArt;
							$ListeTableCom = $ListeTableArt ;
							$WhereCom = $WhereArt ;
							$OrderCom = $OrderArt ;
							$labelSelection = "donn&eacute;e(s) d'activit&eacute;";	
							//echo $listeChampsSel."<br/>";
							if (strpos($listeChampsSel,"act.art_grand_type_engin_id") === false) {
								$listeChampsSpec = ",act.art_type_activite_id,act.nbre_unite_recencee,act.art_grand_type_engin_id";
							} else {
								$listeChampsSpec = ",act.art_type_activite_id,act.nbre_unite_recencee";
							}
							//echo $listeChampsSpec."<br/>";
							$ListeTableSpec = ""; 
							$WhereSpec = "";	
							$ConstIDunique = "ART-##-12"; // Ce qui apres le -##-n sera remplacé par la valeur d'index n de la lecture de la requete par exemple, ici, on va recuperer art.id  
							$valueCount = "act.id" ; // pour gerer la pagination				
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",art_activite as act,art_agglomeration as agg,art_unite_peche as upec";
							}  else {
								if (strpos($LeftOuterJoin,"art_activite as act") === false ) {
									$LeftOuterJoin = ",art_activite as act ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_agglomeration as agg") === false ) {
									$LeftOuterJoin = ",art_agglomeration as agg ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_unite_peche as upec") === false ) {
									$LeftOuterJoin = ",art_unite_peche as upec ".$LeftOuterJoin;
								}
							}
							break;			
						case "capture" :
							// Liste des debarquements.
							$labelSelection = "donn&eacute;e(s) de capture";	
							$listeChampsCom = $listeChampsDeb;
							$ListeTableCom = $ListeTableDeb ;
							$WhereCom = $WhereDeb ;
							$OrderCom = $OrderDeb ;
							if (strpos($listeChampsSel,"deb.art_grand_type_engin_id") === false) {
								$listeChampsSpec = ", deb.poids_total,deb.art_unite_peche_id,deb.art_grand_type_engin_id";
							} else {
								$listeChampsSpec = ", deb.poids_total,deb.art_unite_peche_id";
							}
							$ListeTableSpec = " "; 
							$WhereSpec = " ";
							$ConstIDunique = "DEB-##-11";
							$valueCount = "deb.id" ; // pour gerer la pagination	
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",art_debarquement as deb,art_grand_type_engin as gte,art_agglomeration as agg,art_unite_peche as upec";
							} else {
								if (strpos($LeftOuterJoin,"art_debarquement as deb") === false ) {
									$LeftOuterJoin = ",art_debarquement as deb ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_grand_type_engin as gte") === false ) {
									$LeftOuterJoin = ",art_grand_type_engin as gte ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_agglomeration as agg") === false ) {
									$LeftOuterJoin = ",art_agglomeration as agg ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_unite_peche as upec") === false ) {
									$LeftOuterJoin = ",art_unite_peche as upec ".$LeftOuterJoin;
								}
							}
							break;
						case "NtPart" :
							$labelSelection = "donn&eacute;e(s) NtPt";				
							$listeChampsCom = $listeChampsDeb;
							$ListeTableCom = $ListeTableDeb ;
							$WhereCom = $WhereDeb ;
							if (!($_SESSION['listeRegroup'] == "")) {
								$OrderCom = $OrderDeb."  , afra.ref_espece_id asc ";
								} else {
								$OrderCom = $OrderDeb ;
							}
							$posDEBID = 11 ; //position deb.id - 1 / Pour gestion regroupement
							$posESPID = 18 ; //position afra.ref_espece_id - 1 / Pour gestion regroupement
							$posESPNom = 19 ; //position esp.libelle - 1 / Pour gestion regroupement
							$posStat1 = 16 ; //position afra.poids - 1 / Pour gestion regroupement
							$posStat2 = 17 ; //position afra.nbre_poissons - 1 / Pour gestion regroupement
							$posStat3 = -1 ; // Non utilisé
							if (strpos($listeChampsSel,"deb.art_grand_type_engin_id") === false) {
								$listeChampsSpec = ", deb.poids_total,deb.art_unite_peche_id,afra.id,afra.poids, afra.nbre_poissons, afra.ref_espece_id,esp.libelle, deb.art_grand_type_engin_id";
							} else {
								$listeChampsSpec = ", deb.poids_total,deb.art_unite_peche_id,afra.id,afra.poids, afra.nbre_poissons, afra.ref_espece_id, esp.libelle";
							}
							if (strpos($listeChampsSel,"catt.id") === false) {
								$listeChampsSpec .= ",esp.ref_categorie_trophique_id";
							}
							if (strpos($listeChampsSel,"cate.id") === false) {
								$listeChampsSpec .= ",esp.ref_categorie_ecologique_id";
							}						
							$ListeTableSpec = ", art_fraction as afra,ref_famille as fam"; 
							$WhereSpec = " 	and ".$WhereEsp." afra.art_debarquement_id = deb.id 
											and esp.id = afra.ref_espece_id	
											and fam.id = esp.ref_famille_id and ".$compPoisSQL;					
							$ConstIDunique = "DEB-##-11";
							$valueCount = "deb.id" ; // pour gerer la pagination	
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",art_debarquement as deb,ref_espece as esp,art_grand_type_engin as gte,art_agglomeration as agg,art_unite_peche as upec";
							} else {
								if (strpos($LeftOuterJoin,"art_debarquement as deb") === false ) {
									$LeftOuterJoin = ",art_debarquement as deb ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"ref_espece as esp") === false ) {
									$LeftOuterJoin = ",ref_espece as esp ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_grand_type_engin as gte") === false ) {
									$LeftOuterJoin = ",art_grand_type_engin as gte ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_agglomeration as agg") === false ) {
									$LeftOuterJoin = ",art_agglomeration as agg ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_unite_peche as upec") === false ) {
									$LeftOuterJoin = ",art_unite_peche as upec ".$LeftOuterJoin;
								}
							}
							break;
						case "taillart" :
							$labelSelection = "donn&eacute;e(s) de tailles";	
							$listeChampsCom = $listeChampsDeb;
							$ListeTableCom = $ListeTableDeb ;
							$WhereCom = $WhereDeb ;
							if (!($_SESSION['listeRegroup'] == "")) {
								$OrderCom = $OrderDeb."  , afra.ref_espece_id asc ";
								} else {
								$OrderCom = $OrderDeb ;
							}
							$listeChampsDeb = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom,se.id, deb.art_agglomeration_id, agg.nom, deb.annee, deb.mois, deb.id, deb.date_debarquement";
							$posDEBID = 11 ; //position deb.id - 1 / Pour gestion regroupement
							$posESPID = 19 ; //position afra.ref_espece_id - 1 / Pour gestion regroupement
							$posESPNom = 20 ; //position esp.libelle - 1 / Pour gestion regroupement
							$posStat1 = 16 ; //position afra.poids - 1 / Pour gestion regroupement
							$posStat2 = 17 ; //position afra.nbre_poissons - 1 / Pour gestion regroupement
							$posStat3 = 18 ; //position ames.taille - 1 / Pour gestion regroupement
							if (strpos($listeChampsSel,"deb.art_grand_type_engin_id") === false) {
								$listeChampsSpec = ", deb.poids_total, deb.art_unite_peche_id,afra.id, afra.poids, afra.nbre_poissons, ames.taille, afra.ref_espece_id, esp.libelle, deb.art_grand_type_engin_id ";
							} else {
								$listeChampsSpec = ", deb.poids_total, deb.art_unite_peche_id,afra.id, afra.poids, afra.nbre_poissons, ames.taille, afra.ref_espece_id, esp.libelle ";
							}
							if (strpos($listeChampsSel,"catt.id") === false) {
								$listeChampsSpec .= ",esp.ref_categorie_trophique_id";
							}
							if (strpos($listeChampsSel,"cate.id") === false) {
								$listeChampsSpec .= ",esp.ref_categorie_ecologique_id";
							}
							$ListeTableSpec = ",ref_famille as fam, art_fraction as afra left outer join art_poisson_mesure as ames on ames.art_fraction_id = afra.id"; 
							$WhereSpec = " 	and ".$WhereEsp." afra.art_debarquement_id = deb.id  
											and esp.id = afra.ref_espece_id	
											and fam.id = esp.ref_famille_id and ".$compPoisSQL;						
							$ConstIDunique = "DEB-##-11";
							$valueCount = "deb.id" ; // pour gerer la pagination
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",art_debarquement as deb,ref_espece as esp,art_grand_type_engin as gte,art_agglomeration as agg,art_unite_peche as upec";
							} else {
								if (strpos($LeftOuterJoin,"art_debarquement as deb") === false ) {
									$LeftOuterJoin = ",art_debarquement as deb ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"ref_espece as esp") === false ) {
									$LeftOuterJoin = ",ref_espece as esp ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_grand_type_engin as gte") === false ) {
									$LeftOuterJoin = ",art_grand_type_engin as gte ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_agglomeration as agg") === false ) {
									$LeftOuterJoin = ",art_agglomeration as agg ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_unite_peche as upec") === false ) {
									$LeftOuterJoin = ",art_unite_peche as upec ".$LeftOuterJoin;
								}
							}
							break;
						case "engin" :
							$labelSelection = "donn&eacute;e(s) d'engin";	
							$listeChampsCom = $listeChampsDeb;
							$ListeTableCom = $ListeTableDeb ;
							$WhereCom = $WhereDeb ;
							$OrderCom = $OrderDeb ;	
							$listeChampsSpec = ",deb.art_grand_type_engin_id, aeng.art_type_engin_id,teng.libelle";
							$ListeTableSpec = ", art_engin_peche as aeng, art_type_engin as teng"; 
							$WhereSpec = " and aeng.art_debarquement_id = deb.id and teng.id = aeng.art_type_engin_id";						
							$ConstIDunique = "DEB-##-11";
							$valueCount = "deb.id" ; // pour gerer la pagination
							$builQuery = true;
							if (strpos($LeftOuterJoin,"left outer join") === false ) {
								$LeftOuterJoin = ",art_debarquement as deb,art_grand_type_engin as gte,art_agglomeration as agg,art_unite_peche as upec";
							} else {
								if (strpos($LeftOuterJoin,"art_debarquement as deb") === false ) {
									$LeftOuterJoin = ",art_debarquement as deb ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_grand_type_engin as gte") === false ) {
									$LeftOuterJoin = ",art_grand_type_engin as gte ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_agglomeration as agg") === false ) {
									$LeftOuterJoin = ",art_agglomeration as agg ".$LeftOuterJoin;
								}
								if (strpos($LeftOuterJoin,"art_unite_peche as upec") === false ) {
									$LeftOuterJoin = ",art_unite_peche as upec ".$LeftOuterJoin;
								}
							}
							break;															
						default	:	
							$labelSelection = "p&eacute;riode(s) d'enqu&ecirc;te";
							$SQLfinal = "select * from art_periode_enquete as penq
											where penq.id in (".$SQLPeEnquete.")";
							$SQLcountfinal = "select count(*) from art_periode_enquete as penq
											where penq.id in (".$SQLPeEnquete.")";; // pour gerer la pagination	
							break;
					}
					break;
				// ********** FIN TRAITEMENT PECHE ARTISANALE
				default:
					echo "Erreur pas de peche selectionnee. Ca ne devrait pas arriver....<br/>";
					exit;
			} 
			break;
		// ********** FIN TRAITEMENT EXTRACTION
		// #
		// #####################################################################################
		// STATISTIQUES
		// #####################################################################################		
		case "statistiques" :
			// ********** DEBUT TRAITEMENTDES STATISTIQUES
			// ********** Gestion de l'affichage des colonnes sélectionnées 
			// Le traitement est different des deux premiers cas : on va construire plusieurs requetes a executer a la suite
			//
			$listeChampsSel = "";
			$ListeTableSel = "";
			$WhereSel = "";
			$compSQL = "";
			if ($SQLAgg == "") {
				$WhereAgg = "";
			} else {
				$WhereAgg = "agg.id in (".$SQLAgg.") and";
			}
			if ($SQLPeEnquete == "") {
				$WherePeEnq = "";
			} else {
				$WherePeEnq = "penq.id in (".$SQLPeEnquete.") and ";
			}
			// Grand type engin
			if (!($_SESSION['SQLGTEngin'] == "")) {
				$LabGTE = " - restreint aux grands types engin : ";
				$champSel = explode(",",$_SESSION['SQLGTEngin']);
				$nbrSel = count($champSel)-1;
				$valGTE= "";
				for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
					if ($valGTE == "") {
						$valGTE = "'".$champSel[$cptSel]."'";
					} else {
						$valGTE .= ",'".$champSel[$cptSel]."'";
					}
					$LabGTE .= $champSel[$cptSel]." ";
				}
				$compGTESQL ="gte.id in (".$valGTE.") and ";
			} else {
				$compGTESQL = "";
				$LabGTE = " - toutes les grands types engin ";
			}

			// Cas particulier d'aucun sélection des espèces : 
			// On reconstruit cette liste pour l'ensemble de la sélection car on va en avoir besoin
			// pour les catégories trophiques/ecologiques
			$ajouteTable ="";
			if ($SQLEspeces == "") {
				if (!($compGTESQL == "")) {
					$ajouteTable =",art_grand_type_engin as gte";
			}
			$SQLEsp = "select distinct(asp.ref_espece_id) from ref_pays as py,ref_systeme as sy,ref_secteur as se,art_agglomeration as agg,art_periode_enquete as penq, art_stat_totale as ast,art_stat_sp as asp,ref_espece as esp 
			where ".$WhereSyst." ".$WhereAgg." ".$WhereSect." ".$WherePeEnq."	
				agg.id = penq.art_agglomeration_id and
				ast.art_agglomeration_id = penq.art_agglomeration_id and
				py.id = sy.ref_pays_id and
				sy.id = se.ref_systeme_id and
				se.id = agg.ref_secteur_id  and asp.art_stat_totale_id = ast.id and esp.id = asp.ref_espece_id";
				$SQLEspeces = RecupereEspeces($SQLEsp);
				$_SESSION['SQLEspeces'] = $SQLEspeces; // ca va servir pour la suite..
			}
			// Si malgré tout, toujours pas d'especes dispo, ben tant pis....
			if ($SQLEspeces == "") {
				$WhereEsp = "";
			} else {
				// Enfin on verifie qu'il n'y a pas eu de restriction supplémentaires
				if (!($_SESSION['listeEspeces'] == "")) {
					$TempSQLEspeces = $SQLEspeces;
					$SQLEspeces = "";
					$EspecesSele = explode (",",$_SESSION['listeEspeces']);
					$NumEsp = count($EspecesSele) - 1;
					for ($cptES=0 ; $cptES<=$NumEsp;$cptES++) {
						if (strpos($TempSQLEspeces,$EspecesSele[$cptES]) === false ){
			
						} else {
							// La valeur est disponible, on la met à jour
							if ($SQLEspeces == "" ) {
								$SQLEspeces = "'".$EspecesSele[$cptES]."'";
							} else {
								$SQLEspeces .= ",'".$EspecesSele[$cptES]."'";
							}
						}
					}
				} 
			}
			// On charge les colonnes supplémentaires juste pour l'affichage.
			analyseColonne("statistiques",$typeAction,"ast");
			$toutesColonnes = recupereTouteColonnes("statistiques",$typeStatistiques); // C'est juste pour charger le nom des alias dans la variable de session 
			// Dans le cas des stats par systeme, on n'a pas de liste de période d'enquetes, on la reconstruit.
			// $SQLdateDebut annee/mois
			// $SQLdateFin annee/mois
			if ($WherePeEnq == "") {
				$moisDateFin = substr($SQLdateFin, -2);
				switch ($moisDateFin) {
					case "/1" : $SQLdateFinEnq = $SQLdateFin."/31"; break;
					case "/2" : $SQLdateFinEnq = $SQLdateFin."/28"; break;
					case "/3" : $SQLdateFinEnq = $SQLdateFin."/31"; break;
					case "/4" : $SQLdateFinEnq = $SQLdateFin."/30"; break;
					case "/5" : $SQLdateFinEnq = $SQLdateFin."/31"; break;
					case "/6" : $SQLdateFinEnq = $SQLdateFin."/30"; break;
					case "/7" : $SQLdateFinEnq = $SQLdateFin."/31"; break;
					case "/8" : $SQLdateFinEnq = $SQLdateFin."/31"; break;
					case "/9" : $SQLdateFinEnq = $SQLdateFin."/30"; break;
					case "10" : $SQLdateFinEnq = $SQLdateFin."/31"; break;
					case "11" : $SQLdateFinEnq = $SQLdateFin."/30"; break;
					case "12" : $SQLdateFinEnq = $SQLdateFin."/31"; break;
				}
				// si pas de secteur selectionné, on recupere toutes les agglo du systeme
				if ($SQLSecteur=="") {
					if ($SQLSysteme == "") {
						// Ca ne devrait pas arriver, les stats sont par systeme.
						$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;Erreur, pas de systeme selectionne...<br/>";
					} else {
						$SQLSecteurPenq = "select id from ref_secteur where ref_systeme_id in (".$SQLSysteme.")";
					} 
				} else {
					$SQLSecteurPenq = $SQLSecteur;
				}
				$SQLperEnq = "select id from art_periode_enquete as penq where penq.date_debut >= '".$SQLdateDebut."/01' and penq.date_fin <= '".$SQLdateFinEnq."'
				and penq.art_agglomeration_id in (select id from art_agglomeration where ref_secteur_id in (".$SQLSecteurPenq."))";
				$SQLperEnqResult = pg_query($connectPPEAO,$SQLperEnq);
				$erreurSQL = pg_last_error($connectPPEAO);
		
				if ( !$SQLperEnqResult ) { 
					if ($EcrireLogComp ) {
						WriteCompLog ($logComp, "ERREUR : Erreur query final ".$SQLfinal." (erreur complete = ".$erreurSQL.")",$pasdefichier);
					}
					$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query ".$SQLfinal." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
					$erreurProcess = true;
				} else {
					if (pg_num_rows($SQLperEnqResult) == 0) {
						// Avertissement
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp, "Pas de resultat disponible pour la selection ".$SQLfinal,$pasdefichier);
						}
					} else {
		
						while ($PenqRow = pg_fetch_row($SQLperEnqResult) ) {
							if ($SQLPeEnquete == "") {
								$SQLPeEnquete = $PenqRow[0];
							} else {
								$SQLPeEnquete .= ",".$PenqRow[0];
							}
						}
					}
				}
				$WherePeEnq = "penq.id in (".$SQLPeEnquete.") and ";
				pg_free_result($SQLperEnqResult);
			}
			
			// ********** DEBUT STATISTIQUES PAR AGGLOMERATION
			// ********** CONSTRUCTION DES SQL DEFINITIFS PAR TYPE DE STATISTIQUES CHOISIS
			$listeChampsCom = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom ,agg.id ,agg.nom ,penq.annee ,penq.mois";
			$ListeTableCom = "ref_pays as py,ref_systeme as sy,ref_secteur as se,art_agglomeration as agg";
			$WhereCom = 	$WhereSyst." ".$WhereAgg." ".$WhereSect." ".$WherePeEnq."	
							agg.id = penq.art_agglomeration_id and
							ast.mois = penq.mois and
							ast.annee = penq.annee and 
							ast.art_agglomeration_id = penq.art_agglomeration_id and
							py.id = sy.ref_pays_id and
							sy.id = se.ref_systeme_id and
							se.id = agg.ref_secteur_id " ;		
			$posSystemeID = 2 ; //position systeme ID pour calcul stats generales
			$OrderCom = "order by py.id asc,sy.id asc,se.id asc,penq.annee asc,penq.mois asc";
			switch ($typeAction) {
				case "stats" :
					// On construit les differentes requetes a executer a la suite:
					// Les variables pour l'affichage a l'ecran :
					$labelSelection = "Statistiques totales";	
					$listeChampsSpec = ",ast.pue,ast.fm,ast.cap,ast.id,se.id";
					
					$ListeTableSpec = ",art_periode_enquete as penq, art_stat_totale as ast"; 
					$WhereSpec = " and ast.art_agglomeration_id = penq.art_agglomeration_id";						
					$ConstIDunique = "AST-##-13";
					$valueCount = "ast.id" ; // pour gerer la pagination
					$builQuery = true;
					$posSecteurID = 14 ; //position systeme ID pour calcul stats generales
					// ******************
					// **** art_stat_totale
					$listeChampsSpecast = ",ast.pue,ast.fm,ast.cap,ast.id,se.id";
					$ListeTableSpecast = ",art_periode_enquete as penq, art_stat_totale as ast"; 
					$WhereSpecast = " and ast.art_agglomeration_id = penq.art_agglomeration_id";
					$ConstIDuniqueast = "AST-##-13";
					$posSecteurIDast = 14 ; //position systeme ID pour calcul stats generales
					$posGTEIDast = -1 ; //pas de GTE
					$posStat1ast = 10 ; //position stat 1 a cumuler  - 1 / Pour stats generales
					$posStat2ast = 11 ; //position stat 2 a cumuler  - 1 / Pour stats generales
					$posStat3ast = 12 ; //position stat 3 a cumuler  - 1 / Pour stats generales
					// ******************
					// **** art_stat_sp
					$listeChampsSpecasp = ",ast.pue,ast.fm,ast.cap,asp.ref_espece_id,esp.libelle ,asp.pue_sp,asp.cap_sp ,asp.id ,ast.id,se.id";
					$ListeTableSpecasp = ",art_periode_enquete as penq, art_stat_totale as ast,art_stat_sp as asp,ref_espece as esp"; 
					$WhereSpecasp = "	and asp.art_stat_totale_id = ast.id and esp.id = asp.ref_espece_id";
					if (!($SQLEspeces == "")) {
						$WhereSpecasp .= " and asp.ref_espece_id in (".$SQLEspeces.") ";
					}
					$OrderComasp = ",asp.id asc";
					$ConstIDuniqueasp = "AST-##-18";
					$posSecteurIDasp = 19 ; //position systeme ID pour calcul stats generales
					$posGTEIDasp = -1 ; //pas de GTE
					// Gestion des positionnements pour les regroupements
					$posDEBIDasp = 18 ; //position ast.id - 1 / Pour gestion regroupement
					$posESPIDasp = 13 ; //position afra.ref_espece_id - 1 / Pour gestion regroupement
					$posESPNomasp = 14 ; //position esp.libelle - 1 / Pour gestion regroupement
					$posStat1asp = 15 ; //position stat 1 a cumuler  - 1 / Pour gestion regroupement
					$posStat2asp = 16 ; //position stat 2 a cumuler  - 1 / Pour gestion regroupement
					$posStat3asp = -1 ; //position stat 3 a cumuler  - 1 / Pour gestion regroupement
					// ******************
					// **** art_taille_sp
					$listeChampsSpecats = ",asp.ref_espece_id,esp.libelle, asp.pue_sp,asp.cap_sp,ats.li,ats.xi,asp.id,ast.id,ats.id,se.id";
					$ListeTableSpecats = ",art_periode_enquete as penq, art_stat_totale as ast,art_stat_sp as asp,art_taille_sp as ats,ref_espece as esp"; 
					$WhereSpecats = " 	and ats.art_stat_sp_id = asp.id and
											asp.art_stat_totale_id = ast.id and esp.id = asp.ref_espece_id";
					if (!($SQLEspeces == "")) {
						$WhereSpecats .= " and  asp.ref_espece_id in (".$SQLEspeces.") ";
					}
					$ConstIDuniqueats = "AST-##-17";
					$posSecteurIDats = 19 ; //position systeme ID pour calcul stats generales
					$posGTEIDats = -1 ; //pas de GTE
					// Gestion des positionnements pour les regroupements
					$posDEBIDats = 17 ; //position ast.id - 1 / Pour gestion regroupement
					$posESPIDats = 10 ; //position afra.ref_espece_id - 1 / Pour gestion regroupement
					$posESPNomats = 11 ; //position esp.libelle - 1 / Pour gestion regroupement
					$posStat1ats = 14 ; //position stat 1 a cumuler  - 1 / Pour gestion regroupement
					$posStat2ats = 15 ; //position stat 2 a cumuler  - 1 / Pour gestion regroupement
					$posStat3ats = -1 ; //position stat 3 a cumuler  - 1 / Pour gestion regroupement
					// ******************
					// **** art_stat_gt	attgt
					$listeChampsSpecasgt = ", asgt.art_grand_type_engin_id,gte.libelle,asgt.pue_gt,asgt.fm_gt,asgt.cap_gt, asgt.id,ast.id,se.id";
					$ListeTableSpecasgt = ",art_periode_enquete as penq, art_stat_gt as asgt, art_stat_totale as ast,art_grand_type_engin as gte"; 
					$WhereSpecasgt = "	and asgt.art_stat_totale_id = ast.id 
											and gte.id = 	asgt.art_grand_type_engin_id";
					$ConstIDuniqueasgt = "AST-##-16";
					$posSecteurIDasgt = 17 ; //position systeme ID pour calcul stats generales
					$posGTEIDasgt = 10 ; //position systeme ID pour calcul stats generales
					$posStat1asgt = 13 ; //position stat 1 a cumuler  - 1 / Pour stats generales
					$posStat2asgt = 14 ; //position stat 2 a cumuler  - 1 / Pour stats generales
					$posStat3asgt = -1 ; //position stat 3 a cumuler  - 1 / Pour stats generales
					// ******************
					// **** art_stat_gt_sp
					$listeChampsSpecattgt = ",asgt.art_grand_type_engin_id,gte.libelle,attgt.ref_espece_id, esp.libelle,attgt.pue_gt_sp,attgt.cap_gt_sp,attgt.id, asgt.id, ast.id,se.id";
					$ListeTableSpecattgt = ",art_periode_enquete as penq, art_stat_gt_sp as attgt,art_stat_gt as asgt, art_stat_totale as ast,art_grand_type_engin as gte,ref_espece as esp"; 
					$WhereSpecattgt = "	and attgt.art_stat_gt_id = asgt.id  
											and asgt.art_stat_totale_id = ast.id 
											and gte.id = asgt.art_grand_type_engin_id
											and esp.id = attgt.ref_espece_id";
					if (!($SQLEspeces == "")) {
						$WhereSpecattgt .= " and  attgt.ref_espece_id in (".$SQLEspeces.")";
					}
					$ConstIDuniqueattgt = "AST-##-16";
					$posSecteurIDattgt = 19 ; //position systeme ID pour calcul stats generales
					$posGTEIDattgt = 10 ; //position systeme ID pour calcul stats generales
					// Gestion des positionnements pour les regroupements
					$posDEBIDattgt = 16 ; //position ast.id - 1 / Pour gestion regroupement
					$posESPIDattgt = 12 ; //position afra.ref_espece_id - 1 / Pour gestion regroupement
					$posESPNomattgt = 13 ; //position esp.libelle - 1 / Pour gestion regroupement
					$posStat1attgt = 14 ; //position stat 1 a cumuler  - 1 / Pour gestion regroupement
					$posStat2attgt = 15 ; //position stat 2 a cumuler  - 1 / Pour gestion regroupement
					$posStat3attgt = -1;
					// ******************
					//art_stat_gt_sp
					$listeChampsSpecatgts = ", asgt.art_grand_type_engin_id,gte.libelle,attgt.ref_espece_id, esp.libelle, attgt.pue_gt_sp, attgt.cap_gt_sp, atgts.li, atgts.xi,atgts.id, attgt.id, asgt.id, ast.id,se.id";
					$ListeTableSpecatgts = ",art_periode_enquete as penq, art_taille_gt_sp as atgts, art_stat_gt_sp as attgt,art_stat_gt as asgt, art_stat_totale as ast,art_grand_type_engin as gte,ref_espece as esp"; 
					$WhereSpecatgts = "	and atgts.art_stat_gt_sp_id = attgt.id  
											and attgt.art_stat_gt_id = asgt.id  
											and asgt.art_stat_totale_id = ast.id 
											and gte.id = asgt.art_grand_type_engin_id
											and esp.id = attgt.ref_espece_id";
					if (!($SQLEspeces == "")) {
						$WhereSpecatgts .= " and  attgt.ref_espece_id in (".$SQLEspeces.")";
					}
					$ConstIDuniqueatgts = "AST-##-21";
					$posSecteurIDatgts = 22 ; //position systeme ID pour calcul stats generales
					$posGTEIDatgts = 10 ; //position systeme ID pour calcul stats generales
					// Gestion des positionnements pour les regroupements
					$posDEBIDatgts = 21 ; //position ast.id - 1 / Pour gestion regroupement
					$posESPIDatgts = 12 ; //position attgt.ref_espece_id - 1 / Pour gestion regroupement
					$posESPNomatgts = 13 ; //position esp.libelle - 1 / Pour gestion regroupement
					$posStat1gatgts = 16 ; //position stat 1 a cumuler  - 1 / Pour gestion regroupement
					$posStat2atgts = 17 ; //position stat 2 a cumuler  - 1 / Pour gestion regroupement
					$posStat3atgts = -1 ; //position stat 3 a cumuler  - 1 / Pour gestion regroupement
					break;
				default	:	
					$labelSelection = "p&eacute;riode(s) d'enqu&ecirc;te";
					$SQLfinal = "select * from art_periode_enquete as penq
									where penq.id in (".$SQLPeEnquete.")";
					$SQLcountfinal = "select count(*) from art_periode_enquete as penq
							where penq.id in (".$SQLPeEnquete.")";; // pour gerer la pagination	
				break;
			}
			break;
		// #
		// ********** FIN TRAITEMENT STATISTIQUES
		// #
		default:
			echo "Erreur pas de typeSelection disponible. Ca ne devrait pas arriver....<br/>";
			exit;
		
	} // fin du switch ($typeSelection) 

	// *
	// *********************************************************************************
	// EXECUTION DE LA REQUETE APRES SA CONSTRUCTION
	// *********************************************************************************
	//echo $SQLfinal."<br/>";
	//echo $SQLcountfinal."<br/>";
	if ($debugAff) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "fin traitement donnees artisanales - avant execution requete :".$debugTimer."<br/>";
	}	
	// On construit (ou non) la requete finale.
	// Elle peut avoir déjà été construite précédement, notament dans les cas par defaut
	if ($EcrireLogComp && $debugLog) {
		WriteCompLog ($logComp, "DEBUG :  synthese champs = ".$listeChampsSel." table = ".$ListeTableSel." where = ".$WhereSel,$pasdefichier);
	}
	if ($builQuery) {
		$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
		$listeTable = $ListeTableCom.$ListeTableSpec.$ListeTableSel; // L'ordre est important pour les join
		if ($WhereSel == "") {
			$WhereTotal = $WhereCom.$WhereSpec;
		} else {
			$WhereTotal = $WhereCom.$WhereSpec." and ".$WhereSel;
		}
		$SQLfinal = "select ".$listeChamps." from ".$listeTable." ".$LeftOuterJoin." where ".$WhereTotal ." ".$OrderCom;
		//$SQLcountfinal = "select count(".$valueCount.") from ".$ListeTableCom.$ListeTableSpec." ".$LeftOuterJoin." where ".$WhereCom.$WhereSpec;
		$SQLcountfinal = "select count(".$valueCount.") from ".$listeTable." ".$LeftOuterJoin." where ".$WhereTotal ;
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "INFO SQL en cours :".$SQLfinal,$pasdefichier);
		}
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG :  select countfinal = ".$SQLcountfinal,$pasdefichier);
	}
	}
	// Gestion des regroupements / stats generales
	// A ce niveau, pour gérer les regroupements, il faut passer par une étape intermédiaire d'agrégation
	// On exécute la requete, on effectue les groupements et enfin on créé des entrées dans la table temporaire temp_extraction
	// on gère aussi a ce niveau la creation des stats générales, puisqu'il s'agit du meme type d'agregation de données, mais pas sur les mêmes ruptures.
	// Le regroupement se gere sur la rupture Id stat/Id espece, alors que la rupture pour les stats générales sera secteur (ou systeme) / id Stat (ou especes)
	// Dans le cas ou il n'y a pas de regroupement pour les statistiques generales, on en cree un bidon.
	$creationRegBidon = false;
	if (!($_SESSION['listeRegroup'] == "") || ($typeStatistiques == "generales" && $typeAction =="stats")) {
		if ($typeSelection == "statistiques") {
			if ($typeStatistiques == "generales") {
				$listeTableStat = "ast,asp,ats,asgt,attgt,atgts"; // Toutes les tables
				if ($_SESSION['listeRegroup'] == "") {
					// Pas de regroupement en cours, il faut creer un regroupement par espece
					$SQLReg = "select id,libelle from ref_espece where id in (".$SQLEspeces.") order by libelle";	
					$SQLRegResult = pg_query($connectPPEAO,$SQLReg);
					$erreurSQL = pg_last_error($connectPPEAO);
					if ( !$SQLRegResult ) {
						echo "erreur execution SQL pour ".$SQLReg." erreur complete = ".$erreurSQL."<br/>";
					//erreur
					} else { 
						if (pg_num_rows($SQLRegResult) == 0) {
							// Erreur
							echo "pas d'especes trouvees dont le id est ".$SQLEspeces."<br/>" ;
						} else { 
							$creationRegBidon = true;
							while ($RegRow = pg_fetch_row($SQLRegResult) ) {
								// On crée un regroupement par espece. On récupère le libellé
								if (!($_SESSION['listeRegroup'] == "")) {
									$rangNvReg = count($_SESSION['listeRegroup']) + 1;
								} else {
									$rangNvReg = 1;
								}
								$_SESSION['listeRegroup'][$rangNvReg][1]=$RegRow[0]."&#&".$RegRow[1];
								$_SESSION['listeRegroup'][$rangNvReg][2]=$RegRow[0]."&#&".$RegRow[1];
							}
						}
						pg_free_result($SQLRegResult);
					}

				}
				$SQLfinal = "select * from temp_extraction where key4 = 'ast' order by key1 asc,key2 asc,key3 asc";
				$SQLcountfinal = "select count(*) from temp_extraction where key4 = 'ast'";
				$ConstIDunique = "AST-##-1";

			} else {
				// On est forcement dans le cas des stats par agglo avec un regroupement
				$listeTableStat = "asp,ats,attgt,atgts"; // que celles qui ont un regroupement
			}
			$tableStat = explode(",",$listeTableStat);
			$nbrTS = count($tableStat)-1;
			// On boucle sur toutes les tables pour extraire les donnees
			for ($cptTS = 0;$cptTS <= $nbrTS;$cptTS++) {
				$nomValLChampsSpec = "listeChampsSpec".$tableStat[$cptTS];
				$nomValLTableSpec = "ListeTableSpec".$tableStat[$cptTS];
				$nomValWhereSpec = "WhereSpec".$tableStat[$cptTS];
				// Construction du SQL pour chacun des tables
				// Analyse de la liste des colonnes venant des sélections précédentes, ajout de ces colonnes au fichier
				$AjoutWhere = "";
				$listeChampsSel="";
				$listeChampsSel="";
				analyseColonne("statistiques",$typeAction,$tableStat[$cptTS]);
				$WhereSel = $AjoutWhere;
				$listeChampsReg = $listeChampsCom.${$nomValLChampsSpec}.$listeChampsSel;
				$listeTableReg = $ListeTableCom.${$nomValLTableSpec}.$ListeTableSel; 
				if ($WhereSel == "") {
					$WhereTotalReg = $WhereCom.${$nomValWhereSpec};
				} else {
					$WhereTotalReg = $WhereCom.${$nomValWhereSpec}." and ".$WhereSel;
				}
				$SQLfinalreg = "select ".$listeChampsReg." from ".$listeTableReg." where ".$WhereTotalReg ." ".$OrderCom;
				//echo "<br/><b>requete [".$tableStat[$cptTS]."] </b> = ".$SQLfinalreg."<br/>";
				$posDEBIDm = posDEBID.$tableStat[$cptTS];
				$posESPIDm = posESPID.$tableStat[$cptTS];
				$posESPNomm = posESPNom.$tableStat[$cptTS];
				$posStat1m = posStat1.$tableStat[$cptTS];
				$posStat2m = posStat2.$tableStat[$cptTS];
				$posStat3m = posStat3.$tableStat[$cptTS];
				// Variables supplémentaires pour les stats générales
				$posSecteurIDm = posSecteurID.$tableStat[$cptTS];
				$posGTEIDm = posGTEID.$tableStat[$cptTS];
				//echo $tableStat[$cptTS]."-".$$posDEBIDm." - ".$$posESPIDm." - ".$$posESPNomm." - ".$$posStat1m." - ".$$posStat2m." - ".$$posStat3m."<br/>";
				creeRegroupement($SQLfinalreg,${$posDEBIDm} ,${$posESPIDm},${$posESPNomm},${$posStat1m},${$posStat2m},${$posStat3m},$typeSelection,$tableStat[$cptTS],$cptTS,$posSystemeID,${$posSecteurIDm},${$posGTEIDm},$creationRegBidon,$typeStatistiques);				
			}
		} else {
			creeRegroupement($SQLfinal,$posDEBID ,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$typeSelection,"",0,-1,-1,-1,false,"");
			$SQLfinal = "select * from temp_extraction order by key1 asc,key2 asc,key3 asc";
			$SQLcountfinal = "select count(*) from temp_extraction ";
			if ($typeSelection == "extraction") {
				$ConstIDunique = "DEB-##-1";			
			}else {
				$ConstIDunique = "AST-##-1";
			}
			$valueCount = "temp_extraction.id" ; // pour gerer la pagination	

		}
	} // fin du if (!($_SESSION['listeRegroup'] == ""))
	// **** fin gestion des regroupements
	//**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// Debut des traitements d'affichage à l'écran et extraction fichiers
	//**++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// ********************************
	// Gestion d'affichage a l'ecran
	// ********************************	
	// Gestion de la pagination
	$countTotal=0; // Contient le resultat total de la requete
	//echo $SQLfinal."<br/>";
	//echo $SQLcountfinal."<br/>";
	// On recupere le nombre total de resultat.
	// On doit executer la requete
	$SQLcountfinalResult = pg_query($connectPPEAO,$SQLcountfinal);
	$erreurSQL = pg_last_error($connectPPEAO);
	$cpt1 = 0;
	if ( !$SQLcountfinalResult ) { 
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "WARNING : Erreur pagination pour requete ".$SQLcountfinal." (erreur complete = ".$erreurSQL.")",$pasdefichier);
		}
		$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Erreur pagination<br/>";
	} else {
		if (pg_num_rows($SQLcountfinalResult) == 0) {
			// Avertissement
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "WARNING : pagination Pas de resultat disponible pour la selection ".$SQLcountfinal,$pasdefichier);
			}
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Erreur pagination<br/>";
		} else {
			$countRow=pg_fetch_row($SQLcountfinalResult);
			$countTotal=$countRow[0];
		}	
	}
	pg_free_result($SQLcountfinalResult); 
	if ($debugAff) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "apres  requete SQLcountfinal :".$debugTimer."<br/>";
	}
	// On gère la pagination
	// on prend en compte la pagination
	// Déclaration des variables  
	$rowsPerPage = 15; // nombre d'entrées à afficher par page (entries per page) 
	$countPages = ceil($countTotal/$rowsPerPage); // calcul du nombre de pages $countPages (on arrondit à l'entier supérieur avec la fonction ceil() ) 
 
	// Récupération du numéro de la page courante depuis l'URL avec la méthode GET  
	if(!isset($_GET['page']) || !is_numeric($_GET['page']) ) // si $_GET['page'] n'existe pas OU $_GET['page'] n'est pas un nombre (petite sécurité supplémentaire) 
		$currentPage = 1; // la page courante devient 1 
	else { 
		$currentPage = intval($_GET['page']); // stockage de la valeur entière uniquement 
		if ($currentPage < 1) $currentPage=1; // cas où le numéro de page est inférieure 1 : on affecte 1 à la page courante 
		elseif ($currentPage > $countPages) $currentPage=$countPages; //cas où le numéro de page est supérieur au nombre total de pages : on affecte le numéro de la dernière page à la page courante 
		else $currentPage=$currentPage; // sinon la page courante est bien celle indiquée dans l'URL 
	} 
 	// $start est la valeur de départ du LIMIT dans notre requête SQL (est fonction de la page courante)  
	$startRow = ($currentPage * $rowsPerPage - $rowsPerPage);
	// on construit la requête SQL pour obtenir les valeurs de la table à afficher si il y en a
	if ($countTotal!=0) {
		// Pour pouvoir gérer la pagination, on doit séparer la requete d'affichage de la requete de creation du fichier.
		// On ne creera le fichier qu'une seule fois!
		$SQLfinalFichier = $SQLfinal; // On stocke la requete pour le fichier
		// ********************************
		// Gestion de l'affichage ecran
		// ********************************
		$SQLfinal .= " LIMIT ".$rowsPerPage." OFFSET ".$startRow;
		// Execution de la requete
		$SQLfinalResult = pg_query($connectPPEAO,$SQLfinal);
		$erreurSQL = pg_last_error($connectPPEAO);
		$cpt1 = 0;
		if ( !$SQLfinalResult ) { 
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "ERREUR : Erreur query final ".$SQLfinal." (erreur complete = ".$erreurSQL.")",$pasdefichier);
			}
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query ".$SQLfinal." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
			$erreurProcess = true;
		} else {
			if (pg_num_rows($SQLfinalResult) == 0) {
				// Avertissement
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "Pas de resultat disponible pour la selection ".$SQLfinal,$pasdefichier);
				}
				$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Pas de resultat disponible pour la sélection<br/>";
			} else {
				// Si on ajoute un identifiant unique en debut de ligne, on l'indique dans la liste des champs.
				if (!($ConstIDunique =="")) {
					$listeChamps ="id.UNIQUE,".$listeChamps;
				}
				if ($typeAction == "biologie") {
					// On ajoute le libelle pour le coefficient
					$listeChamps .=",Nombre_individus_mesures,Coeff_extrapolation";
				}
				if ($typeStatistiques == "generales") {
					$listeChamps .=",Effort_total";
				}
				// On remplace les noms des alias par le nom des tables...
				$listeChamps = remplaceAlias($listeChamps);
				// On commence le formatage sous forme de table/
				$libelleAction = recupereLibelleFiliere($typeAction);
				if ($typeSelection == "statistiques") {
					$resultatLecture .="<br/><span class=\"titreAff\">Liste des résultats (stats par ".$typeStatistiques.") </span>";
				} else {
					$resultatLecture .="<br/><span class=\"titreAff\">Liste des résultats (".$libelleAction.") </span>";	
				}
				$resultatLecture .="<table id=\"affresultat\" ><tr class=\"affresultattitre\"><td>";
				// Gestion des regroupements a l'affichage. Attention, non valable pour les statistiques vu qu'on affiche toujours 
				// art_stat_totale qui ne peut pas avoir de regroupement
				if ($debugAff) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "apres  requete SQLfinal - avant regroupement :".$debugTimer."<br/>";
				}
				if (!($_SESSION['listeRegroup'] == "") && (!($typeSelection == "statistiques"))) {
					// On modifie le label pour les especes (nom et cid)
					$tabTitre = explode(",",$listeChamps);
					$listeChamps = "";
					$NbTitre = count($tabTitre);
					for ($cptTitre = 0;$cptTitre < $NbTitre;$cptTitre++) {
						$ValAAjouter = "";
						$Position = intval($cptTitre)-1;// On prend en compte que la premiere valeur de la ligne est l'id unique rajouté
						// On remplace la valeur du titre pour le code et le nom espece par respectivement le code et le nom du regroupement
						switch ($Position) {
							case $posESPID:
								$ValAAjouter = "code Regroupement";
								break;
							case $posESPNom:
								$ValAAjouter = "nom Regroupement";
								break;	
							default: 
								$ValAAjouter = $tabTitre[$cptTitre];
								break;
						}
						// On reconstruit la ligne contenant tous les titres
						if ($listeChamps == "") {
							$listeChamps = $ValAAjouter;
						} else {
							$listeChamps .= ",".$ValAAjouter;
						}
					}
				}
				if ($debugAff) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "apres regroupement :".$debugTimer."<br/>";
				}
				$resultatLecture .= str_replace(","," </td><td> ",$listeChamps);
				$resultatLecture .="</td></tr>";
				$cptNbRow = 0;
				while ($finalRow = pg_fetch_row($SQLfinalResult) ) {
					if ( $cptNbRow&1 ) {$rowStyle='edit_row_odd';} else {$rowStyle='edit_row_even';}
					$resultatLecture .="<tr class=".$rowStyle.">";
					// Construction de la liste des résultat
					// Tout d'abord, construction de l'ID unique
					// Ex $ConstIDunique = "DEB-##-11";
					// On garde le prefixe DEB et on extrait l'index du champ a recuperer de la ligne du resultat de la requete. ici 11
					$IDunique = "";
					if (!($ConstIDunique =="")) {
						$Locprefixe = substr($ConstIDunique,0,3); // Attention, pour des raisons de simplicité, le sufffixe n'est que sur 3 caractères.
						$locIndex = substr(strrchr($ConstIDunique, "-##-"),1);
						//echo $Locprefixe." - ".$locIndex. " - ".strrchr($ConstIDunique, "-##-");
						$IDunique = $Locprefixe.$finalRow[$locIndex];
						$resultatLecture .= "<td>".$IDunique."</td>";
					}
					if ( (!($_SESSION['listeRegroup'] == "") && (!($typeSelection == "statistiques"))) || ($typeSelection == "statistiques" && $typeStatistiques == "generales") ) {
						// Gestion des regroupements
						// On doit récupérer la liste dans le champ valeur_ligne de la table temp_extraction
						// et construire la ligne de resultat avec
						//echo"gestion regroupement - statistiques generales<br/>";
						$ligne_resultat = $finalRow[8];
						$tabResultat = explode("&#&",$ligne_resultat);
						$NbResultat = count($tabResultat);
						for ($cptResult = 1;$cptResult < $NbResultat;$cptResult++) {
							$resultatLecture .= "<td>".$tabResultat[$cptResult]."</td>";
						}
					} else {
						// Le traitement normal
						switch ($typeAction) {
							case "biologie" :
								// On doit calculer un coefficient d'extrapolation 
								// On execute une requete supplémentaire pour recuperer le nombre d'individu dans exp_biologie pour la fraction et l'espece considerée
								// On recupere le nombre de poissons reellement mesures pour une fraction donnée (qui elle meme correspond à 
								// une seule espece.
								// On recupere le nombre total d'individu de la fraction
								$totalIndividus = $finalRow[19];
								// On compte les enregs dans biologie (individus effectivement analyse) pour cette fraction
								$SQLcomplement = "Select count(id) from exp_biologie where exp_fraction_id =  ".$finalRow[18] ;
								$SQLcomplementResult = pg_query($connectPPEAO,$SQLcomplement);
								$erreurSQL = pg_last_error($connectPPEAO);
								if ( !$SQLcomplementResult ) { 
									if ($EcrireLogComp ) {
										WriteCompLog ($logComp, "ERREUR : Erreur query complementaire biologie ".$SQLcomplement." (erreur complete = ".$erreurSQL.")",$pasdefichier);
									}							
								} else {
									$RowComplement = pg_fetch_row($SQLcomplementResult); 
									$totalBio = $RowComplement[0];
									pg_free_result($SQLcomplementResult);
								}
								// Calcul du coefficient = nombre de poisson peches / nombre de poissons mesures
								$coefficient =floatval( intval($totalIndividus) / intval($totalBio));	
								$coefficient = round($coefficient,2);
								$nbrRow = count($finalRow)-1;
								// Transcription du resultat de la requete globale pour un affichage écran et un export sous forme de fichier
								for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
									$resultatLecture .= "<td>".$finalRow[$cptRow]."</td>";
								}
								// Ajout du coefficient tout a la fin du fichier
								$resultatLecture .= "<td>".$totalBio."</td><td>".$coefficient."</td>";
								break;	
							default	:
								$nbrRow = count($finalRow)-1;
								// Transcription du resultat de la requete globale pour un affichage écran et un export sous forme de fichier
								for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
									$resultatLecture .= "<td>".$finalRow[$cptRow]."</td>";
								}	
								break;
						}
					}
					$resultatLecture .="</tr>";
					$cptNbRow ++;
				}//fin du while
				if ($debugAff) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "apres creation de l'affichage a l ecran:".$debugTimer."<br/>";
				}				
				$resultatLecture .="</table>";		
			}			
			pg_free_result($SQLfinalResult);
		} // fin du !$SQLfinalResult
		// ********************************
		// Fin gestion affichage ecran
		// ********************************
		// ********************************
		// Gestion de creation du fichier
		// ********************************
		if ($debugAff) {
			$debugTimer = number_format(timer()-$start_while,4);
			echo "avant export fichier:".$debugTimer."<br/>";
		}
		if ($exportFichier && (!($fichierDejaCree))) {
			// Creation du fichier d'apres le SQL
			if ($typeSelection == "statistiques") {

				$listeTableStat = "ast,asp,ats,asgt,attgt,atgts";
				$tableStat = explode(",",$listeTableStat);
				$nbrTS = count($tableStat)-1;
				// On boucle sur toutes les tables pour extraire les donnees
				for ($cptTS = 0;$cptTS <= $nbrTS;$cptTS++) {
					$nomValLChampsSpec = "listeChampsSpec".$tableStat[$cptTS];
					$nomValLTableSpec = "ListeTableSpec".$tableStat[$cptTS];
					$nomValWhereSpec = "WhereSpec".$tableStat[$cptTS];
					//echo $nomValWhereSpec. " - ".${$nomValWhereSpec}."<br/>";
					// Construction du SQL pour chacun des tables
					// Construction du SQL pour chacun des tables
					// Analyse de la liste des colonnes venant des sélections précédentes, ajout de ces colonnes au fichier
					$AjoutWhere = "";
					$listeChampsSel="";
					$listeChampsSel="";
					analyseColonne("statistiques",$typeAction,$tableStat[$cptTS]);
					$WhereSel = $AjoutWhere;
					$listeChamps = $listeChampsCom.${$nomValLChampsSpec}.$listeChampsSel;
					$listeTable = $ListeTableCom.${$nomValLTableSpec}.$ListeTableSel; // L'ordre est important pour les join
					if ($WhereSel == "") {
						$WhereTotal = $WhereCom.${$nomValWhereSpec};
					} else {
						$WhereTotal = $WhereCom.${$nomValWhereSpec}." and ".$WhereSel;
					}
					$SQLfinal = "select ".$listeChamps." from ".$listeTable." where ".$WhereTotal ." ".$OrderCom;					
					//echo "<b>".$tableStat[$cptTS]."</b> ". $SQLfinal."<br/>";
					// Creation du fichier par stat.
					$ficSuffixe = getSuffixeFicStat($tableStat[$cptTS]);
					$nomFicExport = $dirLog."/".date('y\-m\-d')."_".$ficSuffixe.".txt";
					//echo $nomFicExport."<br/>";
					// On ne cree le fichier que si il n'a pas deja ete rempli !
					if (!($fichierDejaCree)) {
						$ExpCompStat = fopen($nomFicExport , "w+");
						if (! $ExpCompStat ) {
							$resultatLecture .= " erreur de cr&eacute;ation du fichier export ".$nomFicExpLien;
							exit;		
						}
					}
					// Deux cas: si stat par agglo, on fait la difference regroupement / pas regroupement, si stat generales on lit systemeatiquement la table temp
					if ($typeStatistiques == "generales") {
						$SQLfinal = "select * from temp_extraction where key4 = '".$tableStat[$cptTS]."' order by key1 asc,key2 asc,key3 asc";
						$SQLcountfinal = "select count(*) from temp_extraction ";
						$ConstIDunique = "AST-##-1";
						$listeChamps ="id.UNIQUE,".$listeChamps;
						creeFichier($SQLfinal,$listeChamps,$typeAction,$ConstIDunique,$ExpCompStat,false);
					} else {
						$listeTableStatSp = "asp,ats,attgt,atgts";
						if ( strpos($listeTableStatSp,$tableStat[$cptTS]) === false) {
							$ConstIDuniqueStat = "ConstIDunique".$tableStat[$cptTS];
							$listeChamps ="id.UNIQUE,".$listeChamps;
							creeFichier($SQLfinal,$listeChamps,$typeAction,${$ConstIDuniqueStat},$ExpCompStat,true);
						} else {
							if (!($_SESSION['listeRegroup'] == "")) {
								$SQLfinal = "select * from temp_extraction where key4 = '".$tableStat[$cptTS]."' order by key1 asc,key2 asc,key3 asc";
								$SQLcountfinal = "select count(*) from temp_extraction ";
								$ConstIDunique = "AST-##-1";
								$listeChamps ="id.UNIQUE,".$listeChamps;
								creeFichier($SQLfinal,$listeChamps,$typeAction,$ConstIDunique,$ExpCompStat,false);
							} else {
								$ConstIDuniqueStat = "ConstIDunique".$tableStat[$cptTS];
								$listeChamps ="id.UNIQUE,".$listeChamps;
								creeFichier($SQLfinal,$listeChamps,$typeAction,${$ConstIDuniqueStat},$ExpCompStat,false);
							}
						}
					}

				}
			} else {
				creeFichier($SQLfinalFichier,$listeChamps,$typeAction,$ConstIDunique,$ExpComp,false);
			}
			if ($debugAff) {
				$debugTimer = number_format(timer()-$start_while,4);
				echo "apres creation fichier:".$debugTimer."<br/>";
			}
			// Export des selections et regroupements dans des fichiers separes
			$SelectionPourFic = str_replace("<br/>","\n",$SelectionPourFic);
			$SelectionPourFic = str_replace("<b>","",$SelectionPourFic);
			$SelectionPourFic = str_replace("</b>","",$SelectionPourFic);
			$SelectionPourFic = str_replace("&eacute;","é",$SelectionPourFic);
			$SelectionPourFic = str_replace("&ecirc;","ê",$SelectionPourFic);
			$SelectionPourFic = str_replace("&egrave;","è",$SelectionPourFic);
			$SelectionPourFic = str_replace("&#x27;","'",$SelectionPourFic);
			//$SelectionPourFic = "test";
			if (! fwrite($ExpCompSel,$SelectionPourFic) ) {
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "ERREUR : erreur ecriture dans fichier export selection : ".$SelectionPourFic,$pasdefichier);
				} else {
					$resultatLecture .= "erreur ecriture dans fichier export selection" ;
				}
				return "erreur ecriture fichier selection <br/>";
			} else {
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "INFO : selection ecrite dans le fichier [date]_".$typeSelection."_".$typeAction."-Selection.txt",$pasdefichier);
				}
			}
			$RegroupPourFic= "";
			if (!($_SESSION['listeRegroup'] == "" && !$creationRegBidon)) {
				// On cree la liste
				$NbReg = count($_SESSION['listeRegroup']);
				for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
					$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
					$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
					$RegroupPourFic .="Le regroupement ".$infoReg[1]." (".$infoReg[0].") contient les especes suivantes :\n";
					for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
						$infoEsp = explode("&#&",$_SESSION['listeRegroup'][$cptR][$cptR2]);
						$RegroupPourFic .="\t - ".$infoEsp[0]." - ".$infoEsp[1]."\n";
					}
					
				}
				$RegroupPourFic .="Toutes les autres especes se retrouvent dans le regroupement DIV. \n";
				if (! fwrite($ExpCompReg,$RegroupPourFic) ) {
					if ($EcrireLogComp ) {
						WriteCompLog ($logComp, "ERREUR : erreur ecriture dans fichier export regroupement.".$RegroupPourFic,$pasdefichier);
					} else {
						$resultatLecture .= "erreur ecriture dans fichier export regroupement" ;
					}
					return "erreur ecriture fichier regroupement <br/>";
				} else {
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "INFO : description regroupement ecrite dans le fichier [date]_".$typeSelection."_".$typeAction."-Regroupement.txt",$pasdefichier);
				}
			}
			}
			// ********* Fin creation fichier ==> Zip
			// On contrôle si il existe des documents sélectionnés, si oui, on les extrait
			// Doc pays
			$listeDocURL = array();		
			if (!($_SESSION['listeDocPays'] == "")) {
				// Appel de la fonction litTableDocument qui renvoie une variable partagée avec les chemins des fichiers à ajouter
				// Elle renvoie un message d'erreur en cas de probleme, sinon vide.
				 $resultatLecture .=litTableDocument("meta_pays",$_SESSION['listeDocPays']);
			}
			if (!($_SESSION['listeDocSys'] == "")) {
				// Appel de la fonction litTableDocument qui renvoie une variable partagée avec les chemins des fichiers à ajouter
				// Elle renvoie un message d'erreur en cas de probleme, sinon vide.
				$resultatLecture .= litTableDocument("meta_systemes",$_SESSION['listeDocSys']);
			}
			if (!($_SESSION['listeDocSect'] == "")) {
				// Appel de la fonction litTableDocument qui renvoie une variable partagée avec les chemins des fichiers à ajouter
				// Elle renvoie un message d'erreur en cas de probleme, sinon vide.
				$resultatLecture .= litTableDocument("meta_secteurs",$_SESSION['listeDocSect']);
			}		
			$NbrDoc = count($listeDocURL);
			if ($NbrDoc>0) {
				for ($cpt = 1; $cpt <= $NbrDoc; $cpt++) {
					$FileFullPath = $_SERVER["DOCUMENT_ROOT"]."/".$listeDocURL[$cpt];
					if ($EcrireLogComp ) {
						WriteCompLog ($logComp, "Ajout de fichiers sup ".$FileFullPath." dans l'archive ".$zipFilename ,$pasdefichier);
					}
					$theZipFile->add_files($FileFullPath);
				}
			}
			// Ajout des fichiers de stats dans l'archive
			if ($typeSelection == "statistiques") {
								// On boucle sur toutes les tables pour extraire les donnees
				for ($cptTS = 0;$cptTS <= $nbrTS;$cptTS++) {
					$ficSuffixe = getSuffixeFicStat($tableStat[$cptTS]);
					$nomFicExport = $dirLog."/".date('y\-m\-d')."_".$ficSuffixe.".txt";
					$theZipFile->add_files($nomFicExport);
				}
			} else {
				// Ajout des autres fichiers dans l'archive
				// Fichier de données
				$theZipFile->add_files($nomFicExport);
			}
			// Fichier de selection
			$theZipFile->add_files($nomFicExportSel);
			// Si regroupement, le fichier de definition des regroupements
			if (!($_SESSION['listeRegroup'] == "")) {
				$theZipFile->add_files($nomFicExportReg);
			}		
			// Creation effective de l'archive
			$theZipFile->create_archive();
			$fichierDejaCree = true;
		} // fin if ($exportFichier && (!($fichierDejaCree))
	}// fin du if ($countTotal!=0) 
	if ($fichierDejaCree)  {
		$addURLPag = "&dejf=y";
	} else {
		$addURLPag = "";
	}
	$resultatLecture .= paginate($_SERVER['PHP_SELF'].'?'.removeQueryStringParam($_SERVER['QUERY_STRING'],'page'), $addURLPag."&amp;page=", $countPages, $currentPage);
	
	$compteurItem = $countTotal;
	if ($EcrireLogComp ) {
		WriteCompLog ($logComp, "INFO : resultat pour la requete en cours : ".$compteurItem." / ".$countTotal." lignes.",$pasdefichier);
	}
	if ($exportFichier && $EcrireLogComp ) {
		WriteCompLog ($logComp, "Les donnees ont ete ecrites dans le fichier ".$nomFicExpLien." pour la filiere ".$typeAction,$pasdefichier);
	}
	// Suppression du regroupement fictif cree pour les stats globales
	if ($creationRegBidon) {
		unset($_SESSION['listeRegroup']);
	}
	if ($debugAff) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "fin du traitement en ".$debugTimer."<br/>";
	}

}

//*********************************************************************
// recupereLibelleFiliere : Fonction pour recuperer le libelle de la filiere
function recupereLibelleFiliere($typeAction){
switch ($typeAction) {
	case "activite":
		$libelleAction = "Activit&eacute;";
		break;
	case "capture":
		$libelleAction = "Captures totales";
		break;
	case "NtPart":
		$libelleAction = "NtPt";
		break;
	case "taillart":
		$libelleAction = "Structure de taille";
		break;
	case "engin":
		$libelleAction = "Engin";
		break;
	default:
	$libelleAction = $typeAction;
		break;
}
return $libelleAction;
}

//*********************************************************************
// creeRegroupement : Fonction de creation d'un regroupement a partir d'un SQL
function creeRegroupement($SQLaExecuter,$posDEBID ,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$typeSelection,$tableStat,$Compteur,$posSysteme,$posSecteur,$posGTE,$creationRegBidon,$typeStatistiques) {
// Cette fonction permet de gerer la creation des regrouepements
// Elle est aussi tres importante car elle permet de gerer le calcul des statistiques générales.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLaExecuter:
// $posDEBID :
// $posESPID :
// $posESPNom:
// $posStat1 :
// $posStat2 :
// $posStat3 :
// $typeSelection:
// $tableStat:
// $Compteur :
// $posSysteme:
// $posSecteur:
// $posGTE :
// $creationRegBidon:
// $typeStatistiques:
//*********************************************************************
// En sortie : 
// La fonction cree une ligne dans la table temporaire 
//*********************************************************************
	$debugLog = true;
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	global $resultatLecture;
	//echo $typeSelection." - ".$tableStat."<br/>";
	// On commence par vider la table temporaire
	if ($Compteur ==0) {
		$SQLDel = "delete from temp_extraction";
		$SQLDelresult = pg_query($connectPPEAO,$SQLDel);
		$erreurSQL = pg_last_error($connectPPEAO);
		if ( !$SQLDelresult ) { 
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "ERREUR : Erreur delete temp_extraction (erreur complete = ".$erreurSQL.")",$pasdefichier);
			}
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur Erreur delete temp_extraction , cette table n'existe peut etre pas dans votre base (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
			$erreurProcess = true;
		} else {
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "INFO : suppression de tous les enregs dans temp_extraction OK",$pasdefichier);
			}
			pg_free_result($SQLDelresult);
		}
	}
	// Traitement du SQL
	//echo $SQLaExecuter."<br/>";
	if ($EcrireLogComp && $debugLog && $typeStatistiques == "generales") {
		WriteCompLog ($logComp, "DEBUG : tables stat = ".$tableStat,$pasdefichier);
	}
	$SQLfinalResult = pg_query($connectPPEAO,$SQLaExecuter);
	$erreurSQL = pg_last_error($connectPPEAO);
	$cpt1 = 0;
	if ( !$SQLfinalResult ) { 
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "ERREUR : Erreur query final regroupements ".$SQLaExecuter." (erreur complete = ".$erreurSQL.")",$pasdefichier);
		}
		$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query regroupements ".$SQLaExecuter." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
		$erreurProcess = true;
	} else {
		if (pg_num_rows($SQLfinalResult) == 0) {
			// Avertissement
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "Regroupements : pas de resultat disponible pour la selection ".$SQLaExecuter,$pasdefichier);
			}
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Regroupements : pas de resultat disponible pour la sélection<br/>";
		} else {
			// Attention, les variables esp / deb ne contiendront pas toujours des id especes ou debarquement. Pour les stats, les ruptures se feront sur d'autres ID.
			// On garde toutefois ces noms (manque de temps pour les changer)
			$cptNbRow = 0;
			$espPrec = "";
			$debIDPrec = "";
			$espEnCours = "";
			$debEnCours = "";
			$RegPrec = "";
			$RegEnCours = "";
			$NomRegEncours = "";
			$Mesure = 0;
			$regroupDeb = array(); // gestion du regroupement pour un débarquement
			$cptTempExt = 0;
			$ColonneTE = "";
			$ValuesTE = "";
			$NumRegEnCours = 0;
			$effortSysSect = 0;
			while ($finalRow = pg_fetch_row($SQLfinalResult) ) {
				// ********************************
				// **** GESTION DES STATS GENERALES
				if ($typeStatistiques == "generales") {
					// Gestion du cas des stats generales
					// Les ruptures sont les memes. Elles dependent en plus de ce qu'on va trouver dans la table des efforts.
					
					$anneeEnCours = $finalRow[8];		//annee
					$moisEnCours = $finalRow[9];	//mois
					$SystemeEncours = $finalRow[2];	// systeme
					$sectEnCours = $finalRow[$posSecteur]; // Secteur
					$espEnCours = $finalRow[$posESPID];		// Especes
					// **** Analyse des valeurs disponibles dans la tables des efforts
					// Requete dans la table des efforts pour savoir si l'effort est disponible par secteur ou par systeme.
					$pasEffortSecteur = false;
					$SQLaExecuter = "select * from art_stat_effort where ref_systeme_id = ".$finalRow[2]." and ref_secteur_id = ".$finalRow[$posSecteur]." and annee = ".$anneeEnCours." and ref_mois_id = ".$moisEnCours;
					//echo $SQLaExecuter."<br/>";
					$SQLResult = pg_query($connectPPEAO,$SQLaExecuter);
					$erreurSQL = pg_last_error($connectPPEAO);
					if ( !$SQLResult ) { 
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp, "ERREUR : Erreur query final regroupements ".$SQLaExecuter." (erreur complete = ".$erreurSQL.")",$pasdefichier);
						}
						$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query systeme dans calcul stat generales ".$SQLaExecuter." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
						$erreurProcess = true;
					} else {
						if (pg_num_rows($SQLResult) == 0) {
							// Avertissement
							if ($EcrireLogComp ) {
								WriteCompLog ($logComp, "INFO : pas d'effort pour le systeme id = ". $finalRow[2]." et le secteur id = ".$finalRow[$posSecteur]." et annee = ".$anneeEnCours." et mois = ".$moisEnCours.", on recherche l'effort sur le systeme",$pasdefichier);
							}
							$pasEffortSecteur = true;
						} else {
							$ResultRow = pg_fetch_row($SQLResult);
							$effortSysSect = $ResultRow[8];
							$sectSystEncours = $sectEnCours; // On va faire le calcul par secteur
							pg_free_result($SQLResult);
						}
					}
					
					// La recherche sur le secteur a echoue, on cherche la valeur par systeme
					if ($pasEffortSecteur) {
						// On recherche l'effort pour le systeme en cours
						$SQLaExecuter = "select * from art_stat_effort as aeff where ref_systeme_id = ".$finalRow[2]." and annee = ".$anneeEnCours." and ref_mois_id = ".$moisEnCours;
						$SQLResult = pg_query($connectPPEAO,$SQLaExecuter);
						$erreurSQL = pg_last_error($connectPPEAO);
						if ( !$SQLResult ) { 
							if ($EcrireLogComp ) {
								WriteCompLog ($logComp, "ERREUR : Erreur query final regroupements ".$SQLaExecuter." (erreur complete = ".$erreurSQL.")",$pasdefichier);
							}
							$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query systeme dans calcul stat generales ".$SQLaExecuter." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
							$erreurProcess = true;
						} else {
							if (pg_num_rows($SQLResult) == 0) {
								// Avertissement
								if ($EcrireLogComp ) {
									WriteCompLog ($logComp, "ERREUR : pas d'effort pour le systeme ".$finalRow[2]." ni pour le secteur ".$finalRow[$posSecteur]." pour annee = ".$anneeEnCours." et mois = ".$moisEnCours.", arret du traitement.",$pasdefichier);
									$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>pas d'effort ni le systeme ".$finalRow[2]." ni pour le secteur ".$finalRow[$posSecteur]." pour annee = ".$anneeEnCours." et mois = ".$moisEnCours.". Arret du calcul<br/>";
								}
								$erreurProcess = true;
							} else {
								$ResultRow = pg_fetch_row($SQLResult);
								$effortSysSect = $ResultRow[8];
								$sectSystEncours = $SystemeEncours; // On va faire le calcul par systeme
								pg_free_result($SQLResult);
							}
						}
					}
					
					// La rupture est pour tout nouveau triplé Secteur ou systeme / Annee / mois
					// Une entree est ajoutée à chaque rupture
					//echo $sectSystEncours." - ".$anneeEnCours." - ".$moisEnCours."<br/>";
					if ($EcrireLogComp && $debugLog) {
						WriteCompLog ($logComp, "DEBUG : ".$sectSystEncours." - ".$anneeEnCours." - ".$moisEnCours,$pasdefichier);
					}
					if ( ($sectSystEncours<> $sectSystPrec) || ($sectSystEncours == $sectSystPrec && $anneeEnCours<>$anneePrec ) ||
						($sectSystEncours == $sectSystPrec && $anneeEnCours==$anneePrec && $moisEnCours<>$moisPrec) ) {
						if (!($debIDPrec == "")) {
							// Ajout du contenu de ce tableau dans la table temporaire.
							if (!(AjoutEnreg($regroupDeb,$sectSystPrec."-".$anneePrec."-".$moisPrec,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$DerniereLigne,$typeStatistiques,$EffortPrec))) {
								$erreurProcess = true;
								echo "erreur fonction AjoutEnrg<br/>";
							}
							//echo "ajout a la rupture<br/>";
							// On reinitialise les compteurs
							if ($EcrireLogComp && $debugLog) {
								WriteCompLog ($logComp, "DEBUG : reinitialisation",$pasdefichier);
							}
							$Mesure = 0;
							unset($regroupDeb);
							$NumRegEnCours = 0;
							$RegPrec = "";
						}
					} // fin du if ($debEnCours<>$debIDPrec)
					$controleRegroupement = false;	 // Est-ce qu'on controle la presence de l'espece dans le regroupement, eventuellement on le cree ?
					// Deux cas, soit il s'agit d'une table avec especes ou non
					$listeTableStatSp = "asp,ats,attgt,atgts";
					if ( strpos($listeTableStatSp,$tableStat) === false) {
						$controleRegroupement = true;
						if ($EcrireLogComp && $debugLog) {
							WriteCompLog ($logComp, "DEBUG : table sans especes ".$tableStat,$pasdefichier);
						}
					} else {
						// On controle les especes
						if ($espEnCours<>$espPrec) {
							// Nouvelle espece
							// On recherche le regroupement pour cette espece.
							$RegTrouve = false;
							$NbReg = count($_SESSION['listeRegroup']);
							for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
								$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
								for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
									$infoEsp = explode("&#&",$_SESSION['listeRegroup'][$cptR][$cptR2]);
									if ($infoEsp[0] == $espEnCours) {
										$RegTrouve = true;
										$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
										$RegEnCours = $infoReg[0];
										$NomRegEncours = $infoReg[1];
										if ($EcrireLogComp && $debugLog) {
											WriteCompLog ($logComp, "DEBUG : Regroupement trouve = ".$RegEnCours." ".$NomRegEncours,$pasdefichier);
										}
										break;
									}
								}
								if ($RegTrouve) {
									break;
								}
							}
							if (!$RegTrouve) {
								if ($EcrireLogComp && $debugLog) {
									WriteCompLog ($logComp, "DEBUG : pas de Regroupement trouve pour espece ".$espEnCours." ==> dans DIV",$pasdefichier);
								}
								// Pas de regroupement trouvé pour cette espece, on le met dans le regroupement "DIV"
								$RegEnCours = "DIV";
								$NomRegEncours = "divers";
							}
							if ($RegEnCours == $RegPrec) {
								// On met a jour le total en cours
								$regroupDeb = majReg($regroupDeb,$RegEnCours,$NumRegEnCours,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$debugLog);
							} else {
								// On doit controler si l'espece n'est pas déja dans un regroupement dans le tableau temporaire pour le débarquement en cours.
								$controleRegroupement = true;
							}
						} else {
							// On est toujours sur la meme espece
							// On ajoute
							$regroupDeb = majReg($regroupDeb,$RegEnCours,$NumRegEnCours,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$debugLog);
							
						}// fin du ( $espEnCours<>$espPrec)
					}
					if ($controleRegroupement) {
						// On regarde si on n'a pas déjà créée un enregistrement pour ce regroupement (On doit toujours etre dans le meme debarquement / groupe stat)
						// dans le tableau temporaire
						$RegTempTrouve = false;
						$NbRegDeb = count($regroupDeb);
						if ($EcrireLogComp && $debugLog) {
							WriteCompLog ($logComp, "DEBUG : nbre enreg regroupDeb = ".$NbRegDeb. " regencours = ".$RegEnCours,$pasdefichier);
						}
						if ($NbRegDeb >= 1 ) {
							// Des regroupements sont disponibles.
							// On verifie si l'espece dans l'un des regroupements disponibles. Si oui, on met a jour le calcul + maj d'un flag
							for ($cptRg=1 ; $cptRg<=$NbRegDeb;$cptRg++) {
								if ($regroupDeb[$cptRg][1] == $RegEnCours) {
									$NumRegEnCours = $cptRg;
									$regroupDeb = majReg($regroupDeb,$RegEnCours,$cptRg,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$debugLog);
									$RegTempTrouve = true;
									break;
								}
							}
						} else {
							// Aucun regroupement disponible.
							// On crée une entrée dans le tableau
							$NbRegDebSuiv = count($regroupDeb) +1;
							$NumRegEnCours = $NbRegDebSuiv;
							$regroupDeb= creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$tableStat,$typeSelection,$debugLog);
							$RegTempTrouve = true; // On le met a vrai pour eviter que le tableau soit créé deux fois
								
						}// fin du 	if ($NbRegDeb >= 1 )	
						if (!($RegTempTrouve)) {
							// Dans le cas ou precedement, aucun regroupement n'a été trouvé, on le crée.
							// On cree le nouveau regroupement
							$NbRegDebSuiv = count($regroupDeb) +1;
							$NumRegEnCours = $NbRegDebSuiv;
							$regroupDeb = creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$tableStat,$typeSelection,$debugLog);
						}
					} // fin du if ($controleRegroupement)
					// On met a jour les variables contenant toutes les valeurs de rupture precedentes
					$espPrec = $espEnCours;
					$RegPrec = $RegEnCours;
					$DerniereLigne = $finalRow;
					$sectSystPrec = $sectSystEncours ;
					$anneePrec= $anneeEnCours;
					$moisPrec= $moisEnCours;
					$debIDPrec = $sectSystPrec."-".$anneePrec."-".$moisPrec; // Pour la mise a jour de la derniere ligne
					$EffortPrec = $effortSysSect;
					
				} else {
				// ********************************
				// **** GESTION DES REGROUPEMENTS DANS LE CAS GENERAL
					// Cas général des regroupements sans calcul stat
					$espEnCours = $finalRow[$posESPID];
					$debEnCours = $finalRow[$posDEBID];
					// Debug
					if ($EcrireLogComp && $debugLog) {
						WriteCompLog ($logComp, "DEBUG : debencours = ".$debEnCours." espencours = ".$espEnCours. " [".$posStat1."]poids = ".$finalRow[$posStat1]." [".$posStat2."]nombre = ".$finalRow[$posStat2],$pasdefichier);
						//WriteCompLog ($logComp, "DEBUG : debprec = ".$debIDPrec." espprec = ".$espPrec,$pasdefichier);
					}
					if ($debEnCours<>$debIDPrec ) {
						if (!($debIDPrec == "")) {
							// Ajout du contenu de ce tableau dans la table temporaire.
							if (!(AjoutEnreg($regroupDeb,$debIDPrec,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$DerniereLigne,$typeStatistiques,0))) {
								$erreurProcess = true;
								echo "erreur fonction AjoutEnrg<br/>";
							}
							//echo "ajout a la rupture<br/>";
							// On reinitialise les compteurs
							if ($EcrireLogComp && $debugLog) {
								WriteCompLog ($logComp, "DEBUG : reinitialisation",$pasdefichier);
							}
							$Mesure = 0;
							unset($regroupDeb);
							$NumRegEnCours = 0;
							$RegPrec = "";
						}
					} // fin du if ($debEnCours<>$debIDPrec)
					$controleRegroupement = false;	 // Est-ce qu'on controle la presence de l'espece dans le regroupement, eventuellement on le cree ?					
					if ($espEnCours<>$espPrec) {
						// Nouvelle espece
						// On recherche le regroupement pour cette espece.
						$RegTrouve = false;
						$NbReg = count($_SESSION['listeRegroup']);
						for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
							$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
							for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
								$infoEsp = explode("&#&",$_SESSION['listeRegroup'][$cptR][$cptR2]);
								if ($infoEsp[0] == $espEnCours) {
									$RegTrouve = true;
									$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
									$RegEnCours = $infoReg[0];
									$NomRegEncours = $infoReg[1];
									if ($EcrireLogComp && $debugLog) {
										WriteCompLog ($logComp, "DEBUG : Regroupement trouve = ".$RegEnCours." ".$NomRegEncours,$pasdefichier);
									}
									break;
								}
							}
							if ($RegTrouve) {
								break;
							}
						}
						if (!$RegTrouve) {
							if ($EcrireLogComp && $debugLog) {
								WriteCompLog ($logComp, "DEBUG : pas de Regroupement trouve pour espece ".$espEnCours." ==> dans DIV",$pasdefichier);
							}
							// Pas de regroupement trouvé pour cette espece, on le met dans le regroupement "DIV"
							$RegEnCours = "DIV";
							$NomRegEncours = "divers";
						}
						if ($RegEnCours == $RegPrec) {
							// On met a jour le total en cours
							$regroupDeb = majReg($regroupDeb,$RegEnCours,$NumRegEnCours,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$debugLog);
						} else {
							// On doit controler si l'espece n'est pas déja dans un regroupement dans le tableau temporaire pour le débarquement en cours.
							$controleRegroupement = true;
						}
					} else {
						// On est toujours sur la meme espece
						// On ajoute
						$regroupDeb = majReg($regroupDeb,$RegEnCours,$NumRegEnCours,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$debugLog);
						
					}// fin du ( $espEnCours<>$espPrec)
	
					if ($controleRegroupement) {
						// On regarde si on n'a pas déjà créée un enregistrement pour ce regroupement (On doit toujours etre dans le meme debarquement / groupe stat)
						// dans le tableau temporaire
						$RegTempTrouve = false;
						$NbRegDeb = count($regroupDeb);
						if ($EcrireLogComp && $debugLog) {
							WriteCompLog ($logComp, "DEBUG : nbre enreg regroupDeb = ".$NbRegDeb. " regencours = ".$RegEnCours,$pasdefichier);
						}
						if ($NbRegDeb >= 1 ) {
							// Des regroupements sont disponibles.
							// On verifie si l'espece dans l'un des regroupements disponibles. Si oui, on met a jour le calcul + maj d'un flag
							for ($cptRg=1 ; $cptRg<=$NbRegDeb;$cptRg++) {
								if ($regroupDeb[$cptRg][1] == $RegEnCours) {
									$NumRegEnCours = $cptRg;
									$regroupDeb = majReg($regroupDeb,$RegEnCours,$cptRg,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$debugLog);
									$RegTempTrouve = true;
									break;
								}
							}
						} else {
							// Aucun regroupement disponible.
							// On crée une entrée dans le tableau
							$NbRegDebSuiv = count($regroupDeb) +1;
							$NumRegEnCours = $NbRegDebSuiv;
							$regroupDeb= creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$tableStat,$typeSelection,$debugLog);
							$RegTempTrouve = true; // On le met a vrai pour eviter que le tableau soit créé deux fois
								
						}// fin du 	if ($NbRegDeb >= 1 )	
						if (!($RegTempTrouve)) {
							// Dans le cas ou precedement, aucun regroupement n'a été trouvé, on le crée.
							// On cree le nouveau regroupement
							$NbRegDebSuiv = count($regroupDeb) +1;
							$NumRegEnCours = $NbRegDebSuiv;
							$regroupDeb = creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$finalRow[$posStat1],$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$tableStat,$typeSelection,$debugLog);
						}
					} // fin du if ($controleRegroupement)
					// On met a jour les variables contenant l'espece et le regroupement precedent
					$espPrec = $espEnCours;
					$debIDPrec = $debEnCours;
					$RegPrec = $RegEnCours;
					$DerniereLigne = $finalRow;
					$EffortPrec = 0;
				}
			} // fin du while
			// Attention, quand on sort, on doit mettre à jour le dernier tableau dans la BD.
			// On cree autant de lignes dans la table temp que de lignes dans le tableau temporaire pour ce debarquement
			if (!(AjoutEnreg($regroupDeb,$debIDPrec,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$DerniereLigne,$typeStatistiques,$EffortPrec))) {
				$erreurProcess = true;
			}
		} // fin du if (pg_num_rows($SQLfinalResult) == 0)
	}
	pg_free_result($SQLfinalResult);
	//exit; // pour test
}

//*********************************************************************
// creeNouveauReg : Fonction de creation d'un regroupement
function creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$Stat1,$Stat2,$posStat3,$Stat3,$tableStat,$typeSelection,$debugLog) {
// Cette fonction permet de creer un fichier a exporter a partir d'un SQL
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $regroupDeb
// $RegEnCours
// $NomRegEncours
// $Stat1
// $Stat2
// $Stat3
// $tableStat
// $debugLog
//*********************************************************************
// En sortie : créé le fichier $ExpCom
// La fonction met a jour $regroupDeb
//*********************************************************************
// 
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	
	$regroupDeb[$NbRegDebSuiv][1] = $RegEnCours;
	$regroupDeb[$NbRegDebSuiv][2] = $NomRegEncours;							
	$regroupDeb[$NbRegDebSuiv][3] = floatval($Stat1);
	$regroupDeb[$NbRegDebSuiv][4] = floatval($Stat2);
	if ($typeSelection == "statistiques") {
		$regroupDeb[$NbRegDebSuiv][6] = $tableStat;						
	}
	if (!($posStat3 == -1 )) {
		$regroupDeb[$NbRegDebSuiv][5] = floatval($Stat3);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : CREATION tableau temporaire pour ".$regroupDeb[$NbRegDebSuiv][1]." val1 = ".$regroupDeb[$NbRegDebSuiv][3]." val2 = ".$regroupDeb[$NbRegDebSuiv][4]." val3 = ".$regroupDeb[$NbRegDebSuiv][5],$pasdefichier);
		}							
	} else {
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : CREATION tableau temporaire pour ".$regroupDeb[$NbRegDebSuiv][1]." val1 = ".$regroupDeb[$NbRegDebSuiv][3]." val2 = ".$regroupDeb[$NbRegDebSuiv][4],$pasdefichier);
		}							
	}
	return $regroupDeb;
}
//*********************************************************************
// creeNouveauReg : Fonction de creation d'un regroupement
function majReg($regroupDeb,$RegEnCours,$NumRegEC,$Stat1,$Stat2,$posStat3,$Stat3,$debugLog) {
// Cette fonction permet de creer un fichier a exporter a partir d'un SQL
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $regroupDeb
// $RegEnCours
// $NomRegEncours
// $Stat1
// $Stat2
// $Stat3
// $tableStat
// $debugLog
//*********************************************************************
// En sortie : créé le fichier $ExpCom
// La fonction met a jour $regroupDeb
//*********************************************************************
// 
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	
	$regroupDeb[$NumRegEC][3] = $regroupDeb[$NumRegEC][3] + floatval($Stat1);
	$regroupDeb[$NumRegEC][4] = $regroupDeb[$NumRegEC][4] + floatval($Stat2); 	
	if (!($posStat3 == -1 )) {
		$regroupDeb[$NumRegEC][5] = floatval($regroupDeb[$NumRegEC][5]) + floatval($Stat3);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : MAJ tableau meme espece = ".$RegEnCours. " num ".$NumRegEC." val1 = ".$regroupDeb[$NumRegEC][3]." - val2= ".$regroupDeb[$NumRegEC][4]." - val3= ".$regroupDeb[$NumRegEC][5],$pasdefichier);
		}
	} else { 
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : MAJ tableau meme espece = ".$RegEnCours. " num ".$NumRegEC." val1 = ".$regroupDeb[$NumRegEC][3]." - val2= ".$regroupDeb[$NumRegEC][4],$pasdefichier);
		}
	}
	return $regroupDeb;

}

//*********************************************************************
// creeFichier : Fonction de creation d'un fichier a partir d'un SQL
function creeFichier($SQLaExecuter,$listeChamps,$typeAction,$ConstIDunique,$ExpComp,$pasTestReg) {
// Cette fonction permet de creer un fichier a exporter a partir d'un SQL
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLaExecuter : SQL a executer pour creer le fichier
// $listeChamps
// $typeAction
// $ConstIDunique
// $ExpComp
// $pasTestReg : si vrai, on ne teste pas les regroupements
//*********************************************************************
// En sortie : créé le fichier $ExpCom
// La fonction met a jour $resultatLecture
//*********************************************************************
// 
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	global $erreurProcess;
	global $resultatLecture;

	// Execution de la requete
	$SQLfinalResult = pg_query($connectPPEAO,$SQLaExecuter);
	$erreurSQL = pg_last_error($connectPPEAO);
	$cpt1 = 0;
	if ( !$SQLfinalResult ) { 
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "ERREUR : Erreur creation fichier query final ".$SQLaExecuter." (erreur complete = ".$erreurSQL.")",$pasdefichier);
		}
		$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur creation fichier query ".$SQLaExecuter." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
		$erreurProcess = true;
	} else {
		if (pg_num_rows($SQLfinalResult) == 0) {
			// Avertissement
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "Pas de resultat disponible pour la selection ".$SQLaExecuter,$pasdefichier);
			}
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Pas de resultat disponible pour la sélection (creation fichier)<br/>";
		} else {

			$resultatFichier = str_replace(",","\t",$listeChamps);
			if (! fwrite($ExpComp,$resultatFichier."\r\n") ) {
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "ERREUR : erreur ecriture dans fichier export.",$pasdefichier);
				} else {
					$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Erreur ecriture dans fichier export" ;
				}
				exit;
			}	
			while ($finalRow = pg_fetch_row($SQLfinalResult) ) {
				$resultatFichier = "";
				// Construction de la liste des résultat
				// Tout d'abord, construction de l'ID unique
				// Ex $ConstIDunique = "DEB-##-11";
				// On garde le prefixe DEB et on extrait l'index du champ a recuperer de la ligne du resultat de la requete. ici 11
				$IDunique = "";
				if (!($ConstIDunique == "")) {
								
					$Locprefixe = substr($ConstIDunique,0,3); // Attention, pour des raisons de simplicité, le suffixe n'est que sur 3 caractères.
					$locIndex = substr(strrchr($ConstIDunique, "-##-"),1);
					$IDunique = $Locprefixe.$finalRow[$locIndex];
					$resultatFichier .= $IDunique."\t";
				}
				if (!($_SESSION['listeRegroup'] == "") && (!($pasTestReg)) ) {
					// Gestion des regroupements
					// On doit récupérer la liste dans le champ valeur_ligne de la table temp_extraction
					// et construire la ligne de resultat avec
					$ligne_resultat = $finalRow[8];
					$tabResultat = explode("&#&",$ligne_resultat);
					$NbResultat = count($tabResultat);
					for ($cptResult = 1;$cptResult <= $NbResultat;$cptResult++) {
						$resultatFichier .= $tabResultat[$cptResult]."\t";
					}
				} else {
					switch ($typeAction) {
						case "biologie" :

							// On doit calculer un coefficient d'extrapolation 
							// On execute une requete supplémentaire pour recuperer le nombre d'individu dans exp_biologie pour la fraction et l'espece considerée
							// On recupere le nombre de poissons reellement mesures pour une fraction donnée (qui elle meme correspond à 
							// une seule espece.
							// On recupere le nombre total d'individu de la fraction
							$totalIndividus = $finalRow[19];
							// On compte les enregs dans biologie (individus effectivement analyse) pour cette fraction
							$SQLcomplement = "Select count(id) from exp_biologie where exp_fraction_id =  ".$finalRow[18] ;
							$SQLcomplementResult = pg_query($connectPPEAO,$SQLcomplement);
							$erreurSQL = pg_last_error($connectPPEAO);
							if ( !$SQLcomplementResult ) { 
								if ($EcrireLogComp ) {
									WriteCompLog ($logComp, "ERREUR : Erreur query complementaire biologie ".$SQLcomplement." (erreur complete = ".$erreurSQL.")",$pasdefichier);
								}							
							} else {
								$RowComplement = pg_fetch_row($SQLcomplementResult); 
								$totalBio = $RowComplement[0];
								pg_free_result($SQLcomplementResult);
							}
							// Calcul du coefficient = nombre de poisson peches / nombre de poissons mesures
							$coefficient =floatval( intval($totalIndividus) / intval($totalBio));	
							$coefficient = round($coefficient,2);
							$nbrRow = count($finalRow)-1;
							// Transcription du resultat de la requete globale pour un affichage écran et un export sous forme de fichier
							for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
								if (is_numeric($finalRow[$cptRow])){
									$AjChps = strval($finalRow[$cptRow]);
									$AjChps =str_replace(".",",",$AjChps);
								}else {
									$AjChps = $finalRow[$cptRow];
									
								}
								$resultatFichier .=$AjChps."\t";
							}
							// Ajout du coefficient tout a la fin du fichier
							$resultatFichier .= $totalBio."\t".$coefficient;
							break;	
						default	:
							$nbrRow = count($finalRow)-1;
								for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
									if (is_numeric($finalRow[$cptRow])){
										$AjChps = strval($finalRow[$cptRow]);
										$AjChps =str_replace(".",",",$AjChps);
									}else {
										$AjChps = $finalRow[$cptRow];
										
									}
									$resultatFichier .=$AjChps."\t";
								}									
					}
				}
				$resultatFichier .="\n";
				if (! fwrite($ExpComp,$resultatFichier) ) {
					if ($EcrireLogComp ) {
						WriteCompLog ($logComp, "ERREUR : erreur ecriture dans fichier export.",$pasdefichier);
					} else {
						$resultatLecture .= "erreur ecriture dans fichier export" ;
					}
					exit;
				}
			// Compteur
			$cpt1++;							
			}

		}
		pg_free_result($SQLfinalResult);	
	} // fin du !$SQLfinalResult
	fclose($ExpComp);
}

//*********************************************************************
// AfficheCategories : Fonction pour afficher les catégories troph / ecologiques a selectionner
function litTableDocument($nomTable,$ListeID) {
// Cette fonction permet de construire la liste des checkboxes pour la selection des especes à selectionner
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $nomTable : nom de la table a lire
// $ListeID : liste des ID sur lesquels faire la requete
//*********************************************************************
// En sortie : 
// La fonction renvoie $messageErreur
// La fonction met aussi à jour la variable partagée listeDocURL
//*********************************************************************
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	global $listeDocURL;
	$messageErreur = "";

	$SQLDoc = "select * from ".$nomTable." where meta_id in (".$ListeID.") order by meta_id asc";
	$SQLDocResult = pg_query($connectPPEAO,$SQLDoc);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLDocResult ) { 
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "ERREUR : Erreur lecture table doc pays ".$SQLDoc." (erreur complete = ".$erreurSQL.")",$pasdefichier);
		}
		$messageErreur .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;Erreur lecture table doc pays ".$SQLDoc." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";

	} else {
		if (pg_num_rows($SQLDocResult) == 0) {
			$messageErreur .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>la requ&ecirc;te ".$SQLDoc." ne renvoie rien alors qu'un document a &eacute;t&eacute; s&eacute;lectionn&eacute;.<br/>";

		} else {
			// pour debug 
			$messageErreur .="<br/>Document trouv&eacute; !!<br/>";
			$cptURL = count($listeDocURL);
			while ($docRow = pg_fetch_row($SQLDocResult) ) {
				$cptURL ++;
				$listeDocURL[$cptURL] = "documentation/metadata/".$docRow[3];
			}
		}
		pg_free_result($SQLDocResult);
	}
	return $messageErreur;
}

//*********************************************************************
// AfficheCategories : Fonction pour afficher les catégories troph / ecologiques a selectionner
function AfficheCategories($typeCategorie,$typeAction,$ListeCE,$changtAction,$typePeche,$numTab,$ListEsp,$ListPoiss) {
// Cette fonction permet de construire la liste des checkboxes pour la selection des especes à selectionner
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $typeCategorie : le type de catégorie, soit Ecologiques soit Trophiques
// $typeAction : La filiere en cours
// $ListeEsp : la liste des valeurs sélectionnées pour la categorie en cours
// $changtAction : est-ce qu'on vient juste de changer la selection ?
//*********************************************************************
// En sortie : 
// La fonction renvoie $construitSelection
//*********************************************************************

	// Pour construire les SQL (il faut d'abord avoir rempli ces champs !!!
	// donc avoir appele AfficherSelection
	// Données pour la selection 
	// Note si $changtAction = "y", alors on remet les choix par defaut, i.e. on coche toutes les valeurs.
	global $connectPPEAO;
	global $CRexecution;
	global $erreurProcess;
	$SQLEspeces	= $_SESSION['SQLEspeces'];
	$construitSelection = "";
	$listEspFamille = "";
	// Definition des differents parametres selon qu'on recupere les categories trop ou eco
	switch ($typeCategorie) {
		case "Ecologiques":
			$champID = "ref_categorie_ecologique_id";
			$table = "ref_categorie_ecologique";
			$libelleTable = "categorie ecologique";
			$nomInput = "CEco";
			break;
		case "Trophiques":
			$champID = "ref_categorie_trophique_id";
			$table = "ref_categorie_trophique";
			$libelleTable = "categorie trophique";
			$nomInput = "CTro";
			break;
	}
	// Selon le type de peches, la fonction Js n'est pas la meme.
	switch ($typePeche) {
		case "artisanale" :
		$runfilieres = "runFilieresArt";
		break;
		case "experimentale":
		$runfilieres = "runFilieresExp";
		break;
	 }
	// Definition du SQL pour trouver toutes les catégories trophiques des especes de la selection
	//  $SQLEspeces ne contient que la liste des ID des especes de la selection
	$SQLCEco = "select distinct(".$champID.") from ref_espece where id in (".$SQLEspeces.")";	
	//echo $SQLCEco."<br/>";
	//$SQLCEco = "select * from ref_espece";
	$SQLCEcoResult = pg_query($connectPPEAO,$SQLCEco);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLCEcoResult ) {
		echo "erreur execution SQL pour ".$SQLCEco." erreur complete = ".$erreurSQL."<br/>";
	//erreur
	} else { 
		if (pg_num_rows($SQLCEcoResult) == 0) {
			// Erreur
			echo "pas d'especes trouvees dont le id est ".$SQLEspeces."<br/>" ;
		} else { 
			$cptInput = 1;
			$construitSelection .="<table id=\"".$nomInput."\"><tr><td class=\"catitem\">"; 
			// A faire : formater le resultat avec une table
			if (strpos($ListeCE,"tout") === false) {
				$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"tout\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','tout-".$nomInput."','','')\"/>&nbsp;<b>tout</b></td><td class=\"catitem\">";
			} else {
				$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"tout\" checked=\"checked\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','aucun-".$nomInput."','','')\"/>&nbsp;<b>tout</b></td><td class=\"catitem\">";
			}
			// Analyse des categories disponibles pour l'espèce considérée
			while ($CERow = pg_fetch_row($SQLCEcoResult) ) {
				$ContinueTrt = false ;
				$cptInput ++;				
				if (!($CERow[0] =="" || $CERow[0] == null)) {
					// on récupère le libelle de la categorie ecologique
					$SQLlibelle = "select libelle from ".$table." where id = '".$CERow[0]."'";
					$SQLlibelleResult = pg_query($connectPPEAO,$SQLlibelle);
					$erreurSQL = pg_last_error($connectPPEAO);
					$libelleCE ="";
					
					if ( !$SQLCEcoResult ) {
						echo "erreur execution SQL pour ".$SQLlibelle." erreur complete = ".$erreurSQL."<br/>";
						$cptInput --;
					//erreur
					} else { 
						if (pg_num_rows($SQLlibelleResult) == 0) {
							// Erreur
							echo "pas de ".$libelleTable." trouvee pour id = ".$CERow[0]."<br/>";
							$cptInput --;
						} else {
							$libelleRow = pg_fetch_row($SQLlibelleResult)	;
							$libelleCE = $libelleRow[0];
							$ContinueTrt = true;
							$valCont = $CERow[0];
						}
						pg_free_result($SQLlibelleResult);
					}// fin du if ( !$SQLtestResult )	
				} else { 
					$valCont = "";
					$libelleCE = "Vide";
					if ($CERow[0] == null) {
						$libelleCE = "Null";
						$valCont = "null";
					}
					$ContinueTrt = true;
				}	// fin du if (!($CERow[0] =="" || $CERow[0] == null))
				if ($ContinueTrt) {
					// Si on est en train de changer d'action, on remet à zéro
					if ($changtAction =="y" || strpos($ListeCE,"toutX") > 0  ) {
						$checked ="checked=\"checked\"";
					} else {
						// On teste si la valeur a déjà été saisie par l'utilisateur.
						if ($ListeCE == "") {
							$checked =""; 
						} else {
							if (strpos($ListeCE,$valCont) === false || (strpos($ListeCE,"pasttX") > 0 )) {
								$checked =""; 
							} else {
								$checked ="checked=\"checked\"";
							}
						}
					}
					$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"".$valCont."\" ".$checked."/>&nbsp;".$libelleCE;
					// C'est super moche c'est juste pour tester la validiter de la chose, a modifier pour faire quelque chose de mieux
					$str_cptInput = strval($cptInput);
					if (fmod($str_cptInput,'3') == '0') {
						$construitSelection .= "</td></tr><tr><td class=\"catitem\">";
					} else {
						$construitSelection .= "</td><td class=\"catitem\">";
					}
				} // fin du if ($ContinueTrt)
			} // fin du while
			$construitSelection .="</td></tr></table>";
			$construitSelection .= "<input id=\"num".$nomInput."\" type=\"hidden\" name=\"num".$nomInput."\" value=\"".$cptInput ."\"/>";
		}
	}	
	pg_free_result($SQLCEcoResult);
	return $construitSelection;
}

//*********************************************************************
// AfficheEspeces : Fonction pour afficher les especes a selectionner 
function AfficheEspeces($SQLEspeces,$ListeEsp,$changtAction,$typePeche,$typeAction,$numTab,$regroup,$ListCatE,$ListCatT,$ListPoiss) {
// Cette fonction permet de construire la liste des checkboxes pour la selection des especes à selectionner
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLEspeces : la liste des especes issues de la sélection initiale (du module précédent)
// $ListeEsp : la liste des especes sélectionnées
// $changtAction : est-ce qu'on vient juste de changer la selection ? y/n
// $regroup : est qu'on gere le regroupement d'especes ? y/n
//*********************************************************************
// En sortie : 
// La fonction renvoie $construitSelection
//*********************************************************************
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	$construitSelection = "";
	$listeSelectEsp = "";
	
	if ($SQLEspeces == "") {
		echo "erreur SQLEspeces vide dans la fonction AfficheEspeces<br/>Arret du traitement<br/>.";
		exit;
	}
	// Selon le type de peches, la fonction Js n'est pas la meme.
	switch ($typePeche) {
		case "artisanale" :
			$runfilieres = "runFilieresArt";
			break;
		case "experimentale":
			$runfilieres = "runFilieresExp";
			break;
		case "agglomeration":
			$runfilieres = "runFilieresStat";
			break;
		case "generales":
			$runfilieres = "runFilieresStat";
			break;
	 }
	 // Attention, warning dans le cas ou un ou plusieures regroupements existent.
	 if (isset($_SESSION['listeRegroup']) && (!($_SESSION['listeRegroup']=="")) ) {
		 //if (isset($_SESSION['listeRegroup'])) {
		$construitSelection .= "<span id=\"EspInfo\"><img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Des regroupements ont d&eacute;j&agrave; &eacute;t&eacute; cr&eacute;&eacute;s. Toute modification de la liste des esp&egrave;ces risque de g&eacute;n&eacute;rer des erreurs.</span><br/><br/>";
	}
// Gere l'affichage des différentes espèces
	$SQLCEco = "select id,libelle,ref_famille_id,ref_categorie_trophique_id,ref_categorie_ecologique_id from ref_espece where id in (".$SQLEspeces.") order by libelle";	
	$SQLCEcoResult = pg_query($connectPPEAO,$SQLCEco);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLCEcoResult ) {
		echo "erreur execution SQL pour ".$SQLCEco." erreur complete = ".$erreurSQL."<br/>";
	//erreur
	} else { 
		if (pg_num_rows($SQLCEcoResult) == 0) {
			// Erreur
			echo "pas d'especes trouvees dont le id est ".$SQLEspeces."<br/>" ;
		} else { 
			$cptInput = 1;
			$cptEsptotal = 0;
			$construitSelection .="<table id=\"espece\"><tr><td>"; 
			if (strpos($ListeEsp,"XtoutX") === false) {
				$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"XtoutX\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','tout','')\"/>&nbsp;<b>tout</b></td>";
			} else {
				$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"XtoutX\" checked=\"checked\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','aucun','')\"/>&nbsp;<b>tout</b></td>";
			}
			//echo $ListPoiss." - ".$ListCatT." - ".$ListCatE." <br/>";
			while ($CERow = pg_fetch_row($SQLCEcoResult) ) {
				if (!($CERow[0] =="" || $CERow[0] == null)) {
					$cptEsptotal ++;
					$non_poisson =-1;
					// Test sur le critere poisson - non poisson
					$SQLFam = "select id,non_poisson from ref_famille where id = ".$CERow[2] ;	
					$SQLFamResult = pg_query($connectPPEAO,$SQLFam);
					$erreurSQL = pg_last_error($connectPPEAO);
					$addClass = " grise";
					$checked ="";
					if ( !$SQLFamResult ) {
						echo "erreur execution SQL pour ".$SQLFam." erreur complete = ".$erreurSQL."<br/>";
					//erreur
					} else { 
						if (pg_num_rows($SQLFamResult) == 0) {
							// Erreur
							echo "pas de famille trouvee pour l'espece ".$CERow[0]." / id famille = ".$CERow[2]."<br/>" ;
						} else { 
							$famRow = pg_fetch_row($SQLFamResult)	;
							$non_poisson = $famRow[1];
						}
					}
					$TestPoisson = ExclureSelectionPoisson($non_poisson,$ListPoiss);
					$TestCatT = ExclureCategorie($CERow[3],$ListCatT);					
					$TestCatE = ExclureCategorie($CERow[4],$ListCatE);
					// Si on est en train de changer d'action, on remet à zéro
					if ($changtAction =="y" || strpos($ListeEsp,"toutX") > 0  ){
						$checked ="checked=\"checked\"";
					} else {
						// On teste si la valeur a déjà été saisie par l'utilisateur.
						if ($ListeEsp == "") {
							$checked ="checked=\"checked\""; 
						} else {
							if (strpos($ListeEsp,$CERow[0]) === false || (strpos($ListeEsp,"pasttX") > 0 )) {
								$checked =""; 
							} else {
								$checked ="checked=\"checked\"";
							}
						}
					}
					//echo syntheseTestExclusionEsp($TestPoisson,$TestCatE,$TestCatT)." catT = ".$CERow[3]."/ catE = ".$CERow[4]."<br/>";
					if (syntheseTestExclusionEsp($TestPoisson,$TestCatE,$TestCatT) == "ok") {
						$cptInput ++;
						$libelleEsp = $CERow[1];
						$construitSelection .= "<td>&nbsp;<input  id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"".$CERow[0]."\" ".$checked."/>&nbsp;".$libelleEsp;
						// C'est super moche c'est juste pour tester la validiter de la chose, a modifier pour faire quelque chose de mieux
						$str_cptInput = strval($cptInput);
						if (fmod($str_cptInput,'3') == '0') {
							$construitSelection .= "</td></tr><tr>";
						} else {
							$construitSelection .= "</td>";
						}
					}
				}
			} // fin du while
			
			$construitSelection .="</td></tr></table>";
			$construitSelection = "<span class=\"hint small\">".($cptInput-1)." Esp&egrave;ces disponibles (sur ".$cptEsptotal." totales pour la s&eacute;lection en cours)</span>".$construitSelection;
			$construitSelection .= "<input id=\"numEsp\" type=\"hidden\" name=\"numEsp\" value=\"".$cptInput."\"/>";
		}
		pg_free_result($SQLCEcoResult);
	}	
	
	return $construitSelection;
}

//*********************************************************************
// ExclureSelectionPoisson : Fonction de test des valeurs non-poissons par rapport a la valeur selectionnée
function ExclureSelectionPoisson($valeurATester,$listePoisson) {
	$ValeurRetournee = "ok";
	if ($listePoisson=="") {
		$ValeurRetournee = "ok";
	} else {
		// Si listepoisson = "0,1", on prend tout...
		if (!($listePoisson =="0,1")) {
			$valPoisson = explode(",",$listePoisson);
			$nbPoisson = count($valPoisson) - 1;
			$StopTest = false;
			for ($cptPoi=0 ; $cptPoi<=$nbPoisson;$cptPoi++) {
				switch ($valPoisson[$cptPoi]) {
					case "0":
						if  ($valeurATester == "1") {
							$ValeurRetournee = "ko";
							$StopTest = true;
						}
						break;
					case "1":
						if  ($valeurATester == "0") {
							$ValeurRetournee = "ko";
							$StopTest = true;
						}
						break;
					case "np":
						if  ($valeurATester == "1") {
							$ValeurRetournee = "ko";
							$StopTest = true;
						}
						break;
					case "pp":
						if  ($valeurATester == "0") {
							$ValeurRetournee = "ko";
							$StopTest = true;
						}
						break;			
				}
				if ($StopTest) {break;}
			}
		}
	}
	//echo $valeurATester." - ".$listePoisson." - ".$ValeurRetournee."<br/>";
	return $ValeurRetournee;
}

//*********************************************************************
// ExclureCategorie : Fonction de test des valeurs categories eco ou troph par rapport a la valeur selectionnée
function ExclureCategorie($valeurATester,$ListCat) {
	$ValeurRetournee = "ko";
	if ($ListCat=="") {
		$ValeurRetournee = "ok";
	} else {
		if ($valeurATester == "") {
			if (strpos($ListCat,"null") === false) {
			
			} else {
				$ValeurRetournee = "ok";
			}
		} else {
			$valCat = explode(",",$ListCat);
			$nbCat = count($valCat) - 1;
			$StopTest = false;
			for ($cptCat=0 ; $cptCat<=$nbCat;$cptCat++) {
				if ($valCat[$cptCat] == $valeurATester) {
					$ValeurRetournee = "ok";
					$StopTest = true;
				}
				if ($StopTest) {break;}
			}
		}
	}
	return $ValeurRetournee;
}

//*********************************************************************
// syntheseTestExclusionEsp : Fonction de synthes des 3 test poisson / catE / CatT
function syntheseTestExclusionEsp($TestPoisson,$TestCatE,$TestCatT) {
	$ValeurRetournee = "ok";
	if ($TestPoisson == "ko") {$ValeurRetournee = "ko";}
	if ($TestCatE == "ko") {$ValeurRetournee = "ko";}
	if ($TestCatT == "ko") {$ValeurRetournee = "ko";}
	return $ValeurRetournee;	
}

//*********************************************************************
// AfficheColonnes : Fonction pour afficher les tables / colonnes a selectionner par type de peche
function AfficheRegroupEsp($typePeche,$typeAction,$numTab,$SQLEspeces,$RegroupEsp,$RegEncours,$CreeReg) {
// Cette fonction permet de gerer les regroupements d'especes
// On crée un variable de session contenant un tableau multidimensionnel
// pour un regroupement, la colonne O contient le code et le libelle (separé par &#&), et enfin, les colonnes >1 contiennent
// les especes pour ce regroupement
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLespeces : le SQL contenant les especes sélectionnées
//*********************************************************************
// En sortie : 
// La fonction renvoie $tableau
//*********************************************************************
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	global $RegroupPourFic;
	if (!(isset($_SESSION['listeRegroup']))) {
		$_SESSION['listeRegroup'] = "";	
	}
	$ulrComp="";
	$info = "";
	// Reinitialisation des variables d'affichage
	$OptionRegroup = "";
	$labelRegroup = "";
	$OptionEspDispo = "";
	$nouveauRegroupement = "";
	$OptionRegroupCont = "";
	$labelListeRegroupt = "";
	$construitSelection = "<b>g&eacute;rez les regroupements d'esp&egrave;ces</b><br/>";
	if ($RegEncours == "" && (!($_SESSION['listeRegroup'] ==""))) {
		$RegEncours = 1;					  
	}
	// Selon le type de peches, la fonction Js n'est pas la meme.
	//$tableaudebug = print_r($_SESSION['listeRegroup']);
	switch ($typePeche) {
		case "artisanale" :
		$runfilieres = "runFilieresArt";
		break;
		case "agglomeration":
		$runfilieres = "runFilieresStat";
		break;
		case "generales":
		$runfilieres = "runFilieresStat";
		break;
	 }
	// *******************************
	// Gestion des différentes actions
	// *******************************
	//echo count($_SESSION['listeRegroup'])." ".$RegEncours."<br/>";
	// Reinitialisation des regroupements
	// Ou suppression d'un regroupement
	if (isset($_GET['suppReg'])) {
		switch ($_GET['suppReg']) {
			case "tout":
				unset($_SESSION['listeRegroup']);
				$_SESSION['listeRegroup'] = "";
				$info ="tous les regroupements ont &eacute;t&eacute; supprim&eacute;s";	
				break;
			case "EC":
				$infoReg = explode("&#&",$_SESSION['listeRegroup'][$RegEncours][1]);
				$nomRegSupp = $infoReg[1];
				unset($_SESSION['listeRegroup'][$RegEncours]);
				//$_SESSION['listeRegroup'] = "";
				$info ="regroupement ".$nomRegSupp." supprim&eacute;";
				$RegEncours = $RegEncours - 1;
				break;
		}
	}

	// Reinitialisation des especes pour un regroupement
	// Ou suppression d'une espece pour un regroupement
	if (isset($_GET['suppEsp'])) {
		switch ($_GET['suppEsp']) {
			case "tout":
					$nbListEsp = count($_SESSION['listeRegroup'][$RegEncours]);
					for ($cptEsp=2 ; $cptEsp<=$nbListEsp;$cptEsp++) {
						unset($_SESSION['listeRegroup'][$RegEncours][$cptEsp]);
					}
					// On reindexe le tableau.
					reset($_SESSION['listeRegroup'][$RegEncours]);
					$infoReg = explode("&#&",$_SESSION['listeRegroup'][$RegEncours][1]);
					$info ="toutes les esp&egrave;ces ont &eacute;t&eacute; supprim&eacute;es du regroupement ".$infoReg[1];
				break;
			case "EC":
			// Ca ne fonctionne pas comme ca devrait, il reste des blancs dans le tableau.
			// Pour l'instant, pas accessible
				if (isset($_GET['espasup'])) {
					$espVraimentSup = "";
					$EspAsupp = $_GET['espasup'];
					//echo "liste a supp = ".$EspAsupp."<br/>";
					$nbListEsp = count($_SESSION['listeRegroup'][$RegEncours]);
					for ($cptEsp=2 ; $cptEsp<=$nbListEsp;$cptEsp++) {
						//echo $nbListEsp." ".$cptEsp." ".$EspAsupp." ".$_SESSION['listeRegroup'][$RegEncours][$cptEsp]."<br/>";
						$infoEsp = explode("&#&",$_SESSION['listeRegroup'][$RegEncours][$cptEsp]);
						if (strpos($EspAsupp,$infoEsp[0]) === false) {
						} else {
							$_SESSION['listeRegroup'][$RegEncours][$cptEsp]="";
							//unset($_SESSION['listeRegroup'][$RegEncours][$cptEsp]); doesn't work as wanted..
							$espVraimentSup .= ",".$_SESSION['listeRegroup'][$RegEncours][$cptEsp];
						}
					}
					// On reindexe le tableau.
					reset($_SESSION['listeRegroup'][$RegEncours]);
					$infoReg = explode("&#&",$_SESSION['listeRegroup'][$RegEncours][1]);
					$info ="les esp&egrave;ces ".$espVraimentSup." ont &eacute;t&eacute; supprim&eacute;es du regroupement ".$infoReg[1];			
				} 
				break;
		}
	}
	// Gestion de l'ajout d'espèces dans un groupe
	// Ou création d'un groupe pour cette espece unique (garder=y)
	$garderEsp = "";
	if (isset($_GET['garder'])) {
		$garderEsp =$_GET['garder'];
	}
	if (isset($_GET['affEsp'])) {
		if( $_GET['affEsp']=="y") {
			if (isset($_GET['espAff'])) {
				$EspAAffecter = $_GET['espAff'];
				//echo "liste a ajouter = ".$EspAAffecter."<br/>";
				$ListeEsp = explode(",",$EspAAffecter);
				$nbListEsp = count($ListeEsp);	
				if (!($_SESSION['listeRegroup'] == "")) {
					$derEsp = intval(count($_SESSION['listeRegroup'][$RegEncours]))-1;
				} else {
					$derEsp = 1;
				}
				for ($cptEsp=0 ; $cptEsp<$nbListEsp;$cptEsp++) {
					$SQLReg = "select id,libelle from ref_espece where id = '".$ListeEsp[$cptEsp]."'";	
					$SQLRegResult = pg_query($connectPPEAO,$SQLReg);
					$erreurSQL = pg_last_error($connectPPEAO);
					if ( !$SQLRegResult ) {
						echo "erreur execution SQL pour ".$SQLReg." erreur complete = ".$erreurSQL."<br/>";
					//erreur
					} else { 
						if (pg_num_rows($SQLRegResult) == 0) {

						} else { 
							// On n'a qu'une seule ligne en résultat.
							$RegRow = pg_fetch_row($SQLRegResult);
							if ($garderEsp == "") {
								// On ajoute les especes au regroupement sélectionné						
								$rangEsp = intval($cptEsp+2+$derEsp); // le + 2 indique qu'on commence au rang 1 et que le rang 1 est déjà pris par le nom du regroupement
								$_SESSION['listeRegroup'][$RegEncours][$rangEsp] = $ListeEsp[$cptEsp]."&#&".$RegRow[1];
								$infoReg = explode("&#&",$_SESSION['listeRegroup'][$RegEncours][1]);
								$info ="les esp&egrave;ces ".$EspAAffecter." ont &eacute;t&eacute; ajout&eacute;es au regroupement ".$infoReg[1];
							} else {
								// On crée un regroupement par espece. On récupère le libellé
								if (!($_SESSION['listeRegroup'] == "")) {
									$rangNvReg = count($_SESSION['listeRegroup']) + 1;
								} else {
									$rangNvReg = 1;
								}
								$_SESSION['listeRegroup'][$rangNvReg][1]=$RegRow[0]."&#&".$RegRow[1];
								$_SESSION['listeRegroup'][$rangNvReg][2]=$RegRow[0]."&#&".$RegRow[1];
								$info .="Regroupement espece unique n&deg;".$rangNvReg." ".$RegRow[1]." (".$RegRow[0].") ajout&eacute;<br/>";
								$RegEncours = $rangNvReg;
							}
						}
						pg_free_result($SQLRegResult);
					}
				}
			} 
		}
	}
	// Gestion de la création d'un nouveau regroupement
	switch ($CreeReg) {
		case "y" : 
			$ulrComp="&nvReg=f";
			$gardLib = "";
			$nvNomReg = "";
			$nvCodeReg = "";
			if (isset($_GET['gard'])) {
				// On a déjà travaillé sur ce regroupement, on a voulu garder le libelle, on le precharge
				if (isset($_GET['nomReg'])) {
					$gardLib = $_GET['gard'];
					$nvNomReg = $_GET['nomReg'];
				}
			}
			if (isset($_GET['codeEC'])) {
				// On a déjà travaillé sur ce regroupement, le libelle etait vide, on precharge le code deja saisi
				$nvCodeReg = $_GET['codeEC'];
			}
			$nouveauRegroupement = "<b>cr&eacute;er un nouveau regroupement</b>
			<table id=\"CreeReg\">
				<tr><td>code&nbsp;</td><td><input id=\"codeReg\" title=\"code du regroupement\" type=\"textbox\" maxlength=\"3\" size=\"3\" value=\"".$nvCodeReg."\"/></td><tr>
				<tr><td>nom&nbsp;&nbsp;</td><td><input id=\"nomReg\" type=\"textbox\" title=\"nom du regroupement\" value=\"".$nvNomReg."\"/></td><tr>
				<tr><td colspan=\"2\">";
			$nouveauRegroupement .= "<a href=\"#\" class=\"lienReg\" onClick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','".$ulrComp."')\">cr&eacute;er regroupement</a> - <a href=\"#\" class=\"lienReg\" onClick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','')\">annuler</a></td></tr>
			</table>";
			break;
		case "f" : 
			// Creation effective du nouveau regroupement
			if (isset($_GET['nomReg'])) {
				if (isset($_GET['gard'])) {
					$gardLib = $_GET['gard'];
				}else {
					$gardLib = "";
				}
				$ajoutRegOK = true;
				$nvNomReg = $_GET['nomReg'];
				$nvCodeReg = strtoupper ($_GET['codeReg']);
				if (!($_SESSION['listeRegroup'] =="" )) {
					// on controle que le groupe n'existe pas deja dans les regroupements déjà saisis.
					$NbReg = count($_SESSION['listeRegroup']);
					for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
						$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
						if ($infoReg[0] == $nvCodeReg) {
							$info .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/> le groupe en cours d'ajout ".$nvNomReg." (code = <b>".$nvCodeReg."</b>) existe d&eacute;j&agrave; ! Merci d'utiliser un autre code<br/>";
							$ajoutRegOK = false;
							break;
						}
					}
					if ($ajoutRegOK) {
						// On contrôle que le regroupement n'existe pas deja dans les especes. Si oui, proposer le meme label.
						// Si refus d'accepter le meme label, proposer la saisie d'un autre.						
						$SQLReg = "select id,libelle from ref_espece where id = '".$nvCodeReg."'";	
						$SQLRegResult = pg_query($connectPPEAO,$SQLReg);
						$erreurSQL = pg_last_error($connectPPEAO);
						if ( !$SQLRegResult ) {
							echo "erreur execution SQL pour ".$SQLReg." erreur complete = ".$erreurSQL."<br/>";
						//erreur
						} else { 
							if (pg_num_rows($SQLRegResult) == 0) {

							} else { 
							// On n'a qu'une seule ligne en résultat.
								$RegRow = pg_fetch_row($SQLRegResult);
								if (!(trim($RegRow[1]) == trim($nvNomReg))) {
									if (!($gardLib == "y")) {
										// On controle le libelle
										$ajoutRegOK = false;
										$info .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/> un groupe existe dans la base des esp&egrave;ces dont le libell&eacute; est :<b>".$RegRow[1]."</b>.<br/>";
										$info .="Voulez-vous garder ce libell&eacute; ?<input id=\"codeReg\" type=\"hidden\" value=\"".$RegRow[0]."\"/><input id=\"nomReg\" type=\"hidden\" value=\"".$nvNomReg."\"/>(<a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&nvReg=f&gard=y')\">[Oui]</a>&nbsp;<a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&nvReg=y&gard=n')\">[Non]</a>)";
										$info .="Si non, merci de resaisir le regroupement avec un autre code.<br/>";
									} else {
										// On garde le libellé
										$nvNomReg = $RegRow[1];
									}
								}
							}
							pg_free_result($SQLRegResult);
						}
						if ($ajoutRegOK) {
							$rangNvReg = count($_SESSION['listeRegroup']) + 1;
							$_SESSION['listeRegroup'][$rangNvReg][1]=$nvCodeReg."&#&".$nvNomReg;
							$info .="Regroupement numero ".$rangNvReg." ".$nvNomReg." (".$nvCodeReg.") ajout&eacute;<br/>";
							$RegEncours = $rangNvReg;
						}
					}
				} else {
					$ajoutRegOK = true;
					// On contrôle que le regroupement n'existe pas deja dans les especes. Si oui, proposer le meme label.
					// Si refus d'accepter le meme label, proposer la saisie d'un autre.						
					$SQLReg = "select id,libelle from ref_espece where id = '".$nvCodeReg."'";	
					$SQLRegResult = pg_query($connectPPEAO,$SQLReg);
					$erreurSQL = pg_last_error($connectPPEAO);
					if ( !$SQLRegResult ) {
						echo "erreur execution SQL pour ".$SQLReg." erreur complete = ".$erreurSQL."<br/>";
					//erreur
					} else { 
						if (pg_num_rows($SQLRegResult) == 0) {

						} else { 
						// On n'a qu'une seule ligne en résultat.
							$RegRow = pg_fetch_row($SQLRegResult);
							if (!(trim($RegRow[1]) == trim($nvNomReg))) {
								if (!($gardLib == "y")) {
									// On controle le libelle
									$ajoutRegOK = false;
									$info .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/> un groupe existe dans la base des esp&egrave;ces dont le libell&eacute; est :<b>".$RegRow[1]."</b>.<br/>";
									$info .="Voulez-vous garder ce libell&eacute; ?<input id=\"codeReg\" type=\"hidden\" value=\"".$RegRow[0]."\"/><input id=\"nomReg\" type=\"hidden\" value=\"".$nvNomReg."\"/>(<a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&nvReg=f&gard=y')\">[Oui]</a>&nbsp;<a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&nvReg=y&gard=n')\">[Non]</a>)";
									$info .="Si non, merci de resaisir le regroupement avec un autre code.<br/>";
								} else {
									// On garde le libellé
									$nvNomReg = $RegRow[1];
								}
							}
						}
						pg_free_result($SQLRegResult);
					}
					if ($ajoutRegOK) {
						$_SESSION['listeRegroup'][1][1]=$nvCodeReg."&#&".$nvNomReg;
						$RegEncours = 1;
						$info .="Regroupement num&eacute;ro 1 ".$nvNomReg." (".$nvCodeReg.") ajout&eacute;<br/>";
					}
				}
			} else {
				$info .= "erreur saisie nom <br/>";
			}
			break;
	}
	// Fin des différentes actions
	// On construit les différentes options
	// **** contruction du select pour les espèces disponibles à la sélection.
	$labelEspDispo = "esp&egrave;ces disponible &agrave; la s&eacute;lection";
	$libGarderEsp = ""; // permet de gérer des boutons pour selectionner des especes et en créer directement des groupes
	// Analayse des restrictions possibles sur le choix des especes
	if (!($_SESSION['listeEspeces'] == "")) {
		$TempSQLEspeces = $SQLEspeces;
		$SQLEspeces = "";
		$EspecesSele = explode (",",$_SESSION['listeEspeces']);
		$NumEsp = count($EspecesSele) - 1;
		for ($cptES=0 ; $cptES<=$NumEsp;$cptES++) {
			if (strpos($TempSQLEspeces,$EspecesSele[$cptES]) === false ){
	
			} else {
				// La valeur est disponible, on la met à jour
				if ($SQLEspeces == "" ) {
					$SQLEspeces = "'".$EspecesSele[$cptES]."'";
				} else {
					$SQLEspeces .= ",'".$EspecesSele[$cptES]."'";
				}
			}
		}
	} 
	$SQLReg = "select id,libelle from ref_espece where id in (".$SQLEspeces.") order by libelle";	
	$SQLRegResult = pg_query($connectPPEAO,$SQLReg);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLRegResult ) {
		echo "erreur execution SQL pour ".$SQLReg." erreur complete = ".$erreurSQL."<br/>";
	//erreur
	} else { 
		if (pg_num_rows($SQLRegResult) == 0) {
			// Erreur
			echo "pas d'especes trouvees dont le id est ".$SQLEspeces."<br/>" ;
		} else { 
			$cptEsp = 0;
			while ($RegRow = pg_fetch_row($SQLRegResult) ) {
				if ($_SESSION['listeRegroup'] == "" ) {
					if (!($RegRow[0] =="" || $RegRow[0] == null)) {
						$OptionEspDispo .= "<option value=\"".$RegRow[0]."\">".$RegRow[1]."</option>";
						$cptEsp ++;
					} 
				} else {
					$pasAjoutEsp = false;
					// On regarde si l'espece est déja dans un groupe. Si oui, on ne l'affiche pas.
					$NbReg = count($_SESSION['listeRegroup']);
					for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
						$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
						for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
							$infoEsp = explode("&#&",$_SESSION['listeRegroup'][$cptR][$cptR2]);
							//echo $infoEsp[0]." - ".$RegRow[0]."<br/>";
							if ($infoEsp[0] == $RegRow[0]) {
								$pasAjoutEsp = true;
							}
						}
					}
					if (!($pasAjoutEsp)) {
						$OptionEspDispo .= "<option value=\"".$RegRow[0]."\">".$RegRow[1]."</option>";
						$cptEsp ++;
					}
				}
			}
			$libGarderEsp = "<a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&affEsp=y&garder=y')\" title=\"garder les esp&egrave;ces s&eacute;lectionn&eacute;es comme groupe\">garder ces esp&egrave;ces </a>";
		}
	pg_free_result($SQLRegResult);		
	}
	// Fin de la liste des especes disponible à la sélection
	
	// **** contruction du select pour les regroupements disponibles.
	if ($_SESSION['listeRegroup'] == "" ) {
		$NbReg = 0;
		$labelRegroup = "aucun regroupement cr&eacute;&eacute;";
	} else {
		$NbReg = count($_SESSION['listeRegroup']);
		$labelRegroup = $NbReg." regroupements disponibles";
	}
	// Le onlclick sur le regroupement permet d'afficher les especes de ce regroupement
	$OptionRegroup ="liste des regroupements<br/><select id=\"Regroupement\" class=\"level_select\" size=\"10\" style=\"min-width: 10em;\" name=\"Regroupement\"> \">";
	// Remplissage de la liste des regroupements
	if ($NbReg > 0) {
		for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
			if ($RegEncours == $cptR) {
				$selected = "selected =\"selected\"";
			} else {
				$selected = "";
			}
			$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
			$OptionRegroup .= "<option value=\"".$cptR."\" ".$selected." onClick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&regRec=change') \">".$infoReg[1]."</option>";
		}
	} else {
		$OptionRegroup .= "<option disabled=\"disabled\">pas de regroupement disponible</option>";
	}
	$OptionRegroup .="</select><br/>";
	// Ajout des options de création / suppression
	$OptionRegroup .=$labelRegroup."<br/><a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&nvReg=y')\" title=\"ajouter un regroupement\">ajouter</a> - <a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppReg=EC')\" title=\"supprimer le regroupement\">supprimer </a> <br/><a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppReg=tout')\" title=\"supprimer tous les regroupements\">supprimer tous les regroupements</a> <br/>";
	
	// **** contruction du select pour afficher le contenu du regroupement en cours.	
	$selectionComp="";
	$OptionRegroupCont ="liste des esp&egrave;ces/regroupement<br/><select id=\"Regroupcontenu\" class=\"level_select\" multiple=\"multiple\" style=\"min-width: 10em;\" size=\"10\" name=\"Regroupcontenu\">";
	$labelListeRegroupt="";
	// Remplissage des especes pour ce groupement
	if ($NbReg > 0 ) {
		for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
			if ($RegEncours == $cptR) {
				$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
		
				if ($NbReg2 >=2) {
					for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
						$nbrEspeceReg = $cptR2- 1;
						$infoEsp = explode("&#&",$_SESSION['listeRegroup'][$cptR][$cptR2]);
						$OptionRegroupCont .= "<option value=\"".$infoEsp[0]."\">".$infoEsp[1]."</option>";
					}
					//$selectionComp = "<br/>".$nbrEspeceReg." esp&egrave;ces pour le regroupement s&eacute;lectionn&eacute<br/> supprimer : <a href=\"#\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppEsp=EC')\" title=\"supprimer l'esp&egrave;ce s&eacute;lectionn&eacute;e\">s&eacute;lection</a> - <a href=\"#\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppEsp=tout')\" title=\"supprimer toutes les esp&egrave;ces\">tout</a> <br/>";
					$selectionComp = "<br/>".$nbrEspeceReg." esp&egrave;ces pour le regroupement s&eacute;lectionn&eacute<br/><a href=\"#\" class=\"lienReg\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppEsp=tout')\" title=\"supprimer toutes les esp&egrave;ces\">supprimer toutes les esp&egrave;ces</a> <br/>";
					break;
				} else {
					$OptionRegroupCont .= "<option disabled=\"disabled\">pas d'esp&egrave;ces associ&eacute;es</option>";
					$selectionComp = "<br/>Pas d'esp&egrave;ces pour ce regroupement";
					$info .="s&eacute;lectionnez une esp&egrave;ce dans la troisi&egrave;me colonne et cliquez sur <-- pour l'affecter &agrave; ce regroupement";
					break;
				}
			}
		}	
	} else {
		$OptionRegroupCont .= "<option disabled=\"disabled\">pas d'esp&egrave;ces associ&eacute;es</option>";
	}
	$OptionRegroupCont .="</select>".$selectionComp;
	
	// **** Fin de la gestion de la liste des regroupements.
	// Gestion des icones (quand il y en aura) pour deplacer une especes dans un regroupement ou l'enlever
	if (!($info == "")) { 
		$info = "<span id=\"infoSuppReg\">".$info."</span>";
	}
	if ($_SESSION['listeRegroup'] =="" ) {
		$AffAffection="";
	} else {
		//$AffAffection="<div id=\"gereAffectation\" class=\"level_div\"><br/><br/><br/><a href=\"#\" class=\"lienReg2\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&affEsp=y')\" title=\"ajouter l'esp&egrave;ce au regroupement\"\><--</a><br/><br/><br/><a href=\"#\" class=\"lienReg2\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppEsp=EC')\" title=\"supprimer l'esp&egrave;ce du regroupement\"\>--></a> </div>";
		$AffAffection="<div id=\"gereAffectation\" class=\"level_div\"><br/><br/><br/><a href=\"#\" class=\"lienReg2\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&affEsp=y')\" title=\"ajouter l'esp&egrave;ce au regroupement\"\><--</a><br/><br/><br/></div>";
	}
	// Enfin derniere etape,
	// On construit l'affichage
	// Les trois premiers div sont dans le meme bloc
	// Premier div : contient la liste des especes disponibles
	$AffListeEspecesDispo = "<div id=\"listeEspece\" class=\"reg_div\">".$labelEspDispo."<br/>
							<select id=\"especesDispo\" class=\"reg_select\" multiple=\"multiple\" size=\"10\" name=\"especesDispo\">
							".$OptionEspDispo."</select><br/>".$cptEsp." esp&egrave;ces disponibles <br/>".$libGarderEsp."</div>";
	// Deuxième div : contient la liste des regroupements
	$AffListeRegroup = "<div id=\"Regroupt\" class=\"reg_div\">".$OptionRegroup."</div>" ;
	// Troisieme div : contient la liste des especes pour le regroupement
	$AffListeRegroupCont = "<div id=\"listeRegroupt\" class=\"reg_div\">".$OptionRegroupCont."</div>" ;
	// Construction de la ligne contenant les 3 divs (on peut changer l'ordre sans impacter sur la structure de chacun des div
	if ($_SESSION['listeRegroup'] =="") {
		$infoDummies = "<span class=\"hint clear small\">Si vous n'avez jamais utilis&eacute; cette interface, vous pouvez vous r&eacute;ferer &agrave; l'aide en bas de page</span>";
	} else {
		$infoDummies = "";	
	}
	$construitSelection .= $infoDummies."<br/>".$AffListeRegroup.$AffListeRegroupCont.$AffAffection.$AffListeEspecesDispo;
	// Ligne suivante : affichage de la zone de travail et/ou des messages
	if ( (!($info == "")) || (!( $nouveauRegroupement=="") )) {
		$construitSelection .= "<div id=\"Reginfo\" class=\"clear \"><span id=\"Reginfogen\">".$info."</span><span id=\"zonetrav\">".$nouveauRegroupement."</span></div><br/>";
	} else {
		$construitSelection .="<br/>";
	}
	$construitSelection .="<div class=\"hint clear \">
	<span class=\"hint_label\">aide : </span>
	<span class=\"hint_text\">
	Pour commencer, cliquez soit sur \"Ajouter\" sous la colonne des regroupements, soit cliquez sur une esp&egrave;ce puis sur \"garder ces esp&egrave;ces\" pour cr&eacute;er un regroupement d'une seule esp&egrave;ce. <br/>Une fois le regroupement cr&eacute;&eacute;, s&eacute;lectionnez une esp&egrave;ce dans la liste des esp&egrave;ces disponibles puis cliquez sur la fl&ecirc;che <-- pour affecter cette esp&egrave;ce au regroupement s&eacute;lectionn&eacute;.<br/>
	Vous pouvez s&eacute;lectionner ou d&eacute;s&eacute;lectionner plusieurs valeurs en cliquant tout en tenant la touche \"CTRL\" (Windows, Linux) ou \"CMD\" (Mac) enfonc&eacute;e
	</span>
	</div>
	</div>";
	return $construitSelection;
}

//*********************************************************************
// AfficheColonnes : Fonction pour afficher les tables / colonnes a selectionner par type de peche
function AfficheColonnes($typePeche,$typeAction,$TableEnCours,$numTab,$ListeColonnes) {
// Cette fonction permet de construire la liste des checkboxes pour la selection des tables/colonnes à selectionner
// Pour cela, elle va lire le fichier de definition (XML)
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $typePeche : le type de peche (artisanale/experimentale)
// $typeAction : la filere en cours
// $TableEnCours : la table en cours d'affichage
// $numTab: le numéro du tab en cours
// $ListeColonnes : la liste des colonnes deja cochées
//*********************************************************************
// En sortie : 
// La fonction renvoie $tableau
//*********************************************************************
	global $ListeTable;
	global $ListeChampTableDef ;
	global $ListeChampTableFac ;
	global $TableATester ;
	global $Filiere ;
	global $FiliereEnCours ;
	global $TypePecheEnCours;
	global $NumChampDef;	
	global $NumChampFac;
	global $TabEnCours;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	if ($TableEnCours=="") {$TableEnCours = "Pays";}
	//if ($EcrireLogComp ) {
	//	WriteCompLog ($logComp, "DEBUG : liste colonnes dans  affichescolonnees = ".$ListeColonnes,$pasdefichier);
	//}	
	$inputNumFac = "";
	$inputNumDef = "";
	$inputListeTable = "";
	// Fichier à analyser
	$TableATester = $TableEnCours;
	$FiliereEnCours = $typeAction;
	$TypePecheEnCours = $typePeche;
	$ListeChampTableDef = "";
	$ListeChampTableFac = "";
	// Selon le type de peches, la fonction Js n'est pas la meme.
	switch ($typePeche) {
		case "artisanale" :
		$runfilieres = "runFilieresArt";
		break;
		case "experimentale":
		$runfilieres = "runFilieresExp";
		break;
		case "agglomeration":
		$runfilieres = "runFilieresStat";
		break;
		case "generales":
		$runfilieres = "runFilieresStat";
		break;
	}
	$TabEnCours = $numTab;
	$fichiercolonne = $_SERVER["DOCUMENT_ROOT"]."/conf/ExtractionDefColonnes.xml";
	// Appel à la fonction de création et d'initialisation du parseur
	if (!(list($xml_parser_col, $fp) = new_xml_parser_Colonnes($fichiercolonne,"un"))){ 
		die("Impossible d'ouvrir le document XML"); 
	}
	// Traitement de la ressource XML
	while ($data = fread($fp, 4096)){
		if (!xml_parse($xml_parser_col, $data, feof($fp))){
			die(sprintf("Erreur XML : %s à la ligne %d<br/>",
			xml_error_string(xml_get_error_code($xml_parser_col)),
			xml_get_current_line_number($xml_parser_col)));
		}
	}
	// Libération de la ressource associée au parser
	xml_parser_free($xml_parser_col);
	if ($ListeChampTableFac == "") {
		$ContenuChampTableFac = ""; // ca ne devrait plus etre le cas !!! 
	} else {
		$ContenuChampTableFac = "liste des colonnes export&eacute;es pour <b>".$TableEnCours."</b><br/><span class=\"hints_small\">vous pouvez les s&eacute;lectionner en les cochant quand elles ne sont pas gris&eacute;es </span><br/><br/><table id=\"colonneSel\"><tr><td class=\"colitem\">".$ListeChampTableFac."</td></tr></table><br/>";
	}
	$inputTableEC = "<input type=\"hidden\" id=\"tableEC\" value=\"".$TableEnCours."\"/>";
	$inputNumDef = "<input type=\"hidden\" id=\"numDef\" value=\"".$NumChampDef."\"/>";
	$inputNumFac = "<input type=\"hidden\" id=\"numFac\" value=\"".$NumChampFac."\"/>";
	$InputTout = "";
	if (strpos($ListeColonnes,"XtoutX") === false) {
		$InputTout = "<input id=\"facTout\" type=\"checkbox\"  name=\"fac0\" value=\"tout\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','tout','','','')\" />&nbsp;tout<br/>";
	} else {
		$InputTout = "<input id=\"facTout\" type=\"checkbox\"  name=\"fac0\" value=\"tout\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','aucun','','','')\" checked=\"checked\" />&nbsp;tout<br/>";
	}
	
	$tableau = $InputTout."<table class=\"ChoixChampComp\"><tr><td class=\"CCCTable\">&nbsp;".$ListeTable." </td><td class=\"CCCChamp\">";
	//if($ListeChampTableDef =="") {
		$tableau .=	$ContenuChampTableFac."</td></tr></table>".$inputTableEC.$inputNumFac.$inputNumDef;
	//} else {
	//	$tableau .=	"colonnes par d&eacute;faut : <br/>".$ListeChampTableDef."<br/>".$ContenuChampTableFac."</td></tr></table>".$inputTableEC.$inputNumFac.$inputNumDef;	
	//}
	return $tableau; 
}

//*********************************************************************
// recupereTouteColonnes : Fonction qui recupère l'ensemble de colonnes
function recupereTouteColonnes ($typePeche,$typeAction) {
// Cette fonction permet de recupérer l'ensemble des colonnes pour toutes les tables quand l'option tout a été cochée
// Pour cela, elle va lire le fichier de definition (XML)
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $typePeche : le type de peche ou de stats(artisanale/experimentale)
// $typeAction : le type de filiere
//*********************************************************************
// En sortie : 
// La fonction renvoie $tableau
//*********************************************************************	
	global $FiliereEnCours ;
	global $TypePecheEnCours;
	global $ListeToutesValeurs;
	$ListeToutesValeurs = "";
	$FiliereEnCours = $typeAction;
	$TypePecheEnCours = $typePeche;
	$fichiercolonne = $_SERVER["DOCUMENT_ROOT"]."/conf/ExtractionDefColonnes.xml";
	// Appel à la fonction de création et d'initialisation du parseur
	if (!(list($xml_parser_col, $fp) = new_xml_parser_Colonnes($fichiercolonne,"tout"))){ 
		die("Impossible d'ouvrir le document XML"); 
	}
	// Traitement de la ressource XML
	while ($data = fread($fp, 4096)){
		if (!xml_parse($xml_parser_col, $data, feof($fp))){
			die(sprintf("Erreur XML : %s à la ligne %d<br/>",
			xml_error_string(xml_get_error_code($xml_parser_col)),
			xml_get_current_line_number($xml_parser_col)));
		}
	}
	// Libération de la ressource associée au parser
	xml_parser_free($xml_parser_col);
	return $ListeToutesValeurs;
}

//*********************************************************************
// recupereTouteColonnes : Fonction qui analyse la colonne en cours et complete le SQL si besoin
function analyseColonne($typePeche,$typeAction,$tableStat){
// Cette fonction permet de recupérer controler si la colonne en cours necessite une requete SQL supplementaire et l'ajoute 
// cas echeant
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $typePeche : le type de peche ou de stats(artisanale/experimentale)
// $typeAction : le type de filiere
// $tableStat : uniquement pour les tables de statistiques.
//*********************************************************************
// En sortie : 
// La fonction renvoie un message d'erreur ou non
// La fonction met a jour les variables globales :	$listeChampsSel, $ListeTableSel, $AjoutWhere;
//*********************************************************************	
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	global $listeChampsSel;
	global $ListeTableSel;
	global $AjoutWhere;
	global $LeftOuterJoin;
	
	$CR="ok";
	if (!($_SESSION['listeColonne'] =="")){
		// On gere deux cas distincts
		// Les stats pour lesquelles c'est tout ou rien.
		// L'extration PecheArt PecheExp pour lesquelles on gere table par table.
		if ($typePeche == "statistiques"){
			if ($_SESSION['listeColonne'] == "XtoutX") {
				switch ($tableStat) {
					case "ast":
						$listeChampsSel = ",agg.art_type_agglomeration_id,ast.nbre_obs, ast.obs_min,ast.obs_max, ast.pue_ecart_type,ast.fpe,ast.nbre_unite_recensee_periode,ast.nbre_jour_activite,ast.nbre_jour_enq_deb";
						break;	
					case "asp":
						$listeChampsSel = ",asp.nbre_enquete_sp, asp.obs_sp_min,asp.obs_sp_max, asp.pue_sp_ecart_type";
					break;
					case "ats":
						$listeChampsSel = "";
					break;
					case "asgt":
						$listeChampsSel = ",asgt.nbre_enquete_gt, asgt.obs_gt_min, asgt.obs_gt_max, asgt.pue_gt_ecart_type,asgt.fpe_gt";
					break;
					case "attgt":
						$listeChampsSel = ",attgt.nbre_enquete_gt_sp,attgt.obs_gt_sp_min, attgt.obs_gt_sp_max,attgt.pue_gt_sp_ecart_type";
					break;
					case "atgts":
						$listeChampsSel = "";
					break;
				}	
			} 
		} else {
			$champSel = explode(",",$_SESSION['listeColonne']);
			// On va completer les champs si on a tout selectionné.
			if (strpos($_SESSION['listeColonne'],"toutX") > 0) {
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "INFO : liste complete a construire",$pasdefichier);
				} 
				$toutesColonnes = recupereTouteColonnes($typePeche,$typeAction);
				//echo "toute colonnes = ".$toutesColonnes."<br/>";
				$champSel = explode(",",$toutesColonnes);
			}  
			$nbrSel = count($champSel)-1;
			for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
				$TNomLongTable ="";
				if (($champSel[$cptSel] == "XtoutX") || ($champSel[$cptSel] == "XpasttX")) {
					continue ;
				}
				if (strpos($champSel[$cptSel],"-N") === false  ) { // On ne traite pas les colonnes décochées, ni le choix tout / pas tout
					if ( strpos($champSel[$cptSel],"-X") === false ) {
						$valTest = $champSel[$cptSel];
					} else {
						$valTest = substr($champSel[$cptSel],0,-2);
					}
					// Extraction de l'alias de la variable correspondant au champ coché
					$PosDas = strpos($valTest,"-");
					$TNomTable = substr($valTest,0,$PosDas);
					// Traitement de cas particulier ou on ajoute des champs supplémentaires
					switch ($typePeche) {
						case "experimentale" :
								case "cate" : 
								if ($typeAction=="NtPart") {
									if (strpos($listeChampsSel,"cate.id") === false) {
										$listeChampsSel .= ",cate.id";
									}
								}
								break;
								case "catt" : 
								if ($typeAction=="NtPart") {
									if (strpos($listeChampsSel,"catt.id") === false) {
										$listeChampsSel .= ",catt.id";
									}
								}
						break;
						case "artisanale" :	
							switch ($TNomTable) {
								case "cate" : 
								if ($typeAction=="NtPart") {
									if (strpos($listeChampsSel,"cate.id") === false) {
										$listeChampsSel .= ",cate.id";
									}
								}
								break;
								case "catt" : 
								if ($typeAction=="NtPart") {
									if (strpos($listeChampsSel,"catt.id") === false) {
										$listeChampsSel .= ",catt.id";
									}
								}
								break;
								case "gte" :
								if ($typeAction=="activite") {
									if (strpos($listeChampsSel,"act.art_grand_type_engin_id") === false) {
										$listeChampsSel .= ",act.art_grand_type_engin_id";
									}
								}
								if ($typeAction=="capture" || $typeAction=="NtPart" || $typeAction=="taillart") {
									if (strpos($listeChampsSel,"deb.art_grand_type_engin_id") === false) {
										$listeChampsSel .= ",deb.art_grand_type_engin_id";
									}
								}
								break;
							}
							break;
					}					
					// Ajout du champ en cours dans la liste des champs supplémentaires
					$listeChampsSel .= ",".str_replace("-",".",$valTest);
					switch ($typePeche) {
						case "experimentale" :						
							switch ($TNomTable) {
								// On peut avoir des valeurs null, on y met un left outer join	
								// On le fait que si on n'a pas mis de remplissage deja dedans car la selection de remplissage arrive avant.
								// si l'ordre est changé, ce test doit etre mis a jour (ou non !)
								case "cate" : 	
									if (strpos($LeftOuterJoin,"cate.id = esp.ref_categorie_ecologique_id") === false ) {
										$LeftOuterJoin .= ",(ref_espece as esp left outer join ref_categorie_ecologique as cate on cate.id = esp.ref_categorie_ecologique_id) left outer join ref_categorie_trophique as catt on catt.id = esp.ref_categorie_trophique_id";
									}
									break;
								case "catt" :
									if (strpos($LeftOuterJoin,"catt.id = esp.ref_categorie_trophique_id") === false ) {
										$LeftOuterJoin .= ",(ref_espece as esp left outer join ref_categorie_ecologique as cate on cate.id = esp.ref_categorie_ecologique_id) left outer join ref_categorie_trophique as catt on catt.id = esp.ref_categorie_trophique_id";
									}			
									break;
								case "ord" :
									$TNomLongTable = "ref_ordre";	
									$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = fam.".$TNomLongTable."_id "); 		
									break;	
								case "xsed" :
									if (strpos($LeftOuterJoin,"xdeb.id = stat.exp_debris_id") == false ) {
										$LeftOuterJoin .= ",(((exp_station as stat left outer join exp_vegetation as xveg on xveg.id = stat.exp_vegetation_id) left outer join exp_debris as xdeb on  xdeb.id = stat.exp_debris_id) left outer join exp_sediment as xsed  on xsed.id = stat.exp_sediment_id) left outer join exp_position as xpos  on xpos.id = stat.exp_position_id";
									} 
									//$TNomLongTable = "exp_sediment";	
									//$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									//$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = stat.".$TNomLongTable."_id "); 		
									break;	
								case "efc" :
									$LeftOuterJoin .= ",(exp_environnement as env left outer join exp_force_courant as efc  on efc.id = env.exp_force_courant_id) left outer join exp_sens_courant as xssc  on xssc.id = env.exp_sens_courant_id";
									//$TNomLongTable = "exp_force_courant";	
									//$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									//$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = env.".$TNomLongTable."_id "); 		
									break;
								case "xremp" :
									$LeftOuterJoin .= ",((exp_biologie as bio left outer join exp_sexe as xsex  on xsex.id = bio.exp_sexe_id) left outer join exp_remplissage as xremp  on xremp.id = bio.exp_remplissage_id) left outer join exp_stade as xsta  on xsta.id = bio.exp_stade_id";		
									break;
								case "xpos" :
									if (strpos($LeftOuterJoin,"xdeb.id = stat.exp_debris_id") == false ) {
										$LeftOuterJoin .= ",(((exp_station as stat left outer join exp_vegetation as xveg on xveg.id = stat.exp_vegetation_id) left outer join exp_debris as xdeb on  xdeb.id = stat.exp_debris_id) left outer join exp_sediment as xsed  on xsed.id = stat.exp_sediment_id) left outer join exp_position as xpos  on xpos.id = stat.exp_position_id";
									} 
									//$TNomLongTable = "exp_position";	
									//$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									//$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = stat.".$TNomLongTable."_id "); 		
									break;
								case "xsta" :
									if (strpos($LeftOuterJoin,"xremp.id = bio.exp_remplissage_id") == false ) {
										$LeftOuterJoin .= ",((exp_biologie as bio left outer join exp_sexe as xsex  on xsex.id = bio.exp_sexe_id) left outer join exp_remplissage as xremp  on xremp.id = bio.exp_remplissage_id) left outer join exp_stade as xsta  on xsta.id = bio.exp_stade_id";
									}		
									break;
								case "xssc" :
									if (strpos($LeftOuterJoin,"efc.id = env.exp_force_courant_id") == false ) {
										$LeftOuterJoin .= ",(exp_environnement as env left outer join exp_force_courant as efc  on efc.id = env.exp_force_courant_id) left outer join exp_sens_courant as xssc  on xssc.id = env.exp_sens_courant_id";
									}
									//$TNomLongTable = "exp_sens_courant";	
									//$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									//$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = env.".$TNomLongTable."_id "); 		
									break;
								case "xsex" :
									if (strpos($LeftOuterJoin,"xsta.id = bio.exp_stade_id") == false ) {
										$LeftOuterJoin .= ",((exp_biologie as bio left outer join exp_sexe as xsex  on xsex.id = bio.exp_sexe_id) left outer join exp_remplissage as xremp  on xremp.id = bio.exp_remplissage_id) left outer join exp_stade as xsta  on xsta.id = bio.exp_stade_id";
									} 		
									break;								
								case "xveg" :
									if (strpos($LeftOuterJoin,"xdeb.id = stat.exp_debris_id") == false ) {
										$LeftOuterJoin .= ",(((exp_station as stat left outer join exp_vegetation as xveg on xveg.id = stat.exp_vegetation_id) left outer join exp_debris as xdeb on  xdeb.id = stat.exp_debris_id) left outer join exp_sediment as xsed  on xsed.id = stat.exp_sediment_id) left outer join exp_position as xpos  on xpos.id = stat.exp_position_id";
									}
									break;
								case "xdeb" :
									if (strpos($LeftOuterJoin,"xdeb.id = stat.exp_debris_id") == false ) {
										$LeftOuterJoin .= ",(((exp_station as stat left outer join exp_vegetation as xveg on xveg.id = stat.exp_vegetation_id) left outer join exp_debris as xdeb on  xdeb.id = stat.exp_debris_id) left outer join exp_sediment as xsed  on xsed.id = stat.exp_sediment_id) left outer join exp_position as xpos  on xpos.id = stat.exp_position_id";
									} 	
									break;								
						} // fin du switch ($TNomTable) 
							break;
						case "artisanale" :
							$LeftOuterJoinDeb = ",(((((art_debarquement as deb left outer join art_grand_type_engin as gte on gte.id = deb.art_grand_type_engin_id) left outer join art_type_sortie as atsor on atsor.id = deb.art_type_sortie_id) left outer join art_lieu_de_peche as alieup on alieup.id = deb.art_lieu_de_peche_id) left outer join art_vent as avent on avent.id = deb.art_vent_id) left outer join art_millieu as amil on amil.id = deb.art_millieu_id) left outer join art_etat_ciel as aetatc on aetatc.id = deb.art_etat_ciel_id";
							$LeftOuterJoinAct = ",(((((art_activite as act left outer join art_grand_type_engin as gte on gte.id = act.art_grand_type_engin_id) left outer join art_type_sortie as atsor on atsor.id = act.art_type_sortie_id) left outer join art_engin_activite as aenga on aenga.art_activite_id = act.id ) left outer join art_type_engin as teng on teng.id = aenga.art_type_engin_id) left outer join art_type_activite as tact on tact.id = act.art_type_activite_id) left outer join art_millieu as amil on amil.id = act.art_millieu_id";
							switch ($TNomTable) {
								case "debrec" : 	
									$TNomLongTable = "art_debarquement_rec";	
									$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".art_debarquement_id = deb.id ");
									break;
								case "afrarec" : 	
									$TNomLongTable = "art_fraction_rec";	
									$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = afra.id ");
									break;
								case "cate" : 
									if (strpos($LeftOuterJoin,"cate.id = esp.ref_categorie_ecologique_id") === false ) {
										$LeftOuterJoin .= ",(ref_espece as esp left outer join ref_categorie_ecologique as cate on cate.id = esp.ref_categorie_ecologique_id) left outer join ref_categorie_trophique as catt on catt.id = esp.ref_categorie_trophique_id";
									}
									break;
								case "catt" :
									if (strpos($LeftOuterJoin,"catt.id = esp.ref_categorie_trophique_id") === false ) {
										$LeftOuterJoin .= ",(ref_espece as esp left outer join ref_categorie_ecologique as cate on cate.id = esp.ref_categorie_ecologique_id) left outer join ref_categorie_trophique as catt on catt.id = esp.ref_categorie_trophique_id";
									}		
									break;
								case "ord" :
									$TNomLongTable = "ref_ordre";	
									// On teste si on a choisi aussi d'afficher la famille. Si non, il faut ajouter la requete.
									if (strpos($_SESSION['listeColonne'],"fam-") === false) {
										if (strpos($ListeTableSel,"ref_famille") === false) {
											$ajoutFam = " ,ref_famille as fam";
											$ajoutWhereFam = "and ref_famille.id = esp.ref_espece_id ";
										} else {
											$ajoutFam = "";
											$ajoutWhereFam = "";	
										}
									} else {
										$ajoutFam = "";
										$ajoutWhereFam = "";								
									}
									$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									$ListeTableSel .= $ajoutFam;
									$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = fam.".$TNomLongTable."_id ");
									$AjoutWhere .= $ajoutWhereFam; 		
									break;	
								case "fam" :
									$TNomLongTable = "ref_famille";
									if (strpos($ListeTableSel,"ref_famille") === false) {
										$ListeTableSel = ajoutAuTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									}
									$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id "); 		
									break;
								case "acsp" :
									$LeftOuterJoin .= ",art_unite_peche as upec left outer join art_categorie_socio_professionnelle as acsp on acsp.id = upec.art_categorie_socio_professionnelle_id";
									break;
								case "aengp" :
									if (strpos($LeftOuterJoin,"gte.id = deb.art_grand_type_engin_id") === false ) {
										$LeftOuterJoin .= "(".$LeftOuterJoinDeb.") left outer join art_engin_peche as aengp on aengp.art_debarquement_id = deb.id";
									} else {
										$LeftOuterJoin .= "art_debarquement as deb left outer join art_engin_peche as aengp on aengp.art_debarquement_id = deb.id";
									}
 		
									break;
								case "aetatc" :
									if (strpos($LeftOuterJoin,"gte.id = deb.art_grand_type_engin_id") === false ) {
										$LeftOuterJoin .= $LeftOuterJoinDeb;
									}

									break;								
								case "amil" :
									if ($typeAction=="activite") {
										if (strpos($LeftOuterJoin,"gte.id = act.art_grand_type_engin_id") === false ) {
											$LeftOuterJoin .= $LeftOuterJoinAct;
										}
									} else {
										if (strpos($LeftOuterJoin,"gte.id = deb.art_grand_type_engin_id") === false ) {
											$LeftOuterJoin .= $LeftOuterJoinDeb;
										}
									}
									break;
								case "alieup" :
									if (strpos($LeftOuterJoin,"gte.id = deb.art_grand_type_engin_id") === false ) {
										$LeftOuterJoin .= $LeftOuterJoinDeb;
									}
									break;
								case "atsor" :
									if ($typeAction=="activite") {
										if (strpos($LeftOuterJoin,"gte.id = act.art_grand_type_engin_id") === false ) {
											$LeftOuterJoin .= $LeftOuterJoinAct;
										}
									} else {
										if (strpos($LeftOuterJoin,"gte.id = deb.art_grand_type_engin_id") === false ) {
											$LeftOuterJoin .= $LeftOuterJoinDeb;
										}
									}
									break;
								case "avent" :
									if (strpos($LeftOuterJoin,"gte.id = deb.art_grand_type_engin_id") === false ) {
										$LeftOuterJoin .= $LeftOuterJoinDeb;
									}
									break;									
								case "gte" :
									if ($typeAction=="activite") {
										if (strpos($LeftOuterJoin,"gte.id = act.art_grand_type_engin_id") === false ) {
											$LeftOuterJoin .= $LeftOuterJoinAct;
										}
									}
									if ($typeAction=="capture" || $typeAction=="NtPart") {
										if (strpos($LeftOuterJoin,"gte.id = deb.art_grand_type_engin_id") === false ) {
											$LeftOuterJoin .= $LeftOuterJoinDeb;
										}
									}
									break;
								case "teng" :
									if ($typeAction=="activite") {
										if (strpos($LeftOuterJoin,"gte.id = act.art_grand_type_engin_id") === false ) {
											$LeftOuterJoin .= $LeftOuterJoinAct;
										}
									}
									break;
								case "tagg" :
									$LeftOuterJoin .= ",art_agglomeration as agg left outer join art_type_agglomeration as tagg on tagg.id = agg.art_type_agglomeration_id";		
									break;
								case "tact" :
									if (strpos($LeftOuterJoin,"gte.id = act.art_grand_type_engin_id") === false ) {
											$LeftOuterJoin .= $LeftOuterJoinAct;
									}		
									break;
							} // fin du switch ($TNomTable)
							break;
					}// fin du switch ($typePeche)
				} // fin du if (strpos($champSel[$cptSel],"-N") === false  )
			} // fin du for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
		}// fin du if ($typePeche == "statistiques")
	} // fin du (!($_SESSION['listeColonne'] ==""))	
	return $CR;
}

//*********************************************************************
// AnaylseVarSession : Fonction qui reconstruit une variable de session
function AnaylseVarSession($ValeurATester){
// Cette fonction permet de tester si la variable de session contient la valeur à tester
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $VarSession : la variable de session
// $ValeurATester : la valeur à tester
//*********************************************************************
// En sortie : 
// La fonction renvoie $VarSession
//*********************************************************************
// Euh, est-ce encore important de garder cette fonction ????? C'est un peu cretin comme truc. N'a d'interet que si on teste quelque chose..... A REVOIR ET/OU A VIRER
// On reconstruit les valeurs pour la variable de session
	$VarSession = "";
	$colRecues = explode (",",$ValeurATester);
	$NumColR = count($colRecues) - 1;
	for ($cptCR=0 ; $cptCR<=$NumColR;$cptCR++) {
		if ($VarSession == "") {
			$VarSession = $colRecues[$cptCR] ;
		} else {
			$VarSession .= ",".$colRecues[$cptCR];
		}
	}
	return $VarSession;
}

//*********************************************************************
// ouvreFichierLog : Fonction pour ouvrir le fichier log
function ouvreFichierLog($dirLog,$fileLogComp) {
// Cette fonction permet d'ouvrir le fichier log
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $dirLog : le répertoire du fichier log
// $fileLogComp : le nom du fichier log
//*********************************************************************
// En sortie : 
// La fonction renvoie $listeSelection
//*********************************************************************
	Global $logComp;
	Global $nomLogLien;
	Global $EcrireLogComp;
	if (! file_exists($dirLog)) {
		if (! mkdir($dirLog) ) {
			$messageGen = " erreur de cr&eacute;ation du r&eacute;pertoire de log";
			echo "<b>Erreur de cr&eacute;ation du r&eacute;pertoire de log ".$dirLog."<b/><br/>" ;
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

			echo "<b>Erreur de cr&eacute;ation du fichier de log ".$nomFicLogComp." dans function ouvreFichierLog <b/><br/>" ;
			exit;		
		}
	}
}

//*********************************************************************
// RecupereEspeces : Fonction pour ouvrir le fichier log
function RecupereEspeces($SQLAexec){
// Cette fonction permet de nettoyer le SQL des especes des especes surnumeraires
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLAexec : Le SQL contenant la liste des especes en cours
//*********************************************************************
// En sortie : 
// La fonction renvoie $SQLEspeces, la liste nettoyée des doublons
//*********************************************************************
// On reconstruit la liste des especes de la sélection.
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	global $erreurProcess;
	global $resultatLecture;
	$SQLEspeces = "";
	//if ($EcrireLogComp && $debugLog) {
	//	WriteCompLog ($logComp, "Var SQLEsp = ".$SQLEsp,$pasdefichier);
	//}	

	$SQLEspResult = pg_query($connectPPEAO,$SQLAexec);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLEspResult ) { 
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "ERREUR : construction liste especes. Requete en erreur : ".$SQLEsp." (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
		} else {
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query ".$SQLEsp." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
		}
		$erreurProcess = true;
	} else {
		if (pg_num_rows($SQLEspResult) == 0) {
		// Erreur
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "Activite/debarquement vide pour recuperer les especes...",$pasdefichier);
			} else {
				$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Activite/debarquement vide pour recuperer les especes...<br/>";}
		} else {
		//echo "<b>Nbre especes pr&eacute;lectionnes = &eacute;".pg_num_rows($SQLEspResult)."</b><br/>";
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "INFO : nombre d'especes preselectionnees = ".pg_num_rows($SQLEspResult),$pasdefichier);
		}
			while ($EspRow = pg_fetch_row($SQLEspResult) ) {
				if (strpos($SQLEspeces,$EspRow[0]) === false ) {
					$SQLEspeces .= "'".$EspRow[0]."',";	
				}
			}		
		}
		pg_free_result($SQLEspResult);
	}
	$SQLEspeces	= substr($SQLEspeces,0,- 1); // pour enlever la virgule surnumeraire;
	return $SQLEspeces;
}

//*********************************************************************
// remplaceAlias : Fonction pour remplacer les alias par le nom de la table
function remplaceAlias($listeDesChamps) {
// Cette fonction permet de remplacer pour l'affichage les alias par les nom complets des tables
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $listeDesChamps : la liste des champs avec les alias
//*********************************************************************
// En sortie : 
// La fonction renvoie $listeDesChamps, la liste mise à jour avec les noms des tables
//*********************************************************************
// On reconstruit le titre en recuperant les noms de tables depuis le fichier XML
	$listeDesTitres = "";
	$listeTitre = explode(",",$listeDesChamps);
	$nbrTitre = count($listeTitre)-1;
	for ($cptT=0 ; $cptT<=$nbrTitre;$cptT++) {
		if ( $listeTitre[$cptT]=="id.UNIQUE" || $listeTitre[$cptT]=="Coeff_extrapolation" || $listeTitre[$cptT]=="Nombre_individus_mesures" || $listeTitre[$cptT]=="Effort_total") {
			if ($listeDesTitres == "") {
				$listeDesTitres = $listeTitre[$cptT];
			} else {
				$listeDesTitres .= ",".$listeTitre[$cptT];
			}			
		}else {
			$champ = explode(".",$listeTitre[$cptT]);
			$nomTableEC = $champ[0];
			$nomChampEC = $champ[1];
			//$nomTable = recupeNomTableAlias($nomTableEC);
			$nomChampTemp = recupeNomChamps($nomTableEC."-".$nomChampEC);
			if ($nomChampTemp == "inconnu") {
				$nomChampTemp = $nomChampEC;
			}
			$nomChamp = $nomChampTemp;
			if ($listeDesTitres == "") {
				$listeDesTitres = $nomChamp;
			} else {
				$listeDesTitres .= ",".$nomChamp;
			}
		}
	}
	return $listeDesTitres;
}

//*********************************************************************
// recupeNomTableAlias : Fonction pour recuperer le nom de la table
function recupeNomTableAlias($tableAlias){
	// Attention, comme cette fonction n'est pour l'instant plus utilisé, le remplissage de la variable $_SESSION['libelleTable'] a été désactivé dans extraction_xml.php
	$NbReg = count($_SESSION['libelleTable']);
	$nomTable = "inconnu";
	for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
		$tablib = explode(",",$_SESSION['libelleTable'][$cptR]);
		if(trim($tablib[1]) == trim ($tableAlias)) {
			$nomTable = $tablib[0];
			break;
		}
	}
	return $nomTable;
}

//*********************************************************************
// recupeNomChamps : Fonction pour recuperer le libelle du champs en cours defini dans le fichier de conf XML
function recupeNomChamps($ValeurATester){
	$NbReg = count($_SESSION['libelleChamp']);
	//echo $NbReg." dans libelleChamp <br/>";
	$libelleChamps = "inconnu";
	for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
		//echo $cptR." ".$_SESSION['libelleChamp'][$cptR]."<br/>";
		$tablib = explode(",",$_SESSION['libelleChamp'][$cptR]);
		if(trim($tablib[0]) == trim ($ValeurATester)) {
			$libelleChamps = $tablib[1];
			break;
		}
	}
	return $libelleChamps;
}


//*********************************************************************
// creeDirTemp : Fonction pour creer le repertoire temporaire
function creeDirTemp($dir){
	if (! file_exists($dir)) {
		if (! mkdir($dir) ) {
			$resultat = " erreur,repertoire ne peut etre cree";
		} else {
			$resultat = "ok,repertoire cree";
		}
	} else {
	$resultat = "ok,repertoire deja existant";
	}
	return $resultat;
}

//*********************************************************************
// creeDirTemp : Fonction pour creer le repertoire temporaire
function getSuffixeFicStat($ValATester) {
	$ValARetourner = "";
	Switch ($ValATester) {
		case "ast": $ValARetourner = "stat_totale";  break;
		case "asp": $ValARetourner = "stat_sp";  break;
		case "ats": $ValARetourner = "stat_sp_dft";  break;
		case "asgt": $ValARetourner = "stat_GT";  break;
		case "attgt": $ValARetourner = "stat_GT_sp";  break;
		case "atgts": $ValARetourner = "stat_Gt_sp_dft";  break;
	
	}
	return $ValARetourner;
}

?>