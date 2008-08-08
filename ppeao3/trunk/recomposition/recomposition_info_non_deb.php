<?php
reset($info_non_deb);
while (list($key, $val) = each($info_non_deb))                      //pour tous les debarquements
	{
	while (list($key2, $val2) = each($val))		  		//pour chaque fraction
		{
		$Wfdbq = $info_non_deb[$key][$key2][8];
		$Nfdbq = $info_non_deb[$key][$key2][9];


		//////////////////////////////////////////
		//               cas n°3                //
		//         Wfdbq >0  , Nfdbq = 0        //
		//////////////////////////////////////////

		if ( ($Wfdbq>0) && (($Nfdbq == 0)||($Nfdbq == "")) )
			{
			//selection sur strate STE
			//(pour une espece:agglo, mois, annee et grand type identiques)
			//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			//if (!$connection) {  echo "Non connecté"; exit;}
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
				
				$nb = pg_num_rows($result);	//nb de fractions concernées
				$frac_concernées = $row[0];
				$deb_concerné = $row[1];

				if ($nb >= 5)
					{
					$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
					$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];

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

						$nb = pg_num_rows($result2);	//nb de fractions concernées
						$frac_concernées = $row2[0];
						$deb_concerné = $row2[1];


						if ($nb >= 5)
							{
							$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
							$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];

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

									$nb = pg_num_rows($result3);	//nb de fractions concernées
									$frac_concernées = $row3[0];
									$deb_concerné = $row3[1];
									if ($nb >= 5)
										{
										$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
										$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];

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

											$nb = pg_num_rows($result4);	//nb de fractions concernées
											$frac_concernées = $row4[0];
											$deb_concerné = $row4[1];

											if ($nb >= 5)
												{
												$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
												$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];

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
													$nb = pg_num_rows($result5);	//nb de fractions concernées
													$frac_concernées = $row5[0];
													$deb_concerné = $row5[1];
													
													
													if ($nb >= 5)
														{
														$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
														$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];
	
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
											
															$nb = pg_num_rows($result6);	//nb de fractions concernées
															$frac_concernées = $row6[0];
															$deb_concerné = $row6[1];
															$nb_enlev = 0;
															
															if ($nb >= 5)
																{	//Wfdbq et Nfdbq doivent etre positif
															
															
																$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																
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
											
																	$nb = pg_num_rows($result7);	//nb de fractions concernées
																	$frac_concernées = $row7[0];
																	$deb_concerné = $row7[1];
											
											
																	if ($nb >= 5)
																		{
																		$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																		$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																		
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
													
																			$nb = pg_num_rows($result8);	//nb de fractions concernées
																			$frac_concernées = $row8[0];
																			$deb_concerné = $row8[1];
															
															
																			if ($nb >= 5)
																				{
																				$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																				$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																						
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
										
																					$nb = pg_num_rows($result9);	//nb de fractions concernées
																					$frac_concernées = $row9[0];
																					$deb_concerné = $row9[1];

																					if ($nb >= 5)
																						{
																						$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																						$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																								
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
																							$nb = pg_num_rows($result10);	//nb de fractions concernées
																							$frac_concernées = $row5[0];
																							$deb_concerné = $row5[1];
																							
																							
																							if ($nb >= 5)
																								{
																								$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																								$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																								
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
																								
																								else break;//on laisse la valeur à 0
																								
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
		//               cas n°4                //
		//          Wfdbq =0  , Nfdbq > 0       //
		//////////////////////////////////////////

		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && ($Nfdbq>0) )
			{
			//selection sur strate STE
			//(pour une espece:agglo, mois, annee et grand type identiques)

		//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) {  echo "Non connecté"; exit;}
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
				$nb = pg_num_rows($result);	//nb de fractions concernées
				$frac_concernées = $row[0];
				$deb_concerné = $row[1];

				if ($nb >= 5)
					{
					$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
					$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];

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
						$nb = pg_num_rows($result2);	//nb de fractions concernées
						$frac_concernées = $row2[0];
						$deb_concerné = $row2[1];

						if ($nb >= 5)
							{
							$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
							$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];

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
									$nb = pg_num_rows($result3);	//nb de fractions concernées
									$frac_concernées = $row3[0];
									$deb_concerné = $row3[1];
									if ($nb >= 5)
										{
										$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
										$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];
										
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
											$nb = pg_num_rows($result4);	//nb de fractions concernées
											$frac_concernées = $row4[0];
											$deb_concerné = $row4[1];

											if ($nb >= 5)
												{
												$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
												$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];

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
													$nb = pg_num_rows($result5);	//nb de fractions concernées
													$frac_concernées = $row5[0];
													$deb_concerné = $row5[1];
													
													
													if ($nb >= 5)
														{
														$Ndft = $info_deb[$deb_concerné][$frac_concernées][11];
														$Wdft = $info_deb[$deb_concerné][$frac_concernées][12];

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
											
															$nb = pg_num_rows($result6);	//nb de fractions concernées
															$frac_concernées = $row6[0];
															$deb_concerné = $row6[1];
															$nb_enlev = 0;
															
															if ($nb >= 5)
																{	//Wfdbq et Nfdbq doivent etre positif
															
															
																$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																
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
											
																	$nb = pg_num_rows($result7);	//nb de fractions concernées
																	$frac_concernées = $row7[0];
																	$deb_concerné = $row7[1];
											
											
																	if ($nb >= 5)
																		{
																		$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																		$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																		
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
													
																			$nb = pg_num_rows($result8);	//nb de fractions concernées
																			$frac_concernées = $row8[0];
																			$deb_concerné = $row8[1];
															
															
																			if ($nb >= 5)
																				{
																				$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																				$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																						
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
										
																					$nb = pg_num_rows($result9);	//nb de fractions concernées
																					$frac_concernées = $row9[0];
																					$deb_concerné = $row9[1];

																					if ($nb >= 5)
																						{
																						$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																						$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																								
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
																							$nb = pg_num_rows($result10);	//nb de fractions concernées
																							$frac_concernées = $row5[0];
																							$deb_concerné = $row5[1];
																							
																							
																							if ($nb >= 5)
																								{
																								$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																								$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																								
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
																								
																								else break;//on laisse la valeur à 0
																								
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
		//               cas n°6                //
		//        Wfdbq >0  et Nfdbq > 0        //
		//////////////////////////////////////////

		elseif ( ($Wfdbq >0) && ($Nfdbq > 0) )
			{
			} //fin du elseif

		//////////////////////////////////////////
		//              cas n°8                 //
		//         Wfdbq =0, Nfdbq=0            //
		//////////////////////////////////////////

		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && (($Nfdbq == 0)||($Nfdbq == "")) )
			{
			unset($info_non_deb[$key][$key2]);
			} //fin du elseif

		}
	}
?>