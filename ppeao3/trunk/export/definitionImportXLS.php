<?php 
//*****************************************
// definitionImportXLS.php
//*****************************************
// Created by Yann Laurent
// 2011-03-02 : creation
//*****************************************
// Contient des définitions pour les tables a impoorter depuis fichier xls
//*****************************************

unset ($typeChampTA) ;
switch ($nomTableAccess) {
	case "zone_postgre":
		$typeChampTA[0] = "text"; // CodeClair
		$typeChampTA[1] = "num"; // ZoneNew
		$typeChampTA[2] = "num"; //Systeme
		$typeChampTA[3] = "text"; //Zone
		
		break;
	default:
		break;

}


?>
