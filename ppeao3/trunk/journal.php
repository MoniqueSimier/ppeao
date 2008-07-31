<?
// Mis � jour par Olivier ROUX, 29-07-2008
// code commun � toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="journal";
$zone=1; // zone administration (voir table admin_zones)
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
	<?
	// les balises meta communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/meta.inc';
	?>
	<title>journal des activit&eacute;s</title>
	<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />
	<script src="/js/basic.js" type="text/javascript" charset="utf-8"></script>

	
</head>

<body>

<?
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

// on teste � quelle zone l'utilisateur a acc�s


//debug echo('user='.$_SESSION['s_ppeao_user_id'].'-la zone'.$zone.'<br />');

if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {


?>

<div id="main_container" class="home">

<h1>journal des activit&eacute;s</h1>

<?
logWriteTo(4,'notice','acc&egrave;s au journal','-','-',0);
echo(logDisplayFull('','','',"","",""));

?>
	
</div> <!-- end div id="main_container"-->


<?
// note : on termine la boucle testant si l'utilisateur a acc�s � la page demand�e

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas acc�s ou n'est pas connect�, on affiche un message l'invitant � contacter un administrateur pour obtenir l'acc�s
else {userAccessDenied($zone);}

?>

<?
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
