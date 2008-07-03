<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?
// definit a quelle section appartient la page
$section="home";
// definit la valeur de variables utilisees pour mettre la section courante en surbrillance dans le menu
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
?>

<?php

include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
	<?
	// les balises meta communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/meta.inc';
	?>
	<title>ppeao</title>
	<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />
	<script src="/js/basic.js" type="text/javascript" charset="utf-8"></script>

	
</head>

<body>

<?
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc'
?>

<div id="main_container" class="home">

Bienvenue &agrave; PPEAO.

<?

logWriteTo(1,'warning','ceci est un avertissement','vous avez fait ceci','et pour annuler, faire cela',0);
echo(logDisplayShort('2008-07-01','','',"toto",5,""));

?>
	
</div>

<?
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
