<?php 
//*****************************************
// functions.php
//*****************************************
// Created by Yann Laurent
// 2008-07-07 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilisées dans le portage automatique des bases de données



//*********************************************************************
// WriteCompLog : écrit dans le fichier de compte rendu de comparaison
function WriteCompLog ($fichierlog,$message,$PasAutorisation) {
// Cette fonction permet d'écrire le compte rendu de comparaison dans le fichier spécifique.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $fichierlog : le fichier log (la variable issue du fopen(flog)
// $message : le texte à écrire dans le fichier log
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// La fonction écrit le texte dans le fichier préfixé de la date et l'heure, suffixé d'un saut de ligne
//*********************************************************************
	if (! $PasAutorisation) {
		if (! fwrite($fichierlog,date('y\-m\-d\-His')."- ".$message."\r\n") ) {
			logWriteTo(4,"error","Erreur d'ajout dans le fichier de compte rendu (comparaison.php)","","","0");
		}
	}
}

//*********************************************************************
// WriteCompLog : écrit dans le fichier de script le script SQL
function WriteCompSQL ($fichierSQL,$script,$PasAutorisation) {
// Cette fonction permet de générer un fichier de script SQL lors de la comparaison des données.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $fichierSQL : le fichier log (la variable issue du fopen(flog)
// $script : le script à écrire dans le fichier log (attention, doit contenir le ";" en fin de script
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// La fonction écrit le texte dans le fichier le script, suffixé d'un saut de ligne
//*********************************************************************
	if (! $PasAutorisation) {
		if (! fwrite($fichierSQL,$script."\r\n") ) {
			logWriteTo(4,"error","Erreur d'ajout de script dans le fichier de script (comparaison.php)","","","0");
		}
	}
}



//*********************************************************************
// GetSQL : génère le code SQL pour mettre à jour la table
function formatSQL($value,$fieldType) {
// Cette fonction permet de générer le code SQL en fonction de la table en entrée et du type d'action à mener.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $value : la valeur en entrée
// $fieldType : le type de la valeur
//*********************************************************************
// En sortie : 
// la valeur formatée pour le script SQL
//*********************************************************************
// Le SQL généré sera de la forme :
// Principalement si la valeur est du texte, alors, on ajoute des apostrophes autour.
$formattedValue = "";
if ( $fieldType == "integer" or $fieldType == "real") {
	if ($value == null) {
		$formattedValue = "NULL";
	} else {
		$formattedValue = $value;
	}
} else {
	if ($value == null) {
		$formattedValue = "NULL";
	} else {
		$formattedValue = "'".$value."'";
	}
}	

return $formattedValue;
}

//*********************************************************************
// GetSQL : génère le code SQL pour mettre à jour la table
function GetSQL($SQLAction, $tableName, $whereStatement,$value,$connectionBD,$nomBD ) {
// Cette fonction permet de générer le code SQL en fonction de la table en entrée et du type d'action à mener.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLAction : quelle est l'action à faire : INSERT ou UPDATE
// $tableName : nom de la table qui subit l'action
// $whereStatement : quelle est la condition where à ajouter à l'action d'update ?
// $value : valeurs à maj (c'est un tableau issu d'un pg_fetch_row
//*********************************************************************
// En sortie : 
// La fonction renvoie le code SQL prêt à être exécuté.
//*********************************************************************


$LocScriptSQL = "";
// Deux listes de noms de champs Up pour les updates, In pour les insert.
$LocListAttrUp = "";
$LocListAttrIn1 = "";
$LocListAttrIn2 = "";
$numChamp = 0;
// Etape 1 - on récupère tous les champs de la table à ajouter ou à mettre à jour
$ListAttr="
select c.relname,a.attname,a.attnum,
pg_catalog.format_type(a.atttypid, a.atttypmod) as type
from pg_class as c, pg_attribute as a
where relname = '".$tableName."' and c.oid = a.attrelid and a.attnum > 0;";
// Lance la requete dans la base de reference (base source)
if (!$connectionBD) {
 	logWriteTo(4,"error","Erreur connection ".$nomBD." dans la fonction getSQL de comparaison.php","","","0");
 }
$getAttrBD = pg_query($connectionBD,$ListAttr) or die('erreur dans la requete : '.pg_last_error());
if (pg_num_rows($getAttrBD) == 0) {
 	logWriteTo(4,"error","Erreur dans la lecture definition de la table ".$tableName." dans la BD ".$nomBD." (function // GetSQL portage automatique)","","","0");
} else {
	while ($getAttrBDRow = pg_fetch_row($getAttrBD)) {
		// On n'ajoute pas le champs ID
		if ($getAttrBDRow[1] =="id" && $SQLAction == "update") {
			continue;
		}
		// construit la liste des champs pour l'insert
		// Liste des colonnes
		// numChamp stocke le numéro d'ordre du champs
		$numChamp = $getAttrBDRow[2] - 1;
		//logWriteTo(4,"notice","","numchamp = ".$numChamp." ".$getAttrBDRow[3]." valeur = ".$value[$numChamp],"","1");
		if ($LocListAttrIn1 == "" ) {
 			$LocListAttrIn1 = $getAttrBDRow[1];
		} else {
 			$LocListAttrIn1.=",".$getAttrBDRow[1] ; 
		}
		// Liste des valeurs
		if ($LocListAttrIn2 == "" ) {
 			$LocListAttrIn2 = formatSQL($value[$numChamp],$getAttrBDRow[3]);
		} else {
 			$LocListAttrIn2.=",".formatSQL($value[$numChamp],$getAttrBDRow[3]) ; 
		}	
		// construit la liste des champs pour l'update
		if ($LocListAttrUp == "" ) {
 			$LocListAttrUp = $getAttrBDRow[1]."=".formatSQL($value[$numChamp],$getAttrBDRow[3]) ;
		} else {
 			$LocListAttrUp.=",".$getAttrBDRow[1]."=".formatSQL($value[$numChamp],$getAttrBDRow[3]) ; 
		}
	}
	
	//logWriteTo(4,"notice",$SQLAction." pour ".$tableName." LocListAttr = ".$LocListAttrUp,"","","1");
} 
// Etape 2 - on construit l'instruction SQL complète.
switch ($SQLAction) {
	case "update":
		$LocScriptSQL ="update ".$tableName." set ".$LocListAttrUp." ".$whereStatement ;
		break;
	case "insert":
		$LocScriptSQL ="insert into ".$tableName." (".$LocListAttrIn1.") values (".$LocListAttrIn2.")";
		break;
	case "delete":
		$LocScriptSQL ="delete from ".$tableName." ".$whereStatement ;
		break;
} 

pg_free_result($getAttrBD);
return $LocScriptSQL;
}

