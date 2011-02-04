<?php 
//*****************************************
// functions_ajax.php
//*****************************************
// Created by Yann Laurent
// 2011-01-19 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilisées dans l'export vers les bases ACCESS
	$listeTablesRef="ref_pays,ref_systeme,ref_secteur,ref_categorie_ecologique,ref_categorie_trophique,ref_ordre,ref_famille,ref_espece,ref_origine_kb,art_categorie_socio_professionnelle,art_etat_ciel";
	$listeTablesParamExp = "exp_contenu,exp_debris,exp_engin,exp_force_courant,exp_position,exp_qualite,exp_remplissage,exp_sediment,exp_sens_courant,exp_sexe,exp_stade,exp_vegetation,exp_station";
	$listeTablesParamArt = "art_grand_type_engin,art_millieu,art_type_activite,art_type_agglomeration,art_type_sortie,art_type_engin,art_vent,art_agglomeration";
	$listeTablesDonneesExp="exp_environnement,exp_campagne,exp_coup_peche,exp_fraction,exp_biologie,exp_trophique";
	$listeTablesDonneesArt="art_unite_peche,art_lieu_de_peche,art_debarquement,art_debarquement_rec,art_engin_peche,art_fraction,art_poisson_mesure,art_activite,art_engin_activite,art_fraction_rec,art_periode_enquete";
	$listeTablesDonneesStat="art_stat_totale,art_stat_gt,art_stat_gt_sp,art_stat_sp,art_taille_gt_sp,art_taille_sp,art_stat_effort";
	// Si il y a des restrictions, des tables continuent à s'exporter de la meme facon, d'autres ont des  restrictions
	//$listeTablesRefSansRestriction = "ref_categorie_ecologique,ref_categorie_trophique,ref_ordre,ref_famille,ref_espece,ref_origine_kb,art_categorie_socio_professionnelle,art_etat_ciel";
	//$listeTablesParamExpSansRestriction = "exp_contenu,exp_debris,exp_engin,exp_force_courant,exp_position,exp_qualite,exp_remplissage,exp_sediment,exp_sens_courant,exp_sexe,exp_stade,exp_vegetation,exp_station";
	//$listeTablesParamArtSansRestriction = "art_grand_type_engin,art_millieu,art_type_activite,art_type_agglomeration,art_type_sortie,art_type_engin,art_vent";
	//$listeTablesRefAvecRestriction = "ref_pays,ref_systeme,ref_secteur";
	//$listeTablesParamExpAvecRestriction = "";
	//$listeTablesParamArtAvecRestriction = "art_agglomeration";


function getNomPays ($IDPays,$connectPPEAO)  {
	$nomPays ="";
	// On récupère le nom du pays
	$SQLPays = "select nom from ref_pays where id ='".$IDPays."'";
	$SQLPaysResult = pg_query($connectPPEAO,$SQLPays);
	$erreurSQL = pg_last_error($connectPPEAO);
	if ( !$SQLPaysResult ) { 
		$nomPays = "erreur de lecture du pays pour l'id = ".$IDPays." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
		
	} else {
		$row = pg_fetch_row($SQLPaysResult);
		$nomPays = $row[0];
	}
	pg_free_result($SQLPaysResult);
	return $nomPays;
	
}

//*********************************************************************
// GetTableStructure : renvoie la structure de la table
function GetTableStructure($TableEnCours,$connectPPEAO) {
// Cette fonction permet de recuperer la structure de la table en cours sous forme d'une liste de champs
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $TableEnCours : contient le nom de la table en cours
//*********************************************************************
// En sortie : 
// La fonction renvoie une chaine contenant une chaine avec les champs de la table separés par une virgule
// Les variables global $SQLTypeChamps  et $nbChampTable sont aussi mises a jour
//*********************************************************************
	global $SQLTypeChamps;
	global $nbChampTable;
	$SQLChamps= "";
	//echo "<b>Structure de la table ".$TableEnCours." </b><br/>";
	$sql = "SELECT table_name, column_name, data_type FROM information_schema.columns WHERE table_name='".$TableEnCours."'";
	$Result = pg_query($connectPPEAO,$sql);
	$erreurSQL = pg_last_error($connectPPEAO);
	if (!$Result) {
		$erreur =  "erreur lecture structure table ".$TableEnCours." - erreur complete = ".$erreurSQL."<br/>";
	} else {
		if (pg_num_rows($Result) == 0) {
				echo "pas de resultat lecture structure table ".$TableEnCours." <br/>";
			} else {
				$nbChampTable=0;
				while ($Row = pg_fetch_row($Result) ) {
					$SQLTypeChamps[$nbChampTable] = $Row[2];	
					//echo $Row[0]." - ".$Row[1]." - ".$Row[2]."  <br/>";
					if ($SQLChamps == "") {
						$SQLChamps = $Row[1];
					} else {
						$SQLChamps .= ",".$Row[1];
					}
					$nbChampTable ++;
				}
			}
	}
	pg_free_result($Result);	
	return $SQLChamps;
	
}
							 


