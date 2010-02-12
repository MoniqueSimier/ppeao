<?php 
//*****************************************
// functions_statgene.php
//*****************************************
// Created by Yann Laurent
// 2010-01-26 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilisées pour le calcul des stats generales
// Le fichier de fonctions principales commencait a exploser...
//*****************************************

//*********************************************************************
// TestsuppressionChamp  Fonction de test pour supprimer un enregistrement dans une table
// Cette fonction permet de supprimer des champs dans les lignes de resultat
//*********************************************************************
// En entrée, le nom de la table a tester et la position du champ a tester
// En sortie, le code et le nom du regroupement
function TestsuppressionChamp($table,$TestSupp,$typeStatistiques) {
	// on fait des suppressions a posteriori dans le fichier a extraire
	// Je sais, c'est moche, c'est mal branlé, mais ca reflete l'etat du cahier des charges, ie, le neant
	// Les positions correspondent aux positions des champs dans le SQL construit dans affichedonnees	
	//echo $table."<br/>";
	$debug = false;
	$Asupprimer = false;
	switch ($table) {
		case "NtPart":
			switch ($TestSupp) {
				case 6: $Asupprimer = true;break;// secteur_id
			}
			break;
		case "stats":
			if ($typeStatistiques == "generales") {
				switch ($TestSupp) {
					case 4 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 5 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 6 : $Asupprimer = true; break;	//agglo					
					case 7 : $Asupprimer = true;break;	//agglo id					
					case 10 : $Asupprimer = true;break;
					case 11 : $Asupprimer = true;break;
					case 12 : $Asupprimer = true;break;
					case 13 : $Asupprimer = true;break;
					case 14 : $Asupprimer = true;break;// secteur_id			
				}
			} else {			
				switch ($TestSupp) {
					case 14: $Asupprimer = true;break;// secteur_id
				}
			}
			break;
		case "ast":
			if ($typeStatistiques == "generales") {
				switch ($TestSupp) {
					case 4 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 5 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 6 : $Asupprimer = true; break;						
					case 7 : $Asupprimer = true;break;						
					case 10 : $Asupprimer = true;break;
					case 11 : $Asupprimer = true;break;
					case 12 : $Asupprimer = true;break;
					case 13 : $Asupprimer = true;break;
					case 14 : $Asupprimer = true;break;		
				}
			} else {			
				switch ($TestSupp) {
					case 14: $Asupprimer = true;break;// secteur_id
				}
			}
			break;
		case "asp":
			if ($typeStatistiques == "generales") {
				switch ($TestSupp) {
					case 4 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 5 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 6 : $Asupprimer = true; break;						
					case 7 : $Asupprimer = true;break;						
					case 12 : $Asupprimer = true;break;
					case 13 : $Asupprimer = true;break;
					case 14 : $Asupprimer = true;break;
					case 15 : $Asupprimer = true;break;
					case 16 : $Asupprimer = true;break;
					case 17 : $Asupprimer = true;break;
					case 18 : $Asupprimer = true;break;
					case 19 : $Asupprimer = true;break;					
				}
			} else {
				switch ($TestSupp) {
					case 16: $Asupprimer = true;break;// secteur_id
				}
			}
			break;
		case "ats":
			if ($typeStatistiques == "generales") {
				switch ($TestSupp) {
					case 4 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 5 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 6 : $Asupprimer = true; break;						
					case 7 : $Asupprimer = true;break;						
					case 12 : $Asupprimer = true;break;
					case 13 : $Asupprimer = true;break;
					case 15 : $Asupprimer = true;break;
					case 16 : $Asupprimer = true;break;
					case 17 : $Asupprimer = true;break;
					case 18 : $Asupprimer = true;break;
					case 19 : $Asupprimer = true;break;
					case 20 : $Asupprimer = true;break;					
					case 21 : $Asupprimer = true;break;
					case 22 : $Asupprimer = true;break;
				}
			} else {
				switch ($TestSupp) {
					case 16: $Asupprimer = true;	break;	
					case 17: $Asupprimer = true;break;
					case 18: $Asupprimer = true;break;
					case 19: $Asupprimer = true;break;
				}
			}
			break;
		case "asgt":
			if ($typeStatistiques == "generales") {
				switch ($TestSupp) {
					case 4 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 5 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 6 : $Asupprimer = true; break;						
					case 7 : $Asupprimer = true;break;						
					case 12 : $Asupprimer = true;break;
					case 13 : $Asupprimer = true;break;
					case 14 : $Asupprimer = true;break;
					case 15 : $Asupprimer = true;break;
					case 16 : $Asupprimer = true;break;	
					case 17 : $Asupprimer = true;break;	
				}
			} else {
				switch ($TestSupp) {
					case 17: $Asupprimer = true;break;// secteur_id
				}
			}
			break;
		case "attgt":
			if ($typeStatistiques == "generales") {
				switch ($TestSupp) {
					case 4 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 5 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 6 : $Asupprimer = true; break;						
					case 7 : $Asupprimer = true;break;						
					case 14 : $Asupprimer = true;break;
					case 15 : $Asupprimer = true;break;
					case 16 : $Asupprimer = true;break;	
					case 17 : $Asupprimer = true;break;	
					case 18 : $Asupprimer = true;break;
					case 19 : $Asupprimer = true;break;	
					case 20 : $Asupprimer = true;break;					
					case 21 : $Asupprimer = true;break;
					case 22 : $Asupprimer = true;break;
				}
			} else {
				switch ($TestSupp) {
					case 19: $Asupprimer = true;break;// secteur_id
				}
			}
			break;
		case "atgts":
			if ($typeStatistiques == "generales") {
				switch ($TestSupp) {
					case 4 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 5 : if ($_SESSION['calculStatSysteme ']) {
						 		$Asupprimer = true;
								}
								break;
					case 6 : $Asupprimer = true; break;						
					case 7 : $Asupprimer = true;break;						
					case 14 : $Asupprimer = true;break;
					case 15 : $Asupprimer = true;break;
					case 17 : $Asupprimer = true;break;	
					case 18 : $Asupprimer = true;break;
					case 19 : $Asupprimer = true;break;	
					case 20 : $Asupprimer = true;break;					
					case 21 : $Asupprimer = true;break;
					case 22 : $Asupprimer = true;break;		
					case 23 : $Asupprimer = true;break;					
					case 24 : $Asupprimer = true;break;
					case 25 : $Asupprimer = true;break;	
				}
			} else {
				switch ($TestSupp) {
					case 22: $Asupprimer = true;break;// secteur_id
				}
			}
			break;	
	}
	if ($debug) {
		if ($Asupprimer) {
			echo "- suppression table = ".$table." position =  ".$TestSupp." stat = ".$typeStatistiques."<br/>";
		} else {
			echo "+ pas suppression table = ".$table." position =  ".$TestSupp." stat = ".$typeStatistiques."<br/>";
		}
	}
	return $Asupprimer;
}


