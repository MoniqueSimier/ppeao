<?
// Mis ˆ jour par Olivier ROUX, 29-07-2008
// code commun ˆ toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="portage";
$zone=3; // zone portage (voir table admin_zones)
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<?
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<title>PPEAO Manipulation de donn&eacute;es</title>
	

</head> 
 <body>
<?
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

// on teste ˆ quelle zone l'utilisateur a accs
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>
<div id="main_container" class="home">
		<div id="BDDetail">
		<? $subsection="manuel"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
		<? include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
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


<?
// note : on termine la boucle testant si l'utilisateur a accs ˆ la page demandŽe

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accs ou n'est pas connectŽ, on affiche un message l'invitant ˆ contacter un administrateur pour obtenir l'accs
else {userAccessDenied($zone);}

?>

 </body>
</html>
