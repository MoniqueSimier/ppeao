<?php 
// Cr�� par Gaspard BERTRAND, 30/11/2011
// definit a quelle section appartient la page
$section="apropos";
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
	<title>ppeao::info_ecosystemes</title>

	
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
	<h1>Projet PPEAO</h1>
	<h2>P&ecirc;che artisanale</h2>
	<br>
<table>
	<tr>
		<td>Pays</td>
		<td>Ecosyst&egrave;me</td>
		<td>Date d&eacute;but</td>
		<td>Date fin</td>
	</tr>
	<tr>
		<td>Mali</td>
		<td>lac de Manantali</td>
		<td>2002</td>
		<td>2004</td>
	</tr>
</table>
<br>
<br>

<h2>P&ecirc;che scientifique</h2>



	
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
