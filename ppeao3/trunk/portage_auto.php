<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
// definit a quelle section appartient la page
$section="portage";
// definit la valeur de variables utilisees pour mettre la section courante en surbrillance dans le menu
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
?>

<?
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<?
		// les balises meta communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/meta.inc';
		?>
		<title>PPEAO Portage automatique</title>
		<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />
		<script src="/js/ajaxProcessAuto.js" type="text/javascript" charset="utf-8"></script>
		<script src="/js/basic.js" type="text/javascript" charset="utf-8"></script>
	</head> 
	<body>
		<?
		// le menu horizontal
		include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc'
		?>
		<div id="main_container" class="home">
			<div id="BDDetail">
				<? $subsection="auto"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
				<? include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
			</div>
			<div id="subContent">
				<h1>Base de Données PPEAO</h1>
				<p>Peuplements de poissons et Pêche artisanale des Ecosystèmes estuariens,
				lagunaires ou continentaux d’Afrique de l’Ouest</p>
				<br/>
				<br/>		
				<p>Ce processus permet un portage automatique des bases issues des bases access dans la base principale PPEAO.</p>
				<br/>
				<? include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
					logWriteTo(4,"notice","*** Ouverture page portage automatique","","","0");
				?>
				<div id="runProcess">
				<form>
				<input id="startProcess" type="button" value="Lancer le traitement" onClick="runProcess()"/>
				</form>
				</div>
				<div id="titleProcess">Détail des process en cours</div>
				<br/>
				<div id="sauvegarde"><div id="sauvegarde_img"><img src="/assets/incomplete.png" alt=""/></div><div id="sauvegarde_txt">Sauvegarde en cours</div></div>
				<div id="comparaison"><div id="comparaison_img"><img src="/assets/incomplete.png" alt=""/></div><div id="comparaison_txt">Comparaison en cours</div></div>
				<div id="copieScientifique"><div id="copieScientifique_img"><img src="/assets/incomplete.png" alt=""/></div><div id="copieScientifique_txt">Copie des données scientifiques en cours</div></div>
				<div id="processAuto"><div id="processAuto_img"><img src="/assets/incomplete.png" alt=""/></div><div id="processAuto_txt">Process recalcul données en cours</div></div>
				<div id="copieRecomp"><div id="copieRecomp_img"><img src="/assets/incomplete.png" alt=""/></div><div id="copieRecomp_txt">Copie des données recomposées en cours</div></div>
				<div id="portageOK"><div id="portageOK_img"><img src="/assets/incomplete.png" alt=""/></div><div id="portageOK_txt">portage en cours</div></div>
				<div id="purge"><div id="purge_img"><img src="/assets/incomplete.png" alt=""/></div><div id="purge_txt">purge des données en cours</div></div>
			</div>
		</div>	
	
		<?
		include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';
		
		?>
	
	</body>
</html>
