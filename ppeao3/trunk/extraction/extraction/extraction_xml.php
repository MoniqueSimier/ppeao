<?php 
//*****************************************
// extraction_xml.php
//*****************************************
// Created by Yann Laurent
// 2009-06-29 : creation
//*****************************************
// Ce programme gere les lectures des fichiers XML
//*****************************************
// Paramètres en entrée
// aucun pour l'instant.
// Paramètres en sortie
// aucun pour l'instant.
//*****************************************

// Etat de la pile de parcours du document XML
$stack = array();
// Valeur d'un dernier élément lu
$globaldata ="";
// Flag pour savoir si on est sur la table en cours de sélection.
$RecupDonneesOK = false;
$TableAAjouter = false;
$idenTableEnCours = "";
// Fonction associée à l’événement début d’élément
function startElement($parser, $name, $attrs){

	global $stack;
	// Données pour la selection 
	global $typeSelection ;
	global $typePeche;
	global $typeStatistiques;
	global $listeEnquete ; // contiendra soit la 
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
	global $SQLEspeces	;
	global $SQLFamille ;
	global $SQLPeEnquete ;
	global $SQLdateDebut ; // format annee/mois
	global $SQLdateFin ; // format annee/mois
	
	//for ($i = 0; $i < $depth[$parser]; $i++)
	//{print " ";}
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
		$listeSelection .="<br/><b>Liste des familles</b> = ";
		break;
	case "ESPECELISTE":
		$listeSelection .="<br/><b>Liste des esp&egrave;ces</b> = ";
		break;
	case "PAYSLISTE":
		$listeSelection .="<br/><b>Liste des pays</b> = ";
		break;
	case "SYSTEMELISTE":
		$listeSelection .="<br/><b>Liste des systemes</b> = ";
		break;
	case "SECTEURLISTE":
		$listeSelection .="<br/><b>Liste des secteurs</b> = ";
		break;
	case "AGGLOMERATIONLISTE":
		$listeSelection .="<br/><b>Liste des agglom&eacute;rations</b> = ";
		break;
	case "INTERVALLE":
		$listeSelection .="<br/><b>Liste des interveaux</b> = ";
		break;
	case "GRANDTYPEENGINLISTE":
		$listeSelection .="<br/><b>Liste des GT engin</b> = ";
		break;
	case "ENGINLISTE":
		$listeSelection .="<br/><b>Liste des engins</b> = ";
		break;
	case "ENQUETELISTE":
		$listeSelection .="<br/><b>Liste des enqu&ecirc;tes</b> = ";
		break;		
	case "DATEDEBUT":
		$listeSelection .="de  ".$attrs["MOIS"]."/".$attrs["ANNEE"]." ";
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
	default :
		break;
	}
	
	
	//print "<br/>Debut de l'element : ".$name." -- ";
	
	//print "profondeur : ".$depth[$parser]." -- Attributs de l'element : ";
	
	//affichage des attributs de l'élément
	//while (list ($key, $val) = each ($attrs))
	//	{echo "$key => $val";}
	//print " ";

}

// Fonction associée à l’événement fin d’élément
function endElement($parser, $name){

	global $stack;
	global $globaldata;
		// Données pour la selection 
	global $typeSelection ;
	global $typePeche;
	global $typeStatistiques;
	global $listeGTEngin;
	// Pour construire le bandeau avec la sélection
	global $listeSelection;
	
	switch(strtoupper($name)) {
		case "FAMILLE" :
			$listeSelection .= $globaldata.", ";
			break;	
		case "ESPECE" :
			$listeSelection .= $globaldata.", ";
			break;
		case "PAYS" :
			$listeSelection .= $globaldata.", ";
			break;
		case "SYSTEME":
			$listeSelection .= $globaldata.", ";
			break;
		case "SECTEUR" :
			$listeSelection .= $globaldata.", ";
			break;
		case "AGGLOMERATION" :
			$listeSelection .= $globaldata.", ";
			break;
		case "GRANDTYPEENGIN":
			$listeSelection .= $globaldata.", ";
			break;
		case "ENGIN":
			$listeSelection .= $globaldata.", ";
			break;
		case "ENQUETE":
			$listeSelection .= $globaldata.", ";
			break;	
		default :

		break;
	}
	array_pop($stack);
}

