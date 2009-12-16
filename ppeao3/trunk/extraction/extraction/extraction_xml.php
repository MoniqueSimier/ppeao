<?php 
//*****************************************
// extraction_xml.php
//*****************************************
// Created by Yann Laurent
// 2009-06-29 : creation
//*****************************************
// Ce script contient une serie de fonctions permettant de lire et de parser les fichiers XML contenant la s�lection
// et la d�finition des colonnes � afficher
//*****************************************
// Param�tres en entr�e
// aucun pour l'instant.
// Param�tres en sortie
// aucun pour l'instant.
//*****************************************

// Etat de la pile de parcours du document XML
$stack = array();
// Valeur d'un dernier �l�ment lu
$globaldata ="";
// Flag pour savoir si on est sur la table en cours de s�lection.
$RecupDonneesOK = false;
$TableAAjouter = false;
$idenTableEnCours = "";

//*********************************************************************
// startElement:  Fonction associ�e � l��v�nement d�but d��l�ment pour la lecture du fichier XML 
// contenant la s�lection des donn�es � extraire
function startElement($parser, $name, $attrs){
// Cette fonction permet de construire la liste des colonnes checkables ou non pour une table donn�e $idenTableEnCours
// Pour cela, elle va lire toutes les balises de haut niveau pour analyser les attribut de selection
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $parser : le parser en cours
// $name : le nom de la balise en cours
//*********************************************************************
// En sortie : 
// lUn certain de variables definit en GLOBAL contenant les diff�rents SQL, 
// Ainsi que $listeSelection qui contient le HTML rendant lisible la s�lection
//*********************************************************************
	global $stack;
	// Donn�es pour la selection 
	global $typeSelection ;
	global $typePeche;
	global $typeStatistiques;
	global $listeEnquete ; // contiendra soit la 
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
	global $SQLEspeces	;
	global $SQLFamille ;
	global $SQLPeEnquete ;
	global $SQLdateDebut ; // format annee/mois
	global $SQLdateFin ; // format annee/mois
	global $listeDocPays; // liste des docs pour le pays
	global $listeDocSyst; // liste des docs pour le systeme
	global $listeDocSect; // liste des docs pour le secteur
	array_push($stack,$name);

	// Analyse de l'element et remplissage des variables
	switch(strtoupper($name)) {
	case "EXPLOITATION":
		$typeSelection =  $attrs["TYPE"]; // un seul attribut type
		break;
	case "PECHE": 
		$typePeche =  $attrs["TYPE"]; // un seul attribut type
		break;
	case "STATISTIQUES": 
		$typeStatistiques =  $attrs["TYPE"]; // un seul attribut type
		break;
	case "FAMILLELISTE":
		$listeSelection .="<br/><b>familles</b> <br/> ";
		break;
	case "ESPECELISTE":
		$listeSelection .="<br/><b>esp&egrave;ces</b> <br/>";
		break;
	case "PAYSLISTE":
		$listeSelection .="<br/><b>pays</b><br/>";
		break;
	case "SYSTEMELISTE":
		$listeSelection .="<br/><b>syst&egrave;mes</b><br/>";
		break;
	case "SECTEURLISTE":
		$listeSelection .="<br/><b>secteurs</b> <br/>";
		break;
	case "AGGLOMERATIONLISTE":
		$listeSelection .="<br/><b>agglom&eacute;rations</b><br/>";
		break;
	case "INTERVALLE":
		$listeSelection .="<br/><b>p&eacute;riode d&#x27;int&eacute;r&ecirc;t</b><br/>";
		break;
	case "GRANDTYPEENGINLISTE":
		$listeSelection .="<br/><b>grands types d&#x27;engin</b><br/>";
		break;
	case "ENGINLISTE":
		$listeSelection .="<br/><b>engins</b><br/>";
		break;
	case "ENQUETELISTE":
		$listeSelection .="<br/><b>enqu&ecirc;tes</b><br/>";
		break;		
	case "DATEDEBUT":
		$listeSelection .="du  ".$attrs["MOIS"]."/".$attrs["ANNEE"]." ";
		$SQLdateDebut = $attrs["ANNEE"]."/".$attrs["MOIS"];
		break;	
	case "DATEFIN":
		$listeSelection .="au  ".$attrs["MOIS"]."/".$attrs["ANNEE"]." .";
		$SQLdateFin = $attrs["ANNEE"]."/".$attrs["MOIS"];
		break;
	case "FAMILLE" :
		$SQLFamille .= $attrs["ID"].",";
		break;	
	case "ESPECE" :
		$SQLEspeces .= "'".$attrs["ID"]."',";
		break;
	case "PAYS" :
		$SQLPays .= "'".$attrs["ID"]."',";
		break;
	case "SYSTEME":
		$SQLSysteme .= $attrs["ID"].",";
		break;
	case "SECTEUR" :
		$SQLSecteur .= $attrs["ID"].",";
		break;
	case "GRANDTYPEENGIN":
		$SQLGTEngin .= $attrs["ID"].",";
		break;
	case "ENGIN":
		$SQLEngin .= "'".$attrs["ID"]."',";
		break;
	case "CAMPAGNE":
		$SQLCampagne .= $attrs["ID"].",";
		break;
	case "ENQUETE":
		$SQLPeEnquete .= $attrs["ID"].",";
		break;
	case "AGGLOMERATION":
		$SQLAgg .= $attrs["ID"].",";
		break;
	case "DOCUMENT":
		switch ($attrs["TYPE"]) {
			case "meta_pays":
				$listeDocPays .= $attrs["ID"].",";
				break;
			case "meta_systeme":
				$listeDocSyst .= $attrs["ID"].",";
				break;
			case "meta_secteur":
				$listeDocSect .= $attrs["ID"].",";
				break;
		}
		break;
	default :
		break;
	}
	
	
	//print "<br/>Debut de l'element : ".$name." -- ";
	
	//print "profondeur : ".$depth[$parser]." -- Attributs de l'element : ";
	
	//affichage des attributs de l'�l�ment
	//while (list ($key, $val) = each ($attrs))
	//	{echo "$key => $val";}
	//print " ";

}

