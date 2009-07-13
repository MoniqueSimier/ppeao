<?php 
//*****************************************
// functions.php
//*****************************************
// Created by Yann Laurent
// 2009-06-30 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilisées dans l'extraction des données
//*****************************************

// Variables qui seront globales dans les fonctions
$ListeTable = "";
$ListeChampTableDef = "";
$ListeChampTableFac = "";
$TableATester = "";
$Filiere = "";
$FiliereEnCours = "";
$TypePecheEnCours="";
$NomTableEnCours="";
$NumChampDef = 0;
$NumChampFac = 0;
$ListeTableInput = "";

function AfficherSelection($file) {

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
	global $SQLEngin	;
	global $SQLGTEngin ;
	global $SQLCampagne ;
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
	$SQLEngin	= substr($SQLEngin,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLGTEngin = substr($SQLGTEngin,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLCampagne = substr($SQLCampagne,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLEspeces	= substr($SQLEspeces,0,- 1); // pour enlever la virgule surnumeraire;
	$SQLFamille = substr($SQLFamille,0,- 1); // pour enlever la virgule surnumeraire;

	if ($_SESSION['SQLPays'] == "") {$_SESSION['SQLPays'] = $SQLPays;}
	if ($_SESSION['SQLSysteme'] == "") {$_SESSION['SQLSysteme'] = $SQLSysteme;}
	if ($_SESSION['SQLSecteur'] == "") {$_SESSION['SQLSecteur'] = $SQLSecteur;}
	if ($_SESSION['SQLEngin'] == "") {$_SESSION['SQLEngin'] = $SQLEngin;}
	if ($_SESSION['SQLGTEngin'] == "") {$_SESSION['SQLGTEngin'] = $SQLGTEngin;}
	if ($_SESSION['SQLCampagne'] == "") {$_SESSION['SQLCampagne'] = $SQLCampagne;}
	if ($_SESSION['SQLFamille'] == "") {$_SESSION['SQLFamille'] = $SQLFamille;}
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

function AfficherDonnees($file,$typeAction){

	// Il faut s'assurer qu'au moins une fois la fonction qui remplit ces variables de session a été lancée 
	$typeSelection 	= $_SESSION['typeSelection'];
	$typePeche		= $_SESSION['typePeche'];
	$typeStatistiques = $_SESSION['typeStatistiques'];
	$SQLPays 	= $_SESSION['SQLPays'];
	$SQLSysteme	= $_SESSION['SQLSysteme'];
	$SQLSecteur	= $_SESSION['SQLSecteur'];
	$SQLEngin	= $_SESSION['SQLEngin'];
	$SQLGTEngin = $_SESSION['SQLGTEngin'];
	$SQLCampagne = $_SESSION['SQLCampagne'];
	$SQLEspeces	= $_SESSION['SQLEspeces'];
	$SQLFamille = $_SESSION['SQLFamille'];
	$SQLdateDebut = $_SESSION['SQLdateDebut']; // format annee/mois
	$SQLdateFin = $_SESSION['SQLdateFin']; // format annee/mois
	$listeChamps = "";
	global $connectPPEAO;
	global $resultatLecture;
	global $compteurItem;
	global $restSupp;
	global $labelSelection;
	global $CRexecution;
	global $erreurProcess;
	global $exportFichier;
		
	if ($exportFichier) {
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
		$ExpComp = fopen($nomFicExport , "w+");
		if (! $ExpComp ) {
			$resultatLecture .= " erreur de cr&eacute;ation du fichier export ".$nomFicExpLien;
			exit;		
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
	// Analyse des categories trophiques / ecologiques / poisson-non poisson
	if (!($_SESSION['listeCatEco'] == "")) {
		$restSupp .= " - restreint aux cat&eacute;gories &eacute;cologique : ";
		$champSel = explode(",",$_SESSION['listeCatEco']);
		$nbrSel = count($champSel)-1;
		$valCatE = "";
		for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
			if ($valCatE == "") {
				$valCatE = "'".$champSel[$cptSel]."'";
			} else {
				$valCatE .= ",'".$champSel[$cptSel]."'";
			}
			$LabCatEco = $champSel[$cptSel]." ";
		}
		$compCatEcoSQL =" and esp.ref_categorie_ecologique_id in (".$valCatE.")";
	} else {
		$compCatEcoSQL = "";
				$LabCatEco = " - toutes les cat&eacute;gories &eacute;cologiques ";
	}
	if (!($_SESSION['listeCatTrop'] == "")) {
		$restSupp .= " - restreint aux cat&eacute;gories trophiques : ";
		$champSel = explode(",",$_SESSION['listeCatTrop']);
		$nbrSel = count($champSel)-1;
		$valCatT = "";
		for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
			if ($valCatT == "") {
				$valCatT = "'".$champSel[$cptSel]."'";
			} else {
				$valCatT .= ",'".$champSel[$cptSel]."'";
			}
			$LabCatTrop = $champSel[$cptSel]." ";
		}
		$compCatTropSQL =" and esp.ref_categorie_trophique_id in (".$valCatT.")";
	} else {
		$compCatTropSQL = "";
			$LabCatTrop = " - toutes les cat&eacute;gories trophiques ";
	}
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
				case "p" :
					$LabCatPois = " - que les non poissons ";
					break;
				case "np":
					$LabCatPois = " - que les poissons ";
					break;	
			}
		}
		$compPoisSQL =" and fam.non_poisson in (".$valPoisson.")";
	} else {
		if (!($typeAction =="environnement")){
			$LabCatPois = " - tous les poissons ";
		}
	} // fin du if (!($_SESSION['listePoisson'] == ""))
	
	// *******************************
	// Debut du traitement principal *	
	// *******************************
	switch ($typeSelection) {
		case "extraction" :
		switch ($typePeche) {
		// ********** DEBUT TRAITEMENT PECHE EXPERIMENTALE
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
			$compPoisSQL = "";
			if 	(!($_SESSION['listeQualite'] =="")) {
				$compSQL =" and cph.exp_qualite_id in (".$_SESSION['listeQualite'].")";
				$restSupp = " Qualit&eacute; limit&eacute;e à =".$_SESSION['listeQualite'];
			}
			if (!($_SESSION['listeProtocole'] == "")) {
				switch ($_SESSION['listeProtocole']) {
				case "0" : $restSupp .= " - pas restreint aux coups du protocoles ";
							break;
				case "1" : $restSupp .= " - restreint aux coups du protocoles ";
							$compSQL .=" and cph.protocole = 1";
							break;
				}
			}
			// Les selections ci-dessous ne sont valables que pour les filieres autres que l'environnement
			if (!($typeAction =="environnement")){
				// Maj du libelle de la selection en tete avec les restriction CatEco CatTroph et poisson
				$restSupp .= " - ".$LabCatEco." - ".$LabCatTrop." - ".$LabCatPois = "";
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
			// Analyse de la liste des colonnes venant des sélections précédentes, ajout de ces colonnes au fichier
			if (!($_SESSION['listeColonne'] =="")){
				$champSel = explode(",",$_SESSION['listeColonne']);
				$nbrSel = count($champSel)-1;
				for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
					$TNomLongTable ="";
					$listeChampsSel .= ",".str_replace("-",".",$champSel[$cptSel]);
					// Recuperation de l'alias de la table pour obtenir le nom de la table.
					$PosDas = strpos($champSel[$cptSel],"-");
					$TNomTable = substr($champSel[$cptSel],0,$PosDas);
					//echo $TNomTable."<br/>";
					switch ($TNomTable) {
						case "xqua": //$TNomLongTable = "exp_qualite"; On l'a deja ajouté
									break;
						case "cate" : 		
							$TNomLongTable = "ref_categorie_ecologique";
							if (strpos($joinSel,$TNomTable) === false) {
								$joinSel .= " left outer join ".$TNomLongTable." ".$TNomTable." on ".$TNomTable.".id = esp.".$TNomLongTable."_id"; 
							}
							break;
						case "catt" : 		
							$TNomLongTable = "ref_categorie_trophique";	
							if (strpos($joinSel,$TNomTable) === false) {
								$joinSel .= " left outer join ".$TNomLongTable." ".$TNomTable." on ".$TNomTable.".id = esp.".$TNomLongTable."_id";  
							}
							break;
					} // fin du switch ($TNomTable) 
				}
			} // fin du (!($_SESSION['listeColonne'] ==""))
			$WhereSel .= $compSQL.$compCatEcoSQL.$compCatTropSQL;
			
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
							$SQLEspeces .= "'".$EspRow[0]."',";	
				
						}		
					}				
				}
				$SQLEspeces	= substr($SQLEspeces,0,- 1); // pour enlever la virgule surnumeraire;
			}
			// Si malgré tout, toujours pas d'especes dispo, ben tant pis....
			if ($SQLEspeces == "") {
				$WhereEsp = "";
			} else {
				$WhereEsp = "fra.ref_espece_id in (".$SQLEspeces.") and";
				$_SESSION['SQLEspeces'] = $SQLEspeces; // ca va servir pour la suite....
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
			
			
			// ********** CONSTRUCTION DES SQL DEFINITIFS PAR FILIERE
			switch ($typeAction) {
				case "peuplement" :
						$labelSelection = "Donn&eacute;es de peuplement ";	
						// On n'extrait que des donnéees de fraction
						// Il n'y aucune selection de colonnes supplémentaires
						// On prend tous les poissons (pas de différence poisson/non poisson
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,ref_espece as esp"; // attention a l'ordre pour les left outer join
						$WhereSpec = " and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id ";						
						$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
						$listeTable = $ListeTableCom.$ListeTableSel.$ListeTableSpec; // L'ordre est important pour les join
						$WhereTotal = $WhereCom.$WhereSpec.$WhereSel;
	
						$SQLfinal = "select ".$listeChamps." from ".$listeTable." ".$joinSel." where ".$WhereTotal;
						//echo $SQLfinal;"<br/>";
					break;
				case "environnement" :
						$labelSelection = "Donn&eacute;es d'environnement ";
						// On n'extrait que des donnéees environnements
						// Pas de données poisson
						$listeChampsSpec = ",env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond";
						$ListeTableSpec = ",exp_environnement as env"; // attention a l'ordre pour les left outer join
						$WhereSpec = " 	and env.id = cph.exp_environnement_id ";						
						$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
						$listeTable = $ListeTableCom.$ListeTableSel.$ListeTableSpec; // L'ordre est important pour les join
						$WhereTotal = $WhereCom.$WhereSpec.$WhereSel;
	
						$SQLfinal = "select ".$listeChamps." from ".$listeTable." ".$joinSel." where ".$WhereTotal;
						//echo $SQLfinal;"<br/>";
					break;
				case "NtPt" :
						$labelSelection = "Donn&eacute;es NtPt ";
						// C'est un mixte entre les données peuplements et environnement + des selections de colonnes
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,ref_espece as esp";// attention a l'ordre pour les left outer join
						$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp."
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id ".$compPoisSQL;						
						$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
						$listeTable = $ListeTableCom.$ListeTableSel.$ListeTableSpec;// L'ordre est important pour les join
						$WhereTotal = $WhereCom.$WhereSpec.$WhereSel;
	
						$SQLfinal = "select ".$listeChamps." from ".$listeTable." ".$joinSel." where ".$WhereTotal;
						//echo $SQLfinal;"<br/>";
					break;
				case "biologie" :
						$labelSelection = "Donn&eacute;es biologiques ";
						// Construction de la liste d'individus
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id,env.chlorophylle_fond,env.chlorophylle_surface,env.conductivite_fond,bio.longueur";
						$ListeTableSpec = ",exp_fraction as fra,ref_famille as fam,exp_environnement as env,exp_biologie as bio,ref_espece as esp";// attention a l'ordre pour les left outer join
						$WhereSpec = " 	and fra.exp_coup_peche_id = cph.id and ".$WhereEsp." 
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id and env.id = cph.exp_environnement_id and
							bio.exp_fraction_id = fra.id ".$compPoisSQL;						
						$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
						$listeTable = $ListeTableCom.$ListeTableSel.$ListeTableSpec; // L'ordre est important pour les join
						$WhereTotal = $WhereCom.$WhereSpec.$WhereSel;
	
						$SQLfinal = "select ".$listeChamps." from ".$listeTable." ".$joinSel." where ".$WhereTotal;
						//echo $SQLfinal;"<br/>";

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
							cont.id = trop.exp_contenu_id ".$compPoisSQL;						
						$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
						$listeTable = $ListeTableCom.$ListeTableSel.$ListeTableSpec; // L'ordre est important pour les join
						$WhereTotal = $WhereCom.$WhereSpec.$WhereSel;
	
						$SQLfinal = "select ".$listeChamps." from ".$listeTable." ".$joinSel." where ".$WhereTotal;
						//echo $SQLfinal;"<br/>";
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
			}
			break;
			// ********** FIN TRAITEMENT PECHE EXPERIMENTALE
			//
			// **********
			case "artisanale" :
			// ********** DEBUT TRAITEMENT PECHE ARTISANALE
			// ********** Gestion de l'affichage des colonnes sélectionnées 
			$listeChampsSel = "";
			$ListeTableSel = "";
			$WhereSel = "";
			$joinSel="";
			$compSQL = "";
			// Analyse de la liste des colonnes venant des sélections précédentes, ajout de ces colonnes au fichier
			if (!($_SESSION['listeColonne'] =="")){
				$champSel = explode(",",$_SESSION['listeColonne']);
				$nbrSel = count($champSel)-1;
				for ($cptSel = 0;$cptSel <= $nbrSel;$cptSel++) {
					$TNomLongTable ="";
					$listeChampsSel .= ",".str_replace("-",".",$champSel[$cptSel]);
					// Recuperation de l'alias de la table pour obtenir le nom de la table.
					$PosDas = strpos($champSel[$cptSel],"-");
					$TNomTable = substr($champSel[$cptSel],0,$PosDas);
					//echo $TNomTable."<br/>";
					switch ($TNomTable) {
						case "cate" : 		
							$TNomLongTable = "ref_categorie_ecologique";
							if (strpos($joinSel,$TNomTable) === false) {
								$joinSel .= " left outer join ".$TNomLongTable." ".$TNomTable." on ".$TNomTable.".id = esp.".$TNomLongTable."_id"; 
							}
							break;
						case "catt" : 		
							$TNomLongTable = "ref_categorie_trophique";	
							if (strpos($joinSel,$TNomTable) === false) {
								$joinSel .= " left outer join ".$TNomLongTable." ".$TNomTable." on ".$TNomTable.".id = esp.".$TNomLongTable."_id";  
							}
							break;
					} // fin du switch ($TNomTable) 
				}
			} // fin du (!($_SESSION['listeColonne'] ==""))
			$WhereSel .= $compSQL.$compCatEcoSQL.$compCatTropSQL;
			
			// Cas particulier d'aucun sélection des espèces : 
			// On reconstruit cette liste pour l'ensemble de la sélection car on va en avoir besoin
			// pour les catégories trophiques/ecologiques
			if ($SQLEspeces == "") {
				// On reconstruit la liste des especes de la sélection.
				$SQLEsp = "select esp.id from ref_espece as esp";
						
				$SQLEspResult = pg_query($connectPPEAO,$SQLEsp);
				$erreurSQL = pg_last_error($connectPPEAO);
				if ( !$SQLEspResult ) { 
					$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query ".$SQLEsp." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
					$erreurProcess = true;
				
				} else {
					
					if (pg_num_rows($SQLEspResult) == 0) {
					// Erreur
						$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Activite/debarquement vide pour recuperer les especes...<br/>";
					} else {
						while ($EspRow = pg_fetch_row($SQLEspResult) ) {
							$SQLEspeces .= "'".$EspRow[0]."',";	
				
						}		
					}				
				}
				$SQLEspeces	= substr($SQLEspeces,0,- 1); // pour enlever la virgule surnumeraire;
			}
			// Si malgré tout, toujours pas d'especes dispo, ben tant pis....
			if ($SQLEspeces == "") {
				$WhereEsp = "";
			} else {
				$WhereEsp = "fra.ref_espece_id in (".$SQLEspeces.") and";
				$_SESSION['SQLEspeces'] = $SQLEspeces; // ca va servir pour la suite....
			}
			// ********** PREPARATION DU SQL
			// Definition de tout ce qui est commun aux peches expérimentales
			$listeChampsCom = "*";
			$ListeTableCom = "";
			
			$WhereCom = "";
			
			
			// ********** CONSTRUCTION DES SQL DEFINITIFS PAR FILIERE
			switch ($typeAction) {
				case "peuplement" :
						$labelSelection = "Donn&eacute;es d'activit&eacute;";	
						// On n'extrait que des donnéees de fraction
						// Il n'y aucune selection de colonnes supplémentaires
						// On prend tous les poissons (pas de différence poisson/non poisson
						$listeChampsSpec = "";
						$ListeTableSpec = ""; // attention a l'ordre pour les left outer join
						$WhereSpec = " ";						
						$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
						$listeTable = $ListeTableCom.$ListeTableSel.$ListeTableSpec; // L'ordre est important pour les join
						$WhereTotal = $WhereCom.$WhereSpec.$WhereSel;
	
						$SQLfinal = "select ".$listeChamps." from ".$listeTable." ".$joinSel." where ".$WhereTotal;
						//echo $SQLfinal;"<br/>";
					break;			
					default	:	
					$labelSelection = "Periode d'enquete";
					$SQLfinal = "select * from art_periode_enquete as penq
							where 
							penq.art_agglomeration_id in (63) and 
							penq.date_debut >='".$SQLdateDebut."/01' and 
							penq.date_fin <='".$SQLdateFin."/28'".$WhereSel;
							//$SQLfinal."<br/>";
			}
			break;
			// ********** FIN TRAITEMENT PECHE ARTISANALE
			default:
				echo "Erreur pas de peche selectionnee. Ca ne devrait pas arriver....<br/>";
				exit;
		} 
		break;
		// fin case "extraction" 
		
		case "statistiques" :
		break;
		// fin case "statistiques"
		
		
		default:
				echo "Erreur pas d'action selectionnee. Ca ne devrait pas arriver....<br/>";
				exit;
	} // fin du switch ($typeSelection) {
	
	
	
	
	
	
	// Execution de la requete
	$SQLfinalResult = pg_query($connectPPEAO,$SQLfinal);
	$erreurSQL = pg_last_error($connectPPEAO);
	$cpt1 = 0;
	if ( !$SQLfinalResult ) { 
		$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query ".$SQLfinal." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
		$erreurProcess = true;
	
	} else {
		
		if (pg_num_rows($SQLfinalResult) == 0) {
		// Erreur
			$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Pas de resultat disponible pour la sélection<br/>";
		} else {
			
			$resultatLecture .= "<b>".str_replace(","," - ",$listeChamps)."</b><br/>";
			if ($exportFichier) {
				$resultatFichier = str_replace(",","\t",$listeChamps);
				if (! fwrite($ExpComp,$resultatFichier."\r\n") ) {
					$resultatLecture .= "erreur ecriture fichier export" ;
					exit;
				}	
			}
			
			while ($finalRow = pg_fetch_row($SQLfinalResult) ) {
				$resultatFichier = "";
				// Construction de la liste des résultat
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
					$resultatFichier .="\r\n";
					if (! fwrite($ExpComp,$resultatFichier."\r\n") ) {
						$resultatLecture .= "erreur ecriture fichier export" ;
						exit;
					}	
				}
				// Compteur
				$cpt1++;
			}
		}
	}
	$compteurItem = $cpt1;

}

