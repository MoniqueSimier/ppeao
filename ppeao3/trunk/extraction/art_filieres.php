<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">
</head>
<body BGCOLOR="#CCCCFF">

<?php
if(! ini_set("max_execution_time", "480")) {echo "échec max_execution_time";}
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
$type_donnees = $_POST['type_donnees'];



print("<div align='center'>");
print("<Font Color =\"#333366\">");
print("<br>");
print("</div></Font>");


//Si l'on veux voir à l'écran les données en post :
/*  
while (list($key, $val) = each($_POST))
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
	}
*/
$requete_faite = $_POST['requete_faite'];
$selection_faite = $_POST['selection_faite'];
$colonnes_faites = $_POST['colonnes_faites'];
$regroupement = $_POST['regroupement'];




/////////////////////////////////////////////////////////////////////////////////////
//fabrication de la requete globale recueillant les informations apres preselection//
/////////////////////////////////////////////////////////////////////////////////////
switch ($choix){
case "   Activité    ":

print("<div align='center'>");
print("<Font Color =\"#333366\">");
print("<b>Base de Données PPEAO</b><br><br>Filière sur l'activité de pêche<br><br>");
print("</div></Font>");

if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	$query_globale = "";
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}
	
	
	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur, 
	art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_activite 
	left join art_grand_type_engin on art_activite.art_grand_type_engin_id=art_grand_type_engin.id 
	left join art_type_activite on art_activite.art_type_activite_id=art_type_activite.id 
	left join art_type_sortie on art_activite.art_type_sortie_id=art_type_sortie.id 
	left join art_millieu on art_activite.art_millieu_id=art_millieu.id 
	left join (art_unite_peche
	left join art_categorie_socio_professionnelle on art_unite_peche.art_categorie_socio_professionnelle_id=art_categorie_socio_professionnelle.id)
	on art_activite.art_unite_peche_id=art_unite_peche.id 
	, art_type_engin, art_engin_activite 
	where ref_pays.id=ref_systeme.ref_pays_id 
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_activite.art_agglomeration_id 
	
	and art_activite.id = art_engin_activite.art_activite_id 
	and art_engin_activite.art_type_engin_id=art_type_engin.id 
	
	

	
	 ";

	
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


	
	$nb_engin = count ($_POST['engin']);

	reset($_POST['engin']);
	if ($nb_engin == 1)$query_globale .= "and art_grand_type_engin.id = '".$_POST['engin'][0]."' ";
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
	
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));

	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_activite.annee =".$key." ";

				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_activite.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") order by ref_pays.id asc, ref_systeme.id asc, art_agglomeration.nom, art_activite.annee asc 
	,art_activite.mois asc ,art_activite.id asc, art_activite.art_grand_type_engin_id ";
	
	
	
	
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);

	
	///////////////////////////////////////
	//ecriture des resultats dans un fichier text
	//////////////////////////////////////
	$file="selection_activ.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t refpaysid\t syst_surf\t idsecteur\t sect\t sect_lib\t sect_surf\t refsystemeid\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t mmagglo\t idtype_agglo\t type_agglo\t activ\t up\t agglo\t type_sortie\t grd_type_engin\t milieu\t activ_date\t nup_recens\t activ_an\t activ_mois\t cccoode\t activ_nb_hom\t activ_nb_fem\t activ_nb_enf\t type_activ\t grd_type_engin\t grd_type_engin_lib\t type_activ_lib1\t type_activ_lib2\t type_activ\t type_sortie\t type_sortie_lib\t milieu\t milieu_lib\t up\t csp\t up_lib\t up_lib_menage\t cccoode\t agglo\t bbbasepays\t csp\t csp_lib\t type_engin\t grd_type_ep\t type_engin_lib \t eee\t \t nbre_engin\n";
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);

	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $row[20]."\t";
		
		//si $row[0]different, numero +1
		for ($i=0; $i<64; $i++)	//il y a 56 champs dans la requete
			{
			$contenu .= trim($row[$i])."\t";
			}
		$contenu = substr($contenu, 0, -1);
		$contenu .= "\n";
		fputs($fpm,$contenu);
		$k++;
		}
	fclose($fpm);
	///////////////////////////////////////
	///////////////////////////////////////
	print ("<div align='center'>");
	print ("La sélection fournit ".($k-1)." enquêtes d'activité");//car 1ere ligne est un intitulé
	
	//pg_free_result();
	//pg_close();
	//////////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte
	///////////////////////////////////////////////////////////


	print ("<br><br><b>Sélection des champs optionnels</b>
	<br><br>Vous pouvez cliquez sur le nom d'une table pour la développer
	<br>Les valeurs classiques sont sélectionnées par défaut");
	
	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
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
<?php   print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays_lib</td>");//nom pays
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
	//print ("</tr><tr><td>Champs facultatifs du système</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst_lib</td>");
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
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>agglo</td>");
	print ("<input type=hidden name=\"voir[300]\" value=\"13\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>agglo_lib</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[302]\" value=\"13\"></td><td>type_agglo</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[8]\" value=\"20\"></td><td>type_agglo_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[303]\" value=\"17\"></td><td>agglo_lat</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[304]\" value=\"16\"></td><td>agglo_long</td>");
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	
	
	////////////table unité de peche et activite
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_up').style.display = 'block';"><b>Unité de pêche</b>
</div>

<div id="_up" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>up</td>");
		print ("<input type=hidden name=\"voir[24]\" value=\"22\">");
	print ("<td WIDTH=30>x</td><td>grd_type_engin</td>");
		print ("<input type=hidden name=\"voir[28]\" value=\"36\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"47\" ></td><td>up_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[26]\" value=\"48\" ></td><td>up_lib_menage</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"52\" ></td><td>csp</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"53\" ></td><td>csp_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"32\" ></td><td>activ_nb_hom</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"33\" ></td><td>activ_nb_fem</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"34\" ></td><td>activ_nb_enf</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[27]\" value=\"54\" ></td><td>type_engin</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[222]\" value=\"59\" ></td><td>nbre_engin</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[203]\" value=\"55\" ></td><td>grd_type_ep</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[29]\" value=\"37\" ></td><td>grd_type_engin_lib</td>");
	


	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('_up').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_act').style.display = 'block';"><b>Activité</b>
</div>


<div id="vue_act" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"300\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>activite</td>");
	print ("<input type=hidden name=\"voir[9]\" value=\"21\">");
	print ("<td WIDTH=30>x</td><td>nbre_unite_recencee</td>");
	print ("<input type=hidden name=\"voir[301]\" value=\"28\">");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[13]\" value=\"38\" ></td><td>raison</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"39\" ></td><td>libellé raison</td>");
	

	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[20]\" value=\"41\" ></td><td>type_sortie</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[21]\" value=\"42\" ></td><td>type_sortie_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[22]\" value=\"43\" ></td><td>milieu</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"44\" ></td><td>milieu_lib</td>");
	
	
	
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('vue_act').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	
	
	//table date
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('_date').style.display = 'block';"><b>Date</b>
	</div>
	
	<div id="_date" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[351]\" value=\"27\" ></td><td>date</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[352]\" value=\"29\" ></td><td>annee</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[353]\" value=\"30\" ></td><td>mois</td>");
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('_date').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");

	
	print ("</div></tr></table>");
	
	
	
	
	
	
	
	
print ("<input type=hidden name=\"voir[200]\" value=\"0\">");
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</form></div>");
	}
else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="selection_activ.txt";
	$fpm = fopen($file, "r");
	$nombre_ligne=0;
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
	
	$non_doublon=Array();
	
	//ouverture fichier pour ecriture en local?????
	$file="selection_activ.txt";
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
			$nouveau .= $val_contient . "\t";
			}
		$nouveau = substr($nouveau, 0, -1);//on enleve le dernier \t
		$nouveau .= "\n";
		
		
		//on enleve les doublons
		if (!in_array ($nouveau,$non_doublon))
			{
			fputs($fpm,$nouveau);
			$non_doublon [$nombre_ligne]= $nouveau;
			$nombre_ligne++;
			}
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './selection_activ.txt';

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

print ("<br>Celui ci comporte ".($nombre_ligne-1)." lignes.
<br>Vous devez sauvegarder ce fichier sur votre ordinateur pour ne pas perdre la sélection en cours.<br>Cliquez sur le lien pour l'enregistrement.");

//print ("<br><br><a href=\"http://vmppeao.mpl.ird.fr/extraction/temp_selection_globale.txt.gz\"<b>Enregistrement du fichier texte</b></a>");
print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_activ.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

?>
<SCRIPT LANGUAGE="JavaScript"> 
function fermer() {
if(confirm("Etes vous sûr ?"))window.close();}
</script>

<?php

print("<div align='center'><br><br>");
print("<input type='button' value='Fermer' onClick= 'fermer()'   name=\"button\">"); 

print("</div>");




	
	
	
	
	}
break;



