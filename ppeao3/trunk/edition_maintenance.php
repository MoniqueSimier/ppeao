<?php 
// Cr�� par Olivier ROUX, 02-08-2008
// code commun � toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="gerer";
$subsection="maintenance";

$zone=1; // zone edition (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::g&eacute;rer::maintenance de la base</title>
	
<script src="/js/edition.js" type="text/javascript"  charset="iso-8859-15"></script>

</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
?>

<div id="main_container" class="home">
<h1>Op�rations de maintenance de la base : "<?php echo($base_principale) ?>"</h1>
<!-- �dition des tables de r�f�rence -->
<?php

// on teste � quelle zone l'utilisateur a acc�s
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>


<?php

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';

?>


<ul>
	<li><h2><a href="javascript:doMaintenance('sequences_ref_param');">mise &agrave; jour des s&eacute;quences des tables de r&eacute;f&eacute;rence et de param&eacute;trage</a></h2>
	<p>utilisez cet outil apr&egrave;s avoir import&eacute; en batch des donn&eacute;es de r&eacute;f&eacute;rence et de param&eacute;trage, pour mettre &agrave; jour la prochaine valeur de la s&eacute;quence des tables ayant des identifiants uniques g&eacute;n&eacute;r&eacute;s automatiquement.</p>
	</li>
	<li><h2><a href="javascript:doMaintenance('sequences_donnees');">mise &agrave; jour des s&eacute;quences des tables de donn�es</a></h2>
	<p>utilisez cet outil apr&egrave;s avoir import&eacute; en batch des donn&eacute;es de p&ecirc;che artisanale ou exp&eacute;rimentale, pour mettre &agrave; jour la prochaine valeur de la s&eacute;quence des tables ayant des identifiants uniques g&eacute;n&eacute;r&eacute;s automatiquement.</p>
	</li>
	<li><h2><a href="javascript:doMaintenance('vacuum');">VACUUM</a></h2>
	<p>utilisez cet outil apr&egrave;s avoir effectu&eacute; un grand nombre de suppressions dans la base de donn&eacute;es.</p>
	</li>
	<li><h2><a href="javascript:doMaintenance('reindex');">REINDEX</a></h2>
	<p>utilisez cet outil pour recr�er les index apr&egrave;s avoir effectu&eacute; un grand nombre d&#x27;insertions ou de suppressions dans la base de donn&eacute;es.</p>
	</li>
</ul>	

<div id="maintenance_output"></div>

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