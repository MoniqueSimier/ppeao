<?php 
// Mis à jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="contact";
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';

$zone=0; // zone publique (voir table admin_zones)
?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
	<?php 
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<title>ppeao::contact</title>

	
</head>

<body>

	<?php 
	// le menu horizontal
	include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';


	if (isset($_SESSION['s_ppeao_user_id'])){ // a implementer partout + deploiement de loginform_s.php et function_ppeao.php
		$userID = $_SESSION['s_ppeao_user_id'];
	} else {
		$userID=null;
	}
	
	// on teste à quelle zone l'utilisateur a accès
	if (userHasAccess($userID,$zone)) {
	?>

	<div id="main_container" class="home">
	<div id="main_contact">
	<h1 class="contact">Peuplement de poissons et la p&ecirc;che artisanale des Ecosyst&egrave;mes estuariens, lagunaires ou continentaux d'Afrique de l'Ouest</h1><br/>
	<p class="contact">La base PPEAO archive des informations sur les poissons, leur &eacute;cologie et leur exploitation par la p&ecirc;che artisanale de nombreux &eacute;cosyst&egrave;mes aquatiques de l'Afrique de l'Ouest.</p><br/>
	<p class="contactItalique">Elle a &eacute;t&eacute; con&ccedil;ue et r&eacute;alis&eacute;e par les membres de l'Unit de Recherche RAP (R&eacute;ponses adaptatives des populations et peuplements des poissons aux pressions de l'environnement) de l'IRD (Institut de Recherches pour le D&eacute;veloppement). </p><br/>
	<p class="contact"><span class="gras">contact</span>
	<ul class="contact">
		<li class="contact"><a href="#" onClick="o='@';o=' 	&#109;&#111;&#110;&#105;&#113;&#117;&#101;&#46;&#115;&#105;&#109;&#105;&#101;&#114;'+o;o='mailto:'+o;o+='ird.fr';this.href=o;"><script type="text/javascript"> <!-- o='@';o='&#109;&#111;&#110;&#105;&#113;&#117;&#101;&#46;&#115;&#105;&#109;&#105;&#101;&#114;'+o;o+='ird.fr';document.write(o);//--> </script>Monique SIMIER</a></li>
		<li class="contact"><a href="#" onClick="o='@';o='&#106;&#101;&#97;&#110;&#46;&#109;&#97;&#114;&#99;&#46;&#101;&#99;&#111;&#117;&#116;&#105;&#110;'+o;o='mailto:'+o;o+='ird.fr';this.href=o;"><script type="text/javascript"> <!-- o='@';o='&#106;&#101;&#97;&#110;&#46;&#109;&#97;&#114;&#99;&#46;&#101;&#99;&#111;&#117;&#116;&#105;&#110;'+o;o+='ird.fr';document.write(o);//--> </script>Jean-Marc ECOUTIN</a></li>
	</ul>
	</p>
	<p ><a href="/contactRT.php" >R&eacute;alisation techniques</a>
	</p><br/>
	<span class="logo1">IRD</span><span class="logo2">CRH</span><span class="logo3">
	<UR 070 RAP/span><span class="logo4">PPEAO</span>
	
	
		</div>
	</div> <!-- end div id="main_container"-->
	
	
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
