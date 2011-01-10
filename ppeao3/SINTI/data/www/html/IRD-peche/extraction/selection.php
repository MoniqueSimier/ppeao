<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">

<script type="text/javascript">

function pop_it(the_form) {
   my_form = eval(the_form);
   window.open("blanc.html", "popup", "height=700,width=850,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes");
   my_form.target = "popup";
   my_form.submit();
}


</script>
</head>
<body background="fond.png">


<?php
$bdd = $_POST['base'];
//print("travail sur la base : ".$bdd);


$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
$host= "vmppeao.intranet.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
//$bdd = "BD2_Peche";


//if (isset($_POST['pays']))$pays = $_POST['pays'];
//else $pays ="";
//if (isset($_POST['systeme']))$systeme = $_POST['systeme'];
//else $systeme ="";
if (isset($_POST['annee_deb']))$annee_deb = $_POST['annee_deb'];
else $annee_deb ="";
if (isset($_POST['annee_fin']))$annee_fin = $_POST['annee_fin'];
else $annee_fin ="";
if (isset($_POST['login']))$login = $_POST['login'];
else $login ="";
if (isset($_POST['passe']))$passe = $_POST['passe'];
else $passe ="";
if (isset($_POST['type']))$type = $_POST['type'];
else $type ="";
if (isset($_POST['type_donnees']))$type_donnees = $_POST['type_donnees'];
else $type_donnees ="";






/*while (list($key, $val) = each($_POST['secteur']))
	{
	print("<br>!!!".$val);
	}
*/
//$val2 = str_replace("\'","'",$val);

?>
<div align='center'><Font Color ="#333366">
<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH="600">
<tr><td align='center'><h3><b><Font Color ="#333366">Base de Données PPEAO</font></b></h3>

<?php
if ($type == "scientifique")print ("<Font Color =\"#333366\">Consultation / Extraction de données de pêche scientifiques</Font>");
else if ($type == "artisanale")print ("<Font Color =\"#333366\">Consultation / Extraction de données de pêche artisanales</Font>");
else if ($type == "statistique")print ("<Font Color =\"#333366\">Consultation / Extraction de données statistiques</Font>");
else print("</b></Font>");
?>
</td></tr>
</table>
</div>
<?php


/*
print ("<div align='center'><br>");
print ("Pays : ");
$i=0;
$ligne_a_afficher="";
reset ($_POST['pays']);
while (list($key, $val) = each($_POST['pays']))
	{
	$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
	$i++;
	}
$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
print($ligne_a_afficher);
print ("<br>Système : ");
$i=0;
reset ($_POST['systeme']);
$ligne_a_afficher="";
while (list($key, $val) = each($_POST['systeme']))
	{
	$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
	$i++;
	}
$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
print($ligne_a_afficher);
print ("<br>Période : <Font Color =\"#663399\">".$annee_deb."</Font>-"."<Font Color =\"#663399\">".$annee_fin);

//print ("<br>Pays : <Font Color =\"#663399\">".$pays."</Font> , Système : <Font Color =\"#663399\">".$systeme."</Font> , Année : <Font Color =\"#663399\">".$annee."</Font>");
print ("</div>");

*/





