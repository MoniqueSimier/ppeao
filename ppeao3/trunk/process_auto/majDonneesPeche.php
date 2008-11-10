<?php 
//*****************************************
// controleComp.php
//*****************************************
// Created by Yann Laurent
// 2008-10-30 : creation
//*****************************************
// Gestion spécifique des mises à jour des données peches.
// Contrairement aux paramétrages ou les données sont comparées et mises à jour base à base (meme ID mis à jour),
// les données de peche sont créées sur des bases déportées ou les ID ne sont pas les memes.
// Il faut donc recalculer les ID pour les données a importer dans la base de reference (PPEAO, qui joue
// le role d'un concentrateur)
// 
// Le principe est relativement simple.
// Les 2 tables de references contenant les entites peches exp et peche art sont comparées : exp_campagne, art_debarquement. Si les campagnes sont nouvelles ou non, on peut choisir de supprimer ce qui est existe deja
// Pour cela, une table temporaire est créée = temp_exist_peche 
// Pour chaque entité (campagne pour pech exp, et agglo/annee/mois pour peche art, on stocke l'infor de si elle existe, si elle doit etre supprimee, et enfin l'ancien et le nouvel ID
// Pour la mise à jour a proprement dit, une seconde table est créée : temp_recomp_id
// Cette table resulte du traitement ligne a ligne de toutes les tables, elle contient le script de mise à jour.
// Tous les champs ID reliant cette table à d'autres sont réévalués pour contenir la nouvelle valeur d'ID.
// Attention, l'ordre de traitement est extremement important pour que l'évaluation des ID soit complete
//*****************************************
// Note : certains message a l'ecran sont laisses, le traitement etant long, ca permet d'avoir une idee du traitement qui
// est en cours.

