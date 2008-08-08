<?php
function query_from_ste($values,$key2){
	return "select AF.id, AD.id 
		from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
		where AD.id = AF.art_debarquement_id 
		and AD.art_agglomeration_id = AA.id 
		and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
		and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
		and AD.mois = " . $info_deb[$key][$key2][3] ." 
		and AD.annee = " . $info_deb[$key][$key2][4] ." 
		and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
		and AF.debarquee = 1 
		and AF.poids != 0 
		and AF.nbre_poissons != 0 
		and AF.id != '" . $key2 ."'";

}
function query_from_ste_plus($values,$key2){
	$month=$values[3];
	$query3="select distinct AF.id, AD.id 
			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
			, art_poisson_mesure as APM 
			where AD.id = AF.art_debarquement_id 
			and APM.art_fraction_id = AF.id 
			and AD.art_agglomeration_id = AA.id 
			and AF.ref_espece_id = '" . $values[7] ."' 
			and AA.nom = '" . $values[2] ."' 
			and AD.art_grand_type_engin_id = '" . $values[6]."' 
			and AF.debarquee = 1 
			and AF.id != '" . $key2 ."' 
			and ((AD.annee = " . $values[4] ."";
	
	if ($month == 1){   //si mois 1 (janvier){
		$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
				or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
				or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
				AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
				
	}
	if ($month == 2)   //si mois 2
								{
								$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								
								}
	if ($month == 3)   //si mois 3
								{
								$$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9)) 
								or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
	if ($month == 4)   //si mois 4
								{
								$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
	if ($month == 5)   //si mois 5
								{
								$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
								}
	if ($month == 6)   //si mois 6
								{
								$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $valm1 ." and AD.mois =12))";
							}
	if ($month == 7)   //si mois 7
								{
								$query3.=" and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and AD.mois =1))";
								}
	if ($month == 8)   //si mois 8
								{
								$val1 =$values[4]+1;
								
								$query3.=" and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
								or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
								or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
								or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
								
								
								}
							if ($month == 9)   //si mois 9
								{
								$query3.=" and (AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3)))";
								}
							if ($month == 10)   //si mois 10
								{
								$query3.=" and (AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4)))";
								}
							if ($month == 11)   //si mois 11
								{
								$query3.=" and ((AD.annee = " . $values[4] ." 
								and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
								}
							if ($month == 12)   //si mois 12
								{
								$query3.=" and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
								}
return $query3;
}




function query_from_se($values, $key2){

	if ($values[3] == 1)   //si mois 1 (janvier)
	{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, 
						 art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $values[7] ."' 
						and AA.nom = '" . $values[2] ."' 
						and AD.art_grand_type_engin_id = '" . $values[6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $values[4] ." 
						and (AD.mois = 1 or AD.mois = 2)) 
						or (AD.annee = " . ($values[4]-1) ." 
						and AD.mois = 12))";
						}
	elseif ($values[3] == 12)   //si mois 12
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $values[7] ."' 
						and AA.nom = '" . $values[2] ."' 
						and AD.art_grand_type_engin_id = '" . $values[6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $values[4] ." 
						and (AD.mois = 12 or AD.mois = 11)) 
						or (AD.annee = " . ($values[4]+1) ." 
						and AD.mois = 1))";
						}
	else	{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $values[7] ."' 
						and AA.nom = '" . $values[2] ."' 
						and AD.art_grand_type_engin_id = '" . $values[6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and AD.annee = " . $values[4] ." 
						and ( AD.mois = " . (($values[3])-1) ." 
						or AD.mois = " . $values[3] ." 
						or AD.mois = " . (($values[3])+1) .")"; 
	}
}


function query_from_e($values, $key2,$type){

switch($cas){
	"secteur" :return "select distinct AF.id, AD.id 
			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, ref_secteur as RS 
			, art_poisson_mesure as APM 
			where AD.id = AF.art_debarquement_id 
			and APM.art_fraction_id = AF.id 
			and AD.art_agglomeration_id = AA.id 
			and AA.ref_secteur_id = RS.id
			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
			and RS.nom = '" . $info_deb[$key][$key2][1]."'
			and AF.debarquee = 1 
			and AF.id != '" . $key2 ."'"; 




"systeme" :
$query9 = "select AF.id, AD.id 
		from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, ref_secteur as RS 
		where AD.id = AF.art_debarquement_id 
		and AD.art_agglomeration_id = AA.id 
		
		and AA.ref_secteur_id = RS.id 
		and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
		and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
		and RS.nom = '" . $info_deb[$key][$key2][1]."' 
		and AF.debarquee = 1 
		and AF.poids != 0 
		and AF.nbre_poissons != 0 
		and AF.id != '" . $key2 ."'";
			
}

function query_from_e_plus($values,$key2,$type){

switch($type){
	case "secteur" : return "select distinct AF.id, AD.id 
		from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
		, art_poisson_mesure as APM 
		where AD.id = AF.art_debarquement_id 
		and APM.art_fraction_id = AF.id 
		and AD.art_agglomeration_id = AA.id 
		and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
		and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
		and AF.debarquee = 1 
		and AF.id != '" . $key2 ."'"; 

	case "system" : return "select AF.id, AD.id 
		from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
		where AD.id = AF.art_debarquement_id 
		and AD.art_agglomeration_id = AA.id 
		and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
		and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
		and AF.debarquee = 1 
		and AF.poids != 0 
		and AF.nbre_poissons != 0 
		and AF.id != '" . $key2 ."'"; 
	}
}


?>