//////////////////////////////////////////////////////////////////////////////////////////
//pour les donnees scientifiques:
//print("<br>".$type);
//////////////////////////////////////////////////////////////////////////////////////////
if($type=="scientifique")
	{

///////////////////////////////////////
//           choix du secteur d'etude
///////////////////////////////////////



//si le ou les secteurs ne sont pas encore choisis
if (!isset($_POST['secteur']))
	{
	print ("<div align='center'><Font Color =\"#333366\">");
	print ("<br><b>Sélection du secteur</b></font>");

	print ("<br><br>");
	print ("Pays : ");
	$i=0;
	$ligne_a_afficher="";
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Système : ");
	$i=0;
	reset ($_POST['systeme']);
	$ligne_a_afficher="";
	while (list($key, $val) = each($_POST['systeme']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Période : <Font Color =\"#663399\">".$annee_deb."</Font>-"."<Font Color =\"#663399\">".$annee_fin);
	
	//print ("<br>Pays : <Font Color =\"#663399\">".$pays."</Font> , Système : <Font Color =\"#663399\">".$systeme."</Font> , Année : <Font Color =\"#663399\">".$annee."</Font>");
	//print ("</div>");

	//print ("<div align='center'><Font Color =\"#333366\">");
	//print ("<br>Sélection du secteur");
	
	
	$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	
	$query = "select distinct RS.nom, RP.nom, RSy.libelle 
	from ref_pays as RP, ref_secteur as RS, ref_systeme as RSy, exp_station, exp_coup_peche 
	where RSy.ref_pays_id = RP.id and RSy.id = RS.ref_systeme_id 
	and RS.id=exp_station.ref_secteur_id and exp_station.id=exp_coup_peche.exp_station_id 
	and exp_coup_peche.date_cp > '".$annee_deb."-01-01' and exp_coup_peche.date_cp < '".$annee_fin."-12-31' 
	
	
	and (";
	
	reset ($_POST['systeme']);
		while (list($key, $val) = each($_POST['systeme']))
			{	
			$query .= "(RSy.libelle ='".$val."') or";
			}
		$query = substr($query, 0, -2); 		//on enleve le dernier or
		$query .= ") order by RP.nom, RSy.libelle ";
	
	//print ($query);
	$result = pg_query($connection, $query);
	
	$ST=Array();
	$i = 0;
	while($row = pg_fetch_row($result))
		{
		//$ST[$i][0] = $row[0];	//libelle secteur
		//$ST[$i][1] = $row[1];	//pays
		//$ST[$i][2] = $row[2];	//systeme
		//$i++;
		$STX[$row[1]][$row[2]][$row[0]]="";//pays, syst, secteur =""
		
		}
	
	// Deconnexion de la base de donnees
	pg_close();



		
	 
	//affichage des secteurs du systeme préalablement choisi
	
	print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
									print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	$nb =0;
	$n = count($ST);
	$i=0;
	//$colonne = ceil($n/5);	//affichage de 5 par colonne
	$colonne =3;
	
	?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('secteur') != -1)
		form.elements[i].checked = booleen;
		}
	//--></script>
	<?php
	print ("<table width=\"850\"><tr><td align = middle><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr>");
print ("<table >");
	
	
	//print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
		
	reset($STX);
	$i=0;
	while (list($key_stx, $val_stx) = each($STX))
		{
		while (list($key_stx2, $val_stx2) = each($val_stx))//pour tous les systemes
			{
			print ("<tr ><td align=left ><b>".$key_stx.", ".$key_stx2."</b></td></tr><tr><Td>");
			$nb_ds_ligne=0;
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			while (list($key_stx3, $val_stx3) = each($val_stx2))//pour tous les secteurs
				{
				
				//affichage de 4 secteurs par ligne
				$nb_ds_ligne ++;
				if ($nb_ds_ligne ==5)
					{
					print ("</tr><tr>");
					$nb_ds_ligne =0;
					}
				print ("<td width=\"200\"><input type=\"Checkbox\" name=\"secteur[".$i."]\" value=\"".$key_stx3."\">".$key_stx3."</td>");
				$i++;
				}
			print ("</tr></table></td></tr>");
			}
		
		}
	print ("</td></tr></table>");
	//print ("</table>");
	//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
	//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
	
	
	$i=0;
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		print ("<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	$i=0;
	reset ($_POST['systeme']);
	while (list($key, $val) = each($_POST['systeme']))
		{
		print ("<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=\"hidden\" name=\"login\" value=\"".$login."\">");
	print ("<input type=\"hidden\" name=\"passe\" value=\"".$passe."\">");
	print ("<br><table><tr ><td colspan=\"2\" align =\"middle\"><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
	print ("</td></tr></form>");	
	
	
	
	
	
	
	
	/////////retour preselection

	
	print ("<tr><td>");
	print ("<form name=\"retour\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=\"hidden\" name=\"login\" value=\"".$login."\">");
	print ("<input type=\"hidden\" name=\"passe\" value=\"".$passe."\">");
	print ("<input type=\"hidden\" name=\"type\" value=\"".$type."\">");
						print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
	print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
	print ("</div>");
	//////////
	

	
	
	
	
	
	
	
	}
	
//sinon on choisi les campagnes
else
	{
	//print ("<div align='center'><br>");
	$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	/*
	$pays_raffrai=Array();
	$systeme_raffrai=Array();
	while (list($key, $val) = each($_POST['secteur']))
		{
		
		$query_raffrai = "select distinct RP.nom, RSy.libelle from ref_pays as RP, 
		ref_secteur as RS, ref_systeme as RSy
		where RSy.ref_pays_id = RP.id and RSy.id = RS.ref_systeme_id and 
		RS.nom ='".$val."' ";
		print("<br>!!!".$query_raffrai);
		$result_raffrai = pg_query($connection, $query_raffrai);
		while($row_raffrai = pg_fetch_row($result_raffrai))
			{
				//print("<br>**++++***".$row_raffrai[0]);
			if(!isset($pays_raffrai[$row_raffrai[0]]))$pays_raffrai[$row_raffrai[0]]="";
			if(!isset($systeme_raffrai[$row_raffrai[1]]))$systeme_raffrai[$row_raffrai[1]]="";
			}
		//pg_free_result($result_raffrai);
		}
	
	*/
	print ("<div align='center'><Font Color =\"#333366\">");
	if (!isset($_POST['campagne']))print ("<br><b>Sélection des campagnes de pêche</b></font>");
	else if (!isset($_POST['engin'])) print ("<br><b>Sélection des engins de pêche</b></font>");
	else  print ("<br><b>Choix de la filière d'extraction</b></font>");
	print ("<br><br>");
	
	
	print ("Pays : ");
	$i=0;
	$ligne_a_afficher="";
	reset($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		//print("<br>*****".$key);
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Système : ");
	$i=0;
	reset ($_POST['systeme']);
	$ligne_a_afficher="";
	while (list($key, $val) = each($_POST['systeme']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Période : <Font Color =\"#663399\">".$annee_deb."</Font>-"."<Font Color =\"#663399\">".$annee_fin);
	
	//print ("<br>Pays : <Font Color =\"#663399\">".$pays."</Font> , Système : <Font Color =\"#663399\">".$systeme."</Font> , Année : <Font Color =\"#663399\">".$annee."</Font>");
print ("</div>");
	
	
	
	
	
	
	
	print ("<div align='center'></font>");
	//print ("<br>Secteur : ");

	/*
	$ligne = "Secteur : ";
	reset ($_POST['secteur']);
	while (list($key, $val) = each($_POST['secteur']))
		{
		$ligne .= "<Font Color =\"#663399\">".$val."</font>, ";
		
		}
	$ligne = substr($ligne, 0, -2); 		//on enleve la derniere virgule
	print ($ligne);
	print("</div>");
	
	//raffraissement des noms de pays et de secteur
	*/
	
	
	
	
	
	
	//si campagne non referencées
	if (!isset($_POST['campagne']))
		{
		//print ("<div align='center'><Font Color =\"#333366\">");
		//print ("<br>Sélection des campagnes de pêche");
		
		
		$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
		if (!$connection) { echo "Pas de connection"; exit;}
		
		$query2 = "select distinct EC.id, EC.numero_campagne, EC.date_debut, EC.date_fin, RP.nom, RSY.libelle from exp_campagne as EC, ref_systeme as RSy, ref_pays as RP, ref_secteur as RS 
		
		, ref_systeme_date_butoir, 
		
		ref_utilisateurs, ref_autorisation_exploitation 
		where RSy.ref_pays_id = RP.id and EC.date_debut >= '".$annee_deb."-01-01' and EC.date_fin <= '".$annee_fin."-12-31' 
		
		and ref_utilisateurs.login = '".$login."' 
		and ref_utilisateurs.password = '".$passe."' 
		and ref_systeme_date_butoir.systeme = RSy.libelle 
		and ref_autorisation_exploitation.login=ref_utilisateurs.login 
		and ref_autorisation_exploitation.pointeur=ref_systeme_date_butoir.id 
		and ref_systeme_date_butoir.date_butoire != '01/01/1900' 
		and ref_systeme_date_butoir.type_echant = 1 
		
		
		
		and EC.ref_systeme_id = RSy.id 
		and RS.ref_systeme_id= RSy.id 
		and (";
		reset ($_POST['systeme']);
		while (list($key, $val) = each($_POST['systeme']))
			{	
			$query2 .= "(RSy.libelle ='".$val."') or";
			}
		$query2 = substr($query2, 0, -2); 		//on enleve le dernier or
		$query2 .= ") and (";
		
		reset ($_POST['secteur']);
		while (list($key, $val) = each($_POST['secteur']))
			{	
			$query2 .= "(RS.nom ='".$val."') or";
			}
		$query2 = substr($query2, 0, -2); 		//on enleve le dernier or
		$query2 .= ") order by RP.nom, RSy.libelle, EC.date_debut ";
		
		
		
		
		//print ($query2);
		$result2 = pg_query($connection, $query2);
		
		$T=Array();
		$i = 0;
		/*while($row = pg_fetch_row($result2))
			{
			$T[$row[4]][$row[5]][$row[1]][0] = $row[0];	//id campagne
			$T[$i][1] = $row[1];	//numero campagne
			$T[$i][2] = $row[2];	//date debut
			$T[$i][3] = $row[3];	//date fin
			$T[$i][4] = $row[4];	//pays
			$T[$i][5] = $row[5];	//systeme
			$i++;
			}*/
			while($row = pg_fetch_row($result2))
			{
			$T[$row[4]][$row[5]][$row[1]][0] = $row[2];
			$T[$row[4]][$row[5]][$row[1]][1] = $row[3];	
			$T[$row[4]][$row[5]][$row[1]][2] = $row[1];	
			$T[$row[4]][$row[5]][$row[1]][3] = $row[0];
			}
		
		/////////////////affichage campagnes peche
		
		
		print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
										print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
		
		$nb =0;
		$n = count($T);
		$i=0;
		//$colonne = ceil($n/3);	//affichage de 3 par colonne
		
		?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('campagne') != -1)
		form.elements[i].checked = booleen;
		}
	//--></script>
	<?php
	print ("<table><tr><td align = right><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
		
	print ("<table>");
		//print ("<table  CELLSPACING=2 CELLPADDING=1><tr>");
		reset ($T);
		while (list($key, $val) = each($T))//pour chaque pays
			{
			while (list($key2, $val2) = each($val))//pour chaque systeme
				{
				print ("<tr><td align = left colspan=3><b>".$key.", ".$key2."</b></td></tr><tr><td>");
				$nb_ds_ligne=0;
				print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
				while (list($key3, $val3) = each($val2))//pour chaque campagne
					{
					$nb_ds_ligne ++;
					if ($nb_ds_ligne ==4)
						{
						print ("</tr><tr>");
						$nb_ds_ligne =1;
						}
					
					print ("<td width=300 ><input type=\"Checkbox\" name=\"campagne[".$i."]\" value=\"".$val3[3]."\">campagne n°".$key3.": <Font Color =\"#663399\">".$val3[0]."</font> à <Font Color =\"#663399\">".$val3[1]."</font></td>");
					$i++;
					}
				print ("</tr></table></td></tr>");
				}
			
			
			}
		print ("</table>");
		
		
		
		
		
		
		if ($i == 0)print("pas de campagne référencée dans la période");
		print ("</tr></table>");
		
		//rajout des variables secteurs dans le formulaire
		//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
		//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
		
		
		
		
		$i=0;
		reset ($_POST['pays']);
		while (list($key, $val) = each($_POST['pays']))
			{
			print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
			$i++;
			}
		$i=0;
		reset ($_POST['systeme']);
		while (list($key, $val) = each($_POST['systeme']))
			{
			print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
			$i++;
			}
			
		print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
		$sec=0;
		reset($_POST['secteur']);
		while (list($key, $val) = each($_POST['secteur']))
			{
			print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
			$sec ++;
			}
		print ("<input type=hidden name=\"type\" value=\"".$type."\">");
		print ("<br><br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
		print ("</td></tr></form>");	
			
		}//fin du if campagne non referencées
	else
		{
		print ("<div align='center'>");
		/*print ("Campagne : ");

		$ligne_campagne = "";
		reset ($_POST['campagne']);
		while (list($key_cam3, $val_cam3) = each($_POST['campagne']))
			{
			$query_cam = "select distinct EC.id, EC.numero_campagne, RSy.libelle from exp_campagne as EC, ref_systeme as RSy 
			where RSY.id=EC.ref_systeme_id and 
			EC.id =".$val_cam3." ";
			//print("<br>!!!".$query_cam);
			$result_cam = pg_query($connection, $query_cam);
			while($row_cam = pg_fetch_row($result_cam))
				{
				$ligne_campagne .= "<Font Color =\"#663399\">".$row_cam[1]."</font> (".$row_cam[2]."), ";
				}
			
			
			
			
			
			//$ligne_campagne .= "<Font Color =\"#663399\">".$val_cam."</font>, ";
			}
		$ligne_campagne = substr($ligne_campagne, 0, -2); 		//on enleve la derniere virgule
		print ($ligne_campagne);*/
		
		
		///////selection du type d'engin

		//si engin non referencé
		if (!isset($_POST['engin']))
			{
			//choix engin de peche
	
			print ("<div align='center'><Font Color =\"#333366\">");
			
			
			$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) { echo "Pas de connection"; exit;}
			
			$query3 = "select distinct EE.id, EE.libelle from exp_engin as EE, exp_campagne as EC, exp_coup_peche as ECP 
			where ECP.exp_engin_id=EE.id and EC.id=ECP.exp_campagne_id and (";
			reset ($_POST['campagne']);
			while (list($key_sys, $val_sys) = each($_POST['campagne']))
				{	
				$query3 .= "(EC.id =".$val_sys.") or";
				}
			$query3 = substr($query3, 0, -2); 		//on enleve le dernier or
			$query3 .= ") order by EE.libelle";
			$result3 = pg_query($connection, $query3);
			//print($query3);
			
			$E=Array();
			$i = 0;
			while($row = pg_fetch_row($result3))
				{
				$E[$i][0] = $row[0];	//identifiant engin
				$E[$i][1] = $row[1];	//libelle engin
				$i++;
				}
			reset ($T);
			
			//affichage engin
			print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			
			?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('engin') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<table><tr><td align=center><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
			
			
			
			$nb =0;
			$n = count($E);
			$colonne = ceil($n/5);	//affichage de 5 par colonne
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			$i=0;
			while (list($key_E, $val_E) = each($E))
				{
				//print ("<br>".$val[0]." , ".$val[1]);
				$nb = $nb + 1;
				//pb affichage base on affiche à partir de l'espace
				$libelle = $val_E[1];
				if ($nb <= $colonne)
					{
					print ("<td><input type=\"Checkbox\" name=\"engin[".$i."]\" value=\"".$val_E[0]."\">".$libelle."</td>");
					}
				else 	{
					print ("</tr><tr><td><input type=\"Checkbox\" name=\"engin[".$i."]\" value=\"".$val_E[0]."\">".$libelle."</td>");
					$nb =1;
					}
				$i++;
				}
			if ($i == 0)print("pas de type d'engin référencé");
			
			
			
			print ("</tr></table>");
			//rajout des variables secteurs dans le formulaire
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
				print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['campagne']);
			while (list($key, $val) = each($_POST['campagne']))
				{
				print ("<input type=\"hidden\" name=\"campagne[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
			print ("<input type=hidden name=\"type\" value=\"".$type."\">");
			print ("<br><br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
			print ("</td></tr></form>");
			}//fin du if engin n'existe pas
		else
			{
			/*print ("<div align='center'>");
	
			$ligne_engin = "Identifiants engins: ";
			reset ($_POST['engin']);
			while (list($key_eng, $val_engin) = each($_POST['engin']))
				{
				
				$query_engin = "select distinct EE.id, EE.libelle from exp_engin as EE 
				where EE.id ='".$val_engin."' ";
				//print("<br>!!!".$query_raffrai);
				$result_engin = pg_query($connection, $query_engin);
				while($row_engin = pg_fetch_row($result_engin))
					{
					$ligne_engin .= "<Font Color =\"#663399\">".$row_engin[1]."</font>, ";
					}
				}
			$ligne_engin = substr($ligne_engin, 0, -2); 		//on enleve la derniere virgule
			print ($ligne_engin);*/
			
			
			////////////
			//selection du type de traitement
			
			print ("<div align='center'><Font Color =\"#333366\">");
			print ("<br><br><br>");
			

			print ("<form name=\"form_tot\" method=\"post\" action=\"sc_filieres.php\">");
			//print ("<form name=\"form_tot\" method=\"post\" action=\"sc_filieres.php\" onSubmit=\"OuvrirPopup()\">");
			//print ("<form name=\"form\" method=\"post\" action=\"javascript:OuvrirPopup('sc_filieres.php','','top=10,left=400,resizable=yes, width=600, height=150, scrollbars=yes ')\">");
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['campagne']);
			while (list($key, $val) = each($_POST['campagne']))
				{
				print ("<input type=\"hidden\" name=\"campagne[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
				$eng =0;
			reset($_POST['engin']);
			while (list($key, $val) = each($_POST['engin']))
				{
				print ("<input type=\"hidden\" name=\"engin[".$eng."]\" value=\"".$val."\">");
				$eng ++;
				}
			print ("<input type=hidden name=\"type\" value=\"".$type."\">");
			
			
			
			print ("<table><tr>");
			
			print ("<td><input type=\"submit\" name=\"choix\" value=\" Peuplement \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("<td><input type=\"submit\" name=\"choix\" value=\"Environnement\" onclick=\"pop_it(form_tot);\" ></td>");
			print ("<td><input type=\"submit\" name=\"choix\" value=\"    NT, PT    \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("<td><input type=\"submit\" name=\"choix\" value=\"     Biologie     \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("<td><input type=\"submit\" name=\"choix\" value=\"    Trophique     \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("</tr></table>");
			}
			print ("</form>");
			
			
		
		
		print("</div>");
		}
	

	
	
	
	
	
	
	
	
	// Deconnexion de la base de donnees
	pg_close();
	
	print ("<table><tr><td>");
	print ("<form name=\"form2\" method=\"post\" action=\"selection.php\">");
			print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
	//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
	$i=0;
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	$i=0;
	reset ($_POST['systeme']);
	while (list($key, $val) = each($_POST['systeme']))
		{
		print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
	print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
	
	
	
	
	print ("</div>");

	
	}
	/*print ("<form name=\"formn\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"        Fin       \">");
	print ("</form>");*/
}//fin du if($type=="scientifique")

/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
//filiere donnees peches artisanales:
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
if($type=="artisanale")
{
//print ("<br>".$type." , annee deb ".$annee_deb." anne fin : ".$annee_fin); 
//si le ou les secteurs ne sont pas encore choisis
if (!isset($_POST['secteur']))
	{
	//affichage des données sélectionnées auparavant
	print ("<div align='center'><br><Font Color =\"#333366\"><b>Sélection du secteur</b></font><br><br>");
	print ("Pays : ");
	$i=0;
	$ligne_a_afficher="";
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Système : ");
	$i=0;
	reset ($_POST['systeme']);
	$ligne_a_afficher="";
	while (list($key, $val) = each($_POST['systeme']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Période : <Font Color =\"#663399\">".$annee_deb."</Font>-"."<Font Color =\"#663399\">".$annee_fin);
	
	//print ("<br>Pays : <Font Color =\"#663399\">".$pays."</Font> , Système : <Font Color =\"#663399\">".$systeme."</Font> , Année : <Font Color =\"#663399\">".$annee."</Font>");
	print ("</div>");
	/////suite

	print ("<div align='center'><Font Color =\"#333366\">");
	//print ("<br>Sélection du secteur");
	
	
	$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	
	$query = "select distinct RS.nom, RP.nom, RSy.libelle 
	from ref_pays as RP, ref_secteur as RS, ref_systeme as RSy, art_agglomeration, art_debarquement 
	where RSy.ref_pays_id = RP.id and RSy.id = RS.ref_systeme_id 
	and RS.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_debarquement.art_agglomeration_id 
	and art_debarquement.annee>=".$annee_deb." and art_debarquement.annee<=".$annee_fin." 
	and (";
	
	reset ($_POST['systeme']);
		while (list($key, $val) = each($_POST['systeme']))
			{	
			$query .= "(RSy.libelle ='".$val."') or";
			}
		$query = substr($query, 0, -2); 		//on enleve le dernier or
		$query .= ") order by RP.nom, RSy.libelle, RS.nom ";
	
	//print ($query);
	$result = pg_query($connection, $query);
	
	$ST=Array();
	$i = 0;
	while($row = pg_fetch_row($result))
		{
		//$ST[$i][0] = $row[0];	//libelle secteur
		//$ST[$i][1] = $row[1];	//pays
		//$ST[$i][2] = $row[2];	//systeme
		//$i++;
		$STX[$row[1]][$row[2]][$row[0]]="";//pays, syst, secteur =""
		}
	
	// Deconnexion de la base de donnees
	pg_close();



		
	 
	//affichage des secteurs du systeme préalablement choisi
	
	print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
									print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	//$nb =0;
	//$n = count($ST);
	//$i=0;
	//$colonne = ceil($n/5);	//affichage de 5 par colonne
	//$colonne =3;
	
	?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('secteur') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
	print ("<table><tr><td align = right><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");

	
	
	
	
	
	
	print ("<table>");
	reset($STX);
	$i=0;
	while (list($key_stx, $val_stx) = each($STX))
		{
		while (list($key_stx2, $val_stx2) = each($val_stx))//pour tous les systemes
			{
			print ("<tr><td width=\"200\"><b>".$key_stx.", ".$key_stx2."</b></td></tr><tr><td>");
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			$nb_ds_ligne =0;
			while (list($key_stx3, $val_stx3) = each($val_stx2))//pour tous les secteurs
				{
				$nb_ds_ligne ++;
				if ($nb_ds_ligne ==4)
					{
					print ("</tr><tr>");
					$nb_ds_ligne =0;
					}
				
				print ("<td width=\"200\"><input type=\"Checkbox\" name=\"secteur[".$i."]\" value=\"".$key_stx3."\">".$key_stx3."</td>");
				$i++;
				}
			print ("</tr></table></td></tr>");
			}
		}
		print ("</td></tr></table>");
	//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
	//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
	$i=0;
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		print ("<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	$i=0;
	reset ($_POST['systeme']);
	while (list($key, $val) = each($_POST['systeme']))
		{
		print ("<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<br><br><table><tr><td  align = center><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
	print ("</td></tr></form>");	
	
	
	

	
	
	
	/////////retour preselection


	print ("<table><tr><td>");
	print ("<form name=\"retour\" method=\"post\" action=\"preselection.php\">");
						print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=\"hidden\" name=\"login\" value=\"".$login."\">");
	print ("<input type=\"hidden\" name=\"passe\" value=\"".$passe."\">");
	print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
	print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
	
	
	
	print ("</div>");
	//////////
	
	
	
	
	
	}
	
//sinon on choisi les agglomerations
else
	{
	//print ("<div align='center'>");
	//print ("<br>Secteur : ");

		//print ("<br><Font Color =\"#333366\"><b>Sélection des agglomérations :</b></font><br><br>");
	print ("<div align='center'><Font Color =\"#333366\">");
	if (!isset($_POST['agglo']))print ("<br><Font Color =\"#333366\"><b>Sélection des agglomérations</b></font>");
	else if (!isset($_POST['periode'])) print ("<br><Font Color =\"#333366\"><b>Sélection des périodes d'enquêtes</b></font>");
	else if (!isset($_POST['engin'])) print ("<br><Font Color =\"#333366\"><b>Sélection des engins de pêche</b></font>");
	else print ("<br><Font Color =\"#333366\"><b>Choix de la filière d'extraction</b></font>");
	print ("<br><br>");
	
	
	
	
	
	
	
	
	print ("Pays : ");
	$i=0;
	$ligne_a_afficher="";
	reset($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		//print("<br>*****".$key);
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Système : ");
	$i=0;
	reset ($_POST['systeme']);
	$ligne_a_afficher="";
	while (list($key, $val) = each($_POST['systeme']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Période : <Font Color =\"#663399\">".$annee_deb."</Font>-"."<Font Color =\"#663399\">".$annee_fin);
	print ("</Font><br>");
	
	
	
	
	
	
	
	
	
	
	
	/*
	$ligne = "Secteur : ";
	while (list($key, $val) = each($_POST['secteur']))
		{
		$ligne .= "<Font Color =\"#663399\">".$val."</font>, ";
		}
	$ligne = substr($ligne, 0, -2); 		//on enleve la derniere virgule
	print ($ligne);*/
	print("</div>");
	
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//si campagne non referencées
	if (!isset($_POST['agglo']))
		{
		print ("<div align='center'><Font Color =\"#333366\">");
		//print ("<br>Sélection des agglomérations :");
		
		
		
		
		$query_1 = "select distinct AA.id, AA.nom, RSy.libelle, RS.nom from art_agglomeration as AA, art_debarquement as AD, ref_systeme as Rsy, ref_secteur as RS 
		where AD.art_agglomeration_id=AA.id 
		and RSY.id=RS.ref_systeme_id 
		and RS.id=AA.ref_secteur_id 
		and AD.annee>=".$annee_deb." and AD.annee<=".$annee_fin." 
		and (";
		reset ($_POST['secteur']);
		while (list($key, $val) = each($_POST['secteur']))
			{	
			$query_1 .= "(RS.nom ='".$val."') or";
			}
		$query_1 = substr($query_1, 0, -2); 		//on enleve le dernier or
		$query_1 .= ") order by RS.nom, AA.nom ";
		
		//print ("<br>".$query_1);
		$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
		if (!$connection) { echo "Pas de connection"; exit;}
		$result_1 = pg_query($connection,$query_1);
		
		$A=Array();
		$i = 0;
		while($row_1 = pg_fetch_row($result_1))
			{
			//print ("<br>lkopkopk");
			//$A[$i][0] = $row_1[0];	//id agglo
			//$A[$i][1] = $row_1[1];	//nom
			//$A[$i][2] = $row_1[2];	//systeme
			//$A[$i][3] = $row_1[3];	//secteur
			//$i++;
			$A[$row_1[2]][$row_1[3]][$row_1[0]] = $row_1[1];
			}
		
		/////////////////affichage
		
		
		print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
										print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
		
		?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('agglo') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<table><tr><td align = middle><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
		
		
		$nb =0;
		$n = count($A);
		$i=0;
		$colonne = ceil($n/5);	//affichage de 5 par colonne
		
		//print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
		reset ($A);
		
		
		
		while (list($key, $val) = each($A))//pour chaque systeme
			{
			while (list($key2, $val2) = each($val))//pour chaque secteur
				{
				$nb_ds_ligne =0;
				print ("<table><tr><td align=\"left\"><b>".$key.", ".$key2."</b></td></tr><tr><td>");
				print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
				while (list($key3, $val3) = each($val2))//pour chaque agglo
					{
					$nb_ds_ligne ++;
					if ($nb_ds_ligne ==4)
						{
						print ("</tr><tr>");
						$nb_ds_ligne =0;
						}
					print ("<td width =\"200\"><input type=\"Checkbox\" name=\"agglo[".$i."]\" value=\"".$key3."\">".$val3."</td>");
					$i++;
					}
				print ("</tr></table></td></tr></table>");
				}
			
			
			}
	
		
		
		
		
		
		
		
		/*while (list($key, $val) = each($A))
			{
			//print ("<br>".$val[0]." , ".$val[1]);
			$nb = $nb + 1;
			if ($nb <= $colonne)
				{
				print ("<td><input type=\"Checkbox\" name=\"agglo[".$i."]\" value=\"".$val[0]."\">".$val[1]."(".$val[2].", ".$val[3].")</td>");
				}
			else 	{
				print ("</tr><tr><td><input type=\"Checkbox\" name=\"agglo[".$i."]\" value=\"".$val[0]."\">".$val[1]."(".$val[2].", ".$val[3].")</td>");
				$nb =1;
				}
			$i++;
			}*/
		if ($i == 0)print("pas d'agglomération référencée dans la période");
			
		print ("</tr></table>");
		
		//rajout des variables secteurs dans le formulaire
		//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
		//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
		$i=0;
		reset ($_POST['pays']);
		while (list($key, $val) = each($_POST['pays']))
			{
			print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
			$i++;
			}
		$i=0;
		reset ($_POST['systeme']);
		while (list($key, $val) = each($_POST['systeme']))
			{
			print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
			$i++;
			}
		print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");

		$sec=0;
		reset($_POST['secteur']);
		while (list($key, $val) = each($_POST['secteur']))
			{
			print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
			$sec ++;
			}
		print ("<input type=hidden name=\"type\" value=\"".$type."\">");
		print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
		print ("<br><br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
		print ("</td></tr></form>");	
			
		}//fin du if agglo non referencées
	else
		{
		$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
		if (!$connection) { echo "Pas de connection"; exit;}
		
		
		print ("<div align='center'>");
		/*//affichage agglo:
		print ("Agglomérations : ");
		$ligne_agglo = "";
		reset ($_POST['agglo']);
		while (list($key_agglo3, $val_agglo3) = each($_POST['agglo']))
			{
			$query_agglo = "select distinct AA.id, AA.nom, RS.nom from art_agglomeration as AA, ref_secteur as RS 
			where RS.id=AA.ref_secteur_id and 
			AA.id =".$val_agglo3." ";
			//print("<br>!!!".$query_agglo);
			$result_agglo = pg_query($connection, $query_agglo);
			while($row_agglo = pg_fetch_row($result_agglo))
				{
				//print("<br>!!!");
				$aaa[$row_agglo[2]][$row_agglo[1]]="";
				//print("<br>!!!".$row_agglo[1]);
				//$ligne_agglo .= "<Font Color =\"#663399\">".$row_agglo[1]."</font> (".$row_agglo[2]."), ";
				}
			
			//$ligne_campagne .= "<Font Color =\"#663399\">".$val_cam."</font>, ";
			}
		reset($aaa);
		while (list($key_aaa, $val_aaa) = each($aaa))//pour chaque secteur
			{
			//print("<br>!!!".$key_aaa);
			$ligne_agglo .= $key_aaa." : ";
			while (list($key2_aaa, $val2_aaa) = each($val_aaa))//pour chaque secteur
				{
				$ligne_agglo .= "<Font Color =\"#663399\">".$key2_aaa."</font>, ";
				}
			$ligne_agglo = substr($ligne_agglo, 0, -2); 		//on enleve la derniere virgule
			$ligne_agglo .= "<br>";
			}
		//$ligne_agglo = substr($ligne_agglo, 0, -2); 		//on enleve la derniere virgule
		print ($ligne_agglo);*/
	
	
	
	
	
	
	
	
	
	//print ("<br>agglo : ");

		/*$ligne_agglo = "Identifiants agglomérations (base de données): ";
		reset ($_POST['agglo']);
		while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
			{
			$ligne_agglo .= "<Font Color =\"#663399\">".$val_agglo."</font>, ";
			}
		$ligne_agglo = substr($ligne_agglo, 0, -2); 		//on enleve la derniere virgule
		print ($ligne_agglo);*/
		
		
		//////////////////////////////////////
		//selection des periodes d enquetes par agglomeration
		
		//si periode non referencée
		if (!isset($_POST['periode']))
			{
			
			
			
			
			
			
			
			
			
			
			//choix periode
	
			print ("<div align='center'><Font Color =\"#333366\">");
			print ("<br><br>");
			
			$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) { echo "Pas de connection"; exit;}
			
			$query_2 = "select distinct AAc.mois, AAc.annee 
			from art_agglomeration as AA, art_activite as AAc 
			where AA.id=AAc.art_agglomeration_id 
			and AAc.annee>=".$annee_deb." and AAc.annee<=".$annee_fin." 
			and (";
			reset ($_POST['agglo']);
			while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
				{	
				$query_2 .= "(AA.id =".$val_agglo.") or";
				}
			$query_2 = substr($query_2, 0, -2); 		//on enleve le dernier or
			$query_2 .= ") order by AAc.annee, AAc.mois";
			$result_2 = pg_query($connection, $query_2);
			//print("<br>".$query_2);
			//print("<br>".pg_num_rows($result_2));
			
			$P=Array();
			while($row = pg_fetch_row($result_2))
				{
				$annee = $row[1];	//annee
				$mois = $row[0];
				$P[$annee][$mois]="";
				//$P[$id][$mois][1] = $row[0]."_".$row[2];	//valeur combinée à garder
				}
			reset ($P);
			
			$query_2bis = "select distinct AD.mois, AD.annee 
			from art_agglomeration as AA, art_debarquement as AD 
			where AA.id=AD.art_agglomeration_id 
			and AD.annee>=".$annee_deb." and AD.annee<=".$annee_fin." 
			and (";
			reset ($_POST['agglo']);
			while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
				{	
				$query_2bis .= "(AA.id =".$val_agglo.") or";
				}
			$query_2bis = substr($query_2bis, 0, -2); 		//on enleve le dernier or
			$query_2bis .= ") order by AD.annee, AD.mois";
			$result_2bis = pg_query($connection, $query_2bis);
			//print("<br>".$query_2bis);
			//print("<br>".pg_num_rows($result_2bis));
			
			//on cumul les deux resultats
			while($rowbis = pg_fetch_row($result_2bis))
				{
				$annee = $rowbis[1];	//annee
				$mois = $rowbis[0];
				
				if(!isset($P[$annee][$mois]))
					{
					$P[$annee][$mois] = "";
					//$P[$id][$mois][1] = $rowbis[0]."_".$rowbis[2];	//valeur combinée à garder
					}
				}
			reset ($P);
			
			//affichage
			print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			
			//$nb =0;
			//$n = count($P);
			//print ("<br>!!!!!!!!!nb:".$n);
			//$colonne = ceil($n/5);	//affichage de 5 par colonne
			?>
			<script language="JavaScript"><!--
			function clicTous<?php print($key_P); ?>(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('periode') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous".$key_P."(this.form,true) } else { clicTous".$key_P."(this.form,false) };\"></td><td align = right>Tout</td></tr></table>");
		
			
			
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			
			$i=0;
			while (list($key_P, $val_P) = each($P))
				{
				print ("<tr><td align='center' width=100>Année ".$key_P." :  </td>");
				while (list($key, $val) = each($val_P))
					{
					//print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$i."]\" value=\"".$key."\" ></td>");
					print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$key_P."][".$i."]\" value=\"".$key."\" ></td>");
				
					$i++;
					}
				
				
				?>
			<script language="JavaScript"><!--
			function clicTous<?php print($key_P); ?>(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('periode[<?php print($key_P); ?>]') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<td align = right>Tout</td><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous".$key_P."(this.form,true) } else { clicTous".$key_P."(this.form,false) };\"></td>");
		
		
			
				
				
				
				
				
				print ("</tr>");
				}
			print ("</table>");
			if ($i == 0)print("pas de période référencée");
			
			//rajout des variables secteurs dans le formulaire
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['agglo']);
			while (list($key, $val) = each($_POST['agglo']))
				{
				print ("<input type=\"hidden\" name=\"agglo[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
			/*
			$periode =0;
			reset($_POST['periode']);
			while (list($key, $val) = each($_POST['periode']))
				{
				print ("<input type=\"hidden\" name=\"periode[".$periode."]\" value=\"".$val."\">");
				$periode ++;
				}*/
			
			
			
				
			print ("<br><br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
			print ("</td></tr></form>");
			

			}
		
		
		
		
		
		
		
		
		
		
		
		
		//////////////////////////////////////////////////////////////////////////////
		//////selection du grand type d'engin
		
		
		else
			{
			/*$ligne_periode = "Periodes : ";
			reset ($_POST['periode']);
			
			
			
			
			
			
			while (list($key, $val) = each($_POST['periode']))
				{
				//print ("<br>!!!!".$key." , ".$val);
				$ligne_periode .= $key ." : ";
				while (list($key2, $val2)= each($val))
					{
					$ligne_periode .= "<Font Color =\"#663399\">".$val2."</font>, ";
					//print ("<br>annee".$key."periode : ".$val2." , ");
					}
				$ligne_periode = substr($ligne_periode, 0, -2);
				$ligne_periode .= "<br>";
				}
			print ($ligne_periode);*/
				
				
				
				
		
		///////selection du type d'engin

		//si engin non referencé
		if (!isset($_POST['engin']))
			{
			//choix engin de peche
	
			print ("<div align='center'><Font Color =\"#333366\">");
			print ("<br>");
			
			$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) { echo "Pas de connection"; exit;}
			
			
			
			$query_3 = "select distinct AAc.art_grand_type_engin_id, art_grand_type_engin.libelle 
			from art_agglomeration as AA, art_activite as AAc 
			left join art_grand_type_engin on art_grand_type_engin.id=AAc.art_grand_type_engin_id 
			where AA.id=AAc.art_agglomeration_id 
			and AAc.annee>=".$annee_deb." and AAc.annee<=".$annee_fin." 
			and (";
			reset ($_POST['agglo']);
			while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
				{	
				$query_3 .= "(AA.id =".$val_agglo.") or";
				}
			$query_3 = substr($query_3, 0, -2); 		//on enleve le dernier or
			$query_3 .= ") order by AAc.art_grand_type_engin_id ";
			$result_3 = pg_query($connection, $query_3);
			//print("<br>".$query_3);
			//print("<br>".pg_num_rows($result_3));
			
			$E=Array();
			while($row = pg_fetch_row($result_3))
				{
				$E[$row[0]] = $row[1];	
				}
			reset ($E);
			
			$query_3bis = "select distinct AD.art_grand_type_engin_id, art_grand_type_engin.libelle 
			from art_agglomeration as AA, art_debarquement as AD 
			left join art_grand_type_engin on art_grand_type_engin.id=AD.art_grand_type_engin_id 
			where AA.id=AD.art_agglomeration_id 
			and AD.annee>=".$annee_deb." and AD.annee<=".$annee_fin." 
			and (";
			reset ($_POST['agglo']);
			while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
				{	
				$query_3bis .= "(AA.id =".$val_agglo.") or";
				}
			$query_3bis = substr($query_3bis, 0, -2); 		//on enleve le dernier or
			$query_3bis .= ") order by AD.art_grand_type_engin_id ";
			$result_3bis = pg_query($connection, $query_3bis);
			//print("<br>".$query_3bis);
			//print("<br>".pg_num_rows($result_3bis));
			
			//on cumul les deux resultats
			while($rowbis = pg_fetch_row($result_3bis))
				{
				if(!isset($E[$rowbis[0]]))
					{
					$E[$rowbis[0]] = $rowbis[1]; 
					}
				}
			reset ($E);
			
			//affichage
			print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			
			?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('engin') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\"></td><td align='center'>Tout</td></tr></table>");
			
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			
			$i=0;
			$nb =0;
			$n = count($E);
			//$colonne = ceil($n/5);	//affichage de 5 par colonne
			$colonne = 2;
			while (list($key_E, $val_E) = each($E))
				{
				if(($key_E =="")||($key_E =="INCON"))continue;
					/*{
					$key_E_affich = "non renseigné";
					$val_E = " ";
					}*/
				else $key_E_affich = $key_E;
				$nb = $nb + 1;
				if ($nb <= $colonne)
					{
					print ("<td align='center'>".$key_E_affich." (".$val_E.") </td><td><input type=\"Checkbox\" name=\"engin[".$i."]\" value=\"".$key_E."\" ></td>");
					}
				else 
					{
					print ("</tr><tr><td align='center'>".$key_E_affich." (".$val_E.") </td><td><input type=\"Checkbox\" name=\"engin[".$i."]\" value=\"".$key_E."\" ></td>");
					$nb =1;
					}
				$i++;
				}
			print ("</table>");
			if ($i == 0)print("pas de type d'engin référencé");
			
			
			//rajout des variables secteurs dans le formulaire
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['agglo']);
			while (list($key, $val) = each($_POST['agglo']))
				{
				print ("<input type=\"hidden\" name=\"agglo[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
			//$periode =0;
			
			reset($_POST['periode']);
			
			while (list($key_P, $val_P) = each($_POST['periode']))
				{
				//print ("<tr><td align='center' width=100>Année ".$key_P." :  </td>");
				$i=0;
				while (list($key, $val) = each($val_P))
					{
					//print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$i."]\" value=\"".$key."\" ></td>");
					print ("<input type=\"hidden\" name=\"periode[".$key_P."][".$i."]\" value=\"".$val."\" ></td>");
					$i++;
					}
				}			
			
			
			
			/*
			reset($_POST['periode']);
			while (list($key, $val) = each($_POST['periode']))
				{
				print ("<input type=\"hidden\" name=\"periode[".$periode."]\" value=\"".$val."\">");
				$periode ++;
				}*/
			print ("<br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
			print ("</td></tr></form>");
			}//fin du if engin n'existe pas
		else
			{
			print ("<div align='center'>");
	
	/*while (list($key, $val) = each($_POST['periode']))
				{
				//print ("<br>!!!!".$key." , ".$val);
				while (list($key2, $val2)= each($val))
					{
					print ("<br>annee".$key."periode : ".$val2." , ");
					$periode ++;
					}
				}*/
	
	/*
			$allign=0;
			$ligne_engin = "Engins : ";
			reset ($_POST['engin']);
			while (list($key_eng, $val_engin) = each($_POST['engin']))
				{
				$ligne_engin .= "<Font Color =\"#663399\">".$val_engin."</font>, ";
				$allign++;
				if (($allign == 7)||($allign ==14)||($allign == 21))
					{
					$ligne_engin .= "<br>";
					}
				}
			$ligne_engin = substr($ligne_engin, 0, -2); 		//on enleve la derniere virgule
			print ($ligne_engin);*/
			
			
			//affichage du nombre d’enquêtes de débarquement et d’activité correspondant aux selections
			
			
			
			
			
			
			
			////////////
			//selection du type de traitement
			
			print ("<div align='center'><Font Color =\"#333366\">");
			print ("<br><br><br>");
			

			print ("<form name=\"form_tot\" method=\"post\" action=\"art_filieres.php\">");
			//print ("<form name=\"form_tot\" method=\"post\" action=\"sc_filieres.php\" onSubmit=\"OuvrirPopup()\">");
			//print ("<form name=\"form\" method=\"post\" action=\"javascript:OuvrirPopup('sc_filieres.php','','top=10,left=400,resizable=yes, width=600, height=150, scrollbars=yes ')\">");
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['agglo']);
			while (list($key, $val) = each($_POST['agglo']))
				{
				print ("<input type=\"hidden\" name=\"agglo[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
			
			reset($_POST['periode']);
			
			while (list($key_P, $val_P) = each($_POST['periode']))
				{
				//print ("<tr><td align='center' width=100>Année ".$key_P." :  </td>");
				$i=0;
				while (list($key, $val) = each($val_P))
					{
					//print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$i."]\" value=\"".$key."\" ></td>");
					print ("<input type=\"hidden\" name=\"periode[".$key_P."][".$i."]\" value=\"".$val."\" ></td>");
					$i++;
					}
				}	
			
			
			
			
			
				$eng =0;
			reset($_POST['engin']);
			while (list($key, $val) = each($_POST['engin']))
				{
				print ("<input type=\"hidden\" name=\"engin[".$eng."]\" value=\"".$val."\">");
				$eng ++;
				}
			
			
			
			
			print ("<table><tr>");
			
			print ("<td><input type=\"submit\" name=\"choix\" value=\"   Activité    \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("<td><input type=\"submit\" name=\"choix\" value=\" Captures \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("<td><input type=\"submit\" name=\"choix\" value=\"     Nt, Pt     \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("<td><input type=\"submit\" name=\"choix\" value=\"     Taille     \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("<td><input type=\"submit\" name=\"choix\" value=\"    Engin     \" onclick=\"pop_it(form_tot);\" ></td>");
			print ("</tr></table>");
			}
			print ("</form>");
		
		
		print("</div>");
		}
	}////////////////////////////////////////////////////////////rajout pour periode

	
	
	
	
	
	
	
	
	// Deconnexion de la base de donnees
	pg_close();
	
	print ("<table><tr><td>");
	print ("<form name=\"form2\" method=\"post\" action=\"selection.php\">");
			print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
	//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
	$i=0;
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	$i=0;
	reset ($_POST['systeme']);
	while (list($key, $val) = each($_POST['systeme']))
		{
		print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
	print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
	
	
	print ("</div>");

	
	}
}//de if artisanale


/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
//filiere statistiques de pêches
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
if($type=="statistique")
{
//print ("<br>".$type." , annee deb ".$annee_deb." anne fin : ".$annee_fin); 
//si le ou les secteurs ne sont pas encore choisis
if (!isset($_POST['secteur']))
	{
	
	
	//affichage des données sélectionnées auparavant
	print ("<div align='center'><br><Font Color =\"#333366\"><b>Sélection du secteur</b></font><br><br>");
	print ("Pays : ");
	$i=0;
	$ligne_a_afficher="";
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Système : ");
	$i=0;
	reset ($_POST['systeme']);
	$ligne_a_afficher="";
	while (list($key, $val) = each($_POST['systeme']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Période : <Font Color =\"#663399\">".$annee_deb."</Font>-"."<Font Color =\"#663399\">".$annee_fin);
	
	//print ("<br>Pays : <Font Color =\"#663399\">".$pays."</Font> , Système : <Font Color =\"#663399\">".$systeme."</Font> , Année : <Font Color =\"#663399\">".$annee."</Font>");
	print ("</div>");
	/////suite

	print ("<div align='center'><Font Color =\"#333366\">");
	
	$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	
	$query = "select distinct RS.nom, RP.nom, RSy.libelle 
	from ref_pays as RP, ref_secteur as RS, ref_systeme as RSy, art_agglomeration, 
	art_stat_totale 
	where RSy.ref_pays_id = RP.id and RSy.id = RS.ref_systeme_id 
	and RS.id=art_agglomeration.ref_secteur_id 
	and art_stat_totale.art_agglomeration_id=art_agglomeration.id 
	and art_stat_totale.annee>=".$annee_deb." and art_stat_totale.annee<=".$annee_fin." 
	and (";
	
	reset ($_POST['systeme']);
		while (list($key, $val) = each($_POST['systeme']))
			{	
			$query .= "(RSy.libelle ='".$val."') or";
			}
		$query = substr($query, 0, -2); 		//on enleve le dernier or
		$query .= ") order by RP.nom, RSy.libelle, RS.nom ";
	
	//print ($query);
	$result = pg_query($connection, $query);
	
	$ST=Array();
	$i = 0;
	while($row = pg_fetch_row($result))
		{
		//$ST[$i][0] = $row[0];	//libelle secteur
		//$ST[$i][1] = $row[1];	//pays
		//$ST[$i][2] = $row[2];	//systeme
		//$i++;
		$STX[$row[1]][$row[2]][$row[0]]="";//pays, syst, secteur =""
		}
	
	// Deconnexion de la base de donnees
	pg_close();



		
	 
	//affichage des secteurs du systeme préalablement choisi
	
	print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
									print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	
	?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('secteur') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
	print ("<table><tr><td align = right><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");

	
	
	$nb =0;
	$n = count($ST);
	$i=0;
	//$colonne = ceil($n/5);	//affichage de 5 par colonne
	$colonne =3;
	
	/*print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
	while (list($key, $val) = each($ST))
		{
		//print ("<br>".$val[0]." , ".$val[1]);
		$nb = $nb + 1;
		if ($nb <= $colonne)
			{
			print ("<td><input type=\"Checkbox\" name=\"secteur[".$i."]\" value=\"".$val[0]."\">".$val[0]."  (".$val[1].", ".$val[2].")</td>");
			}
		else 	{
			print ("</tr><tr><td><input type=\"Checkbox\" name=\"secteur[".$i."]\" value=\"".$val[0]."\">".$val[0]."  (".$val[1].", ".$val[2].")</td>");
			$nb =1;
			}
		$i++;
		}
		print ("</table>");*/
		
		print ("<table>");
	reset($STX);
	$i=0;
	while (list($key_stx, $val_stx) = each($STX))
		{
		while (list($key_stx2, $val_stx2) = each($val_stx))//pour tous les systemes
			{
			print ("<tr><td width=\"200\"><b>".$key_stx.", ".$key_stx2."</b></td></tr><tr><td>");
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			$nb_ds_ligne =0;
			while (list($key_stx3, $val_stx3) = each($val_stx2))//pour tous les secteurs
				{
				$nb_ds_ligne ++;
				if ($nb_ds_ligne ==4)
					{
					print ("</tr><tr>");
					$nb_ds_ligne =0;
					}
				
				print ("<td width=\"200\"><input type=\"Checkbox\" name=\"secteur[".$i."]\" value=\"".$key_stx3."\">".$key_stx3."</td>");
				$i++;
				}
			print ("</tr></table></td></tr>");
			}
		}
		print ("</td></tr></table>");
		
		
		
		
	//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
	//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
	$i=0;
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		print ("<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	$i=0;
	reset ($_POST['systeme']);
	while (list($key, $val) = each($_POST['systeme']))
		{
		print ("<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<br><br><table><tr><td  align = center><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
	print ("</td></tr></form>");
	
	
	

	
	
	
	/////////retour preselection

print ("<table><tr><td>");
	print ("<form name=\"retour\" method=\"post\" action=\"preselection.php\">");
						print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=\"hidden\" name=\"login\" value=\"".$login."\">");
	print ("<input type=\"hidden\" name=\"passe\" value=\"".$passe."\">");
	print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
	print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
	
	
	
	print ("</div>");
	//////////
	
	}
	
//sinon on choisi les agglomerations
else
	{
	print ("<div align='center'><Font Color =\"#333366\">");
	if (!isset($_POST['agglo']))print ("<br><Font Color =\"#333366\"><b>Sélection des agglomérations</b></font>");
	else if (!isset($_POST['periode'])) print ("<br><Font Color =\"#333366\"><b>Sélection des périodes d'enquêtes</b></font>");
	else if (!isset($_POST['engin'])) print ("<br><Font Color =\"#333366\"><b>Sélection des engins de pêche</b></font>");
	else if (!isset($_POST['espece'])) print ("<br><Font Color =\"#333366\"><b>Sélection des groupements d'espèces</b></font>");
	else print ("<br><Font Color =\"#333366\"><b>Séléction des tables résultats</b></font>");
	print ("<br><br>");
	print ("Pays : ");
	$i=0;
	$ligne_a_afficher="";
	reset($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		//print("<br>*****".$key);
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Système : ");
	$i=0;
	reset ($_POST['systeme']);
	$ligne_a_afficher="";
	while (list($key, $val) = each($_POST['systeme']))
		{
		$ligne_a_afficher .= "<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
		$i++;
		}
	$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
	print($ligne_a_afficher);
	print ("<br>Période : <Font Color =\"#663399\">".$annee_deb."</Font>-"."<Font Color =\"#663399\">".$annee_fin);
	print ("</Font><br>");

	
	/*$ligne = "Secteur : ";
	while (list($key, $val) = each($_POST['secteur']))
		{
		$ligne .= "<Font Color =\"#663399\">".$val."</font>, ";
		}
	$ligne = substr($ligne, 0, -2); 		//on enleve la derniere virgule
	print ($ligne);
	print("</div>");*/
	
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//si campagne non referencées
	if (!isset($_POST['agglo']))
		{
		print ("<div align='center'><Font Color =\"#333366\">");
		print ("<br>");
		
		
		
		
		$query_1 = "select distinct AA.id, AA.nom, RSy.libelle, RS.nom from art_agglomeration as AA, 
		art_stat_totale, ref_systeme as Rsy, ref_secteur as RS 
		where art_stat_totale.art_agglomeration_id=AA.id 
		and RSY.id=RS.ref_systeme_id 
		and RS.id=AA.ref_secteur_id 
		and art_stat_totale.annee>=".$annee_deb." and art_stat_totale.annee<=".$annee_fin." 
		and (";
		reset ($_POST['secteur']);
		while (list($key, $val) = each($_POST['secteur']))
			{	
			$query_1 .= "(RS.nom ='".$val."') or";
			}
		$query_1 = substr($query_1, 0, -2); 		//on enleve le dernier or
		$query_1 .= ") order by RS.nom, RS.nom, AA.nom ";
		
		//print ("<br>".$query_1);
		$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
		if (!$connection) { echo "Pas de connection"; exit;}
		$result_1 = pg_query($connection,$query_1);
		
		$A=Array();
		$i = 0;
		while($row_1 = pg_fetch_row($result_1))
			{
			//print ("<br>lkopkopk");
			//$A[$i][0] = $row_1[0];	//id agglo
			//$A[$i][1] = $row_1[1];	//nom
			//$A[$i][2] = $row_1[2];	//systeme
			//$A[$i][3] = $row_1[3];	//secteur
			//$i++;
			$A[$row_1[2]][$row_1[3]][$row_1[0]] = $row_1[1];
			}
		
		/////////////////affichage
		
		
		print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
										print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
		
		?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('agglo') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<table><tr><td align = middle><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
		
		
		$nb =0;
		$n = count($A);
		$i=0;
		$colonne = ceil($n/5);	//affichage de 5 par colonne
		
		//print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
		reset ($A);
		
		
		
		while (list($key, $val) = each($A))//pour chaque systeme
			{
			while (list($key2, $val2) = each($val))//pour chaque secteur
				{
				$nb_ds_ligne =0;
				print ("<table><tr><td align=\"left\"><b>".$key.", ".$key2."</b></td></tr><tr><td>");
				print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
				while (list($key3, $val3) = each($val2))//pour chaque agglo
					{
					$nb_ds_ligne ++;
					if ($nb_ds_ligne ==4)
						{
						print ("</tr><tr>");
						$nb_ds_ligne =0;
						}
					print ("<td width =\"200\"><input type=\"Checkbox\" name=\"agglo[".$i."]\" value=\"".$key3."\">".$val3."</td>");
					$i++;
					}
				print ("</tr></table></td></tr></table>");
				}
			
			
			}
		
		/*
		$nb =0;
		$n = count($A);
		$i=0;
		$colonne = ceil($n/5);	//affichage de 5 par colonne
		
		print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
		reset ($A);
		while (list($key, $val) = each($A))
			{
			//print ("<br>".$val[0]." , ".$val[1]);
			$nb = $nb + 1;
			if ($nb <= $colonne)
				{
				print ("<td><input type=\"Checkbox\" name=\"agglo[".$i."]\" value=\"".$val[0]."\">".$val[1]."(".$val[2].", ".$val[3].")</td>");
				}
			else 	{
				print ("</tr><tr><td><input type=\"Checkbox\" name=\"agglo[".$i."]\" value=\"".$val[0]."\">".$val[1]."(".$val[2].", ".$val[3].")</td>");
				$nb =1;
				}
			$i++;
			}*/
		if ($i == 0)print("pas d'agglomération référencée dans la période");
		
		
		print ("</tr></table>");
		
		//rajout des variables secteurs dans le formulaire
		//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
		//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
		$i=0;
		reset ($_POST['pays']);
		while (list($key, $val) = each($_POST['pays']))
			{
			print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
			$i++;
			}
		$i=0;
		reset ($_POST['systeme']);
		while (list($key, $val) = each($_POST['systeme']))
			{
			print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
			$i++;
			}
		print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");

		$sec=0;
		reset($_POST['secteur']);
		while (list($key, $val) = each($_POST['secteur']))
			{
			print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
			$sec ++;
			}
		print ("<input type=hidden name=\"type\" value=\"".$type."\">");
		print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
		print ("<br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
		print ("</td></tr></form>");	
			
		}//fin du if agglo non referencées
	else
		{
		print ("<div align='center'>");
	//print ("<br>agglo : ");

		/*$ligne_agglo = "Identifiants agglomérations (base de données): ";
		reset ($_POST['agglo']);
		while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
			{
			$ligne_agglo .= "<Font Color =\"#663399\">".$val_agglo."</font>, ";
			}
		$ligne_agglo = substr($ligne_agglo, 0, -2); 		//on enleve la derniere virgule
		print ($ligne_agglo);*/
		
		
		//////////////////////////////////////
		//selection des periodes d enquetes par agglomeration
		
		//si periode non referencée
		if (!isset($_POST['periode']))
			{
			//choix periode
	
			print ("<div align='center'>");
			
			$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) { echo "Pas de connection"; exit;}
			
			$query_2 = "select distinct art_stat_totale.mois, art_stat_totale.annee 
			from art_agglomeration as AA, art_stat_totale 
			where AA.id=art_stat_totale.art_agglomeration_id 
			and art_stat_totale.annee>=".$annee_deb." and art_stat_totale.annee<=".$annee_fin." 
			and (";
			reset ($_POST['agglo']);
			while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
				{	
				$query_2 .= "(AA.id =".$val_agglo.") or";
				}
			$query_2 = substr($query_2, 0, -2); 		//on enleve le dernier or
			$query_2 .= ") order by art_stat_totale.annee, art_stat_totale.mois";
			$result_2 = pg_query($connection, $query_2);
			//print("<br>".$query_2);
			//print("<br>".pg_num_rows($result_2));
			
			$P=Array();
			while($row = pg_fetch_row($result_2))
				{
				$annee = $row[1];	//annee
				$mois = $row[0];
				$P[$annee][$mois]="";
				//$P[$id][$mois][1] = $row[0]."_".$row[2];	//valeur combinée à garder
				}
			reset ($P);

			
			//affichage
			print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			
			
			/*print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			
			$i=0;
			while (list($key_P, $val_P) = each($P))
				{
				print ("<tr><td align='center' width=100>Année ".$key_P." :  </td>");
				while (list($key, $val) = each($val_P))
					{
					//print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$i."]\" value=\"".$key."\" ></td>");
					print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$key_P."][".$i."]\" value=\"".$key."\" ></td>");
				
					$i++;
					}
				print ("</tr>");
				}
			print ("</table>");*/
			
			?>
			<script language="JavaScript"><!--
			function clicTous<?php print($key_P); ?>(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('periode') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous".$key_P."(this.form,true) } else { clicTous".$key_P."(this.form,false) };\"></td><td align = right>Tout</td></tr></table>");
		
			
			
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			
			$i=0;
			while (list($key_P, $val_P) = each($P))
				{
				print ("<tr><td align='center' width=100>Année ".$key_P." :  </td>");
				while (list($key, $val) = each($val_P))
					{
					//print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$i."]\" value=\"".$key."\" ></td>");
					print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$key_P."][".$i."]\" value=\"".$key."\" ></td>");
				
					$i++;
					}
				
				
				?>
			<script language="JavaScript"><!--
			function clicTous<?php print($key_P); ?>(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('periode[<?php print($key_P); ?>]') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<td align = right>Tout</td><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous".$key_P."(this.form,true) } else { clicTous".$key_P."(this.form,false) };\"></td>");
		
		
			
				
				
				
				
				
				print ("</tr>");
				}
			print ("</table>");
			if ($i == 0)print("pas de période référencée");
			
			//rajout des variables secteurs dans le formulaire
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['agglo']);
			while (list($key, $val) = each($_POST['agglo']))
				{
				print ("<input type=\"hidden\" name=\"agglo[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
			/*
			$periode =0;
			reset($_POST['periode']);
			while (list($key, $val) = each($_POST['periode']))
				{
				print ("<input type=\"hidden\" name=\"periode[".$periode."]\" value=\"".$val."\">");
				$periode ++;
				}*/
			
			print ("<br><br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
			print ("</td></tr></form>");

			}
		
		
		
		
		
		
		
		
		
		
		
		
		//////////////////////////////////////////////////////////////////////////////
		//////selection du grand type d'engin
		
		
		else
			{
			$ligne_periode = "Identifiants periode (base de données): ";
			reset ($_POST['periode']);
			/*while (list($key, $val) = each($_POST['periode']))
				{
				$ligne_periode .= "<Font Color =\"#663399\">".$val."</font>, ";
				}
			$ligne_periode = substr($ligne_periode, 0, -2); 		//on enleve la derniere virgule
			print ("<br>".$ligne_periode);*/
			
			
			
			
			/*
			while (list($key, $val) = each($_POST['periode']))
				{
				//print ("<br>!!!!".$key." , ".$val);
				while (list($key2, $val2)= each($val))
					{
					print ("<br>annee".$key."periode : ".$val2." , ");
					$periode ++;
					}
				}*/
				
				
				
				
				
		
		///////selection du type d'engin

		//si engin non referencé
		if (!isset($_POST['engin']))
			{
			//choix engin de peche
	
			print ("<div align='center'><Font Color =\"#333366\">");
			print ("<br>");
			
			$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) { echo "Pas de connection"; exit;}
			
			
			
			$query_3 = "select distinct art_stat_gt.art_grand_type_engin_id, art_grand_type_engin.libelle 
			from art_agglomeration as AA, art_stat_totale, art_stat_gt, art_grand_type_engin 
			where AA.id=art_stat_totale.art_agglomeration_id 
			and art_stat_totale.id=art_stat_gt.art_stat_totale_id 
			and art_grand_type_engin.id=art_stat_gt.art_grand_type_engin_id 
			and art_stat_totale.annee>=".$annee_deb." and art_stat_totale.annee<=".$annee_fin." 
			and (";
			reset ($_POST['agglo']);
			while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
				{	
				$query_3 .= "(AA.id =".$val_agglo.") or";
				}
			$query_3 = substr($query_3, 0, -2); 		//on enleve le dernier or
			$query_3 .= ") order by art_stat_gt.art_grand_type_engin_id ";
			$result_3 = pg_query($connection, $query_3);
			//print("<br>".$query_3);
			//print("<br>".pg_num_rows($result_3));
			
			$E=Array();
			while($row = pg_fetch_row($result_3))
				{
				$E[$row[0]] = $row[1];	
				}
			reset ($E);
			
			
			
			//affichage
			print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			
/*
			?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
			  {
			  for (i=0, n=form.elements.length; i<n; i++)
			  if (form.elements[i].name.indexOf('engin') != -1)
			    form.elements[i].checked = booleen;
			  }
			//--></script>
			<?php
			

			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			
			$i=0;
			$nb =0;
			$n = count($E);
			//$colonne = ceil($n/5);	//affichage de 5 par colonne
			$colonne = 2;
			while (list($key_E, $val_E) = each($E))
				{
				if($key_E =="")
					{
					$key_E_affich = "non renseigné";
					$val_E = " ";
					}
				else $key_E_affich = $key_E;
				$nb = $nb + 1;
				if ($nb <= $colonne)
					{
					print ("<td align='center'>".$key_E_affich." (".$val_E.") </td><td><input type=\"Checkbox\" name=\"engin[".$i."]\" value=\"".$key_E."\" ></td>");
					}
				else 
					{
					print ("</tr><tr><td align='center'>".$key_E_affich." (".$val_E.") </td><td><input type=\"Checkbox\" name=\"engin[".$i."]\" value=\"".$key_E."\" ></td>");
					$nb =1;
					}
				$i++;
				}
			print ("</tr><tr><td align='center'>Tout</td><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\"></td></tr>");
			print ("</table>");*/
			//affichage
			print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			
			?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
				{
				for (i=0, n=form.elements.length; i<n; i++)
				if (form.elements[i].name.indexOf('engin') != -1)
				form.elements[i].checked = booleen;
				}
			//--></script>
			<?php
			print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\"></td><td align='center'>Tout</td></tr></table>");
			
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			
			$i=0;
			$nb =0;
			$n = count($E);
			//$colonne = ceil($n/5);	//affichage de 5 par colonne
			$colonne = 2;
			while (list($key_E, $val_E) = each($E))
				{
				if(($key_E =="")||($key_E =="INCON"))continue;
					/*{
					$key_E_affich = "non renseigné";
					$val_E = " ";
					}*/
				else $key_E_affich = $key_E;
				$nb = $nb + 1;
				if ($nb <= $colonne)
					{
					print ("<td align='center'>".$key_E_affich." (".$val_E.") </td><td><input type=\"Checkbox\" name=\"engin[".$i."]\" value=\"".$key_E."\" ></td>");
					}
				else 
					{
					print ("</tr><tr><td align='center'>".$key_E_affich." (".$val_E.") </td><td><input type=\"Checkbox\" name=\"engin[".$i."]\" value=\"".$key_E."\" ></td>");
					$nb =1;
					}
				$i++;
				}
			print ("</table>");
			
			if ($i == 0)print("pas de type d'engin référencé");
			
			
			//rajout des variables secteurs dans le formulaire
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['agglo']);
			while (list($key, $val) = each($_POST['agglo']))
				{
				print ("<input type=\"hidden\" name=\"agglo[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
			//$periode =0;
			
			reset($_POST['periode']);
			
			while (list($key_P, $val_P) = each($_POST['periode']))
				{
				//print ("<tr><td align='center' width=100>Année ".$key_P." :  </td>");
				$i=0;
				while (list($key, $val) = each($val_P))
					{
					//print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$i."]\" value=\"".$key."\" ></td>");
					print ("<input type=\"hidden\" name=\"periode[".$key_P."][".$i."]\" value=\"".$val."\" ></td>");
					$i++;
					}
				}
			
			print ("<br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
			print ("</td></tr></form>");
			}//fin du if engin n'existe pas
			
			
			
			
		
		
		
		
		//////////////////////////////////////////////////////////////////////////////
		//////selection des espèces
		

		//si espece non referencé
		else if (!isset($_POST['espece']))
			{
			//choix especes
	
			print ("<div align='center'>");
			
			$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
			if (!$connection) { echo "Pas de connection"; exit;}
			
			
			
			$query_4 = "select distinct art_stat_sp.ref_espece_id, ref_espece.libelle ,ref_famille.libelle 
			from art_agglomeration as AA, art_stat_totale, art_stat_sp, ref_espece, ref_famille 
			where AA.id=art_stat_totale.art_agglomeration_id 
			and art_stat_totale.id=art_stat_sp.art_stat_totale_id 
			and ref_espece.id=art_stat_sp.ref_espece_id 
			and ref_famille.id=ref_espece.ref_famille_id 
			and art_stat_totale.annee>=".$annee_deb." and art_stat_totale.annee<=".$annee_fin." 
			and (";
			reset ($_POST['agglo']);
			while (list($key_agglo, $val_agglo) = each($_POST['agglo']))
				{	
				$query_4 .= "(AA.id =".$val_agglo.") or";
				}
			$query_4 = substr($query_4, 0, -2); 		//on enleve le dernier or
			$query_4 .= ") order by ref_famille.libelle, art_stat_sp.ref_espece_id ";
			$result_4 = pg_query($connection, $query_4);
			//print("<br>".$query_4);
			//print("<br>".pg_num_rows($result_3));
			
			$E=Array();
			while($row = pg_fetch_row($result_4))
				{
				$Esp[$row[0]][0] = $row[1];
				$Esp[$row[0]][1] = $row[2];	
				}
			reset ($Esp);
			
			
			
			
			?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
			  {
			  for (i=0, n=form.elements.length; i<n; i++)
			  if (form.elements[i].name.indexOf('espece') != -1)
			    form.elements[i].checked = booleen;
			  }
			//--></script>
			<?php
			
			
			
			
			
			
			
			
			//affichage
			print ("<form name=\"form\" method=\"post\" action=\"selection.php\">"); 
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			
			print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\"></td><td align='center'>Tout</td></tr></table>");
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			
			$i=0;
			$nb =0;
			$n = count($Esp);
			//$colonne = ceil($n/5);	//affichage de 5 par colonne
			$colonne = 3;
			while (list($key_Esp, $val_Esp) = each($Esp))
				{
				if($key_Esp =="")
					{
					$key_Esp_affich = "non renseigné";
					$val_Esp = " ";
					}
				else $key_Esp_affich = $key_Esp;
				$nb = $nb + 1;
				if ($nb <= $colonne)
					{
					print ("<td align='center'>".$key_Esp_affich." (".$val_Esp[1].", ".$val_Esp[0].") </td><td><input type=\"Checkbox\" name=\"espece[".$i."]\" value=\"".$key_Esp."\" ></td>");
					}
				else 
					{
					print ("</tr><tr><td align='center'>".$key_Esp_affich." (".$val_Esp[1].", ".$val_Esp[0].") </td><td><input type=\"Checkbox\" name=\"espece[".$i."]\" value=\"".$key_Esp."\" ></td>");
					$nb =1;
					}
				$i++;
				}
			print ("</tr>");
			print ("</table>");
			if ($i == 0)print("pas de type d'espèces référencées");
			
			
			//rajout des variables secteurs dans le formulaire
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['agglo']);
			while (list($key, $val) = each($_POST['agglo']))
				{
				print ("<input type=\"hidden\" name=\"agglo[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
			//$periode =0;
			
			reset($_POST['periode']);
			
			while (list($key_P, $val_P) = each($_POST['periode']))
				{
				//print ("<tr><td align='center' width=100>Année ".$key_P." :  </td>");
				$i=0;
				while (list($key, $val) = each($val_P))
					{
					//print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$i."]\" value=\"".$key."\" ></td>");
					print ("<input type=\"hidden\" name=\"periode[".$key_P."][".$i."]\" value=\"".$val."\" ></td>");
					$i++;
					}
				}
			
			$engin =0;
			reset($_POST['engin']);
			while (list($key, $val) = each($_POST['engin']))
				{
				print ("<input type=\"hidden\" name=\"engin[".$engin."]\" value=\"".$val."\">");
				$engin ++;
				}
			
			
			
			print ("<br><br><table><tr><td><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
			print ("</td><tr></form>");
			}//fin du if especes n'existe pas
			
			
			
			
			
			
			
		else
			{
			print ("<div align='center'>");
	
	/*while (list($key, $val) = each($_POST['periode']))
				{
				//print ("<br>!!!!".$key." , ".$val);
				while (list($key2, $val2)= each($val))
					{
					print ("<br>annee".$key."periode : ".$val2." , ");
					$periode ++;
					}
				}*/
	
	
	/*
			$ligne_engin = "Identifiants engins (base de données) : ";
			reset ($_POST['engin']);
			while (list($key_eng, $val_engin) = each($_POST['engin']))
				{
				$ligne_engin .= "<Font Color =\"#663399\">".$val_engin."</font>, ";
				}
			$ligne_engin = substr($ligne_engin, 0, -2); 		//on enleve la derniere virgule
			print ($ligne_engin);*/
			
			
			//affichage du nombre d’enquêtes de débarquement et d’activité correspondant aux selections
			
			
			
			
			
			
			
			////////////
			//selection du type de traitement
			
			print ("<div align='center'><Font Color =\"#333366\">");
			print ("<br>");
			

			print ("<form name=\"form_tot\" method=\"post\" action=\"stat_filieres.php\">");
			//print ("<form name=\"form_tot\" method=\"post\" action=\"sc_filieres.php\" onSubmit=\"OuvrirPopup()\">");
			//print ("<form name=\"form\" method=\"post\" action=\"javascript:OuvrirPopup('sc_filieres.php','','top=10,left=400,resizable=yes, width=600, height=150, scrollbars=yes ')\">");
											print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			//print ("<input type=\"hidden\" name=\"pays\" value=\"".$pays."\">");
			//print ("<input type=\"hidden\" name=\"systeme\" value=\"".$systeme."\">");
			$i=0;
			reset ($_POST['pays']);
			while (list($key, $val) = each($_POST['pays']))
				{
				print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			$i=0;
			reset ($_POST['systeme']);
			while (list($key, $val) = each($_POST['systeme']))
				{
				print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
				$i++;
				}
			print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
			$sec=0;
			reset($_POST['secteur']);
			while (list($key, $val) = each($_POST['secteur']))
				{
				print ("<input type=\"hidden\" name=\"secteur[".$sec."]\" value=\"".$val."\">");
				$sec ++;
				}
			$cam =0;
			reset($_POST['agglo']);
			while (list($key, $val) = each($_POST['agglo']))
				{
				print ("<input type=\"hidden\" name=\"agglo[".$cam."]\" value=\"".$val."\">");
				$cam ++;
				}
			
			reset($_POST['periode']);
			
			while (list($key_P, $val_P) = each($_POST['periode']))
				{
				//print ("<tr><td align='center' width=100>Année ".$key_P." :  </td>");
				$i=0;
				while (list($key, $val) = each($val_P))
					{
					//print ("<td> n° ".$key." : </td><td><input type=\"Checkbox\" name=\"periode[".$i."]\" value=\"".$key."\" ></td>");
					print ("<input type=\"hidden\" name=\"periode[".$key_P."][".$i."]\" value=\"".$val."\" ></td>");
					$i++;
					}
				}
			
				$eng =0;
			reset($_POST['engin']);
			while (list($key, $val) = each($_POST['engin']))
				{
				print ("<input type=\"hidden\" name=\"engin[".$eng."]\" value=\"".$val."\">");
				$eng ++;
				}
			
				$esp =0;
				reset($_POST['espece']);
				while (list($key, $val) = each($_POST['espece']))
					{
					print ("<input type=\"hidden\" name=\"espece[".$esp."]\" value=\"".$val."\">");
					$esp ++;
					}
			
			?>
			<script language="JavaScript"><!--
			function clicTous(form,booleen) 
			  {
			  for (i=0, n=form.elements.length; i<n; i++)
			  if (form.elements[i].name.indexOf('case') != -1)
			    form.elements[i].checked = booleen;
			  }
			//--></script>
			<?php
			
			
				print ("<table><tr><td align='center' width=\"30\"><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\"></td><td>Tout</td></tr></table>");
			
			//print ("Séléction des tables resultats souhaités :");
			print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1>");
			print ("<tr><td align='center' width=\"30\"></td><td><b>Table</b></td><td><b>Fonction</b></td></tr>");
			print ("<tr><td align='center' width=\"30\"><input type=\"Checkbox\" name=\"case1\" value=\"cap_tot\"></td><td>Cap_tot</td><td>Résultats globaux de la strate ST</td></tr>");
			print ("<tr><td align='center' width=\"30\"><input type=\"Checkbox\" name=\"case2\" value=\"cap_sp\"></td><td>Cap_sp</td><td>Résultats par espèces</td></tr>");
			print ("<tr><td align='center' width=\"30\"><input type=\"Checkbox\" name=\"case3\" value=\"dft_sp\"></td><td>DFT_sp</td><td>Structure en taille des espèces</td></tr>");
			print ("<tr><td align='center' width=\"30\"><input type=\"Checkbox\" name=\"case4\" value=\"cap_gt\"></td><td>Cap_GT</td><td>Résultats globaux par GT de la strate ST</td></tr>");
			print ("<tr><td align='center' width=\"30\"><input type=\"Checkbox\" name=\"case5\" value=\"cap_gt_sp\"></td><td>Cap_GT_sp</td><td>Résultats par espèces et par GT</td></tr>");
			print ("<tr><td align='center' width=\"30\"><input type=\"Checkbox\" name=\"case6\" value=\"dft_gt_sp\"></td><td>DFT_GT_sp</td><td>Structure en taille des espèces par GT</td></tr>");
			
			
		
			print ("</table>");
			
			
			print ("<br><table><tr><td><input type=\"submit\" name=\"go\" value=\"    Valider   \" onclick=\"pop_it(form_tot);\" >");
			
			}
			print ("</td></tr></form>");
		
		
		print("</div>");
		}
	}////////////////////////////////////////////////////////////rajout pour periode

	
	
	
	
	
	
	
	
	// Deconnexion de la base de donnees
	pg_close();
	
	print ("<table><tr><td>");
	print ("<form name=\"form2\" method=\"post\" action=\"selection.php\">");
			print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
	//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
	$i=0;
	reset ($_POST['pays']);
	while (list($key, $val) = each($_POST['pays']))
		{
		print ("<input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	$i=0;
	reset ($_POST['systeme']);
	while (list($key, $val) = each($_POST['systeme']))
		{
		print ("<input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ");
		$i++;
		}
	print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
	print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
	print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
	
	
	print ("</div>");

	
	}
}





?>

</div>

</body>
</HTML>