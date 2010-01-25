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

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<?php 

//echo(logDisplayShort('','','',"",5,""));
	nettoieLogExport();
?>
<div id="home_thumbs"><img src="/assets/home/1.jpg" width="120" height="70" alt="1"><img src="/assets/home/2.jpg" width="120" height="70" alt="2"><img src="/assets/home/3.jpg" width="120" height="70" alt="3"><img src="/assets/home/4.jpg" width="120" height="70" alt="4"><img src="/assets/home/5.jpg" width="120" height="70" alt="5"><img src="/assets/home/6.jpg" width="120" height="70" alt="6"></div>
<br />
<p class="texte">La base PPEAO archive des informations sur les poissons, leur &eacute;cologie et leur exploitation par la p&ecirc;che artisanale de nombreux &eacute;cosyst&egrave;mes aquatiques de l&rsquo;Afrique de l&rsquo;Ouest.
</p>

<p class="texte"><em>Elle a &eacute;t&eacute; con&ccedil;ue et r&eacute;alis&eacute;e par l&rsquo;Unit&eacute; de Recherches RAP (R&eacute;ponses adaptatives des populations et peuplements de poissons aux pressions de l&rsquo;environnement) de l&rsquo;IRD (Institut de Recherches pour le D&eacute;veloppement).</em></p>
<p class="texte"><em>Elle est accessible via le portail de l&rsquo;&eacute;coscope du CRH (Centre de Recherches Halieutiques de S&egrave;te, France)</em>.</p>






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
