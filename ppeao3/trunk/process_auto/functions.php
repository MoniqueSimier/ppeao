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
			logWriteTo(7,"error","Erreur d'ajout dans le fichier de compte rendu (comparaison.php)","","","0");
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
			logWriteTo(7,"error","Erreur d'ajout de script dans le fichier de script (comparaison.php)","","","0");
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
		$value = str_replace("'","''",$value);
		$formattedValue = "'".$value."'";
	}
}	

return $formattedValue;
}

//*********************************************************************
// GetSQL : g�n�re le code SQL pour mettre � jour la table
function GetSQL($SQLAction, $tableName, $whereStatement,$value,$connectionBD,$nomBD,$typeProcess,$PathFichierConf,$newID,$majID,$ListeTableIDPasNum,$debugBool,$start_time) {
// Cette fonction permet de g�n�rer le code SQL en fonction de la table en entr�e et du type d'action � mener.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $SQLAction : quelle est l'action � faire : INSERT ou UPDATE
// $tableName : nom de la table qui subit l'action
// $whereStatement : quelle est la condition where � ajouter � l'action d'update ?
// $value : valeurs � maj (c'est un tableau issu d'un pg_fetch_row
// $connectionBD : connexion BD sur laquelle ex�cuter la requete pour le dico
// $nomBD : nom de la BD pour les logs
// $typeProcess : nom du processus qui appelle cette fonction pour decider si l'ID doit etre mis � jour
// $PathFichierConf : nom du fichier de conf
//*********************************************************************
// En sortie : 
// La fonction renvoie le code SQL pr�t � �tre ex�cut�.
//*********************************************************************

// La liste des tables pour lesquelles l'ID peut etre modifie
$locTableMajID = GetParam("listeTableMajsc",$PathFichierConf);
$locTableMajID = $locTableMajID.",".GetParam("listeTableMajrec",$PathFichierConf);

$LocScriptSQL = "";
// Deux listes de noms de champs Up pour les updates, In pour les insert.
$LocListAttrUp = "";
$LocListAttrIn1 = "";
$LocListAttrIn2 = "";
$locNouvelID = 0;

$numChamp = 0;
// Etape 1 - on r�cup�re tous les champs de la table � ajouter ou � mettre � jour
if ($debugBool) {
	$debugTimer = number_format(timer()-$start_time,4);
	echo "getSQL avant requete attr :".$debugTimer."<br/>";
}
$ListAttr="
select c.relname,a.attname,a.attnum,
pg_catalog.format_type(a.atttypid, a.atttypmod) as type
from pg_class as c, pg_attribute as a
where relname = '".$tableName."' and c.oid = a.attrelid and a.attnum > 0;";
// Lance la requete dans la base de reference (base source)
if (!$connectionBD) {
 	logWriteTo(7,"error","Erreur connexion ".$nomBD." dans la fonction getSQL de comparaison.php","","","0");
 }
$getAttrBD = pg_query($connectionBD,$ListAttr) or die('erreur dans la requete : '.pg_last_error());
if (pg_num_rows($getAttrBD) == 0) {
 	logWriteTo(7,"error","Erreur dans la lecture definition de la table ".$tableName." dans la BD ".$nomBD." (function // GetSQL portage automatique)","","","0");
} else {
	if ($debugBool) {
		$debugTimer = number_format(timer()-$start_time,4);
		echo "getSQL debut trt attr :".$debugTimer."<br/>";
	}
	// On recupere si necessaire l'ID
	while ($getAttrBDRow = pg_fetch_row($getAttrBD)) {
		// On n'ajoute pas le champs ID pour l'update
		if ($getAttrBDRow[1] =="id" && $SQLAction == "update") {
			continue;
		}
		// numChamp stocke le num�ro d'ordre du champs
		$numChamp = $getAttrBDRow[2] - 1;

		// construit la liste des champs pour l'insert
		// Liste des colonnes
		$IDdanschp = strpos($getAttrBDRow[1],"_id");
		//echo $tableName." strpos = ".$IDdanschp."<br/>";
		//if (strpos($getAttrBDRow[1],"_id") > 0) {
		//	echo $tableName." strpos = ".$IDdanschp." ".$getAttrBDRow[1]."<br/>";
		//}
		//if ($tableName == "exp_coup_peche"){
		//echo $getAttrBDRow[1]." <br/>";
		//}
		if (($typeProcess == "majsc" || $typeProcess == "majrec") 
			&& ( strpos($getAttrBDRow[1],"_id") > 0 ) ){
			//echo "col = ".$getAttrBDRow[1]."<br/>";
			// On a fait un premier filtre sur tous les champs qui contiennent id 
			// MAIS qui ne sont pas l'ID de la table en cours
			// On controle ensuite que le champs n'est pas une reference a une table dont il faut changer l'ID
			$tableAControler = str_replace("_id","",$getAttrBDRow[1]);
			//
			$testID = strpos($locTableMajID ,$tableAControler);
			//echo $SQLAction." nomtable = ".$tableAControler." testID = ".$testID."<br/>";
			if ($testID === false) {
				// On garde la valeur du champ
				$valChamp = $value[$numChamp];
			} else {
				// il faut recuperer le nouvel ID
				//echo "faut recuperer l'ID<br/>";
				if (! $value[$numChamp] == null) {
				
					//echo "controle table = ".$tableAControler." value = ".$value[$numChamp]."<br/>";
					// Attention, on peut avoir des ID en char....
					$testTtypeVarID = strpos($ListeTableIDPasNum ,$tableAControler);
					if ($testTtypeVarID === false) {
						// L'ID est bien un num�rique
					$recIDSQL = " select idcible from temp_recomp_id where nomtable = '".$tableAControler."' and Idsource = ".$value[$numChamp] ;	
					} else {
						// L'ID est une chaine
						$recIDSQL = " select idcibleChar from temp_recomp_id where nomtable = '".$tableAControler."' and IdsourceChar = '".$value[$numChamp]."'" ;
					}
					$recIDSQLResult = pg_query($connectionBD,$recIDSQL) or die('erreur dans la requete : '.pg_last_error());
					if (pg_num_rows($recIDSQLResult) == 0) {
						// on garde le meme ID
						$locNouvelID = $value[$numChamp];
						echo $tableAControler."erreur lecture nouvel ID / on garde l'ancien ID = ".$value[$numChamp]."<br/>";
					} else {
						$recIDRow = pg_fetch_row($recIDSQLResult);
						$valChamp = $recIDRow[0];
						//echo "Ancienne valeur de l'ID pour ".$getAttrBDRow[1]." = ".$value[$numChamp]." nouvelle valeur = ".$recIDRow[0]."<br/>";
					}
					pg_free_result($recIDSQLResult);
				} else {
					$valChamp = $value[$numChamp]; // sera toujours NULL !
				}
			}
		} else {
			$valChamp = $value[$numChamp];
		}
		// On recupere si necessaire l'ID
		if ($majID == "y" && $getAttrBDRow[1] =="id") {
			if ($newID == null) {
				// on garde le meme ID
				$locNouvelID = $value[$numChamp];
			} else {

				$valChamp = $newID;

			}

		}
		if ($SQLAction=="insert") {
			if ($LocListAttrIn1 == "" ) {
				$LocListAttrIn1 = $getAttrBDRow[1];
			} else {
				$LocListAttrIn1.=",".$getAttrBDRow[1] ; 
			}
			// Liste des valeurs
			if ($LocListAttrIn2 == "" ) {
				$LocListAttrIn2 = formatSQL($valChamp,$getAttrBDRow[3]);
			} else {
				$LocListAttrIn2.=",".formatSQL($valChamp,$getAttrBDRow[3]) ; 
			}
		}
		// construit la liste des champs pour l'update
		if ($SQLAction=="update") {
			if ($LocListAttrUp == "" ) {
				$LocListAttrUp = $getAttrBDRow[1]."=".formatSQL($valChamp,$getAttrBDRow[3]) ;
			} else {
				$LocListAttrUp.=",".$getAttrBDRow[1]."=".formatSQL($valChamp,$getAttrBDRow[3]) ; 
			}	
		}
	}
	//logWriteTo(7,"notice",$SQLAction." pour ".$tableName." LocListAttr = ".$LocListAttrUp,"","","1");
	if ($debugBool) {
		$debugTimer = number_format(timer()-$start_time,4);
		echo "getSQL fin trt attr :".$debugTimer."<br/>";
	}


} 
//	echo $LocListAttrIn2."<br/>";
// Etape 2 - on construit l'instruction SQL compl�te.
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
	logWriteTo(7,"error","erreur execution : '".$scriptSQLToRun."'" ,"message = ".$r,"","0");


}

