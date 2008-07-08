<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
// Mis à jour Yann LAURENT, 01-07-2008
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
	<title>PPEAO Recomposition des données : choix BD</title>
	<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />
	<script src="/js/basic.js" type="text/javascript" charset="utf-8"></script>
		
	<script src="/js/ajaxEfface.js"></script>
	<SCRIPT type="text/javaScript">
	
	
	function fenetre(){
	fenetre = open("ppeao1.php","Resultats","scrollbars=1,menubar=1, status=1, height=600,width=400,left=10,top=20,resizable=yes");
	}
	
	dimensions="width="+(screen.width/3)+",height="+(screen.width/3)+",top=50,left=100,screenY=50, screenX=100, resizable=no";
	
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
		// A virer a passer dans l'include commun connect.inc
		$bdd = $_POST['base'];
		// le menu horizontal
		include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc'
		?>
		<div id="main_container" class="home">
			<div id="BDDetail">
			<? $subsection="manuel_recomp"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
			<? include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
			</div>
			<div id="subContent">
			
			<? // Code original a mettre à jour ?>
			<div id='headerinfo'>
			<h1>Recomposition automatique des données d’enquêtes.</h1><br/>
			</div>
			Une enquête de pêche est l'opération élémentaire d'observation des débarquements. Dans un cas idéal, toutes les informations demandées sont relevées par l'enquêteur. Dans la plupart des cas, une partie de l'information manque.
			Le but de ce module est de recomposer toutes les enquêtes, une par une, pour obtenir des enquêtes idéales. Cette recomposition comprend 3 phases : 
			<ul>
				<li>une estimation du nombre et du poids des poissons d'une fraction dite débarquée</li>
				<li>une comparaison des poids des fractions débarquées avec le poids total du débarquement annoncé par l'enquêteur</li>
				<li>la prise en compte éventuelle de fractions non observées directement par l'enquêteur.</li>
			</ul>

			<br/><br/>
			<div id="dbinfo">
			<h2>La Base PPEAO contient :</h2>

			<?	include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';

			//////////////////////////////////////////////////////////////////////////////////////////////
			//                      Récupération du nb d'enregistrements à traiter                      //
			//                                 et du nb déjà traités                                    //
			//////////////////////////////////////////////////////////////////////////////////////////////
			// A virer, a remplacer par $connectPPEAO
			$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) {  echo "Une erreur de connection au serveur s'est produite"; exit;}
			
			// Creation et envoi de la requete
			$query = "select count(art_debarquement.id) FROM art_debarquement";
			
			$result = pg_query($connection, $query);
			if (!$result) {  echo "Une erreur s'est produite";  exit;}
			
			
			// Recuperation du resultat
			$row= pg_fetch_row($result);
			$nb_enr = $row[0];
			print ("<div id='nbEnquete'>".$nb_enr . " enquêtes à traiter dont");
			
			// Deconnexion de la base de donnees
			pg_close();
			
			
			
			$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			$query = "select count(id) FROM art_debarquement_rec";
			$result = pg_query($connection, $query);
			$row= pg_fetch_row($result);
			$nb_deja_rec = $row[0];
			if ($nb_deja_rec == 0){print (" ".$nb_deja_rec . " enquête déjà recomposée. </div>");}
			else {print (" ".$nb_deja_rec . " enquêtes déjà recomposées. </div>");}
			pg_close();
			?>
			
			<div id="formRecomp">
			<br/>
			<form name="form" >
			  <p>
				Vous pouvez entrer une adresse mail.<br>
				<input type="text" name="adresse" id="adresse">
				<br/>
				Si vous rentrez une adresse valide, 
				il vous sera envoyé un mail de confirmation à la fin de la recomposition des données.<br/><br/>
				
				<input type="hidden" name="nb_enr" value="<? print($nb_enr);?>" >
				<input type="hidden" name="base" value="<? print($bdd);?>" >
				<!--<input type="submit" name="Recomposition" value="Recomposer les données" onClick="pop_it3(form);">-->
			 	<input type="button" name="Recomposition" value="Recomposer les données" onClick="runRecomp('<? print($nb_enr);?>','<? print($bdd);?>');">
			  </p>
			</form>
			</div>
			
			<div id="formEfface">
			<form >
				<input type="button" name="Effacement" value="Effacer les données recomposées" onClick="runClear('<? print($bdd);?>')"/>
			</form>
			</div>
			<div id="formEffaceResult"> <? // Pour y mettre le resultat de l'effacement des données ! ?>

			</div>			
			
		</div>
	</div>

	<? 
	// fin du code d'origine
	include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';
	
	?>
	</body>
</html>



