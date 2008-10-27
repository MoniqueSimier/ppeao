<?php 
// Mis à jour par Yann Laurent 26/09/08, ajout gestion utilisateur + refonte design
// definit a quelle section appartient la page
$section="consulter";
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
$zone=6; // zone portage (voir table admin_zones)
?>

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
//Include for documentation
include $_SERVER["DOCUMENT_ROOT"].'/documentation/functions_doc.php';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<?php 
			// les balises head communes  toutes les pages
			include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
		?>
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
		<title>ppeao::extraction des donn&eacute;es</title>
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
			<?php
			$AfficheInfoDebug="n";
			include_once("../connect.inc");
			$connection = pg_connect ("host=".$host." dbname=".$db_default." user=".$user." password=".$passwd);
			if (!$connection) { echo "Pas de connection"; exit;}
			$type="";
			$type_donnees="";
			$annee_deb="";
			$annee_fin="";
			
			if (isset($_POST['type'])) {
				$type = $_POST['type'];
			}
			if (isset($_POST['type_donnees'])) {
				$type_donnees = $_POST['type_donnees'];
			}
			if (isset($_POST['annee_deb'])) {
				$annee_deb = $_POST['annee_deb'];
			}
			if (isset($_POST['annee_fin'])) {
				$annee_fin = $_POST['annee_fin'];
			}

			$entete = "consultation / extraction de donn&eacute;es";
			if(isset($_POST['type']))
				{
				if ($type=="artisanale")$entete = "consultation / extraction de donn&eacute;es de p&ecirc;che artisanales";
				else if ($type=="scientifique")$entete = "consultation / extraction de donn&eacute;es de p&ecirc;che scientifiques";
				else if ($type=="statistique")$entete = "consultation / extraction de donn&eacute;es statistiques";
				}
				
			?>
			<h1><?php  print($entete); ?></h1>
			<br/>
			<div id="versionTemp">
		Derni&egrave;re maj 10/2008 - version 2.4 JME modifi&eacute;e Yann Laurent  <?php //******************* A ENLEVER en final?>
		</div>
			
			<br/>
			<?php
			if($type==""){
				if ($AfficheInfoDebug=="y") {
					echo"Presel - 1<br/>";
				}
				echo "<form id=\"formSel\" name=\"form_selction\" method=\"post\" action=\"preselection.php\">";
				echo "<input type=\"hidden\" name=\"type\" value=\"".$type."\">";
				echo "<h2>S&eacute;lection du type de donn&eacute;es &agrave; traiter</h2>";
				echo "<div id=\"lisSel\"><ul class=\"listType\"><li class=\"selType\"><input type=\"radio\" name=\"type\" value=\"scientifique\"/>Donn&eacute;es de p&ecirc;che scientifique  </li>";
				echo"<li class=\"selType\"><input type=\"radio\" name=\"type\" value=\"artisanale\"/>Donn&eacute;es de p&ecirc;che artisanale  </li>";
				echo"<li class=\"selType\"><input type=\"radio\" name=\"type\" value=\"statistique\"/>Donn&eacute;es de statistiques de p&ecirc;che  </li>";
				echo"</ul></div><div id=\"butSel\"><input type=\"submit\" name=\"\" value=\"    Valider    \"></div>";
				echo"</form>";
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
					displayDocumentation($doc);
				}

				include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';
				exit;
			} //end if($type=="")

			$type_donnees= "brutes";


