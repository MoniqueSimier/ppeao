<?php 
// Mis à jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="home";
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';

$zone=0; // zone publique (voir table admin_zones)
?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
	<?php 
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<title>ppeao::accueil</title>

	
</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
?>

<div id="main_container" class="home">

<h1>Bienvenue &agrave; PPEAO</h1>
<h2>Système d'informations sur les Peuplements de poissons et la P&ecirc;che artisanale des Ecosyst&egrave;mes estuariens, lagunaires ou continentaux d&rsquo;Afrique de l&rsquo;Ouest</h2>

<?php
//debug echo('+'.$_SESSION['s_ppeao_user_id'].'+');

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<?php 

//echo(logDisplayShort('','','',"",5,""));

?>
<div id="home_thumbs"><img src="/assets/home/1.jpg" width="120" height="70" alt="1"><img src="/assets/home/2.jpg" width="120" height="70" alt="2"><img src="/assets/home/3.jpg" width="120" height="70" alt="3"><img src="/assets/home/4.jpg" width="120" height="70" alt="4"><img src="/assets/home/5.jpg" width="120" height="70" alt="5"><img src="/assets/home/6.jpg" width="120" height="70" alt="6"></div>
<p class="texte">Durant les 30 derni&egrave;res ann&eacute;es, de nombreux travaux ont &eacute;t&eacute; men&eacute;s &agrave; l&rsquo;IRD sur l&rsquo;environnement, les peuplements de poissons et les activit&eacute;s de p&ecirc;che, dans diff&eacute;rents &eacute;cosyst&egrave;mes estuariens, lagunaires et lacustres ouest-africains. Depuis sa cr&eacute;ation en 2001, l&#x27;Unit&eacute; de Recherches RAP (R&eacute;ponses Adaptatives des populations et peuplements de Poissons aux pressions de l&rsquo;environnement) de l&rsquo;IRD a eu pour priorit&eacute; de regrouper et d&rsquo;harmoniser les informations r&eacute;colt&eacute;es lors de ces diverses &eacute;tudes. Vous trouverez plus d&#x27;informations sur PPEAO dans la rubrique &quot;<a href="/apropos.php" title="s&#x27;informer'">s&#x27;informer</a>&quot;.</p>

<p class="texte">Ce site permet aux visiteurs de <a href="/extraction/preselection.php" title="consulter">consulter l&#x27;ensemble des donn&eacute;es</a> (historiques et actuelles) compil&eacute;es par le projet PPEAO.</p>
<p class="texte">Il permet &eacute;galement aux chercheurs responsables du projet de <a href="/gerer.php" title="g&eacute;rer">g&eacute;rer et importer des donn&eacute;es</a>.</p>






<?php 
// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}
?>

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</div> <!-- end div id="main_container"-->
</body>
</html>
