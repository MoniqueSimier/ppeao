<?php 
//*****************************************
// functions_SQL.php
//*****************************************
// Created by Yann Laurent
// 2008-07-11 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilis�es pour la g�n�ration de scripts SQL ou de leur manipulation



//*********************************************************************
// OpenFileReverseSQL : �crit dans le fichier de compte rendu de comparaison
function OpenFileReverseSQL ($howOpen,$direcLog,$PasAutorisation) {
// Cette fonction permet d'ouvrir le fichier contenant les scripts SQL pour r�aliser les actions inverses.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// howOpen : comment ouvrir le fichier : en ajout (='ajout') ou en ecrasement (='ecras') ?
// direcLog : le r�pertoire ou se trouve le fichier log
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// - Renvoie la ressource  fichier ouvert
//*********************************************************************
	if (! $PasAutorisation) {
		if (! file_exists($direcLog)) {
			if (! mkdir($direcLog) ) {
				$messageGen = " erreur de cr�ation du r�pertoire de log";
				logWriteTo(4,"error","Erreur de creation du repertoire de log dans comparaison.php","","","0");
				return false;
			}
		}
		if ($howOpen =="ajout") {
			$ficOpen = fopen($direcLog."/CompReverseSQL.sql", "a+");
		} else {
			$ficOpen = fopen($direcLog."/CompReverseSQL.sql", "w");
		
		}
		if (! $ficOpen ) {
			logWriteTo(4,"error","Erreur d'ouverture du fichier SQL reverse (function OpenFileReverseSQL) ","","","0");
		}
		return $ficOpen;
	}
}

//*********************************************************************
// OpenFileReverseSQL : �crit dans le fichier de compte rendu de comparaison
function CloseFileReverseSQL ($fileRevSQL,$PasAutorisation) {
// Cette fonction permet d'ouvrir le fichier contenant les scripts SQL pour r�aliser les actions inverses.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// fileRevSQL : fichier SQL
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// -
//*********************************************************************
	if (! $PasAutorisation) {
		fclose($fileRevSQL);
	}
}

//*********************************************************************
// OpenFileReverseSQL : �crit dans le fichier de compte rendu de comparaison
function WriteFileReverseSQL ($fileRevSQL,$script,$PasAutorisation) {
// Cette fonction permet d'ouvrir le fichier contenant les scripts SQL pour r�aliser les actions inverses.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// fileRevSQL : fichier SQL
// script : le script SQL � �crire
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// -
//*********************************************************************
// On teste si un ; est pr�sent � la fin du script SQL, sinon le rajouter
	if (! $PasAutorisation) {
		$pos = strrpos($script, ";");
		if ($pos === false) { 
			$script.=";";
		} else {
			if ( ! $pos == strlen($script)) {
				$script.=";";
			}
		}
		if (! fwrite($fileRevSQL,$script."\r\n") ) {
			logWriteTo(4,"error","Erreur d'ajout dans le fichier SQL reverse (function OpenFileReverseSQL)","","","0");
		}	
	}	
}

