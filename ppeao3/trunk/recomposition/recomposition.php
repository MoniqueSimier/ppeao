<?php
getTime();

//***********************************************
// Cr�ation Yann LAURENT, 01-07-2008
// A partir du fichier php initial du lot 2 PPEAO
//***********************************************

// YL 15/07/2008 on remplace les messages direct par une variable qu'on affiche ou non en fin de traitement
$messageProcess = "Debut programme <br/>" ;
//print "GEt ==== ".print_r($_GET);
//print "<br/>";


//echo "debut programme";
// Variables pour affichage ou non des messages
if (isset($_GET['aff'])) {
	$afficherMessage = $_GET['aff'] ;
} else {
	$afficherMessage = "0" ;
}

set_time_limit(30000);


$nb_enr = $_GET['nb_enr'];
$bdd = $_GET['base'];
$to = $_GET['adresse'];
if($bdd==""){
	$bdd=$db_default;
}

$messageProcess .= "<br/>travail sur la base : ".$bdd ;
//print("<br/>travail sur la base : ".$bdd);


/////////////////////////////////////////////////////////////////////////////////////////////
//                                   Pr�traitement                                         //
//                                                                                         //
//                             Fafrication du tableau coef_esp                             //
//                     receuillant les informations de k et b par esp�ce                   //
/////////////////////////////////////////////////////////////////////////////////////////////

$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) {  echo "Non connect�"; exit;}

$query = "select id, coefficient_k, coefficient_b, ref_espece_id FROM ref_espece order by id";
//print "query ===".$query."<br/>";
$result = pg_query($connection, $query);

while($row = pg_fetch_row($result)){
	$esp = $row[0];            //espece
	$k = $row[1];              //coef k
	$b = $row[2];              //coef b
	$ref = $row[3];            //ref
	
	$coef_esp[$esp][0]= $k;
	$coef_esp[$esp][1]= $b;
	$coef_esp[$esp][2]= $ref;
	}


//pg_close();


//remise � z�ro du pointeur
reset($coef_esp);

while (list($key, $val) = each($coef_esp))
	{    
		
	// si k et b non renseign�s ou renseign�s avec les deux valeurs � 0 
	if ( (($val[0] == 0)&&($val[1] == 0))  ||  (($val[0] == "")&&($val[1] == ""))   ){
		
		//si il existe une esp�ce r�f�rence
		if ($val[2] != "") { 
			$new = $val[2];
			$coef_esp[$key][0]=$coef_esp[$new][0];
			$coef_esp[$key][1]=$coef_esp[$new][1];
			}
		//sinon k=1, b=3
		else {
			$coef_esp[$key][0]= 1;
			$coef_esp[$key][1]= 3;
			}
		}
	
	}// fin du while




////////////////////////////////////////////////////////////////////////////////////////////////
//                   R�cup�ration des informations n�cessaires aux calculs                    //
//                        des donn�es manquantes (pour chaque d�barquement)                   // 
//                                    tableau $info_deb                                       // 
//                 et tableau $FT receuillant les infos sur les tailles par fraction          //
////////////////////////////////////////////////////////////////////////////////////////////////



//if(! ini_set("memory_limit", "256M")) {echo "�chec";}

//m�thode 1
/*$query = "select AD.id, RF.ref_pays_id, RS.nom, AA.nom, AD.mois, AD.annee, AD.poids_total,
	AD.art_grand_type_engin_id, AF.ref_espece_id, AF.poids, AF.nbre_poissons, AF.id 
	from ref_systeme as RF, ref_secteur as RS, art_agglomeration as AA, art_debarquement as AD,
	art_fraction as AF 
	where RS.ref_systeme_id = RF.id 
	and AA.ref_secteur_id = RS.id 
	and AD.art_agglomeration_id = AA.id 
	and AD.id = AF.art_debarquement_id 
	and AF.debarquee = 1 
	order by AD.id";
	print_debug($query);
*/
//fin m�thode 1
//m�thode 2
$query="select count(*) From ref_systeme as RF, ref_secteur as RS, art_agglomeration as AA, art_debarquement as AD,
	art_fraction as AF 
	where RS.ref_systeme_id = RF.id 
	and AA.ref_secteur_id = RS.id 
	and AD.art_agglomeration_id = AA.id 
	and AD.id = AF.art_debarquement_id 
	and AF.debarquee = 1";

$result = pg_query($connection, $query);
//fin m�thode 2
//m�thode 2
$row = pg_fetch_row($result);
$compteur=$row[0];
print_debug("compteur ==".$compteur);
//fin m�thode 2

$info_deb=array();
//m�thode 2
for($index=1; $index<=$compteur; $index+=1000){
	$query = "select AD.id, RF.ref_pays_id, RS.nom, AA.nom, AD.mois, AD.annee, AD.poids_total,
	AD.art_grand_type_engin_id, AF.ref_espece_id, AF.poids, AF.nbre_poissons, AF.id 
	from ref_systeme as RF, ref_secteur as RS, art_agglomeration as AA, art_debarquement as AD,
	art_fraction as AF 
	where RS.ref_systeme_id = RF.id 
	and AA.ref_secteur_id = RS.id 
	and AD.art_agglomeration_id = AA.id 
	and AD.id = AF.art_debarquement_id 
	and AF.debarquee = 1 
	order by AD.id
	LIMIT 1000 OFFSET ".$index."";
//fin m�thode 2	
	
	print_debug("ligne 147=".$query);
	$result = pg_query($connection, $query);


	while($row = pg_fetch_row($result)){
		
		$cl� = $row[0];                                //cle = identifiant du d�barquement
		$cle2 = $row[11];                              //cle2 = identifiant de la fraction
		$info_deb[$cl�][$cle2][0] = $row[1];           //pays
		$info_deb[$cl�][$cle2][1] = $row[2];           //secteur
		$info_deb[$cl�][$cle2][2] = $row[3];           //agglomeration
		$info_deb[$cl�][$cle2][3] = $row[4];           //mois
		$info_deb[$cl�][$cle2][4] = $row[5];           //ann�e
		$info_deb[$cl�][$cle2][5] = $row[6];           //poid total du d�barquement
		$info_deb[$cl�][$cle2][6] = $row[7];           //engin de peche
		$info_deb[$cl�][$cle2][7] = $row[8];           //espece p�ch�e = espece de la fraction
		$info_deb[$cl�][$cle2][8] = $row[9];           //poid de la fraction = Wfdbq
		$info_deb[$cl�][$cle2][9] = $row[10];          //nombre poisson de la fraction = Nfdbq        
	}
//m�thode 2
}
//fin m�thode 2
pg_free_result($result);





//pg_close();



//////////////////////////////////////////////////////////////////////////////////
//                          Pour les tailles:                                   //
//                       cr�ation du tableau $FT                                //
//////////////////////////////////////////////////////////////////////////////////



$query = "select AF.id, APM.taille 
	from art_fraction as AF, art_poisson_mesure as APM 
	where APM.art_fraction_id = AF.id 
	and AF.debarquee = 1 
	order by AF.id";

print_debug("ligne 190=".$query);
$result = pg_query($connection, $query);

while($row = pg_fetch_row($result))
	{
	$id = $row[0];                             //cl� = identifiant fraction
	$FT[$id][] = $row[1];                      //tailles increment�s auto dans tableau :
        }                                    //$FT[$id][0] = taille 1, $FT[$id][1] = taille 2...
pg_free_result($result);//19 09
//pg_close();




////////////////////////////////////////////////////////////////////////////////
//               calcul et ajout des Wdft et Ndft pour chaque fraction        //
//                     dans le tableau recapitulatif $info_deb                //
////////////////////////////////////////////////////////////////////////////////


reset($info_deb);
while (list($key, $val) = each($info_deb))
	{
	while (list($key2, $val2) = each($val))
		{
		$Ndft = 0;
		if(isset($FT[$key2] ))$Ndft = count($FT[$key2]);
		$info_deb[$key][$key2][11] = $Ndft;                    //Ndft ds tableau r�cap		
		
		
		
		$esp = $val2[7];
		$Wdft = 0;                                             //mise � z�ro de la variable
		for($i=0; $i<($Ndft) ; $i++) 
			{
			$Wdft += ($coef_esp[$esp][0] * pow(10, -5) * pow($FT[$key2][$i],$coef_esp[$esp][1]));
			}
		$info_deb[$key][$key2][12] = round($Wdft, 1);		//Wdft ds tableau r�cap
									// avec precision = 0.1
		if ($Ndft != 0) {$Wm = round(($Wdft / $Ndft) , 1);}
		else {$Wm = 0;}
		$info_deb[$key][$key2][13] = $Wm;			//Wm ds tableau recap
									// avec pr�cision = 0.1
		}

	} 


////////////////////////////////////////////////////////////////////////////////////////////////////
//                                RECOMPOSITION INTRA FRACTION                                    //
//                                                                                                //
//                                   Traitements des 8 cas                                        //
//                        gr�ce au tableau recapitulatif $info_deb                                //
//                             (travail fraction par fraction)                                    //
////////////////////////////////////////////////////////////////////////////////////////////////////
$nume_prodgr=1/$nb_enr;
$numero = 0;
reset($info_deb);
while (list($key, $val) = each($info_deb))//pour tous les debarquements
{
	
	$numero = $numero+1;
	//$messageProcess .= "Recomposition de l'enqu&ecirc;te ".$numero . " sur ".$nb_enr." <br/>";
	//print "Recomposition de l'enqu�te ".$numero . " sur ".$nb_enr;

	while (list($key2, $val2) = each($val))			//pour chaque fraction
		{
		$Wfdbq = $info_deb[$key][$key2][8];
		$Nfdbq = $info_deb[$key][$key2][9];
		$Ndft = $info_deb[$key][$key2][11];
		$Wdft = $info_deb[$key][$key2][12];
		$Wm = $info_deb[$key][$key2][13];



		//////////////////////////////////////////
		//               cas n�1                //
		//  Wfdbq = 0 , Nfdbq > 0, DFT existe   //
		//////////////////////////////////////////

		if ( (($Wfdbq == 0)||($Wfdbq == "")) && ($Nfdbq>0) && ($Ndft>0))
			{
			$Wfdbq = $Wm * $Nfdbq;
			if ($Wfdbq < $Wdft) {$Wfdbq = $Wdft;}
			$info_deb[$key][$key2][8] = round(($Wfdbq /1000) , 2);	//en kg	
			}

		//////////////////////////////////////////
		//               cas n�2                //
		//  Wfdbq > 0 , Nfdbq = 0, DFT existe   //
		//////////////////////////////////////////

		elseif ( ($Wfdbq>0) && (($Nfdbq == 0)||($Nfdbq == "")) && ($Ndft>0))
			{
			$Nfdbq = round((($Wfdbq *1000) / $Wm),0);		//wfdbq en kg
			if ($Nfdbq < $Ndft) {$Nfdbq = $Ndft;}
			$info_deb[$key][$key2][9] = $Nfdbq;
			}

		//////////////////////////////////////////
		//               cas n�3                //
		//  Wfdbq >0  , Nfdbq = 0, pas de DFT   //
		//////////////////////////////////////////

		elseif ( ($Wfdbq>0) && (($Nfdbq == 0)||($Nfdbq == "")) && (($Ndft == 0)||($Ndft == "")) )
			{
			
			$query = "select distinct AF.id, AD.id 
				from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, art_poisson_mesure as APM 
				where AD.id = AF.art_debarquement_id 
				and APM.art_fraction_id = AF.id 
				and AD.art_agglomeration_id = AA.id 
				and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
				and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
				and AD.mois = " . $info_deb[$key][$key2][3] ." 
				and AD.annee = " . $info_deb[$key][$key2][4] ." 
				and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
				and AF.debarquee = 1 
				and AF.id != '" . $key2 ."'";

		print_debug("ligne 310=".$query);

			$result = pg_query($connection, $query);
			//pg_close();

			$WdftI = 0;
			$NdftI = 0;

//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
$nb = pg_num_rows($result);
if ($nb == 0){$query = "select id, art_debarquement_id from art_fraction limit 1";
//print "query ==".$query."<br/>";

$result = pg_query($connection, $query); 
//pg_close();
}

			while($row = pg_fetch_row($result))
				{

				$nb = pg_num_rows($result);	//nb de fractions concern�es
				$frac_concern�es = $row[0];
				$deb_concern� = $row[1];

				if ($nb >= 5)
					{
					$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
					$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

					$WdftI += $Wdft;
					$NdftI += $Ndft;

					$Wm = ($WdftI / $NdftI);
					$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
					$info_deb[$key][$key2][9] = $Nfdbq; 
					}

				else	{			//strate STE+
					//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
					pg_free_result($result);
					if ($info_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, 
						 art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
						and (AD.mois = 1 or AD.mois = 2)) 
						or (AD.annee = " . ($info_deb[$key][$key2][4]-1) ." 
						and AD.mois = 12))";
						}
					elseif ($info_deb[$key][$key2][3] == 12)   //si mois 12
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
						and (AD.mois = 12 or AD.mois = 11)) 
						or (AD.annee = " . ($info_deb[$key][$key2][4]+1) ." 
						and AD.mois = 1))";
						}
					else	{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and AD.annee = " . $info_deb[$key][$key2][4] ." 
						and ( AD.mois = " . (($info_deb[$key][$key2][3])-1) ." 
						or AD.mois = " . $info_deb[$key][$key2][3] ." 
						or AD.mois = " . (($info_deb[$key][$key2][3])+1) .")"; 
						}
