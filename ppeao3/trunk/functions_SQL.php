<?php 
//*****************************************
// functions_SQL.php
//*****************************************
// Created by Yann Laurent
// 2008-07-11 : creation
//*****************************************
// Ce fichier contient une serie de fonctions php utilisées pour la génération de scripts SQL ou de leur manipulation



//*********************************************************************
// OpenFileReverseSQL : écrit dans le fichier de compte rendu de comparaison
function OpenFileReverseSQL ($howOpen,$direcLog,$PasAutorisation) {
// Cette fonction permet d'ouvrir le fichier contenant les scripts SQL pour réaliser les actions inverses.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// howOpen : comment ouvrir le fichier : en ajout (='ajout') ou en ecrasement (='ecras') ?
// direcLog : le répertoire ou se trouve le fichier log
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// - Renvoie la ressource  fichier ouvert
//*********************************************************************
	if (! $PasAutorisation) {
		if (! file_exists($direcLog)) {
			if (! mkdir($direcLog) ) {
				$messageGen = " erreur de création du répertoire de log";
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
// OpenFileReverseSQL : écrit dans le fichier de compte rendu de comparaison
function CloseFileReverseSQL ($fileRevSQL,$PasAutorisation) {
// Cette fonction permet d'ouvrir le fichier contenant les scripts SQL pour réaliser les actions inverses.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// fileRevSQL : fichier SQL
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// -
//*********************************************************************
	if (! $PasAutorisation) {
		fclose($fileRevSQL,$PasAutorisation);
	}
}

//*********************************************************************
// OpenFileReverseSQL : écrit dans le fichier de compte rendu de comparaison
function WriteFileReverseSQL ($fileRevSQL,$script,$PasAutorisation) {
// Cette fonction permet d'ouvrir le fichier contenant les scripts SQL pour réaliser les actions inverses.
//*********************************************************************
// En entrée, les paramètres suivants sont :
// fileRevSQL : fichier SQL
// script : le script SQL à écrire
// PasAutorisation : variable pour test linux a priori toujours vrai
//*********************************************************************
// En sortie : 
// -
//*********************************************************************
// On teste si un ; est présent à la fin du script SQL, sinon le rajouter
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

?>
