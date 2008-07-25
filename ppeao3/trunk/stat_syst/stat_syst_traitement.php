<html>
<body>
<?php

$file="temp.txt";
$fpm = fopen($file, "r");


$resume = Array();
$i = 0;
while ($ligne=fgets($fpm,100))
	{
	$i ++;
	$ligne = str_replace ("\n", "", $ligne);
	$ligne = str_replace ("\r", "", $ligne);
	print ("<br>ligne ".$i.": ".$ligne);
	
	$tab=explode("\t",$ligne);
	//$resume[$tab[0]][$tab[1]][$tab[2]][$tab[3]][$tab[4]][$tab[5]] = $tab[6];
	$resume[$tab[0]][$tab[1]][$tab[2]][$tab[3]][$tab[4]][$i][0] = $tab[5];
	$resume[$tab[0]][$tab[1]][$tab[2]][$tab[3]][$tab[4]][$i][1] = $tab[6];
	}
print ("<br><br>");
fclose($fpm);




////////////////////connection base 
$user="devppeao";                      // Le nom d'utilisateur 
$passwd="2devppe!!";                   // Le mot de passe 
$host= "vmppeao.mpl.ird.fr";  // L'hôte (ordinateur sur lequel le SGBD est installé) 
$bdd = "bourlaye_rec";                    // Le nom de la base de données 

$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) {  echo "Non connecté"; exit;}