//*********************************************************************
// initializeTempExtraction : vide la table temp_extraction
function initializeTempExtraction($connectPPEAO) {
// Cette fonction permet de vider la table temp_extraction pour preparer la creation des SQL
//*********************************************************************
// En entrée, les paramètres suivants sont :
// N/A
//*********************************************************************
// En sortie : 
// La fonction renvoie une chaine contenant une erreur sinon vide si pas d'erreur
//*********************************************************************
	$erreur = "";
	$SQLDel = "delete from temp_extraction";
		$SQLDelresult = pg_query($connectPPEAO,$SQLDel);
		$erreurSQL = pg_last_error($connectPPEAO);
		if ( !$SQLDelresult ) { 
			$erreur = "erreur Erreur delete temp_extraction , cette table n'existe peut etre pas dans votre base (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
		} 
		pg_free_result($SQLDelresult);
	return $erreur;
}

//*********************************************************************
// createSQLFile : creation du fichier SQL pour la creation de la base
function createSQLFile($fileSQLname, $connectPPEAO) {
// Cette fonction permet de creer le fichier SQL a partir du contenu de la table temp_extraction
//*********************************************************************
// En entrée, les paramètres suivants sont :
// N/A
//*********************************************************************
// En sortie : 
// La fonction renvoie une chaine contenant une erreur sinon vide si pas d'erreur
//*********************************************************************
	$erreur = "";
	$fileSQL=$_SERVER["DOCUMENT_ROOT"]."/work/export/SQL-bdppeao/".$fileSQLname;
	if (!(file_exists($fileSQL)) ) {
		$erreurDir = creeDirTemp($_SERVER["DOCUMENT_ROOT"]."/work/export/SQL-bdppeao/");
		if (strpos($erreurDir,"erreur") === false ){
		} else {
			$erreur = $erreurDir;
		}
	} 
	$fileSQLopen=fopen($fileSQL,'w');
	rewind($fileSQLopen);	
	if ($erreur =="") {
		$sql = "SELECT key1,valeur_ligne FROM temp_extraction ";
		$Result = pg_query($connectPPEAO,$sql);
		$erreurSQL = pg_last_error($connectPPEAO);
		if (!$Result) {
			$erreur =  "erreur lecture temp_extraction - erreur complete = ".$erreurSQL."<br/>";
		} else {
			if (pg_num_rows($Result) == 0) {
				echo "Table temp_extraction vide<br/>";
			} else {
				while ($Row = pg_fetch_row($Result) ) {
					//echo $Row[1]."<br/>";
					$script = str_replace("##quot##","'",$Row[1]);
					
					fwrite($fileSQLopen,$script."\r\n");
					
				}
			}
		}
	}
	fclose($fileSQLopen);
	return $erreur;
}

