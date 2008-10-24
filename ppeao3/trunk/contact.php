<?php 
// Mis à jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="contacter";
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
			<div id="ligne1">
				<div id="imageTop"><img src="/assets/contact-ph1.jpg" alt=""/></div>
				<div id="contactTitre"><h1 class="contact">Peuplement de poissons et la p&ecirc;che artisanale des Ecosyst&egrave;mes estuariens, lagunaires ou continentaux d'Afrique de l'Ouest</h1>
				</div>
			</div>
			<br/>
			<br/>
			<br/>
			<br/>
			<div id="contentcontact">
				<p class="contact">La base PPEAO archive des informations sur les poissons, leur &eacute;cologie et leur exploitation par la p&ecirc;che artisanale de nombreux &eacute;cosyst&egrave;mes aquatiques de l'Afrique de l'Ouest.</p><br/>
				<p class="contactItalique">Elle a &eacute;t&eacute; con&ccedil;ue et r&eacute;alis&eacute;e par les membres de l'Unit de Recherche RAP (R&eacute;ponses adaptatives des populations et peuplements des poissons aux pressions de l'environnement) de l'IRD (Institut de Recherches pour le D&eacute;veloppement). </p><br/>
				<div id="listcontact"><h2>contact</h2>
				<ul class="contact">
					<li class="contact"><a href="#" onClick="o='@';o=' 	&#109;&#111;&#110;&#105;&#113;&#117;&#101;&#46;&#115;&#105;&#109;&#105;&#101;&#114;'+o;o='mailto:'+o;o+='ird.fr';this.href=o;"><script type="text/javascript"> <!-- o='@';o='&#109;&#111;&#110;&#105;&#113;&#117;&#101;&#46;&#115;&#105;&#109;&#105;&#101;&#114;'+o;o+='ird.fr';document.write(o);//--> </script>Monique SIMIER</a></li>
					<li class="contact"><a href="#" onClick="o='@';o='&#106;&#101;&#97;&#110;&#46;&#109;&#97;&#114;&#99;&#46;&#101;&#99;&#111;&#117;&#116;&#105;&#110;'+o;o='mailto:'+o;o+='ird.fr';this.href=o;"><script type="text/javascript"> <!-- o='@';o='&#106;&#101;&#97;&#110;&#46;&#109;&#97;&#114;&#99;&#46;&#101;&#99;&#111;&#117;&#116;&#105;&#110;'+o;o+='ird.fr';document.write(o);//--> </script>Jean-Marc ECOUTIN</a></li>
				</ul>
				</div>
				<br/>
				<p class="contact">Vous trouverez ici le lien vers les personnes/soci&eacute;t&eacute;s ayant particip&eacute; à la cr&eacute;ation de ce site : <br/><a href="/contactRT.php" >R&eacute;alisation techniques</a>
				</p>
				<br/>
				<br/>
				<div id="logo">
					<div id="logo1"><img src="/assets/ird-logo.jpg" alt="IRD"/></div>
					<div id="space1">&nbsp;</div>
					<div id="logo2"><img src="/assets/crh-logo.jpg" alt="CRH"/></div>
					<div id="space2">&nbsp;</div>
					<div id="logo3"><img src="/assets/ur-070-logo.jpg" alt="UR-070"/></div>
					<div id="space3">&nbsp;</div>
					<div id="logo4"><img src="/assets/ppeao-logo.jpg" alt="PPEAO"/></div>
				</div>
			</div>
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