// Fonction associée à l’événement données textuelles
function characterData($parser, $data){
	global $globaldata;
	$globaldata = $data;
}

// Fonction associée à l’événement de détection d'un appel d'entité externe
function externalEntityRefHandler($parser,$openEntityNames,$base,$systemId,$publicId){
if ($systemId) { 
	if (!list($parser, $fp) = new_xml_parser($systemId))	{
		printf("Impossible d'ouvrir %s à %s<br/>",
						   $openEntityNames,
						   $systemId);
		return FALSE;
	}
	while ($data = fread($fp, 4096)) {
		if (!xml_parse($parser, $data, feof($fp))){
			printf("Erreur XML : %s à la ligne %d lors du traitement de l'entité %s\n",
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

// Fonction de création du parser et d'affectation
// des fonctions aux gestionnaires d'événements
function new_xml_parser($file) {
	global $parser_file;
	//création du parseur
	$xml_parser = xml_parser_create();
	//Activation du respect de la casse du nom des éléments XML
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
	//Déclaration des fonctions à rattacher au gestionnaire d'événement
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	xml_set_character_data_handler($xml_parser, "characterData");
	xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler"); // pas utilisé ici, mais on le laisse
	//Ouverture du fichier
	if (!($fp = @fopen($file, "r"))) { return FALSE; }
	//Transformation du parseur en un tableau
	if (!is_array($parser_file)) { 
		settype($parser_file, "array"); 
	}
	$parser_file[$xml_parser] = $file;
	
	return array($xml_parser, $fp);
}



// Fonction associée à l’événement début d’élément
function startElementCol($parser, $name, $attrs){
	global $stack;
	// Données pour la selection 
	global $ListeTable;
	global $ListeChampTableDef ;
	global $ListeChampTableFac ;
	global $TableATester ;
	global $Filiere ;
	global $RecupDonneesOK;
	global $NumChampDef;
	global $NumChampFac;
	global $idenTableEnCours ;
	array_push($stack,$name);

	// Analyse de l'element et remplissage des variables
	switch(strtoupper($name)) {
	case "CHAMP":
			if ($RecupDonneesOK == true ) {
				if ($attrs["AFFICHAGE"] =="X") {
					$NumChampDef ++;
					$ListeChampTableDef .= "<input id=\"".$idenTableEnCours."def".$NumChampDef."\" type=\"checkbox\"  name=\"".$idenTableEnCours."\" value=\"".$idenTableEnCours."-".$attrs["CODE"]."\" checked=\"checked\" disabled=\"disabled\"/>".$attrs["LIBELLE"]."<br/>";
				} else {
				$checked = "";
				// On vérifie que ce champs n'a pas déjà été coché
					if (!($_SESSION['listeColonne'] == "")) {
						$colRecues = explode (",",$_SESSION['listeColonne']);
						$NumColR = count($colRecues) - 1;
						for ($cptCR=0 ; $cptCR<=$NumColR;$cptCR++) {
							$valTest = substr($colRecues[$cptCR],0,-2);
							// On teste si on a déjà coché cette colonne
							if ($valTest == $idenTableEnCours."-".$attrs["CODE"]) {
								if (strpos($colRecues[$cptCR],"-N") === false) {
									// Soit on vient de la cocher suffixe = -X
									$checked = "checked=\"checked\"";
								} else {
									// Soit on vient de la décocher suffixe = -N
									$checked = "";
								}
								break;
							}
						}
					}
					$NumChampFac ++;
					$ListeChampTableFac .= "<input id=\"".$idenTableEnCours."fac".$NumChampFac."\" type=\"checkbox\"  name=\"".$idenTableEnCours."\" value=\"".$idenTableEnCours."-".$attrs["CODE"]."\" ".$checked."/>".$attrs["LIBELLE"]."<br/>";
				}
				
			}
		break;
	default :
		break;
	}
}

// Fonction associée à l’événement fin d’élément
function endElementCol($parser, $name){
// Note il aurait peut etre ete plus simple d'utiliser les librairies xml::reader pour parser le fichier xml...
	global $stack;
	global $globaldata;
	// Données pour la selection 
	global $ListeTable; 		// contient la liste des tables pretes a l'emploi pour la liste avec tag a et la bonne class
	global $ListeChampTableDef ; // contient la liste des champs obligatoires formattés avec tag input
	global $ListeChampTableFac ; // contient la liste des champs facultatifs formattés avec tag input
	global $TableATester ;
	global $TableAAjouter ;
	global $NomTableEnCours ;
	global $idenTableEnCours ; // Valeur un peu particuliere pour eviter de passer le nom de la table dans les param
	global $Filiere ;
	global $FiliereEnCours ;
	global $TypePecheEnCours ;
	global $RecupDonneesOK;
	global $NumChampDef;	
	global $NumChampFac;
	global $TabEnCours;
	$filiereOK = false;
	switch(strtoupper($name)) {
		case "PECHE" :
			$filiereOK = false;
			if (strtoupper($globaldata) == strtoupper($TypePecheEnCours) || strtoupper($globaldata) == "TOUTES") {
				$TableAAjouter = true;
			} else {
				$TableAAjouter = false;
			}
			break;	
		case "STATISTIQUE" :
			$filiereOK = false;
			if (strtoupper($globaldata) == strtoupper($TypePecheEnCours) || strtoupper($globaldata) == "TOUTES") {
				$TableAAjouter = true;
			} else {
				$TableAAjouter = false;
			}
			break;	
		case "FILIERE" :
			if (!($filiereOK) { 
				 
				if (strtoupper($globaldata) == "TOUTES" || strtoupper($globaldata) == strtoupper($FiliereEnCours)) {
					$filiereOK = true;
				}
			}

			break;
		case "TESTNOM" :
			$idenTableEnCours = $globaldata;
			if (strtoupper($globaldata) == strtoupper($TableATester)) {
				$RecupDonneesOK = true;
			} else {
				$RecupDonneesOK = false;
			}
			break;
		case "LIBELLE" :
			if ($TypePecheEnCours == "artisanale") {
				$RunFilieres = "runFilieresArt";
			} else {
				$RunFilieres = "runFilieresExp";
			}
			if ($TableAAjouter && $filiereOK) {
				if ($RecupDonneesOK) {
					$ListeTable .= "<a href=\"#\" onClick = \"".$RunFilieres."('".$TypePecheEnCours."','".$FiliereEnCours."','".$TabEnCours."','".$idenTableEnCours."')\" class = \"active\">".$globaldata."</a><br/>";
				} else {
					$ListeTable .= "<a href=\"#\" onClick = \"".$RunFilieres."('".$TypePecheEnCours."','".$FiliereEnCours."','".$TabEnCours."','".$idenTableEnCours."')\" class = \"\">".$globaldata."</a><br/>";
				}
			}	
			if ($RecupDonneesOK == true ) {
				$NomTableEnCours = $globaldata;
			}
			break;	
		default :
			break;
	}

	array_pop($stack);
}



// Fonction de création du parser et d'affectation
// des fonctions aux gestionnaires d'événements
function new_xml_parser_Colonnes($file) {

	global $parser_file;
	//création du parseur
	$xml_parser = xml_parser_create();
	//Activation du respect de la casse du nom des éléments XML
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
	//Déclaration des fonctions à rattacher au gestionnaire d'événement
	xml_set_element_handler($xml_parser, "startElementCol", "endElementCol");
	xml_set_character_data_handler($xml_parser, "characterData");
	xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler"); // pas utilisé ici, mais on le laisse
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