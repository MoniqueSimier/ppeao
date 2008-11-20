<?php 
//*****************************************
// gestion_doc.php
//*****************************************
// Created by Yann Laurent
// 2008-10-18 : creation
//*****************************************
// Ce script est le point d'entrée pour la gestion de la documentation 


// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="gerer";
$subsection="documentation";

$zone=6; // zone gestion de la documentation (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::gestion de la documentation</title>
			<script type="text/javascript" charset="iso-8859-15">
		/* <![CDATA[ */		
		window.addEvent('domready', function() {
			var mySlide = new Fx.Slide('vertical_slide');
			mySlide.hide();
			$('v_slidein').addEvent('click', function(e){
				e = new Event(e);
				mySlide.slideIn();
				e.stop();
			});
			 
			$('v_slideout').addEvent('click', function(e){
				e = new Event(e);
				mySlide.slideOut();
				e.stop();
			});
		
		});
	
		/* ]]> */
		</script>


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
// On effectue les actions avant le chargement des documents, ainsi
// automatiquement, tous changement est pris en compte.
	$ContentDiv = "";
	
	if (isset($_GET['action'])) {
		// Gestion des actions
		include $_SERVER["DOCUMENT_ROOT"].'/documentation/gerer_doc.php';
	
	}	
	if (isset($_GET['do'])) {
		// Gestion des actions
		include $_SERVER["DOCUMENT_ROOT"].'/documentation/doAction_doc.php';
	}


	if (isset($_POST['upload'])) {
		include $_SERVER["DOCUMENT_ROOT"].'/documentation/upload.php';
	}


	$labelCreate = "ajouter";
	$labelModify = "modifier";
	// Faire une interface générique avec deux listes du style
	$elementNiv1 = "peche_scientifique,peche_artisanale,statistique";
	$boutonNiv1 = "boutonPS,boutonPA,boutonSTAT";
	
	// On récupère le premier niveau de documentation
	if (getDocumentation("/peche_scientifique","variable","n","") == "") {
		$boutonPS = $labelCreate;
		$ActionPS = "create";
		$boutonSupplPS = "";
	} else {
		$boutonPS = $labelModify;
		$ActionPS = "modify";
		$boutonSupplPS = "&nbsp;<a id=\"ActionPA2\" class=\"link_button\" href=\"/gestion_doc.php?rep=peche_scientifique&amp;action=create\">".$labelCreate."</a>";
	}
	if (getDocumentation("/peche_artisanale","variable","n","") == "") {
		$boutonPA = $labelCreate;
		$ActionPA = "create";
		$boutonSupplPA = "";
	} else {
		$boutonPA = $labelModify;
		$ActionPA = "modify";
		$boutonSupplPA = "&nbsp;<a id=\"ActionPA2\" class=\"link_button\" href=\"/gestion_doc.php?rep=peche_artisanale&amp;action=create\">".$labelCreate."</a>";
	}
	if (getDocumentation("/statistique","variable","n","") == "") {
		$boutonSTAT = $labelCreate;
		$ActionSTAT = "create";
		$boutonSupplSTAT = "";
	} else {
		$boutonSTAT = $labelModify;
		$ActionSTAT = "modify";
		$boutonSupplSTAT = "&nbsp;<a id=\"ActionPA2\" class=\"link_button\" href=\"/gestion_doc.php?rep=statistique&amp;action=create\">".$labelCreate."</a>";
	}

	echo "<form id=\"formSel\" name=\"form_selection\" method=\"get\" action=\"gestion_doc.php\">";

	echo "<h2>Vous pouvez choisir de consulter les documentations pour les pays en choisissant l'un des types de donn&ecirc;es &agrave; g&ecirc;rer</h2>";
	echo "<ul class=\"listDoc\"><li class=\"selDoc\"><span class=\"doccol1\" ><input type=\"checkbox\" name=\"type\" value=\"scientifique\"/>Donn&eacute;es de p&ecirc;che scientifique&nbsp;</span><span class=\"doccol2\" ><a id=\"ActionPS\" class=\"link_button\" href=\"/gestion_doc.php?rep=peche_scientifique&amp;action=".$ActionPS."\">".$boutonPS."</a>".$boutonSupplPS."</span>";
	echo "</li>";	
	if (isset($_GET['type']) ) {
		include $_SERVER["DOCUMENT_ROOT"].'/documentation/get_payssys_doc.php';
	}


	echo"<li class=\"selDoc\"><span class=\"doccol1\" ><input type=\"checkbox\" name=\"type_donnees\" value=\"artisanale\"/>Donn&eacute;es de p&ecirc;che artisanale&nbsp;</span><span class=\"doccol2\" ><a id=\"ActionPA\" class=\"link_button\" href=\"/gestion_doc.php?rep=peche_artisanale&amp;action=".$ActionPA."\">".$boutonPA."</a>".$boutonSupplPA."</span></li>";
	echo"<li class=\"selDoc\"><span class=\"doccol1\" ><input type=\"checkbox\" name=\"type_donnees\" value=\"statistique\"/>Donn&eacute;es de statistiques de p&ecirc;che&nbsp;</span><span class=\"doccol2\" ><a id=\"ActionSTAT\" class=\"link_button\" href=\"/gestion_doc.php?rep=statistique&amp;action=".$ActionSTAT."\">".$boutonSTAT."</a>".$boutonSupplSTAT."</span></li></ul>";
echo "<input type=\"submit\" name=\"choix\" value=\"Valider\">";
	echo"</form>";

	if (!isset($_GET['type'])) {
		// Documentation disponible pour les différents types de peche
		$doc = "";
		$doc = getDocumentation("peche_scientifique","variable","y","Peche Scientifique");
		if ( $doc =="") {
			$doc =getDocumentation("peche_artisanale","variable","y","Peche Artisanale");
		} else {
			$doc .="<br/>".getDocumentation("peche_artisanale","variable","y","Peche Artisanale");
		}
		if ( $doc =="") {
			$doc = getDocumentation("statistique","variable","y","Statistiques");
		} else {
			$doc .="<br/>".getDocumentation("statistique","variable","y","Statistiques");
		}
		if ( ! $doc =="") {
			$DocDiv= $doc;
		}
	}
	// DocDiv peut venir d'autres includes, notamment quand les pays ou les systemes sont selectionnes
	if (!$DocDiv=="") {
		echo"<div id=\"listdocdispo\">";
		displayDocumentation($DocDiv,"Liste des documentations disponibles pour les peches ci-dessus");
		echo"</div>";	
	}
	echo"<div id=\"select_hints\" class=\"hints\"><span class=\"hint_label\">aide : </span><span class=\"hint_text\">Pour modifier ou cr&eacute;er la documentation associ&eacute;e à un &eacute;l&eacute;ment, cliquer sur le bouton en fin de ligne.</span></div>";
		
			
	echo $ContentDiv;	

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
