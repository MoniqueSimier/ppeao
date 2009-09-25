<?php
// on recupere la selection de l'utilisateur et on les transmet a l'extraction

session_start();

include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/extraction/selection/selection_xml_functions.php';

$parsed=parse_url($_SESSION["selection_url"]);
parse_str($parsed["query"],$selection);

//debug echo('<pre>');print_r($selection);echo('</pre>');


// on commence a produire le contenu de la selection en XML
$xml='<?xml version="1.0" encoding="UTF-8"?>';
$xml.='<selection>';

// le type d'exploitation
switch ($selection["exploit"]) {
	// extraction
	case "donnees":
		$xml.='<exploitation type="extraction" />';
	// le type de peche
		switch ($selection["donnees"]) {
			//peche experimentale
			case "exp":
			$xml.='<peche type="experimentale" />';
			$xml.=xmlFamilles($selection);
			$xml.=xmlEspeces($selection);
			$xml.=xmlPays($selection);
			$xml.=xmlSystemes($selection);
			$xml.=xmlSecteurs($selection);
			$xml.=xmlPeriode($selection);
			$xml.=xmlCampagnes($selection);
			$xml.=xmlengins($selection);
			// le script a utiliser pour l'extraction
			$script="extraction_filieres_exp.php?logsupp=0";
			break;
			// peche artisanale
			case "art":
			$xml.='<peche type="artisanale" />';
			$xml.=xmlFamilles($selection);
			$xml.=xmlEspeces($selection);
			$xml.=xmlPays($selection);
			$xml.=xmlSystemes($selection);
			$xml.=xmlSecteurs($selection);
			$xml.=xmlAgglomerations($selection);
			$xml.=xmlPeriode($selection);
			$xml.=xmlEnquetes($selection);
			$xml.=xmlGrandTypeEngins($selection);
			// le script a utiliser pour l'extraction
			$script="extraction_filieres_art.php?logsupp=0";
			break;
		}
	break;
	// statistiques de peche
	case "stats":
	break;
	// fonds de cartes
	case "cartes":
	break;
	
	
}



$xml.='</selection>';

//debug header("Content-Type: text/xml; charset=iso-8859-15"); print_r($xml);
// on stocke le XML dans la variable de session
$_SESSION["selection_xml"]=$xml;

// et on redirige vers le choix des filieres d'extraction
$url = 'http';
	$script_name = '';
	
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
	   $url .=  's';
	}
	$url .=  '://';
	
	if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {$theHost=$_SERVER['HTTP_X_FORWARDED_HOST'];} else {$theHost=$_SERVER['HTTP_HOST'];};
	
$url.=$theHost;


$url.='/extraction/extraction/'.$script;

header('location: ' . $url);

?>