////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
case " Captures ": 
if ($requete_faite != 1)		//si requête globale pas encore faite
	{
	print("<div align='center'>");
	print("<Font Color =\"#333366\">");
	print("<b>Base de Données PPEAO</b><br><br>Filière sur les captures totales<br><br>");
	print("</div></Font>");
	
	
	
	$query_globale = "";
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}
	
	
	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur, 
	art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_debarquement 
	left join art_vent on art_debarquement.art_vent_id=art_vent.id 
	left join art_etat_ciel on art_debarquement.art_etat_ciel_id=art_etat_ciel.id 
	left join art_type_sortie on art_debarquement.art_type_sortie_id=art_type_sortie.id 
	left join art_millieu on art_debarquement.art_millieu_id=art_millieu.id 
	left join art_lieu_de_peche on art_debarquement.art_lieu_de_peche_id=art_lieu_de_peche.id 
	left join art_grand_type_engin on art_debarquement.art_grand_type_engin_id=art_grand_type_engin.id 
	left join (art_unite_peche
	left join art_categorie_socio_professionnelle on art_unite_peche.art_categorie_socio_professionnelle_id=art_categorie_socio_professionnelle.id)
	on art_debarquement.art_unite_peche_id=art_unite_peche.id 
	, art_type_engin , art_debarquement_rec, art_engin_peche 
	where ref_pays.id=ref_systeme.ref_pays_id 
	
	and art_debarquement.id = art_engin_peche.art_debarquement_id 
	and art_engin_peche.art_type_engin_id=art_type_engin.id 
	
	
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_debarquement.art_agglomeration_id 
	
	and art_debarquement.id = art_debarquement_rec.art_debarquement_id 
	";

	
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
	
	
	$nb_engin = count ($_POST['engin']);
	reset($_POST['engin']);
	if ($nb_engin == 1)$query_globale .= "and art_grand_type_engin.id = '".$_POST['engin'][0]."' ";
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
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));
	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_debarquement.annee =".$key." ";
				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_debarquement.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
		
		
	$query_globale .= " 
	order by ref_pays.id asc, ref_systeme.id asc, art_agglomeration.nom, art_debarquement.annee asc 
	,art_debarquement.mois asc ,art_debarquement.id asc ";
	
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);


	
	
	///////////////////////////////////////////////
	//ecriture des resultats dans un fichier text//
	///////////////////////////////////////////////
	$file="selection_captu.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	if($type_donnees=="brutes")$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t refpaysid\t syst_surf\t idsecteur\t sect\t sect_lib\t sect_surf\t refsystemeid\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t mmagglo\t type_agglo\t libellé type\t iddeb\t milieu\t vent\t etat_ciel\t agglo\t lieu_peche\t up\t grd_type_engin\t type_sortie\t dbq_date_dep\t dbq_heure_deb\t dbq_heure\t dbq_heure_pose_engin\t nb_coups\t dbq_pt\t glaciere\t dbq_liste_lieu_peche\t dbq_an\t dbq_mois\t mmmemo\t cccccode\t dbq_nb_hom\t dbq_nb_fem\t dbq_nb_enf\t dbq_date\t vent\t vent_lib\t etat_ciel\t etat_ciel_lib\t type_sirtie\t type_sortie_lib\t milieu\t milieu_lib\t lieu_peche\t sect\t lieu_peche_lib\t cccode\t grd_type_engin\t grd_type_engin_lib\t up\t csp\t up_lib\t up_lib_menage\t cccoode\t agglo\t bbbasepays\t csp\t csp_lib\t type_engin\t grd_type_engin\t type_engin_lib\n";
	if($type_donnees=="elaboree")$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t refpaysid\t syst_surf\t idsecteur\t sect\t sect_lib\t sect_surf\t refsystemeid\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t mmagglo\t type_agglo\t libellé type\t iddeb\t milieu\t vent\t etat_ciel\t agglo\t lieu_peche\t up\t grd_type_engin\t type_sortie\t dbq_date_dep\t dbq_heure_deb\t dbq_heure\t dbq_heure_pose_engin\t nb_coups\t dbq_pt\t glaciere\t dbq_liste_lieu_peche\t dbq_an\t dbq_mois\t mmmemo\t cccccode\t dbq_nb_hom\t dbq_nb_fem\t dbq_nb_enf\t dbq_date\t vent\t vent_lib\t etat_ciel\t etat_ciel_lib\t type_sirtie\t type_sortie_lib\t milieu\t milieu_lib\t lieu_peche\t sect\t lieu_peche_lib\t cccode\t grd_type_engin\t grd_type_engin_lib\t up\t csp\t up_lib\t up_lib_menage\t cccoode\t agglo\t bbbasepays\t csp\t csp_lib\t type_engin\t grd_type_engin\t type_engin_lib\t id\t poids\t ref\n";
	
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);

	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		//$contenu.= $k."\t";
		$contenu.= $row[20]."\t";
		
		
		if($type_donnees=="brutes")$iii=71;
		if($type_donnees=="elaboree")$iii=74;
		//si $row[0]different, numero +1
		for ($i=0; $i<$iii; $i++)	//il y a 71 champs dans la requete
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
	///////////////////////////////////////
	print ("<div align='center'>");
	print ("La sélection fournit ".($k-1)." enquêtes de débarquement");//car 1ere ligne est un intitulé
	
	//pg_free_result();
	//pg_close();
	//////////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte
	///////////////////////////////////////////////////////////

	print ("<br><br><b>Sélection des champs optionnels</b>
	<br><br>Vous pouvez cliquez sur le nom d'une table pour la développer
	<br>Les valeurs classiques sont sélectionnées par défaut");
	
	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
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
<?php   print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays_lib</td>");//nom pays
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
	//print ("</tr><tr><td>Champs facultatifs du système</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst_lib</td>");
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
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>agglo_lib</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[8]\" value=\"20\"></td><td>type_agglo</td>");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	
	
	////////////table unite peche et capture
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top ><td VALIGN=top align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_up').style.display = 'block';"><b>Unité de pêche</b>
</div>

<div id="_up" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>grd_type_engin</td>");
	print ("<input type=hidden name=\"voir[26]\" value=\"58\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[27]\" value=\"59\" ></td><td>grd_type_engin_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[28]\" value=\"69\" ></td><td>type_engin</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[701]\" value=\"60\" ></td><td>up</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[702]\" value=\"62\" ></td><td>up_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[703]\" value=\"63\" ></td><td>lib_men</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[708]\" value=\"65\" ></td><td>agglo</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[709]\" value=\"8\" ></td><td>sect_ori</td>");

	
	
	
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('_up').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('vue_cap').style.display = 'block';"><b>Captures</b>
</div>
<div id="vue_cap" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"45\" ></td><td>dbq_date</td>");
	print ("<td WIDTH=30>x</td><td>dbq_an</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"38\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"39\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[13]\" value=\"32\" ></td><td>dbq_heure</td>");
	
	if($type_donnees=="brutes")
		{
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_pt</td>");
	print ("<input type=hidden name=\"voir[12]\" value=\"35\">");
		}
	if($type_donnees=="elaboree")
		{
		print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_pt</td>");
		print ("<input type=hidden name=\"voir[12]\" value=\"73\">");
		}
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[113]\" value=\"34\" ></td><td>nb_coups</td>");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"42\" ></td><td>dbq_nb_hom</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[116]\" value=\"43\" ></td><td>dbq_nb_fem</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"44\" ></td><td>dbq_nb_enf</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"67\" ></td><td>csp</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"68\" ></td><td>csp_lib</td>");
	
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"36\" ></td><td>glacière</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"54\" ></td><td>lieu_peche</td>");
	
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[20]\" value=\"50\" ></td><td>type_sortie</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[21]\" value=\"51\" ></td><td>type_sortie_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[22]\" value=\"52\" ></td><td>milieu</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[22]\" value=\"53\" ></td><td>milieu_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"46\" ></td><td>vent</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"47\" ></td><td>vent_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[24]\" value=\"48\" ></td><td>etat_ciel</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"49\" ></td><td>etat_ciel_lib</td>");
	
	
	
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('vue_cap').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	
	
	print ("<input type=hidden name=\"voir[200]\" value=\"0\">");
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</form></div>");
	}
	else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="selection_captu.txt";
	$fpm = fopen($file, "r");
	$nombre_ligne=0;
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
	
	$non_doublon=Array();
	
	//ouverture fichier pour ecriture en local?????
	$file="selection_captu.txt";
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
		$nouveau = substr($nouveau, 0, -1);//on enleve le dernier \t
		$nouveau .= "\n";

		
		//on enleve les doublons
		if (!in_array ($nouveau,$non_doublon))
			{
			//$nouveau2=($nombre_ligne+1)."\t".$nouveau;
			fputs($fpm,$nouveau);
			$non_doublon [$nombre_ligne]= $nouveau;
			$nombre_ligne++;
			}
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './selection_captu.txt';

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


print("<div align='center'>");
print("<Font Color =\"#333366\">");
print("<b>Base de Données PPEAO</b><br><br>Filière sur les captures totales<br>");
print("</div></Font>");




//affichage du lien
print ("<div align='center'><br><br><br>");

print ("<br>La sélection représente ".($nombre_ligne -1)." lignes dans le fichier de sortie.
<br>Vous devez sauvegarder ce fichier sur votre ordinateur pour ne pas perdre la sélection en cours.<br>Cliquez sur le lien pour l'enregistrement.");

print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_captu.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");

?>
<SCRIPT LANGUAGE="JavaScript"> 
function fermer() {
if(confirm("Etes vous sûr ?"))window.close();}
</script>

<?php

print("<div align='center'><br><br>");
print("<input type='button' value='Fermer' onClick= 'fermer()'   name=\"button\">"); 

print("</div>");

	}
break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////


case "     Nt, Pt     ": 
print("<div align='center'>");
print("<Font Color =\"#333366\">");
print("<b>Base de Données PPEAO</b><br><br>Filière sur les fractions débarquées (Nt-Pt)<br>");
print("</div></Font>");





