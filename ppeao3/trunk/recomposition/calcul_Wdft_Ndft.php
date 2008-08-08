<?php
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
		$info_deb[$key][$key2][11] = $Ndft;                    //Ndft ds tableau récap		
		
		
		
		$esp = $val2[7];
		$Wdft = 0;                                             //mise à zéro de la variable
		for($i=0; $i<($Ndft) ; $i++) 
			{
			$Wdft += ($coef_esp[$esp][0] * pow(10, -5) * pow($FT[$key2][$i],$coef_esp[$esp][1]));
			}
		$info_deb[$key][$key2][12] = round($Wdft, 1);		//Wdft ds tableau récap
									// avec precision = 0.1
		if ($Ndft != 0) {$Wm = round(($Wdft / $Ndft) , 1);}
		else {$Wm = 0;}
		$info_deb[$key][$key2][13] = $Wm;			//Wm ds tableau recap
									// avec précision = 0.1
		}

	} 
?>