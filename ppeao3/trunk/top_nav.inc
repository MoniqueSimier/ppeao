<!-- le menu de navigation horizontal -->
<div id="nav_container">
	<div id="nav_menu"><a href="/index.php" class="menu <?phpecho($home)?>">home</a><a href="/edition.php" class="menu<?phpecho($edition)?>">&eacute;dition</a><a href="/portage.php" class="menu<?phpecho($portage)?>">portage</a><a href="/journal.php" class="menu<?phpecho($journal)?>">journal</a><a href="/contact.php" class="menu<?phpecho($contact)?>">contact</a></div></div>
<div id="login_block">
	<?php
	// inclut le script de formulaire de login compact
	include ($_SERVER["DOCUMENT_ROOT"].'/session/loginform_s.php');
	?>
</div> <!-- end div id="login_block" -->
