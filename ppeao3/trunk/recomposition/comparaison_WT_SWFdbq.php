<?php
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
		$Wt = $info_deb[$key][$key2][5];            //poid total du débarquement
		$Wfdbq = $info_deb[$key][$key2][8];
		$WfdbqI += $Wfdbq;			    //somme des poids des fractions
	print "key == $key - $key2 - $Wt<br/>";
	
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
?>