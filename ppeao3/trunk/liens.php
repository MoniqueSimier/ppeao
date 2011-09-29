<?php 
// Cr�� par Gaspard BERTRAND, 19/09/2011
// definit a quelle section appartient la page
$section="liens";
// code commun � toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';

$zone=0; // zone publique (voir table admin_zones)
?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
	<?php 
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<title>ppeao::contact</title>

	
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
	
	// on teste � quelle zone l'utilisateur a acc�s
	if (userHasAccess($userID,$zone)) {
	?>

	<div id="main_container" class="home">
	<h2>Liens Utiles</h2>
	<br>
<div id="home_thumbs"><img src="/assets/home/1.jpg" width="120" height="70" alt="1"><img src="/assets/home/2.jpg" width="120" height="70" alt="2"><img src="/assets/home/3.jpg" width="120" height="70" alt="3"><img src="/assets/home/4.jpg" width="120" height="70" alt="4"><img src="/assets/home/5.jpg" width="120" height="70" alt="5"><img src="/assets/home/6.jpg" width="120" height="70" alt="6"></div>
<br />
			
		<div id="main_contact">

			
				</div>
				<br/>
				<p class="contact">Ce projet a &eacute;t&eacute; rendu possible gr&acirc;ce aux financements de la <a href="https://www.ird.fr/dsi/" target="_blank">Direction du Syst�me d'Information de l'IRD</a>.
				</p>
				<p class="contact">Ce projet a &eacute;t&eacute; r&eacute;alis&eacute; par l'�quipe RAP qui appartient aujourd'hui au <a href="http://www-iuem.univ-brest.fr/UMR6539" target="_blank">LEMAR</a>.
				</p>
				<p class="contact">Pour plus d'informations sur les acteurs de la p�che en Afrique de l'Ouest, vous pouvez consulter le site du <a href="http://www.netvibes.com/reshal" target="_blank">reshal</a>, maintenu par le LEMAR.
				</p>
			</div>
		</div>
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
