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

<h1>Bienvenue &agrave; PPEAO.</h1>

<?php
//debug echo('+'.$_SESSION['s_ppeao_user_id'].'+');

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<?php 

//echo(logDisplayShort('','','',"",5,""));

?>
<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed sagittis tempus mi. Etiam elit. In mauris ipsum, tincidunt id, suscipit in, volutpat quis, risus. Nam laoreet feugiat nisi. Donec dignissim risus fermentum urna. Suspendisse et eros sit amet nunc scelerisque egestas. Sed quis purus. Proin augue arcu, aliquam ut, molestie dictum, pulvinar varius, lectus. Fusce at dui imperdiet eros fringilla adipiscing. Duis placerat imperdiet massa. Vestibulum sit amet nibh. Cras est.</p>

<p>Donec convallis imperdiet ante. Duis sapien pede, vestibulum in, elementum at, vestibulum ac, libero. Sed arcu. Suspendisse interdum neque ac lorem. Donec sodales velit in ante. Maecenas iaculis metus. Nulla facilisi. In hac habitasse platea dictumst. In fringilla. Quisque feugiat tempor augue. Donec tristique accumsan mauris. Nulla molestie fringilla nunc. Sed nulla dui, interdum eu, vestibulum ac, ultrices vestibulum, urna. Pellentesque sagittis. Integer est nunc, molestie in, pretium vitae, cursus id, elit. Maecenas turpis est, commodo non, pellentesque ac, adipiscing ac, felis.</p>





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