//*********************************************************************
// getSQLforRefParam : renvoie un script SQL contenant l'ensemble des données pour le référentiel et le paramétrage
function getSQLExport($restrictionPays,$restrictionSysteme,$typeAction,$connectPPEAO) {
// Cette fonction permet de générer le code SQL contenant toutes les données de référentiel et de paramétrage de la base bdppeao
// Ce code est stocké dans la table temporaire tem_extraction
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $restrictionPays = id du pays sur lequel on effectue la selection. Si "", pas de restrictions
// $restrictionSysteme = id(s) du (es) systeme(s) du pays à extraire. Si "", pas de restrictions
// $typeAction = action: 'Tout'; extraire referentiel, parametrage et données
//						 'RefParam':  extraire referentiel et parametrage
//*********************************************************************
// En sortie : 
// La fonction renvoie une chaine contenant une erreur sinon vide si pas d'erreur
//*********************************************************************
	global $listeTablesRef;
	global $listeTablesParamExp;
	global $listeTablesParamArt;
	global $listeTablesDonneesArt;
	global $listeTablesDonneesStat;
	global $listeTablesDonneesExp;
	global $compteRendu;
	global $SQLTypeChamps;
	global $nbChampTable;
	$erreur = "";
	$premierPassage = false;
	
	switch ($typeAction) {
		case "Tout": 
			set_time_limit(0);
			$listeTables= $listeTablesRef.",".$listeTablesParamExp.",".$listeTablesParamArt.",".$listeTablesDonneesExp.",".$listeTablesDonneesArt.",".$listeTablesDonneesStat;
			//$listeTables="art_debarquement";
			break;
		case "RefParam": 
			set_time_limit(90);
			$listeTables= $listeTablesRef.",".$listeTablesParamExp.",".$listeTablesParamArt;
			break;
	}
	$TableEnCours = explode(",",$listeTables);
	$nbrTable = count($TableEnCours)-1;
	for ($cptTable = 0;$cptTable <= $nbrTable;$cptTable++) {
		$SQLTypeChamps = array();
		$SQLChamps = "";
		$nbChampTable=0;
		// Etape 1 : on recupere la structure de la table
		$SQLChamps = GetTableStructure($TableEnCours[$cptTable],$connectPPEAO);
		// Etape 2 : on lit le contenu de la table et on cree les SQL
		//echo "Export de la table ".$TableEnCours[$cptTable]." <br/>";
		$restrictionEcosysteme ="";
		include $_SERVER["DOCUMENT_ROOT"].'/export/gestion_restriction_export.php';
		$sql = "SELECT ".$SQLChamps." FROM ".$TableEnCours[$cptTable]." ".$restrictionEcosysteme;
		//echo $sql."<br/>";
		$Result = pg_query($connectPPEAO,$sql);
		$erreurSQL = pg_last_error($connectPPEAO);
		if (!$Result) {
			$erreur =  "erreur lecture ".$TableEnCours[$cptTable]." - erreur complete = ".$erreurSQL."<br/>";
		} else {
			if (pg_num_rows($Result) == 0) {
				echo "Table ".$TableEnCours[$cptTable]." vide<br/>";
			} else {
				while ($Row = pg_fetch_row($Result) ) {
					$SQLValues = "";
					for ($cptElement = 0;$cptElement < $nbChampTable;$cptElement++) {
							//echo $Row[$cptElement]." type = ".$SQLTypeChamps[$cptElement]." - ";
							$valAAjouter = "";
							// Analyse des types de variables et du contenu
							if (strpos($SQLTypeChamps[$cptElement],"char") === false && 
								strpos($SQLTypeChamps[$cptElement],"text") === false && 
								strpos($SQLTypeChamps[$cptElement],"date") === false && 
								strpos($SQLTypeChamps[$cptElement],"time") === false) {
								if ($Row[$cptElement] == "" || $Row[$cptElement] == null) {
									$valAAjouter = "null"; // Pour eviter des valeurs vides dans le script SQL qui donneraient values (,,,)
								} else {
									$valAAjouter = $Row[$cptElement];
								}
							} else {
								if ($Row[$cptElement] == "") {
									if (strpos($SQLTypeChamps[$cptElement],"date") === false && 
									strpos($SQLTypeChamps[$cptElement],"time") === false) {
										$valAAjouter = "''";
									} else {
										$valAAjouter = "null";
									}
								} else {
									$valQuot = str_replace("'","''",$Row[$cptElement]); // On remplace les quotes qui pourraient etre presentes dans la chaine
									$valAAjouter = "'".$valQuot."'";// une chaine est entouree de quotes dans le SQL
								}
							}
							if ($SQLValues == "") {
								$SQLValues = $valAAjouter;
							} else {
								$SQLValues .= ",".$valAAjouter;
							}
					}
					if ($premierPassage) {
					
					}
					//echo "<br/>insert into ".$TableEnCours[$cptTable]." (".$SQLChamps.") values (".$SQLValues.");<br/>";
					$valeurSQL= "insert into ".$TableEnCours[$cptTable]." (".$SQLChamps.") values (".$SQLValues.");";
					$valeurSQL = str_replace ("'","##quot##",$valeurSQL);
					//  Etape 3: On stocke le SQL dans TempExtraction
					$SQLInsert = "insert into temp_extraction (key1,valeur_ligne) values ('".$TableEnCours[$cptTable]."','".$valeurSQL."')";
					//echo $SQLInsert."<br/>";
					$SQLInsertresult = pg_query($connectPPEAO,$SQLInsert);
					$erreurSQL = pg_last_error($connectPPEAO);
					if ( !$SQLInsertresult ) { 
						echo " Erreur insertion dans temp_extraction - sql = ".$SQLInsertresult." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
					} 
					pg_free_result($SQLInsertresult);
				}
			}
		}
	} // fin du for ($cptTable = 0;$cptTable <= $nbrTable;$cptTable++)
	return $erreur;
}
//*********************************************************************
// getSQLforRefParam : renvoie un script SQL contenant l'ensemble des données pour le référentiel et le paramétrage
function emptyDB($typeAction,$connectPPEAO) {
// Cette fonction permet de générer le code SQL contenant toutes les données de référentiel et de paramétrage de la base bdppeao
// Ce code est stocké dans la table temporaire tem_extraction
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $typeAction = action: 'Tout'; extraire referentiel, parametrage et données
//						 'RefParam':  extraire referentiel et parametrage
// $connectPPEAO = la connexion de la bd a supprimer (permet de choisir un nom different au besoin)

//*********************************************************************
// En sortie : 
// La fonction renvoie une chaine contenant une erreur sinon vide si pas d'erreur
//*********************************************************************
	global $listeTablesRef;
	global $listeTablesParamExp;
	global $listeTablesParamArt;
	global $listeTablesDonneesArt;
	global $listeTablesDonneesStat;
	global $listeTablesDonneesExp;
	global $compteRendu;
	$erreur = "";

	
	switch ($typeAction) {
		case "Tout": 
			set_time_limit(0);
			$listeTables= $listeTablesRef.",".$listeTablesParamExp.",".$listeTablesParamArt.",".$listeTablesDonneesExp.",".$listeTablesDonneesArt.",".$listeTablesDonneesStat;
			break;
		case "RefParam": 
			$listeTables= $listeTablesRef.",".$listeTablesParamExp.",".$listeTablesParamArt;
			break;
	}
	// Etape 1 : on recupere la structure de la table
	$TableEnCours = explode(",",$listeTables);
	$nbrTable = count($TableEnCours)-1;
	//echo $listeTables."<br/>";
	for ($cptTable = 0;$cptTable <= $nbrTable;$cptTable++) {
		//echo "<b>Table ".$TableEnCours[$cptTable]." videe</b><br/>";
		$sql = "delete from ".$TableEnCours[$cptTable];
		$Result = pg_query($connectPPEAO,$sql);
		$erreurSQL = pg_last_error($connectPPEAO);
		if (!$Result) {
			$erreur =  "erreur lecture structure table ".$TableEnCours[$cptTable]." - erreur complete = ".$erreurSQL."<br/>";
		} else {
			$compteRendu.=$TableEnCours[$cptTable]." vid&eacute; <br/>"; 
		}
		pg_free_result($Result);
	}
	return $erreur;
}

