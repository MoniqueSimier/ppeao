<?php 
//*****************************************
// functions.php
//*****************************************
// Created by Yann Laurent
// 2009-06-30 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilis�es dans l'extraction des donn�es
//*****************************************

// Definition d'un param�tre global
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
// Cette fonction est la fonction qui analyse le ficher de s�lection et qui affiche la dite selection
// Elle permet aussi de remplir les variables SQL* qui contient la traduction en liste de variables de la s�lection 
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $file : le fichier de param�trage qui contient la s�lection issue de l'etape pr�c�dente
//*********************************************************************
// En sortie : 
// La fonction renvoie $listeSelection
//*********************************************************************
	// Donn�es pour la selection 
	global $typeSelection ;
	global $typePeche;
	global $typeStatistiques;

	global $listeGTEngin;
	// Pour construire le bandeau avec la s�lection
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

	
	// Appel � la fonction de cr�ation et d'initialisation du parseur
	if (!(list($xml_parser, $fp) = new_xml_parser($file))){ 
		die("Impossible d'ouvrir le document XML"); 
	}
	// Traitement de la ressource XML
	
	while ($data = fread($fp, 4096)){
	
		if (!xml_parse($xml_parser, $data, feof($fp))){
			die(sprintf("Erreur XML : %s � la ligne %d<br/>",
			xml_error_string(xml_get_error_code($xml_parser)),
			xml_get_current_line_number($xml_parser)));
		   }
	}
	
	// Lib�ration de la ressource associ�e au parser
	xml_parser_free($xml_parser);
	// On colle tous en variable de session, comme ca, pas de pb...
	$_SESSION['typeSelection'] = $typeSelection;
	$_SESSION['typePeche'] = $typePeche;
	$_SESSION['typeStatistiques'] = $typeStatistiques;
	$_SESSION['SQLdateDebut'] = $SQLdateDebut;
	$_SESSION['SQLdateFin'] = $SQLdateFin;
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
	
	$_SESSION['SQLPays'] = $SQLPays;
	$_SESSION['SQLSysteme'] = $SQLSysteme;
	$_SESSION['SQLSecteur'] = $SQLSecteur;
	$_SESSION['SQLAgg'] = $SQLAgg;
	$_SESSION['SQLEngin'] = $SQLEngin;
	$_SESSION['SQLGTEngin'] = $SQLGTEngin;
	$_SESSION['SQLCampagne'] = $SQLCampagne;
	$_SESSION['SQLFamille'] = $SQLFamille;
	$_SESSION['SQLPeEnquete'] = $SQLPeEnquete;
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
// AfficherDonnees : Fonction d'extraction qui affiche les donn�es
function AfficherDonnees($file,$typeAction){
// Cette fonction est la fonction principale de l'extraction qui permet de compter les resultats mais aussi de les afficher
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $file : le fichier de param�trage qui contient la s�lection issue de l'etape pr�c�dente
// $typeAction : la filere en cours
//*********************************************************************
// En sortie : 
// La fonction ne renvoie rien. Mais la variable $resultatLecture est mise � jour pour un affichage dans le script qui appelle
// cette fonction. 
//*********************************************************************
	$debugLog = true;

	// Il faut s'assurer qu'au moins une fois la fonction qui remplit ces variables de session a �t� lanc�e 
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
	// SQLEspeces contient les donn�es venant de la s�lection (ie si lors de l'�tape pr�c�dente, on a s�lectionn� des especes ou familles
	// listeEspeces contient la s�lection des especes venant des fili�res. Elle est au maximum �gale a SQLEspeces.
	// La r�f�rence pour le SQL final doit etre ListeEspeces.

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
		// On recup�re les info pour creer le fichier d'export 
		$nomLogLien = "/extraction/extraction/fichier";
		$dirLog = $_SERVER["DOCUMENT_ROOT"].$nomLogLien;
		
		// On fait tous les tests associ�s
		if (! file_exists($dirLog)) {
			if (! mkdir($dirLog) ) {
				$resultatLecture .= " erreur de cr&eacute;ation du r&eacute;pertoire d'export des fichiers";
				exit;
			}
		}
		//	Controle fichiers
		$nomFicExport = $dirLog."/".date('y\-m\-d')."_".$typeSelection."_".$typeAction.".txt";
		$nomFicExpLien = $nomLogLien."/".date('y\-m\-d')."_".$typeSelection."_".$typeAction.".txt";
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
	// Analyse des param�tres communs
	if ($SQLSecteur == "") {
		$WhereSect = "";
	} else {
		$WhereSect = "se.id in (".$SQLSecteur.") and";
	}		
	if ($SQLSysteme == "") {
		$WhereSyst = "";
		// Ici on doit traiter du cas d'une s�lection restrictive des pays
	} else {
		$WhereSyst = "sy.id in (".$SQLSysteme.") and";
	}	
	$LabCatEco = "";
	$LabCatTrop = "";
	$LabCatPois = "";
	$ConstIDunique = ""; // Va contenir la d�finition pour la construction de l'ID unique de ligne. contient une valeur differente selon type peche / filiere
	// Analyse des categories trophiques / ecologiques / poisson-non poisson
	// Analyse des categories ecologiques s�lectionn�es par l'utilisateur (selection restreinte depuis la filiere)
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
	// Analyse des categories trophiques s�lectionn�es par l'utilisateur (selection restreinte  depuis la filiere)
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
	// Analyse du type poisson non poisson s�lectionn� par l'utilisateur (selection restreinte depuis la filiere)
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
		WriteCompLog ($logComp, "INFO : type peche: ".$typePeche,$pasdefichier);
		WriteCompLog ($logComp, "INFO : Liste variable session: ",$pasdefichier);
		WriteCompLog ($logComp, "INFO : CatTrop 	= ".$_SESSION['listeCatTrop'],$pasdefichier);
		WriteCompLog ($logComp, "INFO : CatEco 		= ".$_SESSION['listeCatEco'],$pasdefichier);
		WriteCompLog ($logComp, "INFO : Poissons 	= ".$_SESSION['listePoisson'],$pasdefichier);
		WriteCompLog ($logComp, "INFO : Especes 	=".$_SESSION['listeEspeces'],$pasdefichier);
	}	
	// *******************************
	// Debut du traitement principal *	
	// *******************************
	$builQuery = false; // il a l'air de rien celui-la, mais ce flag est super important pour cr�er le SQL final qui sera execut�.
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
			// On controle que des s�lections ont �t� faites pour les esp�ces / familles
			if ($SQLEngin == "") {
				$WhereEngin = "";
				// Ici on doit traiter du cas d'une s�lection restrictive des pays
			} else {
				$WhereEngin = "cph.exp_engin_id in (".$SQLEngin.") and";
			}							
			// Prise en compte des s�lections compl�mentaires
			$compSQL = "";
			if 	(!($_SESSION['listeQualite'] =="")) {
				$compSQL =" cph.exp_qualite_id in (".$_SESSION['listeQualite'].") ";
				$restSupp = " qualit&eacute; limit&eacute;e � =".$_SESSION['listeQualite'];
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
			// ********** Gestion de l'affichage des colonnes s�lectionn�es 
			$listeChampsSel = "";
			$ListeTableSel = "";
			$WhereSel = "";
			$joinSel="";
			$AjoutWhere = "";
			// Analyse de la liste des colonnes venant des s�lections pr�c�dentes, ajout de ces colonnes au fichier
			if (!($_SESSION['listeColonne'] =="")){
				$champSel = explode(",",$_SESSION['listeColonne']);
				// On va completer les champs si on a tout selectionn�.
				if (strpos($_SESSION['listeColonne'],"toutX") > 0) {
				// A faire... 
				} 
				$nbrSel = count($champSel)-1;
				for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
					$TNomLongTable ="";
					if (($champSel[$cptSel] == "XtoutX") || ($champSel[$cptSel] == "XpasttX")) {
						continue ;
					}
					
					if (strpos($champSel[$cptSel],"-N") === false  ) { // On ne traite pas les colonnes d�coch�es, ni le choix tout / pas tout
						if ( strpos($champSel[$cptSel],"-X") === false ) {
							$valTest = $champSel[$cptSel];
						} else {
							$valTest = substr($champSel[$cptSel],0,-2);
						}
						$listeChampsSel .= ",".str_replace("-",".",$valTest);
						// Recuperation de l'alias de la table pour obtenir le nom de la table.
						// Idealement ici, il faudrait aller taper dans le fichier XML pour recup�rer le nom de la table.
						// On avoir une variable globale contenant une table de correspondance charg�e une fois pour toutes
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
			// Analyse des diff�rents composants du where et ajout des and quand n�cessaire
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
			// Enfin on ajoute les noms des nouveaux champs � lire depuis les colonnes
			if ($WhereSel == "" ) {
				$WhereSel = $AjoutWhere;
			} else {
				if (!($AjoutWhere == "")) {
					$WhereSel = $WhereSel." and ".$AjoutWhere;
				}
			}
			//echo "where sel = ".$WhereSel."<br/>";
			// Cas particulier d'aucun s�lection des esp�ces : 
			// On reconstruit cette liste pour l'ensemble de la s�lection car on va en avoir besoin
			// pour les cat�gories trophiques/ecologiques
			if ($SQLEspeces == "") {
				// On reconstruit la liste des especes de la s�lection.
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
						cpg.id in (".$SQLCampagne.") ";
				if ((!$WhereSel =="")) {
					$SQLEsp .= " and ".$WhereSel;
				}		
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
			// Si malgr� tout, toujours pas d'especes dispo, ben tant pis....
			if ($SQLEspeces == "") {
				$WhereEsp = "";
			} else {
				// Enfin on verifie qu'il n'y a pas eu de restriction suppl�mentaires
				if (!($_SESSION['listeEspeces'] == "")) {
					$TempSQLEspeces = $SQLEspeces;
					$SQLEspeces = "";
					$EspecesSele = explode (",",$_SESSION['listeEspeces']);
					$NumEsp = count($EspecesSele) - 1;
					for ($cptES=0 ; $cptES<=$NumEsp;$cptES++) {
						
						if (strpos($TempSQLEspeces,$EspecesSele[$cptES]) === false ){
			
						} else {
							// La valeur est disponible, on la met � jour
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
			// Definition de tout ce qui est commun aux peches exp�rimentales
			$listeChampsCom = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom, stat.id, stat.nom, cpg.date_debut, cpg.id, cph.date_cp, cph.id, cph.protocole, cph.exp_qualite_id, cph.exp_engin_id, xeng.libelle";
			$ListeTableCom = "ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_station as stat,exp_campagne as cpg,exp_coup_peche as cph,exp_qualite as xqua,exp_engin as xeng";
			
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
			$OrderCom = "order by py.id asc,sy.id asc,cpg.date_debut asc,cph.id asc";
			// ********** CONSTRUCTION DES SQL DEFINITIFS PAR FILIERE
			switch ($typeAction) {
				case "peuplement" :
						$labelSelection = "donn&eacute;es de peuplement ";	
						// On n'extrait que des donn�ees de fraction
						// Il n'y aucune selection de colonnes suppl�mentaires
						// On prend tous les poissons (pas de diff�rence poisson/non poisson
						$listeChampsSpec = ",esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,ref_espece as esp"; 
						$WhereSpec = " and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id ";
						$valueCount = "cph.id" ; // pour gerer la pagination	
						$builQuery = true;					
					break;
				case "environnement" :
						$labelSelection = "donn&eacute;es d'environnement ";
						// On n'extrait que des donn�ees environnements
						// Pas de donn�es poisson
						$listeChampsSpec = ",env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond";
						$ListeTableSpec = ",exp_environnement as env"; 
						$WhereSpec = " 	and env.id = cph.exp_environnement_id ";
						$valueCount = "cph.id" ; // pour gerer la pagination						
						$builQuery = true;
					break;
				case "NtPt" :
						$labelSelection = "donn&eacute;es NtPt ";
						// C'est un mixte entre les donn�es peuplements et environnement + des selections de colonnes
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,ref_espece as esp";
						$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and ".$compPoisSQL;
						$valueCount = "cph.id" ; // pour gerer la pagination						
						$builQuery = true;
					break;
				case "biologie" :
						$labelSelection = "donn&eacute;es biologiques ";
						// Construction de la liste d'individus
						// ATTENTION !!!!!! Si la liste ci-dessous est modifi�e, il faut imperativement modifi� la requete pour calculer le 
						// le coefficient d'extrapolation apres l'execution de la requete 
						$listeChampsSpec = ",fra.id, fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond,bio.longueur";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,exp_biologie as bio,ref_espece as esp";
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
						$labelSelection = "donn&eacute;es trophiques ";
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond,bio.longueur,bio.id,trop.exp_contenu_id,bio.exp_remplissage_id,cont.libelle";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,exp_biologie as bio,exp_trophique as trop, exp_contenu as cont,ref_espece as esp";
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
					$labelSelection = "coups de p&ecirc;ches ";
					$SQLfinal = "select * from ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_station as stat,exp_campagne as cpg,exp_coup_peche as cph
							where cpg.id = cph.exp_campagne_id and
							stat.id = cph.exp_station_id and
							sy.id = cpg.ref_systeme_id and
							".$WhereSyst."
							py.id = sy.ref_pays_id and
							se.id = stat.ref_secteur_id and
							".$WhereSect."
							cpg.id in (".$SQLCampagne.") ".$WhereSel;
					$SQLcountfinal = "select count(cpg.id) from ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_station as stat,exp_campagne as cpg,exp_coup_peche as cph
							where cpg.id = cph.exp_campagne_id and
							stat.id = cph.exp_station_id and
							sy.id = cpg.ref_systeme_id and
							".$WhereSyst."
							py.id = sy.ref_pays_id and
							se.id = stat.ref_secteur_id and
							".$WhereSect."
							cpg.id in (".$SQLCampagne.") ".$WhereSel; // Pour gerer la pagination
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
			// ********** Gestion de l'affichage des colonnes s�lectionn�es 
			$posDEBID = 0 ; 	//Pour gestion regroupement
			$posESPID = 0 ; 	//Pour gestion regroupement
			$posPoids = 0 ; 	//Pour gestion regroupement
			$posNbre = 0 ; 		//Pour gestion regroupement
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
			// Analyse de la liste des colonnes venant des s�lections pr�c�dentes, ajout de ces colonnes au fichier
			if (!($_SESSION['listeColonne'] =="")){
				$champSel = explode(",",$_SESSION['listeColonne']);
				// On va completer les champs si on a tout selectionn�.
				if (strpos($_SESSION['listeColonne'],"toutX") > 0) {
				// A faire... 
				} 
				$nbrSel = count($champSel)-1;
				for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
					$TNomLongTable ="";
					if (($champSel[$cptSel] == "XtoutX") || ($champSel[$cptSel] == "XpasttX")) {
						continue ;
					}
					
					if (strpos($champSel[$cptSel],"-N") === false  ) { // On ne traite pas les colonnes d�coch�es, ni le choix tout / pas tout
						if ( strpos($champSel[$cptSel],"-X") === false ) {
							$valTest = $champSel[$cptSel];
						} else {
							$valTest = substr($champSel[$cptSel],0,-2);
						}
						$listeChampsSel .= ",".str_replace("-",".",$valTest);
						// Recuperation de l'alias de la table pour obtenir le nom de la table.
						// Idealement ici, il faudrait aller taper dans le fichier XML pour recup�rer le nom de la table.
						// On avoir une variable globale contenant une table de correspondance charg�e une fois pour toutes
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
			// Analyse des diff�rents composants du where et ajout des and quand n�cessaire
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
			// Enfin on ajoute les noms des nouveaux champs � lire depuis les colonnes
			if ($WhereSel == "" ) {
				$WhereSel = $AjoutWhere;
			} else {
				if (!($AjoutWhere == "")) {
					$WhereSel = $WhereSel." and ".$AjoutWhere;
				}
			}
			
			// Cas particulier d'aucun s�lection des esp�ces : 
			// On reconstruit cette liste pour l'ensemble de la s�lection car on va en avoir besoin
			// pour les cat�gories trophiques/ecologiques
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
			// Si malgr� tout, toujours pas d'especes dispo, ben tant pis....
			if ($SQLEspeces == "") {
				$WhereEsp = "";
			} else {
				// Enfin on verifie qu'il n'y a pas eu de restriction suppl�mentaires
				if (!($_SESSION['listeEspeces'] == "")) {
					$TempSQLEspeces = $SQLEspeces;
					$SQLEspeces = "";
					$EspecesSele = explode (",",$_SESSION['listeEspeces']);
					$NumEsp = count($EspecesSele) - 1;
					for ($cptES=0 ; $cptES<=$NumEsp;$cptES++) {
						
						if (strpos($TempSQLEspeces,$EspecesSele[$cptES]) === false ){
			
						} else {
							// La valeur est disponible, on la met � jour
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
			// Definition de tout ce qui est commun aux peches exp�rimentales
			// Il va y avoir moins de donn�es communes que pour les peches exp car certaines dependent de la filiere acti ou deb 
			// Donc on cree des variables generales selon qu'on va traiter activite ou debarquement
			// D�finition des SQL de base pour les activites (art_activite)
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
			// D�finition des SQL de base pour les d�barquements (art_debarquement)
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
			$OrderDeb = "order by py.id asc, sy.id asc, agg.nom, deb.annee asc,deb.mois asc,deb.id asc";
			// ********** CONSTRUCTION DES SQL DEFINITIFS PAR FILIERE
			switch ($typeAction) {
				case "activite" :
						// On considere les donn�es d'activit�. On commence par mettre � jour les varialbes communs *com
						$listeChampsCom = $listeChampsArt;
						$ListeTableCom = $ListeTableArt ;
						$WhereCom = $WhereArt ;
						$OrderCom = $OrderArt ;
						$labelSelection = "donn&eacute;es d'activit&eacute;";	
						$listeChampsSpec = ",act.art_type_activite_id,act.nbre_unite_recencee ";
						$ListeTableSpec = ""; 
						$WhereSpec = "";	
						$ConstIDunique = "ART-##-12"; // Ce qui apres le -##-n sera remplac� par la valeur d'index n de la lecture de la requete par exemple, ici, on va recuperer art.id  
						$valueCount = "act.id" ; // pour gerer la pagination				
						$builQuery = true;
					break;			
				case "capture" :
				// Liste des debarquements.
						$labelSelection = "donn&eacute;es de capture";	
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						$OrderCom = $OrderDeb ;
						$listeChampsSpec = ", deb.poids_total";
						$ListeTableSpec = ""; 
						$WhereSpec = "";
						$ConstIDunique = "DEB-##-11";
						$valueCount = "deb.id" ; // pour gerer la pagination	
						$builQuery = true;
					break;
				case "NtPt" :
						$labelSelection = "donn&eacute;es NtPt";				
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						if (!($_SESSION['listeRegroup'] == "")) {
							$OrderCom = $OrderDeb."  , afra.ref_espece_id asc ";
							} else {
							$OrderCom = $OrderDeb ;
						}
						$posDEBID = 11 ; //position deb.id - 1 / Pour gestion regroupement
						$posESPID = 17 ; //position afra.ref_espece_id - 1 / Pour gestion regroupement
						$posESPNom = 16 ; //position esp.libelle - 1 / Pour gestion regroupement
						$posPoids = 14 ; //position afra.poids - 1 / Pour gestion regroupement
						$posNbre = 15 ; //position afra.nbre_poissons - 1 / Pour gestion regroupement
						$listeChampsSpec = ", deb.poids_total,afra.poids,afra.nbre_poissons,esp.libelle,afra.ref_espece_id ";
						$ListeTableSpec = ", art_fraction as afra,ref_espece as esp "; 
						$WhereSpec = " 	and ".$WhereEsp." afra.art_debarquement_id = deb.id 
										and esp.id = afra.ref_espece_id	";					
						$ConstIDunique = "DEB-##-11";
						$valueCount = "deb.id" ; // pour gerer la pagination	
						$builQuery = true;
					break;
				case "taille" :
						$labelSelection = "donn&eacute;es de tailles";	
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
						$posESPNom = 17 ; //position esp.libelle - 1 / Pour gestion regroupement
						$posPoids = 14 ; //position afra.poids - 1 / Pour gestion regroupement
						$posNbre = 15 ; //position afra.nbre_poissons - 1 / Pour gestion regroupement
						$posMes = 16 ; //position afra.nbre_poissons - 1 / Pour gestion regroupement
						$listeChampsSpec = ", deb.poids_total,afra.poids,afra.nbre_poissons,ames.taille,esp.libelle,afra.ref_espece_id ";
						$ListeTableSpec = ", art_fraction as afra,ref_espece as esp,art_poisson_mesure as ames"; 
						$WhereSpec = " 	and ".$WhereEsp." afra.art_debarquement_id = deb.id 
										and ames.art_fraction_id = afra.id 
										and esp.id = afra.ref_espece_id	";						
						$ConstIDunique = "DEB-##-11";
						$valueCount = "deb.id" ; // pour gerer la pagination
						$builQuery = true;
					break;
				case "engin" :
						$labelSelection = "donn&eacute;es d'engin";	
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
					break;															
				default	:	
					$labelSelection = "p&eacute;riode d'enqu&ecirc;te";
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
			// ********** Gestion de l'affichage des colonnes s�lectionn�es 
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
			// Cas particulier d'aucun s�lection des esp�ces : 
			// On reconstruit cette liste pour l'ensemble de la s�lection car on va en avoir besoin
			// pour les cat�gories trophiques/ecologiques
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
			// Si malgr� tout, toujours pas d'especes dispo, ben tant pis....
			if ($SQLEspeces == "") {
				$WhereEsp = "";
			} else {
				// Enfin on verifie qu'il n'y a pas eu de restriction suppl�mentaires
				if (!($_SESSION['listeEspeces'] == "")) {
					$TempSQLEspeces = $SQLEspeces;
					$SQLEspeces = "";
					$EspecesSele = explode (",",$_SESSION['listeEspeces']);
					$NumEsp = count($EspecesSele) - 1;
					for ($cptES=0 ; $cptES<=$NumEsp;$cptES++) {
						
						if (strpos($TempSQLEspeces,$EspecesSele[$cptES]) === false ){
			
						} else {
							// La valeur est disponible, on la met � jour
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
			$choixSynthese = ""; // recupere le choix de la synth�se � extraire
			if (isset($_GET['synth'])) {
				$choixSynthese  = $_GET['synth'];
			} else {
				$choixSynthese  = "cap_tot"; // Par defaut le premier
			}
			switch ($typeStatistiques) {
				// *********************************************************************************
				// STATISTIQUES PAR AGGLOMERATION
				// *********************************************************************************
				case "agglomeration" :
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

						$OrderCom = "order by py.id asc,sy.id asc,penq.annee asc,penq.mois asc";
						switch ($typeAction) {
				
						// Statistiques globales
						case "globale" :
							// On analyse le choix et on cree la requete en focntion
							switch ($choixSynthese) {
								case "cap_tot";
									$labelSelection = "captures totales";	
									$listeChampsSpec = ",ast.fm,ast.cap,ast.pue,ast.id";
									$ListeTableSpec = ",art_periode_enquete as penq, art_stat_totale as ast"; 
									$WhereSpec = " and ast.art_agglomeration_id = penq.art_agglomeration_id";						
									$ConstIDunique = "AST-##-13";
									$valueCount = "ast.id" ; // pour gerer la pagination
									$builQuery = true;
									break;
								case "cap_sp";
									$labelSelection = "r&eacute;sultats par esp&egrave;ces";	
									$listeChampsSpec = ",asp.ref_espece_id,asp.pue_sp,asp.cap_sp,ast.fm,ast.cap,ast.pue,asp.id,ast.id";
									$ListeTableSpec = ",art_periode_enquete as penq, art_stat_totale as ast,art_stat_sp as asp"; 
									$WhereSpec = "	and asp.art_stat_totale_id = ast.id ";					
									$ConstIDunique = "ASP-##-16";
									$valueCount = "asp.id" ; // pour gerer la pagination
									$builQuery = true;
									break;
								case "dft_sp";
									$labelSelection = "structure en taille des esp&egrave;ces";	
									$listeChampsSpec = ",asp.ref_espece_id,asp.pue_sp,asp.cap_sp,ats.li,ats.xi,asp.id,ast.id,ats.id";
									$ListeTableSpec = ",art_periode_enquete as penq, art_stat_totale as ast,art_stat_sp as asp,art_taille_sp as ats"; 
									$WhereSpec = " 	and ats.art_stat_sp_id = asp.id and
													asp.art_stat_totale_id = ast.id ";						
									$ConstIDunique = "ATS-##-13";
									$valueCount = "ats.id" ; // pour gerer la pagination
									$builQuery = true;
									break;
								default:
									echo "erreur pas de selection synthese<br/>";
							}
						break;	
						// Statistiques par Grand type
						case "GT" :	
							// On analyse le choix et on cree la requete en focntion
							switch ($choixSynthese) {
								case "cap_GT";
									$labelSelection = "r&eacute;sultats globaux par GT";	
									$listeChampsSpec = ",asgt.fm_gt,asgt.cap_gt,asgt.pue_gt,asgt.id,ast.id,ast.id";
									$ListeTableSpec = ",art_periode_enquete as penq, art_stat_gt as asgt, art_stat_totale as ast"; 
									$WhereSpec = "	and asgt.art_stat_totale_id = ast.id ";						
									$ConstIDunique = "AGT-##-13";
									$valueCount = "asgt.id" ; // pour gerer la pagination
									$builQuery = true;
									break;
								case "cap_GT_sp";
									$labelSelection = "r&eacute;sultats par esp&egrave;ces et par GT";	
									$listeChampsSpec = ",asgts.ref_espece_id, asgts.cap_gt_sp, asgts.pue_gt_sp, asgts.id, asgt.id, ast.id, ast.id";
									$ListeTableSpec = ",art_periode_enquete as penq, art_stat_gt_sp as asgts,art_stat_gt as asgt, art_stat_totale as ast"; 
									$WhereSpec = "	and asgts.art_stat_gt_id = asgt.id and 
													asgt.art_stat_totale_id = ast.id ";						
									$ConstIDunique = "ATS-##-13";
									$valueCount = "asgts.id" ; // pour gerer la pagination
									$builQuery = true;
									break;
								case "dft_sp_sp";
									$labelSelection = "structure en taille des esp&egrave;ces par GT";	
									$listeChampsSpec = ",asgts.ref_espece_id, asgts.cap_gt_sp, asgts.pue_gt_sp, atgts.li,atgts.xi,atgts.id, asgts.id, asgt.id, ast.id, ast.id";
									$ListeTableSpec = ",art_periode_enquete as penq, art_taille_gt_sp as atgts, art_stat_gt_sp as asgts,art_stat_gt as asgt, art_stat_totale as ast"; 
									$WhereSpec = "	and atgts.art_stat_gt_sp_id = asgts.id and 
													asgts.art_stat_gt_id = asgt.id and 
													asgt.art_stat_totale_id = ast.id ";						
									$ConstIDunique = "ATG-##-16";
									$valueCount = "atgts.id" ; // pour gerer la pagination
									$builQuery = true;
									break;
								default:
									echo "erreur pas de selection synthese<br/>";
							}
						default	:	
							$labelSelection = "Periode d'enquete";
							$SQLfinal = "select * from art_periode_enquete as penq
											where penq.id in (".$SQLPeEnquete.")";
							$SQLcountfinal = "select count(*) from art_periode_enquete as penq
									where penq.id in (".$SQLPeEnquete.")";; // pour gerer la pagination	
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
	// Elle peut avoir d�j� �t� construite pr�c�dement, notament dans les cas par defaut
	if ($builQuery) {
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
	// Gestion des regroupements
	// A ce niveau, pour g�rer les regroupements, il faut passer par une �tape interm�diaire d'agr�gation
	// On ex�cute la requete, on effectue les groupements et enfin on cr�� des entr�es dans la table temporaire temp_extraction
	if (!($_SESSION['listeRegroup'] == "")) {
		//echo $SQLfinal."<br/>";
		// On commence par vider la table temporaire
		$SQLDel = "delete from temp_extraction";
		$SQLDelresult = $SQLfinalResult = pg_query($connectPPEAO,$SQLDel);
		$erreurSQL = pg_last_error($connectPPEAO);
		if ( !$SQLDelresult ) { 
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "ERREUR : Erreur delete temp_extraction (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
			}
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur Erreur delete temp_extraction , cette table n'existe peut etre pas dans votre base (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
			$erreurProcess = true;
		} else {
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "INFO : suppression de tous les enregs dans temp_extraction OK",$pasdefichier);
			}
		}
		
		// Traitement du SQL
		$SQLfinalResult = pg_query($connectPPEAO,$SQLfinal);
		$erreurSQL = pg_last_error($connectPPEAO);
		$cpt1 = 0;
		if ( !$SQLfinalResult ) { 
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "ERREUR : Erreur query final regroupements ".$SQLfinal." (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
			}
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query regroupements ".$SQLfinal." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
			$erreurProcess = true;
		} else {
			if (pg_num_rows($SQLfinalResult) == 0) {
				// Avertissement
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "Regroupements : pas de resultat disponible pour la selection ".$SQLfinal,$pasdefichier);
				}
				$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Regroupements : pas de resultat disponible pour la s�lection<br/>";
			} else {
				$cptNbRow = 0;
				$espPrec = "";
				$debIDPrec = "";
				$espEnCours = "";
				$debEnCours = "";
				$RegPrec = "";
				$RegEnCours = "";
				$NomRegEncours = "";
				$totalPoids = 0;
				$totalNombre =0;
				$Mesure = 0;
				$regroupDeb = array(); // gestion du regroupement pour un d�barquement
				$cptTempExt = 0;
				$ColonneTE = "";
				$ValuesTE = "";
				while ($finalRow = pg_fetch_row($SQLfinalResult) ) {
					$espEnCours = $finalRow[$posESPID];
					$debEnCours = $finalRow[$posDEBID];	
					if ($EcrireLogComp && $debugLog) {
						WriteCompLog ($logComp, "DEBUG : debencours = ".$debEnCours." espencours = ".$espEnCours. " [".$posPoids."]poids = ".$finalRow[$posPoids]." [".$posNbre."]nombre = ".$finalRow[$posNbre],$pasdefichier);
						WriteCompLog ($logComp, "DEBUG : debprec = ".$debIDPrec." espprec = ".$espPrec,$pasdefichier);
					}
					if ($debEnCours<>$debIDPrec ) {
						if (!($debIDPrec == "")) {
							// On cree autant de lignes dans la table temp que de lignes dans le tableau temporaire pour ce debarquement
							$NbRegDeb = count($regroupDeb);
							if ($NbRegDeb >= 1 ) {
								if ($EcrireLogComp && $debugLog) {
									WriteCompLog ($logComp, "DEBUG : mise � jour de la table TEMP_EXTRACTION",$pasdefichier);
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
									$ValuesTE .= ",'".$regroupDeb[$cptRg][4]."'";
// Analyse de la ligne, on remplace l'espece par le regroupement et les valeurs poids et nombre par les valeurs agr�g�es
									$nbrRow = count($finalRow)-1;
									$ligneResultat = "";
									for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
										if ($cptRow<> $posESPID && $cptRow<> $posPoids && $cptRow<> $posNbre && $cptRow<> $posESPNom){
											$ligneResultat .= "&#&".$finalRow[$cptRow];
										} else {
											switch ($cptRow) {
											case $posESPID :
												$ligneResultat .= "&#&".$regroupDeb[$cptRg][1];
												break;
											case $posPoids :
												$ligneResultat .= "&#&".$regroupDeb[$cptRg][2];
												break;
											case $posNbre :
												$ligneResultat .= "&#&".$regroupDeb[$cptRg][3];
												break;
											case $posESPNom :
												$ligneResultat .= "&#&".$regroupDeb[$cptRg][4];
												break;
											}
										}
									}
									$ColonneTE .= ",valeur_ligne";
									$ligneResultat = str_replace("'","''",$ligneResultat);
									$ValuesTE .= ",'".$ligneResultat."'";									
									$ColonneTE .= ",date_creation";
									$ValuesTE .= ",'".date("Y-m-d")."'";
									$SQLInsert = "insert into temp_extraction (".$ColonneTE.") values (".$ValuesTE.")";
									//echo $SQLInsert."<br/>";
									if ($EcrireLogComp && $debugLog) {
											WriteCompLog ($logComp, "DEBUG : ".$SQLInsert,$pasdefichier);
										}
									$SQLInsertresult = pg_query($connectPPEAO,$SQLInsert);
									$erreurSQL = pg_last_error($connectPPEAO);
									if ( !$SQLInsertresult ) { 
										if ($EcrireLogComp ) {
											WriteCompLog ($logComp, "ERREUR : Erreur insert temp_extraction sql = ".SQLInsertresult."(erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
										}
										$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur Erreur insertion dans temp_extraction - sql = ".$SQLInsertresult." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
										$erreurProcess = true;
									} else {
										if ($EcrireLogComp && $debugLog) {
											WriteCompLog ($logComp, "DEBUG : ajout dans temp_suppression".$regroupDeb[$cptRg][1]." ".$regroupDeb[$cptRg][2]." ".$regroupDeb[$cptRg][3]." ",$pasdefichier);
										}
									}
									pg_free_result($SQLInsertresult);
									
								} // fin for ($cptRg=1 ; $cptRg<=$NbRegDeb;$cptRg++)
							} else {
								if ($EcrireLogComp && $debugLog) {
									WriteCompLog ($logComp, "DEBUG : tableau temp vide ==> pas mise � jour de la table TEMP_EXTRACTION",$pasdefichier);
								}
							}
							// On reinitialise les compteurs
							if ($EcrireLogComp && $debugLog) {
								WriteCompLog ($logComp, "DEBUG : reinitialisation",$pasdefichier);
							}
							$totalPoids = 0;
							$totalNombre =0;
							$Mesure = 0;
							unset($regroupDeb);
						}
					} // fin du if ($debEnCours<>$debIDPrec)
					$controleRegroupement = false;	 // Est-ce qu'on controle la presence de l'espece dans le regroupement, eventuellement on le cree ?	
					
					if ($espEnCours<>$espPrec) {
												// On est toujours sur la meme espece
						// On verifie qu'on est dans le meme regroupement
						$RegTrouve = false;
						$NbReg = count($_SESSION['listeRegroup']);
						for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
							$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
							for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
								if ($_SESSION['listeRegroup'][$cptR][$cptR2] == $espEnCours) {
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
								WriteCompLog ($logComp, "DEBUG : pas de Regroupement trouve pour espece ".$espEnCours." ==> dans div",$pasdefichier);
							}
							// Pas de regroupement trouv� pour cette espece, on le met dans le regroupement "DIV"
							$RegEnCours = "div";
							$NomRegEncours = "divers";
						}
						if ($RegEnCours == $RegPrec) {
							// On met a jour le total en cours
							$totalPoids = floatval($totalPoids) + floatval($finalRow[$posPoids]);
							$totalNombre = floatval($totalNombre) + floatval($finalRow[$posNbre]);
							if ($EcrireLogComp && $debugLog) {
								WriteCompLog ($logComp, "DEBUG : maj valeur Regroupement trouve = ".$RegEnCours,$pasdefichier);
							}
						} else {
							// On doit controler si l'espece n'est pas d�ja dans un regroupement dans le tableau temporaire pour le d�barquement en cours.
							$controleRegroupement = true;
						}

					} else {
						
						$totalPoids = floatval($totalPoids) + floatval($finalRow[$posPoids]);
						$totalNombre = floatval($totalNombre) + floatval($finalRow[$posNbre]);
						if ($EcrireLogComp && $debugLog) {
							WriteCompLog ($logComp, "DEBUG : maj totaux en cours",$pasdefichier);
						}
						$controleRegroupement = true;

					}// fin du ( $espEnCours<>$espPrec)

					if ($controleRegroupement) {
						// On regarde si on n'a pas d�j� cr��e un enregistrement
						// dans le tableau temporaire
						$RegTempTrouve = false;
						$NbRegDeb = count($regroupDeb);
						if ($EcrireLogComp && $debugLog) {
							WriteCompLog ($logComp, "DEBUG : nbre enreg regroupDeb = ".$NbRegDeb. " regencours = ".$RegEnCours,$pasdefichier);
						}
						if ($NbRegDeb >= 1 ) {
							for ($cptRg=1 ; $cptRg<=$NbRegDeb;$cptRg++) {
								if ($regroupDeb[$cptRg][1] == $RegEnCours) {
									$regroupDeb[$cptRg][2] = floatval($regroupDeb[$cptRg][2]) + floatval($finalRow[$posPoids]);
									$regroupDeb[$cptRg][3] = floatval($regroupDeb[$cptRg][3]) + floatval($finalRow[$posNbre]);
									if ($EcrireLogComp && $debugLog) {
										WriteCompLog ($logComp, "DEBUG : mise a jour tableau temporaire ".$regroupDeb[$cptRg][1]." ".$regroupDeb[$cptRg][2]." ".$regroupDeb[$cptRg][3],$pasdefichier);
									}
									$RegTempTrouve = true;
									break;
								}
							}
						} else {
							// On cr�e une entr�e dans le tableau
							$NbRegDebSuiv = count($regroupDeb) +1;
							$regroupDeb[$NbRegDebSuiv][1] = $RegEnCours;
							$regroupDeb[$NbRegDebSuiv][2] = $totalPoids;
							$regroupDeb[$NbRegDebSuiv][3] = $totalNombre;
							$regroupDeb[$NbRegDebSuiv][4] = $NomRegEncours;
							$RegTempTrouve = true; // On le met a vrai pour eviter que le tableau soit cr�� deux fois
							if ($EcrireLogComp && $debugLog) {
								WriteCompLog ($logComp, "DEBUG : creation 1ier tableau temporaire pour ".$regroupDeb[$NbRegDebSuiv][1],$pasdefichier);
							}							
						}// fin du 	if ($NbRegDeb >= 1 )	
						if (!($RegTempTrouve)) {
							// On cree le nouveau regroupement
							$NbRegDebSuiv = count($regroupDeb) + 1;
							$regroupDeb[$NbRegDebSuiv][1] = $RegEnCours;
							$regroupDeb[$NbRegDebSuiv][2] = $totalPoids;
							$regroupDeb[$NbRegDebSuiv][3] = $totalNombre;
							$regroupDeb[$NbRegDebSuiv][4] = $NomRegEncours;
							if ($EcrireLogComp && $debugLog) {
								WriteCompLog ($logComp, "DEBUG : creation suivant tableau temporaire pour ".$regroupDeb[$NbRegDebSuiv][1],$pasdefichier);
							}						
						}
					} // fin du if ($controleRegroupement)
					// On met a jour les variables contenant l'espece et le regroupement precedent
					$espPrec = $espEnCours;
					$debIDPrec = $debEnCours;
					$RegPrec = $RegEnCours;
				} // fin du while
			} // fin du if (pg_num_rows($SQLfinalResult) == 0)
		}
		pg_free_result($SQLfinalResult);
		//exit; // pour test
	
		// Recreer le SQLfinal
		$SQLfinal = "select * from temp_extraction order by id asc";
		$SQLcountfinal = "select count(*) from temp_extraction ";
		$ConstIDunique = "DEB-##-1";
		$valueCount = "temp_extraction.id" ; // pour gerer la pagination
		// Gestion de l'identifiant unique

	} // fin du if (!($_SESSION['listeRegroup'] == ""))
	
	// **** fin gestion des regroupements
	// Debut des traitements d'affichage � l'�cran et extraction fichiers
	
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
	// On g�re la pagination
	// on prend en compte la pagination
	/* D�claration des variables */ 
	$rowsPerPage = 15; // nombre d'entr�es � afficher par page (entries per page) 
	$countPages = ceil($countTotal/$rowsPerPage); // calcul du nombre de pages $countPages (on arrondit � l'entier sup�rieur avec la fonction ceil() ) 
 
	/* R�cup�ration du num�ro de la page courante depuis l'URL avec la m�thode GET */ 
	if(!isset($_GET['page']) || !is_numeric($_GET['page']) ) // si $_GET['page'] n'existe pas OU $_GET['page'] n'est pas un nombre (petite s�curit� suppl�mentaire) 
		$currentPage = 1; // la page courante devient 1 
	else { 
		$currentPage = intval($_GET['page']); // stockage de la valeur enti�re uniquement 
		if ($currentPage < 1) $currentPage=1; // cas o� le num�ro de page est inf�rieure 1 : on affecte 1 � la page courante 
		elseif ($currentPage > $countPages) $currentPage=$countPages; //cas o� le num�ro de page est sup�rieur au nombre total de pages : on affecte le num�ro de la derni�re page � la page courante 
		else $currentPage=$currentPage; // sinon la page courante est bien celle indiqu�e dans l'URL 
	} 
 
	/* $start est la valeur de d�part du LIMIT dans notre requ�te SQL (est fonction de la page courante) */ 
	$startRow = ($currentPage * $rowsPerPage - $rowsPerPage);
	// on construit la requ�te SQL pour obtenir les valeurs de la table � afficher si il y en a
	if ($countTotal!=0) {
		// Pour pouvoir g�rer la pagination, on doit s�parer la requete d'affichage de la requete de creation du fichier.
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
				$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Pas de resultat disponible pour la s�lection<br/>";
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
					// Construction de la liste des r�sultat
					// Tout d'abord, construction de l'ID unique
					// Ex $ConstIDunique = "DEB-##-11";
					// On garde le prefixe DEB et on extrait l'index du champ a recuperer de la ligne du resultat de la requete. ici 11
					$IDunique = "";
					if (!($ConstIDunique =="")) {
						$Locprefixe = substr($ConstIDunique,0,3); // Attention, pour des raisons de simplicit�, le sufffixe n'est que sur 3 caract�res.
						$locIndex = substr(strrchr($ConstIDunique, "-##-"),1);
						//echo $Locprefixe." - ".$locIndex. " - ".strrchr($ConstIDunique, "-##-");
						$IDunique = $Locprefixe.$finalRow[$locIndex];
						$resultatLecture .= "<td>".$IDunique."</td>";
					}
					if (!($_SESSION['listeRegroup'] == "")) {
						// Gestion des regroupements
						// On doit r�cup�rer la liste dans le champ valeur_ligne de la table temp_extraction
						// et construire la ligne de resultat avec
						$ligne_resultat = $finalRow[8];
						$tabResultat = explode("&#&",$ligne_resultat);
						$NbResultat = count($tabResultat);
						for ($cptResult = 0;$cptResult <= $NbResultat;$cptResult++) {
							$resultatLecture .= "<td>".$tabResultat[$cptResult]."</td>";
						}
					} else {
						// Le traitement normal
						switch ($typeAction) {
							case "biologie" :
								// On doit calculer un coefficient d'extrapolation 
								// On execute une requete suppl�mentaire pour recuperer le nombre d'individu dans exp_biologie pour la fraction et l'espece consider�e
								// On recupere le nombre de poissons reellement mesures pour une fraction donn�e (qui elle meme correspond � 
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
								// Transcription du resultat de la requete globale pour un affichage �cran et un export sous forme de fichier
								for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
									$resultatLecture .= "<td>".$finalRow[$cptRow]."</td>";
								}
								// Ajout du coefficient tout a la fin du fichier
								$resultatLecture .= "<td>".$coefficient."</td>";
								break;	
							default	:
								$nbrRow = count($finalRow)-1;
								// Transcription du resultat de la requete globale pour un affichage �cran et un export sous forme de fichier
								for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
									$resultatLecture .= "<td>".$finalRow[$cptRow]."</td>";
								}	
								break;
						}
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
					$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Pas de resultat disponible pour la s�lection (creation fichier)<br/>";
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
						// Construction de la liste des r�sultat
						// Tout d'abord, construction de l'ID unique
						// Ex $ConstIDunique = "DEB-##-11";
						// On garde le prefixe DEB et on extrait l'index du champ a recuperer de la ligne du resultat de la requete. ici 11
						$IDunique = "";
						if (!($ConstIDunique =="")) {
							$Locprefixe = substr($ConstIDunique,0,3); // Attention, pour des raisons de simplicit�, le suffixe n'est que sur 3 caract�res.
							$locIndex = substr(strrchr($ConstIDunique, "-##-"),1);
							$IDunique = $Locprefixe.$finalRow[$locIndex];
							$resultatFichier .= $IDunique."\t";
						}
						if (!($_SESSION['listeRegroup'] == "")) {
							// Gestion des regroupements
							// On doit r�cup�rer la liste dans le champ valeur_ligne de la table temp_extraction
							// et construire la ligne de resultat avec
							$ligne_resultat = $finalRow[8];
							$tabResultat = explode("&#&",$ligne_resultat);
							$NbResultat = count($tabResultat);
							for ($cptResult = 0;$cptResult <= $NbResultat;$cptResult++) {
								$resultatFichier .= $tabResultat[$cptResult]."\t";
							}
						} else {
							switch ($typeAction) {
								case "biologie" :
									// On doit calculer un coefficient d'extrapolation 
									// On execute une requete suppl�mentaire pour recuperer le nombre d'individu dans exp_biologie pour la fraction et l'espece consider�e
									// On recupere le nombre de poissons reellement mesures pour une fraction donn�e (qui elle meme correspond � 
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
									// Transcription du resultat de la requete globale pour un affichage �cran et un export sous forme de fichier
									for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
										$resultatFichier .=$finalRow[$cptRow]."\t";
									}
									// Ajout du coefficient tout a la fin du fichier
									$resultatFichier .= $coefficient;
									break;	
								default	:
									$nbrRow = count($finalRow)-1;
									//if ($_SESSION['listeRegroup'] == "") {
										// Transcription du resultat de la requete globale pour un affichage �cran et un export sous forme de fichier
										for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
											$resultatFichier .=$finalRow[$cptRow]."\t";
										}									
									//} else {
										// Gestion des regroupements.
										// Si le code unique est identique, on aggrege selon le regroupement.
										// Toute espece qui n'est pas definie dans un regroupement part dans la cat�gorie DIV
										
										
									//}
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
// AfficheCategories : Fonction pour afficher les cat�gories troph / ecologiques a selectionner
function AfficheCategories($typeCategorie,$typeAction,$ListeCE,$changtAction,$typePeche,$numTab) {
// Cette fonction permet de construire la liste des checkboxes pour la selection des especes � selectionner
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $typeCategorie : le type de cat�gorie, soit Ecologiques soit Trophiques
// $typeAction : La filiere en cours
// $ListeEsp : la liste des valeurs s�lectionn�es pour la categorie en cours
// $changtAction : est-ce qu'on vient juste de changer la selection ?
//*********************************************************************
// En sortie : 
// La fonction renvoie $construitSelection
//*********************************************************************

	// Pour construire les SQL (il faut d'abord avoir rempli ces champs !!!
	// donc avoir appele AfficherSelection
	// Donn�es pour la selection 
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
	// Definition du SQL pour trouver toutes les cat�gories trophiques des especes de la selection
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
			// Analyse des categories disponibles pour l'esp�ce consid�r�e
			while ($CERow = pg_fetch_row($SQLCEcoResult) ) {
				$ContinueTrt = false ;
				$cptInput ++;				
				if (!($CERow[0] =="" || $CERow[0] == null)) {
					// on r�cup�re le libelle de la categorie ecologique
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
					// Si on est en train de changer d'action, on remet � z�ro
					if ($changtAction =="y" || strpos($ListeCE,"toutX") > 0  ) {
						$checked ="checked=\"checked\"";
					} else {
						// On teste si la valeur a d�j� �t� saisie par l'utilisateur.
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
// Cette fonction permet de construire la liste des checkboxes pour la selection des especes � selectionner
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $SQLEspeces : la liste des especes issues de la s�lection initiale (du module pr�c�dent)
// $ListeEsp : la liste des especes s�lectionn�es
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
		case "agglomeration":
		$runfilieres = "runFilieresStat";
		break;
	 }
// Gere l'affichage des diff�rentes esp�ces
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
				$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"XtoutX\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','tout','')\"/>&nbsp;<b>tout</b></td><td>";
			} else {
				$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"XtoutX\" checked=\"checked\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n','','','aucun','')\"/>&nbsp;<b>tout</b></td><td>";
			}
			while ($CERow = pg_fetch_row($SQLCEcoResult) ) {
				if (!($CERow[0] =="" || $CERow[0] == null)) {
					$cptInput ++;
					// Si on est en train de changer d'action, on remet � z�ro
					if ($changtAction =="y" || strpos($ListeEsp,"toutX") > 0  ){
						$checked ="checked=\"checked\"";
					} else {
						// On teste si la valeur a d�j� �t� saisie par l'utilisateur.
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
function AfficheRegroupEsp($typePeche,$typeAction,$numTab,$SQLEspeces,$RegroupEsp,$RegEncours,$CreeReg) {
// Cette fonction permet de gerer les regroupements d'especes
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $SQLespeces : le SQL contenant les especes s�lectionn�es
//*********************************************************************
// En sortie : 
// La fonction renvoie $tableau
//*********************************************************************
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
if (!(isset($_SESSION['listeRegroup']))) {
	$_SESSION['listeRegroup'] = "";					  
}
//echo "Reg en cours = ".$RegEncours." creer reg = ".$CreeReg." nbr Reg = ".count($_SESSION['listeRegroup'])."<br/>";
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
// *******************************
// Gestion des diff�rentes actions
// *******************************
//echo count($_SESSION['listeRegroup'])." ".$RegEncours."<br/>";
// Reinitialisation des regroupements
// Ou suppression d'un regroupement
if (isset($_GET['suppReg'])) {
	switch ($_GET['suppReg']) {
		case "tout":
			unset($_SESSION['listeRegroup']);
			$info ="tous les regroupements ont &eacute;t&eacute; supprim&eacute;s";	
			break;
		case "EC":
			$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
			$nomRegSupp = $infoReg[1];
			unset($_SESSION['listeRegroup'][$RegEncours]);
			$info ="regroupement ".$nomRegSupp." supprim&eacute;";
			$RegEncours = $RegEncours - 1;
			if (count($_SESSION['listeRegroup']) ==  0) {
				unset($_SESSION['listeRegroup']) ; // Necessaire pour retrouver un affichage normal, la variable n'est pas completement vid�e
			}
			break;
	}
	if( $_GET['suppReg']=="tout") {

	}
}
// Reinitialisation des especes pour un regroupement
// Ou suppression d'une espece pour un regroupement
if (isset($_GET['suppEsp'])) {
	switch ($_GET['suppEsp']) {
		case "tout":
			$info ="tous les esp�ces ont &eacute;t&eacute; supprim&eacute;s pour le regroupement ";		
			break;
		case "EC":
			if (isset($_GET['espasup'])) {
				$espVraimentSup = "";
				$EspAsupp = $_GET['espasup'];
				//echo "liste a supp = ".$EspAsupp."<br/>";
				$nbListEsp = count($_SESSION['listeRegroup'][$RegEncours]);
				for ($cptEsp=2 ; $cptEsp<=$nbListEsp;$cptEsp++) {
				//echo $nbListEsp." ".$cptEsp." ".$EspAsupp." ".$_SESSION['listeRegroup'][$RegEncours][$cptEsp]."<br/>";					
					if (strpos($EspAsupp,$_SESSION['listeRegroup'][$RegEncours][$cptEsp]) === false) {
					
					} else {
						$_SESSION['listeRegroup'][$RegEncours][$cptEsp]="";
						$espVraimentSup .= ",".$_SESSION['listeRegroup'][$RegEncours][$cptEsp];
					}
				}
				// On reindexe le tableau.
				reset($_SESSION['listeRegroup'][$RegEncours]);
				$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
				$info ="les esp&egrave;ces ".$espVraimentSup." ont &eacute;t&eacute; supprim&eacute;es du regroupement ".$infoReg[1];			
			} 
			break;
	}
}
// Gestion de l'ajout d'esp�ces dans un groupe
if (isset($_GET['affEsp'])) {
	if( $_GET['affEsp']=="y") {
		if (isset($_GET['espAff'])) {
			$EspAAffecter = $_GET['espAff'];
			//echo "liste a ajouter = ".$EspAAffecter."<br/>";
			$ListeEsp = explode(",",$EspAAffecter);
			$nbListEsp = count($ListeEsp);
			$derEsp = intval(count($_SESSION['listeRegroup'][$RegEncours]))-1;
			for ($cptEsp=0 ; $cptEsp<$nbListEsp;$cptEsp++) {
				$rangEsp = intval($cptEsp+2+$derEsp); // le + 2 indique qu'on commence au rang 1 et que le rang 1 est d�j� pris par le nom du regroupement
				$_SESSION['listeRegroup'][$RegEncours][$rangEsp] = $ListeEsp[$cptEsp];
				$infoReg = explode("&#&",$_SESSION['listeRegroup'][$RegEncours][1]);
				$info ="les esp&egrave;ces ".$EspAAffecter." ont &eacute;t&eacute; ajout&eacute;es au regroupement ".$infoReg[1];
			}
		} 
	}
}

// Gestion de la cr�ation d'un nouveau regroupement
switch ($CreeReg) {
	case "y" : 
		$ulrComp="&nvReg=f";
		$nouveauRegroupement = "<br/>nouveau regroupement<br/>code&nbsp;<input id=\"codeReg\" type=\"textbox\" size=\"3\"/><br/>nom&nbsp;&nbsp;<input id=\"nomReg\" type=\"textbox\" /><br/>";
		$nouveauRegroupement .= "<a href=\"#\" onClick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','".$ulrComp."')\">ajouter regroupement</a>"; 
		break;
	case "f" : 
		// Creation effective du nouveau regroupement
		if (isset($_GET['nomReg'])) {
			$nvNomReg = $_GET['nomReg'];
			$nvCodeReg = $_GET['codeReg'];
			if (!($_SESSION['listeRegroup'] =="" )) {
				$rangNvReg = count($_SESSION['listeRegroup']) + 1;
				$_SESSION['listeRegroup'][$rangNvReg][1]=$nvCodeReg."&#&".$nvNomReg;
				$info .="Regroupement numero ".$rangNvReg." ".$nvNomReg." (".$nvCodeReg.") ajout&eacute;<br/>";
				$RegEncours = $rangNvReg;
			} else {
				$_SESSION['listeRegroup'][1][1]=$nvCodeReg."&#&".$nvNomReg;
				$RegEncours = 1;
				$info .="Regroupement num&eacute;ro 1 ".$nvNomReg." (".$nvCodeReg.") ajout&eacute;<br/>";
			}
		} else {
			$info .= "erreur saisie nom <br/>";
		}
		break;
}
// Fin des diff�rentes actions

// On construit les diff�rentes options
// Especes disponibles

$labelEspDispo = "esp&egrave;ces disponible &agrave; la s&eacute;lection";
$SQLReg = "select id,libelle from ref_espece where id in (".$SQLEspeces.") order by id";	
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
				// On regarde si l'espece est d�ja dans un groupe. Si oui, on le l'affiche pas.
				$NbReg = count($_SESSION['listeRegroup']);
				for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
					$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
					for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
						//echo $cptR.$cptR."-".$_SESSION['listeRegroup'][$cptR][$cptR]." ";
						if ($_SESSION['listeRegroup'][$cptR][$cptR2] == $RegRow[0]) {
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
	}
}

pg_free_result($SQLRegResult);
// Regroupement
if ($_SESSION['listeRegroup'] =="" ) {
	$labelRegroup = "pas de regroupement";
	$OptionRegroup = "cliquez sur \"ajouter regroupement\" ci-dessous pour cr&eacute;er le premier regroupement<br/>";
	$ulrComp="&nvReg=y";
	$OptionRegroup .="<a href=\"#\" onclick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','".$ulrComp."')\">ajouter regroupement</a>";
} else {
	$labelRegroup = "regroupements disponibles";
	// Le onlclick sur le regroupement permet d'afficher les especes de ce regroupement
	$OptionRegroup ="<select id=\"Regroupement\" class=\"level_select\" size=\"10\" name=\"Regroupement\"> \">";
	// Remplissage de la liste des regroupements
	$NbReg = count($_SESSION['listeRegroup']);
	for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
		if ($RegEncours == $cptR) {
			$selected = "selected =\"selected\"";
		} else {
			$selected = "";
		}
		$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
		$OptionRegroup .= "<option value=\"".$cptR."\" ".$selected." onClick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&regRec=change') \">".$infoReg[1]."</option>";
	}
	
	$OptionRegroup .="</select>";
	$OptionRegroup .="<br/><a href=\"#\" onclick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&nvReg=y')\">ajouter regroupement</a><br/><a href=\"#\" onclick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppReg=EC')\">supprimer le regroupement</a> <br/><a href=\"#\" onclick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppReg=tout')\">supprimer tous les regroupements</a> <br/>";
	
}

// contenu du regroupement
$selectionComp="";
if ($_SESSION['listeRegroup'] =="" ) {
	$labelListeRegroupt=$nouveauRegroupement;//nouveauRegroupement = la zone de saisie pour le nouveau groupement
} else {
	$labelListeRegroupt="esp&egrave;ces pour le regroupement s&eacute;lectionn&eacute";
	$OptionRegroupCont ="<select id=\"Regroupcontenu\" class=\"level_select\" multiple=\"multiple\" size=\"10\" name=\"Regroupcontenu\">";
	
	// Remplissage des especes pour ce groupement
	$NbReg = count($_SESSION['listeRegroup']);
	for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
		if ($RegEncours == $cptR) {
			$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
			if ($NbReg2 >=3) {
				for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
					$OptionRegroupCont .= "<option value=\"".$_SESSION['listeRegroup'][$cptR][$cptR2]."\">".$_SESSION['listeRegroup'][$cptR][$cptR2]."</option>";
					$selectionComp = "<br/><a href=\"#\" onclick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppEsp=EC')\">supprimer l'esp&egrave;ce</a> <br/><a href=\"#\" onclick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppEsp=tout')\">supprimer toutes les esp&egrave;ces</a> <br/>";
				}
				break;
			} else {
				$OptionRegroupCont .= "<option disabled=\"disabled\">pas d'esp&egrave;ces associ&eacute;es</option>";
				$selectionComp = "<br/>s&eacute;lectionnez une esp&egrave;ce dans la <br/>premi&egrave;re colonne et cliquez sur --> pour l'affecter <br/>&agrave; ce regroupement";
				break;
			}
		}
	}	
	$OptionRegroupCont .="</select>".$selectionComp;
}
// Gestion des affectations
if (!($info == "")) { 
	$info = "<span id=\"infoSuppReg\">".$info."</span>";
}
if ($_SESSION['listeRegroup'] =="" ) {
	$AffAffection="";
} else {
	$AffAffection="<div id=\"gereAffectation\" class=\"level_div\"><br/><br/><br/><a href=\"#\" onclick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&affEsp=y')  \">--></a><br/><br/><br/><a href=\"#\" onclick=\"runFilieresArt('".$typePeche."','".$typeAction."','".$numTab."','','n','','','','&suppEsp=EC')  \"><--</a> </div>";
}

	
// On construit l'affichage
$AffListeEspecesDispo = "<div id=\"listeEspece\" class=\"level_div\">".$labelEspDispo."<br/>
						<select id=\"especesDispo\" class=\"level_select\" multiple=\"multiple\" size=\"10\" name=\"especesDispo\">
						".$OptionEspDispo."</select><br/>".$cptEsp." esp&egrave;ces disponibles ";
if (!($_SESSION['listeRegroup'] =="") ) {$AffListeEspecesDispo .= $nouveauRegroupement;}
$AffListeEspecesDispo .="</div>";
$AffListeRegroup = "<div id=\"Regroupt\" class=\"level_div\">".$labelRegroup."<br/>".$OptionRegroup."</div>" ;
$AffListeRegroupCont = "<div id=\"listeRegroupt\" class=\"level_div\">".$labelListeRegroupt."<br/>".$OptionRegroupCont."</div>" ;						
$construitSelection .= $info."<br/>".$AffListeEspecesDispo.$AffAffection.$AffListeRegroup.$AffListeRegroupCont;
$construitSelection .="<div class=\"hint clear small\">
<span class=\"hint_label\">aide : </span>
<span class=\"hint_text\">
vous pouvez cr&eacute;er des regroupements et leur affecter des esp&egrave;ces ou g&eacute;rer les regroupements existants<br/>
vous pouvez s&eacute;lectionner ou d&eacute;s&eacute;lectionner plusieurs valeurs en cliquant tout en tenant la touche \"CTRL\" (Windows, Linux) ou \"CMD\" (Mac) enfonc&eacute;e
</span>
</div>
</div>";
return $construitSelection;
}

//*********************************************************************
// AfficheColonnes : Fonction pour afficher les tables / colonnes a selectionner par type de peche
function AfficheColonnes($typePeche,$typeAction,$TableEnCours,$numTab,$ListeColonnes) {
// Cette fonction permet de construire la liste des checkboxes pour la selection des tables/colonnes � selectionner
// Pour cela, elle va lire le fichier de definition (XML)
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $typePeche : le type de peche (artisanale/experimentale)
// $typeAction : la filere en cours
// $TableEnCours : la table en cours d'affichage
// $numTab: le num�ro du tab en cours
// $ListeColonnes : la liste des colonnes deja coch�es
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
	// Fichier � analyser
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
		case "agglomeration":
		$runfilieres = "runFilieresStat";
		break;
	 }
	$TabEnCours = $numTab;
	$fichiercolonne = $_SERVER["DOCUMENT_ROOT"]."/conf/ExtractionDefColonnes.xml";
	// Appel � la fonction de cr�ation et d'initialisation du parseur
	if (!(list($xml_parser_col, $fp) = new_xml_parser_Colonnes($fichiercolonne))){ 
		die("Impossible d'ouvrir le document XML"); 
	}
	// Traitement de la ressource XML
	
	while ($data = fread($fp, 4096)){
	
		if (!xml_parse($xml_parser_col, $data, feof($fp))){
			die(sprintf("Erreur XML : %s � la ligne %d<br/>",
			xml_error_string(xml_get_error_code($xml_parser_col)),
			xml_get_current_line_number($xml_parser_col)));
		   }
	}
	
	// Lib�ration de la ressource associ�e au parser
	xml_parser_free($xml_parser_col);
	if ($ListeChampTableFac == "") {
		$ContenuChampTableFac = "";
	} else {
		$ContenuChampTableFac = "colonnes facultatives<br/>".$ListeChampTableFac."<br/>";
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
	
	$tableau = $InputTout."<table class=\"ChoixChampComp\"><tr><td class=\"CCCTable\">&nbsp;".$ListeTable." </td><td class=\"CCCChamp\">colonnes par d&eacute;faut : <br/>".$ListeChampTableDef."<br/>".$ContenuChampTableFac."</td></tr></table>".$inputTableEC.$inputNumFac.$inputNumDef;
	return $tableau; 
}

//*********************************************************************
// AnaylseVarSession : Fonction qui reconstruit une variable de session
function AnaylseVarSession($ValeurATester){
// Cette fonction permet de tester si la variable de session contient la valeur � tester
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $VarSession : la variable de session
// $ValeurATester : la valeur � tester
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
// En entr�e, les param�tres suivants sont :
// $dirLog : le r�pertoire du fichier log
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
// En entr�e, les param�tres suivants sont :
// $SQLAexec : Le SQL contenant la liste des especes en cours
//*********************************************************************
// En sortie : 
// La fonction renvoie $SQLEspeces, la liste nettoy�e des doublons
//*********************************************************************
// On reconstruit la liste des especes de la s�lection.
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
// En entr�e, les param�tres suivants sont :
// $listeDesChamps : la liste des champs avec les alias
//*********************************************************************
// En sortie : 
// La fonction renvoie $listeDesChamps, la liste mise � jour avec les noms des tables
//*********************************************************************
// Idealement ici, il faudrait aller taper dans le fichier XML pour recup�rer le nom de la table.
	// On avoir une variable globale contenant une table de correspondance charg�e une fois pour toutes.
	$listeDesChamps = str_replace("py.","ref_pays.",$listeDesChamps);
	$listeDesChamps = str_replace("sy.","ref_systeme.",$listeDesChamps);
	$listeDesChamps = str_replace("se.","ref_secteur.",$listeDesChamps);	
	return $listeDesChamps;
}

?>