if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	$query_globale = "";
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}

	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur, 
	art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_debarquement 
	left join art_vent on art_debarquement.art_vent_id=art_vent.id 
	left join art_etat_ciel on art_debarquement.art_etat_ciel_id=art_etat_ciel.id 
	left join art_type_sortie on art_debarquement.art_type_sortie_id=art_type_sortie.id 
	left join art_millieu on art_debarquement.art_millieu_id=art_millieu.id 
	left join art_lieu_de_peche on art_debarquement.art_lieu_de_peche_id=art_lieu_de_peche.id 
	left join art_grand_type_engin on art_debarquement.art_grand_type_engin_id=art_grand_type_engin.id 
	left join (art_unite_peche
	left join art_categorie_socio_professionnelle on art_unite_peche.art_categorie_socio_professionnelle_id=art_categorie_socio_professionnelle.id)
	on art_debarquement.art_unite_peche_id=art_unite_peche.id 
	, art_type_engin 
	, art_fraction 
	left join (ref_espece 
	left join ref_categorie_ecologique on ref_espece.ref_categorie_ecologique_id=ref_categorie_ecologique.id 
	left join ref_categorie_trophique on ref_espece.ref_categorie_trophique_id=ref_categorie_trophique.id 
	left join (ref_famille left join ref_ordre on ref_famille.ref_ordre_id=ref_ordre.id) on ref_espece.ref_famille_id=ref_famille.id 
	)on art_fraction.ref_espece_id=ref_espece.id 
	,  art_debarquement_rec, art_fraction_rec , art_engin_peche 
	
	
	where ref_pays.id=ref_systeme.ref_pays_id 
	
	and art_debarquement.id = art_engin_peche.art_debarquement_id 
	and art_engin_peche.art_type_engin_id=art_type_engin.id 
	
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_debarquement.art_agglomeration_id 
	
	and art_debarquement.id=art_fraction.art_debarquement_id 
	and art_debarquement.id = art_debarquement_rec.art_debarquement_id 
	and art_fraction.id = art_fraction_rec.id ";

	
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

	
	$nb_engin = count ($_POST['engin']);
	//print ("!!!!!!".$nb_secteur);
	reset($_POST['engin']);
	if ($nb_engin == 1)$query_globale .= "and art_grand_type_engin.id = '".$_POST['engin'][0]."' ";
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
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));
	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_debarquement.annee =".$key." ";
				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_debarquement.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
		
		
	$query_globale .= " 
	order by ref_pays.id asc, ref_systeme.id asc, art_agglomeration.nom, art_debarquement.annee asc 
	,art_debarquement.mois asc ,art_debarquement.id asc ";
	
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);

	
	
	///////////////////////////////////////
	//ecriture des resultats dans un fichier text
	//////////////////////////////////////
	$file="selection_nt_pt.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	if($type_donnees=="brutes")$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t refpaysid\t syst_surf\t idsecteur\t sect\t sect_lib\t sect_surf\t refsystemeid\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t mmagglo\t type_agglo\t libellé type\t iddeb\t milieu\t vent\t etat_ciel\t agglo\t lieu_peche\t up\t grd_type_engin\t type_sortie\t dbq_date_dep\t dbq_heure_deb\t dbq_heure\t dbq_heure_pose_engin\t nb_coups\t dbq_pt\t glaciere\t dbq_liste_lieu_peche\t dbq_an\t dbq_mois\t mmmemo\t cccccode\t dbq_nb_hom\t dbq_nb_fem\t dbq_nb_enf\t dbq_date\t vent\t vent_lib\t etat_ciel\t etat_ciel_lib\t type_sirtie\t type_sortie_lib\t milieu\t milieu_lib\t lieu_peche\t sect\t lieu_peche_lib\t cccode\t grd_type_engin\t grd_type_engin_lib\t up\t csp\t up_lib\t up_lib_menage\t cccoode\t agglo\t bbbasepays\t csp\t csp_lib\t type_engin\t grd_type_engin\t type_engin_lib\t fraction\t cooode\t fdbq_pt\t fdbq_nt\t fdbq_observee\t code_sp\t artdebid\t fdbq_prix\t codesp\t espece\t informationespece\t reffamilleid\t cat_ecol\t cat_troph\t coefK\t coefb\t reforiginekbid\t refespeceid\t cat_ecol\t cat_ecol_lib\t cat_troph\t cat_troph_lib\t idfamille\t famille\t refordreid\t non_poisson\t idordre\t ordre\n";
	if($type_donnees=="elaboree")$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t refpaysid\t syst_surf\t idsecteur\t sect\t sect_lib\t sect_surf\t refsystemeid\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t mmagglo\t type_agglo\t libellé type\t iddeb\t milieu\t vent\t etat_ciel\t agglo\t lieu_peche\t up\t grd_type_engin\t type_sortie\t dbq_date_dep\t dbq_heure_deb\t dbq_heure\t dbq_heure_pose_engin\t nb_coups\t dbq_pt\t glaciere\t dbq_liste_lieu_peche\t dbq_an\t dbq_mois\t mmmemo\t cccccode\t dbq_nb_hom\t dbq_nb_fem\t dbq_nb_enf\t dbq_date\t vent\t vent_lib\t etat_ciel\t etat_ciel_lib\t type_sirtie\t type_sortie_lib\t milieu\t milieu_lib\t lieu_peche\t sect\t lieu_peche_lib\t cccode\t grd_type_engin\t grd_type_engin_lib\t up\t csp\t up_lib\t up_lib_menage\t cccoode\t agglo\t bbbasepays\t csp\t csp_lib\t type_engin\t grd_type_engin\t type_engin_lib\t fraction\t cooode\t fdbq_pt\t fdbq_nt\t fdbq_observee\t code_sp\t artdebid\t fdbq_prix\t codesp\t espece\t informationespece\t reffamilleid\t cat_ecol\t cat_troph\t coefK\t coefb\t reforiginekbid\t refespeceid\t cat_ecol\t cat_ecol_lib\t cat_troph\t cat_troph_lib\t idfamille\t famille\t refordreid\t non_poisson\t idordre\t ordre\t id\t poids_total\t id\t fraction\t fdbq\t ndbq\t esp\n";
	
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);
	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $row[20]."\t";
		
		if($type_donnees=="brutes")$iii=99;
		if($type_donnees=="elaboree")$iii=106;
		
		for ($i=0; $i<$iii; $i++)	//il y a 99 champs dans la requete
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
	///////////////////////////////////////
	print ("<div align='center'>");
	//print ("Le fichier texte comporte ".($k-1)." lignes");//car 1ere ligne est un intitulé
	
	//pg_free_result();
	//pg_close();
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte
	///////////////////////////////////////////////////////////

	print ("<br><Font Color =\"#333366\">");
	print ("Critère de selection :");

	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	
	//renseignements sur les champs optionnels de la selection d'espece
	print("<table BORDER=1 CELLSPACING=2 CELLPADDING=1>");
	print ("<tr>");
	print ("<td>Voulez vous les poissons</td>");
	print ("<td><input type=\"radio\" name=\"poisson\" value=\"oui\"checked>oui</td>");
	print ("<td><input type=\"radio\" name=\"poisson\" value=\"non\">non</td>");
	print ("</tr><tr>");
	
	print ("<td>Voulez vous les non-poissons</td>");
	print ("<td><input type=\"radio\" name=\"non_poisson\" value=\"oui\">oui</td>");
	print ("<td><input type=\"radio\" name=\"non_poisson\" value=\"non\"checked>non</td>");
	print ("</tr><tr>");

	print ("</tr></table>");
	//print("<br>".$choix);
	
	print ("<br><br><table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
	print ("<td ROWSPAN=5 align=center>Quelle(s) catégorie(s) écologiques</td>");
		
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}
	$query = "select distinct ref_categorie_ecologique.id, ref_categorie_ecologique.libelle from ref_espece, ref_categorie_ecologique 
	where ref_espece.ref_categorie_ecologique_id = ref_categorie_ecologique.id
		
	";
	//print ($query);
	$result = pg_query($connection, $query);
		
	$cat_ecol=Array();
	$i = 0;
	while($row = pg_fetch_row($result))
		{
		//if($row[0]==null)$cat_ecol[$i] = "null";
		if($row[0]==null)continue;
		$cat_ecol[$i][0] = $row[0];
		$cat_ecol[$i][1] = $row[1];
		$i++;
		}
	// Deconnexion de la base de donnees
	//pg_close();
	$nb =0;
	$n = count($cat_ecol);
	$i=0;
	$colonne = ceil($n/5);	//affichage de 5 par colonne
	reset ($cat_ecol);
	while (list($key, $val) = each($cat_ecol))
		{
		$nb = $nb + 1;
		//$val2 = $val;
		//if($val==null)$val = "null";
		if($val[0]==null)continue;
		if ($nb <= $colonne)
			{
			print ("<td><input type=\"Checkbox\" name=\"ecologique[".$i."]\" value=\"".$val[0]."\"checked>".$val[1]."</td>");
			}
		else 	{
			print ("</tr><tr><td><input type=\"Checkbox\" name=\"ecologique[".$i."]\" value=\"".$val[0]."\"checked>".$val[1]."</td>");
			$nb =1;
			}
		$i++;
		}
		if ($i%2 == 0)//modulo, pour savoir si $i est pair
		print ("</tr><tr><td><input type=\"Checkbox\" name=\"ecologique[100]\" value=\"null\" checked>non renseigné</td>");
		else print ("<td><input type=\"Checkbox\" name=\"ecologique[100]\" value=\"null\" checked>non renseigné</td>");

	
	
	print ("</tr><tr>");
	print ("<td ROWSPAN=5 align=center>Quelle(s) catégorie(s) trophiques</td>");
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}
	$query = "select distinct ref_categorie_trophique.id, ref_categorie_trophique.libelle from ref_espece, ref_categorie_trophique 
	where ref_espece.ref_categorie_trophique_id = ref_categorie_trophique.id
	";
			//print ($query);
		
	$cat_troph=Array();
	$result=Array();
	$result = pg_query($connection, $query);
	$i = 0;
	while($row = pg_fetch_row($result))
		{
		//if($row[0]==null)$cat_troph[$i] = "null";
		//else $cat_troph[$i] = $row[0];
		$cat_troph[$i][0] = $row[0];
		$cat_troph[$i][1] = $row[1];
		$i++;
		}
	// Deconnexion de la base de donnees
	//pg_close();
	$nb =0;
	$n = count($cat_troph);
	$i=0;
	$colonne = ceil($n/5);	//affichage de 5 par colonne
	reset ($cat_troph);
	while (list($key, $val) = each($cat_troph))
		{
		$nb = $nb + 1;
		//$val2 = $val;
		//if($val==null)$val = "null";
		if($val[0]==null)continue;
		if ($nb <= $colonne)
			{
			print ("<td><input type=\"Checkbox\" name=\"trophique[".$i."]\" value=\"".$val[0]."\" checked>".$val[1]."</td>");
			}
		else 	{
			print ("</tr><tr><td><input type=\"Checkbox\" name=\"trophique[".$i."]\" value=\"".$val[0]."\" checked>".$val[1]."</td>");
			$nb =1;
			}
		$i++;
		}
	if ($i%2 == 0)//modulo, pour savoir si $i est pair
	print ("</tr><tr><td><input type=\"Checkbox\" name=\"trophique[100]\" value=\"null\" checked>non renseigné</td>");
	else print ("<td><input type=\"Checkbox\" name=\"trophique[100]\" value=\"null\" checked>non renseigné</td>");
		
	print ("</tr></table>");
	

	
	print ("<br><br><input type=\"submit\" name=\"\" value=\"    Suite    \">");
	print ("</form>");



	}//fin du if $requete_faite !=1
