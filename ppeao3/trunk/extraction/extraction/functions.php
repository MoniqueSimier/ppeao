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
	$SQLfam = "select id from ref_espece where ref_famille_id in (".$SQLFamille.")";	
	$SQLfamResult = pg_query($connectPPEAO,$SQLfam);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLfamResult ) {
		echo "erreur execution SQL pour ".$SQLfam." erreur complete = ".$erreurSQL."<br/>";
	//erreur
	} else { 
		if (pg_num_rows($SQLfamResult) == 0) {
			// Erreur
			echo "pas d'especes trouvees dont le famille_id est ".$SQLFamille."<br/>" ;
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
	if ($_SESSION['SQLEspeces'] == "") {$_SESSION['SQLEspeces'] = $SQLEspeces;}

	pg_free_result($SQLfamResult);

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
	switch ($typeSelection) {
		case "extraction" :
		switch ($typePeche) {
			case "experimentale" :
			// Prise en compte des sélections complémentaires
			$compSQL = "";
			if 	(!($_SESSION['listeQualite'] =="")) {
				$compSQL =" and cph.exp_qualite_id in (".$_SESSION['listeQualite'].")";
				$restSupp = " Qualite limitee à =".$_SESSION['listeQualite'];
			}
			
			if (!($_SESSION['listeProtocole'] == "")) {
				$compSQL .=" and cph.protocole = ".$_SESSION['listeProtocole']."";
				switch ($_SESSION['listeProtocole']) {
				case "0" : $restSupp .= " - pas restreint aux coups du protocoles ";
							break;
				case "1" : $restSupp .= " - restreint aux coups du protocoles ";
							break;
				}

			}
			// Recuperation des données supplémentaires
			echo $_SESSION['listeColonne']."<br/>";
			$listeChampsSel = "";
			
			// Analyse des champs complémentaires 
			switch ($typeAction) {
				case "peuplement" :
	// Test : dans le cas du peuplement, on doit sortir tous les champs obligatoire communs ie :
	// ref_pays.id, ref_pays.nom, ref_systeme.id, ref_systeme.libelle, ref_secteur.id_dans_systeme, ref_secteur.nom, exp_station.id, exp_station.nom, exp_campagne.date_debut, exp_campagne.code, exp_coup_peche.date_cp, exp_coup_peche.code, exp_coup_peche.protocole, exp_coup_peche.exp_qualite_id, exp_coup_peche.exp_engin_id, exp_engin_libelle
// auquel on ajoute les especes
//ref_espece.id, ref_espece.libelle, ref_espece.ref_categorie_ecologique_id, ref_espece.ref_categorie_trophique_id
// On ajoute les selections de l'utilisateur.
// Et enfin, les sélections spéciales pour la filière peuplement :
// exp_fraction.nombre_total, exp_fraction.poids_total.
	
						$listeChampsCom = "py.id, py.nom, sy.id, sy.libelle, se.id_dans_systeme, se.nom, stat.id, stat.nom, cpg.date_debut, cpg.id, cph.date_cp, cph.id, cph.protocole, cph.exp_qualite_id, cph.exp_engin_id, xeng.libelle,esp.id, esp.libelle, esp.ref_categorie_ecologique_id, esp.ref_categorie_trophique_id";
						
						$listeChampsSpec = ",fra.nombre_total, fra.poids_total";
						$listeChamps = $listeChampsCom.$listeChampsSpec.$listeChampsSel;
						$listeTable = "exp_fraction as fra,ref_pays as py,ref_systeme as sy,ref_secteur as se,exp_campagne as cpg,exp_coup_peche as cph,exp_environnement as env,ref_espece as esp,ref_famille as fam,exp_engin as xeng,exp_station as stat";
						$labelSelection = "Fraction";
						
						// Le sql ci-dessous est un test pour verifier la structure du SQL. Le peuplement n'a pas de données d'environnement
						
						
						$SQLfinal = "select ".$listeChamps." from ".$listeTable." 
						
						left join exp_vegetation on exp_vegetation.id = stat.exp_vegetation_id
						where 
							cpg.id = cph.exp_campagne_id and
							stat.id = cph.exp_station_id and
							env.id = cph.exp_environnement_id and
							sy.id = cpg.ref_systeme_id and
							sy.id in (".$SQLSysteme.") and
							py.id = sy.ref_pays_id and
							se.id = stat.ref_secteur_id and
							cpg.date_debut >='".$SQLdateDebut."/01' and 
							cpg.date_fin <='".$SQLdateFin."/28' and
							cph.exp_engin_id in ('t') and
							xeng.id = cph.exp_engin_id and
							fra.exp_coup_peche_id = cph.id and 
							fra.ref_espece_id in (".$SQLEspeces.") and
							esp.id = fra.ref_espece_id and
							fam.id = esp.ref_famille_id
							".$compSQL;
					//echo "<b>".$SQLfinal;"</b><br/>";
					break;
				case "environnement" :
					break;
				case "NtPt" :
					break;
				case "biologie" :
					break;	
				case "trophique" :
					break;
				default	:	
					$labelSelection = "coup de peches";
					$SQLfinal = "select * from exp_coup_peche CP,exp_fraction FR where CP.exp_campagne_id in (
							select id from exp_campagne where ref_systeme_id in (".$SQLSysteme.") 
														and date_debut >='".$SQLdateDebut."/01'
														and date_fin <='".$SQLdateFin."/28') 
							and CP.exp_engin_id in (".$SQLEngin.") 
							and FR.ref_espece_id in (".$SQLEspeces.") 
							and CP.id = FR.exp_coup_peche_id ".$compSQL;			
			}
		

			break;
			case "artisanale" :
				$SQLfinal = "select * form atr_debarquement";
			break;
			default:
		}
		case "statistiques" :
		break;
		default:
	}
	// Execution de la requete
	$SQLfinalResult = pg_query($connectPPEAO,$SQLfinal);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLfinalResult ) { 
		$CRexecution .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>&nbsp;erreur query ".$SQLfinal." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
		$erreurProcess = true;
	
	} else {
		
		if (pg_num_rows($SQLfinalResult) == 0) {
		// Erreur
			$CRexecution .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>table vide...<br/>";
		} else {
			$cpt1 = 0;
			$resultatLecture = str_replace(","," - ",$listeChamps)."<br/>";
			while ($finalRow = pg_fetch_row($SQLfinalResult) ) {
						switch ($typeAction) {
						case "peuplement" :
								$nbrRow = count($finalRow)-1;
								for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
									$resultatLecture .= $finalRow[$cptRow]." - ";
								}

							break;
						case "environnement" :
							break;
						case "NtPt" :
							break;
						case "biologie" :
							break;	
						case "trophique" :
							break;
						default	:	
							$labelSelection = "coup de peches";
							$SQLfinal = "select * from exp_coup_peche where exp_campagne_id in (
									select id from exp_campagne where ref_systeme_id in (".$SQLSysteme.") 
																and date_debut >='".$SQLdateDebut."/01'
																and date_fin <='".$SQLdateFin."/28') 
									and exp_engin_id in (".$SQLEngin.") ".$compSQL;			
					}
	
					
					$cpt1++;
			}
		}
	}
	$compteurItem = $cpt1;

}

function AfficheCategories($typeCategorie,$typeAction,$ListeCE) {
	// Pour construire les SQL (il faut d'abord avoir rempli ces champs !!!
	// donc avoir appele AfficherSelection
	// Données pour la selection 
	
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

?>