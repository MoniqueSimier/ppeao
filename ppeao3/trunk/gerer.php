<?php 
// Mis à jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="gerer";
$subsection="";
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';

$zone=7; // zone gerer (voir table admin_zones)
?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
	<?php 
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<title>ppeao::g&eacute;rer</title>

	
</head>

<body>


<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
?>

<div id="main_container" class="home">
	<h1>Gestion</h1>

	<p>Cette section sert à gérer l'ensemble des données de PPEAO.</p>
<?php
//debug echo('+'.$_SESSION['s_ppeao_user_id'].'+');

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>
<h2><a href="/portage.php" title="portage">portage d'une base</a></h2>
<p>ici, description rapide du processus de portage</p>
<h2><a href="/edition_donnees.php" title="gestion des donn&eacute;es">gestion des donn&eacute;es</a></h2>
<p>vous permet de modifier, supprimer ou ajouter des valeurs dans les tables de donn&eacute;es.</p>
<h2><a href="/edition_reference.php" title="gestion des tables de r&eacute;f&eacute;rence">gestion des tables de référence</a></h2>
<p>vous permet de modifier, supprimer ou ajouter des valeurs dans les tables de r&eacute;f&eacute;rence.</p>
<h2><a href="/edition_param.php" title="gestion des tables de param&eacute;trage">gestion des tables de param&eacute;trage</a></h2>
<p>vous permet de modifier, supprimer ou ajouter des valeurs dans les tables de param&eacute;trage.</p>
<h2><a href="/edition_param.php" title="gestion des tables de param&eacute;trage">gestion des tables de param&eacute;trage</a></h2>
<p>vous permet de modifier, supprimer ou ajouter des valeurs dans les tables de param&eacute;trage.</p>
<h2><a href="/edition_admin.php" title="gestion des tables d&#x27;administration">gestion des tables d&#x27;administration</a></h2>
<p>vous permet de modifier, supprimer ou ajouter des valeurs dans les tables tables d&#x27;administration.</p>
<h2><a href="/gestion_doc.php" title="gestion de la documentation">gestion des de la documentation</a></h2>
<p>vous permet de g&eacute;rer la documentation sur le projet PPEAO et les donn&eacute;es.</p>
<h2><a href="/journal.php" title="journal des op&eacute;rations">journal des op&eacute;rations</a></h2>
<p>vous permet de consulter le journal enregistrant l&#x27;ensemble des op&eacute;rations r&eacute;alis&eacute;es sur le site&nbsp;: connexions, interventions sur les donn&eacute;es, messages d&#x27;erreur...</p>
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
