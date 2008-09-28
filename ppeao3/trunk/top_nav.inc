
<?php

$home="";
$edition="";
$extraction="";
$portage="";
$contact="";
$journal="";
// adds " active" to the style of each menu entry to highlight the current one in the navigation menu
switch ($section) {
	case "home" : $home=" active";
	break;
	case "edition" : $edition=" active";
	break;
	case "extraction" : $extraction=" active";
	break;
	case "portage" : $portage=" active";
	break;
	case "contact" : $contact=" active";
	break;
	case "journal" : $journal=" active";
	break;
}

?>

<!-- le menu de navigation horizontal -->
<div id="nav_container">
	<div id="nav_menu"><a href="/index.php" class="menu<?php echo($home)?>">home</a><a href="/edition.php" class="menu<?php echo($edition)?>">&eacute;dition</a><a href="/extraction/preselection.php" class="menu<?php echo($extraction)?>">extraction</a><a href="/portage.php" class="menu<?php echo($portage)?>">portage</a><a href="/journal.php" class="menu<?php echo($journal)?>">journal</a><a href="/contact.php" class="menu<?php echo($contact)?>">contact</a></div></div>
<div id="login_block">
	<?php 
	// inclut le script de formulaire de login compact
	include ($_SERVER["DOCUMENT_ROOT"].'/session/loginform_s.php');
	?>
</div> <!-- end div id="login_block" -->
