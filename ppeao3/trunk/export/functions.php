<?php 
//*****************************************
// functions.php
//*****************************************
// Created by Yann Laurent
// 2008-12-10 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilisées dans l'export vers les bases ACCESS

//*********************************************************************
// getTableNamePostGRE : permet de recuperer juste le nom postgres de la table ACCESS
function getTableNamePostGRE($fichier,$nomTable) {
// Cette fonction permet de générer le code SQL en fonction de la table en entrée et du type d'action à mener.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $fichier : le nom du fichier xml a lire
// $nomTable : nom de la table pour filtrer les valeurs que l'on renvoie
//*********************************************************************
// En sortie : 
// La fonction renvoie un tableau contenant le nom de la table, le nom POSTGRE et les listes des champs 
// sous forme d'une portion d'XML
//*********************************************************************
$nomTablePostGRE = "";
if($chaine = @implode("",@file($fichier))) {
	// on explode sur <item>
	$tmp = preg_split("/<\/?table>/",$chaine);
	// pour chaque <item> 
	for($i=1;$i<sizeof($tmp)-1;$i+=2) {
		$majok = false;
		// on lit les champs demandés <champ> 
		$champs = array("tablename","postgretablename");
		foreach($champs as $champ) {
			$tmp2 = preg_split("/<\/?".$champ.">/",$tmp[$i]);
			// on ajoute l'élément au tableau
			if ($champ == "tablename" && ($tmp2[1] == $nomTable)) {
				$majok = true;
			} else {
				if ($majok) {
					$nomTablePostGRE = @$tmp2[1];
				} else {
					continue;
				}					
			}
		}
	}
}

return $nomTablePostGRE;
}


//*********************************************************************
// litXML : lit un fichier XML contenant la descritpion des tables ACCESS et leur contrepartie POSTGRESQL
function litXML($fichier,$item,$champs,$nomTable) {
// Cette fonction permet de générer le code SQL en fonction de la table en entrée et du type d'action à mener.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $fichier : le nom du fichier xml a lire
// $item : l'element a partir duquel lire l'arbre XML
// $champs : la liste des champs a lire
// $nomTable : nom de la table pour filtrer les valeurs que l'on renvoie
//*********************************************************************
// En sortie : 
// La fonction renvoie un tableau contenant le nom de la table, le nom POSTGRE et les listes des champs 
// sous forme d'une portion d'XML
//*********************************************************************
	$tmp3= array();
	$Jaitrouvelatable = false;
	// on lit le fichier
	if($chaine = @implode("",@file($fichier))) {
		// on explode sur <item>
		$tmp = preg_split("/<\/?".$item.">/",$chaine);
		// pour chaque <item> 
		for($i=1;$i<sizeof($tmp)-1;$i+=2) {
			$majok = false;
			// on lit les champs demandés <champ> 
			foreach($champs as $champ) {
				$tmp2 = preg_split("/<\/?".$champ.">/",$tmp[$i]);
				// on ajoute l'élément au tableau
			
				if ($champ == "tablename" && ($tmp2[1] == $nomTable)) {
					$tmp3[$i-1][] = @$tmp2[1];
					$majok = true;
					$Jaitrouvelatable = true;
				} else {
					if ($majok) {
						$tmp3[$i-1][] = @$tmp2[1];
					} else {
						continue;
					}					
				}
			}
		}
		// et on retourne le tableau dans la fonction
		if (!$Jaitrouvelatable) {
			$LocScriptSQL = "*-ERREUR*- pas de definition de la structure de la table ".$nomTable." dans le fichier XML /conf/AccessConv.xml";
			return $LocScriptSQL;
		} else {
			foreach($tmp3 as $row) {
				$tableNamePostGRE = $row[1];
				$ListAttr = $row[2];
			}
			// On reconstruit la liste des champs a partir de la partie d'xml renvoyé par la fonction litXML.
			$champ = "champ";
			$tempval = preg_split("/<\/?champ>/",$ListAttr);
			$cptArr = 0;
			for($cpt=1;$cpt<sizeof($tempval)-1;$cpt+=2) {
				$tempval2 = preg_split("/<\/?name>/",$tempval[$cpt]);
				$tempArray[$cptArr][0] = $tempval2[1];
				$tempval2 = preg_split("/<\/?type>/",$tempval[$cpt]);
				$tempArray[$cptArr][1] = $tempval2[1];
				$tempval2 = preg_split("/<\/?postgrename>/",$tempval[$cpt]);
				$tempArray[$cptArr][2] = $tempval2[1];	
				$tempval2 = preg_split("/<\/?postgretype>/",$tempval[$cpt]);	
				$tempArray[$cptArr][3] = $tempval2[1];
				if (strpos($tempval[$cpt],"valdefaut") === false) {
					$tempArray[$cptArr][4] = "rien";
				} else {
					$tempval2 = preg_split("/<\/?valdefaut>/",$tempval[$cpt]);	
					$tempArray[$cptArr][4] = $tempval2[1];
				}
				$cptArr++;
			}
			return $tempArray;
		}
	}
}