else if($selection_faite !=1)
	{
	////////////////////////////////////////////////////////
	//on trie les lignes du resultat en fonction des choix//
	////////////////////////////////////////////////////////
	
	$file="selection_nt_pt.txt";
	$fpm2 = fopen($file, "r");
	
	
	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	while ($ligne=fgets($fpm2,10000))
		{
		//print("<br>!!!!".$ligne);		//ok
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm2);

	reset($tab_ligne);


	//////on réecrit les lignes en ayant enlever celle non désirées
	$compt = 0;
	$nombre =0;//a enlever
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		//on garde la premiere ligne correspondant aux intitulés
		if ($compt == 0){$compt++; continue;}
		reset ($ligne_contient);
		reset ($_POST['ecologique']);
		reset ($_POST['trophique']);

	//pour la categorie ecologique, les valeurs doivent etre une du tableau $_POST['ecologique']
	 if (!in_array (trim($ligne_contient[90]), $_POST['ecologique']))
			{
			if ($_POST['ecologique'][100] != "null")unset($tab_ligne[$compt]);
			}
		//pour la categorie trophique, les valeurs doivent etre une du tableau $_POST['trophique']
		else if (!in_array ($ligne_contient[92], $_POST['trophique']))
			{
			//print ("<br><br>!!!trophique: ".$val_ligne." , ".$compt);
			if ($_POST['trophique'][100] != "null")unset($tab_ligne[$compt]);
			}
		//pour les poisson
		//if (($_POST['poisson']=="oui")&&($_POST['non_poisson']=="oui"))continue;
		else if (($_POST['poisson']=="oui")&&($_POST['non_poisson']=="non"))
			{
			if ($ligne_contient[97] != 0)
				{
				//print ("<br><br>!!!non poisson : ".$val_ligne." , ".$compt);
				unset($tab_ligne[$compt]);
				}
			}
		else if (($_POST['poisson']=="non")&&($_POST['non_poisson']=="oui"))
			{
			if ($ligne_contient[97] != 1)
				{
				unset($tab_ligne[$compt]);
				}
			}
		else if (($_POST['poisson']=="non")&&($_POST['non_poisson']=="non"))unset($tab_ligne[$compt]);//pas poisson ni non poisson
		$compt++;
		}
	
	//on reecrit les lignes restantes
	reset($tab_ligne);
	$n = count($tab_ligne);

	
	$fpm = fopen($file, "w+");
	
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		fputs($fpm,$val_ligne."\n");
		}
	fclose($fpm);


	
	
	///////////////////////////////////////////////
	//choix des especes sur la selection restante//
	///////////////////////////////////////////////
	reset ($tab_ligne);
	$tab_espece = Array();
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		$espece = $ligne_contient[81];
		$famille = $ligne_contient[95];
		if (trim($espece) != "espece")
			{
			if (isset ($tab_espece[$espece])) continue;
			else $tab_espece[$espece]=$famille;
			}
		}
	asort($tab_espece);
	$selection_faite =1;
	
	print ("<div align='center'><br>");
	

	
	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	print ("<input type=hidden name=\"selection_faite\" value=\"".$selection_faite."\">");

	print ("Sélection des espèces<br><br>");
	
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
			print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
		
	
	
	
	print ("<br><table BORDER=1 CELLSPACING=2 CELLPADDING=1>");
	print ("<tr>");
	$nb =0;
	$n = count($tab_espece);
	$i=0;

	$colonne = 3;
	reset ($tab_espece);
	while (list($key_esp, $val_esp) = each($tab_espece))
		{
		$nb = $nb + 1;
		if($key_esp==null)continue;
		if ($nb <= $colonne)
			{
			print ("<td><input type=\"Checkbox\" name=\"espece[".$i."]\" value=\"".$key_esp."\" >".$key_esp." (".$val_esp.")</td>");
			}
		else 	{
			print ("</tr><tr><td><input type=\"Checkbox\" name=\"espece[".$i."]\" value=\"".$key_esp."\" >".$key_esp." (".$val_esp.")</td>");
			$nb =1;
			}
		$i++;
		}
	print ("</tr></table>");
	print ("<br><br><input type=\"submit\" name=\"\" value=\"    Suite    \">");
	print ("</form>");
	}

else if($colonnes_faites !=1) //selection espece faite
	{
	$colonnes_faites=1;
	$file="selection_nt_pt.txt";
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
	

	reset($tab_ligne);
	//////on reecrit les lignes en ayant enlever celle non désirées
	$compt = 0;
	$nombre =0;//a enlever
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		//on garde la premiere ligne correspondant aux intitulés
		if ($compt == 0){$compt++; continue;}
		reset ($ligne_contient);
		reset ($_POST['espece']);
		
		//pour les especes, seules les valeurs de $espece contenu dans $_POST['espece'] doivent rester
		if (!in_array ($ligne_contient[81], $_POST['espece']))
			{
			//print ("<br><br>!!!espece :".$val_ligne." , ".$compt);
			unset($tab_ligne[$compt]);
			}
		$compt++;
		}
	
	//on reecrit les lignes restantes
	reset($tab_ligne);
	$n = count($tab_ligne);

	$fpm = fopen($file, "w+");
	
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		fputs($fpm,$val_ligne."\n");
		}
	fclose($fpm);
	
	//print ("<div align='center'>Le fichier texte comporte <Font Color =\"#333366\">");
	//print (($n-1)."</font> lignes :<br>");
	print ("<div align='center'><br>");
	
	print ("<br><b>Sélection des champs optionnels</b>
	<br><br>Vous pouvez cliquez sur le nom d'une table pour la développer
	<br>Les valeurs classiques sont sélectionnées par défaut");
	
	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	print ("<input type=hidden name=\"selection_faite\" value=\"".$selection_faite."\">");
	print ("<input type=hidden name=\"colonnes_faites\" value=\"".$colonnes_faites."\">");

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
<?php   print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays_lib</td>");//nom pays
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
	//print ("</tr><tr><td>Champs facultatifs du système</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst_lib</td>");
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
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>agglo_lib</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[8]\" value=\"20\"></td><td>type_agglo</td>");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	
	
	
	////////////table unite peche et capture
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_up').style.display = 'block';"><b>Unité de pêche</b>
</div>

<div id="_up" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>grd_type_engin</td>");
	print ("<input type=hidden name=\"voir[26]\" value=\"58\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[27]\" value=\"59\" ></td><td>grd_type_engin_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[28]\" value=\"69\" ></td><td>type_engin</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[29]\" value=\"71\" ></td><td>type_engin_lib</td>");

	
	
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_up').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_cap').style.display = 'block';"><b>Captures</b>
</div>

<div id="vue_cap" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"300\">");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"45\" ></td><td>dbq_date</td>");
	print ("<td WIDTH=30>x</td><td>dbq_an</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"38\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"39\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[13]\" value=\"32\" ></td><td>dbq_heure</td>");
	
	
	if($type_donnees=="brutes")
		{
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_pt</td>");
	print ("<input type=hidden name=\"voir[12]\" value=\"35\">");
		}
	if($type_donnees=="elaboree")
		{
		print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_pt</td>");
		print ("<input type=hidden name=\"voir[12]\" value=\"101\">");
		}
	
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[113]\" value=\"34\" ></td><td>nb_coups</td>");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[116]\" value=\"42\" ></td><td>dbq_nb_hom</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"43\" ></td><td>dbq_nb_fem</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"44\" ></td><td>dbq_nb_enf</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"67\" ></td><td>csp</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"68\" ></td><td>csp_lib</td>");
	
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"36\" ></td><td>glacière</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"54\" ></td><td>lieu_peche</td>");
	
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[20]\" value=\"50\" ></td><td>type_sortie</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[21]\" value=\"51\" ></td><td>type_sortie_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[22]\" value=\"52\" ></td><td>milieu</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[122]\" value=\"53\" ></td><td>milieu_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"46\" ></td><td>vent</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[123]\" value=\"47\" ></td><td>vent_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[24]\" value=\"48\" ></td><td>etat_ciel</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"49\" ></td><td>etat_ciel_lib</td>");
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('vue_cap').style.display = 'none';\">fermer</td>");
	
	
	
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	

	//table fraction et espece 
	
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?><div onClick="document.getElementById('_fr').style.display = 'block';"><b>Fractions et espèces</b>
</div>
	

