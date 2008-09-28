<?php 
// Mis à jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="portage";
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';

$zone=3; // zone portage (voir table admin_zones)
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<?php 
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<title>ppeao::manipulation de donn&eacute;es</title>
	

</head> 
 <body>
<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

	if (isset($_SESSION['s_ppeao_user_id'])){ 
		$userID = $_SESSION['s_ppeao_user_id'];
	} else {
		$userID=null;
	}
	
	// on teste à quelle zone l'utilisateur a accès
	if (userHasAccess($userID,$zone)) {{
?>
<div id="main_container" class="home">
		<div id="BDDetail">
		<?php  $subsection="manuel"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
		<?php  include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
		</div>
		<div id="subContent">
		<h1>Base de donn&eacute;es PPEAO</h1>
		<br/>
		<p>Peuplements de poissons et P&ecirc;che artisanale des Ecosyst&egrave;mes estuariens,</p>
		<p>lagunaires ou continentaux d’Afrique de l’Ouest</p>
		<br/>
		<p>Cette section reprend les traitements manuel d&eacute;velopp&eacute;s dans le lot 2 PPEAO r&eacute;alis&eacute;s en 2007.</p>
		</div>	
	
</div>		<!-- end div id="main_container"-->


<?php 
// note : on termine la boucle testant si l'utilisateur a accs ˆ la page demandŽe

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accs ou n'est pas connectŽ, on affiche un message l'invitant ˆ contacter un administrateur pour obtenir l'accs
else {userAccessDenied($zone);}

?>

 </body>
</html>