print_debug($query2);
					$result2 = pg_query($connection, $query2);
					//pg_close();

					$nb = pg_num_rows($result2);
					if ($nb == 0){$query2 = "select id, art_debarquement_id from art_fraction limit 1";
					//print "query2 ==".$query2."<br/>";

					 $result2 = pg_query($connection, $query2); 
					//pg_close();
					}

					while($row2 = pg_fetch_row($result2))
						{

						$nb = pg_num_rows($result2);	//nb de fractions concern�es
						$frac_concern�es = $row2[0];
						$deb_concern� = $row2[1];


						if ($nb >= 5)
							{
							$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
							$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

							$WdftI += $Wdft;
							$NdftI += $Ndft;

							$Wm = ($WdftI / $NdftI);
							$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
							$info_deb[$key][$key2][9] = $Nfdbq; 
							}

						else	{			//strate SE
							$val1 =$info_deb[$key][$key2][4]+1;
							$valm1 =$info_deb[$key][$key2][4]-1;
							
							//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
							pg_free_result($result2);
							
							if ($info_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
								or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								
								}
							if ($info_deb[$key][$key2][3] == 2)   //si mois 2
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								
								}
							if ($info_deb[$key][$key2][3] == 3)   //si mois 3
								{
								$$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9)) 
								or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 4)   //si mois 4
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 5)   //si mois 5
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 6)   //si mois 6
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $valm1 ." and AD.mois =12))";
								}
							if ($info_deb[$key][$key2][3] == 7)   //si mois 7
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and AD.mois =1))";
								}
							if ($info_deb[$key][$key2][3] == 8)   //si mois 8
								{
								$val1 =$info_deb[$key][$key2][4]+1;
								
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
								or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
								or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
								or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
								
								
								}
							if ($info_deb[$key][$key2][3] == 9)   //si mois 9
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3)))";
								}
							if ($info_deb[$key][$key2][3] == 10)   //si mois 10
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4)))";
								}
							if ($info_deb[$key][$key2][3] == 11)   //si mois 11
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
								}
							if ($info_deb[$key][$key2][3] == 12)   //si mois 12
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
								}

								$row3 = Array();
								print_debug($query3);

								$result3 = pg_query($connection, $query3);
								//pg_close();

								$nb = pg_num_rows($result3);
								if ($nb == 0){$query3 = "select id, art_debarquement_id from art_fraction limit 1";
								print_debug($query3);
								 $result3 = pg_query($connection, $query3); //pg_close();
								}

								while($row3 = pg_fetch_row($result3))
									{

									$nb = pg_num_rows($result3);	//nb de fractions concern�es
									$frac_concern�es = $row3[0];
									$deb_concern� = $row3[1];
									if ($nb >= 5)
										{
										$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
										$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

										$WdftI += $Wdft;
										$NdftI += $Ndft;

										$Wm = ($WdftI / $NdftI);
										$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
										$info_deb[$key][$key2][9] = $Nfdbq; 
										}
									else	{
										pg_free_result($result3);
										//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										
										//strate E
										$query4 = "select distinct AF.id, AD.id 
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
										print_debug("ligne 729=".$query4);
										$result4 = pg_query($connection, $query4);
										//pg_close();

										$nb = pg_num_rows($result4);
										if ($nb == 0){$query4 = "select id, art_debarquement_id from art_fraction limit 1";
										//print "query4 ===".$query4."<br/>";
										 $result4 = pg_query($connection, $query4); //pg_close();
										}

										while($row4 = pg_fetch_row($result4))
											{

											$nb = pg_num_rows($result4);	//nb de fractions concern�es
											$frac_concern�es = $row4[0];
											$deb_concern� = $row4[1];

											if ($nb >= 5)
												{
												$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
												$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

												$WdftI += $Wdft;
												$NdftI += $Ndft;

												$Wm = ($WdftI / $NdftI);
												$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
												$info_deb[$key][$key2][9] = $Nfdbq; 
												}

											else	{
												
												//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
												pg_free_result($result4);
												//strate E+
												$query5 = "select distinct AF.id, AD.id 
												from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
												, art_poisson_mesure as APM 
												where AD.id = AF.art_debarquement_id 
												and APM.art_fraction_id = AF.id 
												and AD.art_agglomeration_id = AA.id 
												and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
												and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
												and AF.debarquee = 1 
												and AF.id != '" . $key2 ."'"; 
print_debug($query5);
												$result5 = pg_query($connection, $query5);
												//pg_close();

												$nb = pg_num_rows($result5);
												if ($nb == 0){$query5 = "select id, art_debarquement_id from art_fraction limit 1";
												//print "query5 ===".$query5."<br/>";
												 $result5 = pg_query($connection, $query5); //pg_close();
												}

												while($row5 = pg_fetch_row($result5))
													{
													$nb = pg_num_rows($result5);	//nb de fractions concern�es
													$frac_concern�es = $row5[0];
													$deb_concern� = $row5[1];

													if ($nb >= 5)
														{		
														$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
														$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];
	
														$WdftI += $Wdft;
														$NdftI += $Ndft;
	
														$Wm = ($WdftI / $NdftI);
														$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
														$info_deb[$key][$key2][9] = $Nfdbq; 
														}
													
													else	{	//absence structure de taille ds le secteur
														//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
														pg_free_result($result5);//19 09
														//strate STE 
														$query6 = "select AF.id, AD.id 
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
														print_debug("ligne 820=".$query6);
														$result6 = pg_query($connection, $query6);
														//pg_close();
														
														$Wm_i = 0;
														$Wm = 0;

														$nb = pg_num_rows($result6);
														if ($nb == 0){pg_free_result($result6);
														$query6 = "select id, art_debarquement_id from art_fraction limit 1";
														print_debug($query6);
														 $result6 = pg_query($connection, $query6); //pg_close();
														}

														while($row6 = pg_fetch_row($result6))
															{
											
															$nb = pg_num_rows($result6);	//nb de fractions concern�es
															
															$nb_enlev = 0;
															
															if ($nb >= 5)
																{	//Wfdbq et Nfdbq doivent etre positif
															$frac_concern�es = $row6[0];
															$deb_concern� = $row6[1];
															
																$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																
																if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																else	{
																	$Wm_i = $Wfdbq / $Nfdbq ;
																	$Wm += $Wm_i / ($nb-$nb_enlev);
											
																
																	$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																	$info_deb[$key][$key2][9] = $Nfdbq; 
																	}
																}
															
															else	{	//strate STE+
																//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);

																if ($info_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
																	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and AF.id != '" . $key2 ."' 
																	and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																	and (AD.mois = 1 or AD.mois = 2)) 
																	or (AD.annee = " . ($info_deb[$key][$key2][4]-1) ." 
																	and AD.mois = 12))";
																	}
																elseif ($info_deb[$key][$key2][3] == 12)   //si mois 12
																	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.id != '" . $key2 ."' 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																	and (AD.mois = 12 or AD.mois = 11)) 
																	or (AD.annee = " . ($info_deb[$key][$key2][4]+1) ." 
																	and AD.mois = 1))";
																	}
																else	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and AF.id != '" . $key2 ."' 
																	and AD.annee = " . $info_deb[$key][$key2][4] ." 
																	and ( AD.mois = " . (($info_deb[$key][$key2][3])-1) ." 
																	or AD.mois = " . $info_deb[$key][$key2][3] ." 
																	or AD.mois = " . (($info_deb[$key][$key2][3])+1) .")"; 
																	
																	}
															print_debug($query27);
																	$result7 = pg_query($connection, $query7);
																	//pg_close();

																$nb = pg_num_rows($result7);
																if ($nb == 0){$query7 = "select id, art_debarquement_id from art_fraction limit 1";
															print_debug($query7);

																 $result7 = pg_query($connection, $query7); //pg_close();
																}

																while($row7 = pg_fetch_row($result7))
																	{
											
																	$nb = pg_num_rows($result7);	//nb de fractions concern�es
																	$frac_concern�es = $row7[0];
																	$deb_concern� = $row7[1];
											
											
																	if ($nb >= 5)
																		{
																		$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																		$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																		
																		if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																		else	{
																			$Wm_i = $Wfdbq / $Nfdbq ;
																			$Wm += $Wm_i / ($nb-$nb_enlev);
													
																		
																			$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																			$info_deb[$key][$key2][9] = $Nfdbq; 
																			}
																		}
																	else	{	//strate SE
																		//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										
																		if ($info_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
																			AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 2)   //si mois 2
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
																			AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 3)   //si mois 3
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 

																			or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 4)   //si mois 4
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =10 
																			or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 5)   //si mois 5
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 6)   //si mois 6
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $valm1 ." and AD.mois =12))";
																			}
																		if ($info_deb[$key][$key2][3] == 7)   //si mois 7
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and AD.mois =1))";
																			}
																		if ($info_deb[$key][$key2][3] == 8)   //si mois 8
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
																			or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
																			or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
																			or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
																			}
																		if ($info_deb[$key][$key2][3] == 9)   //si mois 9
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3)))";
																			}
																		if ($info_deb[$key][$key2][3] == 10)   //si mois 10
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4)))";
																			}
																		if ($info_deb[$key][$key2][3] == 11)   //si mois 11
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
																			}
																		if ($info_deb[$key][$key2][3] == 12)   //si mois 12
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and AF.id != '" . $key2 ."' 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
																			}
																			print_debug($query8);
																			$result8 = pg_query($connection, $query8);
																			//pg_close();

																		$nb = pg_num_rows($result8);
																		if ($nb == 0){$query8 = "select id, art_debarquement_id from art_fraction limit 1";
																		print_debug($query8);
																		$result8 = pg_query($connection, $query8); //pg_close();
																		}

																		while($row8 = pg_fetch_row($result8))
																			{
													
																			$nb = pg_num_rows($result8);	//nb de fractions concern�es
																			$frac_concern�es = $row8[0];
																			$deb_concern� = $row8[1];
															
															
																			if ($nb >= 5)
																				{
																				$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																				$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																				
																				if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																				else	{
																					$Wm_i = $Wfdbq / $Nfdbq ;
																					$Wm += $Wm_i / ($nb-$nb_enlev);
																
																					
																					$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																					$info_deb[$key][$key2][9] = $Nfdbq; 
																					}
																				}
																			else	{
																				//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																				//strate E
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
										print_debug($query9);
																				$result9 = pg_query($connection, $query9);
																				//pg_close();

																				$nb = pg_num_rows($result9);
																				if ($nb == 0){$query9 = "select id, art_debarquement_id from art_fraction limit 1";
																				//print "query9 ===".$query9."<br/>";
																				 $result9 = pg_query($connection, $query9); //pg_close();
																				}

																				while($row9 = pg_fetch_row($result9))
																					{
										
																					$nb = pg_num_rows($result9);	//nb de fractions concern�es
																					$frac_concern�es = $row9[0];
																					$deb_concern� = $row9[1];

																					if ($nb >= 5)
																						{
																						$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																						$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																						
																						if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																						else	{
																							$Wm_i = $Wfdbq / $Nfdbq ;
																							$Wm += $Wm_i / ($nb-$nb_enlev);
																		
																								
																							$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																							$info_deb[$key][$key2][9] = $Nfdbq; 
																							}
																						}
																						
																					else	{
																						//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																						//strate E+
																						$query10 = "select AF.id, AD.id 
																						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																						where AD.id = AF.art_debarquement_id 
																						and AD.art_agglomeration_id = AA.id 
																						and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																						and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																						and AF.debarquee = 1 
																						and AF.poids != 0 
																						and AF.nbre_poissons != 0 
																						and AF.id != '" . $key2 ."'"; 
											print_debug($query10);
																						$result10 = pg_query($connection, $query10);
																						//pg_close();

																						$nb = pg_num_rows($result10);
																						if ($nb == 0){$query10 = "select id, art_debarquement_id from art_fraction limit 1";
																					print_debug($query10);
																						 $result10 = pg_query($connection, $query10); //pg_close();
																						}

																						while($row10 = pg_fetch_row($result10))
																							{
																							$nb = pg_num_rows($result10);	//nb de fractions concern�es
																							$frac_concern�es = $row5[0];
																							$deb_concern� = $row5[1];
																							
																							
																							if ($nb >= 5)
																								{
																								$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																								$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																											
																								if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																								else	{
																									$Wm_i = $Wfdbq / $Nfdbq ;
																									$Wm += $Wm_i / ($nb-$nb_enlev);
																					
																											
																									$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																									$info_deb[$key][$key2][9] = $Nfdbq; 
																									}
																								}
																							else
																								{
																								if ($info_deb[$key][$key2][7]=='PDU')$Wm = 10;
																								elseif ($info_deb[$key][$key2][7]=='SEP')$Wm = 125;
																								elseif ($info_deb[$key][$key2][7]=='CAL')$Wm = 40;
																								elseif ($info_deb[$key][$key2][7]=='CAA')$Wm = 40;
																								elseif ($info_deb[$key][$key2][7]=='CMB')$Wm = 600;
																								elseif ($info_deb[$key][$key2][7]=='OVU')$Wm = 125;
																								
																								else break;//on laisse la valeur � 0
																								
																								$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																								$info_deb[$key][$key2][9] = $Nfdbq;
																								break;
																								}
																							} //fin du while($row10 =
																							break;
																						}			
																					} //fin du while($row9 =
																					break;
																				}
																			} //fin du while($row8 =
																			break;	
																		}
																	} //fin du while($row7 =
																	break;	
																}
															} //fin du while($row6 =
															break;	
														}
													} //fin du while($row5 =
													break;
												}
										}// fin du while($row4 =
										break;
									} 
								}// fin du while($row3 =
								break;
							}
						}//fin du while($row2...
						break;
					}//fin du else
				
				}// fin du while ($row =

			}//fin du elseif

		//////////////////////////////////////////
		//               cas n�4                //
		//  Wfdbq =0  , Nfdbq > 0, pas de DFT   //
		//////////////////////////////////////////
		
		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && ($Nfdbq>0) && (($Ndft == 0)||($Ndft == "")) )
			{
			//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) {  echo "Non connect�"; exit;}
			$query = "select distinct AF.id, AD.id 
				from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, 
				 art_poisson_mesure as APM 
				where AD.id = AF.art_debarquement_id 
				and APM.art_fraction_id = AF.id 
				and AD.art_agglomeration_id = AA.id 
				and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
				and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
				and AD.mois = " . $info_deb[$key][$key2][3] ." 
				and AD.annee = " . $info_deb[$key][$key2][4] ." 
				and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
				and AF.debarquee = 1 
				and AF.id != '" . $key2 ."'";
print_debug($query);
			$result = pg_query($connection, $query);
			//pg_close();

			$WdftI = 0;
			$NdftI = 0;
			
			//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
			$nb = pg_num_rows($result);
			
			if ($nb == 0){$query = "select id, art_debarquement_id from art_fraction limit 1";
			print_debug($query);
			 $result = pg_query($connection, $query); //pg_close();
			
			}


			while($row = pg_fetch_row($result))
				{
				$nb = pg_num_rows($result);	//nb de fractions concern�es
				$frac_concern�es = $row[0];
				$deb_concern� = $row[1];

				if ($nb >= 5)
					{
					$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
					$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

					$WdftI += $Wdft;
					$NdftI += $Ndft;

					$Wm = ($WdftI / $NdftI);
					$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
					$info_deb[$key][$key2][8] = $Wfdbq;
					}

				else	{			//strate STE+
					//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
					pg_free_result($result);//19 09
					if ($info_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, 
						 art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
						and (AD.mois = 1 or AD.mois = 2)) 
						or (AD.annee = " . ($info_deb[$key][$key2][4]-1) ." 
						and AD.mois = 12))";
						}
					elseif ($info_deb[$key][$key2][3] == 12)   //si mois 12
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
						and (AD.mois = 12 or AD.mois = 11)) 
						or (AD.annee = " . ($info_deb[$key][$key2][4]+1) ." 
						and AD.mois = 1))";
						}
					else	{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and AD.annee = " . $info_deb[$key][$key2][4] ." 
						and ( AD.mois = " . (($info_deb[$key][$key2][3])-1) ." 
						or AD.mois = " . $info_deb[$key][$key2][3] ." 
						or AD.mois = " . (($info_deb[$key][$key2][3])+1) .")"; 
						}
						print_debug($query2);
					$result2 = pg_query($connection, $query2);
					//pg_close();
							
					//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
					$nb = pg_num_rows($result2);
					if ($nb == 0){$query2 = "select id, art_debarquement_id from art_fraction limit 1";
					print_debug($query2);
					 $result2 = pg_query($connection, $query2); //pg_close();
					}
					
					
					while($row2 = pg_fetch_row($result2))
						{
						$nb = pg_num_rows($result2);	//nb de fractions concern�es
						$frac_concern�es = $row2[0];
						$deb_concern� = $row2[1];

						if ($nb >= 5)
							{
							$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
							$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

							$WdftI += $Wdft;
							$NdftI += $Ndft;

							$Wm = ($WdftI / $NdftI);
							$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
							$info_deb[$key][$key2][8] = $Wfdbq;
							}

						else	{			//strate SE
							$val1 =$info_deb[$key][$key2][4]+1;
							$valm1 =$info_deb[$key][$key2][4]-1;

							//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
							pg_free_result($result2);
							if ($info_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
								or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 2)   //si mois 2
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 3)   //si mois 3
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9)) 
								or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 4)   //si mois 4
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 5)   //si mois 5
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 6)   //si mois 6
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $valm1 ." and AD.mois =12))";
								}
							if ($info_deb[$key][$key2][3] == 7)   //si mois 7
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and AD.mois =1))";
								}
							if ($info_deb[$key][$key2][3] == 8)   //si mois 8
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
								or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
								or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
								or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
								}
							if ($info_deb[$key][$key2][3] == 9)   //si mois 9
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3)))";
								}
							if ($info_deb[$key][$key2][3] == 10)   //si mois 10
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4)))";
								}
							if ($info_deb[$key][$key2][3] == 11)   //si mois 11
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
								}
							if ($info_deb[$key][$key2][3] == 12)   //si mois 12
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
								and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
								}
								$result3 = pg_query($connection, $query3);
								print_debug($query3);
								//pg_close();

								//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
								$nb = pg_num_rows($result3);
								if ($nb == 0){$query3 = "select id, art_debarquement_id from art_fraction limit 1";
								print_debug($query3);
								 $result3 = pg_query($connection, $query3); //pg_close();
								}


								while($row3 = pg_fetch_row($result3))
									{
									$nb = pg_num_rows($result3);	//nb de fractions concern�es
									$frac_concern�es = $row3[0];
									$deb_concern� = $row3[1];
									if ($nb >= 5) 
										{
										$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
										$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

										$WdftI += $Wdft;
										$NdftI += $Ndft;

										$Wm = ($WdftI / $NdftI);
										$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
										$info_deb[$key][$key2][8] = $Wfdbq;
										}
									else	{
										//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										pg_free_result($result3);//19 09
										//strate E
										$query4 = "select distinct AF.id, AD.id 
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
print_debug($query4);
										$result4 = pg_query($connection, $query4);
										//pg_close();

										//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
										$nb = pg_num_rows($result4);
										if ($nb == 0){$query4 = "select id, art_debarquement_id from art_fraction limit 1";
										print_debug($query4);
										 $result4 = pg_query($connection, $query4); //pg_close();
										}



										while($row4 = pg_fetch_row($result4))
											{
											$nb = pg_num_rows($result4);	//nb de fractions concern�es
											$frac_concern�es = $row4[0];
											$deb_concern� = $row4[1];

											if ($nb >= 5)
												{
												$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
												$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

												$WdftI += $Wdft;
												$NdftI += $Ndft;

												$Wm = ($WdftI / $NdftI);
												$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
												$info_deb[$key][$key2][8] = $Wfdbq;
												}

											else	{
												//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
												pg_free_result($result4);
												//strate E+
												$query5 = "select distinct AF.id, AD.id 
												from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
												, art_poisson_mesure as APM 
												where AD.id = AF.art_debarquement_id 
												and APM.art_fraction_id = AF.id 
												and AD.art_agglomeration_id = AA.id 
												and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
												and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
												and AF.debarquee = 1 
												and AF.id != '" . $key2 ."'"; 
												print_debug("ligne 1834=".$query5);
												$result5 = pg_query($connection, $query5);
												//pg_close();

												//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
												$nb = pg_num_rows($result5);
												if ($nb == 0){$query5 = "select id, art_debarquement_id from art_fraction limit 1";
												print_debug($query5);

												 $result5 = pg_query($connection, $query5); //pg_close();
												}


												while($row5 = pg_fetch_row($result5))
													{
													$nb = pg_num_rows($result5);	//nb de fractions concern�es
													$frac_concern�es = $row5[0];
													$deb_concern� = $row5[1];

													if ($nb >= 5)
														{
														$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
														$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

														$WdftI += $Wdft;
														$NdftI += $Ndft;

														$Wm = ($WdftI / $NdftI);
														$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
														$info_deb[$key][$key2][8] = $Wfdbq;
														}
													
													else	{
													
													//absence structure de taille ds le secteur
														//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
														pg_free_result($result5);
														//strate STE 
														$query6 = "select AF.id, AD.id 
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
													print_debug($query6);
														$result6 = pg_query($connection, $query6);
														//pg_close();
														
														$Wm_i = 0;
														$Wm = 0;
														//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
														$nb = pg_num_rows($result6);
														if ($nb == 0){$query6 = "select id, art_debarquement_id from art_fraction limit 1";
														print_debug($query6);

														 $result6 = pg_query($connection, $query6); //pg_close();
														}


														while($row6 = pg_fetch_row($result6))
															{
											
															$nb = pg_num_rows($result6);	//nb de fractions concern�es
															$frac_concern�es = $row6[0];
															$deb_concern� = $row6[1];
															$nb_enlev = 0;
															
															if ($nb >= 5)
																{	//Wfdbq et Nfdbq doivent etre positif
															
															
																$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																
																if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																else	{
																	$Wm_i = $Wfdbq / $Nfdbq ;
																	$Wm += $Wm_i / ($nb-$nb_enlev);
											
																
																	$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																	$info_deb[$key][$key2][8] = $Wfdbq;
																	
																	}
																}
															
															else	{	//strate STE+
																//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);

																if ($info_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
																	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and AF.id != '" . $key2 ."' 
																	and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																	and (AD.mois = 1 or AD.mois = 2)) 
																	or (AD.annee = " . ($info_deb[$key][$key2][4]-1) ." 
																	and AD.mois = 12))";
																	}
																elseif ($info_deb[$key][$key2][3] == 12)   //si mois 12
																	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.id != '" . $key2 ."' 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																	and (AD.mois = 12 or AD.mois = 11)) 
																	or (AD.annee = " . ($info_deb[$key][$key2][4]+1) ." 
																	and AD.mois = 1))";
																	}
																else	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and AF.id != '" . $key2 ."' 
																	and AD.annee = " . $info_deb[$key][$key2][4] ." 
																	and ( AD.mois = " . (($info_deb[$key][$key2][3])-1) ." 
																	or AD.mois = " . $info_deb[$key][$key2][3] ." 
																	or AD.mois = " . (($info_deb[$key][$key2][3])+1) .")"; 
																	}
															print_debug($query7);

																$result7 = pg_query($connection, $query7);
																//pg_close();

																//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
																$nb = pg_num_rows($result7);
																if ($nb == 0){$query7 = "select id, art_debarquement_id from art_fraction limit 1";
																
																print_debug($query7);
																 $result7 = pg_query($connection, $query7); //pg_close();
																}


																while($row7 = pg_fetch_row($result7))
																	{
											
																	$nb = pg_num_rows($result7);	//nb de fractions concern�es
																	$frac_concern�es = $row7[0];
																	$deb_concern� = $row7[1];
											
											
																	if ($nb >= 5)
																		{
																		$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																		$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																		
																		if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																		else	{
																			$Wm_i = $Wfdbq / $Nfdbq ;
																			$Wm += $Wm_i / ($nb-$nb_enlev);
													
																			$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																			$info_deb[$key][$key2][8] = $Wfdbq;
																			}
																		}
																	else	{	//strate SE
																		//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										
																		if ($info_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
																			AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 2)   //si mois 2
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 

																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
																			AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 3)   //si mois 3
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
																			or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 4)   //si mois 4
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =10 
																			or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 5)   //si mois 5
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_deb[$key][$key2][3] == 6)   //si mois 6
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $valm1 ." and AD.mois =12))";
																			}
																		if ($info_deb[$key][$key2][3] == 7)   //si mois 7
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and AD.mois =1))";
																			}
																		if ($info_deb[$key][$key2][3] == 8)   //si mois 8
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
																			or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
																			or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
																			or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
																			}
																		if ($info_deb[$key][$key2][3] == 9)   //si mois 9
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3)))";
																			}
																		if ($info_deb[$key][$key2][3] == 10)   //si mois 10
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4)))";
																			}
																		if ($info_deb[$key][$key2][3] == 11)   //si mois 11
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
																			}
																		if ($info_deb[$key][$key2][3] == 12)   //si mois 12
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and AF.id != '" . $key2 ."' 
																			and ((AD.annee = " . $info_deb[$key][$key2][4] ." 
																			and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
																			}
																			print_debug($query8);
																			$result8 = pg_query($connection, $query8);
																			//pg_close();

																		//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
																		$nb = pg_num_rows($result8);
																		if ($nb == 0){$query8 = "select id, art_debarquement_id from art_fraction limit 1";
																		print_debug($query8);
																		$result8 = pg_query($connection, $query8); //pg_close();
																		}


																		while($row8 = pg_fetch_row($result8))
																			{
													
																			$nb = pg_num_rows($result8);	//nb de fractions concern�es
																			$frac_concern�es = $row8[0];
																			$deb_concern� = $row8[1];
															
															
																			if ($nb >= 5)
																				{
																				$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																				$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																				
																				if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																				else	{
																					$Wm_i = $Wfdbq / $Nfdbq ;
																					$Wm += $Wm_i / ($nb-$nb_enlev);
																
																					$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																					$info_deb[$key][$key2][8] = $Wfdbq;	
																					
																					}
																				}
																			else	{
																			//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																				//strate E
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
										print_debug($query9);
																				$result9 = pg_query($connection, $query9);
																				//pg_close();

																				//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
																				$nb = pg_num_rows($result9);
																				if ($nb == 0){$query9 = "select id, art_debarquement_id from art_fraction limit 1";
																				
																				print_debug($query9);
																				 $result9 = pg_query($connection, $query9); //pg_close();
																				}


																				while($row9 = pg_fetch_row($result9))
																					{
										
																					$nb = pg_num_rows($result9);	//nb de fractions concern�es
																					$frac_concern�es = $row9[0];
																					$deb_concern� = $row9[1];

																					if ($nb >= 5)
																						{
																						$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																						$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																								
																						if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																						else	{
																							$Wm_i = $Wfdbq / $Nfdbq ;
																							$Wm += $Wm_i / ($nb-$nb_enlev);
																		
																								
																							$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																							$info_deb[$key][$key2][8] = $Wfdbq; 
																							}
																						}
																						
																					else	{
																					//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																						//strate E+
																						$query10 = "select AF.id, AD.id 
																						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																						where AD.id = AF.art_debarquement_id 
																						and AD.art_agglomeration_id = AA.id 
																						and AF.ref_espece_id = '" . $info_deb[$key][$key2][7] ."' 
																						and AD.art_grand_type_engin_id = '" . $info_deb[$key][$key2][6]."' 
																						and AF.debarquee = 1 
																						and AF.poids != 0 
																						and AF.nbre_poissons != 0 
																						and AF.id != '" . $key2 ."'"; 
											print_debug($query10);
																						$result10 = pg_query($connection, $query10);
																						//pg_close();

																						//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
																						$nb = pg_num_rows($result10);
																						if ($nb == 0){$query10 = "select id, art_debarquement_id from art_fraction limit 1";
																						print_debug($query10);
																						 $result10 = pg_query($connection, $query10); //pg_close();
																						}


																						while($row10 = pg_fetch_row($result10))
																							{
																							$nb = pg_num_rows($result10);	//nb de fractions concern�es
																							$frac_concern�es = $row5[0];
																							$deb_concern� = $row5[1];
																							
																							
																							if ($nb >= 5)
																								{
																								$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																								$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																											
																								if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																								else	{
																									$Wm_i = $Wfdbq / $Nfdbq ;
																									$Wm += $Wm_i / ($nb-$nb_enlev);
																					
																											
																									$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																									$info_deb[$key][$key2][8] = $Wfdbq;
																									}
																								}
																							else
																								{
																								if ($info_deb[$key][$key2][7]=='PDU')$Wm = 10;
																								elseif ($info_deb[$key][$key2][7]=='SEP')$Wm = 125;
																								elseif ($info_deb[$key][$key2][7]=='CAL')$Wm = 40;
																								elseif ($info_deb[$key][$key2][7]=='CAA')$Wm = 40;
																								elseif ($info_deb[$key][$key2][7]=='CMB')$Wm = 600;
																								elseif ($info_deb[$key][$key2][7]=='OVU')$Wm = 125;
																								
																								else break;//on laisse la valeur � 0
																								
																								$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																								$info_deb[$key][$key2][8] = $Wfdbq;
																								break;
																								}
																							} //fin du while($row10 =
																							break;
																						}
																					} //fin du while($row9 =
																				
																					break;
																				}
																			} //fin du while($row8 =
																			break;	
																		}
																	} //fin du while($row7 =
																	break;	
																}
															} //fin du while($row6 =
															break;	
														}
													} //fin du while($row5 =
													break;
												}
										}// fin du while($row4 =
										break;
									} 
								}// fin du while($row3 =
								break;
							}
						}//fin du while($row2...
						break;
					}//fin du else

				}// fin du while ($row =

			} //fin du elseif






		//////////////////////////////////////////
		//               cas n�5                //
		//     Wfdbq =0  , Nfdbq = 0, DFT       //
		//////////////////////////////////////////
		
		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && (($Nfdbq == 0)||($Nfdbq == "")) && ($Ndft>0) )
			{
			//print ("<br>cas 5 :".$key2. "Ndft= ".$Ndft. "Pdft= ".$Wdft);
			//print ("<br>esp :".$info_deb[$key][$key2][7]);
			//print ("<br>k :".$coef_esp[CNI][0]." b=".$coef_esp[CNI][1]);
			$Nfdbq = $Ndft; 
			$Wfdbq = $Wdft/1000;
			$info_deb[$key][$key2][8] = round ($Wfdbq, 2);
			$info_deb[$key][$key2][9] = $Nfdbq; 

			//print ("<br>cas 5 Wfdbq =".$Wfdbq." , Nfdbq =".$Nfdbq);


			} //fin du elseif

		//////////////////////////////////////////
		//          cas n�6 et 7                //
		//        Wfdbq >0  et Nfdbq > 0        //
		//////////////////////////////////////////

		elseif ( ($Wfdbq >0) && ($Nfdbq > 0) )
			{

			//print ("<br>cas 6 et 7 Wfdbq =".$Wfdbq." , Nfdbq =".$Nfdbq);

			} //fin du elseif

		//////////////////////////////////////////
		//              cas n�8                 //
		//    Wfdbq =0, Nfdbq=0, pas de DFT     //
		//////////////////////////////////////////

		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && (($Nfdbq == 0)||($Nfdbq == "")) && (($Ndft == 0)||($Ndft == "")) )
			{
			unset($info_deb[$key][$key2]);
			} //fin du elseif
		}
	}






