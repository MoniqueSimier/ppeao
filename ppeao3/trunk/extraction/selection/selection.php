<?php 
// Créé par Olivier ROUX, 02-08-2008
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="consulter";
$subsection="";

$zone=0; // zone libre (voir table admin_zones)


// on réinitialise la selection stockee dans des variables de session
 unset ($_SESSION["selection_url"]);
 unset ($_SESSION["selection_xml"]);

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	include $_SERVER["DOCUMENT_ROOT"].'/extraction/selection/selection_functions.php';

?>
	<title>ppeao::consulter des donn&eacute;es::s&eacute;lection</title>
	
<script src="/extraction/selection/ex_selection.js" type="text/javascript"  charset="iso-8859-15"></script>


</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
?>

<div id="main_container" class="selection">
<h1>consulter des donn&eacute;es : s&eacute;lection
<?php 
// si on a depasse la premiere etape, on affiche le lien permettant d'afficher ou masquer la selection
if ($_GET["step"]>1) {
	echo('<span class="showHide"><a id="selection_precedente_toggle" onclick="javascript:toggleSelection();" title="afficher ou masquer la selection" href="#">[afficher/modifier/masquer la s&eacute;lection]</a></span>');
}
?>
</h1>
<!-- édition des tables de référence -->
<?php

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>


<?php
echo('<div id="ex_selection">');

// on calcule les élements du compteur
$compteur=prepareCompteur();
// on en extrait la liste des campagnes et enquetes correspondant a la selection courante
$campagnes_ids=$compteur["campagnes_ids"];
$coups_ids=$compteur["coups_ids"];
$enquetes_ids=$compteur["enquetes_ids"];

// si on a depasse le step 1, on encapsule les selecteurs precedents dans un DIV id="selection_precedente"
// pour pouvoir les masquer
// le DIV est ferme dans l'une des fonctions afficheXXXXX(), selon le step
if ($_GET["step"]>1) {
	echo('<div id="selection_precedente">');
	echo('<div id="selection_precedente_contenu">');
	}
// on demande si l'utilisateur veut choisir des especes ou pas
afficheChoixEspeces();
// on affiche le selecteur de taxonomie
afficheTaxonomie();
// on affiche le selecteur de geographie
afficheGeographie();
// on affiche le selecteur de periode
affichePeriode();
// on affiche le choix du type d'exploitation si il reste des campagnes ou des enquetes
if ($compteur["campagnes_total"]!=0 || $compteur["enquetes_total"]!=0) {
afficheTypeExploitation();} 

// on poursuit la selection en fonction du type d'exploitation choisi
switch($_GET["exploit"]) {
	// extraction de donnees
	case "donnees":
		// on doit poursuivre la selection par le type de donnees
		afficheTypeDonnees();
		// maintenant on travaille selon les peches exp ou art (parametre &donnee=)
		switch ($_GET["donnees"]) {
			// peche experimentale : on continue la selection par secteurs>campagnes>engins de peche
			case "exp":
			// secteurs
			afficheSecteurs("exp");
			// campagnes
			afficheCampagnes();
			// engins de peche 
			afficheEngins();
			break;
			
			// peche artisanale : on continue la selection par
			// secteurs>agglomerations>periodes d'enquete> grands types d'engins de peche
			case "art":
			//secteurs
			afficheSecteurs("art");
			// agglomerations 
			afficheAgglomerations();
			// periodes d'enquetes
			affichePeriodeEnquetes();
			// grands types d'engins
			afficheGrandsTypesEngins($_GET["exploit"]);
			break;
		}
		
		
	break; // end case "donnees"
	
	// statistiques de peche
	case "stats":
		// on doit poursuivre la selection par le type de stats
		afficheTypeStats();
		switch ($_GET["stats"]) {
			// statistiques par agglomerations
			case "agglo":
				//secteurs
				afficheSecteurs("art");
				// agglomerations 
				afficheAgglomerations();
				// periodes d'enquetes
				affichePeriodeEnquetes();
				// grands types d'engins
				afficheGrandsTypesEngins($_GET["exploit"]);
			break;
			//statistiques generales
			case "gen":
				// systemes ou secteurs
				afficheSecteurs2();
				//grands types d'engins
				afficheGrandsTypesEngins($_GET["exploit"]);
			break;
		}
	break; // end case "stats"
	
	//fonds cartographiques
	case "cartes":
	break; // end case "cartes"
	
	// graphes
	case "graphes":
	break;
	
	//indicateurs ecologiques
	case "indics":
	break;
	
	// si aucun type n'est passe, on ne fait rien
	default:
	break;
}

echo('</div>'); // find div id=ex_selection

// le script pour afficher ou masquer la selection
?> 
<script type="text/javascript" charset="utf-8">
	var mySlider = new Fx.Slide('selection_precedente', {duration: 500});
	mySlider.hide();
	// affiche ou masque le DIV contenant la selection precedente
	function toggleSelection() {
		mySlider.toggle() //toggle the slider up and down.
	}
</script>
<?php

// on affiche le compteur de campagnes / enquetes
echo($compteur["texte"]);
?>

	

<?php 
// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}

?>
</div> <!-- end div id="main_container"-->
<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
