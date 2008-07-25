<?
// Creation by Yann Laurent 2008 - July - 01
// Handle vertical sub menu 

// adds " active" to the style of each menu entry to highlight the current one in the left navigation menu
$home="";
$auto="";
$manuel="";
$manuel_recomp="";
$manuel_agglo="";
switch ($subsection) {
	case "home" : $home=" active";
	break;
	case "auto" : $auto=" active";
	break;
	case "manuel_recomp" : $manuel_recomp=" active"; $manuel=" active";
	break;
	case "manuel_agglo" : $manuel_agglo=" active"; $manuel=" active";
	break;
	case "manuel" : $manuel=" active";
	break;
}

?>
<ul class="listemenu">
	<li class="listmenuitem"><a class="navbarlink<?echo($home)?>" href="/portage.php" >Accueil Portage</a></li>
	<li class="listmenuitem"><a class="navbarlink<?echo($auto)?>" href="/portage_auto.php" >Portage automatique</a></li>
	<li class="listmenuitem"><a class="navbarlink<?echo($manuel)?>" href="/portage_manuel.php" >Portage manuel</a>
	<ul id="port_manuel">
		<li class="sublistmenuitem"><a class="navbarlink<?echo($manuel_recomp)?>" href="/recomposition/rec_choix_base.php" >Recomposition donn&eacute;es</a></li>
		<li class="sublistmenuitem"><a class="navbarlink<?echo($manuel_agglo)?>" href="/statistiques/stat_choix_base.php" >Stats p&ecirc;che par aggl</a></li>
		<li class="sublistmenuitem" class="navbarlink">Statistiques de p&ecirc;che g&eacute;n&eacute;rales</li>
	</ul>
	</li>

</ul>
