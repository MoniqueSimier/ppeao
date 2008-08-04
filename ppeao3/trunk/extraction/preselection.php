<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">
</head>
<body BGCOLOR="#CCCCFF">



<?php
include_once("../connect.inc");
$connection = pg_connect ("host=".$host." dbname=".$db_default." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}

$login = $_POST['login'];
$passe = $_POST['passe'];
$type = $_POST['type'];
$type_donnees = $_POST['type_donnees'];

$annee_deb = $_POST['annee_deb'];
$annee_fin = $_POST['annee_fin'];
//print("log : ".$login."  , mtp : ".$passe." , type :".$type);
//if(isset($_POST['base']))$bdd = $_POST['base'];
//else $bdd="peche_exp_27_09";
//print("<br>travail sur la base : ".$bdd);
$entete = "Consultation / Extraction de données";
if(isset($_POST['type']))
	{
	if ($type==artisanale)$entete = "Consultation / Extraction de données de pêche artisanales";
	else if ($type==scientifique)$entete = "Consultation / Extraction de données de pêche scientifiques";
	else if ($type==statistique)$entete = "Consultation / Extraction de données statistiques";
	}
	
?>
<div align='center'><Font Color ="#333366">
<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH="600">
<tr><td align='center'><h3><b><Font Color ="#333366">Base de Données PPEAO</font></b></h3>
<h4><Font Color ="#333366">Peuplements de poissons et Pêche artisanale des Ecosystèmes estuariens,</font>
<br>
<Font Color ="#333366">lagunaires ou continentaux d’Afrique de l’Ouest</font></h4>
<Font Color ="#333366"><?php print($entete); ?></font>
</td></tr>
</table>
</div>



<?php


/*$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
//$host= "vmppeao.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
$host= "localhost";	// L'hôte (ordinateur sur lequel le SGBD est installé) 

$bdd = "bd_peche";
$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);

if (!$connection) { echo "Pas de connection"; exit;}
*/




if($type=="")
	{
	//test si authentification s'est bien déroulée
	//$connection = pg_connect ("host=".$host." dbname=".$db_default." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}
	
	
	$query_auth = "select login, password from ref_utilisateurs where login='".$login."';";
	$result_auth = pg_query($connection, $query_auth);
	//print ($query_auth);

	while($row = pg_fetch_row($result_auth))
		{
		$password=$row[1];
		//print("<br>".$row[0].", ".$row[1]);
		}
	//pg_close();
	if($password==$passe)print("");
	else print("<div align=\"center\"><br>Attention, vous n'avez pas été identifié.</div>");
	
		
	
	print ("<div align='center'>");
	print ("<br><br><br><br><br>");
	print ("<form name=\"form_selction\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
			print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base 
	
	print ("<Font Color =\"#333366\"><b>Sélection du type de données à traiter</b></font><br><br>");
	
	print ("<br><table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
	print ("<td><input type=\"radio\" name=\"type\" value=\"scientifique\">Données de pêche scientifique</td>");
	print ("<td><input type=\"radio\" name=\"type\" value=\"artisanale\">Données de pêche artisanale</td>");
	print ("<td><input type=\"radio\" name=\"type\" value=\"statistique\">Données de statistiques de pêche</td>");
	
	print ("</tr></table>");
	
	print ("<br><br><table><tr><td><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</td></tr></form><tr><td>");
	
	
	
	print ("<form name=\"form_selction\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"aa\" value=\"        Fin        \" onClick= \"return confirm('Etes vous sûr ?')\" >  ");
	
	print ("</td></tr></table></form>");
	print ("</div>");
	
	exit;
	}


//choix données brutes/elaborées
if (($type==artisanale)&&($type_donnees==""))
	{
	print ("<div align='center'>");
	print ("<br><br><br><br><br>");
	print ("<form name=\"form_s\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
			print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base 
	
	print ("<Font Color =\"#333366\"><b>Sélection du type de données</b></font><br><br>");
	
	print ("<br><table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
	print ("<td widht=\"100\"><input type=\"radio\" name=\"type_donnees\" value=\"brutes\">Données brutes</td>");
	print ("<td widht=\"100\"><input type=\"radio\" name=\"type_donnees\" value=\"elaboree\">Données élaborées</td>");
	print ("</tr></table>");
	
	print ("<br><br><table><tr><td colspan=2 align=center><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</td></tr></form><tr><td>");
	
	
	
	
	
	print ("<form name=\"fortion\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
			print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base 
	print ("<input type=\"submit\" name=\"\" value=\"  Retour  \">");
	print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");



exit;
	}
//$bdd="BD_Peche";
//if(($type=="artisanale")||($type=="statistique"))$bdd="jerome_manant";
//else $bdd="peche_exp_27_09";




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


print ("<div align='center'>");

if ($_POST['pays']=="")		//choix du pays non effectué
	{
	
	print ("<form name=\"form\" method=\"post\" action=\"preselection.php\">"); 
print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
	print ("<input type=hidden name=\"type\" value=\"".$type."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<br><Font Color =\"#333366\"><b>Sélection du pays</b></font><br>");
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
			print ("<br><br><table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
	
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
	$i=0;
	while (list($key, $val) = each($ST))
		{
		if($val =="Inconnu")continue;
		$val2 = str_replace("'","\'",$val);
		$nb = $nb + 1;
		if ($nb <= $colonne)
			{
			print ("<td><input type=\"Checkbox\" name=\"pays[".$i."]\" value=\"".$val2."\">".$val."</td>");
			}
		else 	{
			print ("</tr><tr><td><input type=\"Checkbox\" name=\"pays[".$i."]\" value=\"".$val2."\">".$val."</td>"); 
			$nb =1;
			}	
		$i++;
		}
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
	print ("</table><br><br>");
	print ("<table><tr  valign = \"bottom\"><td colspan =\"2\" align =\"middle\" valign = \"bottom\">");
	print ("<input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
	print ("</td></tr></form>");	
	
	
	
	//retour choix type
	print ("<tr><td>");
	
	print ("<form name=\"fortion\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=hidden name=\"login\" value=\"".$login."\">");
	print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
			print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base 
	print ("<input type=\"submit\" name=\"\" value=\"  Retour  \">");
	print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
	
	
	}
else 	
	{
	if ($_POST['systeme'] =="")		//choix du systeme non effectué
		{
		print ("<form name=\"form\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base 
		print ("<input type=hidden name=\"login\" value=\"".$login."\">");
    print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
    print ("<input type=hidden name=\"type\" value=\"".$type."\">");
    print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
		
		//print ("Choix du ou des systèmes : ");
		print ("<br><Font Color =\"#333366\"><b>Sélection du système</b></font><br>");
		
		
		$query2 = "select distinct RP.nom, RSy.libelle, ref_systeme_date_butoir.date_butoire 
		from ref_systeme as RSy, ref_pays as RP, ref_systeme_date_butoir, 
		ref_utilisateurs, ref_autorisation_exploitation 
		where RSy.ref_pays_id = RP.id 
		and ref_utilisateurs.login = '".$login."' 
		and ref_utilisateurs.password = '".$passe."' 
		and ref_systeme_date_butoir.systeme = RSy.libelle 
		and ref_autorisation_exploitation.login=ref_utilisateurs.login 
		and ref_autorisation_exploitation.pointeur=ref_systeme_date_butoir.id 
		and ref_systeme_date_butoir.date_butoire != '1900-01-01' ";
		
		
		
		
		$query2 .= "and (";

		
		
		reset ($_POST['pays']);
		while (list($key, $val) = each($_POST['pays']))
			{	
			$query2 .= "(RP.nom ='".$val."') or";
			}
		$query2 = substr($query2, 0, -2); 		//on enleve le dernier or
		$query2 .= ")";
		if ($type=="scientifique"){$query2 .= "and ref_systeme_date_butoir.type_echant=1 ";}
		else if ($type=="artisanale"){$query2 .= "and ref_systeme_date_butoir.type_echant=2 ";}
		else if ($type=="statistique"){$query2 .= "and ref_systeme_date_butoir.type_echant=3 ";}
		
		//print ("<br>".$type." , ".$query2);///////////////////////////////////////////
	
		print ("<br>Pays : ");
		$i=0;
		reset ($_POST['pays']);
		$ligne_a_afficher="";
		while (list($key, $val) = each($_POST['pays']))
			{
			$val2 = str_replace("\'","'",$val);
			$ligne_a_afficher .= "<Font Color =\"#663399\">".$val2."</Font>, ";
			$i++;
			}
		$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
		print($ligne_a_afficher);
	
	
		
		$result2 = pg_query($connection, $query2);
		
		
		$j=0;		
		while($row2 = pg_fetch_row($result2))
			{
			$ST2[$j][0] = $row2[1];//pays
			$ST2[$j][1] = $row2[0];//systeme
			$ST2[$j][2] = $row2[2];//date butoire
			$j++;
			}
		if (isset($ST2))
			{
			$nb =0;
			$n = count($ST2);
			$colonne = ceil($n/5);	//affichage de 5 par colonne
			print ("<br><br><table>");
			
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
			print ("</tr><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr>");
			
			
			
			print ("</table><table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
			reset($ST2);
			while (list($key2, $val2) = each($ST2))
				{
				//print ("<br>!!!".$val2[0] .$val2[1]);
				$nb = $nb + 1;
				if ($nb <= $colonne)
					{
					print ("<td><input type=\"Checkbox\" name=\"systeme[".$i."]\" value=\"".$val2[0]."\">".$val2[1].": ".$val2[0]. " (jusqu'au ".$val2[2].")</td>");
					}
				else 	{
					print ("</tr><tr><td><input type=\"Checkbox\" name=\"systeme[".$i."]\" value=\"".$val2[0]."\">".$val2[1].": ".$val2[0]. " (jusqu'au ".$val2[2].")</td>");
					$nb =1;
					}
				$i++;
				}
			
			print ("</tr></table>");
			
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
			
		else 	{
			print("<br><br><Font Color =\"#333366\">Aucun système renseigné ou pas d'autorisation suffisante</font><br>");
			}
		print ("<br><br><table><tr><td colspan=2 align=middle><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
		print ("</td></tr></form>");
		
		//bouton retour
		print ("<tr><td>");
		print ("<form name=\"form2\" method=\"post\" action=\"preselection.php\">");
		print ("<input type=hidden name=\"login\" value=\"".$login."\">");
    print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
    print ("<input type=hidden name=\"type\" value=\"".$type."\">");
    print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
		print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
		print ("</form></td><td>");
		print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
		
		
		}//fin du if($systeme...
	
	else	//pays et systeme renseigné -> suite selection annee d'etude 
		{
		print ("<form name=\"form\" method=\"post\" action=\"preselection.php\">");
		print ("<input type=hidden name=\"login\" value=\"".$login."\">");
    print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
    print ("<input type=hidden name=\"type\" value=\"".$type."\">");
    print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base 
		//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
		//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
		
		print ("<br><Font Color =\"#333366\"><b>Sélection de la période d'étude</b></font><br><br>");
		
		print ("Pays : ");
		$i=0;
		$ligne_a_afficher="";
		reset ($_POST['pays']);
		while (list($key, $val) = each($_POST['pays']))
			{
			//$ST2[$j][0]
			
			$val2 = str_replace("\'","'",$val);
			$ligne_a_afficher.="<Font Color =\"#663399\"><input type=hidden name=\"pays[".$i."]\" value=\"".$val."\"> ".$val2."</Font>, ";
			$i++;
			}
		$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
		print($ligne_a_afficher);
		
		print ("<br>Système : ");
		$i=0;
		$ligne_a_afficher="";
		reset ($_POST['systeme']);
		while (list($key, $val) = each($_POST['systeme']))
			{
			$ligne_a_afficher.="<Font Color =\"#663399\"><input type=hidden name=\"systeme[".$i."]\" value=\"".$val."\"> ".$val."</Font>, ";
			$i++;
			}
		$ligne_a_afficher = substr($ligne_a_afficher, 0, -2);
		print($ligne_a_afficher);
		//print ("Période : ");
		//print ("Pays : <Font Color =\"#663399\">".$pays."</Font> , système : <Font Color =\"#663399\">".$systeme."</Font>");
		
		
		if (($annee_deb =="")||($annee_fin ==""))		//choix de l'année non effectuée
			{
			print ("<br><br><br><table><tr><td>Date de début :  01/01/</td><td>");
			print ("<input type=text size=\"3\" name=\"annee_deb\"></td></tr><tr><td align=\"right\">");
			print ("Date de fin :  31/12/</td><td>");
			print ("<input type=text size=\"3\" name=\"annee_fin\"></td></tr></table>");
			
			print ("<br><br><table><tr><td colspan=2 align=middle><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
			print ("</td></tr></form>");
			
			print ("<tr><td>");
			print ("<form name=\"form2\" method=\"post\" action=\"preselection.php\">");
			print ("<input type=hidden name=\"login\" value=\"".$login."\">");
			print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
			print ("<input type=hidden name=\"type\" value=\"".$type."\">");
			print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
			print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
			print ("</form></td><td>");
			print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
			
		
			
			
			
			
			
			}
		
		else
			{
			//si annee n'est pas au bon format (19xx ou 20xx)
			if ( !ereg("(^(19|20)[0-9]{2}$)",$annee_deb) )
				{
				print ("<br><br><br>L'année ".$annee_deb." n'est pas valide<br><br>");
				print ("<form name=\"form2\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
				print ("<input type=hidden name=\"login\" value=\"".$login."\">");
				print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
				print ("<input type=hidden name=\"type\" value=\"".$type."\">");
				print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
				print ("<input type=\"submit\" name=\"choix\" value=\"    Retour    \">");
				print ("</form>");
				}//fin du if ereg
			else if ( !ereg("(^(19|20)[0-9]{2}$)",$annee_fin) )
				{
				print ("<br><br><br>L'année ".$annee_fin." n'est pas valide<br><br>");
				print ("<form name=\"form2\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
				print ("<input type=hidden name=\"login\" value=\"".$login."\">");
				print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
				print ("<input type=hidden name=\"type\" value=\"".$type."\">");
				print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
				print ("<input type=\"submit\" name=\"choix\" value=\"    Retour    \">");
				print ("</form>");
				}//fin du if ereg
			else if ($annee_fin<$annee_deb)
				{
				print ("<br><br><br>L'année de fin doit être postérieure à l'année de début<br><br>");
				print ("<form name=\"form2\" method=\"post\" action=\"preselection.php\">");
	print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
				print ("<input type=hidden name=\"login\" value=\"".$login."\">");
				print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
				print ("<input type=hidden name=\"type\" value=\"".$type."\">");
				print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
				print ("<input type=\"submit\" name=\"choix\" value=\"    Retour    \">");
				print ("</form>");
				}
			
			
			else 
				{
				print ("</form>");
				
				print ("<br>La période d'étude souhaitée est comprise entre le 01/01/".$annee_deb." et le 31/12/".$annee_fin.".<br>");
				
				
				
			
					
				
				
				
				//if ($verif==1)print("<br>Pour les systèmes sélectionnés, vous n'avez accès qu'aux données antérieures à ".$date_min.".<br> Vous devez indiquer cette année pour la date de fin.");
				
				
				
					print ("<form name=\"form_selction\" method=\"post\" action=\"selection.php\">");
					print ("<input type=hidden name=\"login\" value=\"".$login."\">");
					print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
					print ("<input type=hidden name=\"type\" value=\"".$type."\">");
					print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
		print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base 
					//print ("<input type=hidden name=\"pays\" value=\"".$pays."\">");
					//print ("<input type=hidden name=\"systeme\" value=\"".$systeme."\">");
					print ("<input type=hidden name=\"annee_deb\" value=\"".$annee_deb."\">");
					print ("<input type=hidden name=\"annee_fin\" value=\"".$annee_fin."\">");
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
					
					print ("<br><br><table><tr><td colspan=2 align=middle><input type=\"submit\" name=\"choix\" value=\"    Valider    \">");
					print ("</td></tr></form>");
					
				
				
				
					print ("<tr><td>");
				print ("<form name=\"form2\" method=\"post\" action=\"preselection.php\">");
				print ("<input type=hidden name=\"login\" value=\"".$login."\">");
				print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
				print ("<input type=hidden name=\"type\" value=\"".$type."\">");
				print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=\"hidden\" name=\"base\" value=\"".$bdd."\">");	//à enlever car qu'1 base future
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
				print ("<input type=hidden name=\"annee\" value=\"\">");
				print ("<input type=\"submit\" name=\"choix\" value=\"  Retour  \">");
				print ("</form>");
				print ("</form></td><td>");
	print ("<form name=\"fortion2\" method=\"post\" action=\"accueil.html\">");
	print ("<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form></td></tr></table>");
				

				

//pg_close();
				}
			}
		}
	}
//pg_freeresult();
pg_close();

?>

</div>

</body>
</HTML>