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
/*$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
$host= "vmppeao.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
//$bdd = "BD2_Peche";
*/



//$bdd = $_POST['base'];
//print("travail sur la base : ".$bdd);
$choix = $_POST['choix'];


print("<div align='center'>");
print("<Font Color =\"#333366\">");
print("<b>Extraction de données de statistiques de pêche</b><br>");
print("</div></Font>");


//pour vérifier les variables passées en post:
/*while (list($key, $val) = each($_POST))
	{
	if ($key == 'secteur')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'agglo')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'periode')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'engin')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'qualite')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'ecologique')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'trophique')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'espece')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'voir')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'pays')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'systeme')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else print("<br>!!!".$key." , ".$val);
	}*/

$requete_faite = $_POST['requete_faite'];
$selection_faite = $_POST['selection_faite'];
$colonnes_faites = $_POST['colonnes_faites'];

if(isset($_POST['case1']))
	{
	if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	print("<div align='center'>");
	print("<br><b>Tableau Cap_tot</b><br><br>");
	
	$query_globale = "";
	/*$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	*/
	
	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur
	, art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_stat_totale 
	where ref_pays.id=ref_systeme.ref_pays_id 
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_stat_totale.art_agglomeration_id ";

	
	$nb_campagne = count ($_POST['agglo']);
	//print ("!!!!!!".$nb_secteur);
	reset($_POST['agglo']);
	if ($nb_secteur == 1)$query_globale .= "and art_agglomeration.id = ".$_POST['agglo'][0]." ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['agglo']))
			{
			$query_globale .= "(art_agglomeration.id = ".$val.") or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));

	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_stat_totale.annee =".$key." ";
				
				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_stat_totale.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);


	////////////////////////////////////////////////////////////////
	//       ecriture des resultats dans un fichier text          //
	////////////////////////////////////////////////////////////////
	$file="temp_selection_stat_cap_tot.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t ref_pays_id\t syst_surf\t id_secteur\t sect\t sect_lib\t sect_surf\t ref_systeme_id\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t agglo_memo\t type_agglo\t type_agglo_lib\t stat_tot_id\t annee\t mois\t nbre_obs\t obs_min\t obs_max\t pue_ecart_type\t pue\t fpe\t fm\t cap\t art_agglo_id\t nb_unite_recencee\t nb_jour_activite\n";
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);

	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $k."\t";
		
		//si $row[0]different, numero +1
		for ($i=0; $i<34; $i++)	//il y a 35 champs dans la requete
			{
			$contenu .= trim($row[$i])."\t";
			}
		//$contenu .= $row[0]."\t".$row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\t".$row[5]."\t";
		$contenu = substr($contenu, 0, -1);
		$contenu .= "\n";
		fputs($fpm,$contenu);
		$k++;
		}
	fclose($fpm);

	////////////////////////////////
	print ("<div align='center'>");
	print ("La sélection porte sur ".($k-1)." lignes");//car 1ere ligne est un intitulé
	
	/*pg_free_result();
	pg_close();*/
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte
	///////////////////////////////////////////////////////////
	

	print ("<br><br><Font Color =\"#333366\">");
	print ("Critère de selection :");
	
	print ("<form name=\"form\" method=\"post\" action=\"stat_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	print ("<input type=hidden name=\"case1\" value=\"cap_tot\">");
	
	?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('voir') != -1)
		form.elements[i].checked = booleen;
		}
	</script>
		<?php 
		print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
	
	
	////////////table pays et systeme
	print ("<table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?><div onClick="document.getElementById('_pays').style.display = 'block';"><b>Pays</b>
</div> 


<div id="_pays" style="display:none">
<?php    print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");//nom pays
	print ("<input type=hidden name=\"voir[2]\" value=\"2\">");
print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_pays').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_syst').style.display = 'block';"><b>Système</b>
</div>  

<div id="vue_syst" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le systeme
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");
	print ("<input type=hidden name=\"voir[4]\" value=\"4\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_syst').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
		////////////table secteur et agglomération
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top  align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_sect').style.display = 'block';"><b>Secteur</b>
</div>

<div id="_sect" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour le secteur
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect</td>");
	print ("<input type=hidden name=\"voir[5]\" value=\"8\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect_lib</td>");
	print ("<input type=hidden name=\"voir[6]\" value=\"9\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_sect').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_agglo').style.display = 'block';"><b>Agglomération</b>
</div>

<div id="vue_agglo" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour les agglomerations
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"12\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>nom</td>");
	print ("<input type=hidden name=\"voir[8]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"20\" ></td><td>type</td>");
	
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
	//pour les statistiques totales
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('_stat').style.display = 'block';"><b>Statistiques totales</b>
	</div>
	
	<div id="_stat" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>année</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"22\">");
	print ("<td WIDTH=30>x</td><td>mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"23\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[12]\" value=\"21\" ></td><td>id</td>");
	print ("<td WIDTH=30>x</td><td>pue</td>");
	print ("<input type=hidden name=\"voir[13]\" value=\"28\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"27\" ></td><td>pue_ecart_type</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"25\" ></td><td>obs_min</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"26\" ></td><td>obs_max</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"24\" ></td><td>nbre_obs</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"29\" ></td><td>Fpe</td>");
	print ("<td WIDTH=30>x</td><td>Fm</td>");
	print ("<input type=hidden name=\"voir[19]\" value=\"30\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>Cap</td>");
	print ("<input type=hidden name=\"voir[20]\" value=\"31\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[21]\" value=\"33\" ></td><td>nbre_unite_recensées</td>");
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('_stat').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");

	
	print ("</div></tr></table>");
	
	
	
	
	
	
	
	
	
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
		$i=0;
		while (list($key, $val) = each($val_P))
			{
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
	if(isset($_POST['case1']))print ("<input type=\"hidden\" name=\"case1\" value=\"case1\">");
	if(isset($_POST['case2']))print ("<input type=\"hidden\" name=\"case2\" value=\"case2\">");
	if(isset($_POST['case3']))print ("<input type=\"hidden\" name=\"case3\" value=\"case3\">");
	if(isset($_POST['case4']))print ("<input type=\"hidden\" name=\"case4\" value=\"case4\">");
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	
	
	
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	
	print("</div>");
	}//fin de if ($requete_faite != 1)
	
else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="temp_selection_stat_cap_tot.txt";
	$fpm = fopen($file, "r");

	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	while ($ligne=fgets($fpm,10000))
		{
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm);
	
	//ouverture fichier pour ecriture en local
	$file="temp_selection_stat_cap_tot.txt";
	$fpm = fopen($file, "w+");
	reset($tab_ligne);
	$compt = 0;
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		reset ($ligne_contient);
		reset ($_POST['voir']);
		
		//seules les valeurs des colonnes contenu dans $_POST['voir'] doivent rester
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//key_contient = numero colonne, val_contient=valeur colonne
			if (!in_array ($key_contient, $_POST['voir']))
				{
				//print ("<br>!!!enleve colonne :".$key_contient.", soit : ".$ligne_contient[$key_contient]);
				unset ($ligne_contient[$key_contient]);
				}
				
			}
		
		//on ecrit la nouvelle ligne
		reset($ligne_contient);
		$nouveau = "";
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print("<br>".$key_contient." , ".$val_contient);
			$nouveau .= $val_contient . "\t";
			}
		$nouveau .= "\n";
		//print("<br>!!!!".$nouveau);
		fputs($fpm,$nouveau);
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './temp_selection_stat_cap_tot.txt';

// ouverture du fichier à compresser
if($fp = fopen($filename, "rb"))
	{
	// lecture du contenu
	$size1 = filesize($filename);
	$data = fread($fp, $size1);
	// fermeture
	fclose($fp);

	// compression des données
	$gzdata = gzencode($data, 9);
	
	// ouverture et création du fichier compressé
	if($fp = fopen($filename.'.gz', 'wb'))
		{
		// écriture des données compressées
		fwrite($fp, $gzdata);
		// fermeture
		fclose($fp);
		} else {echo "Impossible d'ouvrir $filename.gz en écriture.";}
	} else {echo "Impossible d'ouvrir $filename en lecture.";}

