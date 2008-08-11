<?php 
//*****************************************
// functions.php
//*****************************************
// Created by Yann Laurent
// 2008-07-07 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilis�es dans le portage automatique des bases de donn�es



//*********************************************************************
// WriteCompLog : �crit dans le fichier de compte rendu de comparaison
function WriteCompLog ($fichierlog,$message,$PasAutorisation) {
// Cette fonction permet d'�crire le compte rendu de comparaison dans le fichier sp�cifique.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $fichierlog : le fichier log (la variable issue du fopen(flog)
// $message : le texte � �crire dans le fichier log
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// La fonction �crit le texte dans le fichier pr�fix� de la date et l'heure, suffix� d'un saut de ligne
//*********************************************************************
	if (! $PasAutorisation) {
		if (! fwrite($fichierlog,date('y\-m\-d\-His')."- ".$message."\r\n") ) {
			logWriteTo(4,"error","Erreur d'ajout dans le fichier de compte rendu (comparaison.php)","","","0");
		}
	}
}

//*********************************************************************
// WriteCompLog : �crit dans le fichier de script le script SQL
function WriteCompSQL ($fichierSQL,$script,$PasAutorisation) {
// Cette fonction permet de g�n�rer un fichier de script SQL lors de la comparaison des donn�es.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $fichierSQL : le fichier log (la variable issue du fopen(flog)
// $script : le script � �crire dans le fichier log (attention, doit contenir le ";" en fin de script
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// La fonction �crit le texte dans le fichier le script, suffix� d'un saut de ligne
//*********************************************************************
	if (! $PasAutorisation) {
		if (! fwrite($fichierSQL,$script."\r\n") ) {
			logWriteTo(4,"error","Erreur d'ajout de script dans le fichier de script (comparaison.php)","","","0");
		}
	}
}



//*********************************************************************
// GetSQL : g�n�re le code SQL pour mettre � jour la table
function formatSQL($value,$fieldType) {
// Cette fonction permet de g�n�rer le code SQL en fonction de la table en entr�e et du type d'action � mener.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $value : la valeur en entr�e
// $fieldType : le type de la valeur
//*********************************************************************
// En sortie : 
// la valeur format�e pour le script SQL
//*********************************************************************
// Le SQL g�n�r� sera de la forme :
// Principalement si la valeur est du texte, alors, on ajoute des apostrophes autour.
$formattedValue = "";
if ( $fieldType == "integer" ) {
	$formattedValue = $value;
} else {
	$formattedValue = "'".$value."'";}

return $formattedValue;
}

//*********************************************************************
// GetSQL : g�n�re le code SQL pour mettre � jour la table
function GetSQL($SQLAction, $tableName, $whereStatement,$value,$connectionBD,$nomBD ) {
// Cette fonction permet de g�n�rer le code SQL en fonction de la table en entr�e et du type d'action � mener.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $SQLAction : quelle est l'action � faire : INSERT ou UPDATE
// $tableName : nom de la table qui subit l'action
// $whereStatement : quelle est la condition where � ajouter � l'action d'update ?
// $value : valeurs � maj (c'est un tableau issu d'un pg_fetch_row
//*********************************************************************
// En sortie : 
// La fonction renvoie le code SQL pr�t � �tre ex�cut�.
//*********************************************************************


$LocScriptSQL = "";
// Deux listes de noms de champs Up pour les updates, In pour les insert.
$LocListAttrUp = "";
$LocListAttrIn1 = "";
$LocListAttrIn2 = "";
$numChamp = 0;
// Etape 1 - on r�cup�re tous les champs de la table � ajouter ou � mettre � jour
$ListAttr="
select c.relname,a.attname,a.attnum,
pg_catalog.format_type(a.atttypid, a.atttypmod) as type
from pg_class as c, pg_attribute as a
where relname = '".$tableName."' and c.oid = a.attrelid and a.attnum > 0;";
// Lance la requete
if (!$connectionBD) {
 	logWriteTo(4,"error","Erreur connection ".$nomBD." dans la fonction getSQL de comparaison.php","","","0");
 }
$getAttrBD = pg_query($connectionBD,$ListAttr) or die('erreur dans la requete : '.pg_last_error());
if (pg_num_rows($getAttrBD) == 0) {
 	logWriteTo(4,"error","Erreur dans la lecture definition de la table ".$tableName." dans la BD ".$nomBD." (function // GetSQL portage automatique)","","","0");
} else {
	while ($getAttrBDRow = pg_fetch_row($getAttrBD)) {
		// construit la liste des champs pour l'insert
		// Liste des colonnes
		// numChamp stocke le num�ro d'ordre du champs
		$numChamp = $getAttrBDRow[2] - 1;
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
	
	logWriteTo(4,"notice","LocListAttr= ".$tableName.$LocListAttrUp,"","","1");
} 
// Etape 2 - on construit l'instruction SQL compl�te.
switch ($SQLAction) {
	case "udpdate":
		$LocScriptSQL ="update ".$tableName." set ".$LocListAttrUp." ".$whereStatement ;
		break;
	case "insert":
		$LocScriptSQL ="insert into ".$tableName." (".$LocListAttrIn1.") values (".$LocListAttrIn2.")";
		break;
	case "delete":
		$LocScriptSQL ="delete from ".$tableName." ".$whereStatement ;
		break;
} 


return $LocScriptSQL;
}

//*********************************************************************
// runQuery : ex�cute une requete SQL en captant les erreurs
function runQuery($scriptSQLToRun,$connectionBD) {
// Cette fonction permet d'ex�cuter un script SQL en r�cup�rant les erreurs dans le log.
// ELle va renvoyer l'�tat d'ex�cution de la requ�te. Aucun warning SQL ne sera affich� � l'�cran.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $scriptSQLToRun : le script � ex�cuter.
// $connectionBD : la connection de BD sur laquelle lancer le script
//*********************************************************************
// En sortie : 
// La fonction renvoie si la requete a ete correctement ex�cut�e.
//*********************************************************************
$runQueryOK = true;

$lev=error_reporting (8); //NO WARNING!!
$compINSResult = pg_query($connectionBD,$scriptSQLToRun);




error_reporting ($lev); //DEFAULT!!
if (strlen ($r=pg_last_error ($connectionBD))) {
	$runQueryOK = false;
	logWriteTo(4,"error","erreur execution : '".$scriptSQLToRun."' message = ".$r,"","","0");


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

?>