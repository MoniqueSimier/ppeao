<?
//*****************************************
// functions.php
//*****************************************
// Created by Yann Laurent
// 2008-07-07 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilis�es dans le portage automatique des bases de donn�es



//*********************************************************************
// WriteCompLog : �crit dans le fichier de compte rendu de comparaison
function WriteCompLog ($fichierlog,$message) {
// Cette fonction permet d'�crire le compte rendu de comparaison dans le fichier sp�cifique.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $fichierlog : le fichier log (la variable issue du fopen(flog)
// $message : le texte � �crire dans le fichier log
//*********************************************************************
// En sortie : 
// La fonction �crit le texte dans le fichier pr�fix� de la date et l'heure, suffix� d'un saut de ligne
//*********************************************************************
	if (! fwrite($fichierlog,date('y\-m\-d\-His')."- ".$message."\r\n") ) {
		logWriteTo(4,"error","Erreur d'ajout dans le fichier de compte rendu (comparaison.php)","","","0");
	}
}

//*********************************************************************
// WriteCompLog : �crit dans le fichier de script le script SQL
function WriteCompSQL ($fichierSQL,$script) {
// Cette fonction permet de g�n�rer un fichier de script SQL lors de la comparaison des donn�es.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $fichierSQL : le fichier log (la variable issue du fopen(flog)
// $script : le script � �crire dans le fichier log (attention, doit contenir le ";" en fin de script
//*********************************************************************
// En sortie : 
// La fonction �crit le texte dans le fichier le script, suffix� d'un saut de ligne
//*********************************************************************
	if (! fwrite($fichierSQL,$script."\r\n") ) {
		logWriteTo(4,"error","Erreur d'ajout de script dans le fichier de script (comparaison.php)","","","0");
	}
}

//*********************************************************************
// GetSQL : g�n�re le code SQL pour mettre � jour la table
function GetSQL($SQLAction, $tableName, $whereStatement,$alias ) {
// Cette fonction permet de g�n�rer le code SQL en fonction de la table en entr�e et du type d'action � mener.
//*********************************************************************
// En entr�e, les param�tres suivants sont :
// $SQLAction : quelle est l'action � faire : INSERT ou UPDATE
// $tableName : nom de la table qui subit l'action
// $whereStatement : quelle est la condition where � ajouter � l'action d'update ?
// $alias : nom de l'alias de la table de BD_PPEAO lue pour la comparaison
//*********************************************************************
// En sortie : 
// La fonction renvoie le code SQL pr�t � �tre ex�cut�.
//*********************************************************************
// Le SQL g�n�r� sera de la forme :
// INSERT : insert into $tableName  ($columnname1,$columnname2, .. $columnnamep) values ($alias.$column1,$alias.$column2, .. $alias.$columnp)
// UPDATE : update $tableName set $columnname1 = alias.$columnname1,$columnname2 = $alias.$columnname2, .. $columnnamep = $alias.$columnnamep where $whereStatement 
// $tablename est recu en param�tre.
// $columnname(i) est le nom de la colonne (i) de la table $tablename (r�cup�r� d'une table de param�trage/fichier de param)
// $alias : nom de l'alias de la table lue dans BD_PPEAO pour faire la comparaison

$LocScriptSQL = "";






return $LocScriptSQL;
}


?>