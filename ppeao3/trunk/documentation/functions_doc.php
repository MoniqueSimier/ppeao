<?php 
//*********************************************************************
// Note sur les fonctions getDocumentation et displayDocumentation
// Si l'effet FXslide est utilisé, il faut rajouter dans le php appelant
// la focntion ce code javascript
//*********************************************************************
//
//		<script type="text/javascript" charset="iso-8859-1">
//		/* <![CDATA[ */		
//		
//		window.addEvent('domready', function() {
//			var status = {
//				'true': 'Ouvert',
//				'false': 'Ferm&eacute;'
//			};
//			//-vertical
//			var myVerticalSlide = new Fx.Slide('vertical_slide');
//			myVerticalSlide.hide();
//			$('vertical_status').set('html', status[myVerticalSlide.open]);
//			$('v_slidein').addEvent('click', function(e){
//				e.stop();
//				myVerticalSlide.slideIn();
//			});
//			$('v_slideout').addEvent('click', function(e){
//				e.stop();
//				myVerticalSlide.slideOut();
//			});
//			
//			// When Vertical Slide ends its transition, we check for its status
//			// note that complete will not affect 'hide' and 'show' methods
//			myVerticalSlide.addEvent('complete', function() {
//				$('vertical_status').set('html', status[myVerticalSlide.open]);
//			});
//		});
//		
//		/* ]]> */

//*********************************************************************
// getDocumentation : permet de recuperer tout type de document pour 
// un groupe de donnees (caracterise par un repertoire)
function getDocumentation ($dir,$displayType,$affTitre,$titreTexte) {
// Cette fonction permet de gerer l'affichage de la documentation
// Elle va scanner le repertoire pour le groupe de donnees (peut etre une page web
// peut etre un ensemble de donnees, peut etre une donnee unique
//*********************************************************************
// En entrée, les paramètres suivants sont :
// dir : repertoire ou se trouve l'info
// displayType : type d'affichage en sortie
// affTire : est-ce qu'on affiche un titre ? (y/n)
// titreTexte: si choix y a la variable précédente, alors on met ici le titre 
//*********************************************************************
// En sortie : 
// - Renvoie un affichage la forme dependant de l'appel en entree
//*********************************************************************
$listDoc="";
$rawListDoc="";
//icone_info.png
$docpath= $_SERVER["DOCUMENT_ROOT"]."/documentation/data/".$dir;
if (! file_exists($docpath)) {
	//echo "<br/>Attention, le repertoire de documentation ".$docpath." n'existe pas. Contacter votre admin PPEAO.";
	//exit;
} else {
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
			
			if 	($displayType == "raw") {
				if ($rawListDoc=="") {
					$rawListDoc="/documentation/data/".$dir."/".$ad;
				} else {
					$rawListDoc=$rawListDoc.",/documentation/data/".$dir."/".$ad;
				}
			} else {
				$listDoc.= "- <a href=\"/documentation/data/".$dir."/".$ad."\" target=\"new\">".$chaine."</a><br/>";
			}
			
		}
	}
	closedir($handle);
	if (!$listDoc =="" || !$rawListDoc=="") {
		switch  ($displayType) {
			case "icone": 
				echo"<div class=\"marginbottom\">Une documentation compl&eacute;mentaire est disponible&nbsp;&nbsp;
					<a id=\"v_slidein\" href=\"#\"> Afficher </a>
					|
					<a id=\"v_slideout\" href=\"#\"> Fermer </a>
					| <strong>status</strong>: <span id=\"vertical_status\">open</span>
					</div>";
				if ($affTitre =="y" && !$titreTexte =="" ){
					echo"<div id=\"vertical_slide\"><h4>".$titreTexte."</h4>".$listDoc."</div>";
				} else {
					echo"<div id=\"vertical_slide\">".$listDoc."</div>";
				}
				break;

			case "paragraphe" : 
				echo "<br/><br/><div id=\"infoAuto\"><div id=\"infotitre\"><img src=\"/assets/icone_info.png\" alt=\"Informations Complementaires\" />&nbsp;Une documentation compl&eacute;mentaire est disponible et est regroup&eacute;e dans la liste ci-dessous.</div>";
				echo "<div id=\"infotexte\">".$listDoc."</div></div>";
				break;
			
			case "text" : 
				if ($affTitre =="y" && !$titreTexte =="" ){
					echo"<h4>".$titreTexte."</h4>";
				}
				echo "$listDoc" ;
				break;
				
			case "variable":
				$returnValue = "";
				if ($affTitre =="y" && !$titreTexte =="" ){
					$returnValue = "<h4>".$titreTexte."</h4>";
				}
				$returnValue .= $listDoc;
				return $returnValue;
				break;
				
			case "raw" : 
				return $rawListDoc;
				break;
		
		}
		
	}
}
}//end function



//*********************************************************************
// displayDocumentation : permet d'afficher un doc dans un div deroulant
function displayDocumentation ($docTexte) {
// Cette fonction permet d'afficher un texte contenant les liens a une doc
// dans un div avec un effet FX Slide (mootools)
//*********************************************************************
// En entrée, les paramètres suivants sont :
// docTexte: le texte (y compris les teqg html) contenant la doc
//*********************************************************************
// En sortie : 
// - Renvoie les div.
//*********************************************************************

	if ($docTexte == "") {
		echo "Pas de documentation compl&eacute;mentaire disponible";
	} else {
		echo "<div class=\"marginbottom\">Une documentation compl&eacute;mentaire est disponible&nbsp;&nbsp;
		<a id=\"v_slidein\" href=\"#\"> Afficher </a>
		|
		<a id=\"v_slideout\" href=\"#\"> Fermer </a>
		| <strong>status</strong>: <span id=\"vertical_status\">open</span>
	</div>
	<div id=\"vertical_slide\">".$docTexte."</div>";
	}


}//end function







?>