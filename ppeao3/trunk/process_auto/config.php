<?php
//*****************************************
// config.php
//*****************************************
// Created by Yann Laurent
// 2008-07-10 : creation
//*****************************************
// Ce fichier contient une serie de variables de configuration pour l'exécution automatique du portage



//*** Config pour sauvegarde BD_PPEAO ***************************
// connexion a BD_PPPEAO										*
$user="devppeao";                   // Le nom d'utilisateur 	*
$passwd="2devppe!!";                // Le mot de passe 			*
$host= "localhost";  				// L'hôte					*
$bdd = "devppeao";  				//							*
$pathBin = "C:/\"Program Files\"/PostgreSQL/8.3/bin/";// windows*
//$pathBin = "";										// linux*
$pathBackup =  $_SERVER["DOCUMENT_ROOT"]."/save_base";		//	*
$backupName = "sauvegarde.sql";		//							*
// **************************************************************

//*** Config pour comparaison BD_PPEAO / BD_PECHE ***************
// connexion a BD_PECHE											*
$bd_peche="bdpeche";				// Nom BD					*
$dirLog = $_SERVER["DOCUMENT_ROOT"]."/log";		//				*
$fileLogComp = "ResultatsComparaison.txt";		//				*
// **************************************************************

?>