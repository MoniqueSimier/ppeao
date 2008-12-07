<?php 
//*****************************************
// SQLDonneesPeche.php
//*****************************************
// Created by Yann Laurent
// 2008-10-30 : creation
//*****************************************
// Lancement des scripts SQL cr��s et sauvegard�s dans le traitement pr�c�dement
// voir majDonneesPeches.php


//*****************************************
// Note : certains message a l'ecran sont laisses, le traitement etant long, ca permet d'avoir une 
//idee du traitement qui est en cours.

$cpt=0;
$tempType = "";
$nomAction = "Exec. SQL pour ".$nomAction;
$pasDeSQL = true;
//****************************************************
// Traitement
// Etape 1 : g�n�rer les correspondances d'ID pour toutes les tables � importer
// On ne se pose pas la question de savoir si la table existe ou non.. On le testera apres
//****************************************************


echo "<b>Etape 3</b> : execution des SQL <br/>";
$CRexecution .="<b>Execution des SQL</b> <br/>";
// On r�cup�re toutes les tables � mettre � jour
if (!$finmajDP) { // on ne lance pas le timer si on sort directement de majDonneesPeches.sql
	$start_while=timer(); // d�but du chronom�trage du for
}

// Liste des tables a traiter
switch($typeAction){
	case "majsc":
		$listTableMajID = GetParam("listeTableMajsc",$PathFicConf);
		break;
	case "majrec" :
		// on rajoute la table art_periode_enquete qui est mise a jour uniquement lors du portage
		$listTableMajID = "art_periode_enquete,".GetParam("listeTableMajrec",$PathFicConf);
		break;
}
// pour TEST
//$listTableMajID ="exp_campagne";
//$listTableMajID ="exp_campagne,exp_environnement,exp_coup_peche,exp_fraction"; // test
//$listTableMajID ="exp_environnement,exp_coup_peche,exp_fraction,exp_biologie,exp_trophique";
//$listTableMajID ="art_unite_peche,art_lieu_de_peche,art_debarquement,art_fraction";
//$listTableMajID ="art_unite_peche";
$NbrTableAlire = substr_count($listTableMajID,",");
if ($NbrTableAlire == 0) {
	$NbrTableAlire = 1;
} else {
	$NbrTableAlire += 1;
}
//echo "liste table SQL a exec =".$listTableMajID."<br/>";

$tableMajID = explode(",",$listTableMajID);
$nbtableMajID = count($tableMajID) - 1;