//*********************************************************************
// endElementCol: Fonction associ�e � l��v�nement fin d��l�ment pour la lecture du fichier XML 
// contenant la s�lection des donn�es � extraire
function endElement($parser, $name){
// Cette fonction permet de construire la liste des tables valides pour le type de peche / type de stat / filieres
// Pour cela, elle analyse les diff�rentes balises et leur contenu
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $parser : le parser en cours
// $name : le nom de la balise en cours
//*********************************************************************
// En sortie : 
// la variable $listeSelection qui contient le HTML rendant lisible la s�lection
//*********************************************************************
	global $stack;
	global $globaldata;
		// Donn�es pour la selection 
	global $typeSelection ;
	global $typePeche;
	global $typeStatistiques;
	global $listeGTEngin;
	// Pour construire le bandeau avec la s�lection
	global $listeSelection;
	
	switch(strtoupper($name)) {
		case "FAMILLE" :
			$listeSelection .= $globaldata."; ";
			break;	
		case "ESPECE" :
			$listeSelection .= $globaldata."; ";
			break;
		case "PAYS" :
			$listeSelection .= $globaldata."; ";
			break;
		case "SYSTEME":
			$listeSelection .= $globaldata."; ";
			break;
		case "SECTEUR" :
			$listeSelection .= $globaldata."; ";
			break;
		case "AGGLOMERATION" :
			$listeSelection .= $globaldata."; ";
			break;
		case "GRANDTYPEENGIN":
			$listeSelection .= $globaldata."; ";
			break;
		case "ENGIN":
			$listeSelection .= $globaldata."; ";
			break;
		case "ENQUETE":
			$listeSelection .= $globaldata."; ";
			break;	
		default :

		break;
	}
	array_pop($stack);
}

// Fonction associ�e � l��v�nement donn�es textuelles
function characterData($parser, $data){
	global $globaldata;
	$globaldata = $data;
}

// Fonction associ�e � l��v�nement de d�tection d'un appel d'entit� externe
function externalEntityRefHandler($parser,$openEntityNames,$base,$systemId,$publicId){
if ($systemId) { 
	if (!list($parser, $fp) = new_xml_parser($systemId))	{
		printf("Impossible d'ouvrir %s � %s<br/>",
						   $openEntityNames,
						   $systemId);
		return FALSE;
	}
	while ($data = fread($fp, 4096)) {
		if (!xml_parse($parser, $data, feof($fp))){
			printf("Erreur XML : %s � la ligne %d lors du traitement de l'entit� %s\n",
						   xml_error_string(xml_get_error_code($parser)),
						   xml_get_current_line_number($parser),
						   $openEntityNames);
			xml_parser_free($parser);
			return FALSE;
		}
	}
	xml_parser_free($parser);
	return TRUE; 
	} 
return FALSE;

}

