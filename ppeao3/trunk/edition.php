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
	
<script src="/js/edition.js" type="text/javascript"  charset="iso-8859-15"></script>

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


// on affiche les choix pour les tables de parametrage
echo('<div id="tables_codage">');	
echo('<h2>&eacute;dition des tables de param&eacute;trage</h2>');
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
	
	echo('<div id="peche_artisanale">');
	echo('<h3>p&ecirc;che artisanale</h3>');
	echo('<select name="artisanale_select" id="codage_artisanale_select" onchange="javascript:showCodageTablesSelect(\'artisanale\');">');
		// la première OPTION ne sert à rien...
		echo('<option value="choose">- choisir un domaine -</option>');
		foreach ($artisanaleHierarchie as $domaine) {
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
En utilisant <a href="">la page d&#x27;&eacute;dition des utilisateurs</a>, vous pourrez cr&eacute;er ou supprimer un compte ou un groupe, modifier les permissions d&#x27;acc&egrave;s d&#x27;un utilisateur ou d&#x27;un groupe existant.
</div>
<?php } ?>

<?php 
logWriteTo(1,'notice','acc&egrave;s &agrave; l\'&eacute;dition des donn&eacute;es','','',0);

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