//*********************************************************************
// getSQLforRefParam : renvoie un script SQL contenant l'ensemble des données pour le référentiel et le paramétrage
function readAndRunSQL($fileSQLname,$connectPPEAO) {
// Cette fonction permet de générer le code SQL contenant toutes les données de référentiel et de paramétrage de la base bdppeao
// Ce code est stocké dans la table temporaire tem_extraction
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $fileName = Le fichier contenant les SQL
// $connectPPEAO= la connexion de la bd a supprimer (permet de choisir un nom different au besoin)
//*********************************************************************
// En sortie : 
// La fonction renvoie une chaine contenant une erreur sinon vide si pas d'erreur
//*********************************************************************
$erreur="";
	$fileSQL=$_SERVER["DOCUMENT_ROOT"]."/work/export/SQL-bdppeao/".$fileSQLname;
	if (!(file_exists($fileSQL)) ) {
		$erreur = "Le fichier ".$_SERVER["DOCUMENT_ROOT"]."/work/export/SQL-bdppeao/".$fileSQLname." n'est pas pr&eacute;sent sur le serveur. Merci de le copier dans le bon r&eacute;pertoire.<br/>";
		return $erreur;
		exit;
	} 
	$fileSQLopen=fopen($fileSQL,'r');
	while ( ($line = fgets($fileSQLopen)) !== false) {
		$sql = $line;
		$Result = pg_query($connectPPEAO,$sql);
		$erreurSQL = pg_last_error($connectPPEAO);
		if (!$Result) {
			$erreur .=  "erreur execution SQL ".$sql." - erreur complete = ".$erreurSQL."<br/>";
		} 
	  //echo $line."<br>";
	}
return $erreur;
}
?>