$cpt=0;
$tempType = "";
$tempCle1 = 0;
$tempCle2 = 0;
$tempCle3 = 0;
$tempExist = "";
$tempSupp = "";
$tempID = 0;
$tempNewID = 0;
$condWhere = "";
$cptAllID=0;
$cptTableEq = 0;
$cptTableTotal = 0;
$start_while=timer(); // début du chronométrage du for
//****************************************************
// Pre traitement
// Creation des tables dans la BD pour
// maj des ID
//
//*****************************************
if ($tableEnCours == "") {
	echo "<b>Etape 1</b> : creation des tables temporaires <br/>";
	$CRexecution = "** Creation des tables temporaires <br/>";
	// on ne le fait qu'une fois...
	set_time_limit(90);
	// Faire un vaccuum avant !
	
	// Creation d'une table temporaire sur la BD pour accelerer les acces vu la quantite de données potentielles a renomer
	$lev=error_reporting (8); //Pour eviter les avertissements si la base n'existe pas.
	$createTableSql = "
		DROP TABLE temp_recomp_id;
		CREATE TABLE temp_recomp_id (
		nomTable character varying(30) NOT NULL,  
		Idsource integer, 
		IdsourceChar character varying(25),   
		IdCible integer ,
		IdCibleChar character varying(25) ,
		exist character varying(1),
		etatAction character varying(1),
		scriptSQL character varying(1000),
		controlenum1 integer ,
		controlenum2 integer ,
		controletxt1 character varying(20)
		);
		ALTER TABLE public.temp_recomp_id OWNER TO devppeao;
		COMMENT ON TABLE temp_recomp_id IS 'Table temporaire pour gerer la mise a jour des id';
		COMMENT ON COLUMN temp_recomp_id.nomTable IS 'Nom de la table';
		COMMENT ON COLUMN temp_recomp_id.Idsource IS 'Id issue de la base source';
		COMMENT ON COLUMN temp_recomp_id.IdCible IS 'Id pour la creation dans la base cible';
		COMMENT ON COLUMN temp_recomp_id.IdsourceChar IS 'Id valeur char issue de la base source';
		COMMENT ON COLUMN temp_recomp_id.IdCibleChar IS 'Id valeur char pour la creation dans la base cible';
		COMMENT ON COLUMN temp_recomp_id.exist IS 'existe deja dans la base ?';
		COMMENT ON COLUMN temp_recomp_id.etatAction IS 's=supprimer m=mettre a jour r=rien';
		COMMENT ON COLUMN temp_recomp_id.scriptSQL IS 'sql a executer';
		COMMENT ON COLUMN temp_recomp_id.controlenum1 IS 'valeur numerique 1 pour tester validite recalcul';
		COMMENT ON COLUMN temp_recomp_id.controlenum2 IS 'valeur numerique 2 pour tester validite recalcul';
		COMMENT ON COLUMN temp_recomp_id.controletxt1 IS 'valeur texte 1 pour tester validite recalcul';
		CREATE INDEX \"table_ID\" ON temp_recomp_id USING btree (nomtable, idsource);
		CREATE INDEX \"table_IDCHAR\" ON temp_recomp_id USING btree (nomtable, idsourcechar);
	";
	$createTableResult = pg_query(${$BDSource},$createTableSql);
	if ($createTableResult == false ) {
		echo "creation temp_recomp_id complete <br/>";
		$createTableSql = "
		CREATE TABLE temp_recomp_id (
		nomTable character varying(30) NOT NULL,  
		Idsource integer,
		IdsourceChar character varying(25),      
		IdCible integer ,
		IdCibleChar character varying(25) ,
		exist character varying(1),
		etatAction character varying(1),
		scriptSQL character varying(1000),
		controlenum1 integer ,
		controlenum2 integer ,
		controletxt1 character varying(20));
		ALTER TABLE public.temp_recomp_id OWNER TO devppeao;
		COMMENT ON TABLE temp_recomp_id IS 'Table temporaire pour gerer la mise a jour des id';
		COMMENT ON COLUMN temp_recomp_id.nomTable IS 'Nom de la table';
		COMMENT ON COLUMN temp_recomp_id.Idsource IS 'Id issue de la base source';
		COMMENT ON COLUMN temp_recomp_id.IdCible IS 'Id pour la creation dans la base cible';
		COMMENT ON COLUMN temp_recomp_id.IdsourceChar IS 'Id valeur char issue de la base source';
		COMMENT ON COLUMN temp_recomp_id.IdCibleChar IS 'Id valeur char pour la creation dans la base cible';
		COMMENT ON COLUMN temp_recomp_id.exist IS 'existe deja dans la base ?';
		COMMENT ON COLUMN temp_recomp_id.etatAction IS 's=supprimer a=ajouter m=mettre a jour r=rien';
		COMMENT ON COLUMN temp_recomp_id.scriptSQL IS 'sql a executer';
		COMMENT ON COLUMN temp_recomp_id.controlenum1 IS 'valeur numerique 1 pour tester validite recalcul';
		COMMENT ON COLUMN temp_recomp_id.controlenum2 IS 'valeur numerique 2 pour tester validite recalcul';
		COMMENT ON COLUMN temp_recomp_id.controletxt1 IS 'valeur texte 1 pour tester validite recalcul';
		CREATE INDEX \"table_ID\" ON temp_recomp_id USING btree (nomtable, idsource);
		CREATE INDEX \"table_IDCHAR\" ON temp_recomp_id USING btree (nomtable, idsourcechar);
		";
		$createTableResult = pg_query(${$BDSource},$createTableSql) or die('erreur dans la requete : '.pg_last_error());
		if (!$createTableResult ) {
			echo "Erreur creation tem_recomp_id <br/>";
		}
	} else {
		echo "creation temp_recomp_id apres DROP <br/>";
	}
	if ($EcrireLogComp ) { WriteCompLog ($logComp,"Table temporaire temp_recomp_id creee",$pasdefichier);}
	pg_free_result($createTableResult);
	
	
	// tant qu'on y ait on cree aussi une table pour identifier les campagnes / peches artisanales deja presentes dans la table.
	
	// Creer un index type / id
	$createTableSql = "
	DROP TABLE temp_exist_peche;
	CREATE TABLE temp_exist_peche (
		type character varying(30) NOT NULL,  
		cle1 integer,    
		cle2 integer,
		cle3 integer,
		exist character varying(1),
		supp character varying(1),
		id integer,
		newid integer
	 );
	ALTER TABLE public.temp_exist_peche OWNER TO devppeao;
	COMMENT ON TABLE temp_exist_peche IS 'Table temporaire pour identifier les peches exp et art deja existantes';
	COMMENT ON COLUMN temp_exist_peche.type IS 'type de peche (exp ou art)';
	COMMENT ON COLUMN temp_exist_peche.cle1 IS 'numero campagne  si peche exp OU agglo ID si peche art'; 
	COMMENT ON COLUMN temp_exist_peche.cle2 IS 'syteme ID si peche exp OU annee si peche art';
	COMMENT ON COLUMN temp_exist_peche.cle3 IS 'mois  si peche art';
	COMMENT ON COLUMN temp_exist_peche.exist IS 'y ou n est-ce qu''elle existe deja';
	COMMENT ON COLUMN temp_exist_peche.supp IS 'a supprimer ?';
	COMMENT ON COLUMN temp_exist_peche.id IS 'id de exp_campagne ou art_debarquement (en cours )';
	COMMENT ON COLUMN temp_exist_peche.newid IS 'nouvel id de exp_campagne ou art_debarquement';
	 ";
	$createTableResult = pg_query(${$BDSource},$createTableSql);
	if ($createTableResult == false ) {
		$createTableSql = "
		CREATE TABLE temp_exist_peche (
			type character varying(30) NOT NULL,  
			cle1 integer,    
			cle2 integer,
			cle3 integer,
			exist character varying(1),
			supp character varying(1),
			id integer,
			newid integer
		 );
		ALTER TABLE public.temp_exist_peche OWNER TO devppeao;
		COMMENT ON TABLE temp_exist_peche IS 'Table temporaire pour identifier les peches exp et art deja existantes';
		COMMENT ON COLUMN temp_exist_peche.type IS 'type de peche (exp ou art)';
		COMMENT ON COLUMN temp_exist_peche.cle1 IS 'numero campagne  si peche exp OU agglo ID si peche art'; 
		COMMENT ON COLUMN temp_exist_peche.cle2 IS 'syteme ID si peche exp OU  annee si peche art';
		COMMENT ON COLUMN temp_exist_peche.cle3 IS 'mois  si peche art';
		COMMENT ON COLUMN temp_exist_peche.exist IS 'y ou n est-ce qu''elle existe deja';
		COMMENT ON COLUMN temp_exist_peche.supp IS 'mois  si peche art';
		COMMENT ON COLUMN temp_exist_peche.id IS 'id de exp_campagne ou art_debarquement (en cours )';
		COMMENT ON COLUMN temp_exist_peche.newid IS 'nouvel id de exp_campagne ou art_debarquement';
		 ";
		$createTableResult = pg_query(${$BDSource},$createTableSql) or die('erreur dans la requete : '.pg_last_error());
	}
	if ($EcrireLogComp ) { WriteCompLog ($logComp,"Table temporaire temp_exist_peche creee",$pasdefichier);}
	pg_free_result($createTableResult);
	
	error_reporting ($lev); // retour au avertissements par defaut
	
	//****************************************************
	// Traitement
	// Etape 1 : controler les peches experimentales (campagne) / peches artisanales presentes (agglo / annee / mois)
	//****************************************************
	switch($typeAction){
		case "majsc":
			$tablesACont = "exp_campagne";
			$tableEnLecture = $tablesACont;
			break;
		case "majrec" :
			$tablesACont = "art_debarquement";
			$tableEnLecture = $tablesACont;
			break;
	}
	
	
	$valDernierID =0;
	
	echo "traitement de la table ".$tablesACont."<br/>";
	switch($typeAction){
		case "majsc":
			$condSelect = " numero_campagne,ref_systeme_id,date_debut,id"; // date_debut n'est la que pour avoir id en 4 ieme position... Je sais, c'est pas beau...
			$condOrder =" numero_campagne ASC,ref_systeme_id ASC";
			$tempType = "exp";
			break;
		case "majrec" :
			$condSelect = " art_agglomeration_id,annee,mois,id"; // 
			$condOrder =" art_agglomeration_id ASC,annee ASC,mois ASC";
			$tempType = "art";
			break;
	}
	$maxReadSql = " select max(id) from ".$tablesACont;
	$maxReadResult = pg_query(${$BDCible},$maxReadSql) or die('erreur dans la requete : '.pg_last_error());
	if (pg_num_rows($maxReadResult) == 0) {
		echo "erreur : table ".$tablesACont." vide <br/>";
		// message d'erreur
	} else {
		$maxRow = pg_fetch_row($maxReadResult);
		if ( $maxRow[0] == null) {
			$valDernierID  = 1;
		} else {
			$valDernierID  = $maxRow[0];
		}
		
	}

	pg_free_result($maxReadResult);
	
	$compReadSql = " select ".$condSelect." from ".$tablesACont." order by ".$condOrder;
	$compReadResult = pg_query(${$BDSource},$compReadSql) or die('erreur dans la requete : '.pg_last_error());
	if (pg_num_rows($compReadResult) == 0) {
		echo "table ".$tablesACont." dans ".$nomBDSource." vide <br/>";
		// message d'erreur
	} else {

		while ($compRow = pg_fetch_row($compReadResult) ) {
			// On va remplir la table temp_exist_peche pour chaque entite en indiquant si elle existe ou non
			// si non, lui affecter un nouvel ID
			switch($typeAction){
				case "majsc":
					$condwhere = "numero_campagne =".$compRow[0]." and ref_systeme_id=".$compRow[1];
					$tempCle3 = 0;
					break;
				case "majrec" :
					$condwhere = "art_agglomeration_id =".$compRow[0]." and annee=".$compRow[1]." and mois=".$compRow[2];
					$tempCle3 = $compRow[2];
					break;
			}
			$tempCle1 = $compRow[0];
			$tempCle2 = $compRow[1];
			
			$compCibleReadSql = " select id from ".$tablesACont." where ".$condwhere; 
			$compCibleReadResult = pg_query(${$BDCible},$compCibleReadSql) or die('erreur dans la requete : '.pg_last_error());
			if (pg_num_rows($compCibleReadResult) == 0) {
			// C'est une nouvelle peche
			// La cible est la base BDPPEAO la source est la base BDPECHE
				$compCibleRow = pg_fetch_row($compCibleReadResult); // une seule ligne en retour, pas besoin de faire une boucle
				$tempExist = "n";
				$tempNewID = $valDernierID;
				$valDernierID ++;
				$tempSupp = "n";
				$tempID = $compRow[3];
				
			} else {
				// La peche existe déjà. On doit demander si on la supprime ou non
				//echo "Peche ".$tempType." cle1=".$tempCle1." , cle2 =".$tempCle2.", cle3 =".$tempCle3." existe deja. Action a mener <br/>";
				$cptTableEq ++;
				$tempExist = "y";
				$tempID = $compRow[3];
				$tempNewID = $compRow[3];
				$tempSupp = "n";
			}
			$cptTableTotal ++;
			// La creation du script SQL avec toutes les info recuperees.
			$insertSQL = "insert into temp_exist_peche values ('".$tempType."', ".$tempCle1.", ".$tempCle2.", ".$tempCle3.", '".$tempExist."', '".$tempSupp."', ".$tempID.",".$tempNewID.")";
			$insertSQLResult = pg_query(${$BDSource},$insertSQL) or die('erreur dans la requete : '.pg_last_error());
			pg_free_result($insertSQLResult);
	
			
		} // end while ($compRow = pg_fetch_row($compReadResult) ) {
	} //end else if (pg_num_rows($compReadResult)
} // end if ($tableEnCours == "")

