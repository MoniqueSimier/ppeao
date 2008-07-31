<!-- le menu de navigation horizontal -->
<div id="nav_container">
	<div id="nav_menu"><a href="/index.php" class="menu <?echo($home)?>">home</a><a href="/edition.php" class="menu<?echo($edition)?>">&eacute;dition</a><a href="/portage.php" class="menu<?echo($portage)?>">portage</a><a href="/journal.php" class="menu<?echo($journal)?>">journal</a><a href="/contact.php" class="menu<?echo($contact)?>">contact</a></div></div>
<div id="login_block">
	<?
	// inclut le script de formulaire de login compact
	include ($_SERVER["DOCUMENT_ROOT"].'/session/loginform_s.php');
	?>
</div> <!-- end div id="login_block" -->
