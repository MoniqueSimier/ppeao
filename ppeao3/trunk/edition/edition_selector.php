<?php 
// Créé par Olivier ROUX, 02-08-2008
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="edition";
$zone=2; // zone edition (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::&eacute;dition des donn&eacute;es::s&eacute;lection de la table &agrave; &eacute;diter</title>

<script src="/js/edition.js" type="text/javascript" charset="iso-8859-15"></script>

</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<div id="main_container" class="home">

<?php

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';


?>

<div id="selector_container">

<?php
	// insertion du sélecteur, en mode "page de selection"
	createSelector("selection");
?>
</div> <!-- end div selector_container -->


<?php

$theType=$_GET["type"];
$theHierarchy=$_GET["hierarchy"];
$targetTable=$_GET["targetTable"];

switch ($theType) {
	case "reference" : $theTypeString=" de r&eacute;f&eacute;rence"; $theSelectorType="tableSelectors";
	break;
	case "parametrage" : $theTypeString=" de param&eacute;trage"; $theSelectorType="tableSelectors";
	break;
	default: $theTypeString="";
	break;
		}
$theLogString=' : '.$theTypeString.' '.${$theSelectorType}[$theHierarchy][$targetTable]["label"];


logWriteTo(1,'notice','acc&egrave;s &agrave; l\'&eacute;dition de la table de '.$theLogString,'','',0);

?>
	
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