//affichage du lien
print ("<div align='center'><br><br><br>Le fichier texte contenant les résultats des sélections a été créé.");
print ("<br><br>Cliquez sur le lien pour l'enregistrer sur votre ordinateur.");

// Ajout variable chemin
$pathtar = "http://".$hostname."/extraction"; 
print ("<br><br><a href=\"".$pathtar."/temp_selection_stat_cap_tot.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

//print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/temp_selection_stat_cap_tot.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");
print("<div align='center'><br><br>");
print ("<form name=\"form_tot\" method=\"post\" action=\"stat_filieres.php\">");
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

		$i=0;
		while (list($key, $val) = each($val_P))
			{
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
	if(isset($_POST['case2']))print ("<input type=\"hidden\" name=\"case2\" value=\"case2\">");
	if(isset($_POST['case3']))print ("<input type=\"hidden\" name=\"case3\" value=\"case3\">");
	if(isset($_POST['case4']))print ("<input type=\"hidden\" name=\"case4\" value=\"case4\">");
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	print ("<br><input type=\"submit\" name=\"\" value=\"    suite    \">");

print("</div>");

	
}//fin du else
	
	exit;
	
	}//fin du if(isset($_POST['case1']))
	











//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
///////                    traitement de cap sp                        ///////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

if(isset($_POST['case2']))
	{
/*	$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
$host= "vmppeao.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
$bdd = "jerome_manant"; */

	
	
	if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	print("<div align='center'>");
	print("<br><b>Tableau Cap_sp</b><br><br>");
	
	$query_globale = "";
	/*$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	*/
	
	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur
	, art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_stat_totale 
	, art_stat_sp 
	, ref_espece 
	left join ref_categorie_ecologique on ref_espece.ref_categorie_ecologique_id=ref_categorie_ecologique.id 
	left join ref_categorie_trophique on ref_espece.ref_categorie_trophique_id=ref_categorie_trophique.id 
	left join (ref_famille left join ref_ordre on ref_famille.ref_ordre_id=ref_ordre.id) on ref_espece.ref_famille_id=ref_famille.id 
	where ref_pays.id=ref_systeme.ref_pays_id 
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_stat_totale.art_agglomeration_id 
	and art_stat_totale.id=art_stat_sp.art_stat_totale_id 
	and art_stat_sp.ref_espece_id=ref_espece.id ";

	
	$nb_campagne = count ($_POST['agglo']);
	reset($_POST['agglo']);
	if ($nb_secteur == 1)$query_globale .= "and art_agglomeration.id = ".$_POST['agglo'][0]." ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['agglo']))
			{
			$query_globale .= "(art_agglomeration.id = ".$val.") or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));
	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_stat_totale.annee =".$key." ";
				

				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_stat_totale.mois = '".$val2."') or ";
					
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
	
	$nb_esp = count ($_POST['espece']);
	reset($_POST['espece']);
	if ($nb_esp == 1)$query_globale .= "and ref_espece.id = '".$_POST['espece'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['espece']))
			{
			$query_globale .= "(ref_espece.id = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);

	////////////////////////////////////////////////////////////////
	//       ecriture des resultats dans un fichier text          //
	////////////////////////////////////////////////////////////////
	$file="temp_selection_stat_cap_sp.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t ref_pays_id\t syst_surf\t id_secteur\t sect\t sect_lib\t sect_surf\t ref_systeme_id\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t agglo_memo\t id_type_agglo\t type_agglo\t stat_tot_id\t annee\t mois\t nbre_obs\t obs_min\t obs_max\t pue_ecart_type\t pue\t fpe\t fm\t cap\t art_agglo_id\t nb_unite_recencee\t nb_jour_activite\t nb_jour_enq_deb\t stat_sp_id\t obs_sp_min\t obs_sp_max\t pue_sp_ecart_type\t pue_sp	cap_sp\t ref_espece_id\t art_stat_totale_id\t nbre_enquete_sp\t esp\t esp_lib\t info\t ref_famille_id\t ref_categorie_ecologique_id\t ref_categorie_trophique_id\t coefficient_k\t coefficient_b\t ref_origine_kb_id\t ref_espece_id\t eco\t eco_lib\t troph\t troph_lib\t famille\t famille_lib\t ref_ordre_id\t non_poisson\t ordre\t ordre_lib\n";
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);

	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $k."\t";
		
		//si $row[0]different, numero +1
		for ($i=0; $i<64; $i++)	//il y a 63 champs dans la requete
			{
			$contenu .= trim($row[$i])."\t";
			}
		//$contenu .= $row[0]."\t".$row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\t".$row[5]."\t";
		$contenu = substr($contenu, 0, -1);
		$contenu .= "\n";
		fputs($fpm,$contenu);
		$k++;
		}
	fclose($fpm);


	///////////////////////////////////////
	//print ("<div align='center'>");
	print ("La sélection porte sur ".($k-1)." lignes");//car 1ere ligne est un intitulé
	
	/*pg_free_result();
	pg_close();
	*/
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte///
	///////////////////////////////////////////////////
	

	print ("<br><br><Font Color =\"#333366\">");
	print ("Critère de selection");
	
	print ("<form name=\"form\" method=\"post\" action=\"stat_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");

	
		?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('voir') != -1)
		form.elements[i].checked = booleen;
		}
	</script>
		<?php 
		print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
	
	
	////////////table pays et systeme
	print ("<table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?><div onClick="document.getElementById('_pays').style.display = 'block';"><b>Pays</b>
</div> 


<div id="_pays" style="display:none">
<?php    print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");//nom pays
	print ("<input type=hidden name=\"voir[2]\" value=\"2\">");
print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_pays').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_syst').style.display = 'block';"><b>Système</b>
</div>  

<div id="vue_syst" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le systeme
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");
	print ("<input type=hidden name=\"voir[4]\" value=\"4\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_syst').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
		////////////table secteur et agglomération
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top  align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_sect').style.display = 'block';"><b>Secteur</b>
</div>

<div id="_sect" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour le secteur
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect</td>");
	print ("<input type=hidden name=\"voir[5]\" value=\"8\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect_lib</td>");
	print ("<input type=hidden name=\"voir[6]\" value=\"9\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_sect').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_agglo').style.display = 'block';"><b>Agglomération</b>
</div>

<div id="vue_agglo" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour les agglomerations
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"12\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>nom</td>");
	print ("<input type=hidden name=\"voir[8]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"20\" ></td><td>type</td>");
	
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
	//pour les statistiques especes
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('_stat').style.display = 'block';"><b>Statistiques par espèce</b>
	</div>
	
	<div id="_stat" style="display:none">
<?php  

	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>année</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"22\">");
	print ("<td WIDTH=30>x</td><td>mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"23\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[12]\" value=\"36\" ></td><td>id</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pue_sp</td>");
	print ("<input type=hidden name=\"voir[13]\" value=\"40\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"39\" ></td><td>pue_sp_ecart_type</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"37\" ></td><td>obs_sp_min</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"38\" ></td><td>obs_sp_max</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"31\" ></td><td>cap_tot</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"30\" ></td><td>Fm_tot</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"28\" ></td><td>pue_tot</td>");
	print ("<td WIDTH=30>x</td><td>pue_sp</td>");
	print ("<input type=hidden name=\"voir[20]\" value=\"40\">");
	print ("<td WIDTH=30>x</td><td>cap_sp</td>");
	print ("<input type=hidden name=\"voir[21]\" value=\"41\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>espece</td>");
	print ("<input type=hidden name=\"voir[22]\" value=\"44\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"46\" ></td><td>espece_libelle</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[24]\" value=\"44\" ></td><td>nbre_enquete_sp</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"60\" ></td><td>famille</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[26]\" value=\"64\" ></td><td>ordre</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[27]\" value=\"56\" ></td><td>cat_ecol</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[28]\" value=\"58\" ></td><td>cat_troph</td>");
		print ("</tr><tr ALIGN=center><td colspan=6 onclick=\"document.getElementById('_stat').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");

	
	print ("</div></tr></table>");
	
	
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
	if(isset($_POST['case2']))print ("<input type=\"hidden\" name=\"case2\" value=\"case2\">");
	if(isset($_POST['case3']))print ("<input type=\"hidden\" name=\"case3\" value=\"case3\">");
	if(isset($_POST['case4']))print ("<input type=\"hidden\" name=\"case4\" value=\"case4\">");
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	
	
	
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	
	print("</div>");
	}//fin de if ($requete_faite != 1)
	
