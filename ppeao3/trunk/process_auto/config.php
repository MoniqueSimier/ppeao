<?php 
//*****************************************
// config.php
//*****************************************
// Created by Yann Laurent
// 2008-07-10 : creation
//*****************************************
// Ce fichier contient une serie de variables de configuration pour l'exécution automatique du portage

//*** Nom du fichier de paramètre     *******************************
//																	*
$PathFicConf = $_SERVER["DOCUMENT_ROOT"]."/conf/processAuto.txt" ;//*	
// ******************************************************************

//*** Config pour sauvegarde BD_PPEAO *******************************
// connexion a BD_PPPEAO											*
$user="devppeao";                   // Le nom d'utilisateur 		*
$passwd="2devppe!!";                // Le mot de passe 				*
$host= "localhost";  				// L'hôte						*
$bdd = "devppeao";  				//								*
// ******************************************************************

//*** Config pour comparaison BD_PPEAO / BD_PECHE *******************
// connexion a BD_PECHE												*
$bd_peche="BD_PECHE_01";			// Nom BD						*
// $bd_peche="bdpeche";			// Nom BD	IRD
// ******************************************************************

?>