//*********************************************************************
// new_xml_parser_Colonnes: Fonction de cr�ation du parser et d'affectation des fonctions aux gestionnaires d'�v�nements
// Pour la lecture du fichier XML contenant la s�lection des donn�es � extraire
function new_xml_parser($file) {
// Cette fonction permet de cr�er le parser pour le lire le fichier XML (en param�tre) et de cr�er les evenements avant et apres l'element
// L'analyse du fichier se fait alors dans ces deux fonctions g�nant ces �v�nements avant/apr�s
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $file : le nom du fichier XML contenant la s�lection des donn�es � extraire
//*********************************************************************
// En sortie : 
// une Tableau
//*********************************************************************
	global $parser_file;
	//cr�ation du parseur
	$xml_parser = xml_parser_create();
	//Activation du respect de la casse du nom des �l�ments XML
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
	//D�claration des fonctions � rattacher au gestionnaire d'�v�nement
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	xml_set_character_data_handler($xml_parser, "characterData");
	xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler"); // pas utilis� ici, mais on le laisse
	//Ouverture du fichier
	if (!($fp = @fopen($file, "r"))) { return FALSE; }
	//Transformation du parseur en un tableau
	if (!is_array($parser_file)) { 
		settype($parser_file, "array"); 
	}
	$parser_file[$xml_parser] = $file;
	
	return array($xml_parser, $fp);
}