//*********************************************************************
// retourne des m�tadonn�es sur les colonnes d'une table postgresql
function getTableColumnsMetadata ($connection,$table) {
// $connection : la connexion � la base postgres
// $ table : le nom de la table
$sql='SELECT ordinal_position,
         column_name,
         data_type,
         column_default,
         is_nullable,
         character_maximum_length,
         numeric_precision
    FROM information_schema.columns
   WHERE table_name = \''.$table.'\'
ORDER BY ordinal_position;';

$result=pg_query($connection,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
$meta=pg_fetch_all($result);

pg_free_result($result);

return $meta;

}



//*********************************************************************
// retourne la liste des contraintes d'une table postgresql
function getTableConstraints ($connection,$table) {
// $connection : la connexion � la base postgres
// $table : le nom de la table
$sql='SELECT DISTINCT tc.constraint_name, tc.constraint_type
    FROM information_schema.table_constraints tc
   	WHERE tc.table_name = \''.$table.'\'
	ORDER BY tc.constraint_name';

$result=pg_query($connection,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
$constraints=pg_fetch_all($result);
pg_free_result($result);

// maintenant, il faut r�cup�rer les d�tails sur ces contraintes
return $constraints;

}

//*********************************************************************
// retourne des informations d�taill�es sur une contrainte postgresql
function getConstraintDetails ($connection,$table,$constraint) {
// $connection : la connexion � la base postgres
// $table : le nom de la table
// $constraint : le nom de la contrainte

$sql='	SELECT tc.constraint_name, tc.constraint_type, tc.table_name, kcu.column_name, tc.is_deferrable, tc.initially_deferred, rc.match_option AS match_type, rc.update_rule AS on_update, rc.delete_rule AS on_delete, ccu.table_name AS references_table, ccu.column_name AS references_field , cc.check_clause
		FROM information_schema.table_constraints tc 
			LEFT JOIN information_schema.check_constraints cc ON tc.constraint_catalog=cc.constraint_catalog AND tc.constraint_schema=cc.constraint_schema AND tc.constraint_name=cc.constraint_name
			LEFT JOIN information_schema.key_column_usage kcu ON tc.constraint_catalog = kcu.constraint_catalog AND tc.constraint_schema = kcu.constraint_schema AND tc.constraint_name = kcu.constraint_name 
			LEFT JOIN information_schema.referential_constraints rc ON tc.constraint_catalog = rc.constraint_catalog AND tc.constraint_schema = rc.constraint_schema AND tc.constraint_name = rc.constraint_name 
			LEFT JOIN information_schema.constraint_column_usage ccu ON rc.unique_constraint_catalog = ccu.constraint_catalog AND rc.unique_constraint_schema = ccu.constraint_schema AND rc.unique_constraint_name = ccu.constraint_name  
		WHERE tc.table_name = \''.$table.'\' AND tc.constraint_name = \''.$constraint.'\''; 

$result=pg_query($connection,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
$details=pg_fetch_assoc($result);
pg_free_result($result);

return ($details);

}


//*********************************************************************
// retourne des informations d�taill�es sur toutes les contraintes d'une table postgresql
function getTableConstraintDetails ($connection,$table) {

// $connection : la connexion � la base postgres
// $table : le nom de la table

// on liste les contraintes sur la table
$constraints=getTableConstraints ($connection,$table);


// on r�cupere les d�tails sur chaque contrainte
$allConstraintDetails=array();
foreach ($constraints as $constraint) {
	$allConstraintDetails[$constraint["constraint_name"]] = getConstraintDetails($connection,$table,$constraint["constraint_name"]);
	//note : dans le cas d'une contrainte de type CHECK, il faut "extraire" les informations de la check_clause
	$theDetail=$allConstraintDetails[$constraint["constraint_name"]];
	
	if ($theDetail["constraint_type"]=='CHECK') {
		// ***dirty hack***... pour les CHECK simulant un ENUM : on teste si la clause contient un "OR"
		if(strpos($theDetail["check_clause"],'OR')==TRUE) {
			//alors on consid�re que l'on a affaire � une �num�ration
			// et on extrait des chaines de type "(column = value)"
			$clause=$theDetail["check_clause"];
			

			$clause=str_replace(")", "", $clause);
			$clause=str_replace("(", "", $clause);
			$clause=str_replace(" ", "", $clause);
			$theClauses=explode("OR",$clause);
			$theColumn=substringBefore($theClauses[0],'=');
			foreach ($theClauses as $theClause) {
				$theValues[]=substringAfter($theClause,'=');
			}
			$newClause.=arrayToList($theValues,',','');
			;
			
			// on ajoute ces informations dans les d�tails sur la contrainte
			$allConstraintDetails[$constraint["constraint_name"]]["constraint_type"]='ENUM';
			$allConstraintDetails[$constraint["constraint_name"]]["column_name"]=$theColumn;
			$allConstraintDetails[$constraint["constraint_name"]]["check_clause"]=$newClause;
			
		}
	}
}

// on retourne le tableau contenant les informations d�taill�es sur les contraintes
return $allConstraintDetails;

}


//*********************************************************************
// retourne des informations d�taill�es sur toutes les contraintes sur une colonne d'une table postgresql
function getColumnConstraintDetails ($allConstraintDetails,$column) {
// $allConstraintDetails : le r�sultat retourn� par la fonction getTableConstraintDetails() i.e. les d�tails des contraintes d'une table
// $column : le nom de la colonne pour laquelle on veut avoir la liste des contraintes

// on s�lectionne les contraintes relatives � la colonne voulue
$columnConstraints=array();
foreach ($allConstraintDetails as $oneConstraintDetails) {
	if ($oneConstraintDetails["column_name"]==$column) {
		$columnConstraints[$oneConstraintDetails["constraint_name"]]=$oneConstraintDetails;
		}
}

return $columnConstraints;

}

//*********************************************************************
// retourne des informations d�taill�es (metadata et contraintes) sur toutes les colonnes d'une table postgresql
function getTableColumnsDetails($connection,$table) {

// $connection : la connexion � la base postgres
// $table : le nom de la table

// on collecte les metadonnees sur les colonnes
$meta=getTableColumnsMetadata($connection,$table);
// on collecte la liste des contraintes
$details=getTableConstraintDetails ($connection,$table);
// on collecte le detail de chaque contrainte
$columnDetails=getColumnConstraintDetails ($details,$column);


// on construit un tableau contenant les metadata et les contraintes sur chaque colonne de la table
$columnsDetails=array();
foreach($meta as $column) {
	
	$columnsDetails[$column["column_name"]]=$column;
	//on ajoute au tableau les �ventuelles contraintes sur cette colonne
	$columnsDetails[$column["column_name"]]["constraints"]=getColumnConstraintDetails ($details,$column["column_name"]);
	
}

return $columnsDetails;
}

?>