////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                //
//                              COMPARAISON ENTRE LE POIDS TOTAL                                  //
//                             DE L'ENQUETE ET LA SOMME DES WFdbq                                 //
//                                                                                                //
////////////////////////////////////////////////////////////////////////////////////////////////////

reset($info_deb);
while (list($key, $val) = each($info_deb))                      //pour tous les debarquements
	{
	$WfdbqI =0;

	while (list($key2, $val2) = each($val))			//pour chaque fraction
		{
		$Wt = $info_deb[$key][$key2][5];            //poid total du d�barquement
		$Wfdbq = $info_deb[$key][$key2][8];
		$WfdbqI += $Wfdbq;			    //somme des poids des fractions
		}
	reset($val);

	//if ($Wt == 0){ $info_deb[$key][$key2][5] = round($WfdbqI,2);}	//Wt = somme(Wfdbq)
	//05/11
	if ($Wt == 0)
		{ 
		reset($val);
		while (list($key2, $val2) = each($val))			//pour chaque fraction
			{
			$info_deb[$key][$key2][5] = round($WfdbqI,2);
			}
		//$Wt=$info_deb[$key][$key2][5];
		}	//Wt = somme(Wfdbq)

	else
		{

		$rapport= round(($WfdbqI / $Wt),2);
	
		//cas (somme Wfdbq / Wt) <0.95 :
		if ($rapport < 0.95000)
			{
			reset($val);
			while (list($key3, $val3) = each($val))			//pour chaque fraction
				{
				$Wfdbq = $info_deb[$key][$key3][8];
				$Wfdbq = $Wfdbq * ($Wt/$WfdbqI);
				$info_deb[$key][$key3][8] = round($Wfdbq,2);
	
				$Nfdbq = $info_deb[$key][$key3][9];
				$Nfdbq = $Nfdbq * ($Wt/$WfdbqI);
				$info_deb[$key][$key3][9] = round($Nfdbq,0);
				
				}
			}
		//cas (somme Wfdbq / Wt) >= 0.95 et < 1.05:
		if (($rapport  >= 0.949999) && ($rapport  < 1.049999))
			{	
			while (list($key3, $val3) = each($val))			//pour chaque fraction
				{
				$info_deb[$key][$key3][5] = round($WfdbqI,2);		//Wt = somme(Wfdbq)
				}
			}
	
		//cas (somme Wfdbq / Wt) >= 1.05 et < 2:
		if (($rapport  >= 1.050000) && ($rapport  < 1.99999))
			{	
			while (list($key3, $val3) = each($val))			//pour chaque fraction
				{
				$info_deb[$key][$key3][5] = round($WfdbqI,2);		//Wt = somme(Wfdbq)
				}
			}	
	
		//cas (somme Wfdbq / Wt) >= 2:
		if ($rapport  >= 2.00000)
			{
			while (list($key3, $val3) = each($val))			//pour chaque fraction
				{
				unset($info_deb[$key][$key3]);
				}
			}
		}//fin du else Wt=0
	}