//*********************************************************************
// recupereRegroupement  Fonction de recuperation du regroupement pour une espece
// Cette fonction permet de recuperer la valeur d'effort de la table pour un systeme/secteur, annee, mois et GT
//*********************************************************************
// En entrée,l'ID de l'espece
// En sortie, le code et le nom du regroupement
function recupereRegroupement($especeID) {
	$RegTrouve = false;
	$NbReg = count($_SESSION['listeRegroup']);
	for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
		$NbReg2 = count($_SESSION['listeRegroup'][$cptR]);
		for ($cptR2=2 ; $cptR2<=$NbReg2;$cptR2++) {
			$infoEsp = explode("&#&",$_SESSION['listeRegroup'][$cptR][$cptR2]);
			if ($infoEsp[0] == $especeID) {
				$RegTrouve = true;
				$infoReg = explode("&#&",$_SESSION['listeRegroup'][$cptR][1]);
				$CodeRegroupement = $infoReg[0];
				$NomRegroupement = $infoReg[1];
				break;
			}
		}
		if ($RegTrouve) {break;	}
	}
	if (!$RegTrouve) {
		$CodeRegroupement = "DIV";
		$NomRegroupement = "divers";
	}
	return $CodeRegroupement."&#&".$NomRegroupement;
}
//*********************************************************************
// recupereEffort : Fonction de recuperation de l'effort depuis la table
function recupereEffort($systemeId,$SecteurID,$AnneeEC,$MoisEC,$GTEEC) {
// Cette fonction permet de recuperer la valeur d'effort de la table pour un systeme/secteur, annee, mois et GT
//*********************************************************************
// En entrée, les paramètres suivants sont :

	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	global $resultatLecture;
	global $erreurStatGene;
	global $erreurProcess;
	
	$effortSysSect = 0;
	$pasEffortSecteur = false;
	$SQLaExecuter = "select * from art_stat_effort where ref_systeme_id = ".$systemeId." and ref_secteur_id = ".$SecteurID." and annee = ".$AnneeEC." and ref_mois_id = ".$MoisEC." and art_grand_type_engin_id = '".$GTEEC."'";
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
				WriteCompLog ($logComp, "INFO : pas d'effort pour le systeme id = ".$systemeId." et le secteur id = ".$SecteurID." et annee = ".$AnneeEC." et mois = ".$MoisEC." et GTE = ".$GTEEC.", on recherche l'effort sur le systeme",$pasdefichier);
			}
			$pasEffortSecteur = true;
		} else {
			$ResultRow = pg_fetch_row($SQLResult);
			$effortSysSect = $ResultRow[8];
			$sectSystEncours = "sect-".$SecteurID; // On va faire le calcul par secteur
			pg_free_result($SQLResult);
		}
	}
	// La recherche sur le secteur a echoue, on cherche la valeur par systeme
	if ($pasEffortSecteur) {
		// On recherche l'effort pour le systeme en cours
		$SQLaExecuter = "select * from art_stat_effort as aeff where ref_systeme_id = ".$systemeId." and annee = ".$AnneeEC." and ref_mois_id = ".$MoisEC." and art_grand_type_engin_id = '".$GTEEC."'";
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
				if (strpos ($erreurStatGene,"Pas d'effort ni pour le systeme ".$systemeId." ni pour le secteur ".$SecteurID." pour annee = ".$AnneeEC." et mois = ".$MoisEC." et GTE = ".$GTEEC."<br/>") === false ) {
					if ($EcrireLogComp) {
						WriteCompLog ($logComp, "ERREUR : pas d'effort pour le systeme ".$systemeId." ni pour le secteur ".$SecteurID." pour annee = ".$AnneeEC." et mois = ".$MoisEC." et GTE = ".$GTEEC.", arret du traitement.",$pasdefichier);
					}
					if (strpos($resultatLecture,"Erreur dans les calcul des stats generales, consulter le fichier des selections pour plus de details (fichier inclus dans le zip)") === false) {
						$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>Erreur dans les calcul des stats generales, consulter le fichier des selections pour plus de details (fichier inclus dans le zip)<br/>";
					}
					//$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>pas d'effort ni pour le systeme ".$systemeId." ni pour le secteur ".$SecteurID." pour annee = ".$anneeEC." et mois = ".$MoisEC." et GTE = ".$GTEEC.". Arret du calcul<br/>";
					$erreurStatGene .= "Pas d'effort ni pour le systeme ".$systemeId." ni pour le secteur ".$SecteurID." pour annee = ".$AnneeEC." et mois = ".$MoisEC." et GTE = ".$GTEEC."<br/>";
				}
				$erreurProcess = true;
			} else {
				if (pg_num_rows($SQLResult) > 1) {
					echo "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>La saisie des efforts n'est pas cohérente. Les calculs suivant seront faux.<br/>";
					exit;
				}
				$ResultRow = pg_fetch_row($SQLResult);
				$effortSysSect = $ResultRow[8];
				$sectSystEncours = "syst-".$systemeId; // On va faire le calcul par systeme
				pg_free_result($SQLResult);
			}
		}
	}
	return $sectSystEncours."&#&".$effortSysSect;
}
//*********************************************************************
// creeTableCaptEsp : Fonction de
function creeTableCaptEsp($tableStat,$systeme,$sect,$annee,$mois,$GTE,$esp,$captureTot){
// Cette fonction permet de 
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $tableStat,$systemePrec,$sectPrec,$anneePrec,$moisPrec,$GTEPrec,$espPrec
//*********************************************************************
// En sortie : 
// 
//*********************************************************************
	// On remplit le tableau temporaire contenant la liste des especes et de la capture par espece pour pouvoir faire le total
	$numEnrEff = count($_SESSION['listeEffortEspeces']) ;
	$dejaPresent = false;
	for ($cptEff=1 ; $cptEff<=$numEnrEff;$cptEff++) {
		if ($_SESSION['listeEffortEspeces'][$cptEff][1] == $tableStat && 
			$_SESSION['listeEffortEspeces'][$cptEff][2] == $systeme &&
			$_SESSION['listeEffortEspeces'][$cptEff][3] == $sect &&			
			$_SESSION['listeEffortEspeces'][$cptEff][4] == $annee &&
			$_SESSION['listeEffortEspeces'][$cptEff][5] == $mois &&
			$_SESSION['listeEffortEspeces'][$cptEff][6] == $GTE && 
			$_SESSION['listeEffortEspeces'][$cptEff][7] == $esp
			) {
			$_SESSION['listeEffortEspeces'][$numEnrEff][8] = $captureTot; // Mise a jour des captures cumulées
			$_SESSION['listeEffortEspeces'][$numEnrEff][9] = $captureTot; // En double pour pouvoir faire le calcul des captures totales pour toutes les especes
			$dejaPresent = true;
			break;
		}
	}
	if (!$dejaPresent) {
		// On recupère l'effort
		$RecEffortSysSect = recupereEffort($systeme,$sect,$annee,$mois,$GTE);
		$tabEffortSysSect = explode ("&#&",$RecEffortSysSect); // tableau contenant le resultat de la requete : [type]-[valeur sect/syst]&#&[valeur effort] 
		$effortSysSect = floatval($tabEffortSysSect[1]);
		$tabsectSystEncours = explode ("-",$tabEffortSysSect[0]);
		$typesectSystEncours = $tabsectSystEncours[0];
		$sectSystEncours = intval($tabsectSystEncours[1]);
		$numEnrEff ++;
		$_SESSION['listeEffortEspeces'][$numEnrEff][1] = trim($tableStat); 
		$_SESSION['listeEffortEspeces'][$numEnrEff][2] = $systeme; 
		$_SESSION['listeEffortEspeces'][$numEnrEff][3] = $sect; 				
		$_SESSION['listeEffortEspeces'][$numEnrEff][4] = $annee; 
		$_SESSION['listeEffortEspeces'][$numEnrEff][5] = $mois; 
		$_SESSION['listeEffortEspeces'][$numEnrEff][6] = $GTE; 
		$_SESSION['listeEffortEspeces'][$numEnrEff][7] = $esp; 
		$_SESSION['listeEffortEspeces'][$numEnrEff][8] = $captureTot; // Capture cumulee pour l'espece
		$_SESSION['listeEffortEspeces'][$numEnrEff][9] = $captureTot; // En double pour pouvoir faire le calcul des captures totales pour toutes les especes
		$_SESSION['listeEffortEspeces'][$numEnrEff][10] = 0; // Pour stocker le prorata
		$_SESSION['listeEffortEspeces'][$numEnrEff][11] = $effortSysSect;
		$_SESSION['listeEffortEspeces'][$numEnrEff][12] = $typesectSystEncours; // Type d'effort sect ou systeme
		$_SESSION['listeEffortEspeces'][$numEnrEff][13] = $sectSystEncours; // sect ou systeme 
		}
}

//*********************************************************************
// analyseTableCaptEsp : Fonction de
function analyseTableCaptEsp(){
// Cette fonction permet de mettre a jour la valeur de la capture totale le tableau contenant les captures par espece
// l'objectif est de remplir $_SESSION['listeEffortEspeces'][$i][9] 
//*********************************************************************
// En entrée, les paramètres suivants sont : néant
//*********************************************************************
// En sortie : la variable de session mise à jour
// 
// Hum, c'est peut etre plus necessaire car la somme des captures par especes par agglo/secteur ne correspond pas a la somme des captures totales par agglo/secteur
// Certainement, a enlever....
//
//*********************************************************************
	//print_r($_SESSION['listeEffortEspeces']);
	//echo"<br/>";
	//echo "<table >";
	$numEnrEff = count($_SESSION['listeEffortEspeces']) ;
	for ($cptEff=1 ; $cptEff<=$numEnrEff;$cptEff++) {
		//echo $_SESSION['listeEffortEspeces'][$cptEff][1]." - ".$_SESSION['listeEffortEspeces'][$cptEff][2]." - ".
		//		$_SESSION['listeEffortEspeces'][$cptEff][3]." - ".$_SESSION['listeEffortEspeces'][$cptEff][4]." - ".$_SESSION['listeEffortEspeces'][$cptEff][5]." - ".
		//		$_SESSION['listeEffortEspeces'][$cptEff][6]." - ".$_SESSION['listeEffortEspeces'][$cptEff][7]." - ".$_SESSION['listeEffortEspeces'][$cptEff][8]."<br/> ";
				
		if ( ($_SESSION['listeEffortEspeces'][$cptEff][1] == $tableStatPrec && 
				$_SESSION['listeEffortEspeces'][$cptEff][2] == $systemePrec &&
				$_SESSION['listeEffortEspeces'][$cptEff][3] == $sectPrec &&			
				$_SESSION['listeEffortEspeces'][$cptEff][4] == $anneePrec &&
				$_SESSION['listeEffortEspeces'][$cptEff][5] <> $moisPrec ) || 
				($_SESSION['listeEffortEspeces'][$cptEff][1] == $tableStatPrec && 
				$_SESSION['listeEffortEspeces'][$cptEff][2] == $systemePrec &&
				$_SESSION['listeEffortEspeces'][$cptEff][3] == $sectPrec &&			
				$_SESSION['listeEffortEspeces'][$cptEff][4] == $anneePrec &&
				$_SESSION['listeEffortEspeces'][$cptEff][5] == $moisPrec &&
				$_SESSION['listeEffortEspeces'][$cptEff][6] <> $GTEPrec)
			) {
				//echo "<tr><td>rupture ".$_SESSION['listeEffortEspeces'][$cptEff][7]."</td><td> ".$captureTotales."</td><td> total = ".$_SESSION['listeEffortEspeces'][$cptEff][9]."</td></tr>";
				// On met a jour la valeur de la capture totale et on calcule le prorata cap esp / cap totale
				for ($cptMAJ=1 ; $cptMAJ<$numEnrEff;$cptMAJ++) {
					if ($_SESSION['listeEffortEspeces'][$cptMAJ][1] == $tableStatPrec && 
						$_SESSION['listeEffortEspeces'][$cptMAJ][2] == $systemePrec &&
						$_SESSION['listeEffortEspeces'][$cptMAJ][3] == $sectPrec &&			
						$_SESSION['listeEffortEspeces'][$cptMAJ][4] == $anneePrec &&
						$_SESSION['listeEffortEspeces'][$cptMAJ][5] == $moisPrec
						) { 
							// Maj captures totales
							if ($captureTotales == 0) {
								$_SESSION['listeEffortEspeces'][$cptMAJ][9] = 0;
								$_SESSION['listeEffortEspeces'][$cptMAJ][10] = 0;
							} else {
								$_SESSION['listeEffortEspeces'][$cptMAJ][9] = $captureTotales;
								// Maj prorata
								//$_SESSION['listeEffortEspeces'][$cptMAJ][10] = floatval($_SESSION['listeEffortEspeces'][$cptMAJ][8]) / $captureTotales;
								$_SESSION['listeEffortEspeces'][$cptMAJ][10] = floatval($_SESSION['listeEffortEspeces'][$cptMAJ][8]);
							}
					}
				}
			$captureTotales = 0;
		} else {
			//echo "<tr><td>somme ".$_SESSION['listeEffortEspeces'][$cptEff][7]."</td><td>".$captureTotales." </td><td>total = ".$_SESSION['listeEffortEspeces'][$cptEff][9]."</td></tr>";
			$captureTotales = $captureTotales + floatval($_SESSION['listeEffortEspeces'][$cptEff][8]);
		}
		$tableStatPrec = $_SESSION['listeEffortEspeces'][$cptEff][1]; 
		$systemePrec = $_SESSION['listeEffortEspeces'][$cptEff][2] ;
		$sectPrec = $_SESSION['listeEffortEspeces'][$cptEff][3] ;			
		$anneePrec = $_SESSION['listeEffortEspeces'][$cptEff][4] ;
		$moisPrec = $_SESSION['listeEffortEspeces'][$cptEff][5];
		$GTEPrec = $_SESSION['listeEffortEspeces'][$cptEff][6] ; 		
	}
	//echo "</table >";
	//echo "capt totale = ".$captureTotales."<br/>";
	// Maj pour le dernier enregistrement
	//echo "maj dernier enreg ".$_SESSION['listeEffortEspeces'][$cptEff][6]." ".$captureTotales."<br/>";
	// On met a jour la valeur de la capture totale et on calcule le prorata cap esp / cap totale
	//echo "<table >";
	for ($cptMAJ=1 ; $cptMAJ<$numEnrEff;$cptMAJ++) {
		if ($_SESSION['listeEffortEspeces'][$cptMAJ][1] == $tableStatPrec && 
			$_SESSION['listeEffortEspeces'][$cptMAJ][2] == $systemePrec &&
			$_SESSION['listeEffortEspeces'][$cptMAJ][3] == $sectPrec &&			
			$_SESSION['listeEffortEspeces'][$cptMAJ][4] == $anneePrec &&
			$_SESSION['listeEffortEspeces'][$cptMAJ][5] == $moisPrec
			) { 
				// Maj captures totales
				$_SESSION['listeEffortEspeces'][$cptMAJ][9] = $captureTotales;
				// Maj prorata
				//$_SESSION['listeEffortEspeces'][$cptMAJ][10] = floatval($_SESSION['listeEffortEspeces'][$cptMAJ][8]) / $captureTotales;
				$_SESSION['listeEffortEspeces'][$cptMAJ][10] = floatval($_SESSION['listeEffortEspeces'][$cptMAJ][8]);
				//echo "<tr><td>MAJ espece ".$_SESSION['listeEffortEspeces'][$cptMAJ][7]." </td><td> ".$_SESSION['listeEffortEspeces'][$cptMAJ][8]." </td><td>".$_SESSION['listeEffortEspeces'][$cptMAJ][9]." </td><td> ".$_SESSION['listeEffortEspeces'][$cptMAJ][10]."</td></tr>";						
		}
	}
	//echo "</table >";
}