else
	{//print ("!!!!!!!!!!!!!!!!!!!!!");
	//////on enlève des colonnes en trop du fichier temp
	$file="temp_selection_stat_cap_sp.txt";
	$fpm = fopen($file, "r");

	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	while ($ligne=fgets($fpm,10000))
		{
		//print("<br>!!!!".$ligne);		//ok
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm);
	
	//ouverture fichier pour ecriture en local
	$file="temp_selection_stat_cap_sp.txt";
	$fpm = fopen($file, "w+");
	reset($tab_ligne);
	$compt = 0;
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		reset ($ligne_contient);
		reset ($_POST['voir']);
		
		//seules les valeurs des colonnes contenu dans $_POST['voir'] doivent rester
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//key_contient = numero colonne, val_contient=valeur colonne
			if (!in_array ($key_contient, $_POST['voir']))
				{
				//print ("<br>!!!enleve colonne :".$key_contient.", soit : ".$ligne_contient[$key_contient]);
				unset ($ligne_contient[$key_contient]);
				}
				
			}
		
		//on ecrit la nouvelle ligne
		reset($ligne_contient);
		$nouveau = "";
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print("<br>".$key_contient." , ".$val_contient);
			$nouveau .= $val_contient . "\t";
			}
		$nouveau .= "\n";
		//print("<br>!!!!".$nouveau);
		fputs($fpm,$nouveau);
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './temp_selection_stat_cap_sp.txt';

// ouverture du fichier à compresser
if($fp = fopen($filename, "rb"))
	{
	// lecture du contenu
	$size1 = filesize($filename);
	$data = fread($fp, $size1);
	// fermeture
	fclose($fp);

	// compression des données
	$gzdata = gzencode($data, 9);
	
	// ouverture et création du fichier compressé
	if($fp = fopen($filename.'.gz', 'wb'))
		{
		// écriture des données compressées
		fwrite($fp, $gzdata);
		// fermeture
		fclose($fp);
		} else {echo "Impossible d'ouvrir $filename.gz en écriture.";}
	} else {echo "Impossible d'ouvrir $filename en lecture.";}

//affichage du lien
print ("<div align='center'><br><br><br>Le fichier texte contenant les résultats des sélections a été créé.");
print ("<br><br>Cliquez sur le lien pour l'enregistrer sur votre ordinateur.");

// Ajout variable chemin
$pathtar = "http://".$hostname."/extraction"; 
print ("<br><br><a href=\"".$pathtar."/temp_selection_stat_cap_sp.txt.gz\"<b>Enregistrement du fichier texte</b></a>");



//print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/temp_selection_stat_cap_sp.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");
print("<div align='center'><br><br>");
print ("<form name=\"form_tot\" method=\"post\" action=\"stat_filieres.php\">");
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
		$i=0;
		while (list($key, $val) = each($val_P))
			{
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
	if(isset($_POST['case3']))print ("<input type=\"hidden\" name=\"case3\" value=\"case3\">");
	if(isset($_POST['case4']))print ("<input type=\"hidden\" name=\"case4\" value=\"case4\">");
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	print ("<br><input type=\"submit\" name=\"\" value=\"    suite    \">");

print("</div>");

	
}//fin du else
	
	exit;
	
	}//fin du if(isset($_POST['case2']))



//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
///////                    traitement de DFT sp                        ///////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////


if(isset($_POST['case3']))
	{
	/*$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
$host= "vmppeao.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
$bdd = "jerome_manant";  */

	
	
	if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	print("<div align='center'>");
	print("<br><b>Tableau DFT_sp</b><br><br>");

	
	$query_globale = "";
	/*$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	*/
	
	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur
	, art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_stat_totale 
	, art_stat_sp 
	, ref_espece 
	left join ref_categorie_ecologique on ref_espece.ref_categorie_ecologique_id=ref_categorie_ecologique.id 
	left join ref_categorie_trophique on ref_espece.ref_categorie_trophique_id=ref_categorie_trophique.id 
	left join (ref_famille left join ref_ordre on ref_famille.ref_ordre_id=ref_ordre.id) on ref_espece.ref_famille_id=ref_famille.id 
	, art_taille_sp 
	where ref_pays.id=ref_systeme.ref_pays_id 
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_stat_totale.art_agglomeration_id 
	and art_stat_totale.id=art_stat_sp.art_stat_totale_id 
	and art_stat_sp.ref_espece_id=ref_espece.id 
	and art_taille_sp.art_stat_sp_id=art_stat_sp.id ";

	
	$nb_campagne = count ($_POST['agglo']);
	reset($_POST['agglo']);
	if ($nb_secteur == 1)$query_globale .= "and art_agglomeration.id = ".$_POST['agglo'][0]." ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['agglo']))
			{
			$query_globale .= "(art_agglomeration.id = ".$val.") or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));

	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_stat_totale.annee =".$key." ";
				

				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_stat_totale.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
	
	$nb_esp = count ($_POST['espece']);
	reset($_POST['espece']);
	if ($nb_esp == 1)$query_globale .= "and ref_espece.id = '".$_POST['espece'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['espece']))
			{
			$query_globale .= "(ref_espece.id = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);





	////////////////////////////////////////////////////////////////
	//       ecriture des resultats dans un fichier texte         //
	////////////////////////////////////////////////////////////////
	$file="temp_selection_stat_dft_sp.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	
	$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t ref_pays_id\t syst_surf\t id_secteur\t sect\t sect_lib\t sect_surf\t ref_systeme_id\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t agglo_memo\t id_type_agglo\t type_agglo\t stat_tot_id\t annee\t mois\t nbre_obs\t obs_min\t obs_max\t pue_ecart_type\t pue\t fpe\t fm\t cap\t art_agglo_id\t nb_unite_recencee\t nb_jour_activite\t nb_jour_enq_deb\t stat_sp_id\t obs_sp_min\t obs_sp_max\t pue_sp_ecart_type\t pue_sp	cap_sp\t ref_espece_id\t art_stat_totale_id\t nbre_enquete_sp\t esp\t esp_lib\t info\t ref_famille_id\t ref_categorie_ecologique_id\t ref_categorie_trophique_id\t coefficient_k\t coefficient_b\t ref_origine_kb_id\t ref_espece_id\t eco\t eco_lib\t troph\t troph_lib\t famille\t famille_lib\t ref_ordre_id\t non_poisson\t ordre\t ordre_lib\t id\t li\t xi\t art_stat_sp_id\n";
	//print ("<br>".$intitule);
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);

	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $k."\t";
		
		//si $row[0]different, numero +1
		for ($i=0; $i<68; $i++)	//il y a 67 champs dans la requete
			{
			$contenu .= trim($row[$i])."\t";
			}
		//$contenu .= $row[0]."\t".$row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\t".$row[5]."\t";
		$contenu = substr($contenu, 0, -1);
		$contenu .= "\n";
		fputs($fpm,$contenu);
		$k++;
		}
	fclose($fpm);

	///////////////////////////////////////
	//print ("<div align='center'>");
	print ("La sélection porte sur ".($k-1)." lignes");//car 1ere ligne est un intitulé
	
	/*pg_free_result();
	pg_close();*/
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte///
	///////////////////////////////////////////////////
	

	print ("<br><br><Font Color =\"#333366\">");
	print ("Critère de selection");
	
	print ("<form name=\"form\" method=\"post\" action=\"stat_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");

	?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('voir') != -1)
		form.elements[i].checked = booleen;
		}
	</script>
		<?php 
		print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
	
	
	////////////table pays et systeme
	print ("<table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?><div onClick="document.getElementById('_pays').style.display = 'block';"><b>Pays</b>
</div> 


<div id="_pays" style="display:none">
<?php    print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");//nom pays
	print ("<input type=hidden name=\"voir[2]\" value=\"2\">");
print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_pays').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_syst').style.display = 'block';"><b>Système</b>
</div>  