<div id="_fr" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	if($type_donnees=="brutes")
		{
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>fdbq_nt</td>");
	print ("<input type=hidden name=\"voir[100]\" value=\"75\">");
	
	print ("<td WIDTH=30>x</td><td>fdbq_pt</td>");
	print ("<input type=hidden name=\"voir[101]\" value=\"74\">");
}
	if($type_donnees=="elaboree")
		{
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>fdbq_nt</td>");
	print ("<input type=hidden name=\"voir[100]\" value=\"105\">");
	print ("<td WIDTH=30>x</td><td>fdbq_pt</td>");
	print ("<input type=hidden name=\"voir[101]\" value=\"104\">");
}
	
	
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>code_sp</td>");
	print ("<input type=hidden name=\"voir[30]\" value=\"80\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[31]\" value=\"81\" ></td><td>espece</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>cat_ecol</td>");
	print ("<input type=hidden name=\"voir[32]\" value=\"90\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[13]\" value=\"91\" ></td><td>cat_ecol_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>cat_troph</td>");
	print ("<input type=hidden name=\"voir[33]\" value=\"92\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[34]\" value=\"93\" ></td><td>cat_troph_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[35]\" value=\"95\" ></td><td>famille</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[400]\" value=\"99\" ></td><td>ordre</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[35]\" value=\"76\" ></td><td>deb</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[401]\" value=\"79\" ></td><td>prix</td>");
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('_fr').style.display = 'none';\">fermer</td>");
	
	
	
	
	
	
	print ("</tr></table>");
?></div> <?php
	print ("</tr></table>");

	print ("<input type=hidden name=\"voir[200]\" value=\"0\">");//pour l'identifiant debarquement 
	print ("<input type=hidden name=\"voir[500]\" value=\"72\">");//pour l'identifiant fraction
	print ("<br><br><br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</form></div>");
	}
	else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="selection_nt_pt.txt";
	$fpm = fopen($file, "r");
	$nombre_ligne=0;
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
	
	$non_doublon=Array();
	
	//ouverture fichier pour ecriture en local
	$file="selection_nt_pt.txt";
	$fpm = fopen($file, "w+");
	reset($tab_ligne);
	$compt = 0;
	$deb=0;
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
		$nouveau = substr($nouveau, 0, -1);//on enleve le dernier \t
		$nouveau .= "\n";
		//print("<br>!!!!".$nouveau);
		
		
		//pour chaque deb, on vérifiera les doublons
		
		if ($ligne_contient[0]!=$deb)$non_doublon=Array();
		
		
		//on enleve les doublons
		if (!in_array ($nouveau,$non_doublon))
			{
			fputs($fpm,$nouveau);
			$non_doublon [$nombre_ligne]= $nouveau;
			$nombre_ligne++;
			}
		$deb=$ligne_contient[0];
		}
	fclose($fpm);
		
		
		
		
		
		

//compression du fichier pour le telechargement
$filename = './selection_nt_pt.txt';