//////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                          //
//                         Sélection de du pays et du système                               //
//                                                                                          //
//////////////////////////////////////////////////////////////////////////////////////////////



		//$query = "select distinct RP.nom from ref_pays as RP";
		$query = "select distinct RP.nom from ref_pays as RP, ref_systeme as RF, exp_campagne as EC where 
		RF.ref_pays_id=RP.id
		and EC.ref_systeme_id=RF.id";
		
		$result = pg_query($connection, $query);
		
		while($row = pg_fetch_row($result))
			{
			$ST[] = $row[0];	//pays
			}
		
		// Deconnexion de la base de donnees
		//pg_close();

		if (isset($_POST['annee']))$annee = $_POST['annee'];
		else $annee ="";

		if (!isset($_POST['pays']) || (isset($_POST['pays']) && $_POST['pays']==""))	//choix du pays non effectué
			{
				if ($AfficheInfoDebug=="y") {
					echo"2<br/>";
				}
			echo "<form id=\"formSel\" name=\"form_selction\" method=\"post\" action=\"preselection.php\">";
			echo "<input type=\"hidden\" name=\"type\" value=\"".$type."\">";
			echo "<input type=\"hidden\" name=\"type_donnees\" value=\"".$type_donnees."\">";
			echo "<h2>S&eacute;lection du pays</h2><br/>";
			$nb =0;
			$n = count($ST);
			$colonne = ceil($n/5);	//affichage de 5 par colonne
			?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('pays') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			echo "<input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\"> Tout";
			echo"<ul id=\"listPays\">";
			$i=0;
			while (list($key, $val) = each($ST))
			{
				if($val =="Inconnu")continue;
				$val2 = str_replace("'","\'",$val);
				echo "<li class=\"itemPays\"><input type=\"Checkbox\" name=\"pays[".$i."]\" value=\"".$val2."\">".$val."</li>";
				$i++;
			}
			echo "</ul>";
			echo "<input type=\"submit\" name=\"choix\" value=\"    Valider    \">";
			echo "</form>";	
			include $_SERVER["DOCUMENT_ROOT"].'/extraction/back.inc';

			}
		else 	
			{
			if (!isset($_POST['systeme']) || (isset($_POST['systeme']) && $_POST['systeme']=="")){//choix du systeme non effectué	
				if ($AfficheInfoDebug=="y") {
					echo"Presel - 3<br/>";
				}		
				echo "<form id=\"formSel\" name=\"form_selction\" method=\"post\" action=\"preselection.php\">";
				echo "<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">";	//à enlever car qu'1 base 
				echo "<input type=\"hidden\" name=\"type\" value=\"".$type."\">";
				echo "<input type=\"hidden\" name=\"type_donnees\" value=\"".$type_donnees."\">";
				//print ("Choix du ou des systèmes : ");
				echo "<h2>S&eacute;lection du syst&egrave;me</h2>";
				// ini
				//$query2 = "select distinct RP.nom, RSy.libelle, ref_systeme_date_butoir.date_butoire 
				//from ref_systeme as RSy, ref_pays as RP, ref_systeme_date_butoir, 
				//admin_users, ref_autorisation_exploitation 
				//where RSy.ref_pays_id = RP.id ";
				//and admin_users.user_id = ".$_SESSION['s_ppeao_user_id'];
				//and ref_systeme_date_butoir.systeme = RSy.libelle 
				//and ref_autorisation_exploitation.login=ref_utilisateurs.login 
				//and ref_autorisation_exploitation.pointeur=ref_systeme_date_butoir.id 
				//and ref_systeme_date_butoir.date_butoire != '1900-01-01' ";
				// New query
				$query2 = "select distinct RP.nom, RSy.libelle 
				from ref_systeme as RSy, ref_pays as RP 
				where RSy.ref_pays_id = RP.id ";
				
				
				$query2 .= "and (";

				reset ($_POST['pays']);
				while (list($key, $val) = each($_POST['pays']))
					{	
					$query2 .= "(RP.nom ='".$val."') or";
					}
				$query2 = substr($query2, 0, -2); 		//on enleve le dernier or
				$query2 .= ")";
				//if ($type=="scientifique"){$query2 .= "and ref_systeme_date_butoir.type_echant=1 ";}
				//else if ($type=="artisanale"){$query2 .= "and ref_systeme_date_butoir.type_echant=2 ";}
				//else if ($type=="statistique"){$query2 .= "and ref_systeme_date_butoir.type_echant=3 ";}
				
				//print ("<br/>".$type." , ".$query2);///////////////////////////////////////////
			
				print ("<br/><h3>Pays s&eacute;lectionn&eacute;s: </h3>");
				$i=0;
				reset ($_POST['pays']);
				$ligne_a_afficher="";
				while (list($key, $val) = each($_POST['pays']))
					{
					$val2 = str_replace("\'","'",$val);
					$ligne_a_afficher .= "".$val2.", ";
					$i++;
					}
				$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
				print($ligne_a_afficher);
			
			
				$result2 = pg_query($connection, $query2);
				
				$i=0;
				$j=0;		
				while($row2 = pg_fetch_row($result2))
					{
					$ST2[$j][0] = $row2[1];//pays
					$ST2[$j][1] = $row2[0];//systeme
					$j++;
					}
				if (isset($ST2))
					{
					$nb =0;
					$n = count($ST2);
					$colonne = ceil($n/5);	//affichage de 5 par colonne
			
					?>
					<script language="JavaScript"><!--
					function clicTous(form,booleen) 
						{
						for (i=0, n=form.elements.length; i<n; i++)
						if (form.elements[i].name.indexOf('systeme') != -1)
						form.elements[i].checked = booleen;
						}
					//--></script>
					<?php
					echo "<br/><br/><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout";
					reset($ST2);
					echo"<ul id=\"listSys\">";
					while (list($key2, $val2) = each($ST2))
					{
						echo "<li class=\"itemSys\"><input type=\"Checkbox\" name=\"systeme[".$i."]\" value=\"".$val2[0]."\">".$val2[1].": ".$val2[0] ;//(jusqu'au ".$val2[2].")";
						$i++;

					}
					
					echo"</ul>";
					
					//ajout pays concernés
				
					$i=0;
					reset ($ST2);
					$inter="";
					while (list($key3, $val3) = each($ST2))
						{
						if ($val3[1]!= $inter)
							{
							//$val2 = str_replace("\'","'",$val);
							print("<input type=hidden name=\"pays[".$i."]\" value=\"".$val3[1]."\">");
							$inter=$val3[1];
							$i++;
							}
						}
					}
				else {
					echo "<br/><h2>Aucun syst&egrave;me renseign&eacute; ou pas d'autorisation suffisante</h2><br/>";
				}
				
				echo "<input type=\"submit\" name=\"choix\" value=\"    Valider    \">";
				echo "</form>";
				include $_SERVER["DOCUMENT_ROOT"].'/extraction/back.inc';
				
				
				}//fin du if($systeme...
			
			else	//pays et systeme renseigné -> suite selection annee d'etude 
				{
				if ($AfficheInfoDebug=="y") {
					echo"Presel - 4<br/>";
				}	
				echo "<form id=\"formSel\" name=\"form_selction\" method=\"post\" action=\"preselection.php\">";
				echo "<input type=\"hidden\" name=\"type\" value=\"".$type."\">";
				echo "<input type=\"hidden\" name=\"type_donnees\" value=\"".$type_donnees."\">";
		
				//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
				//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
				
				print ("<h2>S&eacute;lection de la p&eacute;riode d'&eacute;tude</h2>");
				
				print ("<br/><h3>Pays s&eacute;lectionn&eacute;s: </h3>");
				
				$i=0;
				$ligne_a_afficher="";
				reset ($_POST['pays']);
				while (list($key, $val) = each($_POST['pays']))
					{
					//$ST2[$j][0]
					
					$val2 = str_replace("\'","'",$val);
					$ligne_a_afficher.="<input type=\"hidden\" name=\"pays[".$i."]\" value=\"".$val."\"> ".$val2.", ";
					$i++;
					}
				$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
				print($ligne_a_afficher);
				
				print ("<br/><br/><h3>Syst&egrave;mes choisis : </h3>");
				$i=0;
				$ligne_a_afficher="";
				reset ($_POST['systeme']);
				while (list($key, $val) = each($_POST['systeme']))
					{
					$ligne_a_afficher.="<input type=\"hidden\" name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val.", ";
					$i++;
					}
				$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
				print($ligne_a_afficher);
				//print ("Période : ");
				//print ("Pays : <Font Color =\"#663399\">".$pays."</Font> , système : <Font Color =\"#663399\">".$systeme."</Font>");
				
				print ("<br/><br/><h3>S&eacute;lection de la date: </h3>");
				if (($annee_deb =="")||($annee_fin ==""))		//choix de l'année non effectuée
					{
					echo "<br/>Date de d&eacute;but :  01/01/";
					echo "<input type=text size=\"3\" name=\"annee_deb\"><br/>";
					echo "Date de fin :  31/12/</td><td>";
					echo "<input type=text size=\"3\" name=\"annee_fin\"><br/><br/>";
					echo "<input type=\"submit\" name=\"choix\" value=\"    Valider    \">";
					echo "<br/></form>";
					include $_SERVER["DOCUMENT_ROOT"].'/extraction/back.inc';
					
					}
				
				else
					{
					//si annee n'est pas au bon format (19xx ou 20xx)
					if ( !ereg("(^(19|20)[0-9]{2}$)",$annee_deb) ){
						echo "<h3>L'ann&eacute;e ".$annee_deb." n'est pas valide</h3>";
						echo "<form name=\"form2\" method=\"post\" action=\"preselection.php\">";
						echo "<input type=hidden name=\"type\" value=\"".$type."\">";
						echo "<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">";
						echo "<input type=\"submit\" name=\"choix\" value=\"    Retour    \">";
						echo "</form>";
					}//fin du if ereg
					else if ( !ereg("(^(19|20)[0-9]{2}$)",$annee_fin) ){
						echo "<h3>L'ann&eacute;e ".$annee_fin." n'est pas valide</h3>";
						echo "<form name=\"form2\" method=\"post\" action=\"preselection.php\">";
						echo "<input type=hidden name=\"type\" value=\"".$type."\">";
						echo "<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">";
						echo "<input type=\"submit\" name=\"choix\" value=\"    Retour    \">";
						echo "</form>";
						}//fin du if ereg
					else if ($annee_fin<$annee_deb){
						echo "<h3>L'ann&eacute;e de fin doit &ecirc;tre post&eacute;rieure &agrave; l'ann&eacute;e de d&eacute;but</h3>";
						echo "<form name=\"form2\" method=\"post\" action=\"preselection.php\">";
						echo "<input type=hidden name=\"type\" value=\"".$type."\">";
						echo "<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">";
						echo "<input type=\"submit\" name=\"choix\" value=\"    Retour    \">";
						echo "</form>";
					} else {
						echo "<br/><br/><h3>La p&eacute;riode d'&eacute;tude souhait&eacute;e est comprise entre le 01/01/".$annee_deb." et le 31/12/".$annee_fin.".</h3>";
						echo "<br/></form>";
						echo "<form name=\"form_selction\" method=\"post\" action=\"selection.php\">";
						echo "<input type=hidden name=\"type\" value=\"".$type."\">";
						echo "<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">";
						echo "<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">";
						echo "<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">";
						$i=0;
						reset ($_POST['pays']);
						while (list($key, $val) = each($_POST['pays']))
							{
							echo "<h3><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> </h3>";
							$i++;
							}
						$i=0;
						reset ($_POST['systeme']);
						while (list($key, $val) = each($_POST['systeme'])){
							echo "<h3><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"></h3> ";
							$i++;
						}
						echo "<input type=\"submit\" name=\"choix\" value=\"    Valider    \">";
						echo "<br/></form>";
						echo "<form name=\"form2\" method=\"post\" action=\"preselection.php\">";
						echo "<input type=hidden name=\"type\" value=\"".$type."\">";
						echo "<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">";
						$i=0;
						reset ($_POST['pays']);
						while (list($key, $val) = each($_POST['pays'])){
							echo "<h3><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"></h3> ";
							$i++;
						}
						$i=0;
						reset ($_POST['systeme']);
						while (list($key, $val) = each($_POST['systeme'])){
							echo "<h3><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"></h3> ";
							$i++;
						}
						echo "<input type=hidden name=\"annee\" value=\"\">";
						echo "<input type=\"submit\" name=\"choix\" value=\"  Retour  \">";
						echo "</form>";
						echo "</form>";
						echo "<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">";
						echo "<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form>";
						}
					}
				}
			}

		pg_close();
		
		?>

		</div>	<!-- end div id="main_container"-->


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