<div id="vue_syst" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le systeme
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");
	print ("<input type=hidden name=\"voir[4]\" value=\"4\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_syst').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
		////////////table secteur et agglomération
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top  align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_sect').style.display = 'block';"><b>Secteur</b>
</div>

<div id="_sect" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour le secteur
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect</td>");
	print ("<input type=hidden name=\"voir[5]\" value=\"8\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect_lib</td>");
	print ("<input type=hidden name=\"voir[6]\" value=\"9\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_sect').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_agglo').style.display = 'block';"><b>Agglomération</b>
</div>

<div id="vue_agglo" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour les agglomerations
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"12\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>nom</td>");
	print ("<input type=hidden name=\"voir[8]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"20\" ></td><td>type</td>");
	
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
	//pour les statistiques totales
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"250\">");
	
	?>
	<div onClick="document.getElementById('_stat').style.display = 'block';"><b>Statistiques et structures de taille</b>
	</div>
	
	<div id="_stat" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>année</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"22\">");
	print ("<td WIDTH=30>x</td><td>mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"23\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[12]\" value=\"36\" ></td><td>id</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pue_sp</td>");
	print ("<input type=hidden name=\"voir[13]\" value=\"40\">");
	print ("<td WIDTH=30>x</td><td>li</td>");
	print ("<input type=hidden name=\"voir[14]\" value=\"66\">");
	print ("<td WIDTH=30>x</td><td>xi</td>");
	print ("<input type=hidden name=\"voir[15]\" value=\"67\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"65\" ></td><td>id taille</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"31\" ></td><td>cap_tot</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"30\" ></td><td>Fm_tot</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"28\" ></td><td>pue_tot</td>");
	print ("<td WIDTH=30>x</td><td>pue_sp</td>");
	print ("<input type=hidden name=\"voir[20]\" value=\"40\">");
	print ("<td WIDTH=30>x</td><td>cap_sp</td>");
	print ("<input type=hidden name=\"voir[21]\" value=\"41\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>espece</td>");
	print ("<input type=hidden name=\"voir[22]\" value=\"45\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"46\" ></td><td>espece_libelle</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[24]\" value=\"44\" ></td><td>nbre_enquete_sp</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"60\" ></td><td>famille</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[26]\" value=\"64\" ></td><td>ordre</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[27]\" value=\"56\" ></td><td>cat_ecol    </td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[28]\" value=\"58\" ></td><td>cat_troph</td>");
print ("</tr><tr ALIGN=center><td colspan=6 onclick=\"document.getElementById('_stat').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");

	
	print ("</div></tr></table>");
	
	
	
	
	
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
		$i=0;
		while (list($key, $val) = each($val_P))
			{
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

	if(isset($_POST['case3']))print ("<input type=\"hidden\" name=\"case3\" value=\"case3\">");
	if(isset($_POST['case4']))print ("<input type=\"hidden\" name=\"case4\" value=\"case4\">");
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	
	
	
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	
	print("</div>");
	}//fin de if ($requete_faite != 1)
	
else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="temp_selection_stat_dft_sp.txt";
	$fpm = fopen($file, "r");

	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	while ($ligne=fgets($fpm,10000))
		{
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm);
	
	//ouverture fichier pour ecriture en local
	$file="temp_selection_stat_dft_sp.txt";
	$fpm = fopen($file, "w+");
	reset($tab_ligne);
	$compt = 0;
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		reset ($ligne_contient);
		reset ($_POST['voir']);
		
		//seules les valeurs des colonnes contenu dans $_POST['voir'] doivent rester
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print ("<br><br>***".$key_contient."    ,    ".$val_contient);
			//key_contient = numero colonne, val_contient=valeur colonne
			if (!in_array ($key_contient, $_POST['voir']))
				{
				//print ("<br>!!!enleve colonne :".$key_contient.", soit : ".$ligne_contient[$key_contient]);
				unset ($ligne_contient[$key_contient]);
				}
				
			}
		
		//on ecrit la nouvelle ligne
		reset($ligne_contient);
		$nouveau = "";
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print("<br>".$key_contient." , ".$val_contient);
			$nouveau .= $val_contient . "\t";
			}
		$nouveau .= "\n";
		//print("<br>!!!!".$nouveau);
		fputs($fpm,$nouveau);
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './temp_selection_stat_dft_sp.txt';

// ouverture du fichier à compresser
if($fp = fopen($filename, "rb"))
	{
	// lecture du contenu
	$size1 = filesize($filename);
	$data = fread($fp, $size1);
	// fermeture
	fclose($fp);

	// compression des données
	$gzdata = gzencode($data, 9);
	
	// ouverture et création du fichier compressé
	if($fp = fopen($filename.'.gz', 'wb'))
		{
		// écriture des données compressées
		fwrite($fp, $gzdata);
		// fermeture
		fclose($fp);
		} else {echo "Impossible d'ouvrir $filename.gz en écriture.";}
	} else {echo "Impossible d'ouvrir $filename en lecture.";}

//affichage du lien
print ("<div align='center'><br><br><br>Le fichier texte contenant les résultats des sélections a été créé.");
print ("<br><br>Cliquez sur le lien pour l'enregistrer sur votre ordinateur.");

// Ajout variable chemin
$pathtar = "http://".$hostname."/extraction"; 
print ("<br><br><a href=\"".$pathtar."/temp_selection_stat_dft_sp.txt.gz\"<b>Enregistrement du fichier texte</b></a>");



//print ("<br><br><a href=\"http://vmppeao.mpl.ird.fr/extraction/temp_selection_globale.txt.gz\"<b>Enregistrement du fichier texte</b></a>");
//print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/temp_selection_stat_dft_sp.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");
print("<div align='center'><br><br>");
print ("<form name=\"form_tot\" method=\"post\" action=\"stat_filieres.php\">");
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
		$i=0;
		while (list($key, $val) = each($val_P))
			{
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
	if(isset($_POST['case4']))print ("<input type=\"hidden\" name=\"case4\" value=\"case4\">");
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	print ("<br><input type=\"submit\" name=\"\" value=\"    suite    \">");

print("</div>");

	
}//fin du else
	
	exit;
	
	}//fin du if(isset($_POST['case2']))




//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
///////                    traitement de cap gt                        ///////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