//****************************************************
// Traitement
// Etape 2 : générer les correspondances d'ID pour toutes les tables à importer
// On ne se pose pas la question de savoir si la table existe ou non.. On le testera apres
//****************************************************
if (!$cptTableEq ==0) {
	$CRexecution = "** Nombre de peche ".$tempType." deja existantes = ".$cptTableEq." / ".$cptTableTotal." lues <br/>";
} else {
	$CRexecution = "** Nombre de peche ".$tempType." traitees = ".$cptTableTotal."<br/>";
}
$cptTableEq = 0;
$cptTableTotal = 0;
echo "<b>Etape 2</b> : creation des SQL <br/>";

// On récupère toutes les tables à mettre à jour

set_time_limit(ini_get('max_execution_time')); // on remet le timer normal

// Liste des tables a traiter
switch($typeAction){
	case "majsc":
		$listTableMajID = GetParam("listeTableMajsc",$PathFicConf);
		break;
	case "majrec" :
		$listTableMajID = GetParam("listeTableMajrec",$PathFicConf);
		break;
}
// pour TEST
//$listTableMajID ="exp_campagne";
//$listTableMajID ="exp_campagne,exp_environnement,exp_coup_peche,exp_fraction"; // test
//$listTableMajID ="exp_environnement,exp_coup_peche,exp_fraction,exp_biologie,exp_trophique";
//$listTableMajID ="art_unite_peche,art_lieu_de_peche,art_debarquement,art_debarquement_rec,art_fraction_rec";
//echo "liste table = ".$listTableMajID." <br/>";
$NbrTableAlire = substr_count($listTableMajID,",");
if ($NbrTableAlire == 0) {
	$NbrTableAlire = 1;
} else {
	$NbrTableAlire += 1;
}

