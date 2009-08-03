<?php 
//*****************************************
// extraction_xml.php
//*****************************************
// Created by Yann Laurent
// 2009-06-29 : creation
//*****************************************
// Ce programme gere les lectures des fichiers XML
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
// Fonction associ�e � l��v�nement d�but d��l�ment
function startElement($parser, $name, $attrs){

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
	
	//affichage des attributs de l'�l�ment
	//while (list ($key, $val) = each ($attrs))
	//	{echo "$key => $val";}
	//print " ";

}

// Fonction associ�e � l��v�nement fin d��l�ment
function endElement($parser, $name){

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

// Fonction de cr�ation du parser et d'affectation
// des fonctions aux gestionnaires d'�v�nements
function new_xml_parser($file) {
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



// Fonction associ�e � l��v�nement d�but d��l�ment
function startElementCol($parser, $name, $attrs){
	global $stack;
	// Donn�es pour la selection 
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
				// On v�rifie que ce champs n'a pas d�j� �t� coch�
					if (!($_SESSION['listeColonne'] == "")) {
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

// Fonction associ�e � l��v�nement fin d��l�ment
function endElementCol($parser, $name){
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



// Fonction de cr�ation du parser et d'affectation
// des fonctions aux gestionnaires d'�v�nements
function new_xml_parser_Colonnes($file) {

	global $parser_file;
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