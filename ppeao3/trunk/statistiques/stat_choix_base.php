<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
// Mis à jour Yann LAURENT, 07-07-2008
// definit a quelle section appartient la page
$section="portage";
// definit la valeur de variables utilisees pour mettre la section courante en surbrillance dans le menu
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
?>

<?

include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<?
	// les balises meta communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/meta.inc';
	?>
	<title>PPEAO Recomposition des donn&eacute;es</title>
	<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />
	<script src="/js/basic.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/ajaxStat.js"></script>	
	</head>
	<body>
		 <?
		// le menu horizontal
		include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc'
		?>
		<div id="main_container" class="home">
			<div id="BDDetail">
			<? $subsection="manuel_agglo"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
			<? include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
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
		</div>
	</body>
</html>