//*********************************************************************
// AjoutEnreg : ajoute un enreg dans la table temporaire
function AjoutEnreg($regroupDeb,$debIDPrec,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$posStat4,$posStat5,$finalRow,$typeStatistiques,$tableStat,$posLongueur){
// Cette fonction permet d'ajouter les lignes du tableau temporaire regroupDeb dans la table temp_extraction
// Le calcul final des efforts / captures et PUE se fait ici
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $regroupDeb : le tableau contenant les lignes a ajouter
// $debIDPrec : id du debarquement, ou secteur/systeme - annee - mois - GTE quand statistiques generales
// $posESPID
// $posESPNom
// $posStat1
// $posStat2
// $posStat3
// $posStat4
// $posStat5
// $finalRow
// $typeStatistiques
// $tableStat
// $posLongueur
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
	//echo "<b> table = ".$tableStat."<br/>";
	if ($NbRegDeb >= 1 ) {
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : mise à jour de la table TEMP_EXTRACTION",$pasdefichier);
			WriteCompLog ($logComp, "DEBUG : parametres = debIDPrec ".$debIDPrec,$pasdefichier);
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
			$ColonneTE .= ",key5";
			$ValuesTE .= ",'".$regroupDeb[$cptRg][9]."'";
			$ColonneTE .= ",key6";
			$ValuesTE .= ",'".$regroupDeb[$cptRg][10]."'";			
	// Analyse de la ligne, on remplace l'espece par le regroupement et les valeurs poids et nombre par les valeurs agrégées
			$nbrRow = count($finalRow)-1;
			$ligneResultat = "";
			if ($tableStat == "ats" || $tableStat == "atgts") {
				// On doit recuperer uniquement le code du regroupement sans la taille
				$infoReg = explode("-",$regroupDeb[$cptRg][1]);
				$nomReg = $infoReg[0];
				$nomTaille = $infoReg[1];
			} else {
				$nomReg = $regroupDeb[$cptRg][1];
				$nomTaille = "";
			}
			for ($cptRow = 0;$cptRow <= $nbrRow;$cptRow++) {
				$Asupprimer = TestsuppressionChamp($tableStat,$cptRow,$typeStatistiques);
				if (!$Asupprimer) {					
				switch ($cptRow) {
					case 0 :
						$ligneResultat .= "&#&".$finalRow[$cptRow];
						break;				
					case $posESPID :
						$ligneResultat .= "&#&".$nomReg;
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
					case $posStat4 :
						$ligneResultat .= "&#&".$regroupDeb[$cptRg][7];
						break;
					case $posStat5 :
						$ligneResultat .= "&#&".$regroupDeb[$cptRg][8];						
						break;
					case $posLongueur :// uniquement pour les stats par taille
						if ($tableStat == "ats" || $tableStat == "atgts") {
							$ligneResultat .= "&#&".$nomTaille;
						} else {
							$ligneResultat .= "&#&".$finalRow[$cptRow];
						}
						break;
					default :
						$ligneResultat .= "&#&".$finalRow[$cptRow]; 
						break;
					}
				} // fin du switch
			}
			//echo $ligneResultat."<br/>";
			if ($typeStatistiques == "generales") {
				$messErreur = "Erreur car division par 0";
				$effort = floatval($regroupDeb[$cptRg][9]);
				$prorata = floatval($regroupDeb[$cptRg][10]);
				// Pour les statistiques generales, on effectue le calcul de l'effort total pour la strate correspondante
				// En fonction de la table sur laquelle on est, on a deux types de calcul
				// sur art_stat_totale et art_stat_GT, on calcule l'effort total
				// sur les autres, on calcule le prorata de l'effort total soit par especes, soit par GT, soit par GT/espece.
				switch ($tableStat) {
					case "ast":
						// Ici, en 3 on a la somme des efforts, en 4 la somme des captures, en 9 l'effort saisi.
						if (floatval($regroupDeb[$cptRg][3]) <> 0 ) {
							//echo $regroupDeb[$cptRg][4]." - ".$regroupDeb[$cptRg][3]."<br/>";
							$PueStrate = floatval($regroupDeb[$cptRg][4]) / floatval($regroupDeb[$cptRg][3]);
							$EffortStrate = $effort;
							$CaptureStrate = $PueStrate * $effort;
							$ligneResultat .= "&#&".$PueStrate."&#&".$EffortStrate."&#&".$CaptureStrate;
							// On ajoute un enreg dans le tableau temporaire des efforts
							$numEnrEff = count($_SESSION['listeEffortTotal']) + 1;
							$ajoutEffOK = true;
							for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
								$infoEff = explode("&#&",$_SESSION['listeEffortTotal'][$cptEff][1]);
								if ($infoEff[0] == $debIDPrec) {
									$ajoutEffOK = false;
									break;
								}
							}
							if ($ajoutEffOK) {$_SESSION['listeEffortTotal'][$numEnrEff] = $debIDPrec."&#&".$EffortStrate."&#&".$PueStrate."&#&".$regroupDeb[$cptRg][4]."&#&".$regroupDeb[$cptRg][3];}
						} else {
							$ligneResultat .= "&#&".$messErreur;
						}
						break;	
					case "asp":
						// Ici, en 3 on a la somme des efforts, en 9 l'effort saisi,en 10 le prorata et en 11 la pue.
						$pue = floatval($regroupDeb[$cptRg][11]);
						$CapturesTotal = $pue * $effort;
						$CapturesSp =  $CapturesTotal * $prorata;
						if ($effort == 0) {
							$pue_sp = 0;
						} else {
							$pue_sp = $CapturesSp / $effort;
						}
						$ligneResultat .= "&#&".$pue_sp."&#&".$CapturesSp."&#&".$pue."&#&".$effort."&#&".$CapturesTotal;	

						break;
					case "ats":
						// Ici, en 3 on a la somme des efforts, en 9 l'effort saisi,en 10 le prorata et en 11 la pue.
						$pue = floatval($regroupDeb[$cptRg][11]);
						$effortSp = floatval($regroupDeb[$cptRg][12]);
						$CapturesTotal = $pue * $effort;
						$CapturesSp =  $CapturesTotal * $prorata;
						if ($effort == 0) {
							$pue_sp = 0;
						} else {
							$pue_sp = $CapturesSp / $effort;
						}
						if ($effortSp == 0) {
							//echo "Erreur effort pour l'espece ".$finalRow[$posESPID]." est nul.<br/>";
							$Coeff = 0;
						} else {
							$Coeff = $CapturesSp / $effortSp;
						}
						//echo "coef = ".$Coeff."<br/>";
						$CapturesTaille = floatval($regroupDeb[$cptRg][3]) * $Coeff;
						$ligneResultat .= "&#&".$CapturesTaille."&#&".$pue_sp."&#&".$CapturesSp."&#&".$pue."&#&".$effort."&#&".$CapturesTotal;

						break;
					case "asgt":
						$pue = floatval($regroupDeb[$cptRg][11]);
						$effortTotal = floatval($regroupDeb[$cptRg][13]);
						$CapturesTotal = $pue * $effort;
						if ($effortTotal == 0) {
							$CapturesGTE = 0;
						} else {
							$CapturesGTE =  $regroupDeb[$cptRg][4]*$CapturesTotal/$effortTotal;
						}
						if ($regroupDeb[$cptRg][3] <> 0) {
							$PueGTE = $regroupDeb[$cptRg][4]/$regroupDeb[$cptRg][3];
							if ($PueGTE == 0) {
								$EffortGTE = 0;
							} else {
								$EffortGTE = $CapturesGTE/$PueGTE;
							}
						} else {
							$PueGTE = 0;
							$EffortGTE = 0;
						}
						$ligneResultat .= "&#&". $PueGTE."&#&".$EffortGTE."&#&".$regroupDeb[$cptRg][4];
						// On ajoute un enreg dans le tableau temporaire des efforts
						$numEnrEff = count($_SESSION['listeEffortGTETotal']) + 1;
						$ajoutEffOK = true;
						for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
							$infoEff = explode("&#&",$_SESSION['listeEffortGTETotal'][$cptEff][1]);
							if ($infoEff[0] == $debIDPrec) {
								$ajoutEffOK = false;
								break;
							}
						}
						if ($ajoutEffOK) {$_SESSION['listeEffortGTETotal'][$numEnrEff] = $debIDPrec."&#&".$EffortGTE."&#&".$PueGTE."&#&".$regroupDeb[$cptRg][4] ; }
						
						break;
					case "attgt":
						$puestrate = floatval($regroupDeb[$cptRg][11]);
						$effortTotal = floatval($regroupDeb[$cptRg][13]);
						$EffortGTEStrate = floatval($regroupDeb[$cptRg][14]) ;
						$CapturesGTEStrate = $puestrate *  $EffortGTEStrate;
						$CapturesGTE = floatval($regroupDeb[$cptRg][15]) ;
						if ( $CapturesGTE == 0) {
							$prorataGTE =0;
							$CapturesGTEEsp = 0;
						} else {
							$prorataGTE = $regroupDeb[$cptRg][4] / $CapturesGTE;
							$CapturesGTEEsp = $CapturesGTEStrate  * $prorataGTE;
							$EffortGTEEsp = $EffortGTEStrate  * $prorataGTE;
							if (  $EffortGTEEsp <> 0) {
								$PUEGTEEsp = $CapturesGTEEsp / $EffortGTEEsp;
							}
						}
						$ligneResultat .= "&#&". $PUEGTEEsp."&#&".$EffortGTEEsp."&#&".$CapturesGTEEsp."&#&". $puestrate."&#&".$EffortGTEStrate."&#&".$CapturesGTEStrate;
						break;
					case "atgts":
						$puestrate = floatval($regroupDeb[$cptRg][11]);
						$effortTotal = floatval($regroupDeb[$cptRg][13]);
						$EffortGTEStrate = floatval($regroupDeb[$cptRg][14]) ;
						$CapturesGTEStrate = $puestrate *  $EffortGTEStrate;
						$CapturesGTE = floatval($regroupDeb[$cptRg][15]) ;
						if ( $CapturesGTE == 0) {
							$prorataGTE =0;
							$CapturesGTEEsp = 0;
						} else {
							$prorataGTE = $regroupDeb[$cptRg][4] / $CapturesGTE;
							$CapturesGTEEsp = $CapturesGTEStrate  * $prorataGTE;
							$EffortGTEEsp = $EffortGTEStrate  * $prorataGTE;
							if (  $EffortGTEEsp <> 0) {
								$PUEGTEEsp = $CapturesGTEEsp / $EffortGTEEsp;
							}
						}

						$pue = floatval($regroupDeb[$cptRg][16]);
						$effortSp = floatval($regroupDeb[$cptRg][12]);
						$CapturesTotal = $pue * $effort;
						$CapturesSp =  $CapturesTotal * $prorata;
						if ($effortSp == 0) {
							$Coeff = 0;
						} else {
							$Coeff = $CapturesSp / $effortSp;
						}	
						//echo $effortSp."-".$CapturesTotal."-". $prorata."-".$CapturesSp."-".$Coeff."<br/>";
						$CapturesTaille = floatval($regroupDeb[$cptRg][3]) * $Coeff;
						$ligneResultat .= "&#&".$CapturesTaille."&#&". $PUEGTEEsp."&#&".$EffortGTEEsp."&#&".$CapturesGTEEsp."&#&". $puestrate."&#&".$EffortGTEStrate."&#&".$CapturesGTEStrate;
						break;
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
// creeRegroupement : Fonction de creation d'un regroupement a partir d'un SQL
function creeRegroupement($SQLaExecuter,$posDEBID ,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$posStat4,$posStat5,$typeSelection,$tableStat,$Compteur,$posSysteme,$posSecteur,$posGTE,$creationRegBidon,$typeStatistiques,$prorataTot,$prorataESPGT,$posRupSup) {
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
	global $erreurStatGene;
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
	//echo "<b>".$tableStat."</b><br/>";
	//echo $SQLaExecuter."<br/>";
	if ($EcrireLogComp && $debugLog && $typeStatistiques == "generales") {
		WriteCompLog ($logComp, "DEBUG : tables stat = ".$tableStat,$pasdefichier);
	}

	if ($typeStatistiques == "generales" && $tableStat == "asp") {
		// On doit faire un calcul preliminaire des totaux par espece
		$SQLfinalResult = pg_query($connectPPEAO,$SQLaExecuter);
		$erreurSQL = pg_last_error($connectPPEAO);
		if ( !$SQLfinalResult ) { 
			if ($EcrireLogComp ) {WriteCompLog ($logComp, "ERREUR : Erreur query final regroupements ".$SQLaExecuter." (erreur complete = ".$erreurSQL.")",$pasdefichier);}
		} else {
			if (pg_num_rows($SQLfinalResult) == 0) {
				if ($EcrireLogComp ) {	WriteCompLog ($logComp, "Regroupements : pas de resultat disponible pour la selection ".$SQLaExecuter,$pasdefichier);}

			} else {
				$captureTotaleEsp = 0;
				$testEffortSysteme = false;
				while ($finalRow = pg_fetch_row($SQLfinalResult) ) {
					$anneeEnCours = $finalRow[8];		//annee
					$moisEnCours = $finalRow[9];	//mois
					$systemeEnCours = $finalRow[2];	// systeme
					if ($_SESSION['calculStatSysteme ']) {
						$sectEnCours = -1; // Stats calculees au niveau du systeme
					} else {
						$sectEnCours = $finalRow[$posSecteur]; // Secteur
					}
					$espEnCours = $finalRow[$posESPID];		// Especes
					if ($posGTE == -1) { $GTEEnCours = "TOUS";} else {$GTEEnCours = $finalRow[$posGTE];}
					if ($posRupSup == -1) {$posRupSupEnCours = 0;} else {$posRupSupEnCours = $finalRow[$posRupSup];}; // La longueur des especes pour la repartition par taille
					if ( ($systemeEnCours<> $systemePrec) || 
							($systemeEnCours == $systemePrec && $sectEnCours<>$sectPrec ) ||
							($systemeEnCours == $systemePrec && $sectEnCours==$sectPrec && $anneeEnCours<>$anneePrec ) ||
							($systemeEnCours == $systemePrec && $sectEnCours==$sectPrec && $anneeEnCours==$anneePrec && $moisEnCours<>$moisPrec) || 
							($systemeEnCours == $systemePrec && $sectEnCours==$sectPrec && $anneeEnCours==$anneePrec && $moisEnCours==$moisPrec && $GTEEnCours<>$GTEPrec) ||
							($systemeEnCours == $systemePrec && $sectEnCours==$sectPrec && $anneeEnCours==$anneePrec && $moisEnCours==$moisPrec && $GTEEnCours==$GTEPrec && $espEnCours<>$espPrec) ) {
							//echo "rupture = ".$sectPrec." esp = ".$espPrec." somme = ".$captureTotaleEsp." cap encours = ".floatval($finalRow[$prorataESPGT])." cap prec = ".$ValCapPrec."<br/>";
							if ($espPrec <> "") {
								// On recherche le regroupement pour l'espece precedente.
								$CodeNomReg = recupereRegroupement($espPrec);
								$infoReg = explode("&#&",$CodeNomReg);
								$RegEnCours = $infoReg[0];
								//echo "regroupement = ".$RegEnCours." nom = ".$infoReg[1]."<br/>";
								creeTableCaptEsp($tableStat,$systemePrec,$sectPrec,$anneePrec,$moisPrec,$GTEPrec,$RegEnCours,$captureTotaleEsp);
							}
							$captureTotaleEsp = floatval($finalRow[$prorataESPGT]);
					} else {
						// On fait la somme
						//echo "somme = ".$captureTotaleEsp." - ".floatval($finalRow[$prorataESPGT])."<br/>";
						$captureTotaleEsp = $captureTotaleEsp + floatval($finalRow[$prorataESPGT]);
					}
					$espPrec = $espEnCours;
					$RegPrec = $RegEnCours;
					$DerniereLigne = $finalRow;
					$systemePrec = $systemeEnCours ;
					$sectPrec = $sectEnCours ;
					$anneePrec= $anneeEnCours;
					$moisPrec= $moisEnCours;
					$GTEPrec = $GTEEnCours;
					$typesectSystPrec = $typesectSystEncours;
					$ValCapPrec = floatval($finalRow[$prorataESPGT]);
					$posRupSupPrec = $posRupSupEnCours;
				
				} // fin du while
				// Cree l'enreg pour la derniere table
				creeTableCaptEsp($tableStat,$systemePrec,$sectPrec,$anneePrec,$moisPrec,$GTEPrec,$espPrec,$captureTotaleEsp,$posRupSupEnCours);
			}
		} // fin du if ( !$SQLfinalResult ) {} else {
		analyseTableCaptEsp(); 

	} // fin du if ($typeStatistiques == "generales" && $tableStat == "asp") {
	//echo('<pre>');print_r($_SESSION['listeEffortTotal']);echo('</pre>');
	//echo "<br/>";
	$SQLfinalResult = pg_query($connectPPEAO,$SQLaExecuter);
	$erreurSQL = pg_last_error($connectPPEAO);
	$cpt1 = 0;
	$AffichageTypeStats = false;
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
				if ($EcrireLogComp && $debugLog) {
					WriteCompLog ($logComp, "DEBUG : ligne en cours val sta1 = ".$finalRow[$posStat1]." - val sta2 = ".$finalRow[$posStat2]." - val sta3 = ".$finalRow[$posStat3],$pasdefichier);
				}
				// ********************************
				// **** GESTION DES STATS GENERALES
				if ($typeStatistiques == "generales") {
					// Gestion du cas des stats generales
					// Les ruptures sont les memes. Elles dependent en plus de ce qu'on va trouver dans la table des efforts.
					$anneeEnCours = $finalRow[8];		//annee
					$moisEnCours = $finalRow[9];	//mois
					$systemeEncours = $finalRow[2];	// systeme
					if ($_SESSION['calculStatSysteme ']) {
						$sectEnCours = -1;
					} else {
						$sectEnCours = $finalRow[$posSecteur]; // Secteur
					}
					$espEnCours = $finalRow[$posESPID];		// Especes
					if ($posGTE == -1) { $GTEEnCours = "TOUS";} else {$GTEEnCours = $finalRow[$posGTE];}
					if ($posRupSup == -1) {$posRupSupEnCours = 0;} else {$posRupSupEnCours = $finalRow[$posRupSup];}; // La longueur des especes pour la repartition par taille
					$RecEffortSysSect = "";
					$prorata 			= 0;
					$effortSysSect 		= 0;
					$typesectSystEncours = "";
					$sectSystEncours 	= 0;
					$pueSysSect = 0;
					$effortEsp = 0;
					$effortGTESysSect = 0;
					$CapturesGTESysSect=0;
					//echo $tableStat."-".$systemeEncours."-".$sectEnCours."-".$anneeEnCours."-".$moisEnCours."<br/>";
					// Ici se recuperent toutes les valeurs necessaires pour effectuer le calcul.
					// Le calcul reel ne s'effectuera qu'au moment de la creation de la ligne a ajouter dans la table temp, ie dans la fonction ajoutEnreg()
					// Les valeurs necessaires seront stockes dans le tableau $regroupDeb	(d'ou les milliards de parametres passes a la fonction qui cree chaque entree du tableau $regroupDeb).
					// Les Coefficients se calculent par table.
					// On stocke dans 2 tableaux supplémentaires les calculs faits au niveau des stats totales (==>	listeEffortTotal) et stats totales par GT (==>listeEffortGTETotal)
					// pour pouvoir les reutiliser.
					// Bon courage pour debugger...
					switch ($tableStat) {
						case "ast":
						// Si on trouve que le calcul se fait par systeme, pas la peine de chercher l'effort a chaque fois
							$RecEffortSysSect = recupereEffort($systemeEncours,$sectEnCours,$anneeEnCours,$moisEnCours,$GTEEnCours);
							$tabEffortSysSect = explode ("&#&",$RecEffortSysSect); // tableau contenant le resultat de la requete : [type]-[valeur sect/syst]&#&[valeur effort] 
							$effortSysSect = floatval($tabEffortSysSect[1]);
							$tabsectSystEncours = explode ("-",$tabEffortSysSect[0]);
							$typesectSystEncours = $tabsectSystEncours[0]; // Va contenir soit sect soit syst si l'effort est trouvé au niveau du systeme ou du secteur.
							$sectSystEncours = intval($tabsectSystEncours[1]);
							// On passe ici pour l'affichage ecran. On sait avant de générer les fichiers si on est sur un effort par systeme ou par secteur.
							if ($typesectSystEncours == "syst") {
								$_SESSION['calculStatSysteme '] = true ;
								if (!$AffichageTypeStats) {
									$resultatLecture .= "Effort par secteur non trouv&eacute; : le calcul des stats g&eacute;n&eacute;rales se fait par syst&egrave;me.<br/>";
									if ($EcrireLogComp ) {
										WriteCompLog ($logComp, "INFO : Effort par secteur non trouve : le calcul des stats generales se fait par syst&egrave;me.",$pasdefichier);
									}
									$AffichageTypeStats = true;
								}								
							} else {
								$_SESSION['calculStatSysteme '] = false ;
								if ($EcrireLogComp ) {
									WriteCompLog ($logComp, "INFO : Effort par secteur trouve : le calcul des stats generales se fait par secteur.",$pasdefichier);
								}
								//$resultatLecture .= "Effort par secteur trouv&eacute; : le calcul des stats g&eacute;n&eacute;rales se fait par secteur.<br/>";
							}
							break;	
						case "asp":
							// Lecture du tableau precedemment cree pour generer le resultat
							//echo('<pre>');print_r($_SESSION['listeEffortEspeces']);echo('</pre>');
							$numEnrEff = count($_SESSION['listeEffortEspeces']) ;
							$EnregTrouve = false;
							$CodeNomReg = recupereRegroupement($espEnCours);
							$infoReg = explode("&#&",$CodeNomReg);
							$RegEspEnCours = $infoReg[0];
							for ($cptEff=1 ; $cptEff<=$numEnrEff;$cptEff++) {
								if ($_SESSION['listeEffortEspeces'][$cptEff][1] == $tableStat && 
									$_SESSION['listeEffortEspeces'][$cptEff][2] == $systemeEncours &&
									$_SESSION['listeEffortEspeces'][$cptEff][3] == $sectEnCours &&			
									$_SESSION['listeEffortEspeces'][$cptEff][4] == $anneeEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][5] == $moisEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][6] == $GTEEnCours && 
									$_SESSION['listeEffortEspeces'][$cptEff][7] == $RegEspEnCours
									) {
									//$prorata 			= $_SESSION['listeEffortEspeces'][$cptEff][10];
									$capturesEsp 		= $_SESSION['listeEffortEspeces'][$cptEff][10];
									$effortSysSect 		= $_SESSION['listeEffortEspeces'][$cptEff][11];
									$typesectSystEncours = $_SESSION['listeEffortEspeces'][$cptEff][12];
									$sectSystEncours 	= $_SESSION['listeEffortEspeces'][$cptEff][13];
									$EnregTrouve = true;
									break;
								}
							}
							
							if (!$EnregTrouve) { echo "asp - Enreg dans listeEffortEspeces non trouvé.<br/>";}
							$testEffortSect = $typesectSystEncours."-".$sectSystEncours."-".$anneeEnCours."-".$moisEnCours."-".$GTEEnCours; 
							//echo $tableStat." | ".$typesectSystEncours."-".$sectSystEncours."-".$anneeEnCours."-".$moisEnCours."-".$GTEEnCours."<br/>";
							$NbReg = count($_SESSION['listeEffortTotal']) ;
							for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
								$infoEff = explode("&#&",$_SESSION['listeEffortTotal'][$cptEff]);
								if ($infoEff[0] == $testEffortSect) {
									$pueSysSect = floatval($infoEff[2]);
									$capturesTotales = floatval($infoEff[3]);
									break;
								}
							}
							$prorata = $capturesEsp / $capturesTotales ;
							break;
						case "ats":
							// Lecture du tableau precedemment cree pour lire les valeurs globales pour ensuite extrapoler
							//$GTEEnCours = $finalRow[$posRupSup];
							$numEnrEff = count($_SESSION['listeEffortEspeces']) ;
							$EnregTrouve = false;
							$CodeNomReg = recupereRegroupement($espEnCours);
							$infoReg = explode("&#&",$CodeNomReg);
							$RegEspEnCours = $infoReg[0];
							for ($cptEff=1 ; $cptEff<=$numEnrEff;$cptEff++) {
								if ($_SESSION['listeEffortEspeces'][$cptEff][1] == "asp" && 
									$_SESSION['listeEffortEspeces'][$cptEff][2] == $systemeEncours &&
									$_SESSION['listeEffortEspeces'][$cptEff][3] == $sectEnCours &&			
									$_SESSION['listeEffortEspeces'][$cptEff][4] == $anneeEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][5] == $moisEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][6] == $GTEEnCours && 
									$_SESSION['listeEffortEspeces'][$cptEff][7] == $RegEspEnCours
									) {
									$effortEsp 			= $_SESSION['listeEffortEspeces'][$cptEff][8]; // effort especes
									//$prorata 			= $_SESSION['listeEffortEspeces'][$cptEff][10];
									$capturesEsp 		= $_SESSION['listeEffortEspeces'][$cptEff][10];
									$effortSysSect 		= $_SESSION['listeEffortEspeces'][$cptEff][11];
									$typesectSystEncours = $_SESSION['listeEffortEspeces'][$cptEff][12];
									$sectSystEncours 	= $_SESSION['listeEffortEspeces'][$cptEff][13];
									$EnregTrouve = true;
									break;
								}
							}
							if (!$EnregTrouve) { echo "ats - Enreg dans listeEffortEspeces non trouvé.<br/>";}
							$testEffortSect = $typesectSystEncours."-".$sectSystEncours."-".$anneeEnCours."-".$moisEnCours."-".$GTEEnCours; 
							$NbReg = count($_SESSION['listeEffortTotal']) ;
							for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
								$infoEff = explode("&#&",$_SESSION['listeEffortTotal'][$cptEff]);
								if ($infoEff[0] == $testEffortSect) {
									$pueSysSect = floatval($infoEff[2]);
									$capturesTotales = floatval($infoEff[3]);
									break;
								}
							}
							$prorata = $capturesEsp / $capturesTotales ;
							break;
						case "asgt":
							// Lecture du tableau precedemment cree pour lire les valeurs globales (pour les GTE) pour ensuite extrapoler par GTE
							$numEnrEff = count($_SESSION['listeEffortEspeces']) ;
							$EnregTrouve = false;
							for ($cptEff=1 ; $cptEff<=$numEnrEff;$cptEff++) {
								if ($_SESSION['listeEffortEspeces'][$cptEff][1] == "asp" && 
									$_SESSION['listeEffortEspeces'][$cptEff][2] == $systemeEncours &&
									$_SESSION['listeEffortEspeces'][$cptEff][3] == $sectEnCours &&			
									$_SESSION['listeEffortEspeces'][$cptEff][4] == $anneeEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][5] == $moisEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][6] == "TOUS" 
									) {
									$effortTotalEsp 		= $_SESSION['listeEffortEspeces'][$cptEff][9]; // contient la valeur de la capture totale pour tout le secteur/mois
									$effortSysSect 		= $_SESSION['listeEffortEspeces'][$cptEff][11]; // contient la valeur de l'effort saisi
									$typesectSystEncours = $_SESSION['listeEffortEspeces'][$cptEff][12];
									$sectSystEncours 	= $_SESSION['listeEffortEspeces'][$cptEff][13];
									$EnregTrouve = true;
									break;
								}
							}
							if (!$EnregTrouve) { echo "asgt - Enreg dans listeEffortEspeces non trouvé.<br/>";}
							$testEffortSect = $typesectSystEncours."-".$sectSystEncours."-".$anneeEnCours."-".$moisEnCours."-TOUS"; 
							$NbReg = count($_SESSION['listeEffortTotal']) ;
							for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
								$infoEff = explode("&#&",$_SESSION['listeEffortTotal'][$cptEff]);
								if ($infoEff[0] == $testEffortSect) {
									$pueSysSect = floatval($infoEff[2]);
									break;
								}
							}
							break;	
						case "attgt":
							// Lecture du tableau precedemment cree pour generer le resultat
							$numEnrEff = count($_SESSION['listeEffortEspeces']) ;
							$EnregTrouve = false;
							$CodeNomReg = recupereRegroupement($espEnCours);
							$infoReg = explode("&#&",$CodeNomReg);
							$RegEspEnCours = $infoReg[0];
							for ($cptEff=1 ; $cptEff<=$numEnrEff;$cptEff++) {
								if ($_SESSION['listeEffortEspeces'][$cptEff][1] == "asp" && 
									$_SESSION['listeEffortEspeces'][$cptEff][2] == $systemeEncours &&
									$_SESSION['listeEffortEspeces'][$cptEff][3] == $sectEnCours &&			
									$_SESSION['listeEffortEspeces'][$cptEff][4] == $anneeEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][5] == $moisEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][6] == "TOUS" && 
									$_SESSION['listeEffortEspeces'][$cptEff][7] == $RegEspEnCours
									) {
									$effortTotalEsp 			= $_SESSION['listeEffortEspeces'][$cptEff][9]; //  contient la valeur de la capture totale pour tout le secteur/mois
									//$prorata 			= $_SESSION['listeEffortEspeces'][$cptEff][10];
									$capturesEsp 		= $_SESSION['listeEffortEspeces'][$cptEff][10];
									$effortSysSect 		= $_SESSION['listeEffortEspeces'][$cptEff][11];// contient la valeur de l'effort saisi
									$typesectSystEncours = $_SESSION['listeEffortEspeces'][$cptEff][12];
									$sectSystEncours 	= $_SESSION['listeEffortEspeces'][$cptEff][13];
									$EnregTrouve = true;
									break;
								}
							}
							if (!$EnregTrouve) { echo "attgt - Enreg dans listeEffortEspeces non trouvé.<br/>";}
							$testEffortSect = $typesectSystEncours."-".$sectEnCours."-".$anneeEnCours."-".$moisEnCours."-".$GTEEnCours; 
							$NbReg = count($_SESSION['listeEffortGTETotal']) ;
							for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
								$infoEff = explode("&#&",$_SESSION['listeEffortGTETotal'][$cptEff]);
								if ($infoEff[0] == $testEffortSect) {
									$pueSysSect = floatval($infoEff[2]); // PUE pour le GTE
									$effortGTESysSect = floatval($infoEff[1]); // effort pour le GTE
									$CapturesGTESysSect = floatval($infoEff[3]); // captures pour le GTE
									break;
								}
							}
							// Pour le calcul du prorata
							$testEffortSect = $typesectSystEncours."-".$sectEnCours."-".$anneeEnCours."-".$moisEnCours."-TOUS"; 
							$NbReg = count($_SESSION['listeEffortTotal']) ;
							for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
								$infoEff = explode("&#&",$_SESSION['listeEffortTotal'][$cptEff]);
								if ($infoEff[0] == $testEffortSect) {
									$capturesTotales = floatval($infoEff[3]);
									break;
								}
							}
							$prorata = $capturesEsp / $capturesTotales ;
							break;
						case "atgts":
							// Lecture du tableau precedemment cree pour generer le resultat
							$numEnrEff = count($_SESSION['listeEffortEspeces']) ;
							$EnregTrouve = false;
							$CodeNomReg = recupereRegroupement($espEnCours);
							$infoReg = explode("&#&",$CodeNomReg);
							$RegEspEnCours = $infoReg[0];
							for ($cptEff=1 ; $cptEff<=$numEnrEff;$cptEff++) {
								if ($_SESSION['listeEffortEspeces'][$cptEff][1] == "asp" && 
									$_SESSION['listeEffortEspeces'][$cptEff][2] == $systemeEncours &&
									$_SESSION['listeEffortEspeces'][$cptEff][3] == $sectEnCours &&			
									$_SESSION['listeEffortEspeces'][$cptEff][4] == $anneeEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][5] == $moisEnCours &&
									$_SESSION['listeEffortEspeces'][$cptEff][6] == "TOUS" && 
									$_SESSION['listeEffortEspeces'][$cptEff][7] == $RegEspEnCours
									) {
									$effortEsp 			= $_SESSION['listeEffortEspeces'][$cptEff][8]; //  contient la valeur de la capture pour l'espece
									$effortTotalEsp 	= $_SESSION['listeEffortEspeces'][$cptEff][9]; //  contient la valeur de la capture totale pour tout le secteur/mois
									//$prorata 			= $_SESSION['listeEffortEspeces'][$cptEff][10];
									$capturesEsp 		= $_SESSION['listeEffortEspeces'][$cptEff][10];
									$effortSysSect 		= $_SESSION['listeEffortEspeces'][$cptEff][11];// contient la valeur de l'effort saisi
									$typesectSystEncours = $_SESSION['listeEffortEspeces'][$cptEff][12];
									$sectSystEncours 	= $_SESSION['listeEffortEspeces'][$cptEff][13];
									$EnregTrouve = true;
									break;
								}
							}
							if (!$EnregTrouve) { echo "attgt - Enreg dans listeEffortEspeces non trouvé.<br/>";}
							$testEffortSect = $typesectSystEncours."-".$sectEnCours."-".$anneeEnCours."-".$moisEnCours."-".$GTEEnCours; 
							$NbReg = count($_SESSION['listeEffortGTETotal']) ;
							for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
								$infoEff = explode("&#&",$_SESSION['listeEffortGTETotal'][$cptEff]);
								if ($infoEff[0] == $testEffortSect) {
									$pueSysSect = floatval($infoEff[2]);
									break;
								}
							}						
							// Pour le calcul du coefficient
							$testEffortSect = $typesectSystEncours."-".$sectEnCours."-".$anneeEnCours."-".$moisEnCours."-TOUS"; 
							$NbReg = count($_SESSION['listeEffortTotal']) ;
							for ($cptEff=1 ; $cptEff<=$NbReg;$cptEff++) {
								$infoEff = explode("&#&",$_SESSION['listeEffortTotal'][$cptEff]);
								if ($infoEff[0] == $testEffortSect) {
									$pueTous = floatval($infoEff[2]);
									$capturesTotales = floatval($infoEff[3]);
									break;
								}
							}
							$prorata = $capturesEsp / $capturesTotales ;
							break;
					}
					// La rupture est pour tout nouveau triplé Secteur ou systeme / Annee / mois
					// Une entree est ajoutée à chaque rupture
					if ($EcrireLogComp && $debugLog) {
						WriteCompLog ($logComp, "DEBUG : avant test sect = ".$sectSystEncours." - annee = ".$anneeEnCours." - mois = ".$moisEnCours." - GTE = ".$GTEEnCours,$pasdefichier);
					}
					// Debut de l'analyse des lignes.
					// rupture sur les systemes ou secteur, annee, mois et GTE
					if ( ($sectSystEncours<> $sectSystPrec) || ($sectSystEncours == $sectSystPrec && $anneeEnCours<>$anneePrec ) ||
						($sectSystEncours == $sectSystPrec && $anneeEnCours==$anneePrec && $moisEnCours<>$moisPrec) || 
						($sectSystEncours == $sectSystPrec && $anneeEnCours==$anneePrec && $moisEnCours==$moisPrec && $GTEEnCours<>$GTEPrec) ) {
						if (!($debIDPrec == "")) {
							// Ajout du contenu de ce tableau dans la table temporaire.
							if ($EcrireLogComp ) {
								WriteCompLog ($logComp, "DEBUG : appel AjoutEnreg rupture sur debIDPrec",$pasdefichier);
							}
							if (!(AjoutEnreg($regroupDeb,$typesectSystPrec."-".$sectSystPrec."-".$anneePrec."-".$moisPrec.'-'.$GTEPrec,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$posStat4,$posStat5,$DerniereLigne,$typeStatistiques,$tableStat,$posRupSupPosPrec))) {
								$erreurProcess = true;
								echo "erreur fonction AjoutEnrg<br/>";
							}  
							// On reinitialise les compteurs
							//echo "sect = ".$sectSystPrec." - annee = ".$anneePrec." - mois = ".$moisPrec." - GTE = ".$GTEPrec."<br/>";
							//echo "sect = ".$sectSystEncours." - annee = ".$anneeEnCours." - mois = ".$moisEnCours." - GTE = ".$GTEEnCours."<br/>";
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
						// On gere aussi le cas des repartitions par taille. d'ou le $posRupSupEnCours <> $posRupSupPrec en plus
						if ($tableStat == "x") {
							echo "x-".$espEnCours." - ".$espPrec." - ".$posRupSupEnCours." - ".$posRupSupPrec."<br/>";
						}
						if ($espEnCours<>$espPrec || ($espEnCours == $espPrec && $posRupSupEnCours <> $posRupSupPrec)) {
							if ($tableStat == "x") {
								echo "Rupture ".$espEnCours." - ".$espPrec." - ".$posRupSupEnCours." - ".$posRupSupPrec."<br/>";
							}
							if ($posRupSupEnCours==0) {
								$AjoutID = "";
							} else {
								$AjoutID = "-".strval($posRupSupEnCours);
							}
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
											$CodeRegEnCours = $infoReg[0];
											$RegEnCours = $infoReg[0].$AjoutID;
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
									$CodeRegEnCours = "DIV";
									$RegEnCours = $CodeRegEnCours.$AjoutID;
									//$RegEnCours = "DIV";
									$NomRegEncours = "divers";
									ajouteEspeceADIV($espEnCours);
								}
								if ($RegEnCours == $RegPrec) {
									// On met a jour le total en cours
									$regroupDeb = majReg($regroupDeb,$RegEnCours,$NumRegEnCours,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$debugLog);
								} else {
									// On doit controler si l'espece n'est pas déja dans un regroupement dans le tableau temporaire pour le débarquement en cours.
									$controleRegroupement = true;
								}	
							} else {
								if ($posRupSupEnCours <> 0) {
									// Nouvelle taille, meme espece : 
									$RegEnCours = $CodeRegEnCours.$AjoutID;
									$controleRegroupement = true;
								}
							}
						} else {
							// On est toujours sur la meme espece
							// On met a jour le regroupement en cours
							$regroupDeb = majReg($regroupDeb,$RegEnCours,$NumRegEnCours,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$debugLog);
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

									$regroupDeb = majReg($regroupDeb,$RegEnCours,$cptRg,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$debugLog);
									$RegTempTrouve = true;
									break;
								}
							}
						} else {
							// Aucun regroupement disponible.
							// On crée une entrée dans le tableau
							$NbRegDebSuiv = count($regroupDeb) +1;
							$NumRegEnCours = $NbRegDebSuiv;

							$regroupDeb= creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$typeSelection,$effortSysSect,$prorata,$pueSysSect,$effortEsp,$effortTotalEsp,$effortGTESysSect,$CapturesGTESysSect,$pueTous,$debugLog);
							$RegTempTrouve = true; // On le met a vrai pour eviter que le tableau soit créé deux fois
								
						}// fin du 	if ($NbRegDeb >= 1 )	
						if (!($RegTempTrouve)) {
							// Dans le cas ou precedement, aucun regroupement n'a été trouvé, on le crée.
							// On cree le nouveau regroupement
							$NbRegDebSuiv = count($regroupDeb) +1;
							$NumRegEnCours = $NbRegDebSuiv;

							$regroupDeb = creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$typeSelection,$effortSysSect,$prorata,$pueSysSect,$effortEsp,$effortTotalEsp,$effortGTESysSect,$CapturesGTESysSect,$pueTous,$debugLog);
						}
					} // fin du if ($controleRegroupement)
					// On met a jour les variables contenant toutes les valeurs de rupture precedentes
					$posRupSupPosPrec = $posRupSup;
					$posRupSupPrec = $posRupSupEnCours;
					$espPrec = $espEnCours;
					$RegPrec = $RegEnCours;
					$DerniereLigne = $finalRow;
					$sectSystPrec = $sectSystEncours ;
					$anneePrec= $anneeEnCours;
					$moisPrec= $moisEnCours;
					$GTEPrec = $GTEEnCours;
					$typesectSystPrec = $typesectSystEncours;
					$debIDPrec = $typesectSystPrec."-".$sectSystPrec."-".$anneePrec."-".$moisPrec.'-'.$GTEPrec; // Pour la mise a jour de la derniere ligne
					$effortPrec = $effortSysSect;
					$prorataPrec = $prorata;
				} else {
				// ********************************
				// **** GESTION DES REGROUPEMENTS DANS LE CAS GENERAL
					// Cas général des regroupements sans calcul stat
					if ($posGTE == -1) { $GTEEnCours = "TOUS";} else {$GTEEnCours = $finalRow[$posGTE];}
					$espEnCours = $finalRow[$posESPID];
					$debEnCours = $finalRow[$posDEBID];
					if ($posRupSup == -1) {$posRupSupEnCours = 0;} else {$posRupSupEnCours = $finalRow[$posRupSup];}; // La longueur des especes pour la repartition par taille
					// Debug
					if ($EcrireLogComp && $debugLog) {
						WriteCompLog ($logComp, "DEBUG : debencours = ".$debEnCours." espencours = ".$espEnCours. " [".$posStat1."] val1 = ".$finalRow[$posStat1]." [".$posStat2."] val2 = ".$finalRow[$posStat2],$pasdefichier);
						//WriteCompLog ($logComp, "DEBUG : debprec = ".$debIDPrec." espprec = ".$espPrec,$pasdefichier);
					}
					//if ($posRupSupEnCours<>0) {
					//echo "debencours = ".$debEnCours." espencours = ".$espEnCours. " || pos ".$posStat1." val1 = ".$finalRow[$posStat1]." - pos ".$posStat2." val2 = ".$finalRow[$posStat2]." - pos ".$posStat3." val3 = ".$finalRow[$posStat3]." taille encours = ".$posRupSupEnCours." taile prec = ".$posRupSupPrec."<br/>";
					//}
					if ($debEnCours<>$debIDPrec || ($debEnCours == $debIDPrec && $GTEEnCours <>$GTEPrec)) {
						if (!($debIDPrec == "")) {
							// Ajout du contenu de ce tableau dans la table temporaire.
							if (!(AjoutEnreg($regroupDeb,$debIDPrec,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$posStat4,$posStat5,$DerniereLigne,$typeStatistiques,$tableStat,$posRupSupPosPrec))) {
								$erreurProcess = true;
								echo "erreur fonction AjoutEnrg<br/>";
							} 
							//if ($posRupSupEnCours<>0) {
								//echo('<pre>');print_r($regroupDeb);echo('</pre>');
								//echo "ajout a la rupture ".$RegEnCours."<br/>";
							//}
							// On reinitialise les compteurs
							$Mesure = 0;
							unset($regroupDeb);
							$NumRegEnCours = 0;
							$RegPrec = "";
						}
					} // fin du if ($debEnCours<>$debIDPrec)
					$controleRegroupement = false;	 // Est-ce qu'on controle la presence de l'espece dans le regroupement, eventuellement on le cree ?					
					if ($espEnCours<>$espPrec || ($espEnCours == $espPrec && $posRupSupEnCours <> $posRupSupPrec)) {
						if ($posRupSupEnCours==0) {
							$AjoutID = "";
						} else {
							$AjoutID = "-".strval($posRupSupEnCours);
						}
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
										$CodeRegEnCours = $infoReg[0];
										$RegEnCours = $infoReg[0].$AjoutID;
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
								$CodeRegEnCours = "DIV";
								$RegEnCours = $CodeRegEnCours.$AjoutID;
								$NomRegEncours = "divers";
								ajouteEspeceADIV($espEnCours);
							}
							if ($RegEnCours == $RegPrec) {
								// On met a jour le total en cours
								if ($posRupSupEnCours<>0) {
									//echo "1- maj regroupement ".$RegEnCours."<br/>";
								}
								$regroupDeb = majReg($regroupDeb,$RegEnCours,$NumRegEnCours,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$debugLog);
							} else {
								// On doit controler si l'espece n'est pas déja dans un regroupement dans le tableau temporaire pour le débarquement en cours.
								$controleRegroupement = true;
							}
						} else {
							if ($posRupSupEnCours<>0) {
								// Nouvelle taille, meme espece : 
								$RegEnCours = $CodeRegEnCours.$AjoutID;
								$controleRegroupement = true;
							}
						}
					} else {
						// On est toujours sur la meme espece
						// On ajoute
						if ($posRupSupEnCours<>0) {
							//echo "2- maj regroupement ".$RegEnCours."<br/>";
						}
						$regroupDeb = majReg($regroupDeb,$RegEnCours,$NumRegEnCours,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$debugLog);	
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
									if ($posRupSupEnCours<>0) {
										//echo "3- maj regroupement existant".$RegEnCours."<br/>";
									}
									$regroupDeb = majReg($regroupDeb,$RegEnCours,$cptRg,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$debugLog);
									$RegTempTrouve = true;
									break;
								}
							}
						} else {
							// Aucun regroupement disponible.
							// On crée une entrée dans le tableau
							$NbRegDebSuiv = count($regroupDeb) +1;
							$NumRegEnCours = $NbRegDebSuiv;
							if ($posRupSupEnCours<>0) {
								//echo "1-cree nouvel enreg".$RegEnCours."<br/>";
							}
							$regroupDeb= creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$typeSelection,$effort,$prorata,0,0,0,0,0,0,$debugLog);
							$RegTempTrouve = true; // On le met a vrai pour eviter que le tableau soit créé deux fois		
						}// fin du 	if ($NbRegDeb >= 1 )	
						if (!($RegTempTrouve)) {
							// Dans le cas ou precedement, aucun regroupement n'a été trouvé, on le crée.
							// On cree le nouveau regroupement
							$NbRegDebSuiv = count($regroupDeb) +1;
							$NumRegEnCours = $NbRegDebSuiv;
							if ($posRupSupEnCours<>0) {
								//echo "2-cree nouvel enreg".$RegEnCours."<br/>";
							}
							$regroupDeb = creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$finalRow[$posStat1],$posStat2,$finalRow[$posStat2],$posStat3,$finalRow[$posStat3],$posStat4,$finalRow[$posStat4],$posStat5,$finalRow[$posStat5],$tableStat,$typeSelection,$effort,$prorata,0,0,0,0,0,0,$debugLog);
						}
					} // fin du if ($controleRegroupement)
					// On met a jour les variables contenant l'espece et le regroupement precedent
					$posRupSupPosPrec = $posRupSup;
					$posRupSupPrec = $posRupSupEnCours;
					$GTEPrec = $GTEEnCours;
					$espPrec = $espEnCours;
					$debIDPrec = $debEnCours;
					$RegPrec = $RegEnCours;
					$DerniereLigne = $finalRow;
					$effortPrec = 0;
					$prorataPrec = 0;
				}
			} // fin du while
			// Attention, quand on sort, on doit mettre à jour le dernier tableau dans la BD.
			// On cree autant de lignes dans la table temp que de lignes dans le tableau temporaire pour ce debarquement
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "DEBUG : appel AjoutEnreg derniere ligne",$pasdefichier);
			}

			if (!(AjoutEnreg($regroupDeb,$debIDPrec,$posESPID,$posESPNom,$posStat1,$posStat2,$posStat3,$posStat4,$posStat5,$DerniereLigne,$typeStatistiques,$tableStat,$posRupSupPosPrec))) {
				$erreurProcess = true;
			} 
		} // fin du if (pg_num_rows($SQLfinalResult) == 0)
	}
	pg_free_result($SQLfinalResult);
	//exit; // pour test
}