// ouverture du fichier à compresser
if($fp = fopen($filename, "rb"))
	{
	// lecture du contenu
	$size1 = filesize($filename);
	print "size=".$size1."<br/>";
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
print ("<div align='center'><br><br><br>");

print ("<br>La sélection représente ".($nombre_ligne -1)." lignes dans le fichier de sortie.
<br>Vous devez sauvegarder ce fichier sur votre ordinateur pour ne pas perdre la sélection en cours.<br>Cliquez sur le lien pour l'enregistrement.");


print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_nt_pt.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");

?>
<SCRIPT LANGUAGE="JavaScript"> 
function fermer() {
if(confirm("Etes vous sûr ?"))window.close();}
</script>

<?php

print("<div align='center'><br><br>");
print("<input type='button' value='Fermer' onClick= 'fermer()'   name=\"button\">"); 

print("</div>");
	}
break;
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

case "     Taille     ":

print("<div align='center'>");
print("<Font Color =\"#333366\">");
print("<b>Base de Données PPEAO</b><br><br>Filière sur les structures de tailles<br><br>");
print("</div></Font>");




if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	$query_globale = "";
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}

	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur, 
	art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_debarquement 
	left join art_vent on art_debarquement.art_vent_id=art_vent.id 
	left join art_etat_ciel on art_debarquement.art_etat_ciel_id=art_etat_ciel.id 
	left join art_type_sortie on art_debarquement.art_type_sortie_id=art_type_sortie.id 
	left join art_millieu on art_debarquement.art_millieu_id=art_millieu.id 
	left join art_lieu_de_peche on art_debarquement.art_lieu_de_peche_id=art_lieu_de_peche.id 
	left join art_grand_type_engin on art_debarquement.art_grand_type_engin_id=art_grand_type_engin.id 
	left join (art_unite_peche
	left join art_categorie_socio_professionnelle on art_unite_peche.art_categorie_socio_professionnelle_id=art_categorie_socio_professionnelle.id)
	on art_debarquement.art_unite_peche_id=art_unite_peche.id 
	, art_type_engin 
	, art_fraction 
	left join (ref_espece 
	left join ref_categorie_ecologique on ref_espece.ref_categorie_ecologique_id=ref_categorie_ecologique.id 
	left join ref_categorie_trophique on ref_espece.ref_categorie_trophique_id=ref_categorie_trophique.id 
	left join (ref_famille left join ref_ordre on ref_famille.ref_ordre_id=ref_ordre.id) on ref_espece.ref_famille_id=ref_famille.id 
	)on art_fraction.ref_espece_id=ref_espece.id 
	, art_poisson_mesure, art_debarquement_rec, art_fraction_rec, art_engin_peche 
	where ref_pays.id=ref_systeme.ref_pays_id 
	
	and art_debarquement.id=art_engin_peche.art_debarquement_id 
	and art_engin_peche.art_type_engin_id=art_type_engin.id 
	
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_debarquement.art_agglomeration_id 
	
	and art_debarquement.id=art_fraction.art_debarquement_id 
	and art_fraction.id=art_poisson_mesure.art_fraction_id 
	and art_debarquement.id = art_debarquement_rec.art_debarquement_id 
	and art_fraction.id = art_fraction_rec.id ";

	
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
	
	
	$nb_engin = count ($_POST['engin']);
	//print ("!!!!!!".$nb_secteur);
	reset($_POST['engin']);
	if ($nb_engin == 1)$query_globale .= "and art_grand_type_engin.id = '".$_POST['engin'][0]."' ";
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
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));
	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_debarquement.annee =".$key." ";
				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_debarquement.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
		
		
	$query_globale .= " 
	order by ref_pays.id asc, ref_systeme.id asc, art_agglomeration.nom, art_debarquement.annee asc 
	,art_debarquement.mois asc ,art_debarquement.id asc, art_debarquement.art_grand_type_engin_id, art_fraction.id, art_fraction.ref_espece_id ";
	
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);
	//print("<br>§§§§§§§§§§§".pg_num_rows($result));
	//exit;
	
	
	////////////////////////////////////////////////
	//ecriture des resultats dans un fichier texte//
	////////////////////////////////////////////////
	$file="selection_taille.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	if($type_donnees=="brutes")$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t refpaysid\t syst_surf\t idsecteur\t sect\t sect_lib\t sect_surf\t refsystemeid\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t mmagglo\t type_agglo\t libellé type\t iddeb\t milieu\t vent\t etat_ciel\t agglo\t lieu_peche\t up\t grd_type_engin\t type_sortie\t dbq_date_dep\t dbq_heure_deb\t dbq_heure\t dbq_heure_pose_engin\t nb_coups\t dbq_pt\t glaciere\t dbq_liste_lieu_peche\t dbq_an\t dbq_mois\t mmmemo\t cccccode\t dbq_nb_hom\t dbq_nb_fem\t dbq_nb_enf\t dbq_date\t vent\t vent_lib\t etat_ciel\t etat_ciel_lib\t type_sirtie\t type_sortie_lib\t milieu\t milieu_lib\t lieu_peche\t sect\t lieu_peche_lib\t cccode\t grd_type_engin\t grd_type_engin_lib\t up\t csp\t up_lib\t up_lib_menage\t cccoode\t agglo\t bbbasepays\t csp\t csp_lib\t type_engin\t grd_type_engin\t type_engin_lib\t fraction\t cooode\t fdbq_pt\t fdbq_nt\t fdbq_observee\t code_sp\t artdebid\t fdbq_prix\t codesp\t espece\t informationespece\t reffamilleid\t cat_ecol\t cat_troph\t coefK\t coefb\t reforiginekbid\t refespeceid\t cat_ecol\t cat_ecol_lib\t cat_troph\t cat_troph_lib\t idfamille\t famille\t refordreid\t non_poisson\t idordre\t ordre\t id_mes\t cccode\t long_lf\t art_fractionid\n";
	if($type_donnees=="elaboree")$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t refpaysid\t syst_surf\t idsecteur\t sect\t sect_lib\t sect_surf\t refsystemeid\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t mmagglo\t type_agglo\t libellé type\t iddeb\t milieu\t vent\t etat_ciel\t agglo\t lieu_peche\t up\t grd_type_engin\t type_sortie\t dbq_date_dep\t dbq_heure_deb\t dbq_heure\t dbq_heure_pose_engin\t nb_coups\t dbq_pt\t glaciere\t dbq_liste_lieu_peche\t dbq_an\t dbq_mois\t mmmemo\t cccccode\t dbq_nb_hom\t dbq_nb_fem\t dbq_nb_enf\t dbq_date\t vent\t vent_lib\t etat_ciel\t etat_ciel_lib\t type_sirtie\t type_sortie_lib\t milieu\t milieu_lib\t lieu_peche\t sect\t lieu_peche_lib\t cccode\t grd_type_engin\t grd_type_engin_lib\t up\t csp\t up_lib\t up_lib_menage\t cccoode\t agglo\t bbbasepays\t csp\t csp_lib\t type_engin\t grd_type_engin\t type_engin_lib\t fraction\t cooode\t fdbq_pt\t fdbq_nt\t fdbq_observee\t code_sp\t artdebid\t fdbq_prix\t codesp\t espece\t informationespece\t reffamilleid\t cat_ecol\t cat_troph\t coefK\t coefb\t reforiginekbid\t refespeceid\t cat_ecol\t cat_ecol_lib\t cat_troph\t cat_troph_lib\t idfamille\t famille\t refordreid\t non_poisson\t idordre\t ordre\t id_mes\t cccode\t long_lf\t art_fractionid\t id\t poids_total\t id\t fraction\t fdbq\t ndbq\t esp\n";

	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);
	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		$contenu.= $row[20]."\t";
		
		if($type_donnees=="brutes")$iii=103;
		if($type_donnees=="elaboree")$iii=110;
		//si $row[0]different, numero +1
		for ($i=0; $i<$iii; $i++)	//il y a 103 champs dans la requete
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
	///////////////////////////////////////
	print ("<div align='center'>");
	print ("Le fichier texte comporte ".($k-1)." lignes de tailles");//car 1ere ligne est un intitulé
	
	//pg_free_result();
	//pg_close();
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte
	///////////////////////////////////////////////////////////

	print ("<br><br><Font Color =\"#333366\">");
	print ("Critère de selection :");

	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	
	//renseignements sur les champs optionnels de la selection d'espece
	print("<table BORDER=1 CELLSPACING=2 CELLPADDING=1>");
	print ("<tr>");
	print ("<td>Voulez vous les poissons</td>");
	print ("<td><input type=\"radio\" name=\"poisson\" value=\"oui\"checked>oui</td>");
	print ("<td><input type=\"radio\" name=\"poisson\" value=\"non\">non</td>");
	print ("</tr><tr>");
	
	print ("<td>Voulez vous les non-poissons</td>");
	print ("<td><input type=\"radio\" name=\"non_poisson\" value=\"oui\">oui</td>");
	print ("<td><input type=\"radio\" name=\"non_poisson\" value=\"non\"checked>non</td>");
	print ("</tr><tr>");

	print ("</tr></table>");
	//print("<br>".$choix);
	
	print ("<br><br><table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
	print ("<td ROWSPAN=5 align=center>Quelle(s) catégorie(s) écologiques</td>");
		
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}
	$query = "select distinct ref_categorie_ecologique.id, ref_categorie_ecologique.libelle from ref_espece, ref_categorie_ecologique 
	where ref_espece.ref_categorie_ecologique_id = ref_categorie_ecologique.id
		
	";
	//print ($query);
	$result = pg_query($connection, $query);
		
	$cat_ecol=Array();
	$i = 0;
	while($row = pg_fetch_row($result))
		{
		//if($row[0]==null)$cat_ecol[$i] = "null";
		if($row[0]==null)continue;
		$cat_ecol[$i][0] = $row[0];
		$cat_ecol[$i][1] = $row[1];
		$i++;
		}
	// Deconnexion de la base de donnees
	//pg_close();
	$nb =0;
	$n = count($cat_ecol);
	$i=0;
	$colonne = ceil($n/5);	//affichage de 5 par colonne
	reset ($cat_ecol);
	while (list($key, $val) = each($cat_ecol))
		{
		$nb = $nb + 1;
		//$val2 = $val;
		//if($val==null)$val = "null";
		if($val[0]==null)continue;
		if ($nb <= $colonne)
			{
			print ("<td><input type=\"Checkbox\" name=\"ecologique[".$i."]\" value=\"".$val[0]."\"checked>".$val[1]."</td>");
			}
		else 	{
			print ("</tr><tr><td><input type=\"Checkbox\" name=\"ecologique[".$i."]\" value=\"".$val[0]."\"checked>".$val[1]."</td>");
			$nb =1;
			}
		$i++;
		}
		if ($i%2 == 0)//modulo, pour savoir si $i est pair
		print ("</tr><tr><td><input type=\"Checkbox\" name=\"ecologique[100]\" value=\"null\" checked>non renseigné</td>");
		else print ("<td><input type=\"Checkbox\" name=\"ecologique[100]\" value=\"null\" checked>non renseigné</td>");
		

	
	
	print ("</tr><tr>");
	print ("<td ROWSPAN=5 align=center>Quelle(s) catégorie(s) trophiques</td>");
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}
	$query = "select distinct ref_categorie_trophique.id, ref_categorie_trophique.libelle from ref_espece, ref_categorie_trophique 
	where ref_espece.ref_categorie_trophique_id = ref_categorie_trophique.id
	";
			//print ($query);
		
	$cat_troph=Array();
	$result=Array();
	$result = pg_query($connection, $query);
	$i = 0;
	while($row = pg_fetch_row($result))
		{
		//if($row[0]==null)$cat_troph[$i] = "null";
		//else $cat_troph[$i] = $row[0];
		$cat_troph[$i][0] = $row[0];
		$cat_troph[$i][1] = $row[1];
		$i++;
		}
	// Deconnexion de la base de donnees
	//pg_close();
	$nb =0;
	$n = count($cat_troph);
	$i=0;
	$colonne = ceil($n/5);	//affichage de 5 par colonne
	reset ($cat_troph);
	while (list($key, $val) = each($cat_troph))
		{
		$nb = $nb + 1;
		//$val2 = $val;
		//if($val==null)$val = "null";
		if($val[0]==null)continue;
		if ($nb <= $colonne)
			{
			print ("<td><input type=\"Checkbox\" name=\"trophique[".$i."]\" value=\"".$val[0]."\" checked>".$val[1]."</td>");
			}
		else 	{
			print ("</tr><tr><td><input type=\"Checkbox\" name=\"trophique[".$i."]\" value=\"".$val[0]."\" checked>".$val[1]."</td>");
			$nb =1;
			}
		$i++;
		}
	if ($i%2 == 0)//modulo, pour savoir si $i est pair
	print ("</tr><tr><td><input type=\"Checkbox\" name=\"trophique[100]\" value=\"null\" checked>non renseigné</td>");
	else print ("<td><input type=\"Checkbox\" name=\"trophique[100]\" value=\"null\" checked>non renseigné</td>");
	
	print ("</tr></table>");
	

	
	print ("<br><br><input type=\"submit\" name=\"\" value=\"    Suite    \">");
	print ("</form>");



	}//fin du if $requete_faite !=1
else if($selection_faite !=1)
	{
	/////////////////////////////////////////////
	//on trie les lignes du resultat en fonction des choix
	/////////////////////////////////////////////
	
	$file="selection_taille.txt";
	$fpm2 = fopen($file, "r");
	
	
	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	while ($ligne=fgets($fpm2,10000))
		{
		//print("<br>!!!!".$ligne);		//ok
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm2);

	reset($tab_ligne);


	//////on reecrit les lignes en ayant enlever celle non désirées
	$compt = 0;
	$nombre =0;//a enlever
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		//on garde la premiere ligne correspondant aux intitulés
		if ($compt == 0){$compt++; continue;}
		reset ($ligne_contient);
		reset ($_POST['ecologique']);
		reset ($_POST['trophique']);

	//pour la categorie ecologique, les valeurs doivent etre une du tableau $_POST['ecologique']
	 if (!in_array (trim($ligne_contient[90]), $_POST['ecologique']))
			{
			//print ("<br><br>!!!ecologique: ".$val_ligne." , ".$compt);
			if ($_POST['ecologique'][100] != "null")unset($tab_ligne[$compt]);
			}
		//pour la categorie trophique, les valeurs doivent etre une du tableau $_POST['trophique']
		else if (!in_array ($ligne_contient[92], $_POST['trophique']))
			{
			//print ("<br><br>!!!trophique: ".$val_ligne." , ".$compt);
			if ($_POST['trophique'][100] != "null")unset($tab_ligne[$compt]);
			}
		//pour les poisson
		//if (($_POST['poisson']=="oui")&&($_POST['non_poisson']=="oui"))continue;
		else if (($_POST['poisson']=="oui")&&($_POST['non_poisson']=="non"))
			{
			if ($ligne_contient[97] != 0)
				{
				//print ("<br><br>!!!non poisson : ".$val_ligne." , ".$compt);
				unset($tab_ligne[$compt]);
				}
			}
		else if (($_POST['poisson']=="non")&&($_POST['non_poisson']=="oui"))
			{
			if ($ligne_contient[97] != 1)
				{
				//print ("<br><br>!!!poisson: ".$val_ligne." , ".$compt);
				unset($tab_ligne[$compt]);
				}
			}
		else if (($_POST['poisson']=="non")&&($_POST['non_poisson']=="non"))unset($tab_ligne[$compt]);//pas poisson ni non poisson
		$compt++;
		}
	
	//on reecrit les lignes restantes
	reset($tab_ligne);
	$n = count($tab_ligne);
	
	$fpm = fopen($file, "w+");
	
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		fputs($fpm,$val_ligne."\n");
		}
	fclose($fpm);


	
	
	/////////////////////////////////////////////
	//choix des especes sur la selection restante
	////////////////////////////////////////////
	reset ($tab_ligne);
	$tab_espece = Array();
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		$espece = $ligne_contient[81];
		$famille = $ligne_contient[95];
		if (trim($espece) != "espece")
			{
			if (isset ($tab_espece[$espece])) continue;
			else $tab_espece[$espece]=$famille;
			}
		}
	asort($tab_espece);
	$selection_faite =1;
	
	//////////affichage du resultat dans un formulaire

	print ("<div align='center'><br>");

	
	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	print ("<input type=hidden name=\"selection_faite\" value=\"".$selection_faite."\">");
	
	print ("Sélection des espèces<br><br>");
	
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
			print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
	
	
	print ("<br><table BORDER=1 CELLSPACING=2 CELLPADDING=1>");
	print ("<tr>");
	$nb =0;
	$n = count($tab_espece);
	$i=0;

	$colonne = 3;
	reset ($tab_espece);
	while (list($key_esp, $val_esp) = each($tab_espece))
		{
		$nb = $nb + 1;
		if($key_esp==null)continue;
		if ($nb <= $colonne)
			{
			print ("<td><input type=\"Checkbox\" name=\"espece[".$i."]\" value=\"".$key_esp."\" >".$key_esp." (".$val_esp.")</td>");
			}
		else 	{
			print ("</tr><tr><td><input type=\"Checkbox\" name=\"espece[".$i."]\" value=\"".$key_esp."\" >".$key_esp." (".$val_esp.")</td>");
			$nb =1;
			}
		$i++;
		}
	print ("</tr></table>");
	print ("<br><br><input type=\"submit\" name=\"\" value=\"    Suite    \">");
	print ("</form>");
	}

