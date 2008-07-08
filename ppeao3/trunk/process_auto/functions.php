<?
//*****************************************
// functions.php
//*****************************************
// Created by Yann Laurent
// 2008-07-07 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilisées dans le portage automatique des bases de données



//*********************************************************************
// WriteCompLog : écrit dans le fichier de compte rendu de comparaison
function WriteCompLog ($fichierlog,$message) {
// Cette fonction permet d'écrire le compte rendu de comparaison dans le fichier spécifique.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $fichierlog : le fichier log (la variable issue du fopen(flog)
// $message : le texte à écrire dans le fichier log
//*********************************************************************
// En sortie : 
// La fonction écrit le texte dans le fichier préfixé de la date et l'heure, suffixé d'un saut de ligne
//*********************************************************************
	if (! fwrite($fichierlog,date('y\-m\-d\-His')."- ".$message."\r\n") ) {
		logWriteTo(4,"error","Erreur d'ajout dans le fichier de compte rendu (comparaison.php)","","","0");
	}
}

//*********************************************************************
// WriteCompLog : écrit dans le fichier de script le script SQL
function WriteCompSQL ($fichierSQL,$script) {
// Cette fonction permet de générer un fichier de script SQL lors de la comparaison des données.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $fichierSQL : le fichier log (la variable issue du fopen(flog)
// $script : le script à écrire dans le fichier log (attention, doit contenir le ";" en fin de script
//*********************************************************************
// En sortie : 
// La fonction écrit le texte dans le fichier le script, suffixé d'un saut de ligne
//*********************************************************************
	if (! fwrite($fichierSQL,$script."\r\n") ) {
		logWriteTo(4,"error","Erreur d'ajout de script dans le fichier de script (comparaison.php)","","","0");
	}
}

//*********************************************************************
// GetSQL : génère le code SQL pour mettre à jour la table
function GetSQL($SQLAction, $tableName, $whereStatement,$alias ) {
// Cette fonction permet de générer le code SQL en fonction de la table en entrée et du type d'action à mener.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// $SQLAction : quelle est l'action à faire : INSERT ou UPDATE
// $tableName : nom de la table qui subit l'action
// $whereStatement : quelle est la condition where à ajouter à l'action d'update ?
// $alias : nom de l'alias de la table de BD_PPEAO lue pour la comparaison
//*********************************************************************
// En sortie : 
// La fonction renvoie le code SQL prêt à être exécuté.
//*********************************************************************
// Le SQL généré sera de la forme :
// INSERT : insert into $tableName  ($columnname1,$columnname2, .. $columnnamep) values ($alias.$column1,$alias.$column2, .. $alias.$columnp)
// UPDATE : update $tableName set $columnname1 = alias.$columnname1,$columnname2 = $alias.$columnname2, .. $columnnamep = $alias.$columnnamep where $whereStatement 
// $tablename est recu en paramètre.
// $columnname(i) est le nom de la colonne (i) de la table $tablename (récupéré d'une table de paramétrage/fichier de param)
// $alias : nom de l'alias de la table lue dans BD_PPEAO pour faire la comparaison

$LocScriptSQL = "";






return $LocScriptSQL;
}


?>