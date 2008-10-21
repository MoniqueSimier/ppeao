<?php 
// Cr�� par Olivier ROUX, 02-08-2008
// code commun � toutes les pages (demarrage de session, doctype etc.)
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

// on teste � quelle zone l'utilisateur a acc�s
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<div id="main_container" class="home">

<?php

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';

?>

<h1>&eacute;dition des donn&eacute;es</h1>
<!-- �dition des tables de r�f�rence -->

<?php
// on affiche les choix pour les tables de r�f�rence
echo('<div id="tables_reference">');	
echo('<h2>&eacute;dition des tables de r&eacute;f&eacute;rence</h2>');

	// g�ographie
	echo("<h3>g&eacute;ographie</h3>");
	buildTableSelect("geographie","");

	// esp�ces
	echo("<h3>esp&egrave;ces</h3>");
	buildTableSelect("especes","");

echo('</div>');


// on affiche les choix pour les tables de parametrage
echo('<div id="tables_codage">');	
echo('<h2>&eacute;dition des tables de param&eacute;trage</h2>');
	// p�che scientifique
	echo('<div id="peche_scientifique">');
	echo('<h3>p&ecirc;che scientifique</h3>');
	echo('<select name="codage_scientifique_select" id="codage_scientifique_select" onchange="javascript:showCodageTablesSelect(\'scientifique\');">');
		// la premi�re OPTION ne sert � rien...
		echo('<option value="choose" >- choisir un domaine -</option>');
		foreach ($scientifiqueHierarchie as $domaine) {
			echo('<option value="'.$domaine.'">'.$domainesListe[$domaine]["label"].'</option>');	
		} //end for each
	echo('</select>');
	echo('</div>');
	
	echo('<div id="peche_artisanale">');
	echo('<h3>p&ecirc;che artisanale</h3>');
	echo('<select name="artisanale_select" id="codage_artisanale_select" onchange="javascript:showCodageTablesSelect(\'artisanale\');">');
		// la premi�re OPTION ne sert � rien...
		echo('<option value="choose">- choisir un domaine -</option>');
		foreach ($artisanaleHierarchie as $domaine) {
			echo('<option value="'.$domaine.'">'.$domainesListe[$domaine]["label"].'</option>');	
		} //end for each
	echo('</select>');
	echo('</div>');
	
	
echo('</div>');

?>



<!-- �dition des utilisateurs r�serv�e aux admins-->
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
// note : on termine la boucle testant si l'utilisateur a acc�s � la page demand�e

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas acc�s ou n'est pas connect�, on affiche un message l'invitant � contacter un administrateur pour obtenir l'acc�s
else {userAccessDenied($zone);}

?>

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
