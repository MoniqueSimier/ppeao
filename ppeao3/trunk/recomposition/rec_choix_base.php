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
	<?
	// les balises meta communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/meta.inc';
	?>
	<title>PPEAO Recomposition des données</title>
	<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />
	<script src="/js/basic.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
	
	function pop_it3(the_form) {
	   my_form = eval(the_form);
	   window.open("blanc.html", "popup", "height=300,width=500,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes");
	   my_form.target = "popup";
	   my_form.submit();
	}
	
	
	</script>
	</head>
	<body>
		 <?
		// le menu horizontal
		include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc'
		?>
		<div id="main_container" class="home">
			<div id="BDDetail">
			<? $subsection="manuel_recomp"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
			<? include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
			</div>
			<div id="subContent">
				<h1>Recomposition des données</h1>
				<h2>Choix de la base</h2>
				<br/>
				Entrez le nom de la base:
				<br/>
				<form name="form" method="post" action="rec_appel.php" >
					<p>
					<input type=text name="base"/>
					<br/>
					<input type="submit" name="sss" value="valider"/>
					 </p>
				</form>
		</div>
	</div>

<?
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>



</body>
</html>