<?php 

//*********************************************************************
// getDocumentation : permet de recuperer tout type de document pour 
// un groupe de donnees (caracterise par un repertoire)
function getDocumentation ($dir,$displayType) {
// Cette fonction permet de gerer l'affichage de la documentation
// Elle va scanner le repertoire pour le groupe de donnees (peut etre une page web
// peut etre un ensemble de donnees, peut etre une donnee unique
//*********************************************************************
// En entrée, les paramètres suivants sont :
// dir : repertoire ou se trouve l'info
// displayType : type d'affichage pour
//*********************************************************************
// En sortie : 
// - Renvoie un affichage la forme dependant de l'appel en entree
//*********************************************************************
$listDoc="";
//icone_info.png
$docpath= $_SERVER["DOCUMENT_ROOT"]."/documentation/data/".$dir;
if (! file_exists($docpath)) {
	echo "<br/>Attention, le repertoire de documentation ".$docpath." n'existe pas. Contacter votre admin PPEAO.";
	exit;
}
$handle = opendir($docpath); 
while (($file = readdir())!=false) { 
	clearstatcache(); 
	$ext=substr($file,strpos($file,"."),4);
	if($file!=".." && $file!="." ){
		$ad=htmlspecialchars($file); //source
		$ad=str_replace("'",'%92',$ad);
		$ad=str_replace('é','%E9',$ad);
		$ad=str_replace('è','%E8',$ad);
		$ad=str_replace('à','%E0',$ad);
		$ad=str_replace('â','%E2',$ad);
		$ad=str_replace('ä','%E4',$ad);
		$ad=str_replace('ê','%EA',$ad);
		$ad=str_replace('ô','%F4',$ad);
		$ad=str_replace('ë','%EB',$ad);
		$ad=str_replace('ö','%F6',$ad);
		$ad=str_replace('ü','%FC',$ad);
		$chaine = substr( $file , 0 , strpos($file , ".") ); //
		$listDoc.= "- <a href=\"/documentation/data/".$dir."/".$ad."\" target=\"new\">".$chaine."</a><br/>";

	}
} 
closedir($handle); 
echo "<div id=\"infotitre\"><br/><img src=\"/assets/icone_info.png\" alt=\"Informations Complementaires\" />&nbsp;Une documentation compl&eacute;mentaire est disponible et est regroup&eacute;e dans la liste ci-dessous.</div>";
echo "<div id=\"infotitre\">".$listDoc."</div>";
//end function

}
?>