////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                //
//                            TRAITEMENT DES FRACTIONS NON DEBARQUEES                             //
//                                           Fndbq                                                //
//                                                                                                //
////////////////////////////////////////////////////////////////////////////////////////////////////

//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) {  echo "Non connect�"; exit;}

$query = "select AD.id, RF.ref_pays_id, RS.nom, AA.nom, AD.mois, AD.annee, AD.poids_total,
	AD.art_grand_type_engin_id, AF.ref_espece_id, AF.poids, AF.nbre_poissons, AF.id 
	from ref_systeme as RF, ref_secteur as RS, art_agglomeration as AA, art_debarquement as AD,
	art_fraction as AF 
	where RS.ref_systeme_id = RF.id 
	and AA.ref_secteur_id = RS.id 
	and AD.art_agglomeration_id = AA.id 
	and AD.id = AF.art_debarquement_id 
	and AF.debarquee != 1 
	order by AD.id";
print_debug($query);
$result = pg_query($connection, $query);
$info_non_deb=array();
while($row = pg_fetch_row($result)){
	$cl� = $row[0];                                //cle = identifiant du d�barquement
	$cle2 = $row[11];                              //cle2 = identifiant de la fraction
	
	$info_non_deb[$cl�][$cle2][0] = $row[1];           //pays
	$info_non_deb[$cl�][$cle2][1] = $row[2];           //secteur
	$info_non_deb[$cl�][$cle2][2] = $row[3];           //agglomeration
	$info_non_deb[$cl�][$cle2][3] = $row[4];           //mois
	$info_non_deb[$cl�][$cle2][4] = $row[5];           //ann�e
	$info_non_deb[$cl�][$cle2][5] = $row[6];           //poid total du d�barquement
	$info_non_deb[$cl�][$cle2][6] = $row[7];           //engin de peche
	$info_non_deb[$cl�][$cle2][7] = $row[8];           //espece p�ch�e = espece de la fraction
	$info_non_deb[$cl�][$cle2][8] = $row[9];           //poid de la fraction = Wfdbq
	$info_non_deb[$cl�][$cle2][9] = $row[10];          //nombre poisson de la fraction = Nfdbq        
	}