//*********************************************************************
// creeNouveauReg : Fonction de creation d'un regroupement
function creeNouveauReg($regroupDeb,$RegEnCours,$NomRegEncours,$NbRegDebSuiv,$Stat1,$posStat2,$Stat2,$posStat3,$Stat3,$posStat4,$Stat4,$posStat5,$Stat5,$tableStat,$typeSelection,$effort,$prorata,$pue,$effortEsp,$effortTotalEsp,$effortGTE,$CapturesGTE,$pueTous,$debugLog) {
// Cette fonction permet de creer un fichier a exporter a partir d'un SQL
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $regroupDeb
// $RegEnCours
// $NomRegEncours
// $Stat1
// $Stat2
// $Stat3
// $Stat4
// $Stat5
// $tableStat
// $typeSelection
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
	if ($typeSelection == "statistiques") {
		$regroupDeb[$NbRegDebSuiv][6] = $tableStat;						
	}
	if (!($posStat2 == -1 )) {
		$regroupDeb[$NbRegDebSuiv][4] = floatval($Stat2);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : CREATION tableau temporaire pour ".$regroupDeb[$NbRegDebSuiv][1]." val1 = ".$regroupDeb[$NbRegDebSuiv][3]." val2 = ".$regroupDeb[$NbRegDebSuiv][4]." val3 = ".$regroupDeb[$NbRegDebSuiv][5],$pasdefichier);
		}							
	}
	if (!($posStat3 == -1 )) {
		$regroupDeb[$NbRegDebSuiv][5] = floatval($Stat3);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : CREATION tableau temporaire pour ".$regroupDeb[$NbRegDebSuiv][1]." val1 = ".$regroupDeb[$NbRegDebSuiv][3]." val2 = ".$regroupDeb[$NbRegDebSuiv][4]." val3 = ".$regroupDeb[$NbRegDebSuiv][5],$pasdefichier);
		}							
	}
	if (!($posStat4 == -1 )) {
		$regroupDeb[$NbRegDebSuiv][7] = floatval($Stat4);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : CREATION tableau temporaire prise en compte de la 4ieme colonne ".$regroupDeb[$NbRegDebSuiv][7],$pasdefichier);
		}							
	}
	if (!($posStat5 == -1 )) {
		$regroupDeb[$NbRegDebSuiv][8] = floatval($Stat5);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : CREATION tableau temporaire prise en compte de la 5ieme colonne ".$regroupDeb[$NbRegDebSuiv][8],$pasdefichier);
		}							
	}	
	$regroupDeb[$NbRegDebSuiv][9] = floatval($effort);
	$regroupDeb[$NbRegDebSuiv][10] = floatval($prorata);
	$regroupDeb[$NbRegDebSuiv][11] = floatval($pue);	
	$regroupDeb[$NbRegDebSuiv][12] = floatval($effortEsp);
	$regroupDeb[$NbRegDebSuiv][13] = floatval($effortTotalEsp);
	$regroupDeb[$NbRegDebSuiv][14] = floatval($effortGTE);
	$regroupDeb[$NbRegDebSuiv][15] = floatval($CapturesGTE);
	$regroupDeb[$NbRegDebSuiv][16] = floatval($pueTous);
	return $regroupDeb;
}
//*********************************************************************
// creeNouveauReg : Fonction de creation d'un regroupement
function majReg($regroupDeb,$RegEnCours,$NumRegEC,$Stat1,$posStat2,$Stat2,$posStat3,$Stat3,$posStat4,$Stat4,$posStat5,$Stat5,$tableStat,$debugLog) {
// Cette fonction permet de creer un fichier a exporter a partir d'un SQL
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $regroupDeb
// $RegEnCours
// $NumRegEC
// $Stat1
// $Stat2
// $posStat3
// $Stat3
// $posStat4
// $Stat4
// $posStat5
// $Stat5
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
	if (!($posStat2 == -1 )) {
		$regroupDeb[$NumRegEC][4] = $regroupDeb[$NumRegEC][4] + floatval($Stat2); 
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : MAJ tableau meme espece = ".$RegEnCours. " num ".$NumRegEC." val1 = ".$regroupDeb[$NumRegEC][3]." - val2= ".$regroupDeb[$NumRegEC][4],$pasdefichier);
		}
	}	
	if (!($posStat3 == -1 )) {
		$regroupDeb[$NumRegEC][5] = floatval($regroupDeb[$NumRegEC][5]) + floatval($Stat3);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : MAJ tableau prise en compte de la 3ieme colonne ".$regroupDeb[$NbRegDebSuiv][5],$pasdefichier);
		}
	}
	if (!($posStat4 == -1 )) {
		$regroupDeb[$NumRegEC][7] = floatval($regroupDeb[$NumRegEC][7]) + floatval($Stat4);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : MAJ tableau prise en compte de la 4ieme colonne ".$regroupDeb[$NbRegDebSuiv][7],$pasdefichier);
		}							
	}
	if (!($posStat5 == -1 )) {
		$regroupDeb[$NumRegEC][8] = floatval($regroupDeb[$NumRegEC][8]) + floatval($Stat5);
		if ($EcrireLogComp && $debugLog) {
			WriteCompLog ($logComp, "DEBUG : MAJ tableau prise en compte de la 5ieme colonne ".$regroupDeb[$NbRegDebSuiv][8],$pasdefichier);
		}							
	}
	return $regroupDeb;

}
//*********************************************************************
// ajouteEspeceADIV : Fonction d'ajout d'une espece div a la variable de session
function ajouteEspeceADIV($espID){
// Cette fonction permet de creer une variable de session contenant toutes les especes du div pour l'ajouter dans le fichier des regroupements
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $espEnCours: l'espece en cours
//*********************************************************************
// En sortie : La fonction met a jour la variable de session $_SESSION['listeDIV'] avec l'especes
// La fonction met a jour $regroupDeb
//*********************************************************************
// 
	global $connectPPEAO;
	global $EcrireLogComp;
	global $logComp;
	global $pasdefichier;
	global $resultatLecture;
	$libelleEsp="inconnu";
	//echo "ajout ".$espID." au div<br/>";
	$EspTrouve = false;
	$NbReg = count($_SESSION['listeDIV']);
	for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
		if ($_SESSION['listeDIV'][$cptR][0] == $espID) {
			$EspTrouve = true;
			break;
		}

	}
	if (!$EspTrouve) {
		// On recherche le libelle de l'espece
		$SQLLibelle = "select libelle from ref_espece where id = '".$espID."'";
		$SQLLibellepResult = pg_query($connectPPEAO,$SQLLibelle);
		$erreurSQL = pg_last_error($connectPPEAO);
		if ( !$SQLLibellepResult ) { 
			if ($EcrireLogComp ) {
				WriteCompLog ($logComp, "ERREUR :recherche libelle espece construction liste especes pour DIV en erreur : ".$SQLLibelle." (erreur compl&egrave;te = ".$erreurSQL.")",$pasdefichier);
			} else {
				$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>recherche libelle espece en erreur : ".$SQLLibelle." (erreur compl&egrave;te = ".$erreurSQL.")<br/>";
			}
		} else {
			if (pg_num_rows($SQLLibellepResult) == 0) {
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp, "ERREUR : onstruction liste especes pour DIV : pas d'espece trouvee pour l'id ".$espID." donc pas de libelle trouve...",$pasdefichier);
				} else {
					$resultatLecture .= "<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>ERREUR : onstruction liste especes pour DIV : pas d'espece trouvee pour l'id ".$espID." donc pas de libelle trouve...<br/>";}
			} else {
				$EspRow = pg_fetch_row($SQLLibellepResult) ;
				$libelleEsp = $EspRow[0];
			}
			pg_free_result($SQLLibellepResult);
		}
		$NbReg ++;
		$_SESSION['listeDIV'][$NbReg][0] = $espID;
		$_SESSION['listeDIV'][$NbReg][1] = $libelleEsp;	

	}

	
	
	//echo('<pre>');print_r($_SESSION['listeDIV']);echo('</pre>');
}

?>