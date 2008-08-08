<?php
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
?>