//pg_close();

//remise � z�ro du pointeur
reset($info_non_deb);

while (list($key, $val) = each($info_non_deb))                      //pour tous les debarquements
	{
	while (list($key2, $val2) = each($val))		  		//pour chaque fraction
		{
		$Wfdbq = $info_non_deb[$key][$key2][8];
		$Nfdbq = $info_non_deb[$key][$key2][9];


		//////////////////////////////////////////
		//               cas n�3                //
		//         Wfdbq >0  , Nfdbq = 0        //
		//////////////////////////////////////////

		if ( ($Wfdbq>0) && (($Nfdbq == 0)||($Nfdbq == "")) )
			{
			//selection sur strate STE
			//(pour une espece:agglo, mois, annee et grand type identiques)
			//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			//if (!$connection) {  echo "Non connect�"; exit;}
			$query = "select distinct AF.id, AD.id 
				from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
				, art_poisson_mesure as APM 
				where AD.id = AF.art_debarquement_id 
				and APM.art_fraction_id = AF.id 
				and AD.art_agglomeration_id = AA.id 
				and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
				and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
				and AD.mois = " . $info_non_deb[$key][$key2][3] ." 
				and AD.annee = " . $info_non_deb[$key][$key2][4] ." 
				and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
				and AF.debarquee = 1 
				and AF.id != '" . $key2 ."'";
print_debug("ligne 2650=".$query);

			$result = pg_query($connection, $query);
			//pg_close();

			$WdftI = 0;
			$NdftI = 0;

			//si aucun resultat, on fait une nouvelle requete qui donne 1 seul resultat pour rentrer dans la boucle suivante
			$nb = pg_num_rows($result);
			if ($nb == 0){$query = "select id, art_debarquement_id from art_fraction limit 1";
			//print "query ===".$query."<br/>";

			 $result = pg_query($connection, $query); //pg_close();
			}


			while($row = pg_fetch_row($result))
				{
				
				$nb = pg_num_rows($result);	//nb de fractions concern�es
				$frac_concern�es = $row[0];
				$deb_concern� = $row[1];

				if ($nb >= 5)
					{
					$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
					$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

					$WdftI += $Wdft;
					$NdftI += $Ndft;

					$Wm = ($WdftI / $NdftI);

					$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
					$info_non_deb[$key][$key2][9] = $Nfdbq; 
					}

				else	{			//strate STE+
					//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);

					if ($info_non_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
						and (AD.mois = 1 or AD.mois = 2)) 
						or (AD.annee = " . ($info_non_deb[$key][$key2][4]-1) ." 
						and AD.mois = 12))";
						}
					elseif ($info_non_deb[$key][$key2][3] == 12)   //si mois 12
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
						and (AD.mois = 12 or AD.mois = 11)) 
						or (AD.annee = " . ($info_non_deb[$key][$key2][4]+1) ." 
						and AD.mois = 1))";
						}
					else	{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and AD.annee = " . $info_non_deb[$key][$key2][4] ." 
						and ( AD.mois = " . (($info_non_deb[$key][$key2][3])-1) ." 
						or AD.mois = " . $info_non_deb[$key][$key2][3] ." 
						or AD.mois = " . (($info_non_deb[$key][$key2][3])+1) .")"; 
						}
print_debug($query2);

					$result2 = pg_query($connection, $query2);
					//pg_close();

