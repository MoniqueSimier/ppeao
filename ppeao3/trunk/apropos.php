<?php 
// Mis à jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="sinformer";
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
	<title>ppeao::&agrave; propos de PPEAO</title>

	
</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';


if (isset($_SESSION['s_ppeao_user_id'])){ // a implementer partout + deploiement de loginform_s.php et function_ppeao.php
	$userID = $_SESSION['s_ppeao_user_id'];
} else {
	$userID=null;
}

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($userID,$zone)) {
?>
<div id="main_container" class="home">
	<h1>PPEAO</h1>
	<h2 style="text-align:center">Système d'informations sur les Peuplements de poissons et la P&ecirc;che artisanale<br> des Ecosyst&egrave;mes estuariens, lagunaires ou continentaux d&rsquo;Afrique de l&rsquo;Ouest</h2>
	<br>
	<div id="home_thumbs"><img src="/assets/home/1.jpg" width="120" height="70" alt="Pirogue de p&ecirc;che artisanale sur le lac de S&eacute;lingu&eacute; (Mali)" title="Pirogue de p&ecirc;che artisanale sur le lac de S&eacute;lingu&eacute; (Mali)">
<img src="/assets/home/2.jpg" width="120" height="70" alt="Nasses de p&ecirc;che artisanale, lac de S&eacute;lingu&eacute; (Mali)" title="Nasses de p&ecirc;che artisanale, lac de S&eacute;lingu&eacute; (Mali)">
<img src="/assets/home/3.jpg" width="120" height="70" alt="Paysage de mangrove, Estuaire de la Gambie" title="Paysage de mangrove, Estuaire de la Gambie">
<img src="/assets/home/4.jpg" width="120" height="70" alt="Lanche du Banc d'Arguin (Mauritanie)" title="Lanche du Banc d'Arguin (Mauritanie)">
<img src="/assets/home/5.jpg" width="120" height="70" alt="&Eacute;quipe de p&ecirc;che scientifique" title="&Eacute;quipe de p&ecirc;che scientifique">
<img src="/assets/home/6.jpg" width="120" height="70" alt="Pirogues de p&ecirc;che artisanale" title="Pirogues de p&ecirc;che artisanale"></div>
<br />
<div id="main_contact">
<div id="contentcontact">
<p class="texte">La base de donn&eacute;es PPEAO archive des informations sur les poissons, leur &eacute;cologie et leur exploitation par la p&ecirc;che artisanale. Les observations sont issues de nombreux &eacute;cosyst&egrave;mes aquatiques tant continentaux que lagunaires, estuariens ou c&ocirc;tiers d&rsquo;Afrique de l&rsquo;Ouest. Cette base de donn&eacute;es renferme des informations collect&eacute;es depuis 1978. Elle est r&eacute;guli&egrave;rement mise &agrave; jour. 
</p><br>
<p class="texte">Les peuplements de poissons de ces &eacute;cosyst&egrave;mes aquatiques ont &eacute;t&eacute; &eacute;chantillonn&eacute;s soit par des techniques de p&ecirc;che scientifique, soit par un suivi des p&ecirc;ches artisanales d&eacute;ploy&eacute;es sur ces milieux. </p>
<br>
<p class="texte">Le principe de cet archivage est de conserver l&rsquo;information telle qu&rsquo;elle a &eacute;t&eacute; collect&eacute;e et valid&eacute;e sur le terrain. Cependant, pour diminuer les temps de consultation, plusieurs &eacute;tapes de calcul concernant les statistiques de p&ecirc;che artisanale ont été effectu&eacute;es de fa&ccedil;on automatique.
</p>
</div>
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
