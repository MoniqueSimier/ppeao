<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
// definit a quelle section appartient la page
$section="portage";
// definit la valeur de variables utilisees pour mettre la section courante en surbrillance dans le menu
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
?>

<?

include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<?
	// les balises meta communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/meta.inc';
	?>
	<title>PPEAO Manipulation de donn&eacute;es</title>
	<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />

</head> 
 <body>
 <?
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc'
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
	
</div>

<?
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>

 </body>
</html>