return $runQueryOK;

}

//*********************************************************************
// runQuery : ex�cute une requete SQL en captant les erreurs
function stockQuery($scriptSQLToRun,$ScriptComplet) {
// Cette fonction permet d'ex�cuter un script SQL en r�cup�rant les erreurs dans le log.
// On utilise le double ; comme s�parateur pour eviter lors de l'execution pas a pas du SQL
// par un explode les cas ou un ; est present dans le champ memo
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $scriptSQLToRun : le script � ex�cuter.
// $ScriptComplet : le script contenant l'ensemble des requetes
//*********************************************************************
// En sortie : 
// La fonction renvoie la variable contenant l'ensemble des requetes a executer
//*********************************************************************

$locScript =$ScriptComplet.$scriptSQLToRun.";;";

return $locScript;
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

// Function timer() : pour g�rer un chronom�tre 
function timer(){ //chronom�tre - http://www.phpcs.com/code.aspx?ID=32471
	$time=explode(' ',microtime());
	return $time[0] + $time[1];
} 

//*********************************************************************
// GetParam : recupere dans le fichier de configuration la valeur d'un parametre
function GetParam($nomParam,$nomFichier) {
// Cette fonction permet de lire le fichier de parametrage et de renvoyer la valeur du parametre
// Si le parametre n'existe pas dans le fichier, on renvoie false.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $nomParam : nom du param�tre � extraire.
// $nomFichier : nom du fichier de param�tre � ouvrir
//*********************************************************************
// En sortie : 
// La fonction renvoie la valeur du param�tre (string) sinon false si le param�tre n'est pas trouv�e
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
		// Le param�tre n'est pas trouv� dans le fichier
			$ParamValue = false;
		} else {
		// On a trouve le param�tre, on r�cup�re sa valeur
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