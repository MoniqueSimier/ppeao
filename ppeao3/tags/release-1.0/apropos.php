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
	<h2>Système d'informations sur les Peuplements de poissons et la P&ecirc;che artisanale des Ecosyst&egrave;mes estuariens, lagunaires ou continentaux d&rsquo;Afrique de l&rsquo;Ouest</h2>
	<div id="home_thumbs"><img src="/assets/home/1.jpg" width="120" height="70" alt="1"><img src="/assets/home/2.jpg" width="120" height="70" alt="2"><img src="/assets/home/3.jpg" width="120" height="70" alt="3"><img src="/assets/home/4.jpg" width="120" height="70" alt="4"><img src="/assets/home/5.jpg" width="120" height="70" alt="5"><img src="/assets/home/6.jpg" width="120" height="70" alt="6"></div>
<div id="main_contact">
<div id="contentcontact">
<p class="contact">La base PPEAO archive des informations sur les poissons, leur &eacute;cologie et leur exploitation par la p&ecirc;che artisanale de nombreux &eacute;cosyst&egrave;mes aquatiques d&rsquo;Afrique de l&rsquo;Ouest. Les peuplements de poissons de ces &eacute;cosyst&egrave;mes ont &eacute;t&eacute; &eacute;chantillonn&eacute;s soit par des techniques de p&ecirc;che scientifique, soit par un suivi des p&ecirc;ches artisanales d&eacute;velopp&eacute;es sur ces milieux. Cette base archive des informations collect&eacute;es &agrave; partir de 1978 et est r&eacute;guli&egrave;rement mise &agrave; jour.</p><br/>
<p class="contact">Le principe de cet archivage est de conserver l&rsquo;information telle qu&rsquo;elle a &eacute;t&eacute; collect&eacute;e sur le terrain. Pour diminuer les temps de consultation, plusieurs &eacute;tapes de calcul des statistiques de p&ecirc;che artisanale sont r&eacute;alis&eacute;es de fa&ccedil;on automatique en routine.
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