if(isset($_POST['case4']))
	{
/*	$user="devppeao";			// Le nom d'utilisateur 
	$passwd="2devppe!!";			// Le mot de passe 
	$host= "vmppeao.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
	$bdd = "jerome_manant";  */

	
	
	if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	print("<div align='center'>");
	//print("<br>Choix des variables du tableau Cap_gt :<br>");
	print("<br><b>Tableau Cap_gt</b><br>");
	
	$query_globale = "";
	/*$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	*/
	
	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur
	, art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_stat_totale 
	, art_stat_gt 
	left join art_grand_type_engin on art_stat_gt.art_grand_type_engin_id=art_grand_type_engin.id 
	where ref_pays.id=ref_systeme.ref_pays_id 
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_stat_totale.art_agglomeration_id 
	and art_stat_totale.id=art_stat_gt.art_stat_totale_id ";

	
	$nb_campagne = count ($_POST['agglo']);
	reset($_POST['agglo']);
	if ($nb_secteur == 1)$query_globale .= "and art_agglomeration.id = ".$_POST['agglo'][0]." ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['agglo']))
			{
			$query_globale .= "(art_agglomeration.id = ".$val.") or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));

	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_stat_totale.annee =".$key." ";
				
				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_stat_totale.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
	
	$nb_engin = count ($_POST['engin']);

	reset($_POST['engin']);
	if ($nb_esp == 1)$query_globale .= "and art_grand_type_engin.id = '".$_POST['engin'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['engin']))
			{
			$query_globale .= "(art_grand_type_engin.id = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);

	
	////////////////////////////////////////////////////////////////
	//       ecriture des resultats dans un fichier text          //
	////////////////////////////////////////////////////////////////
	$file="temp_selection_stat_cap_gt.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t ref_pays_id\t syst_surf\t id_secteur\t sect\t sect_lib\t sect_surf\t ref_systeme_id\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t agglo_memo\t id_type_agglo\t type_agglo\t stat_tot_id\t annee\t mois\t nbre_obs\t obs_min\t obs_max\t pue_ecart_type\t pue\t fpe\t fm\t cap\t art_agglo_id\t nb_unite_recencee\t nb_jour_activite\t nb_jour_deb\t stat_gt_id\t obs_gt_min\t obs_gt_max\t pue_gt_ecart_type\t pue_gt\t fpe_gt\t fm_gt\t cap_gt\t art_grand_type_engin_id\t art_stat_totale_id\t nbre_enquete_gt\t grand_type_engin\t grand_type_engin_lib\n";

	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);

	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $k."\t";
		
		//si $row[0]different, numero +1
		for ($i=0; $i<48; $i++)	//il y a 47 champs dans la requete
			{
			$contenu .= trim($row[$i])."\t";
			}
		//$contenu .= $row[0]."\t".$row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\t".$row[5]."\t";
		$contenu = substr($contenu, 0, -1);
		$contenu .= "\n";
		fputs($fpm,$contenu);
		$k++;
		}
	fclose($fpm);

	///////////////////////////////////////
	//print ("<div align='center'>");

	print ("<br>La sélection porte sur ".($k-1)." lignes");//car 1ere ligne est un intitulé
	/*pg_free_result();
	pg_close();*/
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte
	///////////////////////////////////////////////////////////
	

	print ("<br><br><Font Color =\"#333366\">");
	print ("Critère de selection :");
	
	print ("<form name=\"form\" method=\"post\" action=\"stat_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");

	?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('voir') != -1)
		form.elements[i].checked = booleen;
		}
	</script>
		<?php 
		print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
	
	
	////////////table pays et systeme
	print ("<table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?><div onClick="document.getElementById('_pays').style.display = 'block';"><b>Pays</b>
</div> 


<div id="_pays" style="display:none">
<?php    print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");//nom pays
	print ("<input type=hidden name=\"voir[2]\" value=\"2\">");
print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_pays').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_syst').style.display = 'block';"><b>Système</b>
</div>  

<div id="vue_syst" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le systeme
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");
	print ("<input type=hidden name=\"voir[4]\" value=\"4\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_syst').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
		////////////table secteur et agglomération
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top  align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_sect').style.display = 'block';"><b>Secteur</b>
</div>

<div id="_sect" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour le secteur
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect</td>");
	print ("<input type=hidden name=\"voir[5]\" value=\"8\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect_lib</td>");
	print ("<input type=hidden name=\"voir[6]\" value=\"9\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_sect').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_agglo').style.display = 'block';"><b>Agglomération</b>
</div>

<div id="vue_agglo" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour les agglomerations
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"12\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>nom</td>");
	print ("<input type=hidden name=\"voir[8]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"20\" ></td><td>type</td>");
	
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
	//pour les statistiques totales
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('_stat').style.display = 'block';"><b>Statistiques par Grand Type</b>
	</div>
	
	<div id="_stat" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"250\">");
		print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>année</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"22\">");
	print ("<td WIDTH=30>x</td><td>mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"23\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[12]\" value=\"36\" ></td><td>id</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pue_gt</td>");
	print ("<input type=hidden name=\"voir[13]\" value=\"40\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"39\" ></td><td>pue_gt_ecart_type</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"37\" ></td><td>obs_gt_min</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"38\" ></td><td>obs_gt_max</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"31\" ></td><td>cap_tot</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"30\" ></td><td>Fm_tot</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"46\" ></td><td>nbre_enquete_gt</td>");
	print ("<td WIDTH=30>x</td><td>pue_tot</td>");
	print ("<input type=hidden name=\"voir[20]\" value=\"28\">");
	print ("<td WIDTH=30>x</td><td>cap_gt</td>");
	print ("<input type=hidden name=\"voir[21]\" value=\"43\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>fm_gt</td>");
	print ("<input type=hidden name=\"voir[22]\" value=\"42\">");
	print ("<td WIDTH=30>x</td><td>fpe_gt</td>");
	print ("<input type=hidden name=\"voir[23]\" value=\"41\">");
	print ("<td WIDTH=30>x</td><td>grand_type_engin</td>");
	print ("<input type=hidden name=\"voir[24]\" value=\"44\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"46\"></td><td>grd_type_eng_lib</td>");
	
	print ("</tr><tr ALIGN=center><td colspan=6 onclick=\"document.getElementById('_stat').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");

	
	print ("</div></tr></table>");
	
	
	
	
	
	
	
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
	if(isset($_POST['case4']))print ("<input type=\"hidden\" name=\"case4\" value=\"case4\">");
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	
	
	
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	
	print("</div>");
	}//fin de if ($requete_faite != 1)
	
else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="temp_selection_stat_cap_gt.txt";
	$fpm = fopen($file, "r");

	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	while ($ligne=fgets($fpm,10000))
		{
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm);
	
	//ouverture fichier pour ecriture en local
	$file="temp_selection_stat_cap_gt.txt";
	$fpm = fopen($file, "w+");
	reset($tab_ligne);
	$compt = 0;
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		reset ($ligne_contient);
		reset ($_POST['voir']);
		
		//seules les valeurs des colonnes contenu dans $_POST['voir'] doivent rester
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print ("<br><br>***".$key_contient."    ,    ".$val_contient);
			//key_contient = numero colonne, val_contient=valeur colonne
			if (!in_array ($key_contient, $_POST['voir']))
				{
				//print ("<br>!!!enleve colonne :".$key_contient.", soit : ".$ligne_contient[$key_contient]);
				unset ($ligne_contient[$key_contient]);
				}
				
			}
		
		//on ecrit la nouvelle ligne
		reset($ligne_contient);
		$nouveau = "";
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print("<br>".$key_contient." , ".$val_contient);
			$nouveau .= $val_contient . "\t";
			}
		$nouveau .= "\n";
		//print("<br>!!!!".$nouveau);
		fputs($fpm,$nouveau);
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './temp_selection_stat_cap_gt.txt';

// ouverture du fichier à compresser
if($fp = fopen($filename, "rb"))
	{
	// lecture du contenu
	$size1 = filesize($filename);
	$data = fread($fp, $size1);
	// fermeture
	fclose($fp);

	// compression des données
	$gzdata = gzencode($data, 9);
	
	// ouverture et création du fichier compressé
	if($fp = fopen($filename.'.gz', 'wb'))
		{
		// écriture des données compressées
		fwrite($fp, $gzdata);
		// fermeture
		fclose($fp);
		} else {echo "Impossible d'ouvrir $filename.gz en écriture.";}
	} else {echo "Impossible d'ouvrir $filename en lecture.";}

//affichage du lien
print ("<div align='center'><br><br><br>Le fichier texte contenant les résultats des sélections a été créé.");
print ("<br><br>Cliquez sur le lien pour l'enregistrer sur votre ordinateur.");

