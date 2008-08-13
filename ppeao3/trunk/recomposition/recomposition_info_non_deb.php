<?php
reset($info_non_deb);
foreach($info_non_deb as $key=>$val){                      //pour tous les debarquements
	print_debug("ND".$key);
	foreach($val as $key2=>$val2){		  		//pour chaque fraction
		//Recomposition info_non_deb
		$Wfdbq = $info_non_deb[$key][$key2][8];
		$Nfdbq = $info_non_deb[$key][$key2][9];
		$info_non_deb=choix_cas_recomposition(false,$info_non_deb,$key,$key2,$Wfdbq,$Wfdbq);
		
		
	//                     CREATION DE LA  NOUVELLE FRACTION   dans $info_deb                     //
	//	reset($info_deb);
	//reset($info_non_deb);
		
		$info_deb[$key][$key2][8] = $info_non_deb[$key][$key2][8];
		$info_deb[$key][$key2][9] = $info_non_deb[$key][$key2][9];
		$info_deb[$key][$key2][7] = $info_non_deb[$key][$key2][7];
		unset($info_non_deb[$key][$key2]);
	}
}
?>