else if($colonnes_faites !=1) //selection espece faite
	{
	$colonnes_faites=1;
	$file="selection_taille.txt";
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
	

	reset($tab_ligne);
	//////on reecrit les lignes en ayant enlever celle non désirées
	$compt = 0;
	$nombre =0;//a enlever
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		$ligne_contient = Array();
		$ligne_contient = explode ("\t",$val_ligne);
		//on garde la premiere ligne correspondant aux intitulés
		if ($compt == 0){$compt++; continue;}
		reset ($ligne_contient);
		reset ($_POST['espece']);
		
		//pour les especes, seules les valeurs de $espece contenu dans $_POST['espece'] doivent rester
		if (!in_array ($ligne_contient[81], $_POST['espece']))
			{
			unset($tab_ligne[$compt]);
			}
		$compt++;
		}
	
	//on reecrit les lignes restantes
	reset($tab_ligne);
	$n = count($tab_ligne);
	
	$fpm = fopen($file, "w+");
	
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		fputs($fpm,$val_ligne."\n");
		}
	fclose($fpm);
	
	//print ("<div align='center'>Le fichier texte comporte <Font Color =\"#333366\">");
	//print (($n-1)."</font> lignes :<br>");
	print ("<div align='center'>");
	
	print ("<br><b>Sélection des champs optionnels</b>
	<br><br>Vous pouvez cliquez sur le nom d'une table pour la développer
	<br>Les valeurs classiques sont sélectionnées par défaut");
	
	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	print ("<input type=hidden name=\"selection_faite\" value=\"".$selection_faite."\">");
	print ("<input type=hidden name=\"colonnes_faites\" value=\"".$colonnes_faites."\">");


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
<?php   print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays_lib</td>");//nom pays
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
	//print ("</tr><tr><td>Champs facultatifs du système</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst_lib</td>");
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
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>agglo_lib</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[8]\" value=\"20\"></td><td>type_agglo</td>");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	
	
	////////////table unite peche et capture
	print ("<br><table  BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?>
		<div onClick="document.getElementById('_up').style.display = 'block';"><b>Unité de pêche</b>
</div>

<div id="_up" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>grd_type_engin</td>");
	print ("<input type=hidden name=\"voir[26]\" value=\"58\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[27]\" value=\"59\" ></td><td>grd_type_engin_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[28]\" value=\"69\" ></td><td>type_engin</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[29]\" value=\"71\" ></td><td>type_engin_lib</td>");

	
	
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_up').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_cap').style.display = 'block';"><b>Captures</b>
</div>