/////////////////////////////
print ("<br><br>");
reset ($resume);
$compteur=0;
while (list($key, $val) = each($resume))
	{
	$pays = $key;
	//print ("<br>pays : ".$key);
	while (list($key2, $val2) = each($val))
		{
		$systeme = $key2;
		//print ("<br>systeme : ".$key2);
		while (list($key3, $val3) = each($val2))
			{
			$secteur = $key3;
			//print ("<br>secteur : ".$key3);
			while (list($key4, $val4) = each($val3))
				{
				$annee = $key4;
				//print ("<br>annee : ".$key4);
				while (list($key5, $val5) = each($val4))
					{
					//print ("<br>mois : ".$key5);
					$mois = $key5;
					$cumul = 0;
					while (list($key6, $val6) = each($val5))
						{
						//print ("<br>agglo : ".$val6[0]);
						$cumul += round($val6[1],1);
						//print ("<br>cumul effort : ".$cumul);
						}
					print ("<br><br>".$pays.", ".$systeme.", ".$secteur.", ".$annee.", ".$mois);
					print ("<br>effort total du secteur : ".$cumul);
					
					
					//connection à la base pour obtenir les donnees liées au secteur
					$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
					if (!$connection) {  echo "Non connecté"; exit;}
					
					$row = Array();
					$query = "select AST.fm, AST.cap, AST.art_agglomeration_id FROM art_stat_totale as AST, 
					ref_secteur as RF, art_agglomeration as AA 
					where RF.nom = '".$secteur."' 
					and RF.id = AA.ref_secteur_id 
					and AA.id = AST.art_agglomeration_id 
					and AST.annee = ".$annee." 
					and AST.mois = ".$mois." 
					"; 
					$result = pg_query($connection, $query); 
					
					$effort_temp=0;
					$cap_temp=0;
					while($row = pg_fetch_row($result))
						{ 
						//print ("<br>".$row[0].$row[1].$row[2]);
						$effort_temp += $row[0];
						$cap_temp += $row[1];
						}
					$prise_moy = $cap_temp / $effort_temp;
					$resultat_secteur = round(($prise_moy * $cumul), 2);
					print ("<br>resultat avec base : ".$resultat_secteur."<br>");
					
					pg_close();
					
					
					////////////
					//Par grand type :
					//recuperation de la capture totale ainsi que des rapports des captures par GT pour la state ST
					
					$query2 = "select AST.art_agglomeration_id, AST.cap, ASGT.art_grand_type_engin_id, ASGT.cap_gt FROM art_stat_totale as AST, 
					art_stat_gt as ASGT, 
					ref_secteur as RF, art_agglomeration as AA 
					where RF.nom = '".$secteur."' 
					and RF.id = AA.ref_secteur_id 
					and AST.id = ASGT.art_stat_totale_id 
					and AA.id = AST.art_agglomeration_id 
					and AST.annee = ".$annee." 
					and AST.mois = ".$mois." 
					"; 
					$result2 = pg_query($connection, $query2); 
					print ($query2);
					
					$tab_gt = Array();
					while($row = pg_fetch_row($result2))
						{
						if (isset($tab_gt[$row[2]][0]))$tab_gt[$row[2]][0] += $row[3];
						else {$tab_gt[$row[2]][0]= $row[3];}
						
						if (isset($tab_gt[$row[2]][1]))$tab_gt[$row[2]][1] += $row[1];
						else {$tab_gt[$row[2]][1]= $row[1];}
						}
					pg_close();
					
					reset ($tab_gt);
					while (list($key10, $val10) = each($tab_gt))
						{
						$gt = round($val10[0]/$val10[1] , 2);
						$resultat_gt = round($gt*$resultat_secteur , 2);
						print ("<br> gt :".$key10." , rapport : ".$gt." , resultat_gt : ". $resultat_gt);
						//multiplier rapport par cap tot pour chaque engin
						
						$query_ins1 = "insert into art_effort_gt( id, effort_tot, effort_gt, rapport_gt, 
						art_grand_type_engin, secteur) 
						values (".$compteur.", ".$resultat_secteur.", ".$resultat_gt.", ".$gt.", '".$key10."', '".$secteur."')";
						$compteur++;
	
						$result_ins1 = pg_exec($connection, $query_ins1);
						print ("<br>".$query_ins1);
						
						
						
						}
					print ("<br>");
					////////////
					//Par esp :
					//recuperation de la capture totale ainsi que des rapports des captures par esp pour la state ST
					
					$compteur=0;
					$query3 = "select AST.art_agglomeration_id, AST.cap, ASSP.ref_espece_id, ASSP.cap_sp FROM art_stat_totale as AST, 
					art_stat_sp as ASSP, 
					ref_secteur as RF, art_agglomeration as AA 
					where RF.nom = '".$secteur."' 
					and RF.id = AA.ref_secteur_id 
					and AST.id = ASSP.art_stat_totale_id 
					and AA.id = AST.art_agglomeration_id 
					and AST.annee = ".$annee." 
					and AST.mois = ".$mois." 
					"; 
					$result3 = pg_query($connection, $query3); 
					//print ("<br>".$query3);
					
					$tab_sp = Array();
					while($row = pg_fetch_row($result3))
						{
						if (isset($tab_sp[$row[2]][0]))$tab_sp[$row[2]][0] += $row[3];
						else {$tab_sp[$row[2]][0]= $row[3];}
						
						if (isset($tab_sp[$row[2]][1]))$tab_sp[$row[2]][1] += $row[1];
						else {$tab_sp[$row[2]][1]= $row[1];}
						}
					pg_close();
					
					reset ($tab_sp);
					while (list($key11, $val11) = each($tab_sp))
						{
						$sp = round($val11[0]/$val11[1] , 2);
						$resultat_sp = round($sp*$resultat_secteur , 2);
						if ($sp != 0)print ("<br> sp :".$key11." , rapport sp : ".$sp." , resultat sp : ".$resultat_sp);
						//multiplier rapport par cap tot pour chaque esp
						
						
						$query_ins2 = "insert into art_effort_sp( id, effort_tot, effort_sp, rapport_sp, 
						ref_espece_id, secteur) 
						values (".$compteur.", ".$resultat_secteur.", ".$resultat_sp.", ".$sp.", '".$key11."', '".$secteur."')";
						$compteur++;
	
						$result_ins2 = pg_exec($connection, $query_ins2);
						print ("<br>".$query_ins1);
						
						
						
						}
					
					////////////
					//Par grand type et esp :
					//recuperation de la capture totale ainsi que des rapports des captures par gt et esp pour la state ST
					
					$compteur=0;
					$query4 = "select AST.art_agglomeration_id, AST.cap, ASGTSP.ref_espece_id, ASGTSP.cap_gt_sp, ASGT.art_grand_type_engin_id FROM art_stat_totale as AST, 
					art_stat_gt as ASGT, art_stat_gt_sp as ASGTSP, 
					ref_secteur as RF, art_agglomeration as AA 
					where RF.nom = '".$secteur."' 
					and RF.id = AA.ref_secteur_id 
					and AST.id = ASGT.art_stat_totale_id 
					and ASGT.id = ASGTSP.art_stat_gt_id 
					and AA.id = AST.art_agglomeration_id 
					and AST.annee = ".$annee." 
					and AST.mois = ".$mois." 
					"; 
					$result4 = pg_query($connection, $query4); 
					//print ("<br>".$query4);
					
					$tab_gt_sp = Array();
					while($row = pg_fetch_row($result4))
						{
						if (isset($tab_gt_sp[$row[4]][$row[2]][0]))$tab_gt_sp[$row[4]][$row[2]][0] += $row[3];
						else {$tab_gt_sp[$row[4]][$row[2]][0]= $row[3];}
						
						if (isset($tab_gt_sp[$row[4]][$row[2]][1]))$tab_gt_sp[$row[4]][$row[2]][1] += $row[1];
						else {$tab_gt_sp[$row[4]][$row[2]][1]= $row[1];}
						}
					pg_close();
					
					reset ($tab_gt_sp);
					$gt = 0;
					$sp = 0;
					while (list($key12, $val12) = each($tab_gt_sp))
						{
						$gt = $key12;
						//print("<br>".$key12);
						while (list($key13, $val13) = each($val12))
							{
							$sp = $key13;
							//print("<br>".$gt."  ".$sp);
							$rapport_gt_sp = round($val13[0]/$val13[1] , 2);
							$resultat_gt_sp = round($rapport_gt_sp*$resultat_secteur , 2);
							if ($rapport_gt_sp != 0)print ("<br> gt : ".$gt." sp :".$sp." , rapport gt_sp : ".$rapport_gt_sp." , resultat gt_sp : \t ".$resultat_gt_sp);
							//multiplier rapport par cap tot pour chaque gt/esp
							
							
							$query_ins3 = "insert into art_effort_gt_sp( id, effort_tot, effort_gt_sp, rapport_gt_sp, 
							art_grand_type_engin, ref_espece_id, secteur) 
							values (".$compteur.", ".$resultat_secteur.", ".$resultat_gt_sp.", ".$rapport_gt_sp.", '".$gt."', '".$sp."', '".$secteur."')";
							$compteur++;
		
							$result_ins3 = pg_exec($connection, $query_ins3);
							//print ("<br>".$query_ins1);
							
							
							
							}
						}
					
					////////////
					//Pour les structures de tailles :
					//somme des effectifs de classes de tailles des captures totales par espece
					
					$compteur=0;
					$query5 = "select AST.art_agglomeration_id, AST.cap, ASSP.ref_espece_id, ATSP.li, ATSP.xi 
					FROM art_stat_totale as AST, 
					art_stat_sp as ASSP, 
					art_taille_sp as ATSP, 
					ref_secteur as RF, art_agglomeration as AA 
					where RF.nom = '".$secteur."' 
					and RF.id = AA.ref_secteur_id 
					and AA.id = AST.art_agglomeration_id 
					and AST.id = ASSP.art_stat_totale_id 
					and ASSP.id = ATSP.art_stat_sp_id 
					and AST.annee = ".$annee." 
					and AST.mois = ".$mois." 
					"; 
					$result5 = pg_query($connection, $query5); 
					//print ("<br>".$query5);
					
					$tab_taille_sp = Array();
					//creation du tableau résultat $tab_taille_sp des couples li, xi (classe taille, nb)
					while($row = pg_fetch_row($result5))
						{
						if (isset($tab_taille_sp[$row[3]]))$tab_taille_sp[$row[3]] += $row[4];
						else {$tab_taille_sp[$row[3]] = $row[4];}
						}
					pg_close();
					
					ksort($tab_taille_sp);
					reset ($tab_taille_sp);
					while (list($key13, $val13) = each($tab_taille_sp))
						{
						print("<br>classe taille : ".$key13.", effectif : ".$val13);
						
						
						$query_ins4 = "insert into art_effort_taille( id, xi, ni, secteur) 
						values (".$compteur.", ".$key13.", ".$val13.", '".$secteur."')";
						$compteur++;
		
						$result_ins4 = pg_exec($connection, $query_ins4);
						//print ("<br>".$query_ins1);
						
						}
					
					
					
					}
				}
			}
		}
	}


//


print("<br><br><div align=center>Statistiques réalisées</div>");



?>

<br><br>
<div id="retour" align='center'>
<form action="stat_syst.php"><br>
<input type="submit" name="ret" value="Retour">
</form>
</div>

</body>
</html>