function AfficheCategories($typeCategorie,$typeAction,$ListeCE,$changtAction) {
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
	$SQLCEco = "select distinct(".$champID.") from ref_espece where id in (".$SQLEspeces.")";	
	//echo $SQLCEco."<br/>";
	//$SQLCEco = "select * from ref_espece";
	$SQLCEcoResult = pg_query($connectPPEAO,$SQLCEco);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLCEcoResult ) {
		echo "erreur execution SQL pour ".$SQLTest." erreur complete = ".$erreurSQL."<br/>";
	//erreur
	} else { 
		if (pg_num_rows($SQLCEcoResult) == 0) {
			// Erreur
			echo "pas d'especes trouvees dont le id est ".$SQLEspeces."<br/>" ;
		} else { 
			$cptInput = 0;
			// A faire : formater le resultat avec une table
			while ($CERow = pg_fetch_row($SQLCEcoResult) ) {
				if (!($CERow[0] =="" || $CERow[0] == null)) {
					$cptInput ++;
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
							// Si on est en train de changer d'action, on remet à zéro
							if ($changtAction =="y") {
								$checked ="checked=\"checked\"";
							} else {
								// On teste si la valeur a déjà été saisie par l'utilisateur.
								if ($ListeCE == "") {
									$checked =""; 
								} else {
									if (strpos($ListeCE,$CERow[0]) === false) {
										$checked =""; 
									} else {
										$checked ="checked=\"checked\"";
									}
								}
							}
							$libelleRow = pg_fetch_row($SQLlibelleResult)	;
							$libelleCE = $libelleRow[0];
							$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"".$CERow[0]."\" ".$checked."/>&nbsp;".$libelleCE;
							//$construitSelection .= "&nbsp;<input id=\"".$nomInput.$cptInput."\" type=\"checkbox\"  name=\"".$nomInput."\" value=\"".$CERow[0]."\" ".$checked."/>&nbsp;test".$cptInput;
						}
					}// fin du if ( !$SQLtestResult )
				}
				
			} // fin du while
			$construitSelection .= "<input id=\"num".$nomInput."\" type=\"hidden\" name=\"num".$nomInput."\" value=\"".$cptInput ."\"/>";
		}
	}	
	pg_free_result($SQLCEcoResult);
	return $construitSelection;
	
}


function AfficheColonnes($typePeche,$typeAction,$TableEnCours,$numTab) {
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
	// Fichier à analyser
	if ($TableEnCours == "") {$TableEnCours = "py";}
	$TableATester = $TableEnCours;
	$FiliereEnCours = $typeAction;
	$TypePecheEnCours = $typePeche;
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
	$tableau = "<table class=\"ChoixChampComp\"><tr><td class=\"CCCTable\">".$ListeTable." </td><td class=\"CCCChamp\">Colonnes par d&eacute;faut : <br/>".$ListeChampTableDef."<br/>".$ContenuChampTableFac."</td></tr></table>".$inputTableEC.$inputNumFac.$inputNumDef;
	return $tableau; 
}

function AnaylseVarSession($VarSession,$ValeurATester){
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


?>