$tableMajID = explode(",",$listTableMajID);

$nbtableMajID = count($tableMajID) - 1;
for ($cptID = 0; $cptID <= $nbtableMajID; $cptID++) {
	// controle de la table en cours si besoin (gestion TIMEOUT)
	//echo "table = ".$nbtableMajID." tableencours = ".$tableEnCours."<br/>";
	if ((!$tableEnCours == "" && $tableEnCours == $tableMajID[$cptID]) || $tableEnCours == "") {
		if ($debugAff==true) {
			$debugTimer = number_format(timer()-$start_while,4);
			echo "lecture table :".$debugTimer."<br/>";
		}
		if ($EcrireLogComp ) { WriteCompLog ($logComp,"Creation SQL pour ".$tableMajID[$cptID],$pasdefichier);}

		echo $cptID." / ".$nbtableMajID." Creation SQL table en cours = ".$tableMajID[$cptID]."<br/>";
		//Pour gestion timeout, la valeur de la table en cours
		$tableEnLecture = $tableMajID[$cptID];
		//echo "XXXX---  table en lecture = ".$tableEnLecture."<br/>";
		// Compteur 
		$compReadSqlC = " select count(id) from ".$tableMajID[$cptID];
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
			// on reinitialise les valeurs avec les variables de session mise à jour lors du traitement précédent
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
		
		// Traitement dans le cas du timeout 
		if ($tableEnCours == "") {
			// Reconstruire tous les id pour cette entité
			echo "traitement table ".$tableMajID[$cptID]."<br/>";
			$maxenvirIdSource  = 1;
			$maxReadSql = " select max(id) from ".$tableMajID[$cptID];
			$maxReadResult = pg_query(${$BDCible},$maxReadSql) or die('erreur dans la requete : '.pg_last_error());
			if (pg_num_rows($maxReadResult) == 0) {
				echo "erreur : table ".$tableMajID[$cptID]." vide <br/>";
			// message d'erreur
			} else {
				$maxRow = pg_fetch_row($maxReadResult);
				if ( $maxRow[0] == null) {
					$maxenvirIdSource  = 1;
				} else {
					$maxenvirIdSource  = intval($maxRow[0] + 1);
				}
			}
			
			pg_free_result($maxReadResult);
		} else  {
			$maxenvirIdSource  = $_SESSION['s_max_envir_Id_Source'];
			echo "timeout ".$tableEnCours." id en cours =".$maxenvirIdSource."<br/>";
		}
		// Gestion TIMEOUT : on reprend la ou on s'etait arrete
		// Comme on trie par ID, on ne va pas en perdre en route
		if ($tableEnCours == "") {
			$condWhere = "";
		} else {
			$condWhere = " where id > ".$IDEnCours;
		}
		if ($debugAff==true) {
			$debugTimer = number_format(timer()-$start_while,4);
			echo "lecture dans la base source :".$debugTimer."<br/>";
		}
		// debut de l'analyse : lecture de la table en cour	
		$envSrcSql = " select id from ".$tableMajID[$cptID].$condWhere. " order by id ASC"; 
		$envSrcResult = pg_query(${$BDSource},$envSrcSql) or die('erreur dans la requete : '.pg_last_error());
		if (pg_num_rows($envSrcResult) == 0) {
		// Message d'erreur
			echo "table ".$tableMajID[$cptID]." vide dans ".$nomBDSource." <br/>";
			$tableEnCours == "";
		} else {
			//echo "nb enreg ".$tableMajID[$cptID]." = ".pg_num_rows($envSrcResult)." where = ".$condWhere."<br/>";
			while ($envRow = pg_fetch_row($envSrcResult) ) {
				if ($debugAff==true) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "xxx debut trt enreg :".$debugTimer."<br/>";
				}
				// Gestion TIMEOUT
				//echo "XX-----  table lue = ".$tableMajID[$cptID]."  id lu = ".$envRow[$RangId]." table en lecture = ".$tableEnLecture." id en lecture = ".$IDEnLecture."<br/>";
				$ourtime = (int)number_format(timer()-$start_while,7);
				$seuiltemps= ceil(0.9*$max_time);
				//echo $ourtime."  ".$seuiltemps."<br/>";
				// On prend un peu de marge par rapport au temps max.
				if ($ourtime >= ceil(0.9*$max_time)) {
					$delai=number_format(timer() - $start_while,7);
					$ArretTimeOut =true;
					echo "timeout table en lecture = ".$tableEnLecture." id en lecture = ".$IDEnLecture."<br/>";
					break;
				}

				$testTtypeID = strpos($ListeTableIDPasNum ,$tableMajID[$cptID]);
				if ($testTtypeID === false) {
					// L'ID est bien un numérique
					$where = " where id = ".intval($envRow[$RangId]) ; 
					$idNomTable = intval($envRow[$RangId]);
				} else {
					// L'ID est une chaine
					$where = " where id = '".$envRow[$RangId]."'" ;
					$idNomTable = "'".$envRow[$RangId]."'";
				}
				// Compteur 
				$cptChampTotal++;
				$IDEnLecture = $envRow[$RangId] ;					
				// On regarde si l'enregistrement est a maj dans la base ou non
				//echo "Traitement de l'enregistrement ".$cptChampTotal." sur ".$totalLignes;

				$nomTable = $tableMajID[$cptID];
				// attention gérer les id en char... notamment pour art_fraction
				
				$tempExist = "";
				$tempetatAction = "";
				$tempSQL = "";
				$tempNewID = 0;
				$tempID = 0;
				if ($debugAff==true) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "avant test appartenance :".$debugTimer."<br/>";
				}
				include $_SERVER["DOCUMENT_ROOT"].'/process_auto/appartenance.php';
				if ($tempetatAction == "a" || $tempetatAction == "m") {
					//echo "table =".$tableMajID[$cptID]." id=".$envRow[0]." action = ".$tempetatAction."<br/>";
					$sourceSql = " select * from ".$tableMajID[$cptID]." ".$where;
					$sourceSqlResult = pg_query(${$BDSource},$sourceSql) or die('erreur dans la requete : '.pg_last_error());
					if ($debugAff==true) {
						$debugTimer = number_format(timer()-$start_while,4);
						echo "apres test appartenance :".$debugTimer."<br/>";
					}
					// Cas particulier des tables de références
					// On a déjà réévaluer le nouvel ID....
					if 	($tableMajID[$cptID] =="exp_campagne" || $tableMajID[$cptID] == "art_debarquement")
					{
						$tempNewID = $scriptSQLRow[2];
					} else { 
						$tempNewID = $maxenvirIdSource;
					}
					// Evaluation du type de SQL a executer
					// Et stockage de la requete.
					if (pg_num_rows($sourceSqlResult) == 0) {
					// Ca ne devrait jamais etre le cas !
						echo "Vous ne devriez pas voir ce message; arrggghhhh.<br/>";
					} else {
						$sourceSqlRow = pg_fetch_row($sourceSqlResult);
						switch	($tempetatAction) {
						case "a" ;
							$tempSQL = GetSQL('insert',  $tableMajID[$cptID], $where, $sourceSqlRow,${$BDSource},$nomBDSource,$typeAction,$PathFicConf,$tempNewID,"y",$ListeTableIDPasNum,$debugAff,$start_while);						
							break;
						case "m" :					
							$tempSQL = GetSQL('update',  $tableMajID[$cptID], $where, $sourceSqlRow,${$BDSource},$nomBDSource,$typeAction,$PathFicConf,$tempNewID,"n",$ListeTableIDPasNum,$debugAff,$start_while);
							break;
						}					
					}	

					if ($debugAff==true) {
						$debugTimer = number_format(timer()-$start_while,4);
						echo "avant replace :".$debugTimer."<br/>";
					}
					$tempSQL = str_replace("'","''",$tempSQL);
					// Avant de créer le SQL on doit prendre en compte le fait qu'on peut avoir des ID en char
					if ($testTtypeID === false) {
						// L'ID est bien un numérique
						$tempIDchar = 'null';
						$tempNewIDchar = 'null';
					} else {
						// L'ID est une chaine
						$tempIDchar = $idNomTable;
						$tempNewIDchar = "'".$tempNewID."'";
						$idNomTable = 'null';
						$tempNewID = 'null';
					}
					if ($debugAff==true) {
						$debugTimer = number_format(timer()-$start_while,4);
						echo "avant insert du sql dans la BD :".$debugTimer."<br/>";
					}
					//On rajoute a la fin du script les valeurs de du nouvel Id et l'ancien ID de la campagne
					$insertSQL = "insert into temp_recomp_id values ('".$tableMajID[$cptID]."',".$idNomTable.",".$tempIDchar.",".$tempNewID.",".$tempNewIDchar.",'".$tempExist."','".$tempetatAction."','".$tempSQL."',".$tempNewID.",".$tempID.",'')";
					
					//$insertSQL = "insert into temp_recomp_id values ('".$tableMajID[$cptID]."',".$idNomTable.",".$tempNewID.",'".$tempExist."','".$tempetatAction."','".$tempSQL."',".$tempNewID.",".$tempID.",'')";
					//echo $insertSQL."<br/>";
					$insertSQLResult = pg_query(${$BDSource},$insertSQL) ;
					if ($insertSQLResult) {
						$cptAjoutMaj ++;
					} else {
						$cptSQLErreur ++;
					}
					pg_free_result($insertSQLResult);
					$maxenvirIdSource ++; 
					
				} else {
					// Enregistrement existant déjà qu'on doit ignorer.
					//echo "Pas maj table ".$tableMajID[$cptID]." pour id = ".$idNomTable."<br/>";
				}
				if ($debugAff==true) {
					$debugTimer = number_format(timer()-$start_while,4);
					echo "xxx fin trt enreg :".$debugTimer."<br/>";
				}  
			}// End while ($envRow = pg_fetch_row($envSrcResult) )
			// On effectue ici tous les traitements liés à la fin de la lecture d'une table
			if ($ArretTimeOut) {
				break;
			}
			// TIMEOUT, reinitialisation des variables EnCours
			$IDEnCours = 0;
			$tableEnCours = "";
			
			// On gère le compte-rendu
			if (!$ArretTimeOut) {
				$CRexecution = $CRexecution." *-".$tableMajID[$cptID]." : ".$cptChampTotal." lus";
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp,"FIN ".$nomAction." pour ".$tableMajID[$cptID]." : lus = ".$cptAjoutMaj,$pasdefichier);
				}
			
				$CRexecution = $CRexecution." (a maj/a ajouter total=".$cptAjoutMaj.") -* <br/>" ;
			}
				
			pg_free_result($envSrcResult);
		}
	} //end ((!$tableEnCours == "" && $tableEnCours == $$tableMajID[$cptID]) ...
	
	
} // end for ($cptID = 0; $cptID <= $nbtableMajID; $cptID++)
if ($ArretTimeOut){
	$_SESSION['s_max_envir_Id_Source'] = $maxenvirIdSource;
} else {
	// On peut lancer maintenant le traitement des SQL
	$ExecSQL = "y";
	$finmajDP = true;
}


?>