//*********************************************************************
// GetSQL : génère le code SQL pour mettre à jour la table
function GetSQLACCESS($SQLAction, $tableName, $whereStatement,$value,$BDType,$nomTableACCESS,$connACCESS,$connPOST) {
// Cette fonction permet de générer le code SQL en fonction de la table en entrée et du type d'action à mener.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLAction : quelle est l'action à faire : INSERT ou UPDATE
// $tableName : nom de la table qui subit l'action
// $whereStatement : quelle est la condition where à ajouter à l'action d'update ?
// $value : valeurs à maj (c'est un tableau issu d'un pg_fetch_row
// $BDType : soit pour maj ACCESS soit POSTGRES
// $nomTableACCESS: nom de la table pour la recherche dans le fichier XML
// $connACCESS: connection a la base ACCESS de travail
// $connPOST: connection a la base PPEAO de reference
//*********************************************************************
// En sortie : 
// La fonction renvoie le code SQL prêt à être exécuté.
//*********************************************************************

$LocScriptSQL = "";
// Deux listes de noms de champs Up pour les updates, In pour les insert.
$LocListAttrUp = "";
$LocListAttrIn1 = "";
$LocListAttrIn2 = "";
$locNouvelID = 0;

$numChamp = 0;
// Etape 1 - on récupère tous les champs de la table à ajouter ou à mettre à jour

// Lecture du du fichier XML et recuperation de la liste des champs (champ1 ACCESS, nom champ1 dans la table POSTGRE,
// champ2 ACCESS, nom champ2 dans la table POSTGRE, etc...)

$ficXMLDef = $_SERVER["DOCUMENT_ROOT"]."/conf/AccessConv.xml";
$tempArray = litXML($ficXMLDef,"table",array("tablename","postgretablename","champs"),$nomTableACCESS);

$Attr = $tempArray;
$nbAttr = count($Attr) - 1;
for ($cpt = 0; $cpt <= $nbAttr; $cpt++) {
	// on recupère le nom des champs 
	$NomChampACCESS = $Attr[$cpt][0];
	$typeChampACCESS = $Attr[$cpt][1];
	$NomChampPOSTGRE = $Attr[$cpt][2];
	$typeChampPOSTGRE = $Attr[$cpt][3];
	switch ($Attr[$cpt][4]) {
		case "rien" : 
			$valeurDefaut ="";
			break;
		case "vide" : 
			if ($typeChampACCESS == "integer" || $typeChampACCESS == "real") {
				$valeurDefaut = 0; 
			} else {
				$valeurDefaut = "' '";
			}
			break;
		default : 
			$valeurDefaut = $Attr[$cpt][4];
			break;	
	}
	// selon la base a maj, ce ne sont pas les memes noms de champs
	switch ($BDType) {
		case "ACCESS" : 
			$nomChampSQL = $NomChampACCESS;
			break;
		case "POSTGRE" : 
			$nomChampSQL = $NomChampPOSTGRE;
			break;
	}
	switch ($NomChampPOSTGRE) {
		case "CALCULAUTO" :
			// On doit generer un ID a partir de la derniere valeur de la table ACCESS
			$countSQL = "select max(".$NomChampACCESS.") from ".$nomTableACCESS;
			$countSQLResult = odbc_exec($connACCESS,$countSQL);
			$erreurSQL = odbc_errormsg($connACCESS); //
			if (! $countSQLResult) {
				echo "erreur dans ".$countSQL." recacul ID dans GetSQLACCESS<br/>";
				$cptSQLErreur ++;
			} else {
				$row = odbc_fetch_row($countSQLResult);
				$valChampSQL = intval(odbc_result($countSQLResult,1)) + 1 ;
			}
			break;
		case "AUTRETABLE" :
			// On doit recuperer la valeur dans une autre table (uniquement valable pour POSTGRE)
			
			break;
		default:
			if ($valeurDefaut == "") {
				$valChamp = $value[$nomChampSQL];
				$valChampSQL = formatSQL($valChamp,$typeChampACCESS);
			} else {
				$valChampSQL = $valeurDefaut;
			}
	}

	//echo $NomChampACCESS." ".$valChamp."<br/>";	
	if ($SQLAction=="insert") {
		if ($LocListAttrIn1 == "" ) {
			$LocListAttrIn1 = $NomChampACCESS;
		} else {
			$LocListAttrIn1.=",".$NomChampACCESS ; 
		}
		// Liste des valeurs
		if ($LocListAttrIn2 == "" ) {
			$LocListAttrIn2 = $valChampSQL;
		} else {
			$LocListAttrIn2.=",".$valChampSQL ; 
		}
	}
	// construit la liste des champs pour l'update
	if ($SQLAction=="update") {
		if ($LocListAttrUp == "" ) {
			$LocListAttrUp = $NomChampACCESS."=".$valChampSQL ;
		} else {
			$LocListAttrUp.=",".$NomChampACCESS."=".$valChampSQL ; 
		}	
	}
}

// Etape 2 - on construit l'instruction SQL complète.
switch ($SQLAction) {
	case "update":
		$LocScriptSQL ="update ".$nomTableACCESS." set ".$LocListAttrUp." ".$whereStatement ;
		break;
	case "insert":
		$LocScriptSQL ="insert into ".$nomTableACCESS." (".$LocListAttrIn1.") values (".$LocListAttrIn2.")";
		break;
	case "delete":
		$LocScriptSQL ="delete from ".$nomTableACCESS." ".$whereStatement ;
		break;
} 


return $LocScriptSQL;
}





?>