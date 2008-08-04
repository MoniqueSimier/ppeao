<?php
// Mis à jour par Olivier ROUX, 29-07-2008
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="portage";
$zone=3; // zone portage (voir table admin_zones)
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<?php
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<title>PPEAO Manipulation de donn&eacute;es</title>

</head> 
 <body>
<?php
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<div id="main_container" class="home">
		<div id="BDDetail">
		<?php $subsection="home"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
		<?php include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
		</div>
		<div id="subContent">
		<h1>Base de donn&eacute;es PPEAO</h1>
		<br/>
		<p>Cette section vous permet de lancer les traitements sp&eacute;cifiques sur les bases de donn&eacute;es import&eacute;es.</p>
		<br/>
		<p>Le traitement peut &ecirc;tre soit manuel (traitement pas &agrave; pas sans sauvegarde) soit automatique (inclus les sauvegardes).</p>
		<ul class="list">
			<li class="listitem"><a href="/portage_auto.php" ><b>Portage automatique</b></a></li>
			<li class="listitem"><a href="/portage_manuel.php" ><b>Portage manuel</b></a>
		</ul>
		</div>	
	
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
