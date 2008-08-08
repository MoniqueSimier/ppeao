<?php

////////////////////////////////////////////////////////////////////////////////////////////////////
//                                RECOMPOSITION INTRA FRACTION                                    //
//                                                                                                //
//                                   Traitements des 8 cas                                        //
//                        grâce au tableau recapitulatif $info_deb                                //
//                             (travail fraction par fraction)                                    //
////////////////////////////////////////////////////////////////////////////////////////////////////
$nume_prodgr=1/$nb_enr;
$numero = 0;
reset($info_deb);
while (list($key, $val) = each($info_deb))//pour tous les debarquements
{
	
	$numero = $numero+1;
	//$messageProcess .= "Recomposition de l'enqu&ecirc;te ".$numero . " sur ".$nb_enr." <br/>";
	//print "Recomposition de l'enquête ".$numero . " sur ".$nb_enr;

	while (list($key2, $val2) = each($val))			//pour chaque fraction
		{
		$Wfdbq = $info_deb[$key][$key2][8];
		$Nfdbq = $info_deb[$key][$key2][9];
		$Ndft = $info_deb[$key][$key2][11];
		$Wdft = $info_deb[$key][$key2][12];
		$Wm = $info_deb[$key][$key2][13];



		//////////////////////////////////////////
		//               cas n°1                //
		//  Wfdbq = 0 , Nfdbq > 0, DFT existe   //
		//////////////////////////////////////////

		if ( (($Wfdbq == 0)||($Wfdbq == "")) && ($Nfdbq>0) && ($Ndft>0))
			{
			$Wfdbq = $Wm * $Nfdbq;
			if ($Wfdbq < $Wdft) {$Wfdbq = $Wdft;}
			$info_deb[$key][$key2][8] = round(($Wfdbq /1000) , 2);	//en kg	
			}

		//////////////////////////////////////////
		//               cas n°2                //
		//  Wfdbq > 0 , Nfdbq = 0, DFT existe   //
		//////////////////////////////////////////

		elseif ( ($Wfdbq>0) && (($Nfdbq == 0)||($Nfdbq == "")) && ($Ndft>0))
			{
			$Nfdbq = round((($Wfdbq *1000) / $Wm),0);		//wfdbq en kg
			if ($Nfdbq < $Ndft) {$Nfdbq = $Ndft;}
			$info_deb[$key][$key2][9] = $Nfdbq;
			}

		//////////////////////////////////////////
		//               cas n°3                //
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
					$info_deb[$key][$key2][9] = $Nfdbq; 
					}

				else	{			//strate STE+
					//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
					pg_free_result($result);
					$query2=query_from_ste_plus($info_deb,$key2);
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
							$info_deb[$key][$key2][9] = $Nfdbq; 
							}

						else	{			//strate SE
							$val1 =$info_deb[$key][$key2][4]+1;
							$valm1 =$info_deb[$key][$key2][4]-1;
							
							//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
							pg_free_result($result2);
							$quey3=query_from_se($info_deb,$key2);
							
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
										$info_deb[$key][$key2][9] = $Nfdbq; 
										}
									else	{
										pg_free_result($result3);
										//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										
										//strate E secteur ou système
										//$query4=query_from_e($info_deb,$key2);
										$query4=query_strate_e($info_deb,$key2,"seceteur");
										
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
												$info_deb[$key][$key2][9] = $Nfdbq; 
												}

											else	{
												
												//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
												pg_free_result($result4);
												//strate E+
												$query5=query_from_e_plus($info_deb,$key2);
												$result5 = pg_query($connection, $query5);
												//pg_close();

												$nb = pg_num_rows($result5);
												if ($nb == 0){$query5 = "select id, art_debarquement_id from art_fraction limit 1";
												//print "query5 ===".$query5."<br/>";
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
														$info_deb[$key][$key2][9] = $Nfdbq; 
														}
													
													else	{	//absence structure de taille ds le secteur
														//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
														pg_free_result($result5);//19 09
														//strate STE 
														$query6=query_from_ste($info_deb,$key2);
														
														
														
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
											
															$nb = pg_num_rows($result6);	//nb de fractions concernées
															
															$nb_enlev = 0;
															
															if ($nb >= 5)
																{	//Wfdbq et Nfdbq doivent etre positif
															$frac_concernées = $row6[0];
															$deb_concerné = $row6[1];
															
																$Wfdbq = $info_deb[$deb_concerné][$frac_concernées][9];
																$Nfdbq = $info_deb[$deb_concerné][$frac_concernées][8];
																
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

																$query7=query_from_ste_plus($info_deb,$key2);
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
																			$info_deb[$key][$key2][9] = $Nfdbq; 
																			}
																		}
																	else	{	//strate SE
																		//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																		$query8=query_from_se($info_deb,$key2);	
																		
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
																					$info_deb[$key][$key2][9] = $Nfdbq; 
																					}
																				}
																			else	{
																				//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																				//strate E système?
																				$query9=query_from_e($deb_info,$key2,"systeme"); 
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
																							$info_deb[$key][$key2][9] = $Nfdbq; 
																							}
																						}
																						
																					else	{
																						//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																						//strate E+ systeme
																						$query10=query_from_e($info_deb;$key2,"systeme");
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
																								
																								else break;//on laisse la valeur à 0
																								
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
		//               cas n°4                //
		//  Wfdbq =0  , Nfdbq > 0, pas de DFT   //
		//////////////////////////////////////////
		
		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && ($Nfdbq>0) && (($Ndft == 0)||($Ndft == "")) )
			{
			//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) {  echo "Non connecté"; exit;}
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
					$info_deb[$key][$key2][8] = $Wfdbq;
					}

				else	{			//strate STE+
					//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
					pg_free_result($result);//19 09
					$query2=query_from_ste_plus($info_deb,$key2);
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
							$info_deb[$key][$key2][8] = $Wfdbq;
							}

						else	{			//strate SE
							$val1 =$info_deb[$key][$key2][4]+1;
							$valm1 =$info_deb[$key][$key2][4]-1;

							//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
							pg_free_result($result2);
								$query3=query_from_se($info_deb,$key2);
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
										$info_deb[$key][$key2][8] = $Wfdbq;
										}
									else	{
										//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
										pg_free_result($result3);//19 09
										//strate E secteur
										print_debug($query4);
										$query4=query_from_e($info_deb,$key2,"secteur");
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
												$info_deb[$key][$key2][8] = $Wfdbq;
												}

											else	{
												//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
												pg_free_result($result4);
												//strate E+
												$query5=query_from_e_plus($info_deb,$key2,"systeme");
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
														$info_deb[$key][$key2][8] = $Wfdbq;
														}
													
													else	{
													
													//absence structure de taille ds le secteur
														//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
														pg_free_result($result5);
														//strate STE 
														$query6=query_from_ste($info_deb,$key2);
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
																	$info_deb[$key][$key2][8] = $Wfdbq;
																	
																	}
																}
															
															else	{	//strate STE+
																//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
															$query7=query_from_ste_plus($info_deb,$key2);
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
																			$info_deb[$key][$key2][8] = $Wfdbq;
																			}
																		}
																	else	{	//strate SE
																		//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																			$query8=query_from_se($info_deb,$key2);
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
																					$info_deb[$key][$key2][8] = $Wfdbq;	
																					
																					}
																				}
																			else	{
																			//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																				//strate E systeme
																				$query9=query_from_e($info_deb,$key2,"systeme");
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
																							$info_deb[$key][$key2][8] = $Wfdbq; 
																							}
																						}
																						
																					else	{
																					//	$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
																						//strate E+ systeme???? //TODO
																						$query10=query_from_e_plus($info_deb,$key2,"systeme");
																						
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
																								
																								else break;//on laisse la valeur à 0
																								
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
		//               cas n°5                //
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
		//          cas n°6 et 7                //
		//        Wfdbq >0  et Nfdbq > 0        //
		//////////////////////////////////////////

		elseif ( ($Wfdbq >0) && ($Nfdbq > 0) )
			{

			//print ("<br>cas 6 et 7 Wfdbq =".$Wfdbq." , Nfdbq =".$Nfdbq);

			} //fin du elseif

		//////////////////////////////////////////
		//              cas n°8                 //
		//    Wfdbq =0, Nfdbq=0, pas de DFT     //
		//////////////////////////////////////////

		elseif ( (($Wfdbq == 0)||($Wfdbq == "")) && (($Nfdbq == 0)||($Nfdbq == "")) && (($Ndft == 0)||($Ndft == "")) )
			{
			unset($info_deb[$key][$key2]);
			} //fin du elseif
		}
	}
?>