<div id="vue_cap" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"300\">");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[9]\" value=\"45\" ></td><td>dbq_date</td>");
	print ("<td WIDTH=30>x</td><td>dbq_an</td>");
	print ("<input type=hidden name=\"voir[10]\" value=\"38\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"39\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[13]\" value=\"32\" ></td><td>dbq_heure</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_pt</td>");
	if($type_donnees=="brutes")print ("<input type=hidden name=\"voir[12]\" value=\"35\">");
	if($type_donnees=="elaboree")print ("<input type=hidden name=\"voir[12]\" value=\"105\">");

	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[13]\" value=\"34\" ></td><td>nb_coups</td>");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"42\" ></td><td>dbq_nb_hom</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[116]\" value=\"43\" ></td><td>dbq_nb_fem</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"44\" ></td><td>dbq_nb_enf</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"67\" ></td><td>csp</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"68\" ></td><td>csp_lib</td>");
	
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"36\" ></td><td>glacière</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"54\" ></td><td>lieu_peche</td>");
	
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[20]\" value=\"50\" ></td><td>type_sortie</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[21]\" value=\"51\" ></td><td>type_sortie_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[22]\" value=\"52\" ></td><td>milieu</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[122]\" value=\"53\" ></td><td>milieu_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"46\" ></td><td>vent</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[123]\" value=\"47\" ></td><td>vent_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[24]\" value=\"48\" ></td><td>etat_ciel</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"49\" ></td><td>etat_ciel_lib</td>");
	
	
	
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('vue_cap').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	

	//table fraction et espece 
	
	print ("<br><table  BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?>
	<div onClick="document.getElementById('_fr').style.display = 'block';"><b>Fractions et espèces</b>
</div>

<div id="_fr" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"300\">");
	if($type_donnees=="brutes")
		{
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>fdbq_nt</td>");
	print ("<input type=hidden name=\"voir[100]\" value=\"75\">");
	print ("<td WIDTH=30>x</td><td>fdbq_pt</td>");
	print ("<input type=hidden name=\"voir[101]\" value=\"74\">");
		}
		
	if($type_donnees=="elaboree")
		{
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>fdbq_nt</td>");
	print ("<input type=hidden name=\"voir[100]\" value=\"109\">");
	print ("<td WIDTH=30>x</td><td>fdbq_pt</td>");
	print ("<input type=hidden name=\"voir[101]\" value=\"108\">");
		}
	
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>code_sp</td>");
	print ("<input type=hidden name=\"voir[30]\" value=\"80\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[31]\" value=\"81\" ></td><td>espece</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>cat_ecol</td>");
	print ("<input type=hidden name=\"voir[32]\" value=\"90\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[113]\" value=\"91\" ></td><td>cat_ecol_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>cat_troph</td>");
	print ("<input type=hidden name=\"voir[33]\" value=\"92\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[34]\" value=\"93\" ></td><td>cat_troph_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[35]\" value=\"95\" ></td><td>famille</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[36]\" value=\"99\" ></td><td>ordre</td>");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[100]\" value=\"86\" ></td><td>coefK</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[101]\" value=\"87\" ></td><td>coefb</td>");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>long_lf</td>");
	print ("<input type=hidden name=\"voir[37]\" value=\"102\">");
	
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('_fr').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php
	print ("</tr></table>");

	print ("<input type=hidden name=\"voir[200]\" value=\"0\">");
	print ("<input type=hidden name=\"voir[500]\" value=\"72\">");//pour l'identifiant fraction
	print ("<input type=hidden name=\"voir[501]\" value=\"100\">");//pour l'identifiant taille
	print ("<br><br><br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</form></div>");
	}
	else
	{
	//////on enlève des colonnes en trop du fichier temp
	$file="selection_taille.txt";
	$fpm = fopen($file, "r");
	$nombre_ligne=0;
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
	
	$non_doublon=Array();
	
	//ouverture fichier pour ecriture en local?????
	$file="selection_taille.txt";
	$fpm = fopen($file, "w+");
	reset($tab_ligne);
	$compt = 0;
	$deb=0;
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
		$nouveau = substr($nouveau, 0, -1);//on enleve le dernier \t
		$nouveau .= "\n";
		//print("<br>!!!!".$nouveau);
		

		//pour chaque deb, on vérifiera les doublons
		
		if ($ligne_contient[0]!=$deb)$non_doublon=Array();
		
		
		//on enleve les doublons
		if (!in_array ($nouveau,$non_doublon))
			{
			fputs($fpm,$nouveau);
			$non_doublon [$nombre_ligne]= $nouveau;
			$nombre_ligne++;
			}
		$deb=$ligne_contient[0];
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './selection_taille.txt';

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
print ("<div align='center'><br><br>");

print ("<br>La sélection représente ".($nombre_ligne -1)." lignes dans le fichier de sortie.
<br>Vous devez sauvegarder ce fichier sur votre ordinateur pour ne pas perdre la sélection en cours.<br>Cliquez sur le lien pour l'enregistrement.");


print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_taille.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");

?>
<SCRIPT LANGUAGE="JavaScript"> 
function fermer() {
if(confirm("Etes vous sûr ?"))window.close();}
</script>

<?php

print("<div align='center'><br><br>");
print("<input type='button' value='Fermer' onClick= 'fermer()'   name=\"button\">"); 

print("</div>");
}

break;

//////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
case "    Engin     ":

print("<div align='center'>");
print("<Font Color =\"#333366\">");
print("<b>Base de Données PPEAO</b><br><br>Filière sur les engins de pêche<br><br>");
print("</div></Font>");





if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	$query_globale = "";
	//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
	//if (!$connection) { echo "Pas de connection"; exit;}

	$query_globale = " select * 
	from ref_pays, ref_systeme, ref_secteur, 
	art_agglomeration 
	left join art_type_agglomeration on art_agglomeration.art_type_agglomeration_id=art_type_agglomeration.id 
	, art_debarquement 
	left join art_grand_type_engin on art_debarquement.art_grand_type_engin_id=art_grand_type_engin.id 
	left join art_unite_peche on art_debarquement.art_unite_peche_id=art_unite_peche.id 
	, art_type_engin 
	, art_engin_peche 
	where ref_pays.id=ref_systeme.ref_pays_id 
	and ref_systeme.id=ref_secteur.ref_systeme_id 
	and ref_secteur.id=art_agglomeration.ref_secteur_id 
	and art_agglomeration.id=art_debarquement.art_agglomeration_id 
	
	and art_debarquement.id=art_engin_peche.art_debarquement_id 
	
	and art_type_engin.id = art_engin_peche.art_type_engin_id ";

	
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

	
	$nb_engin = count ($_POST['engin']);

	reset($_POST['engin']);
	if ($nb_engin == 1)$query_globale .= "and art_grand_type_engin.id = '".$_POST['engin'][0]."' ";
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
	
	reset($_POST['periode']);
	$nb_annee=count(array_keys($_POST['periode']));
	$query_globale .= " and (";
	while (list($key, $val) = each($_POST['periode']))
				{
				$query_globale .= " (art_debarquement.annee =".$key." ";
				$query_globale .= "and (";
				while (list($key2, $val2)= each($val))
					{
					$query_globale .= "(art_debarquement.mois = '".$val2."') or ";
					}
				$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
				$query_globale .= ")) or ";
			}
			$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
			$query_globale .= ") ";
		
		
	$query_globale .= " 
	order by ref_pays.id asc, ref_systeme.id asc, art_agglomeration.nom, art_debarquement.annee asc 
	,art_debarquement.mois asc ,art_debarquement.id asc , art_debarquement.art_grand_type_engin_id ";
	
	
	//print ("<br>".$query_globale);
	$result = pg_query($connection, $query_globale);

	
	
	////////////////////////////////////////////////
	//ecriture des resultats dans un fichier texte//
	////////////////////////////////////////////////
	$file="selection_engin.txt";
	$fpm = fopen($file, "w");
	$i = 0;
	$k=1;//nombre lignes
	$intitule = "numero\t pays\t pays_lib\t syst\t syst_lib\t refpaysid\t syst_surf\t idsecteur\t sect\t sect_lib\t sect_surf\t refsystemeid\t agglo\t type_agglo\t sect\t agglo_lib\t agglo_long\t agglo_lat\t mmagglo\t type_agglo\t libellé type\t iddeb\t milieu\t vent\t etat_ciel\t agglo\t lieu_peche\t up\t grd_type_engin\t type_sortie\t dbq_date_dep\t dbq_heure_deb\t dbq_heure\t dbq_heure_pose_engin\t nb_coups\t dbq_pt\t glaciere\t dbq_liste_lieu_peche\t dbq_an\t dbq_mois\t mmmemo\t cccccode\t dbq_nb_hom\t dbq_nb_fem\t dbq_nb_enf\t dbq_date\t grd_type_engin\t grd_type_engin_lib\t up\t csp\t up_lib\t up_lib_menage\t cccoode\t agglo\t bbbasepays\t type_engin\t grd_type_engin\t type_engin_lib\t idengin\t engin\t engin_long\t engin_haut\t engin_nb_nap\t engin_nb\t engin_nb_effort\t engin_maille_ham\t engin_nb_ham\t engin_proprietaire\t arttypeenginid\t artdebid\n";
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);
	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		//$contenu.= $k."\t";
		$contenu.= $row[20]."\t";
		
		//si $row[0]different, numero +1
		for ($i=0; $i<69; $i++)	//il y a 69 champs dans la requete
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
	///////////////////////////////////////
	print ("<div align='center'>");
	print ("La sélection comporte ".($k-1)." engins décrits");//car 1ere ligne est un intitulé
	if ((k-1)==0)print ("Attention, il n'y a pas d'engins référencés dans votre sélection de départ");
	
	//pg_free_result();
	//pg_close();
	////////////////////////////////
	$requete_faite = 1;

	///////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte///
	///////////////////////////////////////////////////


	print ("<br><br><Font Color =\"#333366\">");
	print ("<b>Sélection des champs optionnels</b>
	<br><br>Vous pouvez cliquez sur le nom d'une table pour la développer
	<br>Les valeurs classiques sont sélectionnées par défaut");
	
	print ("<form name=\"form\" method=\"post\" action=\"art_filieres.php\">");
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
<?php   print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[1]\" value=\"1\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays_lib</td>");//nom pays
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
	//print ("</tr><tr><td>Champs facultatifs du système</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst</td>");
	print ("<input type=hidden name=\"voir[3]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst_lib</td>");
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
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>agglo_lib</td>");
	print ("<input type=hidden name=\"voir[7]\" value=\"15\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[8]\" value=\"20\"></td><td>type_agglo</td>");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_agglo').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	
	
	
	////////////table debarquement et engin
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top><td VALIGN=top align = center WIDTH=\"200\">");
	?><div onClick="document.getElementById('_deb').style.display = 'block';"><b>Débarquement</b>
</div>

<div id="_deb" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq</td>");
	print ("<input type=hidden name=\"voir[9]\" value=\"21\">");
	print ("<td WIDTH=30>x</td><td>dbq_date</td>");//
	print ("<input type=hidden name=\"voir[10]\" value=\"45\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>dbq_mois</td>");
	print ("<input type=hidden name=\"voir[11]\" value=\"39\">");
	print ("<td WIDTH=30>x</td><td>dbq_an</td>");
	print ("<input type=hidden name=\"voir[12]\" value=\"38\">");
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('_deb').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php
	print ("</td><td VALIGN=top align = center WIDTH=\"200\">");
	
	?>
	<div onClick="document.getElementById('vue_engin').style.display = 'block';"><b>Engin de pêche</b>
</div>

<div id="vue_engin" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>up</td>");
	print ("<input type=hidden name=\"voir[13]\" value=\"27\">");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[14]\" value=\"50\" ></td><td>up_lib</td>");

	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"51\" ></td><td>up_lib_menage</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"46\" ></td><td>grd_type_engin</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"47\" ></td><td>grd_type_engin_lib</td>");
	print ("<td WIDTH=30>x</td><td>type_engin</td>");
	print ("<input type=hidden name=\"voir[18]\" value=\"55\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[19]\" value=\"57\" ></td><td>type_engin_lib</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[20]\" value=\"60\" ></td><td>engin_long</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[21]\" value=\"61\" ></td><td>engin_haut</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[22]\" value=\"62\" ></td><td>engin_nb_nap</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[23]\" value=\"63\" ></td><td>engin_nb</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[24]\" value=\"64\" ></td><td>engin_nb_effort</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"65\" ></td><td>engin_maille_ham</td>");
	print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[26]\" value=\"67\" ></td><td>engin_proprietaire</td>");
	print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('vue_engin').style.display = 'none';\">fermer</td>");
	
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td></tr></table>");
	
	
	
	print ("<input type=hidden name=\"voir[200]\" value=\"0\">");
	print ("<input type=hidden name=\"voir[500]\" value=\"59\">");//ident engin
	print ("<br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</form></div>");
	}
else
	{
		//////on enlève des colonnes en trop du fichier temp
	$file="selection_engin.txt";
	$fpm = fopen($file, "r");
	$nombre_ligne=0;
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
	
	$non_doublon=Array();
	
	//ouverture fichier pour ecriture en local?????
	$file="selection_engin.txt";
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
		$nouveau = substr($nouveau, 0, -1);//on enleve le dernier \t
		$nouveau .= "\n";
		
		//on enleve les doublons
		if (!in_array ($nouveau,$non_doublon))
			{
			fputs($fpm,$nouveau);
			$non_doublon [$nombre_ligne]= $nouveau;
			$nombre_ligne++;
			}
		}
	fclose($fpm);
		

//compression du fichier pour le telechargement
$filename = './selection_engin.txt';

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
print ("<div align='center'><br>");

print ("<br>La sélection représente ".($nombre_ligne -1)." lignes dans le fichier de sortie.
<br>Vous devez sauvegarder ce fichier sur votre ordinateur pour ne pas perdre la sélection en cours.<br>Cliquez sur le lien pour l'enregistrement.");

//print ("<br><br><a href=\"http://vmppeao.mpl.ird.fr/extraction/temp_selection_globale.txt.gz\"<b>Enregistrement du fichier texte</b></a>");
print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_engin.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

print ("</div>");

?>
<SCRIPT LANGUAGE="JavaScript"> 
function fermer() {
if(confirm("Etes vous sûr ?"))window.close();}
</script>

<?php

print("<div align='center'><br><br>");
//print("<input type='button' value='Fermer' onClick= 'confirm(\"Etes vous sûr ?\");' 'self.close();' name=\"button\">"); 
print("<input type='button' value='Fermer' onClick= 'fermer()'   name=\"button\">"); 
//print("<script language=JavaScript>window.close()</script>");

print("</div>");

	}
break;

}
pg_close();
?>
</body>
</html>