//*********************************************************************
// runQuery : exécute une requete SQL en captant les erreurs
function runQuery($scriptSQLToRun,$connectionBD) {
// Cette fonction permet d'exécuter un script SQL en récupérant les erreurs dans le log.
// ELle va renvoyer l'état d'exécution de la requête. Aucun warning SQL ne sera affiché à l'écran.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $scriptSQLToRun : le script à exécuter.
// $connectionBD : la connection de BD sur laquelle lancer le script
//*********************************************************************
// En sortie : 
// La fonction renvoie si la requete a ete correctement exécutée.
//*********************************************************************
$runQueryOK = true;

$lev=error_reporting (8); //NO WARNING!!
$compINSResult = pg_query($connectionBD,$scriptSQLToRun);




error_reporting ($lev); //DEFAULT!!
if (strlen ($r=pg_last_error ($connectionBD))) {
	$runQueryOK = false;
	logWriteTo(4,"error","erreur execution : '".$scriptSQLToRun."'" ,"message = ".$r,"","0");


}

return $runQueryOK;

}

/**
 * Print out debug info (including arrays)
 */
function print_debug($dbgstr0){
    ob_start();
    print_r($dbgstr0);
    $dbgstr = ob_get_contents();
    ob_end_clean();   
    
    $fpOut = fopen("error.log2", "a+");
    fwrite($fpOut, "\n$dbgstr");
    fclose($fpOut);
}

function getTime() {
    static $timer = false, $start;
    if ($timer === false) {
        $start = array_sum(explode(' ',microtime()));
        $timer = true;
        return NULL;
    } else {
        $timer = false;
        $end = array_sum(explode(' ',microtime()));
        return round(($end - $start) * 1000, 3);
    }
}

// Function timer() : pour gérer un chronomètre 
function timer(){ //chronomètre - http://www.phpcs.com/code.aspx?ID=32471
	$time=explode(' ',microtime());
	return $time[0] + $time[1];
} 

//*********************************************************************
// GetParam : recupere dans le fichier de configuration la valeur d'un parametre
function GetParam($nomParam,$nomFichier) {
// Cette fonction permet de lire le fichier de parametrage et de renvoyer la valeur du parametre
// Si le parametre n'existe pas dans le fichier, on renvoie false.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $nomParam : nom du paramètre à extraire.
// $nomFichier : nom du fichier de paramètre à ouvrir
//*********************************************************************
// En sortie : 
// La fonction renvoie la valeur du paramètre (string) sinon false si le paramètre n'est pas trouvée
//*********************************************************************

if ( file_exists($nomFichier)) {

	$ficParam = fopen($nomFichier,"r");
	while (!feof($ficParam)) {
   		$ficLine = fgets($ficParam);
		if (substr  ( $ficLine  , 0,1  ) ==";") {
			continue;
		} ;
		$posParam = strpos($ficLine,$nomParam);
		if ( $posParam === false ){
		// Le paramètre n'est pas trouvé dans le fichier
			$ParamValue = false;
		} else {
		// On a trouve le paramètre, on récupère sa valeur
		// Cette valeur est comprise en le signe = et ;
			$longNomParam = strlen($nomParam) +1;
			$valeurParam = substr  ( $ficLine  , $longNomParam  );
			$ParamValue = str_replace(";","",$valeurParam);
			$ParamValue = trim( $ParamValue);
			break;
		}
	} 
	fclose($ficParam);
} else {
	$ParamValue = false;
}
return $ParamValue;
}
?>