<?php
/*
function selection STE
@param $index du tableau de donn�es � recompios�es
@param $index du tableau
@param $round tour de selection 
@return query sql
*/
function query_from_ste($values,$key,$key2,$round){
	switch($round){	
		case "0":$query= "select distinct AF.id, AD.id 
				from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, art_poisson_mesure as APM 
				where AD.id = AF.art_debarquement_id 
				and APM.art_fraction_id = AF.id 
				and AD.art_agglomeration_id = AA.id 
				and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
				and AA.nom = '" . $values[$key][$key2][2] ."' 
				and AD.mois = " . $values[$key][$key2][3] ." 
				and AD.annee = " . $values[$key][$key2][4] ." 
				and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
				and AF.debarquee = 1 
				and AF.id != '" . $key2 ."'";
				break;
		case "1":$query= "select distinct AF.id, AD.id 
				from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
				where AD.id = AF.art_debarquement_id 
				and AD.art_agglomeration_id = AA.id 
				and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
				and AA.nom = '" . $values[$key][$key2][2] ."' 
				and AD.mois = " . $values[$key][$key2][3] ." 
				and AD.annee = " . $values[$key][$key2][4] ." 
				and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
				and AF.debarquee = 1 
				and AF.poids != 0 
				and AF.nbre_poissons != 0 
				and AF.id != '" . $key2 ."'";
				break;

	
	
	
	
	}
	//print_debug($query);
	return $query;
}

/*
function selection strate SE
@param array $values tableau des info_deb
@param string $key 
@param string $key2
@param string $round ou ou 1 : 
@return $datas
*/
function query_from_se($values,$key,$key2,$round){
	$month=$values[$key][$key2][3];
	$val1 =$values[$key][$key2][4]+1;
	$valm1 =$values[$key][$key2][4]-1;

	switch($round){
		case "0": 
			$query3="select distinct AF.id, AD.id 
			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
			, art_poisson_mesure as APM 
			where AD.id = AF.art_debarquement_id 
			and APM.art_fraction_id = AF.id 
			and AD.art_agglomeration_id = AA.id 
			and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
			and AA.nom = '" . $values[$key][$key2][2] ."' 
			and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
			and AF.debarquee = 1 
			and AF.id != '" . $key2 ."' 
			and ((AD.annee = " . $values[$key][$key2][4] ."";
			break;
		case "1":
			$query3="select AF.id, AD.id 
			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
			where AD.id = AF.art_debarquement_id 
			and AD.art_agglomeration_id = AA.id 
			and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
			and AA.nom = '" . $values[$key][$key2][2] ."' 
			and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
			and AF.debarquee = 1 
			and AF.poids != 0 
			and AF.nbre_poissons != 0 
			and AF.id != '" . $key2 ."' 
			and ((AD.annee = " . $values[$key][$key2][4] .""; 
			break;
	}

	if ($month == 1){   //si mois 1 (janvier){
		$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
				or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
				or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
				AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
				
	}elseif ($month == 2){   //si mois 2
		$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
				or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
				or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
				AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
	}elseif ($month == 3){   //si mois 3
		$$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
				or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
				or AD.mois = 9)) 
				or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
				or AD.mois =11 or AD.mois =12)))";
	}elseif ($month == 4){   //si mois 4
		$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
			or AD.mois = 9 or AD.mois =10)) 
			or (AD.annee = " . $valm1 ." and (AD.mois =10 
			or AD.mois =11 or AD.mois =12)))";
	}elseif ($month == 5){   //si mois 5
		$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
				or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
				or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
				or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
	}elseif ($month == 6){   //si mois 6
		$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
				or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
				or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
				or (AD.annee = " . $valm1 ." and AD.mois =12))";
	}elseif ($month == 7){   //si mois 7
		$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
			or (AD.annee = " . $val1 ." and AD.mois =1))";
	}elseif ($month == 8){   //si mois 8
		//$val1 =$values[$key][$key2][4]+1;
		$query3.=" and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
				or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
				or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
				or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
	}elseif ($month == 9){   //si mois 9
		$query3.=" and (AD.mois = 3 or AD.mois = 4 
				or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
				or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
				or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
				or AD.mois =3)))";
	}elseif ($month == 10){   //si mois 10
		$query3.=" and (AD.mois = 4 
			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
			or AD.mois =3 or AD.mois =4)))";
	}elseif ($month == 11){   //si mois 11
		$query3.=" and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
				or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
				or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
				or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
	}elseif ($month == 12){   //si mois 12
		$query3.=" and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
				or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
				or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
				or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
	}
	
	return $query3;
}



/*
function selection strate STE +
@param array $values tableau des info_deb
@param string $key 
@param string $key2
@param string $round ou ou 1 : 
@return $datas
*/
function query_from_ste_plus($values,$key, $key2,$round){
	switch($round){
		case "0":$query2="select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, 
						 art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
						and AA.nom = '" . $values[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $values[$key][$key2][4] ."";
						break;
		case "1":	$query2="select AF.id, AD.id 
			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
			where AD.id = AF.art_debarquement_id 
			and AD.art_agglomeration_id = AA.id 
			and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
			and AA.nom = '" . $values[$key][$key2][2] ."' 
			and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
			and AF.debarquee = 1 
			and AF.poids != 0 
			and AF.nbre_poissons != 0 
			and AF.id != '" . $key2 ."' 
			and ((AD.annee = " . $values[$key][$key2][4] .""; 
			break;
	}
	if ($values[$key][$key2][3] == 1){   //si mois 1 (janvier)
		$query2.= " and (AD.mois = 1 or AD.mois = 2)) 
			or (AD.annee = " . ($values[$key][$key2][4]-1) ." 
			and AD.mois = 12))";
	}elseif ($values[$key][$key2][3] == 12){   //si mois 12
		$query2.= " and (AD.mois = 12 or AD.mois = 11)) 
			or (AD.annee = " . ($values[$key][$key2][4]+1) ." 
			and AD.mois = 1))";
	}else{
		$query2.= " and ( AD.mois = " . (($values[$key][$key2][3])-1) ." 
			or AD.mois = " . $values[$key][$key2][3] ." 
			or AD.mois = " . (($values[$key][$key2][3])+1) .")))"; 
	}
	return $query2;
}