//*********************************************************************
// startElementCol:  Fonction associ�e � l��v�nement d�but d��l�ment pour la lecture du fichier XML 
// contenant la d�finition des colonnes � afficher
function startElementCol($parser, $name, $attrs){
// Cette fonction permet de construire la liste des colonnes checkables ou non pour une table donn�e $idenTableEnCours
// Pour cela, elle va lire la balise CHAMP et analyse ses attributs
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $parser : le parser en cours
// $name : le nom de la balise en cours
//*********************************************************************
// En sortie : 
// la variable $ListeChampTableFac ou $ListeChampTableDef qui contient la liste des colonnes pour la table en cours
//*********************************************************************

	global $stack;
	// Donn�es pour la selection 
	global $ListeTable;
	global $ListeChampTableDef ;
	global $ListeChampTableFac ;
	global $TableATester ;
	global $Filiere ;
	global $FiliereEnCours ;
	global $groupeOK ;
	global $TableAAjouter;
	global $RecupDonneesOK;
	global $NumChampDef;
	global $NumChampFac;
	global $idenTableEnCours ;
	global $NomTableEnCours ;
	global $EcrireLogComp;
	global $pasdefichier;
	global $logComp;
	global $typeRecupTable;
	global $ListeToutesValeurs;
	global $Groupe;
	global $GroupeEnCours;
	global $ignoreGroupe;
	global $TypePecheEnCours ;
	global $groupePasAffecte;
	array_push($stack,$name);
	$continueTrait = true;
	// Analyse de l'element et remplissage des variables
	switch(strtoupper($name)) {
		case "GROUPE" :
				if (array_key_exists("MULTI",$attrs)) {
					// On indique par cet atribut qu'on devra prendre en compte une selection multiple par groupe
					// ie un element peut etre affich� dans differents groupes, on ne se limite pas 
					// au controle sur le groupe.
					
					if ((strtoupper($attrs["FILIERE"]) == "TOUTES" || strtoupper($attrs["FILIERE"]) == strtoupper($FiliereEnCours)) &&
						(strtoupper($attrs["TYPE"]) == "TOUT" || strtoupper($attrs["TYPE"]) == strtoupper($TypePecheEnCours))																									
						) {
						$groupeOK = true;
						//echo "Groupe multi pas ok<br/>";
					} else {
						$groupeOK = false;
						//echo "Groupe multi OK<br/>";
					}
					$ignoreGroupe = true;
				} else {
					if (!($groupeOK)) {
						if ((strtoupper($attrs["FILIERE"]) == "TOUTES" || strtoupper($attrs["FILIERE"]) == strtoupper($FiliereEnCours)) &&
							(strtoupper($attrs["TYPE"]) == "TOUT" || strtoupper($attrs["TYPE"]) == strtoupper($TypePecheEnCours))																									
							) {
							$groupeOK = true;
							//echo "Groupe OK<br/>";
						} 
					} 
				}
			break;
		case "CHAMP":
			// On construit une variable de session contenant le libelle pour la valeur de champs
			$NomChampsEncours = $idenTableEnCours."-".$attrs["CODE"];
			$champTrouve = false;
			$NbReg = count($_SESSION['libelleChamp']);
			for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
				$tablib = explode(",",$_SESSION['libelleChamp'][$cptR]);
				if($tablib[0] == $NomChampsEncours) {
					$champTrouve = true;
					break;
				}
			}	
			if (!$champTrouve) {
				$posSuiv = 	intval($NbReg) + 1;
				$_SESSION['libelleChamp'][$posSuiv] =  $idenTableEnCours."-".$attrs["CODE"].",".$attrs["LIBELLE"];
			}
			if ($RecupDonneesOK == true ) {
				if (array_key_exists("FILIERE",$attrs)) {
					// Cela veut dire qu'au niveau du champs, on a une restriction suppl�mentaire par rapport � la fili�re.
					// On le teste.
					if (strpos($attrs["FILIERE"],trim($FiliereEnCours)) === false) {
					// Si la filiere n'est pas dans la liste, on arrete
						$continueTrait = false;
					} 
				}
				if ($ignoreGroupe) {
					if (array_key_exists("GROUPE",$attrs)) {
						// Cela veut dire qu'au niveau du champs, on a une restriction suppl�mentaire par rapport au groupe.
						// On le teste.
						if (!($TableATester == "")) {
							if (strpos($attrs["GROUPE"],trim($TableATester)) === false) {
							// Si le champ n'appartient pas au groupe, on ne le prend pas...
								$continueTrait = false;
							}
						}
					}
				}
				if ($continueTrait){
					$libDebug = "";
				
					if ($typeRecupTable == "tout" && $TableAAjouter ) {
						//echo "AJOUT DANS LISTE<br/>";
						if (!($attrs["AFFICHAGE"] =="X")) {
							$majOk = true;
							// On recup�re toutes les valeurs pour la ou les filieres autoris�es
							// On doit tester quand meme si une valeur a �t� d�coch�e
							if (!($_SESSION['listeColonne'] == "") ) {
								$colRecues = explode (",",$_SESSION['listeColonne']);
								$NumColR = count($colRecues) - 1;
								for ($cptCR=0 ; $cptCR<=$NumColR;$cptCR++) {
									$valTest = substr($colRecues[$cptCR],0,-2);
									// On teste si on a d�j� coch� cette colonne
									if ($valTest == $idenTableEnCours."-".$attrs["CODE"]) {
										if (strpos($colRecues[$cptCR],"-N") === false) {
										} else {
											// Soit on vient de la d�cocher suffixe = -N
											$majOk = false;
										}
										break;
									} 
								}
							} 
							if ($majOk) {
								if ( $ListeToutesValeurs == "") {
									 $ListeToutesValeurs = $idenTableEnCours."-".$attrs["CODE"];
								} else {
									$ListeToutesValeurs .= ",".$idenTableEnCours."-".$attrs["CODE"];
								}	
							}
						}
					} // fin du if ($typeRecupTable == "tout" && $TableAAjouter && $groupeOK) {
					//echo $ListeToutesValeurs."<br/>";
					// GEstion de l'afficha proprement dit
					if ($typeRecupTable == "un") {
						if ($attrs["AFFICHAGE"] =="X") {
							if ($typeRecupTable == "un") {
							$NumChampDef ++;
							$testVal = $NumChampDef + $NumChampFac;
							$str_testVal = strval ($testVal);
							if (fmod($str_testVal,'7') == '0') {
								$ListeChampTableFac .="</td><td class=\"colitem\">";
							}
							$ListeChampTableFac .= "<input id=\"".$TableATester."def".$NumChampDef."\" type=\"checkbox\"  name=\"".$TableATester."\" value=\"".$idenTableEnCours."-".$attrs["CODE"]."\" checked=\"checked\" disabled=\"disabled\"/>&nbsp;".$attrs["LIBELLE"]."<br/>";
							}
						} else {
							$checked = "";
							$dejaControle = false ;
							// On v�rifie que ce champs n'a pas d�j� �t� coch�
							if (!($_SESSION['listeColonne'] == "") ) {
								$colRecues = explode (",",$_SESSION['listeColonne']);
								$NumColR = count($colRecues) - 1;
								for ($cptCR=0 ; $cptCR<=$NumColR;$cptCR++) {
									$valTest = substr($colRecues[$cptCR],0,-2);
									// On teste si on a d�j� coch� cette colonne
									if ($valTest == $idenTableEnCours."-".$attrs["CODE"]) {
										if (strpos($colRecues[$cptCR],"-N") === false) {
											// Soit on vient de la cocher suffixe = -X
											$checked = "checked=\"checked\"";
										} else {
											// Soit on vient de la d�cocher suffixe = -N
											$checked = "";
										}
										break;
									} else {
										// Si on a d�j� choisi de tout affich� alors on coche
										if (strpos($_SESSION['listeColonne'],"toutX") > 0) { // Attention, on ne teste pas XtoutX mais bien toutX car sinon strpos renvoie 0 qui eput aussi vouloir dire false... 
											$checked = "checked=\"checked\"";
										} else {
											if (strpos($_SESSION['listeColonne'],"pasttX") > 0) { // Meme remarque que precedemment sur pastoutX
												$checked = "";
											} 
										}							
									}
								}
							}
							$NumChampFac ++;
							$testVal = $NumChampDef + $NumChampFac;
							$str_testVal = strval ($testVal);
							if (fmod($str_testVal,'7') == '0') {
								$ListeChampTableFac .="</td><td class=\"colitem\">";
							}
							$ListeChampTableFac .= "<input id=\"".$TableATester."fac".$NumChampFac."\" type=\"checkbox\"  name=\"".$TableATester."\" value=\"".$idenTableEnCours."-".$attrs["CODE"]."\" ".$checked."/>&nbsp;".$attrs["LIBELLE"]."<br/>";								
						} // fin du if ($attrs["AFFICHAGE"] =="X") 
					} // fin du if ($typeRecupTable == "un")
				}
			}
		break;
	default :
		break;
	}
}