$nb = pg_num_rows($result2);
if ($nb == 0){$query2 = "select id, art_debarquement_id from art_fraction limit 1";
print_debug($query2);

$result2 = pg_query($connection, $query2); //pg_close();
}


					while($row2 = pg_fetch_row($result2))
						{

						$nb = pg_num_rows($result2);	//nb de fractions concern�es
						$frac_concern�es = $row2[0];
						$deb_concern� = $row2[1];


						if ($nb >= 5)
							{
							$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
							$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

							$WdftI += $Wdft;
							$NdftI += $Ndft;

							$Wm = ($WdftI / $NdftI);
							$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
							$info_non_deb[$key][$key2][9] = $Nfdbq; 
							}

						else	{			//strate SE

							$val1 =$info_non_deb[$key][$key2][4]+1;
							$valm1 =$info_non_deb[$key][$key2][4]-1;
							
							//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
							if ($info_non_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
								or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 2)   //si mois 2
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 3)   //si mois 3
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9)) 
								or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_deb[$key][$key2][3] == 4)   //si mois 4
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 5)   //si mois 5
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 6)   //si mois 6
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $valm1 ." and AD.mois =12))";
								}
							if ($info_non_deb[$key][$key2][3] == 7)   //si mois 7
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and AD.mois =1))";
								}
							if ($info_non_deb[$key][$key2][3] == 8)   //si mois 8
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
								or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
								or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
								or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
								}
							if ($info_non_deb[$key][$key2][3] == 9)   //si mois 9
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3)))";
								}
							if ($info_non_deb[$key][$key2][3] == 10)   //si mois 10
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4)))";
								}
							if ($info_non_deb[$key][$key2][3] == 11)   //si mois 11
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
								}
							if ($info_non_deb[$key][$key2][3] == 12)   //si mois 12
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
								}
								print_debug($query3);

								$result3 = pg_query($connection, $query3);
								//pg_close();

								$nb = pg_num_rows($result3);
								if ($nb == 0){$query3 = "select id, art_debarquement_id from art_fraction limit 1";
								print_debug($query3);

								 $result3 = pg_query($connection, $query3); //pg_close();
								}



								while($row3 = pg_fetch_row($result3))
									{

									$nb = pg_num_rows($result3);	//nb de fractions concern�es
									$frac_concern�es = $row3[0];
									$deb_concern� = $row3[1];
									if ($nb >= 5)
										{
										$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
										$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

										$WdftI += $Wdft;
										$NdftI += $Ndft;

										$Wm = ($WdftI / $NdftI);
										$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
										$info_non_deb[$key][$key2][9] = $Nfdbq;
										}
									else	{
										//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										//strate E
										$query4 = "select distinct AF.id, AD.id 
										from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, ref_secteur as RS 
										, art_poisson_mesure as APM 
										where AD.id = AF.art_debarquement_id 
										and APM.art_fraction_id = AF.id 
										and AD.art_agglomeration_id = AA.id 
										and AA.ref_secteur_id = RS.id 
										and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
										and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
										and RS.nom = '" . $info_non_deb[$key][$key2][1]."' 
										and AF.debarquee = 1 
										and AF.id != '" . $key2 ."'"; 

print_debug($query4);
										$result4 = pg_query($connection, $query4);
										//pg_close();

										$nb = pg_num_rows($result4);
										if ($nb == 0){$query4 = "select id, art_debarquement_id from art_fraction limit 1";
										print_debug($query4);

										 $result4 = pg_query($connection, $query4); //pg_close();
										}


										while($row4 = pg_fetch_row($result4))
											{

											$nb = pg_num_rows($result4);	//nb de fractions concern�es
											$frac_concern�es = $row4[0];
											$deb_concern� = $row4[1];

											if ($nb >= 5)
												{
												$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
												$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

												$WdftI += $Wdft;
												$NdftI += $Ndft;

												$Wm = ($WdftI / $NdftI);
												$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
												$info_non_deb[$key][$key2][9] = $Nfdbq; 
												}

											else	{
												//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
												//strate E+
												$query5 = "select distinct AF.id, AD.id 
												from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
												, art_poisson_mesure as APM 
												where AD.id = AF.art_debarquement_id 
												and APM.art_fraction_id = AF.id 
												and AD.art_agglomeration_id = AA.id 
												and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
												and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
												and AF.debarquee = 1 
												and AF.id != '" . $key2 ."'"; 
print_debug($query5);

												$result5 = pg_query($connection, $query5);
												//pg_close();

												$nb = pg_num_rows($result5);
												if ($nb == 0){$query5 = "select id, art_debarquement_id from art_fraction limit 1";
												print_debug($query5);

												 $result5 = pg_query($connection, $query5); //pg_close();
												}


												while($row5 = pg_fetch_row($result5))
													{
													$nb = pg_num_rows($result5);	//nb de fractions concern�es
													$frac_concern�es = $row5[0];
													$deb_concern� = $row5[1];
													
													
													if ($nb >= 5)
														{
														$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
														$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];
	
														$WdftI += $Wdft;
														$NdftI += $Ndft;
	
														$Wm = ($WdftI / $NdftI);
														$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
														$info_non_deb[$key][$key2][9] = $Nfdbq;
														}
													
													else	{	//absence structure de taille ds le secteur
													//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
														//strate STE 
														$query6 = "select AF.id, AD.id 
														from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
														where AD.id = AF.art_debarquement_id 
														and AD.art_agglomeration_id = AA.id 
														and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
														and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
														and AD.mois = " . $info_non_deb[$key][$key2][3] ." 
														and AD.annee = " . $info_non_deb[$key][$key2][4] ." 
														and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
														and AF.debarquee = 1 
														and AF.poids != 0 
														and AF.nbre_poissons != 0 
														and AF.id != '" . $key2 ."'";
														print_debug($query6);

														$result6 = pg_query($connection, $query6);
														//pg_close();
														
														$Wm_i = 0;
														$Wm = 0;

														$nb = pg_num_rows($result6);
														if ($nb == 0){$query6 = "select id, art_debarquement_id from art_fraction limit 1";
														print_debug($query6);

														 $result6 = pg_query($connection, $query6); //pg_close();
														}


														while($row6 = pg_fetch_row($result6))
															{
											
															$nb = pg_num_rows($result6);	//nb de fractions concern�es
															$frac_concern�es = $row6[0];
															$deb_concern� = $row6[1];
															$nb_enlev = 0;
															
															if ($nb >= 5)
																{	//Wfdbq et Nfdbq doivent etre positif
															
															
																$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																
																if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																else	{
																	$Wm_i = $Wfdbq / $Nfdbq ;
																	$Wm += $Wm_i / ($nb-$nb_enlev);
											
																
																	$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																	$info_non_deb[$key][$key2][9] = $Nfdbq; 
																	}
																}
															
															else	{	//strate STE+
																//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);

																if ($info_non_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
																	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and AF.id != '" . $key2 ."' 
																	and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																	and (AD.mois = 1 or AD.mois = 2)) 
																	or (AD.annee = " . ($info_non_deb[$key][$key2][4]-1) ." 
																	and AD.mois = 12))";
																	}
																elseif ($info_non_deb[$key][$key2][3] == 12)   //si mois 12
																	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.id != '" . $key2 ."' 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																	and (AD.mois = 12 or AD.mois = 11)) 
																	or (AD.annee = " . ($info_non_deb[$key][$key2][4]+1) ." 
																	and AD.mois = 1))";
																	}
																else	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and AF.id != '" . $key2 ."' 
																	and AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																	and ( AD.mois = " . (($info_non_deb[$key][$key2][3])-1) ." 
																	or AD.mois = " . $info_non_deb[$key][$key2][3] ." 
																	or AD.mois = " . (($info_non_deb[$key][$key2][3])+1) .")"; 
																	//print ("<br>query2 :".$query2);
																	}
															print_debug($query7);

																$result7 = pg_query($connection, $query7);
																//pg_close();

																$nb = pg_num_rows($result7);
																if ($nb == 0){$query7 = "select id, art_debarquement_id from art_fraction limit 1";
																$result7 = pg_query($connection, $query7);
																print_debug($query7);

																 //pg_close();
																}


																while($row7 = pg_fetch_row($result7))
																	{
											
																	$nb = pg_num_rows($result7);	//nb de fractions concern�es
																	$frac_concern�es = $row7[0];
																	$deb_concern� = $row7[1];
											
											
																	if ($nb >= 5)
																		{
																		$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																		$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																		
																		if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																		else	{
																			$Wm_i = $Wfdbq / $Nfdbq ;
																			$Wm += $Wm_i / ($nb-$nb_enlev);
													
																		
																			$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																			$info_non_deb[$key][$key2][9] = $Nfdbq; 
																			}
																		}
																	else	{	//strate SE
																	//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										
																		if ($info_non_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
																			AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 2)   //si mois 2
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
																			AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 3)   //si mois 3
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
																			or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 4)   //si mois 4
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =10 
																			or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 5)   //si mois 5
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 6)   //si mois 6
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $valm1 ." and AD.mois =12))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 7)   //si mois 7
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12))) 
																			or (AD.annee = " . $val1 ." and AD.mois =1)";
																			}
																		if ($info_non_deb[$key][$key2][3] == 8)   //si mois 8
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
																			or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
																			or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
																			or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
																			}
																		if ($info_non_deb[$key][$key2][3] == 9)   //si mois 9
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 10)   //si mois 10
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 11)   //si mois 11
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 12)   //si mois 12
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and AF.id != '" . $key2 ."' 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
																			}
																			print_debug($query8);
																		$result8 = pg_query($connection, $query8);
																		//pg_close();

																		$nb = pg_num_rows($result8);
																		if ($nb == 0){$query8 = "select id, art_debarquement_id from art_fraction limit 1";
																		print_debug($query8);
																		$result8 = pg_query($connection, $query8); //pg_close();
																		}


																		while($row8 = pg_fetch_row($result8))
																			{
													
																			$nb = pg_num_rows($result8);	//nb de fractions concern�es
																			$frac_concern�es = $row8[0];
																			$deb_concern� = $row8[1];
															
															
																			if ($nb >= 5)
																				{
																				$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																				$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																						
																				if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																				else	{
																					$Wm_i = $Wfdbq / $Nfdbq ;
																					$Wm += $Wm_i / ($nb-$nb_enlev);
																
																						
																					$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																					$info_non_deb[$key][$key2][9] = $Nfdbq; 
																					}
																				}
																			else	{
																			//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																				//strate E
																				$query9 = "select AF.id, AD.id 
																				from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, ref_secteur as RS 
																				where AD.id = AF.art_debarquement_id 
																				and AD.art_agglomeration_id = AA.id 
																				and AA.ref_secteur_id = RS.id 
																				and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																				and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																				and RS.nom = '" . $info_non_deb[$key][$key2][1]."' 
																				and AF.debarquee = 1 
																				and AF.poids != 0 
																				and AF.nbre_poissons != 0 
																				and AF.id != '" . $key2 ."'"; 
										print_debug($query9);

																				$result9 = pg_query($connection, $query9);
																				//pg_close();

																				$nb = pg_num_rows($result9);
																				if ($nb == 0){$query9 = "select id, art_debarquement_id from art_fraction limit 1";
																				print_debug("ligne 3584=".$query9);

																				$result9 = pg_query($connection, $query9); //pg_close();
																				}


																				while($row9 = pg_fetch_row($result9))
																					{
										
																					$nb = pg_num_rows($result9);	//nb de fractions concern�es
																					$frac_concern�es = $row9[0];
																					$deb_concern� = $row9[1];

																					if ($nb >= 5)
																						{
																						$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																						$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																								
																						if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																						else	{
																							$Wm_i = $Wfdbq / $Nfdbq ;
																							$Wm += $Wm_i / ($nb-$nb_enlev);
																		
																								
																							$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																							$info_non_deb[$key][$key2][9] = $Nfdbq; 
																							}
																						}
																						
																					else	{
																					//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																						//strate E+
																						$query10 = "select AF.id, AD.id 
																						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																						where AD.id = AF.art_debarquement_id 
																						and AD.art_agglomeration_id = AA.id 
																						and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																						and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																						and AF.debarquee = 1 
																						and AF.poids != 0 
																						and AF.nbre_poissons != 0 
																						and AF.id != '" . $key2 ."'"; 
											print_debug($query10);

																						$result10 = pg_query($connection, $query10);
																						//pg_close();

																						$nb = pg_num_rows($result10);
																						if ($nb == 0){$query10 = "select id, art_debarquement_id from art_fraction limit 1";
																					print_debug($query10);

																						$result10 = pg_query($connection, $query10); //pg_close();
																						}


																						while($row10 = pg_fetch_row($result10))
																							{
																							$nb = pg_num_rows($result10);	//nb de fractions concern�es
																							$frac_concern�es = $row5[0];
																							$deb_concern� = $row5[1];
																							
																							
																							if ($nb >= 5)
																								{
																								$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																								$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																								
																								if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																								else	{
																									$Wm_i = $Wfdbq / $Nfdbq ;
																									$Wm += $Wm_i / ($nb-$nb_enlev);
																					
																									
																									$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																									$info_non_deb[$key][$key2][9] = $Nfdbq; 
																									}
																								}
																							else
																								{
																								if ($info_non_deb[$key][$key2][7]=='PDU')$Wm = 10;
																								elseif ($info_non_deb[$key][$key2][7]=='SEP')$Wm = 125;
																								elseif ($info_non_deb[$key][$key2][7]=='CAL')$Wm = 40;
																								elseif ($info_non_deb[$key][$key2][7]=='CAA')$Wm = 40;
																								elseif ($info_non_deb[$key][$key2][7]=='CMB')$Wm = 600;
																								elseif ($info_non_deb[$key][$key2][7]=='OVU')$Wm = 125;
																								
																								else break;//on laisse la valeur � 0
																								
																								$Nfdbq = round( (($Wfdbq * 1000)/$Wm) , 0);
																								$info_non_deb[$key][$key2][9] = $Nfdbq;
																								break;
																								}
																							} //fin du while($row10 =
																							break;
																						}			
																					} //fin du while($row9 =
																				
																					break;
																				}
																			} //fin du while($row8 =
																			break;	
																		}
																	} //fin du while($row7 =
																	break;	
																}
															} //fin du while($row6 =
															break;	
														}
													} //fin du while($row5 =
													break;	
												}

										}// fin du while($row4 =
										break;
									}
								}// fin du while($row3 =
								break;
							}
						}//fin du while($row2...
						break;
					}//fin du else

				}// fin du while ($row =

			}//fin du elseif


		//////////////////////////////////////////
		//               cas n�4                //
		//          Wfdbq =0  , Nfdbq > 0       //
		//////////////////////////////////////////

		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && ($Nfdbq>0) )
			{
			//selection sur strate STE
			//(pour une espece:agglo, mois, annee et grand type identiques)

		//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) {  echo "Non connect�"; exit;}
			$query = "select distinct AF.id, AD.id 
				from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
				, art_poisson_mesure as APM 
				where AD.id = AF.art_debarquement_id 
				and APM.art_fraction_id = AF.id 
				and AD.art_agglomeration_id = AA.id 
				and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
				and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
				and AD.mois = " . $info_non_deb[$key][$key2][3] ." 
				and AD.annee = " . $info_non_deb[$key][$key2][4] ." 
				and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
				and AF.debarquee = 1 
				and AF.id != '" . $key2 ."'";
print_debug($query);

			$result = pg_query($connection, $query);
			//pg_close();

			$WdftI = 0;
			$NdftI = 0;

$nb = pg_num_rows($result);
if ($nb == 0){$query = "select id, art_debarquement_id from art_fraction limit 1";
print_debug("ligne 3747=".$query);

$result = pg_query($connection, $query);
//print "query ===".$query."<br/>";

 //pg_close();
}

			while($row = pg_fetch_row($result))
				{
				$nb = pg_num_rows($result);	//nb de fractions concern�es
				$frac_concern�es = $row[0];
				$deb_concern� = $row[1];

				if ($nb >= 5)
					{
					$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
					$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

					$WdftI += $Wdft;
					$NdftI += $Ndft;

					$Wm = ($WdftI / $NdftI);
					$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
					$info_non_deb[$key][$key2][8] = $Wfdbq;
					}

				else	{			//strate STE+
				//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);

					if ($info_non_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
						and (AD.mois = 1 or AD.mois = 2)) 
						or (AD.annee = " . ($info_non_deb[$key][$key2][4]-1) ." 
						and AD.mois = 12))";
						}
					elseif ($info_non_deb[$key][$key2][3] == 12)   //si mois 12
						{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
						and (AD.mois = 12 or AD.mois = 11)) 
						or (AD.annee = " . ($info_non_deb[$key][$key2][4]+1) ." 
						and AD.mois = 1))";
						}
					else	{
						$query2 = "select distinct AF.id, AD.id 
						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
						, art_poisson_mesure as APM 
						where AD.id = AF.art_debarquement_id 
						and APM.art_fraction_id = AF.id 
						and AD.art_agglomeration_id = AA.id 
						and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
						and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
						and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
						and AF.debarquee = 1 
						and AF.id != '" . $key2 ."' 
						and AD.annee = " . $info_non_deb[$key][$key2][4] ." 
						and ( AD.mois = " . (($info_non_deb[$key][$key2][3])-1) ." 
						or AD.mois = " . $info_non_deb[$key][$key2][3] ." 
						or AD.mois = " . (($info_non_deb[$key][$key2][3])+1) .")"; 
						//print ("<br>query2 :".$query2);
						}
					print_debug("ligne 3831=".$query2);

					$result2 = pg_query($connection, $query2);
					//pg_close();

					$nb = pg_num_rows($result2);
					if ($nb == 0){$query2 = "select id, art_debarquement_id from art_fraction limit 1";
					print_debug($query2);
					$result2 = pg_query($connection, $query2);
					//print "query2 ===".$query2."<br/>";

					 //pg_close();
					}


					while($row2 = pg_fetch_row($result2))
						{
						$nb = pg_num_rows($result2);	//nb de fractions concern�es
						$frac_concern�es = $row2[0];
						$deb_concern� = $row2[1];

						if ($nb >= 5)
							{
							$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
							$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

							$WdftI += $Wdft;
							$NdftI += $Ndft;

							$Wm = ($WdftI / $NdftI);
							$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
							$info_non_deb[$key][$key2][8] = $Wfdbq;
							}

						else	{			//strate SE
							$val1 =$info_non_deb[$key][$key2][4]+1;
							$valm1 =$info_non_deb[$key][$key2][4]-1;
							
							//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);

							if ($info_non_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
								or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 2)   //si mois 2
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
								AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 3)   //si mois 3
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9)) 
								or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 4)   //si mois 4
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =10 
								or AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 5)   //si mois 5
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
								or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
								}
							if ($info_non_deb[$key][$key2][3] == 6)   //si mois 6
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $valm1 ." and AD.mois =12))";
								}
							if ($info_non_deb[$key][$key2][3] == 7)   //si mois 7
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and AD.mois =1))";
								}
							if ($info_non_deb[$key][$key2][3] == 8)   //si mois 8
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
								or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
								or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
								or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
								}
							if ($info_non_deb[$key][$key2][3] == 9)   //si mois 9
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 3 or AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3)))";
								}
							if ($info_non_deb[$key][$key2][3] == 10)   //si mois 10
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 4 
								or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4)))";
								}
							if ($info_non_deb[$key][$key2][3] == 11)   //si mois 11
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
								}
							if ($info_non_deb[$key][$key2][3] == 12)   //si mois 12
								{
								$query3 = "select distinct AF.id, AD.id 
								from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
								, art_poisson_mesure as APM 
								where AD.id = AF.art_debarquement_id 
								and APM.art_fraction_id = AF.id 
								and AD.art_agglomeration_id = AA.id 
								and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
								and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
								and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
								and AF.debarquee = 1 
								and AF.id != '" . $key2 ."' 
								and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
								and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
								or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
								or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
								or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
								}
								print_debug($query3);

								$result3 = pg_query($connection, $query3);
								//pg_close();

								$nb = pg_num_rows($result3);
								if ($nb == 0){$query3 = "select id, art_debarquement_id from art_fraction limit 1";
								print_debug($query3);

								$result3 = pg_query($connection, $query3); //pg_close();
								}


								while($row3 = pg_fetch_row($result3))
									{
									$nb = pg_num_rows($result3);	//nb de fractions concern�es
									$frac_concern�es = $row3[0];
									$deb_concern� = $row3[1];
									if ($nb >= 5)
										{
										$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
										$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];
										
										$WdftI += $Wdft;
										$NdftI += $Ndft;
										
										$Wm = ($WdftI / $NdftI);
										$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
										$info_non_deb[$key][$key2][8] = $Wfdbq;
										}
									else	{
										//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										//strate E
										$query4 = "select distinct AF.id, AD.id 
										from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, ref_secteur as RS 
										, art_poisson_mesure as APM 
										where AD.id = AF.art_debarquement_id 
										and APM.art_fraction_id = AF.id 
										and AD.art_agglomeration_id = AA.id 
										and AA.ref_secteur_id = RS.id 
										and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
										and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
										and RS.nom = '" . $info_non_deb[$key][$key2][1]."'
										and AF.debarquee = 1 
										and AF.id != '" . $key2 ."'"; 
										print_debug($query4);

										$result4 = pg_query($connection, $query4);
										//pg_close();

										$nb = pg_num_rows($result4);
										if ($nb == 0){$query4 = "select id, art_debarquement_id from art_fraction limit 1";
										
										print_debug($query4);
										$result4 = pg_query($connection, $query4); //pg_close();
										}


										while($row4 = pg_fetch_row($result4))
											{
											$nb = pg_num_rows($result4);	//nb de fractions concern�es
											$frac_concern�es = $row4[0];
											$deb_concern� = $row4[1];

											if ($nb >= 5)
												{
												$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
												$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

												$WdftI += $Wdft;
												$NdftI += $Ndft;

												$Wm = ($WdftI / $NdftI);
												$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
												$info_non_deb[$key][$key2][8] = $Wfdbq;
												}

											else	{
											//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
												//strate E+
												$query5 = "select distinct AF.id, AD.id 
												from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
												, art_poisson_mesure as APM 
												where AD.id = AF.art_debarquement_id 
												and APM.art_fraction_id = AF.id 
												and AD.art_agglomeration_id = AA.id 
												and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
												and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
												and AF.debarquee = 1 
												and AF.id != '" . $key2 ."'"; 

												print_debug("ligne 4194=".$query5);

												$result5 = pg_query($connection, $query5);
												//pg_close();

												$nb = pg_num_rows($result5);
												if ($nb == 0){$query5 = "select id, art_debarquement_id from art_fraction limit 1";
												
												print_debug($query5);

												 $result5 = pg_query($connection, $query5); //pg_close();
												}


												while($row5 = pg_fetch_row($result5))
													{
													$nb = pg_num_rows($result5);	//nb de fractions concern�es
													$frac_concern�es = $row5[0];
													$deb_concern� = $row5[1];
													
													
													if ($nb >= 5)
														{
														$Ndft = $info_deb[$deb_concern�][$frac_concern�es][11];
														$Wdft = $info_deb[$deb_concern�][$frac_concern�es][12];

														$WdftI += $Wdft;
														$NdftI += $Ndft;

														$Wm = ($WdftI / $NdftI);
														$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
														$info_non_deb[$key][$key2][8] = $Wfdbq;
														}
													
													else	{
													
													//absence structure de taille ds le secteur
													//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
														//strate STE 
														$query6 = "select AF.id, AD.id 
														from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
														where AD.id = AF.art_debarquement_id 
														and AD.art_agglomeration_id = AA.id 
														and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
														and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
														and AD.mois = " . $info_non_deb[$key][$key2][3] ." 
														and AD.annee = " . $info_non_deb[$key][$key2][4] ." 
														and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
														and AF.debarquee = 1 
														and AF.poids != 0 
														and AF.nbre_poissons != 0 
														and AF.id != '" . $key2 ."'";
														print_debug($query6);

														$result6 = pg_query($connection, $query6);
														//pg_close();
														
														$Wm_i = 0;
														$Wm = 0;

														$nb = pg_num_rows($result6);
														if ($nb == 0){$query6 = "select id, art_debarquement_id from art_fraction limit 1";
														
														//print "query6 ===".$query6."<br/>";

														$result6 = pg_query($connection, $query6); //pg_close();
														}


														while($row6 = pg_fetch_row($result6))
															{
											
															$nb = pg_num_rows($result6);	//nb de fractions concern�es
															$frac_concern�es = $row6[0];
															$deb_concern� = $row6[1];
															$nb_enlev = 0;
															
															if ($nb >= 5)
																{	//Wfdbq et Nfdbq doivent etre positif
															
															
																$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																
																if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																else	{
																	$Wm_i = $Wfdbq / $Nfdbq ;
																	$Wm += $Wm_i / ($nb-$nb_enlev);
											
																
																	$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																	$info_non_deb[$key][$key2][8] = $Wfdbq;
																	
																	}
																}
															
															else	{	//strate STE+
																//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);

																if ($info_non_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
																	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and AF.id != '" . $key2 ."' 
																	and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																	and (AD.mois = 1 or AD.mois = 2)) 
																	or (AD.annee = " . ($info_non_deb[$key][$key2][4]-1) ." 
																	and AD.mois = 12))";
																	}
																elseif ($info_non_deb[$key][$key2][3] == 12)   //si mois 12
																	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.id != '" . $key2 ."' 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																	and (AD.mois = 12 or AD.mois = 11)) 
																	or (AD.annee = " . ($info_non_deb[$key][$key2][4]+1) ." 
																	and AD.mois = 1))";
																	}
																else	{
																	$query7 = "select AF.id, AD.id 
																	from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																	where AD.id = AF.art_debarquement_id 
																	and AD.art_agglomeration_id = AA.id 
																	and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																	and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																	and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																	and AF.debarquee = 1 
																	and AF.poids != 0 
																	and AF.nbre_poissons != 0 
																	and AF.id != '" . $key2 ."' 
																	and AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																	and ( AD.mois = " . (($info_non_deb[$key][$key2][3])-1) ." 
																	or AD.mois = " . $info_non_deb[$key][$key2][3] ." 
																	or AD.mois = " . (($info_non_deb[$key][$key2][3])+1) .")"; 
																	//print ("<br>query2 :".$query2);
																	}
																print_debug("ligne 4346=".$query7);

																$result7 = pg_query($connection, $query7);
																//pg_close();

																$nb = pg_num_rows($result7);
																if ($nb == 0){$query7 = "select id, art_debarquement_id from art_fraction limit 1";
																
																print_debug($query7);

																$result7 = pg_query($connection, $query7); //pg_close();
																}


																while($row7 = pg_fetch_row($result7))
																	{
											
																	$nb = pg_num_rows($result7);	//nb de fractions concern�es
																	$frac_concern�es = $row7[0];
																	$deb_concern� = $row7[1];
											
											
																	if ($nb >= 5)
																		{
																		$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																		$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																		
																		if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																		else	{
																			$Wm_i = $Wfdbq / $Nfdbq ;
																			$Wm += $Wm_i / ($nb-$nb_enlev);
													
																			$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																			$info_non_deb[$key][$key2][8] = $Wfdbq;
																			}
																		}
																	else	{	//strate SE
																	//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										
																		if ($info_non_deb[$key][$key2][3] == 1)   //si mois 1 (janvier)
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 )) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =7 or AD.mois =8 or 
																			AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 2)   //si mois 2
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =8 or 
																			AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 3)   //si mois 3
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois = 9 or AD.mois =10 
																			or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 4)   //si mois 4
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =10 
																			or AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 5)   //si mois 5
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11)) 
																			or (AD.annee = " . $valm1 ." and (AD.mois =11 or AD.mois =12)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 6)   //si mois 6
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $valm1 ." and AD.mois =12))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 7)   //si mois 7
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 1 or AD.mois = 2 or AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and AD.mois =1))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 8)   //si mois 8
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 8 or AD.mois = 9 or AD.mois = 10 or AD.mois = 11 
																			or AD.mois = 12 or AD.mois = 7 or AD.mois = 6 or AD.mois = 5 
																			or AD.mois = 4 or AD.mois = 3 or AD.mois = 2 )) 
																			or (AD.annee = " . $val1 ." and (AD.mois = 1 or AD.mois = 2)))"; 
																			}
																		if ($info_non_deb[$key][$key2][3] == 9)   //si mois 9
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 3 or AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 10)   //si mois 10
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 4 
																			or AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 11)   //si mois 11
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.id != '" . $key2 ."' 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 5 or AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4 or AD.mois =5)))";
																			}
																		if ($info_non_deb[$key][$key2][3] == 12)   //si mois 12
																			{
																			$query8 = "select AF.id, AD.id 
																			from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																			where AD.id = AF.art_debarquement_id 
																			and AD.art_agglomeration_id = AA.id 
																			and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																			and AA.nom = '" . $info_non_deb[$key][$key2][2] ."' 
																			and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																			and AF.debarquee = 1 
																			and AF.poids != 0 
																			and AF.nbre_poissons != 0 
																			and AF.id != '" . $key2 ."' 
																			and ((AD.annee = " . $info_non_deb[$key][$key2][4] ." 
																			and (AD.mois = 6 or AD.mois = 7 or AD.mois = 8 
																			or AD.mois = 9 or AD.mois =10 or AD.mois =11 or AD.mois =12)) 
																			or (AD.annee = " . $val1 ." and (AD.mois =1 or AD.mois =2 
																			or AD.mois =3 or AD.mois =4 or AD.mois =5 or AD.mois =6)))";
																			}
																			print_debug($query8);

																		$result8 = pg_query($connection, $query8);
																		//pg_close();

																		$nb = pg_num_rows($result8);
																		if ($nb == 0){$query8 = "select id, art_debarquement_id from art_fraction limit 1";
																		print_debug($query8);
																		 $result8 = pg_query($connection, $query8); //pg_close();
																		}


																		while($row8 = pg_fetch_row($result8))
																			{
													
																			$nb = pg_num_rows($result8);	//nb de fractions concern�es
																			$frac_concern�es = $row8[0];
																			$deb_concern� = $row8[1];
															
															
																			if ($nb >= 5)
																				{
																				$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																				$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																						
																				if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																				else	{
																					$Wm_i = $Wfdbq / $Nfdbq ;
																					$Wm += $Wm_i / ($nb-$nb_enlev);
																
																					$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																					$info_non_deb[$key][$key2][8] = $Wfdbq;	
																					
																					}
																				}
																			else	{
																			//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																				//strate E
																				$query9 = "select AF.id, AD.id 
																				from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA, ref_secteur as RS 
																				where AD.id = AF.art_debarquement_id 
																				and AD.art_agglomeration_id = AA.id 
																				and AA.ref_secteur_id = RS.id 
																				and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																				and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																				and RS.nom = '" . $info_non_deb[$key][$key2][1]."'
																				and AF.debarquee = 1 
																				and AF.poids != 0 
																				and AF.nbre_poissons != 0 
																				and AF.id != '" . $key2 ."'"; 
										print_debug($query9);

																				$result9 = pg_query($connection, $query9);
																				
																				//pg_close();

																				$nb = pg_num_rows($result9);
																				if ($nb == 0){$query9 = "select id, art_debarquement_id from art_fraction limit 1";
																			print_debug($query9);

																				 $result9 = pg_query($connection, $query9); //pg_close();
																				}


																				while($row9 = pg_fetch_row($result9))
																					{
										
																					$nb = pg_num_rows($result9);	//nb de fractions concern�es
																					$frac_concern�es = $row9[0];
																					$deb_concern� = $row9[1];

																					if ($nb >= 5)
																						{
																						$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																						$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																								
																						if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																						else	{
																							$Wm_i = $Wfdbq / $Nfdbq ;
																							$Wm += $Wm_i / ($nb-$nb_enlev);
																		
																								
																							$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																							$info_non_deb[$key][$key2][8] = $Wfdbq; 
																							}
																						}
																						
																					else	{
																					//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																						//strate E+
																						$query10 = "select AF.id, AD.id 
																						from art_debarquement as AD, art_fraction as AF, art_agglomeration as AA 
																						where AD.id = AF.art_debarquement_id 
																						and AD.art_agglomeration_id = AA.id 
																						and AF.ref_espece_id = '" . $info_non_deb[$key][$key2][7] ."' 
																						and AD.art_grand_type_engin_id = '" . $info_non_deb[$key][$key2][6]."' 
																						and AF.debarquee = 1 
																						and AF.poids != 0 
																						and AF.nbre_poissons != 0 
																						and AF.id != '" . $key2 ."'"; 
																						print_debug("ligne 4720=".$query10);

																						$result10 = pg_query($connection, $query10);
																						//pg_close();

																						$nb = pg_num_rows($result10);
																						if ($nb == 0){$query10 = "select id, art_debarquement_id from art_fraction limit 1";
																						print_debug($query10);

																						 $result10 = pg_query($connection, $query10); //pg_close();
																						}


																						while($row10 = pg_fetch_row($result10))
																							{
																							$nb = pg_num_rows($result10);	//nb de fractions concern�es
																							$frac_concern�es = $row5[0];
																							$deb_concern� = $row5[1];
																							
																							
																							if ($nb >= 5)
																								{
																								$Wfdbq = $info_deb[$deb_concern�][$frac_concern�es][9];
																								$Nfdbq = $info_deb[$deb_concern�][$frac_concern�es][8];
																								
																								if (($Wfdbq == "") || ($Nfdbq == "")){$nb_enlev ++;}
																								else	{
																									$Wm_i = $Wfdbq / $Nfdbq ;
																									$Wm += $Wm_i / ($nb-$nb_enlev);
																					
																									
																									$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																									$info_non_deb[$key][$key2][8] = $Wfdbq;
																									}
																								}
																							else
																								{
																								if ($info_non_deb[$key][$key2][7]=='PDU')$Wm = 10;
																								elseif ($info_non_deb[$key][$key2][7]=='SEP')$Wm = 125;
																								elseif ($info_non_deb[$key][$key2][7]=='CAL')$Wm = 40;
																								elseif ($info_non_deb[$key][$key2][7]=='CAA')$Wm = 40;
																								elseif ($info_non_deb[$key][$key2][7]=='CMB')$Wm = 600;
																								elseif ($info_non_deb[$key][$key2][7]=='OVU')$Wm = 125;
																								
																								else break;//on laisse la valeur � 0
																								
																								$Wfdbq = round( (($Wm * $Nfdbq)/1000) , 2);  //en kg
																								$info_non_deb[$key][$key2][8] = $Wfdbq;
																								break;
																								}
																							} //fin du while($row10 =
																							break;
																						}
																					} //fin du while($row9 =
																				
																					break;
																				}
																			} //fin du while($row8 =
																			break;	
																		}
																	} //fin du while($row7 =
																	break;	
																}
															} //fin du while($row6 =
															break;	
														}
													} //fin du while($row5 =
													break;	
												}

										}// fin du while($row4 =
										break;
									} 
								}// fin du while($row3 =
								break;
							}
						}//fin du while($row2...
						break;
					}//fin du else
				
				}// fin du while ($row =

			} //fin du elseif




		//////////////////////////////////////////
		//               cas n�6                //
		//        Wfdbq >0  et Nfdbq > 0        //
		//////////////////////////////////////////

		elseif ( ($Wfdbq >0) && ($Nfdbq > 0) )
			{
			} //fin du elseif

		//////////////////////////////////////////
		//              cas n�8                 //
		//         Wfdbq =0, Nfdbq=0            //
		//////////////////////////////////////////

		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && (($Nfdbq == 0)||($Nfdbq == "")) )
			{
			unset($info_non_deb[$key][$key2]);
			} //fin du elseif

		}
	}


	////////////////////////////////////////////////////////////
  //                     CREATION DE LA                     //
	//                    NOUVELLE FRACTION                   //
	//                     dans $info_deb                     //
	////////////////////////////////////////////////////////////

