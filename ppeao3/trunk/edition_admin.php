<?php 
// Cr�� par Olivier ROUX, 02-08-2008
// code commun � toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="gerer";
$subsection="administration";

$zone=1; // zone edition (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::g&eacute;rer::tables d&#x27;administration</title>
	
<script src="/js/edition.js" type="text/javascript"  charset="iso-8859-15"></script>

</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
?>

<div id="main_container" class="edition">
<h1>G&eacute;rer les tables d&#x27;administration</h1>
<!-- �dition des tables de r�f�rence -->
<?php

// on teste � quelle zone l'utilisateur a acc�s
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
// affiche un avertissement concernant l'utilisation de IE pour les outils d'administration
IEwarning();
?>


<?php

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';

?>



<?php
// on affiche la liste des tables
echo('<div id="tables_liste">');	
echo('<h2>contr&ocirc;le de l&#x27;extraction des donn&eacute;es</h2>');
echo('<ul>');
echo('<li><a href="/edition/edition_table.php?selector=no&editTable=acces_systemes">d&eacute;finir une date butoir particuli&egrave;re pour un syst&egrave;me</a></li>');
echo('<li><a href="/edition/edition_droits_acces.php">autoriser un utilisateur ou un groupe &agrave; acc&eacute;der &agrave; toutes les donn&eacute;es</a></li>');
echo('</ul>');
buildTableList("admin");

echo('</div>');

?>
	

<?php 
// note : on termine la boucle testant si l'utilisateur a acc�s � la page demand�e

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas acc�s ou n'est pas connect�, on affiche un message l'invitant � contacter un administrateur pour obtenir l'acc�s
else {userAccessDenied($zone);}

?>
</div> <!-- end div id="main_container"-->
<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