// Ajout variable chemin
$pathtar = "http://".$hostname."/extraction"; 
print ("<br><br><a href=\"".$pathtar."/temp_selection_stat_cap_gt.txt.gz\"<b>Enregistrement du fichier texte</b></a>");


//print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/temp_selection_stat_cap_gt.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");
print("<div align='center'><br><br>");
print ("<form name=\"form_tot\" method=\"post\" action=\"stat_filieres.php\">");
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
		$i=0;
		while (list($key, $val) = each($val_P))
			{
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
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	print ("<br><input type=\"submit\" name=\"\" value=\"    suite    \">");

print("</div>");

	
}//fin du else
	
	exit;
	
	}//fin du if(isset($_POST['case4']))




//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
///////                    traitement de cap gt sp                     ///////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

if(isset($_POST['case5']))
	{
/*	$user="devppeao";			// Le nom d'utilisateur 
	$passwd="2devppe!!";			// Le mot de passe 
	$host= "vmppeao.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
	$bdd = "jerome_manant";   */

	
	
	if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	print("<div align='center'>");
	print("<br><b>Tableau Cap_gt_sp</b><br>");
	
	$query_globale = "";
	/*$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	*/
	
	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur
	, art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_stat_totale 
	, art_stat_gt 
	left join art_grand_type_engin on art_stat_gt.art_grand_type_engin_id=art_grand_type_engin.id 
	, art_stat_gt_sp 
	, ref_espece 
	left join ref_categorie_ecologique on ref_espece.ref_categorie_ecologique_id=ref_categorie_ecologique.id 
	left join ref_categorie_trophique on ref_espece.ref_categorie_trophique_id=ref_categorie_trophique.id 
	left join (ref_famille left join ref_ordre on ref_famille.ref_ordre_id=ref_ordre.id) on ref_espece.ref_famille_id=ref_famille.id 
	where ref_pays.id=ref_systeme.ref_pays_id 
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_stat_totale.art_agglomeration_id 
	and art_stat_totale.id=art_stat_gt.art_stat_totale_id 
	and art_stat_gt.id=art_stat_gt_sp.art_stat_gt_id 
	and art_stat_gt_sp.ref_espece_id=ref_espece.id ";

	
	$nb_campagne = count ($_POST['agglo']);
	reset($_POST['agglo']);
	if ($nb_secteur == 1)$query_globale .= "and art_agglomeration.id = ".$_POST['agglo'][0]." ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['agglo']))
			{
			$query_globale .= "(art_agglomeration.id = ".$val.") or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));

	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_stat_totale.annee =".$key." ";
				

				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_stat_totale.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
	
	$nb_engin = count ($_POST['engin']);

	reset($_POST['engin']);
	if ($nb_esp == 1)$query_globale .= "and art_grand_type_engin.id = '".$_POST['engin'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['engin']))
			{
			$query_globale .= "(art_grand_type_engin.id = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	$nb_espece = count ($_POST['espece']);

	reset($_POST['espece']);
	if ($nb_esp == 1)$query_globale .= "and ref_espece.id = '".$_POST['espece'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['espece']))
			{
			$query_globale .= "(ref_espece.id = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);



	////////////////////////////////////////////////////////////////
	//       ecriture des resultats dans un fichier text          //
	////////////////////////////////////////////////////////////////
	$file="temp_selection_stat_cap_gt_sp.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t ref_pays_id\t syst_surf\t id_secteur\t sect\t sect_lib\t sect_surf\t ref_systeme_id\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t agglo_memo\t id_type_agglo\t type_agglo\t stat_tot_id\t annee\t mois\t nbre_obs\t obs_min\t obs_max\t pue_ecart_type\t pue\t fpe\t fm\t cap\t art_agglo_id\t nb_unite_recencee\t nb_jour_activite\t nb_jour_deb\t stat_gt_id\t obs_gt_min\t obs_gt_max\t pue_gt_ecart_type\t pue_gt\t fpe_gt\t fm_gt\t cap_gt\t art_grand_type_engin_id\t art_stat_totale_id\t nbre_enquete_gt\t art_grand_type\t art_grand_type_lib\t stat_gt_sp_id\t obs_gt_sp_min\t obs_gt_sp_max\t pue_gt_sp_ecart_type\t pue_gt_sp\t cap_gt_sp\t ref_espece_id\t art_stat_gt_id\t nbre_enquete_gt_sp\t esp\t esp_lib\t info\t ref_famille_id\t ref_categorie_ecologique_id\t ref_categorie_trophique_id\t coefficient_k\t coefficient_b\t ref_origine_kb_id\t ref_espece_id\t eco\t eco_lib\t troph\t troph_lib\t famille\t famille_lib\t ref_ordre_id\t non_poisson\t ordre\t ordre_lib\n";

	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);

	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $k."\t";
		
		//si $row[0]different, numero +1
		for ($i=0; $i<77; $i++)	//il y a 76 champs dans la requete
			{
			$contenu .= trim($row[$i])."\t";
			}
		//$contenu .= $row[0]."\t".$row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\t".$row[5]."\t";
		$contenu = substr($contenu, 0, -1);
		$contenu .= "\n";
		fputs($fpm,$contenu);
		$k++;
		}
	fclose($fpm);

///////////////////////////////////////
	print ("<br>La sélection porte sur ".($k-1)." lignes");//car 1ere ligne est un intitulé
	
	
	/*pg_free_result();
	pg_close();*/
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte
	///////////////////////////////////////////////////////////
	

	print ("<br><br><Font Color =\"#333366\">");
	print ("Critère de selection :</font>");
	
	print ("<form name=\"form\" method=\"post\" action=\"stat_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");

	
	?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('voir') != -1)
		form.elements[i].checked = booleen;
		}
	</script>
		<?php 
		print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
	
	
	////////////table pays et systeme
	print ("<table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?><div onClick="document.getElementById('_pays').style.display = 'block';"><b>Pays</b>
</div> 


<div id="_pays" style="display:none">
<?php    print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");//nom pays
	print ("<input type=hidden name=\"voir[2]\" value=\"2\">");
print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_pays').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_syst').style.display = 'block';"><b>Système</b>
</div>  

<div id="vue_syst" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le systeme
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");
	print ("<input type=hidden name=\"voir[4]\" value=\"4\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_syst').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
		////////////table secteur et agglomération
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top  align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_sect').style.display = 'block';"><b>Secteur</b>
</div>

<div id="_sect" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour le secteur
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect</td>");
	print ("<input type=hidden name=\"voir[5]\" value=\"8\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect_lib</td>");
	print ("<input type=hidden name=\"voir[6]\" value=\"9\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_sect').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_agglo').style.display = 'block';"><b>Agglomération</b>
</div>

<div id="vue_agglo" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour les agglomerations
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"12\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>nom</td>");
	print ("<input type=hidden name=\"voir[8]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"20\" ></td><td>type</td>");
	
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
	//pour les statistiques
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"300\">");
	
	?>
	<div onClick="document.getElementById('_stat').style.display = 'block';"><b>Statistiques par Grand Type et Espèce</b>
	</div>
	
	<div id="_stat" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>année</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"22\">");
	print ("<td WIDTH=30>x</td><td>mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"23\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[12]\" value=\"36\" ></td><td>id</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pue_gt_sp</td>");
	print ("<input type=hidden name=\"voir[13]\" value=\"53\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"52\" ></td><td>pue_gt_sp_ecart_type</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"50\" ></td><td>obs_gt_sp_min</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"51\" ></td><td>obs_gt_sp_max</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"31\" ></td><td>cap_tot</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"30\" ></td><td>Fm_tot</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"57\" ></td><td>nbre_enquete_gt_sp</td>");
	print ("<td WIDTH=30>x</td><td>cap_gt_sp</td>");
	print ("<input type=hidden name=\"voir[20]\" value=\"54\">");
	print ("<td WIDTH=30>x</td><td>grand_type_engin</td>");
	print ("<input type=hidden name=\"voir[21]\" value=\"44\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[22]\" value=\"48\"></td><td>grd_type_eng_lib</td>");
	print ("<td WIDTH=30>x</td><td>id espece</td>");
	print ("<input type=hidden name=\"voir[23]\" value=\"59\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[24]\" value=\"44\"></td><td>grand_type_engin</td>");
	print ("<input type=hidden name=\"voir[24]\" value=\"44\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"69\"></td><td>cat_eco</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[26]\" value=\"71\"></td><td>cat_troph</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[27]\" value=\"73\"></td><td>famille</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[28]\" value=\"77\"></td><td>ordre</td>");
	
	print ("</tr><tr ALIGN=center><td colspan=6 onclick=\"document.getElementById('_stat').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");

	
	print ("</div></tr></table>");
	
	
	
	
	
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
	if(isset($_POST['case5']))print ("<input type=\"hidden\" name=\"case5\" value=\"case5\">");
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	
	
	
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	
	print("</div>");
	}//fin de if ($requete_faite != 1)
	