/*
function selection strate E
@param array $values tableau des info_deb
@param string $key 
@param string $key2
@param string $round ou ou 1 : 
@return $datas
*/
function query_from_e($values,$key, $key2,$round){
	switch($round){
		case "0" :return "select distinct AF.id, AD.id 
			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, ref_secteur as RS 
			, art_poisson_mesure as APM 
			where AD.id = AF.art_debarquement_id 
			and APM.art_fraction_id = AF.id 
			and AD.art_agglomeration_id = AA.id 
			and AA.ref_secteur_id = RS.id
			and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
			and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
			and RS.nom = '" . $values[$key][$key2][1]."'
			and AF.debarquee = 1 
			and AF.id != '" . $key2 ."'"; 
			break;
		case "1" : return "select AF.id, AD.id 
		from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, ref_secteur as RS 
		where AD.id = AF.art_debarquement_id 
		and AD.art_agglomeration_id = AA.id 
		
		and AA.ref_secteur_id = RS.id 
		and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
		and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
		and RS.nom = '" . $values[$key][$key2][1]."' 
		and AF.debarquee = 1 
		and AF.poids != 0 
		and AF.nbre_poissons != 0 
		and AF.id != '" . $key2 ."'";                
		break;
	}
	return $query;
	
}

/*
function selection strate E +
@param array $values tableau des info_deb
@param string $key 
@param string $key2
@param string $round ou ou 1 : 
@return $datas
*/
function query_from_e_plus($values,$key,$key2,$round){
	switch($round){
		case "0" : return "select distinct AF.id, AD.id 
		from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
		, art_poisson_mesure as APM 
		where AD.id = AF.art_debarquement_id 
		and APM.art_fraction_id = AF.id 
		and AD.art_agglomeration_id = AA.id 
		and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
		and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
		and AF.debarquee = 1 
		and AF.id != '" . $key2 ."'"; 
		break;
		case "1" : return "select AF.id, AD.id 
		from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
		where AD.id = AF.art_debarquement_id 
		and AD.art_agglomeration_id = AA.id 
		and AF.ref_espece_id = '" . $values[$key][$key2][7] ."' 
		and AD.art_grand_type_engin_id = '" . $values[$key][$key2][6]."' 
		and AF.debarquee = 1 
		and AF.poids != 0 
		and AF.nbre_poissons != 0 
		and AF.id != '" . $key2 ."'"; 
		break;
	}
	
	return "";
}


/*
function INSERTION DES DONNEES RESULTATS CONTENUES DANS $info_deb DANS LA BASE DE DONNEES PPEAO  (tables art_debarquement_rec et art_fraction_rec)
@param array datas tableau des donn�es recompos�es
@param string $afficherMessage
@return $afficherMessage 
*/
function insert_values_recompose($datas,$afficherMessage,$nb_enr){
	global $connection;
	$messageProcess="";
	reset($datas);
	$compteur=0;
	foreach($datas as $key =>$val){
		$compteur++;
		$messageProcess.="<br/><b>Recomposisiton de l'enqu&ecirc;te ".$compteur . " sur ".$nb_enr ."</b><br/><br/>";
		$Wti =0;
		foreach ($val as $key2=>$val2){
			$query = "insert into art_fraction_rec ( id, poids , nbre_poissons, ref_espece_id ) 
				values ('".$key2."', ".$datas[$key][$key2][8].", ".$datas[$key][$key2][9].", '".$datas[$key][$key2][7]."');";
			//print_debug($query);
			$RunQErreur = runQuery($query,$connection);
			if ($RunQErreur){
			
			}else {
				$messageProcess.="<font color='blue'>Pb insertion de cette requ�te</font><br/>";
				// traitement d'erreur ? On arr�te ou seulement avertissement ?
			}
			$messageProcess .= "".$query."<br/>";
			$Wti += $datas[$key][$key2][8];
		}
		$query = "insert into art_debarquement_rec ( id, poids_total, art_debarquement_id ) 
		values ('rec_".$key."', ".$Wti.", ".$key.");";
		// Modification YL 15/07/2008 pour eviter les warning affich�s � l'�cran erreur ==> dans le log
		//if($Wti!=0)$result2 = pg_exec($connection, $query2); // Ancienne ajout donn�es. 
		// nouvelle insertion donn�es en utilisant la fonction runQuery
		
		if($Wti!=0) {
			//print_debug($query);
			$messageProcess .= "".$query."<br/>";
			$RunQErreur = runQuery($query,$connection);
			if ( $RunQErreur){
				
			} else {
				$messageProcess.="<font color='blue'>Pb insertion de cette requ�te</font><br/>";
				// traitement d'erreur ? On arr�te ou seulement avertissement ?
			}
		}
	}
	if ($afficherMessage == "1") {
		return $messageProcess ;
	}else{
		return "";
	}
}
?>