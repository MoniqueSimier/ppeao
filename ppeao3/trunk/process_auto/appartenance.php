<?php 
//*****************************************
// appartenance.php
//*****************************************
// Created by Yann Laurent
// 2008-10-31 : creation
//*****************************************
// Cet include permet de recuperer l'appartenance a une campagne ou une peche artisanale 

// Issu du programme appelant:
// $nomTable = nom de la table en cours de lecture
// $idNomTable = num de l'id en cours



// Variable de test 
$pasdetraitement = true;

if (strpos ($nomTable,"exp_") > 0 ) {
	$typeDonnees = "exp";
} else {
	$typeDonnees = "art";
}
$enregAmaj = false;

$pasDeRequete = false;

switch ($nomTable) {
case "exp_campagne" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='exp' and id =".$idNomTable ;
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	
	break;
	
case "exp_environnement" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='exp' and id in (select exp_campagne_id from exp_coup_peche where exp_environnement_id=".$idNomTable.")" ;
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;
	
case "exp_coup_peche" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='exp' and id = (select exp_campagne_id from exp_coup_peche where id=".$idNomTable.")";
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;
case "exp_fraction" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='exp' and id = (select exp_campagne_id from exp_coup_peche where id=(select exp_coup_peche_id from exp_fraction where id=".$idNomTable."))";
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;
	
case "exp_biologie" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='exp' and id = (select exp_campagne_id from exp_coup_peche where id=(select exp_coup_peche_id from exp_fraction where id=(select exp_fraction_id from exp_biologie where id=".$idNomTable.")))";
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;
	
case "exp_trophique" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='exp' and id = (select exp_campagne_id from exp_coup_peche where id=(select exp_coup_peche_id from exp_fraction where id=(select exp_fraction_id from exp_biologie where id=(select exp_biologie_id from exp_trophique where id=".$idNomTable."))))";
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;

case "art_unite_peche" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;
	
	
case "art_lieu_de_peche" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;

case "art_debarquement" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='art' and id =".$idNomTable ;
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;

case "art_debarquement_rec" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='art' and id = (select art_debarquement_id from art_debarquement_rec where id=".$idNomTable.")";
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;


case "art_fraction_rec" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;
	
case "art_stat_totale" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;
	
case "art_stat_gt" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;

case "art_stat_gt_sp" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;

case "art_stat_sp" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;

case "art_taille_gt_sp" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;

case "art_taille_sp" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;


case "art_engin_peche" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='art' and id = (select art_debarquement_id from art_engin_peche where id=".$idNomTable.")";
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;

case "art_fraction" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='art' and id = (select art_debarquement_id from art_fraction where id=".$idNomTable.")";
	//echo $scriptSQL."<br/>";
	$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
	break;

case "art_poisson_mesure" :
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete 1 ".$nomTable." :".$debugTimer."<br/>";
	}
	$scriptSQLini = "select art_fraction_id from art_poisson_mesure where id = ".$idNomTable;
	
	$scriptSQLResultini = pg_query(${$BDSource},$scriptSQLini) or die('erreur dans la requete : '.pg_last_error());
	if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance apres requete 1 ".$nomTable." :".$debugTimer."<br/>";
	}
	if (pg_num_rows($scriptSQLResultini) == 0) {
		// Message d'erreur
		echo "erreur execution query appartenance etape 1 pour ".$nomTable.".<br/>";
	} else {
		if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "Appartenance avant requete 2 ".$nomTable." :".$debugTimer."<br/>";
	}
		$scriptSQLIniRow = pg_fetch_row($scriptSQLResultini);	
		$scriptSQL = "select exist,supp,newid,id from temp_exist_peche where type ='art' and id = (select art_debarquement_id from art_fraction where id='".$scriptSQLIniRow[0]."')";
		$scriptSQLResult = pg_query(${$BDSource},$scriptSQL) or die('erreur dans la requete : '.pg_last_error());
		if ($debugAff==true) {
			$debugTimer = number_format(timer()-$start_while,4);
			echo "Appartenance apres requete 2 ".$nomTable." :".$debugTimer."<br/>";
		}
	}
	break;

case "art_activite" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;

case "art_engin_activite" :
	// on recalcule systematiquement l'ID
	$pasDeRequete = true;
	$tempetatAction = "a";
	break;
}



if (!$pasDeRequete) {
	if (pg_num_rows($scriptSQLResult) == 0) {
	// Message d'erreur
		echo "erreur execution query appartenance ".$nomTable.".<br/>";
		if ($EcrireLogComp ) { WriteCompLog ($logComp,"erreur recup ".$typeDonnees." pour ".$nomTable,$pasdefichier);}
	} else {
		if ($debugAff==true) {
		$debugTimer = number_format(timer()-$start_while,4);
		echo "traitement apres requete ".$nomTable." :".$debugTimer."<br/>";
	}
		$scriptSQLRow = pg_fetch_row($scriptSQLResult);	
		$testTtypeID = strpos($ListeTableIDPasNum ,$nomTable);

		$tempExist = $scriptSQLRow[0] ;
		$tempNewID = $scriptSQLRow[2];
		$tempID = $scriptSQLRow[3];
		// L'enreg existe et on met a jour
		if ($scriptSQLRow[1] == "y" && $scriptSQLRow[0]=="y" ) {
			$tempetatAction = "m" ;
		} 
		// L'enreg n'existe pas
		if ($scriptSQLRow[0] == "n") {
			$tempetatAction = "a";
		}
		// L'enreg existe mais pas de maj
		if ($scriptSQLRow[1] == "n" && $scriptSQLRow[0]=="y" ) {
			$tempetatAction = "r" ;
		} 		
	}
}


?>
