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
// AfficherSelection : Fonction d'affichage de la selection
function  ajouterAuWhere($WhereEncours,$CodeAajouter) {
	if ($WhereEncours == "" ) {
		$WhereEncours = $CodeAajouter;
	} else {
		$WhereEncours .= " and ".$CodeAajouter;
	}
	return $WhereEncours;
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
	$divExportFic = "<div id=\"exportFic\"><input type=\"button\" id=\"validation\" onClick=\"runFilieresArt('".$typePeche."?>','".$typeAction."','1','".$codeTableEnCours."','y')\" value=\"Voir les r&eacute;sultats\"/>
<input type=\"checkbox\" id=\"ExpFic\" />Exporter sous forme de fichier</div>";
		}
	if ($exportFichier) {
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
		$nomFicExport = $dirLog."/".date('y\-m\-d')."_".$typePeche."_".$typeAction.".txt";
		$nomFicExpLien = $nomLogLien."/".date('y\-m\-d')."_".$typePeche."_".$typeAction.".txt";
		$resultatLecture = "Le fichier de r&eacute;sultat peut &ecirc;tre consult&eacute; : <a href=\"".$nomFicExpLien."\" target=\"export\"/>".$nomFicExpLien."</a><br/><br/>";
		$ExpComp = fopen($nomFicExport , "w+");
		if (! $ExpComp ) {
			$resultatLecture .= " erreur de cr&eacute;ation du fichier export ".$nomFicExpLien;
			exit;		
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
				$restSupp = " Qualit&eacute; limit&eacute;e � =".$_SESSION['listeQualite'];
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
				$nbrSel = count($champSel)-1;
				for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
					$TNomLongTable ="";
					if (strpos($champSel[$cptSel],"-N") === false ) { // On ne traite pas les colonnes d�coch�es
						if ( strpos($champSel[$cptSel],"-X") === false ) {
							$valTest = $champSel[$cptSel];
						} else {
							$valTest = substr($champSel[$cptSel],0,-2);
						}
						$listeChampsSel .= ",".str_replace("-",".",$valTest);
						// Recuperation de l'alias de la table pour obtenir le nom de la table.
						$PosDas = strpos($valTest,"-");
						$TNomTable = substr($valTest,0,$PosDas);
						//echo $TNomTable."<br/>";
						switch ($TNomTable) {
							case "cate" : 	
								$TNomLongTable = "ref_categorie_ecologique";	
								$ListeTableSel .= ", ".$TNomLongTable." as ".$TNomTable;
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id ");
								break;
							case "catt" :
								$TNomLongTable = "ref_categorie_trophique";	
								$ListeTableSel .= ", ".$TNomLongTable." as ".$TNomTable;
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id "); 		
								break;
							case "ord" :
								$TNomLongTable = "ref_ordre";	
								$ListeTableSel .= ", ".$TNomLongTable." as ".$TNomTable;
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
						// On n'extrait que des donn�ees de fraction
						// Il n'y aucune selection de colonnes suppl�mentaires
						// On prend tous les poissons (pas de diff�rence poisson/non poisson
						$listeChampsSpec = ",esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,ref_espece as esp"; // attention a l'ordre pour les left outer join
						$WhereSpec = " and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id ";	
						$builQuery = true;					
					break;
				case "environnement" :
						$labelSelection = "Donn&eacute;es d'environnement ";
						// On n'extrait que des donn�ees environnements
						// Pas de donn�es poisson
						$listeChampsSpec = ",env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond";
						$ListeTableSpec = ",exp_environnement as env"; // attention a l'ordre pour les left outer join
						$WhereSpec = " 	and env.id = cph.exp_environnement_id ";						
						$builQuery = true;
					break;
				case "NtPt" :
						$labelSelection = "Donn&eacute;es NtPt ";
						// C'est un mixte entre les donn�es peuplements et environnement + des selections de colonnes
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,ref_espece as esp";// attention a l'ordre pour les left outer join
						$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and ".$compPoisSQL;						
						$builQuery = true;
					break;
				case "biologie" :
						$labelSelection = "Donn&eacute;es biologiques ";
						// Construction de la liste d'individus
						$listeChampsSpec = ",fra.id, fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond,bio.longueur";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,exp_biologie as bio,ref_espece as esp";// attention a l'ordre pour les left outer join
						$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp." 
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and
							bio.exp_fraction_id = fra.id and ".$compPoisSQL;
						$OrderCom .= ",fra.id asc ";						
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
						$builQuery = true;	
					break;
					default	:	
					$labelSelection = "Coup de p&ecirc;ches ";
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
							//echo 	$SQLfinal."<br/>";
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
				$nbrSel = count($champSel)-1;
				for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
					$TNomLongTable ="";
					if (strpos($champSel[$cptSel],"-N") === false ) { // On ne traite pas les colonnes d�coch�es
						if ( strpos($champSel[$cptSel],"-X") === false ) {
							$valTest = $champSel[$cptSel];
						} else {
							$valTest = substr($champSel[$cptSel],0,-2);
						}
						$listeChampsSel .= ",".str_replace("-",".",$valTest);
						// Recuperation de l'alias de la table pour obtenir le nom de la table.
						$PosDas = strpos($valTest,"-");
						$TNomTable = substr($valTest,0,$PosDas);
						//echo $TNomTable."<br/>";
						switch ($TNomTable) {
							case "cate" : 	
								$TNomLongTable = "ref_categorie_ecologique";	
								$ListeTableSel .= ", ".$TNomLongTable." as ".$TNomTable;
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id ");
								break;
							case "catt" :
								$TNomLongTable = "ref_categorie_trophique";	
								$ListeTableSel .= ", ".$TNomLongTable." as ".$TNomTable;
								$AjoutWhere = ajouterAuWhere($AjoutWhere," ".$TNomTable.".id = esp.".$TNomLongTable."_id "); 		
								break;
							case "ord" :
								$TNomLongTable = "ref_ordre";	
								$ListeTableSel .= ", ".$TNomLongTable." as ".$TNomTable;
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
			$listeChampsDeb = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom,se.id, deb.art_agglomeration_id, agg.nom, deb.art_agglomeration_id, agg.nom, deb.annee, deb.mois, deb.id, deb.date_debarquement";
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
						// On considere les donn�es d'activit�. On commence par mettre � jour les varialbes communs *com
						$listeChampsCom = $listeChampsArt;
						$ListeTableCom = $ListeTableArt ;
						$WhereCom = $WhereArt ;
						$OrderCom = $OrderArt ;
						$labelSelection = "Donn&eacute;es d'activit&eacute;";	
						$listeChampsSpec = ",act.art_type_activite_id,act.nbre_unite_recencee ";
						$ListeTableSpec = ""; // attention a l'ordre pour les left outer join
						$WhereSpec = "";						
						$builQuery = true;
					break;			
				case "capture" :
				// Liste des debarquements.
						$labelSelection = "Donn&eacute;es de capture";	
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						$OrderCom = $OrderDeb ;
						$listeChampsSpec = "";
						$ListeTableSpec = ""; // attention a l'ordre pour les left outer join
						$WhereSpec = "";						
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
						$builQuery = true;
					break;
				case "structure" :
						$labelSelection = "Donn&eacute;es de structures";	
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						$OrderCom = $OrderDeb ;	
						$listeChampsSpec = ", deb.poids_total,afra.poids,afra.nbre_poissons,ames.taille ";
						$ListeTableSpec = ", art_fraction as afra,ref_espece as esp,art_poisson_mesure as ames"; // attention a l'ordre pour les left outer join
						$WhereSpec = " 	and ".$WhereEsp." afra.art_debarquement_id = deb.id 
										and ames.art_fraction_id = afra.id 
										and esp.id = afra.ref_espece_id	";						
						$builQuery = true;
					break;
				case "engin" :
						$labelSelection = "Donn&eacute;es d'engin";	
						$listeChampsCom = $listeChampsDeb;
						$ListeTableCom = $ListeTableDeb ;
						$WhereCom = $WhereDeb ;
						$OrderCom = $OrderDeb ;	
						$listeChampsSpec = ",deb.art_grand_type_engin_id";
						$ListeTableSpec = ""; // attention a l'ordre pour les left outer join
						$WhereSpec = " ";						
						$builQuery = true;
					break;															
				default	:	
					$labelSelection = "Periode d'enquete";
					$SQLfinal = "select * from art_periode_enquete as penq
									where penq.id in (".$SQLPeEnquete.")";
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
							}
							// Si malgr� tout, toujours pas d'especes dispo, ben tant pis....
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
	// Elle peut avoir d�j� �t� construite pr�c�dement, notament dans les cas par defaut
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
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp, "INFO SQL en cours :".$SQLfinal,$pasdefichier);
		}
	}

	
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
			
			$resultatLecture .= "<b>".str_replace(","," - ",$listeChamps)."</b><br/>";
			if ($exportFichier) {
				$resultatFichier = str_replace(",","\t",$listeChamps);
				if (! fwrite($ExpComp,$resultatFichier."\r\n") ) {
					if ($EcrireLogComp ) {
							WriteCompLog ($logComp, "ERREUR : erreur ecriture dans fichier export.",$pasdefichier);
					} else {
						$resultatLecture .= "erreur ecriture dans fichier export" ;
					}
					exit;
				}	
			}
			
			while ($finalRow = pg_fetch_row($SQLfinalResult) ) {
				$resultatFichier = "";
				// Construction de la liste des r�sultat
				switch ($typeAction) {
					case "biologie" :
						// On doit calculer un coefficient d'extrapolation 
						// er nombre total d'individu
							$nbrRow = count($finalRow)-1;
							for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
								$resultatLecture .= $finalRow[$cptRow]." - ";
								if ($exportFichier) {
									$resultatFichier .=$finalRow[$cptRow]."\t";
								}
							}
							
							$resultatLecture .="<br/>";
						break;	
					default	:
							$nbrRow = count($finalRow)-1;
							for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
								$resultatLecture .= $finalRow[$cptRow]." - ";
								if ($exportFichier) {
									$resultatFichier .=$finalRow[$cptRow]."\t";
								}
							}	
							$resultatLecture .="<br/>";
	
				}
				if ($exportFichier) {
					$resultatFichier .="\n";
					if (! fwrite($ExpComp,$resultatFichier) ) {
						if ($EcrireLogComp ) {
							WriteCompLog ($logComp, "ERREUR : erreur ecriture dans fichier export.",$pasdefichier);
						} else {
							$resultatLecture .= "erreur ecriture dans fichier export" ;
						}
						exit;
					}	
				}
				// Compteur
				$cpt1++;
			}
		}
	}
	$compteurItem = $cpt1;
	
	if ($EcrireLogComp ) {
		WriteCompLog ($logComp, "INFO : resultat pour la requete en cours : ".$compteurItem." lignes.",$pasdefichier);
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
			$construitSelection .="<table id=\"".$nomInput."\"><tr><td>"; 
			// A faire : formater le resultat avec une table
			if (strpos($ListeCE,"tout") === false) {
				$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"tout\"  onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n')\"/>&nbsp;Tout</td><td>";
			} else {
				$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"tout\" checked=\"checked\" onclick=\"".$runfilieres."('".$typePeche."','".$typeAction."','".$numTab."','','n')\"/>&nbsp;Tout</td><td>";
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
					if ($changtAction =="y") {
						$checked ="checked=\"checked\"";
					} else {
						// On teste si la valeur a d�j� �t� saisie par l'utilisateur.
						if ($ListeCE == "") {
							$checked =""; 
						} else {
							if (strpos($ListeCE,$valCont) === false && (strpos($ListeCE,"tout") === false)) {
								$checked =""; 
							} else {
								$checked ="checked=\"checked\"";
							}
						}
					}
					$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"".$valCont."\" ".$checked."/>&nbsp;".$libelleCE;
					if ($cptInput/2 == 2 || $cptInput/2 == 4 || $cptInput/2 == 6 || $cptInput/2 == 8 || $cptInput/2 == 10 || $cptInput/2 == 12 || $cptInput/2 == 14 || $cptInput/2 == 16 || $cptInput/2 == 18 || $cptInput/2 == 20 ) {
						$construitSelection .= "</td></tr><tr><td>";
					} else {
						$construitSelection .= "</td><td>";
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
function AfficheEspeces($SQLEspeces,$ListeEsp,$changtAction,$typePeche,$typeAction,$numTab) {
// Cette fonction permet de construire la liste des checkboxes pour la selection des especes � selectionner
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $SQLEspeces : la liste des especes issues de la s�lection initiale (du module pr�c�dent)
// $ListeEsp : la liste des especes s�lectionn�es
// $changtAction : est-ce qu'on vient juste de changer la selection ?
//*********************************************************************
// En sortie : 
// La fonction renvoie $construitSelection
//*********************************************************************
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	$construitSelection = "";
	if ($SQLEspeces == "") {
		echo "erreur SQLEspeces vide dans la fonction AfficheEspeces<br/>Arret du traitement<br/>.";
		exit;
	}
	if ($EcrireLogComp ) {
		WriteCompLog ($logComp, "INFO : sql espece dans AfficheEspeces = ".$SQLEspeces,$pasdefichier);
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
			// A faire : formater le resultat avec une table
			if (strpos($ListeEsp,"tout") === false) {
				$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"tout\"  onclick=\"runFilieresExp('".$typePeche."','".$typeAction."','".$numTab."','','n')\"/>&nbsp;Tout";
			} else {
				$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"tout\" checked=\"checked\" onclick=\"runFilieresExp('".$typePeche."','".$typeAction."','".$numTab."','','n')\"/>&nbsp;Tout";
			}
			while ($CERow = pg_fetch_row($SQLCEcoResult) ) {
				if (!($CERow[0] =="" || $CERow[0] == null)) {
					$cptInput ++;
					// Si on est en train de changer d'action, on remet � z�ro
					if ($changtAction =="y") {
						$checked ="checked=\"checked\"";
					} else {
						// On teste si la valeur a d�j� �t� saisie par l'utilisateur.
						if ($ListeEsp == "") {
							$checked ="checked=\"checked\""; 
						} else {
							if (strpos($ListeEsp,$CERow[0]) === false && (strpos($ListeEsp,"tout") === false)) {
								$checked =""; 
							} else {
								$checked ="checked=\"checked\"";
							}
						}
					}
					$libelleEsp = $CERow[1];
					$construitSelection .= "&nbsp;<input id=\"Esp".$cptInput."\" type=\"checkbox\"  name=\"Esp\" value=\"".$CERow[0]."\" ".$checked."/>&nbsp;".$libelleEsp;
					if ($cptInput/2 == 2 || $cptInput/2 == 4 || $cptInput/2 == 6 || $cptInput/2 == 8 || $cptInput/2 == 10 || $cptInput/2 == 12 || $cptInput/2 == 14 || $cptInput/2 == 16 || $cptInput/2 == 18 || $cptInput/2 == 20 ) {
						$construitSelection .= "</td></tr><tr>";
					} else {
						$construitSelection .= "</td><td>";
					}
				}
			} // fin du while
			$construitSelection .="</td></tr></table>";
			$construitSelection .= "<input id=\"numEsp\" type=\"hidden\" name=\"numEsp\" value=\"".$cptInput ."\"/>";
		}
	}	
	pg_free_result($SQLCEcoResult);
	return $construitSelection;
}

//*********************************************************************
// AfficheColonnes : Fonction pour afficher les tables / colonnes a selectionner par type de peche
function AfficheColonnes($typePeche,$typeAction,$TableEnCours,$numTab) {
// Cette fonction permet de construire la liste des checkboxes pour la selection des tables/colonnes � selectionner
// Pour cela, elle va lire le fichier de definition (XML)
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $typePeche : le type de peche (artisanale/experimentale)
// $typeAction : la filere en cours
// $TableEnCours : la table en cours d'affichage
// $numTab: le num�ro du tab en cours
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
	
	$inputNumFac = "";
	$inputNumDef = "";
	$inputListeTable = "";
	// Fichier � analyser
	if ($TableEnCours == "") {$TableEnCours = "py";}
	$TableATester = $TableEnCours;
	$FiliereEnCours = $typeAction;
	$TypePecheEnCours = $typePeche;
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
		$ContenuChampTableFac = "Colonnes facultatives<br/>".$ListeChampTableFac."<br/>";
	}

	$inputTableEC = "<input type=\"hidden\" id=\"tableEC\" value=\"".$TableEnCours."\"/>";
	$inputNumDef = "<input type=\"hidden\" id=\"numDef\" value=\"".$NumChampDef."\"/>";
	$inputNumFac = "<input type=\"hidden\" id=\"numFac\" value=\"".$NumChampFac."\"/>";
	$tableau = "<table class=\"ChoixChampComp\"><tr><td class=\"CCCTable\">".$ListeTable." </td><td class=\"CCCChamp\">Colonnes par d&eacute;faut : <br/>".$ListeChampTableDef."<br/>".$ContenuChampTableFac."</td></tr></table>".$inputTableEC.$inputNumFac.$inputNumDef;
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
// ouvreFichierLog : Fonction pour ouvrir le fichier log
function RecupereEspeces($SQLAexec){
// Cette fonction permet d'ouvrir le fichier log
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $dirLog : le r�pertoire du fichier log
// $fileLogComp : le nom du fichier log
//*********************************************************************
// En sortie : 
// La fonction renvoie $listeSelection
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


?>