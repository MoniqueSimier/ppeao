
<?php

$home="";
$gerer="";
$consulter="";
$contacter="";
$sinformer="";
$portage="";
$donnees="";
$reference="";
$parametrage="";
$administration="";
$journal="";
$documentation="";
// adds " active" to the style of each menu entry to highlight the current one in the navigation menu
switch ($section) {
	case "home" : $home=" active";
	break;
	case "gerer" : $gerer=" active";
	break;
	case "consulter" : $consulter=" active";
	break;
	case "contacter" : $contacter=" active";
	break;
	case "sinformer" : $sinformer=" active";
	break;
}


?>

<!-- le menu de navigation horizontal -->
<div id="nav_container">
	<div id="nav_menu"><a href="/index.php" class="menu<?php echo($home)?>">accueil</a><a href="/extraction/preselection.php" class="menu<?php echo($consulter)?>">consulter</a><a href="/gerer.php" class="menu <?php echo($gerer)?>">g&eacute;rer</a><a href="/apropos.php" class="menu<?php echo($sinformer)?>">s&#x27;informer</a><a href="/contact.php" class="menu<?php echo($contacter)?>">contacter</a>
	</div> <!-- end div id="nav_menu" -->
</div> <!-- end div id="nav_container" -->

<!-- le sous-menu de navigation, si besoin -->
<?php
if ($section=='gerer') {
	switch ($subsection) {
		case "portage" : $portage=" active";
		break;
		case "maintenance" : $manintenance=" active";
		break;
		case "donnees" : $donnees=" active";
		break;
		case "reference" : $reference=" active";
		break;
		case "parametrage" : $parametrage=" active";
		break;
		case "administration" : $administration=" active";
		break;
		case "journal" : $journal=" active";
		break;
		case "documentation" : $documentation=" active";
		break;
	}
?>
<div id="nav_sousmenu"><a href="/portage.php" title="portage" class="sousmenu<?php echo($portage)?>">portage</a><a href="/edition_maintenance.php" title="maintenance" class="sousmenu<?php echo($maintenance)?>">maintenance</a><a href="/edition_donnees.php" title="donn&eacute;es" class="sousmenu<?php echo($donnees)?>">donn&eacute;es</a><a href="/edition_reference.php" title="tables de r&eacute;f&eacute;rence" class="sousmenu<?php echo($reference)?>">r&eacute;f&eacute;rence</a><a href="/edition_param.php" title="tables de param&eacute;trage" class="sousmenu<?php echo($parametrage)?>">param&eacute;trage</a><a href="/edition_admin.php" title="tables d&#x27;administration" class="sousmenu<?php echo($administration)?>">administration</a><a href="/journal.php" title="journal" class="sousmenu<?php echo($journal)?>">journal</a><a href="/gestion_doc.php" title="documentation" class="sousmenu<?php echo($documentation)?>">documentation</a>
</div> <!-- end div id="nav_sousmenu" -->
<?php

}

?>

<div id="login_block">
			<?php 
			// inclut le script de formulaire de login compact
			include ($_SERVER["DOCUMENT_ROOT"].'/session/loginform_s.php');
			?>
		</div> <!-- end div id="login_block" -->

