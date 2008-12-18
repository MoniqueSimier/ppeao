<?php
////////////////////////////////////////////////////////////////////////////////////////////////////
//                                RECOMPOSITION INTRA FRACTION                                    //
//                                   Traitements des 8 cas                                        //
//                        grâce au tableau recapitulatif $info_deb                                //
//                             (travail fraction par fraction)                                    //
////////////////////////////////////////////////////////////////////////////////////////////////////
//$nume_prodgr=1/$nb_enr;
reset($info_deb);
//$datas=$info_deb;
//while (list($key, $val) = each($info_deb)){//pour tous les debarquements

foreach($info_deb as $key=>$val){
	//if($numero<2000){;}
	//$messageProcess .= "Recomposition de l'enqu&ecirc;te ".$numero . " sur ".$nb_enr." <br/>";
	print_debug("D".$key);
	$WfdbqI=0;
	foreach($val as $key2=>$val2){			//pour chaque fraction
		//     calcul et ajout des Wdft et Ndft pour chaque fraction         dans le tableau recapitulatif $info_deb                //
		
		$Wfdbq = $info_deb[$key][$key2][8];
		$Nfdbq = $info_deb[$key][$key2][9];
		$Ndft = $info_deb[$key][$key2][11];
		$Wdft = $info_deb[$key][$key2][12];
		$Wm = $info_deb[$key][$key2][13];
		$info_deb=choix_cas_recomposition(true,$info_deb,$key,$key2,$Wfdbq,$Nfdbq,$Ndft,$Wdft,$Wm);
		
		/*print_debug("  ");
		print_debug( $Wfdbq );
		print_debug( $Nfdbq );
		print_debug( $Ndft );
		print_debug( $Wdft );
		print_debug( $Wm );
		print_debug( $key);
		print_debug( $key2);
		print_debug("  "); */
		
		//   COMPARAISON ENTRE LE POIDS TOTAL DE L'ENQUETE ET LA SOMME DES WFdbq     
		$Wt = $info_deb[$key][$key2][5];            //poids total du débarquement
		$Wfdbq = $info_deb[$key][$key2][8];
		$WfdbqI += $Wfdbq;			    //somme des poids des fractions
	}
	$info_deb=comparaison_WT_SW($info_deb,$key,$val,$Wt,$WfdbqI);
}

?>