for ($cptID = 0; $cptID <= $nbtableMajID; $cptID++) {
	// controle de la table en cours si besoin (gestion TIMEOUT)
	if ((!$tableEnCours == "" && $tableEnCours == $tableMajID[$cptID]) || $tableEnCours == "") {
		if ($debugAff==true) {
			$debugTimer = number_format(timer()-$start_while,4);
			echo "Exec SQL lecture table :".$debugTimer."<br/>";
		}
		echo $cptID." / ".$nbtableMajID." Execution SQL table en cours = ".$tableMajID[$cptID]." <br/>";
		if ($EcrireLogComp ) { WriteCompLog ($logComp,"Exec SQL pour ".$tableMajID[$cptID],$pasdefichier);}

		//Pour gestion timeout, la valeur de la table en cours
		$tableEnLecture = $tableMajID[$cptID];
		// Compteur 
		$compReadSqlC = " select count(nomtable) from temp_recomp_id where nomtable='".$tableMajID[$cptID]."'";
		$compReadResultC = pg_query(${$BDSource},$compReadSqlC) or die('erreur dans la requete : '.pg_last_error());
		$compRowC = pg_fetch_row($compReadResultC);
		$totalLignes = $compRowC[0];
		pg_free_result($compReadResultC);
		// Reinitialisation des compteurs
		$cptChampTotal = 0;
		$cptSQLErreur = 0 ;
		$cptAjoutMaj = 0;
		if ($tableEnCours == "") {
			$ErreurProcess=false;
			$cptTableTotal++;
			$_SESSION['s_cpt_champ_total'] 	= 0;
			$_SESSION['s_en_erreur'] 		= false;
			$_SESSION['s_cpt_erreurs_sql'] 	= 0;
			$_SESSION['s_cpt_maj'] 	= 0; 
		} else {
			// on reinitialise les valeurs avec les variables de session mise � jour lors du traitement pr�c�dent
			$CRexecution 	= $_SESSION['s_CR_processAuto'];
			$cptChampTotal 	= $_SESSION['s_cpt_champ_total'];
			$ErreurProcess 	= $_SESSION['s_erreur_process'];
			$cptAjoutMaj = $_SESSION['s_cpt_maj'];
			// On reinitialise pour eviter de compter deux fois les memes donnees
			$_SESSION['s_CR_processAuto'] 	= "";
			$_SESSION['s_cpt_champ_total'] 	= 0;
			$_SESSION['s_cpt_erreurs_sql'] 	= 0; 
			$_SESSION['s_cpt_maj'] 	= 0; 
		}

		// Gestion des problemes d'ID non numerique
		$ListeTableIdpasRang0 = "art_type_activite";
		$ListeTablepasRang0ID = "3";
		$testTtypeID = strpos($ListeTableIdpasRang0 ,$tableMajID[$cptID]);
		if ($testTtypeID === false) {
			$RangId = 0; 
		} else {
			$RangId = 2; /// pour l'instant qu'une table, on code un peu a la hussarde...
		}

		// Gestion TIMEOUT : on reprend la ou on s'etait arrete
		// Comme on trie par ID, on ne va pas en perdre en route
		$testTtypeIDChar = strpos($ListeTableIDPasNum ,$tableMajID[$cptID]);
		if ($tableEnCours == "") {
			$condWhere = " where nomtable='".$tableMajID[$cptID]."'";
		} else {
			if ($testTtypeIDChar === false) {
				// L'ID est bien un num�rique
				$condWhere = " where nomtable='".$tableMajID[$cptID]."' and Idsource > ".$IDEnCours;
			} else {
				// L'ID est une chaine
				$condWhere = " where nomtable='".$tableMajID[$cptID]."' and IdsourceChar > ".$IDEnCours;
			}	
		}
		// La condition de tri doit aussi �tre �valu�e.
		if ($testTtypeIDChar === false) {
			// L'ID est bien un num�rique
			$condOrder = " order by Idsource ASC ";
		} else {
			// L'ID est une chaine
			$condOrder = " order by IdsourceChar ASC ";
		}

		// Lecture de la table qui contient les SQL / ex�cuti
		$envSrcSql = " select * from temp_recomp_id ".$condWhere. " ".$condOrder; 
		//echo $envSrcSql."<br/>";
		$envSrcResult = pg_query(${$BDSource},$envSrcSql) or die('erreur dans la requete : '.pg_last_error());
		if (pg_num_rows($envSrcResult) == 0) {
		// Message d'erreur
		} else {
			//echo "nb enreg ".$tableMajID[$cptID]." = ".pg_num_rows($envSrcResult)." where = ".$condWhere."<br/>";
			while ($envRow = pg_fetch_row($envSrcResult) ) {
				if ($debugAff==true) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "xxx Exec SQL debut trt enreg :".$debugTimer."<br/>";
				}
				// Gestion TIMEOUT
				$ourtime = (int)number_format(timer()-$start_while,7);
				$seuiltemps= ceil(0.9*$max_time);
				// On prend un peu de marge par rapport au temps max.
				if ($ourtime >= ceil(0.9*$max_time)) {
					$delai=number_format(timer() - $start_while,7);
					$ArretTimeOut =true;
					echo "timeout table en lecture = ".$tableEnLecture." id en lecture = ".$IDEnLecture."<br/>";
					break;
				}
				if ($testTtypeIDChar === false) {
					// L'ID est bien un num�rique
					$IDEnLecture = $envRow[1];
				} else {
					// L'ID est une chaine
					$IDEnLecture = $envRow[2];
				}	
						
				// Compteur 
				$cptChampTotal++;
	
				if ($debugAff==true) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "Exec SQL avant execution SQL :".$debugTimer."<br/>";
				}
				// On r�cup�re le SQL
				//echo $envRow[7]."<br/>";
				$insertSQL = stripslashes($envRow[7]);
				// Execution du SQL
				$insertSQLResult = pg_query(${$BDCible},$insertSQL) ;
				$pasDeSQL = false;
				$errorSQL = pg_last_error(${$BDCible});
				if ($insertSQLResult) {
					$cptAjoutMaj ++;
					
				} else {
					if ($EcrireLogComp ) { 
					WriteCompLog ($logComp,"Erreur execution pour table = ".$envRow[0]."  script = ".$envRow[7]. " erreur complete = ".$errorSQL,$pasdefichier);}
	
					$cptSQLErreur ++;
					$ErreurProcess = true;
				}
				pg_free_result($insertSQLResult);
				if ($debugAff==true) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "xxx Exec SQL fin trt enreg :".$debugTimer."<br/>";
				}  
			}// End while ($envRow = pg_fetch_row($envSrcResult) )
			// On effectue ici tous les traitements li�s � la fin de la lecture d'une table
			if ($ArretTimeOut) {
				break;
			}
			// TIMEOUT, reinitialisation des variables EnCours
			$IDEnCours = 0;
			$tableEnCours = "";
			
			// On g�re le compte-rendu
			if (!$ArretTimeOut) {
				$CRexecution = $CRexecution." *-".$tableMajID[$cptID]." : ".$cptChampTotal." lus";
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp,"FIN ".$nomAction." pour ".$tableMajID[$cptID]." : ajout reel total=".$cptAjoutMaj,$pasdefichier);
				}
			
				$CRexecution = $CRexecution." (maj/ajout reel total=".$cptAjoutMaj.") -* <br/>" ;
			}
				
			pg_free_result($envSrcResult);
		}
	} //end ((!$tableEnCours == "" && $tableEnCours == $$tableMajID[$cptID]) ...
	
	
} // end for ($cptID = 0; $cptID <= $nbtableMajID; $cptID++)

if ($pasDeSQL) {
	$CRexecution = $CRexecution." <img src=\"/assets/warning.gif\" alt=\"Avertissement\"/> Pas de scripts SQL &agrave; ex&eacute;cuter. <br/>";
}
?>