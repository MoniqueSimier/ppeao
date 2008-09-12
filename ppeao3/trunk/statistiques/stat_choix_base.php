<?php
// Mis à jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="portage";
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
$zone=3; // zone portage (voir table admin_zones)
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<?php
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<title>ppeao::calcul des statistiques</title>
	<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />
	<script src="/js/basic.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/ajaxStat.js"></script>	
	</head>
	<body>
		 <?php
		// le menu horizontal
		include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
			// on teste à quelle zone l'utilisateur a accès
			if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
		?>
		<div id="main_container" class="home">
			<div id="BDDetail">
			<?php $subsection="manuel_agglo"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
			<?php include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
			</div>
			<div id="subContent">
				<div id="formStat">
					<h1>Calcul des statistiques de p&ecirc;che par agglom&eacute;ration enqu&ecirc;t&eacute;e.</h1>
					<form name="form"  >
					  <p>
						<br/>
						Entrez le nom de la base de donn&eacute;es &agrave; traiter.<br>
						<input type="text" name="base" id="base"/>
						<br/>
						Entrez une adresse mail.<br>
						
						
						<input type="text" name="adresse" id="adresse"/>
						<br/>
						Si vous rentrez une adresse valide, 
						il vous sera envoy&eacute; un mail de confirmation &agrave; la fin de la cr&eacute;ation des statistiques de p&ecirc;che.<br/>
						<br/>
					 <input type="button" value="lancer le calcul" onClick="runStat();"/>
					  </p>
					</form>
				</div>
				<div id="formStatResult"> <? // Pour y mettre le resultat du calcul des stats ! ?>
	
				</div>
			</div>	
		</div>			<!-- end div id="main_container"-->


<?php
// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}

?>
	</body>
</html>