//*********************************************************************
// endElementCol: Fonction associ�e � l��v�nement fin d��l�ment pour la lecture du fichier XML 
// contenant la d�finition des colonnes � afficher
function endElementCol($parser, $name){
// Cette fonction permet de construire la liste des tables valides pour le type de peche / type de stat / filieres
// Pour cela, elle analyse les diff�rentes balises et leur contenu
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $parser : le parser en cours
// $name : le nom de la balise en cours
//*********************************************************************
// En sortie : 
// la variable $ListeTable qui contient la liste des tables autorises
//*********************************************************************



// Note il aurait peut etre ete plus simple d'utiliser les librairies xml::reader pour parser le fichier xml...
	global $stack;
	global $globaldata;
	// Donn�es pour la selection 
	global $ListeTable; 		// contient la liste des tables pretes a l'emploi pour la liste avec tag a et la bonne class
	global $ListeChampTableDef ; // contient la liste des champs obligatoires formatt�s avec tag input
	global $ListeChampTableFac ; // contient la liste des champs facultatifs formatt�s avec tag input
	global $TableATester ;
	global $TableAAjouter ;
	global $NomTableEnCours ;
	global $NomTableBDEnCours ;
	global $idenTableEnCours ; // Valeur un peu particuliere pour eviter de passer le nom de la table dans les param
	global $Filiere ;
	global $FiliereEnCours ;
	global $TypePecheEnCours ;
	global $RecupDonneesOK;
	global $NumChampDef;	
	global $NumChampFac;
	global $TabEnCours;
	global $groupeOK ;
	global $ignoreGroupe;
	global $groupePasAffecte;
	global $pecheLue ;
	global $typeRecupTable;
	global $ListeToutesValeurs;
	global $Groupe;
	global $GroupeEnCours;
	// pour test
	$AfficheDebug = false;
	switch(strtoupper($name)) {
		CASE "NOM":
			$NomTableBDEnCours = strtoupper($globaldata);
			$pecheLue = false;
			$Groupe ="";
			//echo "<b>".strtoupper($globaldata)."</b> - filiere = ".$FiliereEnCours." table a tester = ".$TableATester."<br/>";
			break;
		case "PECHE" :
			$groupeOK = false;
			$GroupeEnCours = "";
			$RecupDonneesOK = false;
			$ignoreGroupe = false;
			$groupePasAffecte = true;
			$pecheLue = true;
			if (strtoupper($globaldata) == strtoupper($TypePecheEnCours) || strtoupper($globaldata) == "TOUTES") {
				$TableAAjouter = true;
			} else {
				$TableAAjouter = false;
			}
			break;		
		case "GROUPE" :
			$Groupe = $globaldata;
			if ($groupeOK ) {
				//echo "choisi = ".$Groupe."<br/>";
				if ($groupePasAffecte) {
					$GroupeEnCours = $Groupe;
					$groupePasAffecte = false;
				}
				// Soit on teste le nom du groupe soit on est dans le cas ou on recupere toutes les cases a cocher
				//echo "groupe en cours = ".strtoupper($GroupeEnCours)." -  groupe a afficher = ".strtoupper($TableATester)."<br/>";
				if (!($ignoreGroupe)) {
					if ((strtoupper($GroupeEnCours) == strtoupper($TableATester)) || $typeRecupTable == "tout" ) {
						$RecupDonneesOK = true;
					} else {
						$RecupDonneesOK = false;
					}
					
				} else {
					$RecupDonneesOK = true; // On fera le controle au niveau du champs
				}
				switch ($TypePecheEnCours) {
					case "artisanale" :
					$RunFilieres = "runFilieresArt";
					break;
					case "experimentale":
					$RunFilieres = "runFilieresExp";
					break;
					case "agglomeration":
					$RunFilieres = "runFilieresStat";
					break;
				}		
				if ($TableAAjouter && $groupeOK && $typeRecupTable =="un") {
					if (strpos($ListeTable,$GroupeEnCours) === false) {
						if ((strtoupper($GroupeEnCours) == strtoupper($TableATester)) || $typeRecupTable == "tout" ) {
							$ListeTable .= "<a href=\"#\" onClick = \"".$RunFilieres."('".$TypePecheEnCours."','".$FiliereEnCours."','".$TabEnCours."','".$GroupeEnCours."','','','','','')\" class = \"colactive\">".$GroupeEnCours."</a><br/>";
						} else {
							$ListeTable .= "<a href=\"#\" onClick = \"".$RunFilieres."('".$TypePecheEnCours."','".$FiliereEnCours."','".$TabEnCours."','".$GroupeEnCours."','','','','','')\" class = \"colpasactive\">".$GroupeEnCours."</a><br/>";
						}
					}
				}	
				if ($RecupDonneesOK == true ) {
					$NomTableEnCours = $GroupeEnCours;
				}				
	
			}
			break;
		case "ALIAS" :
			// Gestion de la variable pour recuperer les noms de table a partir des alias
			if (isset($_SESSION['libelleTable'])) {
				$NbReg = count($_SESSION['libelleTable']);
			} else {
				$NbReg = 0;
			}
			$tableTrouvee = false;
			for ($cptR=1 ; $cptR<=$NbReg;$cptR++) {
				$tablib = explode(",",$_SESSION['libelleTable'][$cptR]);
				if($tablib[0] == $NomTableBDEnCours) {
					$tableTrouvee = true;
					break;
				}
			}	
			if (!$tableTrouvee) {
				$posSuiv = 	intval($NbReg) + 1;
				$_SESSION['libelleTable'][$posSuiv] = strtolower($NomTableBDEnCours).",".$globaldata;

			}
			$idenTableEnCours = $globaldata;
			break;

		default :
			break;
	}

	array_pop($stack);
}


