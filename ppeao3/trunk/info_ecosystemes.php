<?php 
// Créé par Gaspard BERTRAND, 30/11/2011
// definit a quelle section appartient la page
$section="apropos";
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
	
	// on teste à quelle zone l'utilisateur a accès
	if (userHasAccess($userID,$zone)) {
	?>

	<div id="main_container" class="home">
	<h1>PPEAO</h1>
	<h2 style="text-align:center">Système d'informations sur les Peuplements de poissons et la P&ecirc;che artisanale<br> des Ecosyst&egrave;mes estuariens, lagunaires ou continentaux d&rsquo;Afrique de l&rsquo;Ouest</h2>
    <br>
	<h2>P&ecirc;che artisanale</h2>
	
<table cellpadding="5" border=1 >
		<th align="right">Pays</th>
		<th align="right">Ecosyst&egrave;me</th>
		<th align="right">Date d&eacute;but</th>
		<th align="right">Date fin</th>	</th>
	<tr align="right">
		<td>The Gambia</td>
		<td>Gambia River</td>
		<td>O4/2001</td>
		<td>05/2002</td>
	</tr>
	<tr align="right">
		<td>Mali</td>
		<td>lac de Manantali</td>
		<td>06/1995</td>
		<td>08/1995</td>
	</tr>
	<tr align="right">
		<td>Mali</td>
		<td>lac de Manantali</td>
		<td>05/2002</td>
		<td>05/2003</td>
	</tr>
	<tr align="right">
		<td>Mali</td>
		<td>lac de Selingue</td>
		<td>08/1994</td>
		<td>10/1994</td>
	</tr>
	<tr align="right">
		<td>Mali</td>
		<td>lac de Selingue</td>
		<td>05/2002</td>
		<td>06/2003</td>
	</tr>
	<tr align="right">
		<td>Senegal</td>
		<td>Casamance</td>
		<td>04/2005</td>
		<td>08/2005</td>
	</tr>
</table>
	
<br>
<br>

<h2>P&ecirc;che scientifique</h2>
<table cellpadding="5" border=1 >
		<th align="right">Pays</th>
		<th align="right">Ecosyst&egrave;me</th>
		<th align="right">Campagnes</th>
		<th align="right">Date d&eacute;but</th>
		<th align="right">Date fin</th>	</th>
	<tr align="right">
		<td>The Gambia</td>
		<td>Gambia River</td>
		<td>13</td>
		<td>24/11/2000</td>
		<td>24/11/2003</td>
	</tr>
	<tr align="right">
		<td>Guinea</td>
		<td>Dangara</td>
		<td>7</td>
		<td>29/01/1993</td>
		<td>24/01/1994</td>
	</tr>
	<tr align="right">
		<td>Guinea</td>
		<td>Fatala</td>
		<td>13</td>
		<td>22/01/1993</td>
		<td>22/03/1994</td>
	</tr>
	<tr align="right">
		<td>Guinea-Bissau</td>
		<td>Bijagos</td>
		<td>1</td>
		<td>22/03/1993</td>
		<td>01/04/1993</td>
	</tr>
	<tr align="right">
		<td>Guinea-Bissau</td>
		<td>Rio Buba</td>
		<td>1</td>
		<td>03/04/1993</td>
		<td>07/04/1993</td>
	</tr>
	<tr align="right">
		<td>Ivory Coast</td>
		<td>Ebrié</td>
		<td>73</td>
		<td>17/12/1979</td>
		<td>31/08/1982</td>
	</tr>
	<tr align="right">
		<td>Mali</td>
		<td>Manantali</td>
		<td>3</td>
		<td>19/06/2002</td>
		<td>06/10/2003</td>
	</tr>
	<tr align="right">
		<td>Mali</td>
		<td>Selingue</td>
		<td>3</td>
		<td>10/06/2002</td>
		<td>15/10/2003</td>
	</tr>
	<tr align="right">
		<td>Mauritanie</td>
		<td>Banc Arguin</td>
		<td>3</td>
		<td>07/05/2008</td>
		<td>28/05/2010</td>
	</tr>
	<tr align="right">
		<td>Senegal</td>
		<td>Sine Saloum</td>
		<td>63</td>
		<td>20/04/1990</td>
		<td>26/10/2007</td>
	</tr>
	<tr align="right">
		<td>Senegal</td>
		<td>Bamboung</td>
		<td>25</td>
		<td>11/03/2003</td>
		<td>17/03/2011</td>
	</tr>
</table>

	
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
