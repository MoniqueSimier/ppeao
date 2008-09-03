<?php 
// Créé par Olivier ROUX, 02-08-2008
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="edition";
$zone=2; // zone edition (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::&eacute;dition des donn&eacute;es</title>
	
<script src="/js/edition.js" type="text/javascript"></script>

</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<div id="main_container" class="home">

<?php

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';

?>

<h1>&eacute;dition des donn&eacute;es</h1>
<!-- édition des tables de référence -->

<?php
// on affiche les choix pour les tables de référence
echo('<div id="tables_reference">');	
echo('<h2>&eacute;dition des tables de r&eacute;f&eacute;rence</h2>');

	// géographie
	echo("<h3>g&eacute;ographie</h3>");
	buildTableSelect("geographie","");

	// espèces
	echo("<h3>esp&egrave;ces</h3>");
	buildTableSelect("especes","");

echo('</div>');


// on affiche les choix pour les tables de codage
echo('<div id="tables_codage">');	
echo('<h2>&eacute;dition des tables de codage</h2>');
	// pêche scientifique
	echo('<div id="peche_scientifique">');
	echo('<h3>p&ecirc;che scientifique</h3>');
	echo('<select name="codage_scientifique_select" id="codage_scientifique_select" onchange="javascript:showCodageTablesSelect(\'scientifique\');">');
		// la première OPTION ne sert à rien...
		echo('<option value="choose" >- choisir un domaine -</option>');
		foreach ($scientifiqueHierarchie as $domaine) {
			echo('<option value="'.$domaine.'">'.$domainesListe[$domaine]["label"].'</option>');	
		} //end for each
	echo('</select>');
	echo('</div>');
	
	echo('<div id="peche_experimentale">');
	echo('<h3>p&ecirc;che exp&eacute;rimentale</h3>');
	echo('<select name="codage_experimentale_select" id="codage_experimentale_select" onchange="javascript:showCodageTablesSelect(\'experimentale\');">');
		// la première OPTION ne sert à rien...
		echo('<option value="choose">- choisir un domaine -</option>');
		foreach ($experimentaleHierarchie as $domaine) {
			echo('<option value="'.$domaine.'">'.$domainesListe[$domaine]["label"].'</option>');	
		} //end for each
	echo('</select>');
	echo('</div>');
	
	
echo('</div>');

?>



<!-- édition des utilisateurs réservée aux admins-->
<?php if (userHasAccess($_SESSION['s_ppeao_user_id'],1)) { ?>
<div id="tables_utilisateurs">
<h2>&eacute;dition des utilisateurs</h2>
En utilisant <a href="">la page d'édition des utilisateurs</a>, vous pourrez créer ou supprimer un compte ou un groupe, modifier les permissions d'accès d'un utilisateur ou d'un groupe existant.
</div>
<?php } ?>

<?php 
logWriteTo(2,'notice','acc&egrave;s &agrave; l\'&eacute;dition des donn&eacute;es','','',0);

?>
	
</div> <!-- end div id="main_container"-->


<?php 
// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}

?>

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