//*********************************************************************
// new_xml_parser_Colonnes: Fonction de cr�ation du parser et d'affectation des fonctions aux gestionnaires d'�v�nements
// Pour la lecture du fichier XML contenant la definition des tables et des colonnes a afficher
function new_xml_parser_Colonnes($file,$typeRecup) {
// Cette fonction permet de cr�er le parser pour le lire le fichier XML (en param�tre) et de cr�er les evenements avant et apres l'element
// L'analyse du fichier se fait alors dans ces deux fonctions g�nant ces �v�nements avant/apr�s
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $file : le nom du fichier XML contenant la definition des tables et des colonnes a afficher
// $typeRecup : est-ce qu'on recupere un seul tableau ou toutes les donn�es contient soit "un" soit "tout"
//*********************************************************************
// En sortie : 
// une Tableau
//*********************************************************************

	global $parser_file;
	global $typeRecupTable;
	global $FiliereEnCours ;
	$typeRecupTable = $typeRecup;
	//cr�ation du parseur
	$xml_parser = xml_parser_create();
	//Activation du respect de la casse du nom des �l�ments XML
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
	//D�claration des fonctions � rattacher au gestionnaire d'�v�nement
	xml_set_element_handler($xml_parser, "startElementCol", "endElementCol");
	xml_set_character_data_handler($xml_parser, "characterData");
	xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler"); // pas utilis� ici, mais on le laisse
	//Ouverture du fichier
	if (!($fp = @fopen($file, "r"))) { return FALSE; }
	//Transformation du parseur en un tableau
	if (!is_array($parser_file)) { 
		settype($parser_file, "array"); 
	}
	$parser_file[$xml_parser] = $file;
	
	return array($xml_parser, $fp);
}



?>