else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="temp_selection_stat_cap_gt_sp.txt";
	$fpm = fopen($file, "r");

	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	while ($ligne=fgets($fpm,10000))
		{
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm);
	
	//ouverture fichier pour ecriture en local
	$file="temp_selection_stat_cap_gt_sp.txt";
	$fpm = fopen($file, "w+");
	reset($tab_ligne);
	$compt = 0;
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		reset ($ligne_contient);
		reset ($_POST['voir']);
		
		//seules les valeurs des colonnes contenu dans $_POST['voir'] doivent rester
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print ("<br><br>***".$key_contient."    ,    ".$val_contient);
			//key_contient = numero colonne, val_contient=valeur colonne
			if (!in_array ($key_contient, $_POST['voir']))
				{
				//print ("<br>!!!enleve colonne :".$key_contient.", soit : ".$ligne_contient[$key_contient]);
				unset ($ligne_contient[$key_contient]);
				}
				
			}
		
		//on ecrit la nouvelle ligne
		reset($ligne_contient);
		$nouveau = "";
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print("<br>".$key_contient." , ".$val_contient);
			$nouveau .= $val_contient . "\t";
			}
		$nouveau .= "\n";
		//print("<br>!!!!".$nouveau);
		fputs($fpm,$nouveau);
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './temp_selection_stat_cap_gt_sp.txt';

// ouverture du fichier à compresser
if($fp = fopen($filename, "rb"))
	{
	// lecture du contenu
	$size1 = filesize($filename);
	$data = fread($fp, $size1);
	// fermeture
	fclose($fp);

	// compression des données
	$gzdata = gzencode($data, 9);
	
	// ouverture et création du fichier compressé
	if($fp = fopen($filename.'.gz', 'wb'))
		{
		// écriture des données compressées
		fwrite($fp, $gzdata);
		// fermeture
		fclose($fp);
		} else {echo "Impossible d'ouvrir $filename.gz en écriture.";}
	} else {echo "Impossible d'ouvrir $filename en lecture.";}

//affichage du lien
print ("<div align='center'><br><br><br>Le fichier texte contenant les résultats des sélections a été créé.");
print ("<br><br>Cliquez sur le lien pour l'enregistrer sur votre ordinateur.");

// Ajout variable chemin
$pathtar = "http://".$hostname."/extraction"; 
print ("<br><br><a href=\"".$pathtar."/temp_selection_stat_cap_gt_sp.txt.gz\"<b>Enregistrement du fichier texte</b></a>");


//print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/temp_selection_stat_cap_gt_sp.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");
print("<div align='center'><br><br>");
print ("<form name=\"form_tot\" method=\"post\" action=\"stat_filieres.php\">");
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
		$i=0;
		while (list($key, $val) = each($val_P))
			{
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
	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	print ("<br><input type=\"submit\" name=\"\" value=\"    suite    \">");

print("</div>");

	
}//fin du else
	
	exit;
	
	}//fin du if(isset($_POST['case5']))



//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
///////                    traitement de DFT gt_sp                        ////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////


if(isset($_POST['case6']))
	{
/*	$user="devppeao";			// Le nom d'utilisateur 
$passwd="2devppe!!";			// Le mot de passe 
$host= "vmppeao.mpl.ird.fr";	// L'hôte (ordinateur sur lequel le SGBD est installé) 
$bdd = "jerome_manant";   */

	
	
	if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	print("<div align='center'>");
	print("<br><b>Tableau DFT_gt_sp</b><br>");

	$query_globale = "";
	/*$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	if (!$connection) { echo "Pas de connection"; exit;}
	*/
	
	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur
	, art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_stat_totale 
	, art_stat_gt
	, art_stat_gt_sp 
	, ref_espece 
	left join ref_categorie_ecologique on ref_espece.ref_categorie_ecologique_id=ref_categorie_ecologique.id 
	left join ref_categorie_trophique on ref_espece.ref_categorie_trophique_id=ref_categorie_trophique.id 
	left join (ref_famille left join ref_ordre on ref_famille.ref_ordre_id=ref_ordre.id) on ref_espece.ref_famille_id=ref_famille.id 
	, art_grand_type_engin 
	, art_taille_gt_sp 
	where ref_pays.id=ref_systeme.ref_pays_id 
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_stat_totale.art_agglomeration_id 
	and art_stat_totale.id=art_stat_gt.art_stat_totale_id 
	and art_stat_gt.id=art_stat_gt_sp.art_stat_gt_id 
	and art_stat_gt_sp.ref_espece_id=ref_espece.id 
	and art_stat_gt.art_grand_type_engin_id=art_grand_type_engin.id 
	and art_taille_gt_sp.art_stat_gt_sp_id=art_stat_gt_sp.id ";

	
	$nb_campagne = count ($_POST['agglo']);
	reset($_POST['agglo']);
	if ($nb_secteur == 1)$query_globale .= "and art_agglomeration.id = ".$_POST['agglo'][0]." ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['agglo']))
			{
			$query_globale .= "(art_agglomeration.id = ".$val.") or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));

	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_stat_totale.annee =".$key." ";
				
				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_stat_totale.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
	
	$nb_esp = count ($_POST['espece']);

	reset($_POST['espece']);
	if ($nb_esp == 1)$query_globale .= "and ref_espece.id = '".$_POST['espece'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['espece']))
			{
			$query_globale .= "(ref_espece.id = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	$nb_engin = count ($_POST['engin']);

	reset($_POST['engin']);
	if ($nb_esp == 1)$query_globale .= "and art_grand_type_engin.id = '".$_POST['engin'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['engin']))
			{
			$query_globale .= "(art_grand_type_engin.id = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);

	
	////////////////////////////////////////////////////////////////
	//       ecriture des resultats dans un fichier text          //
	////////////////////////////////////////////////////////////////
	$file="temp_selection_stat_dft_gt_sp.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t ref_pays_id\t syst_surf\t id_secteur\t sect\t sect_lib\t sect_surf\t ref_systeme_id\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t agglo_memo\t id_type_agglo\t type_agglo\t stat_tot_id\t annee\t mois\t nbre_obs\t obs_min\t obs_max\t pue_ecart_type\t pue\t fpe\t fm\t cap\t art_agglo_id\t nb_unite_recencee\t nb_jour_activite\t nb_jour_deb\t stat_gt_id\t obs_gt_min\t obs_gt_max\t pue_gt_ecart_type\t pue_gt\t fpe_gt\t fm_gt\t cap_gt\t art_grand_type_engin_id\t art_stat_totale_id\t nbre_enquete_gt\t stat_gt_sp_id\t obs_gt_sp_min\t obs_gt_sp_max\t pue_gt_sp_ecart_type\t pue_gt_sp\t cap_gt_sp\t ref_espece_id\t art_stat_gt_id\t nbre_enquete_gt_sp\t esp\t esp_lib\t info\t ref_famille_id\t ref_categorie_ecologique_id\t ref_categorie_trophique_id\t coefficient_k\t coefficient_b\t ref_origine_kb_id\t ref_espece_id\t eco\t eco_lib\t troph\t troph_lib\t famille\t famille_lib\t ref_ordre_id\t non_poisson\t ordre\t ordre_lib\t grand_type_engin\t grand_type_engin_lib\t taille_id\t li\t xi\t art_stat_gt_sp_id\n";
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);

	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $k."\t";
		
		//si $row[0]different, numero +1
		for ($i=0; $i<81; $i++)	//il y a 80 champs dans la requete
			{
			$contenu .= trim($row[$i])."\t";
			}
		//$contenu .= $row[0]."\t".$row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\t".$row[5]."\t";
		$contenu = substr($contenu, 0, -1);
		$contenu .= "\n";
		fputs($fpm,$contenu);
		$k++;
		}
	fclose($fpm);

	///////////////////////////////////////
	print ("<div align='center'>");

	print ("<br>La sélection porte sur ".($k-1)." lignes");//car 1ere ligne est un intitulé
	
	/*pg_free_result();
	pg_close();*/
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte///
	///////////////////////////////////////////////////
	

	print ("<br><br><Font Color =\"#333366\">");
	print ("Critère de selection");
	
	print ("<form name=\"form\" method=\"post\" action=\"stat_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");

	

	?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('voir') != -1)
		form.elements[i].checked = booleen;
		}
	</script>
		<?php 
		print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
	
	
	////////////table pays et systeme
	print ("<table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?><div onClick="document.getElementById('_pays').style.display = 'block';"><b>Pays</b>
</div> 


<div id="_pays" style="display:none">
<?php    print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");//nom pays
	print ("<input type=hidden name=\"voir[2]\" value=\"2\">");
print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_pays').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_syst').style.display = 'block';"><b>Système</b>
</div>  

