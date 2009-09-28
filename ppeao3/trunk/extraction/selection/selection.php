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

<div id="main_container" class="edition">
<h1>consulter des donn&eacute;es : s&eacute;lection</h1>
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


//debug echo('<pre>');print_r($compteur);echo('</pre>');





/* on numerote les etapes :
1 = selectionner ou non des especes
2 = selection des especes
3 = selection pays/systemes
4= selection periode
*/
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

// on agit poursuit la selection en fonction du type d'exploitation choisi
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
	
	// si aucun type n'est passe, on e fait rien
	default:
	break;
}

echo('</div>'); // find div id=selection

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
