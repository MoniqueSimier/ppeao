<?php 
//*****************************************
// spec.php
//*****************************************
// Created by Yann Laurent
// 2009-06-18 : creation
//*****************************************
// Ce fichier contient une serie d'actions specifique a réaliser lors de l'export des données vers les bases ACCESS
if ($nomTableACCESS == "CoefficientKb") {
// on doit générer une ligne par système
// On va mettre à jour les variables LocListAttrIn1 et LocListAttrIn2
	if ($SQLAction=="insert") {
		// On n'a que ce cas pour l'instant...
		$SQLSystem = "select id from ref_systeme";
		$ResultSystem = pg_query($connPOST,$SQLSystem);
		$LocerreurSQL = pg_last_error($connPOST);
		if (!$ResultSystem) {
			echo "erreur lecture base post gre pour ref_systeme erreur complete = ".$LocerreurSQL."<br/>";
		} else {
			if (pg_num_rows($ResultSystem) == 0) {
				echo "pas de resultat lecture base post gre pour ref_system <br/>";
			} else {
				// On balaye tous les enregsitrements de ref_system
				while ($systemRow = pg_fetch_row($ResultSystem) ) {
					if ($LocListAttrIn1 == "" ) {
						$TempListAttrIn1 = "IdSysteme";
					} else {
						$TempListAttrIn1 =$LocListAttrIn1.",IdSysteme"; 
					}
					// Liste des valeurs
					if ($LocListAttrIn2 == "" ) {
						$TempListAttrIn2 = $systemRow[0];
					} else {
						$TempListAttrIn2 =$LocListAttrIn2.",".$systemRow[0] ; 
					}
					// Creation du script. autant de ligne par especes que de systeme.
					$LocScriptSQL =$LocScriptSQL." insert into coefficientKb (".$TempListAttrIn1.") values (".$TempListAttrIn2.")#;# ";

				}
			}
		}
	}

}





?>