<div id="vue_syst" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le systeme
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>libellé</td>");
	print ("<input type=hidden name=\"voir[4]\" value=\"4\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_syst').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
		////////////table secteur et agglomération
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top  align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_sect').style.display = 'block';"><b>Secteur</b>
</div>

<div id="_sect" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour le secteur
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect</td>");
	print ("<input type=hidden name=\"voir[5]\" value=\"8\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect_lib</td>");
	print ("<input type=hidden name=\"voir[6]\" value=\"9\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_sect').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php 
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_agglo').style.display = 'block';"><b>Agglomération</b>
</div>

<div id="vue_agglo" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//pour les agglomerations
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"12\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>nom</td>");
	print ("<input type=hidden name=\"voir[8]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"20\" ></td><td>type</td>");
	
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php 
	print ("</td></tr></table>");
	
	
	//pour les statistiques totales
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"350\">");
	
	?>
	<div onClick="document.getElementById('_stat').style.display = 'block';"><b>Structures de taille par Grand Type et Espèce</b>
	</div>
	
	<div id="_stat" style="display:none">
<?php  
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"350\">");
		print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>année</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"22\">");
	print ("<td WIDTH=30>x</td><td>mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"23\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[12]\" value=\"28\" ></td><td>pue_tot</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[13]\" value=\"30\"></td><td>Fm_tot</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"31\"></td><td>Cap_tot</td>");

	print ("<td WIDTH=30>x</td><td>li</td>");
	print ("<input type=hidden name=\"voir[15]\" value=\"78\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>xi</td>");
	print ("<input type=hidden name=\"voir[16]\" value=\"79\">");
	
	print ("<td WIDTH=30>x</td><td>engin_id</td>");
	print ("<input type=hidden name=\"voir[17]\" value=\"76\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"77\" ></td><td>engin_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>espece_id</td>");
	print ("<input type=hidden name=\"voir[19]\" value=\"56\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[20]\" value=\"57\"></td><td>esp_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[21]\" value=\"71\"></td><td>famille</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[22]\" value=\"75\"></td><td>ordre</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"67\" ></td><td>cat_eco</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[24]\" value=\"69\" ></td><td>cat_troph</td>");
	print ("</tr><tr ALIGN=center><td colspan=6 onclick=\"document.getElementById('_stat').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");

	
	print ("</div></tr></table>");
	


	
	
	
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
		$i=0;
		while (list($key, $val) = each($val_P))
			{
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

	if(isset($_POST['case6']))print ("<input type=\"hidden\" name=\"case6\" value=\"case6\">");
	
	
	
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	
	print("</div>");
	}//fin de if ($requete_faite != 1)
	
else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="temp_selection_stat_dft_gt_sp.txt";
	$fpm = fopen($file, "r");

	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	while ($ligne=fgets($fpm,10000))
		{
		//print("<br>!!!!".$ligne);		//ok
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm);
	
	//ouverture fichier pour ecriture en local
	$file="temp_selection_stat_dft_sp.txt";
	$fpm = fopen($file, "w+");
	reset($tab_ligne);
	$compt = 0;
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		reset ($ligne_contient);
		reset ($_POST['voir']);
		
		//seules les valeurs des colonnes contenu dans $_POST['voir'] doivent rester
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print ("<br><br>***".$key_contient."    ,    ".$val_contient);
			//key_contient = numero colonne, val_contient=valeur colonne
			if (!in_array ($key_contient, $_POST['voir']))
				{
				//print ("<br>!!!enleve colonne :".$key_contient.", soit : ".$ligne_contient[$key_contient]);
				unset ($ligne_contient[$key_contient]);
				}
				
			}
		
		//on ecrit la nouvelle ligne
		reset($ligne_contient);
		$nouveau = "";
		while (list($key_contient, $val_contient) = each($ligne_contient))
			{
			//print("<br>".$key_contient." , ".$val_contient);
			$nouveau .= $val_contient . "\t";
			}
		$nouveau .= "\n";
		//print("<br>!!!!".$nouveau);
		fputs($fpm,$nouveau);
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './temp_selection_stat_dft_gt_sp.txt';

// ouverture du fichier à compresser
if($fp = fopen($filename, "rb"))
	{
	// lecture du contenu
	$size1 = filesize($filename);
	$data = fread($fp, $size1);
	// fermeture
	fclose($fp);

	// compression des données
	$gzdata = gzencode($data, 9);
	
	// ouverture et création du fichier compressé
	if($fp = fopen($filename.'.gz', 'wb'))
		{
		// écriture des données compressées
		fwrite($fp, $gzdata);
		// fermeture
		fclose($fp);
		} else {echo "Impossible d'ouvrir $filename.gz en écriture.";}
	} else {echo "Impossible d'ouvrir $filename en lecture.";}

//affichage du lien
print ("<div align='center'><br><br><br>Le fichier texte contenant les résultats des sélections a été créé.");
print ("<br><br>Cliquez sur le lien pour l'enregistrer sur votre ordinateur.");

// Ajout variable chemin
$pathtar = "http://".$hostname."/extraction"; 
print ("<br><br><a href=\"".$pathtar."/temp_selection_stat_dft_sp.txt.gz\"<b>Enregistrement du fichier texte</b></a>");


//print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/temp_selection_stat_dft_gt_sp.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");
print("<div align='center'><br><br>");
print ("<form name=\"form_tot\" method=\"post\" action=\"stat_filieres.php\">");
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
		$i=0;
		while (list($key, $val) = each($val_P))
			{
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

	print ("<br><input type=\"submit\" name=\"\" value=\"    suite    \">");

print("</div>");

	
}//fin du else
	
	exit;
	
	}//fin du if(isset($_POST['case6']))






print("<br><br><br><br><br><br><br><br><br><br><br><br><div align='center'>
Extraction des statistiques terminée<br><br>
<input type='button' value='Fermer' onClick='self.close()' name=\"button\"> </div>"); 

//pg_free_result();
pg_close();
?>
</body>
</html>