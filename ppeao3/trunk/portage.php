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
	<title>PPEAO Manipulation de données</title>
	<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />

</head> 
 <body>
 <?
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc'
?>
<div id="main_container" class="home">
		<div id="BDDetail">
		<? $subsection="home"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
		<? include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
		</div>
		<div id="subContent">
		<h1>Base de Données PPEAO</h1>
		<br/>
		<p>Cette section vous permet de lancer les traitements spécifiques sur les bases de données importées.</p>
		<br/>
		<p>Le traitement peut être soit manuel (traitement pas à pas sans sauvegarde) soit automatique (inclus les sauvegardes).</p>
		<ul class="list">
			<li class="listitem"><a href="/portage_auto.php" ><b>Portage automatique</b></a></li>
			<li class="listitem"><a href="/portage_manuel.php" ><b>Portage manuel</b></a>
		</ul>
		</div>	
	
</div>

<?
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>

 </body>
</html>
