<?php 
// Créé par Yann Laurent, 18-10-2008
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="gestDoc";
$zone=6; // zone edition (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::gestion de la documentation</title>
	


</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<div id="main_container" class="home">

<?php

//Include for documentation
include $_SERVER["DOCUMENT_ROOT"].'/documentation/functions_doc.php';

?>

<h1 >gestion de la documentation</h1>
<!-- gestion de la documentation -->


<br/>

<?php
	$labelCreate = "cr&eacute;er";
	$labelModify = "modifier";
	// Faire une interface générique avec deux listes du style
	$elementNiv1 = "peche_scientifique,peche_artisanale,statistique";
	$boutonNiv1 = "boutonPS,boutonPA,boutonSTAT";
	
	// On récupère le premier niveau de documentation
	if (getDocumentation("/peche_scientifique","variable","n","") == "") {
		$boutonPS = $labelCreate;
		$ActionPS = "create";
	} else {
		$boutonPS = $labelModify;
		$ActionPS = "modify";
	}
	if (getDocumentation("/peche_artisanale","variable","n","") == "") {
		$boutonPA = $labelCreate;
		$ActionPA = "create";
	} else {
		$boutonPA = $labelModify;
		$ActionPA = "modify";
	}
	if (getDocumentation("/statistique","variable","n","") == "") {
		$boutonSTAT = $labelCreate;
		$ActionSTAT = "create";
	} else {
		$boutonSTAT = $labelModify;
		$ActionSTAT = "modify";
	}


	echo "<form id=\"formSel\" name=\"form_selection\" method=\"post\" action=\"gestion_doc.php\">";

	echo "<h2>Vous pouvez choisir de consulter les documentations pour les pays en choisissant l'un des types de donn&ecirc;es &agrave; g&ecirc;rer</h2>";
	echo "<ul class=\"listDoc\"><li class=\"selDoc\"><span class=\"doccol1\" ><input type=\"radio\" name=\"type\" value=\"scientifique\"/>Donn&eacute;es de p&ecirc;che scientifique&nbsp;</span><span class=\"doccol2\" ><a id=\"ActionPS\" class=\"link_button\" href=\"documentation/gerer_doc.php?rep=peche_scientifique&amp;action=".$ActionPS."\">".$boutonPS."</a></span></li>";
	echo"<li class=\"selDoc\"><span class=\"doccol1\" ><input type=\"radio\" name=\"type\" value=\"artisanale\"/>Donn&eacute;es de p&ecirc;che artisanale&nbsp;</span><span class=\"doccol2\" ><a id=\"ActionPA\" class=\"link_button\" href=\"documentation/gerer_doc.php?rep=/peche_artisanale&amp;action=".$ActionPA."\">".$boutonPA."</a></span></li>";
	echo"<li class=\"selDoc\"><span class=\"doccol1\" ><input type=\"radio\" name=\"type\" value=\"statistique\"/>Donn&eacute;es de statistiques de p&ecirc;che&nbsp;</span><span class=\"doccol2\" ><a id=\"ActionSTAT\" class=\"link_button\" href=\"documentation/gerer_doc.php?rep=/statistique&amp;action=".$ActionSTAT."\">".$boutonSTAT."</a></span></li></ul>";

	echo"</form>";
	echo('<div id="select_hints" class="hints"><span class="hint_label">aide : </span><span class="hint_text">Pour modifier ou cr&eacute;er la documentation associ&eacute;e à un &eacute;l&eacute;ment, cliquer sur le bouton en fin de ligne.</span></div>');
				?>

</div>
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