reset($info_deb);
reset($info_non_deb);
while (list($key, $val) = each($info_non_deb))
	{
	while (list($key2, $val2) = each($val))
		{
		$info_deb[$key][$key2][8] = $info_non_deb[$key][$key2][8];
		$info_deb[$key][$key2][9] = $info_non_deb[$key][$key2][9];
		$info_deb[$key][$key2][7] = $info_non_deb[$key][$key2][7];
		unset($info_non_deb[$key][$key2]);
		}
	}


////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                //
//                              INSERTION DES DONNEES RESULTATS                                   //
//                  CONTENUES DANS $info_deb DANS LA BASE DE DONNEES PPEAO                        //                                  //
//                      (tables art_debarquement_rec et art_fraction_rec)                         //
//                                                                                                //
////////////////////////////////////////////////////////////////////////////////////////////////////


reset($info_deb);
$numero2 = 0;
print_debug("\n\n\n**********************************\nINSERTION DES DATAS\n****************************************\n\n\n");
while (list($key, $val) = each($info_deb)){
	$numero2 = $numero2+1;
	// Remplacement print par $messageProcess YL 15.07.2008
	// print ("Insertion de l'enqu�te ".$numero2 . " sur ".$nb_enr ."<br/>");
	//$messageProcess.="Insertion de l'enqu&ecirc;te ".$numero2 . " sur ".$nb_enr ."<br/>";
	
	$messageProcess.="<br/><b>Recomposisiton de l'enqu&ecirc;te ".$numero2 . " sur ".$nb_enr ."</b><br/><br/>";
	
	$Wti =0;
	while (list($key2, $val2) = each($val)){
		$fr_deb =$key2;
		$Wti += $info_deb[$key][$key2][8];
	}

	
	//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) {  echo "pas de connection "; exit;}

	$query2 = "insert into art_debarquement_rec ( id, poids_total, art_debarquement_id ) 
	values ('rec_".$key."', ".$Wti.", ".$key.");";
	print_debug($query2);

	// Modification YL 15/07/2008 pour eviter les warning affich�s � l'�cran erreur ==> dans le log
	 //if($Wti!=0)$result2 = pg_exec($connection, $query2); // Ancienne ajout donn�es. 
	// nouvelle insertion donn�es en utilisant la fonction runQuery
	if($Wti!=0) {
		$messageProcess .= "".$query2."<br/>";
		$RunQErreur = runQuery($query2,$connection);
		if ( $RunQErreur){
			
		} else {
			
			$messageProcess.="<font color='blue'>Pb insertion de cette requ�te</font><br/>";
			// traitement d'erreur ? On arr�te ou seulement avertissement ?
		
		}
	
	}

	//pg_close();

	reset($val);
	while (list($key2, $val2) = each($val)){
	//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//	if (!$connection) {  echo "pas de connection"; exit;}

		$query = "insert into art_fraction_rec ( id, poids , nbre_poissons, ref_espece_id ) 
		values ('".$key2."', ".$info_deb[$key][$key2][8].", ".$info_deb[$key][$key2][9].", '".$info_deb[$key][$key2][7]."');";
		print_debug($query);
		
		$messageProcess .= "".$query."<br/>";

		// Modification YL 15/07/2008 pour eviter les warning affich�s � l'�cran erreur ==> dans le log
		//$result = pg_exec($connection, $query);
		// Ancienne ajout donn�es. 
		// nouvelle insertion donn�es en utilisant la fonction runQuery
		$RunQErreur = runQuery($query,$connection);
		
		
		if ( $RunQErreur){
			
		} else {
			$messageProcess.="<font color='blue'>Pb insertion de cette requ�te</font><br/>";
			// traitement d'erreur ? On arr�te ou seulement avertissement ?
		
		}

		
	} // fin while (list($key2, $val2) = each($val))
} // fin (list($key, $val) = each($info_deb))

// Ajout YL 15.07.2008 afficher le message en fin de traitement si demand�
if ($afficherMessage == "1") {
	echo $messageProcess ;
}

pg_close();

//envoie mail confirm
// To
//$to = 'fauchier@mpl.ird.fr';
// Subject
$subject = 'Base de donn�es'.$_GET['base'];
// Message
$msg = 'Fin du taitement de recomposition des donn�es';
// Headers
$headers = 'From: base_PPEAO'."\r\n";
$headers .= "\r\n";
// Function mail()
 mail($to, $subject, $msg, $headers);

print_debug(getTime()."ms");

?>