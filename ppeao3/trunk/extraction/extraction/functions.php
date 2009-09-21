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
$ListeChampTableDef = "";
$ListeChampTableFac = "";
$TableATester = "";
$Filiere = "";
$FiliereEnCours = "";
$TypePecheEnCours="";
$StatEnCours="";
$NomTableEnCours="";
$NumChampDef = 0;
$NumChampFac = 0;
$ListeTableInput = "";

//*********************************************************************
// ajouterAuWhere : test et ajoute
function  ajouterAuWhere($WhereEncours,$CodeAajouter) {
	if ($WhereEncours == "" ) {
		$WhereEncours = $CodeAajouter;
	} else {
		$WhereEncours .= " and ".$CodeAajouter;
	}
	return $WhereEncours;
}
//*********************************************************************
// ajoutauTableSel : test et ajoute
function  ajoutauTableSel($ListeTableSel,$TNomLongTable,$CondAAjouter) {

	if (strpos($ListeTableSel,$TNomLongTable) === false ) {
		$ListeTableSel .= $CondAAjouter;
	} 
	return $ListeTableSel;

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
	if ($_SESSION['typeSelection'] == "") {$_SESSION['typeSelection'] = $typeSelection;}	
	if ($_SESSION['typePeche'] == "") {$_SESSION['typePeche'] = $typePeche;}	
	if ($_SESSION['typeStatistiques'] == "") {$_SESSION['typeStatistiques'] = $typeStatistiques;}
		
	if ($_SESSION['SQLdateDebut'] == "") {$_SESSION['SQLdateDebut'] = $SQLdateDebut;}
	if ($_SESSION['SQLdateFin'] == "") {$_SESSION['SQLdateFin'] = $SQLdateFin;}
	$SQLPays 	= substr($SQLPays,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLSysteme	= substr($SQLSysteme,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLSecteur	= substr($SQLSecteur,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLAgg	= substr($SQLAgg,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLEngin	= substr($SQLEngin,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLGTEngin = substr($SQLGTEngin,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLCampagne = substr($SQLCampagne,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLEspeces	= substr($SQLEspeces,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLFamille = substr($SQLFamille,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLPeEnquete = substr($SQLPeEnquete,0,- 1); // pour enlever la virgule surnumeraire;
	
	if ($_SESSION['SQLPays'] == "") {$_SESSION['SQLPays'] = $SQLPays;}
	if ($_SESSION['SQLSysteme'] == "") {$_SESSION['SQLSysteme'] = $SQLSysteme;}
	if ($_SESSION['SQLSecteur'] == "") {$_SESSION['SQLSecteur'] = $SQLSecteur;}
	if ($_SESSION['SQLAgg'] == "") {$_SESSION['SQLAgg'] = $SQLAgg;}
	if ($_SESSION['SQLEngin'] == "") {$_SESSION['SQLEngin'] = $SQLEngin;}
	if ($_SESSION['SQLGTEngin'] == "") {$_SESSION['SQLGTEngin'] = $SQLGTEngin;}
	if ($_SESSION['SQLCampagne'] == "") {$_SESSION['SQLCampagne'] = $SQLCampagne;}
	if ($_SESSION['SQLFamille'] == "") {$_SESSION['SQLFamille'] = $SQLFamille;}
	if ($_SESSION['SQLPeEnquete'] == "") {$_SESSION['SQLPeEnquete'] = $SQLPeEnquete;}
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
		$listeSelection = str_replace("<b>Liste des familles</b> =","<b>Liste des familles</b> = toutes",$listeSelection);
	}	
	if ($_SESSION['SQLEspeces'] == "") {$_SESSION['SQLEspeces'] = $SQLEspeces;}
	
	if ($SQLEspeces=="") {
		$listeSelection = str_replace("<b>Liste des especes</b> =","<b>Liste des especes</b> = toutes",$listeSelection);
		// On va reconstruire cette liste plus tard dans la fonction afficherdonnees
	}
	

	return $listeSelection;

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

	// Il faut s'assurer qu'au moins une fois la fonction qui remplit ces variables de session a été lancée 
	$typeSelection 	= $_SESSION['typeSelection'];
	$typePeche		= $_SESSION['typePeche'];
	$typeStatistiques = $_SESSION['typeStatistiques'];
	$SQLPays 	= $_SESSION['SQLPays'];
	$SQLSysteme	= $_SESSION['SQLSysteme'];
	$SQLSecteur	= $_SESSION['SQLSecteur'];
	$SQLAgg		= $_SESSION['SQLAgg'];
	$SQLEngin	= $_SESSION['SQLEngin'];
	$SQLGTEngin = $_SESSION['SQLGTEngin'];
	$SQLCampagne = $_SESSION['SQLCampagne'];
	$SQLPeEnquete = $_SESSION['SQLPeEnquete']; // liste des enquetes	
	$SQLEspeces	= $_SESSION['SQLEspeces'];
	$SQLFamille = $_SESSION['SQLFamille'];
	$SQLdateDebut = $_SESSION['SQLdateDebut']; // format annee/mois
	$SQLdateFin = $_SESSION['SQLdateFin']; // format annee/mois


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
		$nomLogLien = "/extraction/extraction/fichier";
		$dirLog = $_SERVER["DOCUMENT_ROOT"].$nomLogLien;
		
		// On fait tous les tests associés
		if (! file_exists($dirLog)) {
			if (! mkdir($dirLog) ) {
				$resultatLecture .= " erreur de cr&eacute;ation du r&eacute;pertoire d'export des fichiers";
				exit;
			}
		}
	//	Controle fichiers
		$nomFicExport = $dirLog."/".date('y\-m\-d')."_".$typePeche."_".$typeAction.".txt";
		$nomFicExpLien = $nomLogLien."/".date('y\-m\-d')."_".$typePeche."_".$typeAction.".txt";
		$resultatLecture = "Le fichier de r&eacute;sultat peut &ecirc;tre consult&eacute; : <a href=\"".$nomFicExpLien."\" target=\"export\"/>".$nomFicExpLien."</a><br/><br/>";
		// On ne cree le fichier que si il n'a pas deja ete rempli !
		if (!($fichierDejaCree)) {
			$ExpComp = fopen($nomFicExport , "w+");
			if (! $ExpComp ) {
				$resultatLecture .= " erreur de cr&eacute;ation du fichier export ".$nomFicExpLien;
				exit;		
			}
		}

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
				$CatTropNull = true;
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
		for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
			switch ($champSel[$cptSel]) {
				case "0" : 
					if ($valPoisson == "") {
						$valPoisson = $champSel[$cptSel];
					} else {
						$valPoisson .= ",".$champSel[$cptSel];
					}
					break;
				case "1" : 
					if ($valPoisson == "") {
						$valPoisson = $champSel[$cptSel];
					} else {
						$valPoisson .= ",".$champSel[$cptSel];
					}
					break;
				case "pp" :
					$LabCatPois = " que les non poissons ";
					break;
				case "np":
					$LabCatPois = " que les poissons ";
					break;	
			}
		}
		$compPoisSQL =" fam.non_poisson in (".$valPoisson.") ";
	} else {
		if (!($typeAction =="environnement") && !($typeAction =="activite") && !($typeAction =="capture")){
			$LabCatPois = " tous les poissons ";
		}
	} // fin du if (!($_SESSION['listePoisson'] == ""))
	// DEBUG
	if ($EcrireLogComp && $debugLog) {
		WriteCompLog ($logComp, "INFO : Liste variable session: ",$pasdefichier);
		WriteCompLog ($logComp, "INFO : CatTrop 	= ".$_SESSION['listeCatTrop'],$pasdefichier);
		WriteCompLog ($logComp, "INFO : CatEco 		= ".$_SESSION['listeCatEco'],$pasdefichier);
		WriteCompLog ($logComp, "INFO : Poissons 	= ".$_SESSION['listePoisson'],$pasdefichier);
		WriteCompLog ($logComp, "INFO : Especes 	=".$_SESSION['listeEspeces'],$pasdefichier);
	}	
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
				$restSupp = " Qualit&eacute; limit&eacute;e à =".$_SESSION['listeQualite'];
			}
			if (!($_SESSION['listeProtocole'] == "")) {
				switch ($_SESSION['listeProtocole']) {
				case "0" : $restSupp .= " - pas restreint aux coups du protocoles ";
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
			if (!($typeAction =="environnement")){
				// Maj du libelle de la selection en tete avec les restriction CatEco CatTroph et poisson
				$restSupp .= " - ".$LabCatEco." - ".$LabCatTrop." - ".$LabCatPois." ";
			} 	else {
				$compCatEcoSQL = "";
				$compCatTropSQL ="";
				$compPoisSQL ="";
			}
			// ********** Gestion de l'affichage des colonnes sélectionnées 
			$listeChampsSel = "";
			$ListeTableSel = "";
			$WhereSel = "";
			$joinSel="";
			$AjoutWhere = "";
			// Analyse de la liste des colonnes venant des sélections précédentes, ajout de ces colonnes au fichier
			if (!($_SESSION['listeColonne'] =="")){
				$champSel = explode(",",$_SESSION['listeColonne']);
				// On va completer les champs si on a tout selectionné.
				if (strpos($_SESSION['listeColonne'],"toutX") > 0) {
				// A faire... 
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
						$listeChampsSel .= ",".str_replace("-",".",$valTest);
						// Recuperation de l'alias de la table pour obtenir le nom de la table.
						// Idealement ici, il faudrait aller taper dans le fichier XML pour recupérer le nom de la table.
						// On avoir une variable globale contenant une table de correspondance chargée une fois pour toutes
						$PosDas = strpos($valTest,"-");
						$TNomTable = substr($valTest,0,$PosDas);
						switch ($TNomTable) {
							case "cate" : 	
								$TNomLongTable = "ref_categorie_ecologique";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id ");
								break;
							case "catt" :
								$TNomLongTable = "ref_categorie_trophique";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id "); 		
								break;
							case "ord" :
								$TNomLongTable = "ref_ordre";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = fam.".$TNomLongTable."_id "); 		
								break;	
						} // fin du switch ($TNomTable) 
					}
				}
			} // fin du (!($_SESSION['listeColonne'] ==""))
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
			//echo "where sel = ".$WhereSel."<br/>";
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
						cpg.date_debut >='".$SQLdateDebut."/01' and 
						cpg.date_fin <='".$SQLdateFin."/28'".$WhereSel;
						
				$SQLEspResult = pg_query($connectPPEAO,$SQLEsp);
				$erreurSQL = pg_last_error($connectPPEAO);
				if ( !$SQLEspResult ) { 
					$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query ".$SQLEsp." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
					$erreurProcess = true;
				
				} else {
					
					if (pg_num_rows($SQLEspResult) == 0) {
					// Erreur
						$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>pas de coup de peche dispo vide...<br/>";
					} else {
						while ($EspRow = pg_fetch_row($SQLEspResult) ) {
							if (strpos($SQLEspeces,$EspRow[0]) === false ) {
								$SQLEspeces .= "'".$EspRow[0]."',";	
							}
						}		
					}				
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
				$WhereEsp = "fra.ref_espece_id in (".$SQLEspeces.") and";
				
			}
			
			// ********** PREPARATION DU SQL
			// Definition de tout ce qui est commun aux peches expérimentales
			$listeChampsCom = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom, stat.id, stat.nom, cpg.date_debut, cpg.id, cph.date_cp, cph.id, cph.protocole, cph.exp_qualite_id, cph.exp_engin_id, xeng.libelle";
			$ListeTableCom = "ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_station as stat,exp_campagne as cpg,exp_coup_peche as cph,exp_qualite as xqua,exp_engin as xeng";
			
			$WhereCom = "cpg.id = cph.exp_campagne_id and
							stat.id = cph.exp_station_id and
							sy.id = cpg.ref_systeme_id and
							".$WhereSyst."
							py.id = sy.ref_pays_id and
							se.id = stat.ref_secteur_id and
							".$WhereSect."
							cpg.date_debut >='".$SQLdateDebut."/01' and 
							cpg.date_fin <='".$SQLdateFin."/28' and
							xqua.id = cph.exp_qualite_id and
							".$WhereEngin."
							xeng.id = cph.exp_engin_id ";
			$OrderCom = "order by py.id asc,sy.id asc,cpg.date_debut asc,cph.id asc";
			// ********** CONSTRUCTION DES SQL DEFINITIFS PAR FILIERE
			switch ($typeAction) {
				case "peuplement" :
						$labelSelection = "Donn&eacute;es de peuplement ";	
						// On n'extrait que des donnéees de fraction
						// Il n'y aucune selection de colonnes supplémentaires
						// On prend tous les poissons (pas de différence poisson/non poisson
						$listeChampsSpec = ",esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,ref_espece as esp"; // attention a l'ordre pour les left outer join
						$WhereSpec = " and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id ";
						$valueCount = "cph.id" ; // pour gerer la pagination	
						$builQuery = true;					
					break;
				case "environnement" :
						$labelSelection = "Donn&eacute;es d'environnement ";
						// On n'extrait que des donnéees environnements
						// Pas de données poisson
						$listeChampsSpec = ",env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond";
						$ListeTableSpec = ",exp_environnement as env"; // attention a l'ordre pour les left outer join
						$WhereSpec = " 	and env.id = cph.exp_environnement_id ";
						$valueCount = "cph.id" ; // pour gerer la pagination						
						$builQuery = true;
					break;
				case "NtPt" :
						$labelSelection = "Donn&eacute;es NtPt ";
						// C'est un mixte entre les données peuplements et environnement + des selections de colonnes
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,ref_espece as esp";// attention a l'ordre pour les left outer join
						$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and ".$compPoisSQL;
						$valueCount = "cph.id" ; // pour gerer la pagination						
						$builQuery = true;
					break;
				case "biologie" :
						$labelSelection = "Donn&eacute;es biologiques ";
						// Construction de la liste d'individus
						// ATTENTION !!!!!! Si la liste ci-dessous est modifiée, il faut imperativement modifié la requete pour calculer le 
						// le coefficient d'extrapolation apres l'execution de la requete 
						$listeChampsSpec = ",fra.id, fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond,bio.longueur";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,exp_biologie as bio,ref_espece as esp";// attention a l'ordre pour les left outer join
						$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp." 
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and
							bio.exp_fraction_id = fra.id and ".$compPoisSQL;
						$OrderCom .= ",fra.id asc, esp.id asc ";
						$valueCount = "fra.id" ; // pour gerer la pagination						
						$builQuery = true;
					break;	
				case "trophique" :
					// Construction de la liste d'individus
						$labelSelection = "Donn&eacute;es trophiques ";
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond,bio.longueur,bio.id,trop.exp_contenu_id,bio.exp_remplissage_id,cont.libelle";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,exp_biologie as bio,exp_trophique as trop, exp_contenu as cont,ref_espece as esp";// attention a l'ordre pour les left outer join
						$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."  
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and
							bio.exp_fraction_id = fra.id and 
							trop.exp_biologie_id = bio.id 	and
							cont.id = trop.exp_contenu_id and ".$compPoisSQL;						
						$valueCount = "bio.id" ; // pour gerer la pagination
						$builQuery = true;	
					break;
					default	:	
					$labelSelection = "Coups de p&ecirc;ches ";
					$SQLfinal = "select * from ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_station as stat,exp_campagne as cpg,exp_coup_peche as cph
							where cpg.id = cph.exp_campagne_id and
							stat.id = cph.exp_station_id and
							sy.id = cpg.ref_systeme_id and
							".$WhereSyst."
							py.id = sy.ref_pays_id and
							se.id = stat.ref_secteur_id and
							".$WhereSect."
							cpg.date_debut >='".$SQLdateDebut."/01' and 
							cpg.date_fin <='".$SQLdateFin."/28'".$WhereSel;
					$SQLcountfinal = "select count(cpg.id) from ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_station as stat,exp_campagne as cpg,exp_coup_peche as cph
							where cpg.id = cph.exp_campagne_id and
							stat.id = cph.exp_station_id and
							sy.id = cpg.ref_systeme_id and
							".$WhereSyst."
							py.id = sy.ref_pays_id and
							se.id = stat.ref_secteur_id and
							".$WhereSect."
							cpg.date_debut >='".$SQLdateDebut."/01' and 
							cpg.date_fin <='".$SQLdateFin."/28'".$WhereSel; // Pour gerer la pagination
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
			$listeChampsSel = "";
			$ListeTableSel = "";
			$WhereSel = "";
			$joinSel="";
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
			if (!($_SESSION['listeColonne'] =="")){
				$champSel = explode(",",$_SESSION['listeColonne']);
				// On va completer les champs si on a tout selectionné.
				if (strpos($_SESSION['listeColonne'],"toutX") > 0) {
				// A faire... 
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
						$listeChampsSel .= ",".str_replace("-",".",$valTest);
						// Recuperation de l'alias de la table pour obtenir le nom de la table.
						// Idealement ici, il faudrait aller taper dans le fichier XML pour recupérer le nom de la table.
						// On avoir une variable globale contenant une table de correspondance chargée une fois pour toutes
						$PosDas = strpos($valTest,"-");
						$TNomTable = substr($valTest,0,$PosDas);
						switch ($TNomTable) {
							case "cate" : 	
								$TNomLongTable = "ref_categorie_ecologique";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id ");
								break;
							case "catt" :
								$TNomLongTable = "ref_categorie_trophique";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id "); 		
								break;
							case "ord" :
								$TNomLongTable = "ref_ordre";	
								// On teste si on a choisi aussi d'afficher la famille. Si non, il faut ajouter la requete.
								if (strpos($_SESSION['listeColonne'],"fam-") === false) {
									$ajoutFam = " ,ref_famille as fam";
									$ajoutWhereFam = "and ref_famille.id = esp.ref_espece_id ";
								} else {
									$ajoutFam = "";
									$ajoutWhereFam = "";								
								}
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$ListeTableSel .= $ajoutFam;
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = fam.".$TNomLongTable."_id ");
								$AjoutWhere .= $ajoutWhereFam; 		
								break;	
							case "fam" :
								$TNomLongTable = "ref_famille";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id "); 		
								break;
							case "aeng" :
								$TNomLongTable = "art_engin_peche";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = gte.".$TNomLongTable."_id "); 		
								break;
							case "teng" :
								$TNomLongTable = "art_type_engin";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".art_debarquement_id = deb.id "); 		
								break;
							case "tagg" :
								$TNomLongTable = "art_type_agglomeration";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = agg.".$TNomLongTable."_id "); 		
								break;
							case "tact" :
								$TNomLongTable = "art_type_activite";	
								$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = act.".$TNomLongTable."_id "); 		
								break;
							case "gte" :
								if ($typeAction == "activite") {
									$TNomLongTable = "art_type_agglomeration";	
									$ListeTableSel = ajoutauTableSel($ListeTableSel,$TNomLongTable, ", ".$TNomLongTable." as ".$TNomTable);
									$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = agg.".$TNomLongTable."_id "); 
								}		
								break;
						} // fin du switch ($TNomTable) 
					}
				}
			} // fin du (!($_SESSION['listeColonne'] ==""))
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
					$ajouteTable =",art_grand_type_engin as gte";
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
				$WhereEsp = "afra.ref_espece_id in (".$SQLEspeces.") and ";

			}
			// ********** PREPARATION DU SQL
			// Definition de tout ce qui est commun aux peches expérimentales
			// Il va y avoir moins de données communes que pour les peches exp car certaines dependent de la filiere acti ou deb 
			// Donc on cree des variables generales selon qu'on va traiter activite ou debarquement
			// Définition des SQL de base pour les activites (art_activite)
			$listeChampsArt = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.id,se.nom, act.art_agglomeration_id, agg.nom, act.annee, act.mois, act.date_activite, act.id,act.date_activite,upec.id";
			$ListeTableArt = "ref_pays as py,ref_systeme as sy,ref_secteur as se,art_periode_enquete as penq,art_activite as act,art_agglomeration as agg,art_unite_peche as upec";
			
			$WhereArt = "	py.id = sy.ref_pays_id and
							sy.id = se.ref_systeme_id and
							se.id = agg.ref_secteur_id and
							".$WhereSyst." ".$WhereAgg." ".$WhereSect." ".$WherePeEnq." 
							act.art_agglomeration_id = agg.id and
							act.mois = penq.mois and 
							act.annee = penq.annee and
							act.art_agglomeration_id = penq.art_agglomeration_id and
							upec.id = act.art_unite_peche_id";			
			$OrderArt = "order by py.id asc, sy.id asc, agg.nom, act.annee asc,act.mois asc";
			// Définition des SQL de base pour les débarquements (art_debarquement)
			$listeChampsDeb = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom,se.id, deb.art_agglomeration_id, agg.nom, deb.annee, deb.mois, deb.id, deb.date_debarquement";
			$ListeTableDeb = "ref_pays as py,ref_systeme as sy,ref_secteur as se,art_periode_enquete as penq,art_debarquement as deb,art_agglomeration as agg,art_unite_peche as upec,art_grand_type_engin as gte";
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
			$OrderDeb = "order by py.id asc, sy.id asc, agg.nom, deb.annee asc,deb.mois asc";
			// ********** CONSTRUCTION DES SQL DEFINITIFS PAR FILIERE
			switch ($typeAction) {
				case "activite" :
						// On considere les données d'activité. On commence par mettre à jour les varialbes communs *com
						$listeChampsCom = $listeChampsArt;
						$ListeTableCom = $ListeTableArt ;
						$WhereCom = $WhereArt ;
						$OrderCom = $OrderArt ;
						$labelSelection = "Donn&eacute;es d'activit&eacute;";	
						$listeChampsSpec = ",act.art_type_activite_id,act.nbre_unite_recencee ";
						$ListeTableSpec = ""; // attention a l'ordre pour les left outer join
						$WhereSpec = "";	
						$ConstIDunique = "ART-##-12"; // Ce qui apres le -##-n sera remplacé par la valeur d'index n de la lecture de la requete par exemple, ici, on va recuperer art.id  
						$valueCount = "act.id" ; // pour gerer la pagination				
						$builQuery = true;
					break;			
				case "capture" :
				// Liste des debarquements.
						$labelSelection = "Donn&eacute;es de capture";	
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						$OrderCom = $OrderDeb ;
						$listeChampsSpec = ", deb.poids_total";
						$ListeTableSpec = ""; // attention a l'ordre pour les left outer join
						$WhereSpec = "";
						$ConstIDunique = "DEB-##-11";						
						$builQuery = true;
					break;
				case "NtPt" :
						$labelSelection = "Donn&eacute;es NtPt";				
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						$OrderCom = $OrderDeb ;				
						$listeChampsSpec = ", deb.poids_total,afra.poids,afra.nbre_poissons ";
						$ListeTableSpec = ", art_fraction as afra,ref_espece as esp "; // attention a l'ordre pour les left outer join
						$WhereSpec = " 	and ".$WhereEsp." afra.art_debarquement_id = deb.id 
										and esp.id = afra.ref_espece_id	";					
						$ConstIDunique = "DEB-##-11";
						$valueCount = "deb.id" ; // pour gerer la pagination	
						$builQuery = true;
					break;
				case "taille" :
						$labelSelection = "Donn&eacute;es de tailles";	
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						$OrderCom = $OrderDeb ;	
						$listeChampsSpec = ", deb.poids_total,afra.poids,afra.nbre_poissons,ames.taille,esp.libelle ";
						$ListeTableSpec = ", art_fraction as afra,ref_espece as esp,art_poisson_mesure as ames"; // attention a l'ordre pour les left outer join
						$WhereSpec = " 	and ".$WhereEsp." afra.art_debarquement_id = deb.id 
										and ames.art_fraction_id = afra.id 
										and esp.id = afra.ref_espece_id	";						
						$ConstIDunique = "DEB-##-11";
						$valueCount = "deb.id" ; // pour gerer la pagination
						$builQuery = true;
					break;
				case "engin" :
						$labelSelection = "Donn&eacute;es d'engin";	
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						$OrderCom = $OrderDeb ;	
						$listeChampsSpec = ",deb.art_grand_type_engin_id, aeng.art_type_engin_id,teng.libelle";
						$ListeTableSpec = ", art_engin_peche as aeng, art_type_engin as teng"; // attention a l'ordre pour les left outer join
						$WhereSpec = " and aeng.art_debarquement_id = deb.id and teng.id = aeng.art_type_engin_id";						
						$ConstIDunique = "DEB-##-11";
						$valueCount = "deb.id" ; // pour gerer la pagination
						$builQuery = true;
					break;															
				default	:	
					$labelSelection = "Periode d'enquete";
					$SQLfinal = "select * from art_periode_enquete as penq
									where penq.id in (".$SQLPeEnquete.")";
					$SQLcountfinal = "select count(*) from art_periode_enquete as penq
									where penq.id in (".$SQLPeEnquete.")";; // pour gerer la pagination	
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
			$listeChampsSel = "";
			$ListeTableSel = "";
			$WhereSel = "";
			$joinSel="";
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

			
			switch ($typeStatistiques) {
				// *********************************************************************************
				// STATISTIQUES PAR AGGLOMERATION
				// *********************************************************************************
				case "agglomeration" :
					// ********** DEBUT STATISTIQUES PAR AGGLOMERATION
					// ********** CONSTRUCTION DES SQL DEFINITIFS PAR TYPE DE STATISTIQUES CHOISIS
					switch ($typeAction) {
						// Statistiques globales
						case "globale" :
							// Cas particulier d'aucun sélection des espèces : 
							// On reconstruit cette liste pour l'ensemble de la sélection car on va en avoir besoin
							// pour les catégories trophiques/ecologiques
							$ajouteTable ="";
							if ($SQLEspeces == "") {
								if (!($compGTESQL == "")) {
									$ajouteTable =",art_grand_type_engin as gte";
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
							}
							// Si malgré tout, toujours pas d'especes dispo, ben tant pis....
							if ($SQLEspeces == "") {
								$WhereEsp = "";
								echo "pas d'especess <br/>";
							} else {
								$WhereEsp = "afra.ref_espece_id in (".$SQLEspeces.") and ";
								$_SESSION['SQLEspeces'] = $SQLEspeces; // ca va servir pour la suite....
							}
							$labelSelection = "Periode d'enquete";
							$SQLfinal = "select * from art_periode_enquete as penq
											where penq.id in (".$SQLPeEnquete.")";						
						break;	
						// Statistiques par Grand type
						case "GT" :	
							$labelSelection = "Periode d'enquete";
							$SQLfinal = "select * from art_periode_enquete as penq
											where penq.id in (".$SQLPeEnquete.")";
						break;			
						default	:	
							$labelSelection = "Periode d'enquete";
							$SQLfinal = "select * from art_periode_enquete as penq
											where penq.id in (".$SQLPeEnquete.")";
					}									
					break; 
				// ********** FIN STATISTIQUES PAR AGGLOMERATION
				// *
				// *********************************************************************************
				// STATISTIQUES PAR AGGLOMERATION
				// *********************************************************************************
				case "generales" :
					// ********** DEBUT STATISTIQUES GENERALES
				break; 
				// ********** FIN STATISTIQUES GENERALES	
			default:
				echo "Erreur pas d'action selectionnee. Ca ne devrait pas arriver....<br/>";
				exit;
		} // fin du switch ($typeStatistiques) 
	} // fin du switch ($typeSelection) 

	// *
	// *********************************************************************************
	// EXECUTION DE LA REQUETE APRES SA CONSTRUCTION
	// *********************************************************************************
	
	// On construit (ou non) la requete finale.
	// Elle peut avoir déjà été construite précédement, notament dans les cas par defaut
	if ($builQuery) {
	//echo "<b>build query</b><br/>";
		$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
		$listeTable = $ListeTableCom.$ListeTableSel.$ListeTableSpec; // L'ordre est important pour les join
		if ($WhereSel == "") {
			$WhereTotal = $WhereCom.$WhereSpec;
		} else {
			$WhereTotal = $WhereCom.$WhereSpec." and ".$WhereSel;
		}
		
		$SQLfinal = "select ".$listeChamps." from ".$listeTable." ".$joinSel." where ".$WhereTotal ." ".$OrderCom;
		$SQLcountfinal = "select count(".$valueCount.") from ".$listeTable." ".$joinSel." where ".$WhereTotal;
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "INFO SQL en cours :".$SQLfinal,$pasdefichier);
		}
	}
	// Gestion de la pagination
	$countTotal=0; // Contient le resultat total de la requete
	//echo $SQLcountfinal."<br/>";
	// On recupere le nombre total de resultat.
	// On doit executer la requete
	$SQLcountfinalResult = pg_query($connectPPEAO,$SQLcountfinal);
	$erreurSQL = pg_last_error($connectPPEAO);
	$cpt1 = 0;
	if ( !$SQLcountfinalResult ) { 
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "WARNING : Erreur pagination pour requete ".$SQLcountfinal." (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
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
	// On gère la pagination
	//echo "<h1>nombre total de ligne = ".$countTotal."<h1><br/>";
	// on prend en compte la pagination

	/* Déclaration des variables */ 
	$rowsPerPage = 15; // nombre d'entrées à afficher par page (entries per page) 
	$countPages = ceil($countTotal/$rowsPerPage); // calcul du nombre de pages $countPages (on arrondit à l'entier supérieur avec la fonction ceil() ) 
 
	/* Récupération du numéro de la page courante depuis l'URL avec la méthode GET */ 
	if(!isset($_GET['page']) || !is_numeric($_GET['page']) ) // si $_GET['page'] n'existe pas OU $_GET['page'] n'est pas un nombre (petite sécurité supplémentaire) 
		$currentPage = 1; // la page courante devient 1 
	else { 
		$currentPage = intval($_GET['page']); // stockage de la valeur entière uniquement 
		if ($currentPage < 1) $currentPage=1; // cas où le numéro de page est inférieure 1 : on affecte 1 à la page courante 
		elseif ($currentPage > $countPages) $currentPage=$countPages; //cas où le numéro de page est supérieur au nombre total de pages : on affecte le numéro de la dernière page à la page courante 
		else $currentPage=$currentPage; // sinon la page courante est bien celle indiquée dans l'URL 
	} 
 
	/* $start est la valeur de départ du LIMIT dans notre requête SQL (est fonction de la page courante) */ 
	$startRow = ($currentPage * $rowsPerPage - $rowsPerPage);
	// on construit la requête SQL pour obtenir les valeurs de la table à afficher si il y en a
	if ($countTotal!=0) {
		// Pour pouvoir gérer la pagination, on doit séparer la requete d'affichage de la requete de creation du fichier.
		// On ne creera le fichier qu'une seule fois!
		$SQLfinalFichier = $SQLfinal; // On stocke la requete pour le fichier
		// Gestion de l'affichage
		$SQLfinal .= " LIMIT ".$rowsPerPage." OFFSET ".$startRow;
		// Execution de la requete
		$SQLfinalResult = pg_query($connectPPEAO,$SQLfinal);
		$erreurSQL = pg_last_error($connectPPEAO);
		$cpt1 = 0;
		if ( !$SQLfinalResult ) { 
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "ERREUR : Erreur query final ".$SQLfinal." (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
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
					$listeChamps ="ID UNIQUE,".$listeChamps;
				}
				if ($typeAction == "biologie") {
					// On ajoute le libelle pour le coefficient
					$listeChamps .=",Coeff_extrapolation";
				}
				// Ici, remplacer les noms des alias par le nom des tables...		
				$listeChamps = remplaceAlias($listeChamps);
				// On commence le formatage sous forme de table/
				$resultatLecture .="<table id=\"affresultat\" ><tr class=\"affresultattitre\"><td>";
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
					switch ($typeAction) {
						case "biologie" :
							// On doit calculer un coefficient d'extrapolation 
							// On execute une requete supplémentaire pour recuperer le nombre d'individu dans exp_biologie pour la fraction et l'espece considerée
							// On recupere le nombre de poissons reellement mesures pour une fraction donnée (qui elle meme correspond à 
							// une seule espece.
							$SQLcomplement = "Select count(id) from exp_biologie where exp_fraction_id =  ".$finalRow[16] ;
							$SQLcomplementResult = pg_query($connectPPEAO,$SQLcomplement);
							$erreurSQL = pg_last_error($connectPPEAO);
							if ( !$SQLcomplementResult ) { 
								if ($EcrireLogComp ) {
									WriteCompLog ($logComp, "ERREUR : Erreur query complementaire biologie ".$SQLcomplement." (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
								}							
							} else {
								$RowComplement = pg_fetch_row($SQLcomplementResult); 
								$totalBio = $RowComplement[0];
								pg_free_result($SQLcomplementResult);
							}
							// Calcul du coefficient = nombre de poisson peches / nombre de poissons mesures
							$coefficient =floatval( intval($finalRow[17]) / intval($totalBio));	
							$coefficient = round($coefficient,2);
							$nbrRow = count($finalRow)-1;
							// Transcription du resultat de la requete globale pour un affichage écran et un export sous forme de fichier

							for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
								$resultatLecture .= "<td>".$finalRow[$cptRow]."</td>";
							}
							// Ajout du coefficient tout a la fin du fichier
							$resultatLecture .= "<td>".$coefficient."</td>";
							break;	
						default	:
							$nbrRow = count($finalRow)-1;
							// Transcription du resultat de la requete globale pour un affichage écran et un export sous forme de fichier
							for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
								$resultatLecture .= "<td>".$finalRow[$cptRow]."</td>";
							}	
							
							break;
						
					}
					$resultatLecture .="</tr>";
					$cptNbRow ++;
					
				}//fin du while
				$resultatLecture .="</table>";
			}
		} // fin du !$SQLfinalResult
		pg_free_result($SQLfinalResult);

		// Gestion de creation du fichier
		if ($exportFichier && (!($fichierDejaCree))) {
			$fichierDejaCree = true;
			// Execution de la requete
			$SQLfinalResult = pg_query($connectPPEAO,$SQLfinalFichier);
			$erreurSQL = pg_last_error($connectPPEAO);
			$cpt1 = 0;
			if ( !$SQLfinalResult ) { 
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "ERREUR : Erreur creation fichier query final ".$SQLfinalFichier." (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
				}
				$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur creation fichier query ".$SQLfinalFichier." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
				$erreurProcess = true;
			} else {
				if (pg_num_rows($SQLfinalResult) == 0) {
					// Avertissement
					if ($EcrireLogComp ) {
						WriteCompLog ($logComp, "Pas de resultat disponible pour la selection ".$SQLfinalFichier,$pasdefichier);
					}
					$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Pas de resultat disponible pour la sélection (creation fichier)<br/>";
				} else {
					// Si on ajoute un identifiant unique en debut de ligne, on l'indique dans la liste des champs.
					if (!($ConstIDunique =="")) {
						$listeChamps ="ID UNIQUE,".$listeChamps;
					}
					if ($typeAction == "biologie") {
						// On ajoute le libelle pour le coefficient
						$listeChamps .=",Coeff_extrapolation";
					}
					// Ici, remplacer les noms des alias par le nom des tables...		
					$listeChamps = remplaceAlias($listeChamps);
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
						if (!($ConstIDunique =="")) {
							$Locprefixe = substr($ConstIDunique,0,3); // Attention, pour des raisons de simplicité, le sufffixe n'est que sur 3 caractères.
							$locIndex = substr(strrchr($ConstIDunique, "-##-"),1);
							$IDunique = $Locprefixe.$finalRow[$locIndex];
							$resultatFichier .= $IDunique."\t";
						}
						switch ($typeAction) {
							case "biologie" :
								// On doit calculer un coefficient d'extrapolation 
								// On execute une requete supplémentaire pour recuperer le nombre d'individu dans exp_biologie pour la fraction et l'espece considerée
								// On recupere le nombre de poissons reellement mesures pour une fraction donnée (qui elle meme correspond à 
								// une seule espece.
								$SQLcomplement = "Select count(id) from exp_biologie where exp_fraction_id =  ".$finalRow[16] ;
								$SQLcomplementResult = pg_query($connectPPEAO,$SQLcomplement);
								$erreurSQL = pg_last_error($connectPPEAO);
								if ( !$SQLcomplementResult ) { 
									if ($EcrireLogComp ) {
										WriteCompLog ($logComp, "ERREUR : Erreur query complementaire biologie ".$SQLcomplement." (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
									}							
								} else {
									$RowComplement = pg_fetch_row($SQLcomplementResult); 
									$totalBio = $RowComplement[0];
									pg_free_result($SQLcomplementResult);
								}
								// Calcul du coefficient = nombre de poisson peches / nombre de poissons mesures
								$coefficient =floatval( intval($finalRow[17]) / intval($totalBio));	
								$coefficient = round($coefficient,2);						
								$nbrRow = count($finalRow)-1;
								// Transcription du resultat de la requete globale pour un affichage écran et un export sous forme de fichier
								for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
									$resultatFichier .=$finalRow[$cptRow]."\t";
								}
								// Ajout du coefficient tout a la fin du fichier
								$resultatFichier .= $coefficient;
								break;	
							default	:
								$nbrRow = count($finalRow)-1;
								// Transcription du resultat de la requete globale pour un affichage écran et un export sous forme de fichier
								for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
									$resultatFichier .=$finalRow[$cptRow]."\t";
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
			} // fin du !$SQLfinalResult
		} // fin if ($exportFichier && (!($fichierDejaCree))
		// ********* Fin creation fichier
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

}

//*********************************************************************
// AfficheCategories : Fonction pour afficher les catégories troph / ecologiques a selectionner
function AfficheCategories($typeCategorie,$typeAction,$ListeCE,$changtAction,$typePeche,$numTab) {
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
				$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"tout\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','tout-".$nomInput."','')\"/>&nbsp;<b>Tout</b></td><td class=\"catitem\">";
			} else {
				$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"tout\" checked=\"checked\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','aucun-".$nomInput."','')\"/>&nbsp;<b>Tout</b></td><td class=\"catitem\">";
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
					if ($cptInput/3 == 1 || $cptInput/3 == 2 || $cptInput/3 == 3 || $cptInput/3 == 4 || $cptInput/3 == 5 || $cptInput/3 == 6 || $cptInput/3 == 7 || $cptInput/3 == 8 || $cptInput/3 == 9 || $cptInput/3 == 10 || $cptInput/3 == 11 ) {
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
function AfficheEspeces($SQLEspeces,$ListeEsp,$changtAction,$typePeche,$typeAction,$numTab,$regroup) {
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
	 }
// Gere l'affichage des différentes espèces
	$SQLCEco = "select id,libelle from ref_espece where id in (".$SQLEspeces.") order by id";	
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
			$construitSelection .="<table id=\"espece\"><tr><td>"; 
			if (strpos($ListeEsp,"XtoutX") === false) {
				$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"XtoutX\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','tout')\"/>&nbsp;<b>Tout</b></td><td>";
			} else {
				$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"XtoutX\" checked=\"checked\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','aucun')\"/>&nbsp;<b>Tout</b></td><td>";
			}
			while ($CERow = pg_fetch_row($SQLCEcoResult) ) {
				if (!($CERow[0] =="" || $CERow[0] == null)) {
					$cptInput ++;
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
					$libelleEsp = $CERow[1];
					$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"".$CERow[0]."\" ".$checked."/>&nbsp;".$libelleEsp;
					// C'est super moche c'est juste pour tester la validiter de la chose, a modifier pour faire quelque chose de mieux
					if ($cptInput/3 == 1 || $cptInput/3 == 2 || $cptInput/3 == 3 || $cptInput/3 == 4 || $cptInput/3 == 5 || $cptInput/3 == 6 || $cptInput/3 == 7 || $cptInput/3 == 8 || $cptInput/3 == 9 || $cptInput/3 == 10 || $cptInput/3 == 11 ) {
						$construitSelection .= "</td></tr><tr><td>";
					} else {
						$construitSelection .= "</td><td>";
					}
				}
			} // fin du while
			
			$construitSelection .="</td></tr></table>";
;
			$construitSelection .= "<input id=\"numEsp\" type=\"hidden\" name=\"numEsp\" value=\"".$cptInput ."\"/>";
		}
	}	
	pg_free_result($SQLCEcoResult);
	return $construitSelection;
}

//*********************************************************************
// AfficheColonnes : Fonction pour afficher les tables / colonnes a selectionner par type de peche
function AfficheRegroupEsp($SQLespeces,$RegroupEsp) {
// Cette fonction permet de gerer les regroupements d'especes
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLespeces : le SQL contenant les especes sélectionnées
//*********************************************************************
// En sortie : 
// La fonction renvoie $tableau
//*********************************************************************
$construitSelection = "";
$construitSelection .= "<table id=\"regroupement\"><tr id=\"titreReg\"><td>Regroupement esp&egrave;ces</td></tr>";


$construitSelection .= "</table>";
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
	if ($EcrireLogComp ) {
		WriteCompLog ($logComp, "DEBUG : liste colonnes dans  affichescolonnees = ".$ListeColonnes,$pasdefichier);
	}	
	$inputNumFac = "";
	$inputNumDef = "";
	$inputListeTable = "";
	// Fichier à analyser
	if ($TableEnCours == "") {$TableEnCours = "py";}
	$TableATester = $TableEnCours;
	$FiliereEnCours = $typeAction;
	$TypePecheEnCours = $typePeche;
	// Selon le type de peches, la fonction Js n'est pas la meme.
	switch ($typePeche) {
		case "artisanale" :
		$runfilieres = "runFilieresArt";
		break;
		case "experimentale":
		$runfilieres = "runFilieresExp";
		break;
	 }
	$TabEnCours = $numTab;
	$fichiercolonne = $_SERVER["DOCUMENT_ROOT"]."/conf/ExtractionDefColonnes.xml";
	// Appel à la fonction de création et d'initialisation du parseur
	if (!(list($xml_parser_col, $fp) = new_xml_parser_Colonnes($fichiercolonne))){ 
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
		$ContenuChampTableFac = "";
	} else {
		$ContenuChampTableFac = "Colonnes facultatives<br/>".$ListeChampTableFac."<br/>";
	}

	$inputTableEC = "<input type=\"hidden\" id=\"tableEC\" value=\"".$TableEnCours."\"/>";
	$inputNumDef = "<input type=\"hidden\" id=\"numDef\" value=\"".$NumChampDef."\"/>";
	$inputNumFac = "<input type=\"hidden\" id=\"numFac\" value=\"".$NumChampFac."\"/>";
	$InputTout = "";
	if (strpos($ListeColonnes,"XtoutX") === false) {
		$InputTout = "<input id=\"facTout\" type=\"checkbox\"  name=\"fac0\" value=\"tout\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','tout','','')\" />&nbsp;Tout<br/>";
	} else {
		$InputTout = "<input id=\"facTout\" type=\"checkbox\"  name=\"fac0\" value=\"tout\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','aucun','','')\" checked=\"checked\" />&nbsp;Tout<br/>";
	}
	
	$tableau = $InputTout."<table class=\"ChoixChampComp\"><tr><td class=\"CCCTable\">&nbsp;".$ListeTable." </td><td class=\"CCCChamp\">Colonnes par d&eacute;faut : <br/>".$ListeChampTableDef."<br/>".$ContenuChampTableFac."</td></tr></table>".$inputTableEC.$inputNumFac.$inputNumDef;
	return $tableau; 
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
// Idealement ici, il faudrait aller taper dans le fichier XML pour recupérer le nom de la table.
	// On avoir une variable globale contenant une table de correspondance chargée une fois pour toutes.
	$listeDesChamps = str_replace("py.","ref_pays.",$listeDesChamps);
	$listeDesChamps = str_replace("sy.","ref_systeme.",$listeDesChamps);
	$listeDesChamps = str_replace("se.","ref_secteur.",$listeDesChamps);	
	return $listeDesChamps;
}

?>