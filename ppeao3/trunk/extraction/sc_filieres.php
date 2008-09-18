
<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">
</head>
<body BGCOLOR="#CCCCFF">

<?php

//if(! ini_set("max_execution_time", "320")) {echo "échec max_execution_time";}
include_once("../connect.inc");
$connection = pg_connect ("host=".$host." dbname=".$db_default." user=".$user." password=".$passwd);
if (!$connection) { echo "Pas de connection"; exit;}








$choix = $_POST['choix'];


print("<div align='center'>");
print("<Font Color =\"#333366\">");
print("<br><h3><b>Filière ".$choix."</b></h3>");
print("</div></Font>");


//pour faire apparaitre les données transmisent en post

/*while (list($key, $val) = each($_POST))
	{
	if ($key == 'secteur')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
	else if ($key == 'campagne')while (list($key2, $val2) = each($val))print("<br>!!!".$key." : ".$val2);
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
//print_r($_POST);

$requete_faite = $_POST['requete_faite'];
$selection_faite = $_POST['selection_faite'];
$colonnes_faites = $_POST['colonnes_faites'];




/////////////////////////////////////////////////////////////////////////////////////
//fabrication de la requete globale recueillant les informations apres preselection//
/////////////////////////////////////////////////////////////////////////////////////

if ($requete_faite != 1)		//si requete globale pas encore faite
	{
	$query_globale = "";



	
	$query_globale = " select * 
	from ref_pays, ref_systeme, exp_campagne, ref_secteur, exp_station
		left join exp_vegetation on exp_station.exp_vegetation_id=exp_vegetation.id 
		left join exp_debris on  exp_station.exp_debris_id=exp_debris.id 
		left join exp_sediment on exp_station.exp_sediment_id = exp_sediment.id 
		left join exp_position on exp_station.exp_position_id = exp_position.id 
	,	(exp_coup_peche left join exp_environnement on exp_coup_peche.exp_environnement_id=exp_environnement.id) 
			left join exp_sens_courant on exp_environnement.exp_sens_courant_id=exp_sens_courant.id 
			left join exp_force_courant on exp_environnement.exp_force_courant_id=exp_force_courant.id 

	, exp_engin, exp_qualite, exp_fraction, ref_espece
	left join ref_categorie_ecologique 
	on ref_espece.ref_categorie_ecologique_id=ref_categorie_ecologique.id
	left join ref_categorie_trophique 
	on ref_espece.ref_categorie_trophique_id=ref_categorie_trophique.id

	, ref_famille 
		left join ref_ordre on ref_famille.ref_ordre_id=ref_ordre.id ";
	
	
	if(($choix == "     Biologie     ")||($choix == "    Trophique     "))
		{
		$query_globale .= " ,exp_biologie left join exp_sexe on exp_biologie.exp_sexe_id = exp_sexe.id 
			left join exp_remplissage on exp_biologie.exp_remplissage_id = exp_remplissage.id 
			left join exp_stade on exp_biologie.exp_stade_id = exp_stade.id ";
		}
	if($choix == "    Trophique     ")$query_globale.= ",exp_trophique 
left join exp_contenu on exp_trophique.exp_contenu_id=exp_contenu.id ";

	$query_globale.= "
	where ref_pays.id = ref_systeme.ref_pays_id 
	and ref_secteur.ref_systeme_id = ref_systeme.id ";
	
	if(($choix == "     Biologie     ")||($choix == "    Trophique     "))
		$query_globale.= " and exp_biologie.exp_fraction_id=exp_fraction.id ";
	if($choix == "    Trophique     ")$query_globale.= " and exp_trophique.exp_biologie_id= exp_biologie.id ";

	$nb_campagne = count ($_POST['campagne']);

	reset($_POST['campagne']);
	if ($nb_secteur == 1)$query_globale .= "and exp_campagne.id = ".$_POST['campagne'][0]." ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['campagne']))
			{
			$query_globale .= "(exp_campagne.id = ".$val.") or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	
	$query_globale .= " and exp_campagne.ref_systeme_id = ref_systeme.id ";
	$nb_secteur = count ($_POST['secteur']);

	reset($_POST['secteur']);
	if ($nb_secteur == 1)$query_globale .= "and ref_secteur.nom = '".$_POST['secteur'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['secteur']))
			{
			$query_globale .= "(ref_secteur.nom = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	$query_globale .= " and ref_secteur.ref_systeme_id = ref_systeme.id 
	and exp_station.ref_secteur_id = ref_secteur.id 
	and exp_coup_peche.exp_station_id = exp_station.id 
	and exp_coup_peche.exp_campagne_id = exp_campagne.id 
	and exp_coup_peche.exp_qualite_id = exp_qualite.id 
	and exp_engin.id = exp_coup_peche.exp_engin_id ";
	
	$nb_engin = count ($_POST['engin']);

	reset($_POST['engin']);
	if ($nb_secteur == 1)$query_globale .= "and exp_engin.id = '".$_POST['engin'][0]."' ";
	else
		{
		$query_globale .= "and (";
		while (list($key, $val) = each($_POST['engin']))
			{
			$query_globale .= "(exp_engin.id = '".$val."') or ";
			}
		$query_globale = substr($query_globale, 0, -3); 		//on enleve le dernier or
		$query_globale .= ") ";
		}
	$query_globale .= " and exp_fraction.exp_coup_peche_id=exp_coup_peche.id 
	and ref_espece.id=exp_fraction.ref_espece_id 
	and ref_famille.id=ref_espece.ref_famille_id 
	order by ref_pays.id asc, ref_systeme.id asc, 
	exp_campagne.date_debut asc,  exp_coup_peche.id asc 
	 , ref_espece.id , exp_fraction.id asc ";    //exp_campagne.date_debut asc,
	
	
	//print ("<br>".$query_globale);
	$result=Array();
	$result = pg_query($connection, $query_globale);

	
	
	
	
	
	///////////////////////////////////////
	//ecriture des resultats dans un fichier texte
	//////////////////////////////////////

	if ($choix==" Peuplement ")$file="selection_peupl.txt";
	else if ($choix == "Environnement")$file="selection_envir.txt";
	else if ($choix == "    NT, PT    ")$file="selection_nt_pt.txt";
	else if ($choix == "     Biologie     ")$file="selection_biolo.txt";
	else if ($choix == "    Trophique     ")$file="selection_troph.txt";
	
	$fpm = fopen($file, "w+");////w+
	fputs($fpm,"");
	fclose($fpm);
	$fpm = fopen($file, "w");
	$i = 0;
	$j=0;//numero coup de peche de la selection
	$k=1;//nombre lignes
	$coup_peche = 999999;
	if($choix == "    Trophique     ")$intitule = "identifiant\tpays\tpays_lib\tsyst\tsyst_lib\trefpaysid\tsyst_surf\tidcampagne\tsyst\tcamp\tcamp_deb\tcamp_fin\tlibellecampagne\tidsecteur\tsect\tsect_lib\tsect_surf\trefsystemeid\tstation\tstation_lib\tstation_site\tstation_lat\tstation_lon\tstation_memo\tsec\tposition\tvegetation\tdebris\tsediment\tstation_dist_emb\tvegetation\tvegetation_lib\tdebris\tdebris_lib\tsediment\tsediment_lib\tposition\tposition_lib\tidcouppeche\tcoup_date\tcoup_lon\tcoup_lat\tcoup_memo\tcoup_prof\tqualite\texpcampagneid\tstation\tcoup_filet\tcoup\tengin\tcoup_protocole\tcoup_hdeb\tcoup_hfin\texpenvironnementid\tidenvironnement\ttransp\tsals\tsalf\ttmps\ttmpf\toxys\toxyf\tchls\tchlf\tmots\tmops\tmotf\tmopf\tconds\tcondf\tenvir_memo\tfcour\tscour\tscour\tscour_lib\tfcour\tfcour_lib\tengin\tengin_lib\tengin_long\tengin_chute\tengin_maille\tmemo_engin\tqualite\tqualite_lib\tid_fraction\tnt\tpt\tfraction_memo\tcodesp\texpcouppecheid\tnt_est\tcodesp\tespece\tinformationespece\treffamilleid\tcat_ecol\tcat_troph\tcoefK\tcoefb\treforiginekbid\trefespeceid\tcat_ecol\tcat_ecol_lib\tcat_troph\tcat_troph_lib\tidfamille\tfamille\trefordreid\tnon_poisson\tidordre\tordre\tid_biologie\tlong_lf\tlong_lt\tpoids\tsexe\tstade\trempl_stom\texpfractionid\tbiologie_memo\tmesure_estim\tsexe\tsexe_lib\trempl_stom\trempl_stom_lib\tstade\tstade_lib\tid\tbio\tcont_stom\tquant_stom\tcont_stom\tcont_stom_lib\n";
	else $intitule = "identifiant\tpays\tpays_lib\tsyst\tsyst_lib\trefpaysid\tsyst_surf\tidcampagne\tsyst\tcamp\tcamp_deb\tcamp_fin\tlibellecampagne\tidsecteur\tsect\tsect_lib\tsect_surf\trefsystemeid\tstation\tstation_lib\tstation_site\tstation_lat\tstation_lon\tstation_memo\tsec\tposition\tvegetation\tdebris\tsediment\tstation_dist_emb\tvegetation\tvegetation_lib\tdebris\tdebris_lib\tsediment\tsediment_lib\tposition\tposition_lib\tidcouppeche\tcoup_date\tcoup_lon\tcoup_lat\tcoup_memo\tcoup_prof\tqualite\texpcampagneid\tstation\tcoup_filet\tcoup\tengin\tcoup_protocole\tcoup_hdeb\tcoup_hfin\texpenvironnementid\tidenvironnement\ttransp\tsals\tsalf\ttmps\ttmpf\toxys\toxyf\tchls\tchlf\tmots\tmops\tmotf\tmopf\tconds\tcondf\tenvir_memo\tfcour\tscour\tscour\tscour_lib\tfcour\tfcour_lib\tengin\tengin_lib\tengin_long\tengin_chute\tengin_maille\tmemo_engin\tqualite\tqualite_lib\tid_fraction\tnt\tpt\tfraction_memo\tcodesp\texpcouppecheid\tnt_est\tcodesp\tespece\tinformationespece\treffamilleid\tcat_ecol\tcat_troph\tcoefK\tcoefb\treforiginekbid\trefespeceid\tcat_ecol\tcat_ecol_lib\tcat_troph\tcat_troph_lib\tidfamille\tfamille\trefordreid\tnon_poisson\tidordre\tordre\tid_biologie\tlong_lf\tlong_lt\tpoids\tsexe\tstade\trempl_stom\texpfractionid\tbiologie_memo\tmesure_estim\tsexe\tsexe_lib\trempl_stom\trempl_stom_lib\tstade\tstade_lib\n";
	fputs($fpm,$intitule);
	$nombre_enreg = pg_num_rows($result);
	$fraction=999999;
	
	while($row = pg_fetch_row($result))
		{
		$contenu="";
		
		//numérotation des coups de peches
		if ($row[47] != $coup_peche)
			{
			$j++;
			$coup_peche = $row[47];
			}
		else 
			{
			if ($choix=="Environnement")continue;//on veux que des cp différents
			}
		$contenu.= $j."\t";//////84
		
		if ((($choix==" Peuplement ")||($choix=="    NT, PT    "))&&($row[84]==$fraction))
			{
			//print("<br>!!++!!++".$choix.$fraction." , ".$coup_peche);
			continue;
			}
		//on ne veux que les fractions différentes
		$fraction = $row[84]; 
			
			
		if($choix == "    Trophique     ")$iii=133;
		else if($choix == "     Biologie     ")$iii=127;
		else $iii=111; //il y a 127 champs dans la requete
		//si $row[0]different, numero +1
		reset($row);
		for ($i=0; $i<$iii; $i++)	//il y a 127 champs dans la requete
			{
		
			$xx = $row[$i];
				$yy=str_replace(".", ",", $xx);//dans la base de donnée, les champs peuvent contenir tous ces caractères. on les enlèvent pour ne pas engendrer d'erreur
				$aa=str_replace(";", " ", $yy);
				$bb=str_replace("\n", " ", $aa);
				$cc=str_replace("\t", " ", $bb);
			/*if (($i == 41)||($i == 119))
				{
				//$contenu .= "memo \t";
				if ($i == 41)print ("<br>41 : ".$row[$i]);
				if ($i == 119)print ("<br>119 : ".$row[$i]);
				}*/
				
			//else $contenu .= $cc."\t";
			$contenu .= $cc."\t";
			}

		$contenu = substr($contenu, 0, -1);
		$contenu .= "\n";

		fputs($fpm,$contenu);
		$k++;
		}
	fclose($fpm);
	///////////////////////////////////////
	
	
	
	
	
	///////////////////////////////////////
	print ("<br><div align='center'>La selection porte sur ".$j." coups de pêches");
	print ("<br>Le fichier texte comporte ".($k-1)." lignes</div>");//car 1ere ligne est un intitulé
	
	//pg_free_result();
	//pg_close();
	////////////////////////////////
	$requete_faite = 1;



	///////////////////////////////////////////////////////////
	///tri des lignes à garder dans le fichier texte
	///////////////////////////////////////////////////////////

	print ("<div align='center'><Font Color =\"#333366\">");
	if($choix!=" Peuplement ")print ("<br>Critères de selection<br>");
	
	print ("<form name=\"form\" method=\"post\" action=\"sc_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	if($choix!=" Peuplement ")print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1>");
	else print("<br><table CELLSPACING=2 CELLPADDING=1>");
	if (($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "Environnement"))
		{
		print ("<tr>");
		print ("<td ROWSPAN=3>Choix de la qualité du coup de pêche</td>");
		print ("<td><input type=\"Checkbox\" name=\"qualite[0]\" value=\"1\"checked>1</td>");
		print ("<td><input type=\"Checkbox\" name=\"qualite[1]\" value=\"2\">2</td>");
		print ("</tr><tr><td><input type=\"Checkbox\" name=\"qualite[2]\" value=\"3\"checked>3</td>");
		print ("<td><input type=\"Checkbox\" name=\"qualite[3]\" value=\"4\">4</td>");
		print ("</tr><tr><td><input type=\"Checkbox\" name=\"qualite[4]\" value=\"5\"checked>5</td></tr>");
		
		print ("<tr>");
		print ("<td>Restreindre aux coups du protocole?</td>");
		print ("<td><input type=\"radio\" name=\"protocole\" value=\"1\"checked>oui</td>");
		print ("<td><input type=\"radio\" name=\"protocole\" value=\"0\">non</td>");
		print ("</tr>");

		if ($choix == "Environnement")
			{
			print ("<input type=hidden name=\"poisson\" value=\"oui\">");
			print ("<input type=hidden name=\"non_poisson\" value=\"oui\">");
			}
		else
			{
			//renseignements sur les champs optionnels de la selection d'espece
			print ("<tr>");
			print ("<td>Voulez vous les poissons</td>");
			print ("<td><input type=\"radio\" name=\"poisson\" value=\"oui\"checked>oui</td>");
			print ("<td><input type=\"radio\" name=\"poisson\" value=\"non\">non</td>");
			print ("</tr><tr>");
		
			print ("<td>Voulez vous les non-poissons</td>");
			print ("<td><input type=\"radio\" name=\"non_poisson\" value=\"oui\">oui</td>");
			print ("<td><input type=\"radio\" name=\"non_poisson\" value=\"non\"checked>non</td>");
			print ("</tr><tr>");
			}
		}
	else if($choix == " Peuplement ")
		{
		print ("<input type=hidden name=\"qualite[0]\" value=\"1\">");
		print ("<input type=hidden name=\"qualite[1]\" value=\"3\">");
		print ("<input type=hidden name=\"qualite[2]\" value=\"5\">");
		
		print ("<input type=hidden name=\"protocole\" value=\"1\">");
		
		print ("<input type=hidden name=\"poisson\" value=\"oui\">");
		print ("<input type=hidden name=\"non_poisson\" value=\"non\">");
		}
	
	else if($choix == "    Trophique     ")
		{
		print ("<input type=hidden name=\"qualite[0]\" value=\"1\">");
		print ("<input type=hidden name=\"qualite[1]\" value=\"2\">");
		print ("<input type=hidden name=\"qualite[2]\" value=\"3\">");
		print ("<input type=hidden name=\"qualite[3]\" value=\"4\">");
		print ("<input type=hidden name=\"qualite[4]\" value=\"5\">");
		
		print ("<input type=hidden name=\"protocole\" value=\"1\">");
		
		print ("<tr>");
		print ("<td>Voulez vous les poissons</td>");
		print ("<td><input type=\"radio\" name=\"poisson\" value=\"oui\"checked>oui</td>");
		print ("<td><input type=\"radio\" name=\"poisson\" value=\"non\">non</td>");
		print ("</tr><tr>");
		
		print ("<td>Voulez vous les non-poissons</td>");
		print ("<td><input type=\"radio\" name=\"non_poisson\" value=\"oui\">oui</td>");
		print ("<td><input type=\"radio\" name=\"non_poisson\" value=\"non\"checked>non</td>");
		print ("</tr><tr>");
		}
	print ("</tr></table>");

	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     "))
		{
		print ("<br><br><table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr>");
		print ("<td ROWSPAN=5 align=center>Catégorie écologique</td>");
		
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
		///////
	
	
		print ("</tr><tr>");
		print ("<td ROWSPAN=5 align=center>Catégorie trophique</td>");
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
		}//fin du if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     "))

	
	print ("<br><br><input type=\"submit\" name=\"\" value=\"    Suite    \">");
	print ("</form>");

	}//fin du if $requete_faite !=1
else if(($requete_faite ==1)&&($selection_faite !=1))
	{
	////////////////////////////////////////////////////////
	//on trie les lignes du resultat en fonction des choix//
	////////////////////////////////////////////////////////
	

	if ($choix==" Peuplement ")$file="selection_peupl.txt";
	else if ($choix == "Environnement")$file="selection_envir.txt";
	else if ($choix == "    NT, PT    ")$file="selection_nt_pt.txt";
	else if ($choix == "     Biologie     ")$file="selection_biolo.txt";
	else if ($choix == "    Trophique     ")$file="selection_troph.txt";
	
	
	//print "choix === ".$choix."<br/>";
	
	//fclose($fpm);
	$fpm = fopen($file, "r");
	
	
	//creation du tableau $tab_ligne contenant les lignes du fichier temp
	$i=0;
	$tab_ligne = array();
	$ligne = "";
	while ($ligne=fgets($fpm,10000))
		{
		$ligne = substr($ligne, 0, -1); 		//on enleve le dernier \n
		$tab_ligne[$i]=$ligne;
		$i ++;
		}
	fclose($fpm);

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
		
		
		if(is_array($_POST['qualite'])) reset ($_POST['qualite']);
     	if(is_array($_POST['protocole'])) reset ($_POST['protocole']);
		if(is_array($_POST['ecologique'])) reset ($_POST['ecologique']);
		if(is_array($_POST['trophique']))reset ($_POST['trophique']);
        
	
		//pour les qualité, seules les valeurs de $qualite contenu dans $_POST['qualite'] doivent rester
		if (!in_array ($ligne_contient[44], $_POST['qualite']))
			{
			//print ("<br><br>!!!qualite :".$val_ligne." , ".$compt);
			unset($tab_ligne[$compt]);
			}
		//pour le protocole, seules les lignes dont la valeur est $protocole doivent rester
		else if (($_POST['protocole']==1)&& ($ligne_contient[50] != $_POST['protocole']))
			{
			//print ("<br><br>!!!protocole :".$val_ligne." , ".$compt);
			unset($tab_ligne[$compt]);
			}
		//pour la categorie ecologique, les valeurs doivent etre une du tableau $_POST['ecologique']
		else if ((($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")) && 
		(!in_array (trim($ligne_contient[96]), $_POST['ecologique'])))
			{
			//print ("<br><br>!!!ecologique: ".$val_ligne." , ".$compt);
			if ($_POST['ecologique'][100] != "null")unset($tab_ligne[$compt]);
			}
		//pour la categorie trophique, les valeurs doivent etre une du tableau $_POST['trophique']
		else if ((($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")) &&
		(!in_array ($ligne_contient[97], $_POST['trophique'])))
			{
			//print ("<br><br>!!!trophique: ".$ligne_contient[97]." , ");
			if ($_POST['trophique'][100] != "null")unset($tab_ligne[$compt]);
			}
		//pour les poisson
		//if (($_POST['poisson']=="oui")&&($_POST['non_poisson']=="oui"))continue;
		if (($_POST['poisson']=="oui")&&($_POST['non_poisson']=="non"))
			{
			if ($ligne_contient[109] != 0)
				{
				//print ("<br><br>!!!non poisson : ".$val_ligne." , ".$compt);
				unset($tab_ligne[$compt]);
				}
			}
		else if (($_POST['poisson']=="non")&&($_POST['non_poisson']=="oui"))
			{
			if ($ligne_contient[109] != 1)
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

	
	//if(($choix != " Peuplement ")||($choix != "Environnement"))
	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     "))
		{
		/////////////////////////////////////////////
		//choix des especes sur la selection restante
		////////////////////////////////////////////
		reset ($tab_ligne);
		$tab_espece = Array();
		while (list($key_ligne, $val_ligne) = each($tab_ligne))
			{
			$ligne_contient = Array();
			$ligne_contient = explode ("\t",$val_ligne);
			$espece = $ligne_contient[93];
			$famille = $ligne_contient[107];
			if (trim($espece) != "espece")
				{
				if (isset ($tab_espece[$espece])) continue;
				else $tab_espece[$espece]=$famille;
				}
			}
		
		
		arsort($tab_espece);
		asort($tab_espece);
		$selection_faite =1;
		
		
		//////////affichage du resultat dans un formulaire
		//print ("<div align='center'><br>Le fichier texte comporte <Font Color =\"#333366\">");
		print ("<div align='center'><br>");
		
		//print (($n-1)."</font> lignes :<br>");
		
		print ("<form name=\"form\" method=\"post\" action=\"sc_filieres.php\">");
		print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
		print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
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
			
		
		
		
		print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1>");
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
		print ("<br><br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
		print ("</form>");
		exit;////////////////////////////////////////////////////////!!!!!!!!!!!!!!!!!!!!!!!!
		}//fin du if($choix != " Peuplement "|| environnement)
	else 
		{
		$requete_faite = 1;
		$selection_faite =1;
		}
	}

//else if($colonnes_faites !=1) //selection espece faite
if (($requete_faite == 1)&&($selection_faite ==1)&&($colonnes_faites!=1))
	{
	$colonnes_faites=1;
	
	
	if($choix == " Peuplement "){
		$selection_faite =1;
		
	print ("<div align=center>Sélection des champs optionnels<br><br><br>");
	print ("<form name=\"form\" method=\"post\" action=\"sc_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
	print ("<input type=hidden name=\"requete_faite\" value=\"".$requete_faite."\">");
	print ("<input type=hidden name=\"selection_faite\" value=\"".$selection_faite."\">");
	print ("<input type=hidden name=\"colonnes_faites\" value=\"".$colonnes_faites."\">");
	
	
	print ("<input type=hidden name=\"voir[55]\" value=\"0\">");
	print ("<input type=hidden name=\"voir[54]\" value=\"1\">");//pays
	print ("<input type=hidden name=\"voir[53]\" value=\"2\">");//pays_lib
	
	print ("<input type=hidden name=\"voir[52]\" value=\"3\">");//syst
	print ("<input type=hidden name=\"voir[51]\" value=\"4\">");//syst_lib
	
	print ("<input type=hidden name=\"voir[45]\" value=\"14\">");//secteur
	print ("<input type=hidden name=\"voir[44]\" value=\"15\">");//secteur_lib
	
	print ("<input type=hidden name=\"voir[48]\" value=\"10\">");//camp_deb
	print ("<input type=hidden name=\"voir[47]\" value=\"11\">");//camp_fin
	print ("<input type=hidden name=\"voir[49]\" value=\"9\">");//camp
	
	print ("<input type=hidden name=\"voir[42]\" value=\"18\">");//station
	print ("<input type=hidden name=\"voir[41]\" value=\"19\">");//station_lib
	print ("<input type=hidden name=\"voir[40]\" value=\"20\">");//station_site
	
	print ("<input type=hidden name=\"voir[24]\" value=\"48\">");//coup
	print ("<input type=hidden name=\"voir[31]\" value=\"39\">");//coup date
	print ("<input type=hidden name=\"voir[23]\" value=\"49\">");//engin
	print ("<input type=hidden name=\"voir[22]\" value=\"50\">");//coup protocole
	print ("<input type=hidden name=\"voir[26]\" value=\"44\">");//coup qualite
	
	print ("<input type=hidden name=\"voir[14]\" value=\"85\">");//id_fraction
	print ("<input type=hidden name=\"voir[12]\" value=\"87\">");//pt
	print ("<input type=hidden name=\"voir[13]\" value=\"86\">");//nt
	
	print ("<input type=hidden name=\"voir[9]\" value=\"92\">");//code sp
	print ("<input type=hidden name=\"voir[8]\" value=\"93\">");//sp
	print ("<input type=hidden name=\"voir[5]\" value=\"97\">");//cat troph
	//print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[110]\" value=\"105\"></td><td>cat_troph_lib</td>");
	print ("<input type=hidden name=\"voir[6]\" value=\"96\">");//cat ecol
	//print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[111]\" value=\"103\"></td><td>cat_ecol_lib</td>");
	
	//print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[2]\" value=\"107\" ></td><td>famille</td>");
	//print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[60]\" value=\"111\" ></td><td>ordre</td>");
	
	print ("<input type=hidden name=\"voir[19]\" value=\"78\">");//engin lib
	
	?>
	<script language="JavaScript"><!--
	function clicTous(form,booleen) 
		{
		for (i=0, n=form.elements.length; i<n; i++)
		if (form.elements[i].name.indexOf('voir') != -1)
		form.elements[i].checked = booleen;
		}
	//--></script>
	<?php
	
	print ("<table width=\"850\"><tr><td align = middle><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr>");

	
	
	
	
	
	
	
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[110]\" value=\"105\"></td><td>cat_troph_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[111]\" value=\"103\"></td><td>cat_ecol_lib</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[2]\" value=\"107\" ></td><td>famille</td>");
	print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[60]\" value=\"111\" ></td><td>ordre</td>");
	print ("</tr></table>"); 
	
	print ("<br><br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</form>");
	print ("</div>");
	exit;
	}
	
	
	//$file="temp_selection_globale.txt";
	if ($choix==" Peuplement ")$file="selection_peupl.txt";
	else if ($choix == "Environnement")$file="selection_envir.txt";
	else if ($choix == "    NT, PT    ")$file="selection_nt_pt.txt";
	else if ($choix == "     Biologie     ")$file="selection_biolo.txt";
	else if ($choix == "    Trophique     ")$file="selection_troph.txt";
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
	//if(($choix != " Peuplement ")||($choix != "Environnement"))
	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     "))
		{
		while (list($key_ligne, $val_ligne) = each($tab_ligne))
			{
			$ligne_contient = Array();
			$ligne_contient = explode ("\t",$val_ligne);
			//on garde la premiere ligne correspondant aux intitulés
			if ($compt == 0){$compt++; continue;}
			reset ($ligne_contient);
			reset ($_POST['espece']);
			
			//pour les especes, seules les valeurs de $espece contenu dans $_POST['espece'] doivent rester
			if (!in_array ($ligne_contient[93], $_POST['espece']))
				{
				//print ("<br><br>!!!espece :".$val_ligne." , ".$compt);
				unset($tab_ligne[$compt]);
				}
			$compt++;
			}
		}//fin du if $choix l 615
	
	//on reecrit les lignes restantes
	reset($tab_ligne);
	$n = count($tab_ligne);
	//print("<br><br>********".$n);
	
	$fpm = fopen($file, "w+");
	
	if ($choix == "     Biologie     ")
			{
			$tab_frac=Array();
			while (list($key_lignex, $val_lignex) = each($tab_ligne))
				{
				$ligne_contient2 = Array();
				//print("<br>!!".$ligne_contient2);//array
				$ligne_contient2 = explode ("\t",$val_lignex);
				
				if (!isset($tab_frac[$ligne_contient2[85]][0]))$tab_frac[$ligne_contient2[85]][0]="";
				}
			reset ($tab_frac);
			
			while (list($key_lignex, $val_lignex) = each($tab_frac))
				{
				if($key_lignex =="id_fraction")
					{
					$tab_frac[$key_lignex][0]="nbre_indiv_mesur";//intitulés
					$tab_frac[$key_lignex][1]="coef_extr";
					}
				$query_coeff = "select exp_fraction.nombre_total, count(exp_biologie.id) 
				from exp_fraction, exp_biologie 
				where exp_biologie.exp_fraction_id=exp_fraction.id 
				and exp_fraction.id=".$key_lignex." 
				group by exp_fraction.id, exp_fraction.nombre_total ";
				//print("<br><br>".$query_coeff);//128
				$result_extra=Array();
				//$connection = pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
				//if (!$connection) { echo "Pas de connection"; exit;}
				$result_extra = pg_query($connection, $query_coeff);
				$row_extra =Array();
				while($row_extra = pg_fetch_row($result_extra))
					{
					$tab_frac[$key_lignex][0]=$row_extra[1];
					$tab_frac[$key_lignex][1]= str_replace(".", ",", round (($row_extra[0]/$row_extra[1]),2));
					//print("<br><br>!!".$key_lignex." : ".$tab_frac[$key_lignex][0]." , ".round (($row_extra[0]/$row_extra[1]),2));
					}
				}
			}
	reset($tab_ligne);
	while (list($key_ligne, $val_ligne) = each($tab_ligne))
		{
		//si filiere biologie, ajout coeff d'extrapol
		if ($choix == "     Biologie     ")
			{
			$val_ligne_isert="";
			$ligne_contient3 = Array();
			$ligne_contient3 = explode ("\t",$val_ligne);
			$id_fraction = $ligne_contient3[85];
			$val_ligne_isert = $val_ligne."\t".$tab_frac[$id_fraction][0]."\t".$tab_frac[$id_fraction][1]."\n";
			//print("<br><br>!!".$id_fraction." , ".$val_ligne_isert);
			fputs($fpm,$val_ligne_isert);
			}
		
		

		//sinon normal
		else 
		fputs($fpm,$val_ligne."\n");
		}
	fclose($fpm);
	

	
	print ("<div align='center'>");
	//////////////////////////////////////////////////////////////////////////////////////////////////
	print ("<br><b>Séléction des champs optionnels</b>
	<br><br>Vous pouvez cliquez sur le nom d'une table pour la développer
	<br>Les valeurs classiques sont sélectionnées par défaut");
	
	print ("<form name=\"form\" method=\"post\" action=\"sc_filieres.php\">");
	print ("<input type=hidden name=\"base\" value=\"".$bdd."\">");
	print ("<input type=hidden name=\"choix\" value=\"".$choix."\">");
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
	//--></script>
		<?php
		print ("<table><tr><td><input type=\"Checkbox\" onClick=\"if (this.checked) { clicTous(this.form,true) } else { clicTous(this.form,false) };\">Tout</td></tr></table>");
			
	
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 ><tr VALIGN=top><td VALIGN=top align=center WIDTH=\"200\">");
	?>
<div onclick="document.getElementById('_pays').style.display = 'block';"><b>Pays</b>
</div> 
<div id="_pays" style="display:none">
<?php   print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//pour le pays
	print ("<tr ALIGN=center><td WIDTH=30>x</td><td>pays</td>");//id pays
	print ("<input type=hidden name=\"voir[54]\" value=\"1\">");//id dans systeme
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pays_lib</td>");//nom pays
	print ("<input type=hidden name=\"voir[53]\" value=\"2\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('_pays').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
?></div> <?php
	print ("</td><td VALIGN=top align=center WIDTH=\"200\">");
	
	?>
<div onclick="document.getElementById('vue_syst').style.display = 'block';"><b>Système</b>
</div> 

<div id="vue_syst" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	//print ("</tr><tr><td>Champs facultatifs du système</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst</td>");//id systeme
	print ("<input type=hidden name=\"voir[52]\" value=\"3\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>syst_lib</td>");//nom syteme
	print ("<input type=hidden name=\"voir[51]\" value=\"4\">");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[50]\" value=\"6\"></td><td>syst_surf</td>");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_syst').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td><td align=center WIDTH=\"200\">");

	?>
	<div onclick="document.getElementById('vue_sect').style.display = 'block';"><b>Secteur</b>
</div> 

<div id="vue_sect" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"150\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect</td>");//id dans systeme
	print ("<input type=hidden name=\"voir[45]\" value=\"14\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>sect_lib </td>");//nom secteur
	print ("<input type=hidden name=\"voir[44]\" value=\"15\">");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[43]\" value=\"16\"></td><td>sect_surf</td>");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_sect').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	?></div> <?php
	print ("</td></tr></table>");
	
	
	//////////////////////////////////////tables campagnes et stations
	
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top align=center><td VALIGN=top width=\"200\">");
	?>
	<div onclick="document.getElementById('vue_camp').style.display = 'block';"><b>Campagnes</b>
</div>

<div id="vue_camp" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//print ("</tr><tr><td>Champs facultatifs des campagnes</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>camp_deb</td>");//date debut
	print ("<input type=hidden name=\"voir[48]\" value=\"10\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>camp_fin</td>");
	print ("<input type=hidden name=\"voir[47]\" value=\"11\">");//date debut
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>camp</td>");
	print ("<input type=hidden name=\"voir[49]\" value=\"9\">");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_camp').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	
	?></div> <?php
	print ("</td><td width=\"200\">");
	?>
	<div onclick="document.getElementById('vue_station').style.display = 'block';"><b>Stations</b>
</div>
<div id="vue_station" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//print ("</tr><tr><td>Champs facultatifs des stations</td>");
	print ("<tr ALIGN=center><td WIDTH=30>x</td><td>station</td>");//id station
	print ("<input type=hidden name=\"voir[42]\" value=\"18\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>station_lib</td>");//nom station
	print ("<input type=hidden name=\"voir[41]\" value=\"19\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>station_site</td>");
	print ("<input type=hidden name=\"voir[40]\" value=\"20\">");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[39]\" value=\"21\" ></td><td>station_lat</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[38]\" value=\"22\" ></td><td>station_lon</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[32]\" value=\"29\" ></td><td>station_dist_emb</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[33]\" value=\"28\" ></td><td>sediment</td>");
	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == "Environnement"))print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[58]\" value=\"35\" ></td><td>sediment_lib</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[34]\" value=\"27\" ></td><td>debris</td>");
	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == "Environnement"))print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[56]\" value=\"33\" ></td><td>debris_lib</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[35]\" value=\"26\" ></td><td>vegetation</td>");
	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == "Environnement"))print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[57]\" value=\"31\" ></td><td>vegetation_lib</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[36]\" value=\"25\" ></td><td>position</td>");
if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == "Environnement"))print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[59]\" value=\"37\" ></td><td>position_lib</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[37]\" value=\"23\" ></td><td>station_memo</td>");
	
	
	//print ("<input type=hidden name=\"voir[41]\" value=\"19\">");//nom station
	//print ("<input type=hidden name=\"voir[42]\" value=\"18\">");//id station
	
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_station').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	?></div> <?php
	print ("</td></tr></table>");
	
////////////////////////////////////////////tables coup de peche et fraction
print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top align=center><td VALIGN=top width=\"200\">");
	?>
	<div onclick="document.getElementById('vue_coup').style.display = 'block';"><b>Coups de pêche</b>
</div>

<div id="vue_coup" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//print ("</tr><tr><td>Champs facultatifs des coups de pêches</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>coup</td>");
		print ("<input type=hidden name=\"voir[24]\" value=\"48\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>coup_date</td>");//date
	print ("<input type=hidden name=\"voir[31]\" value=\"39\">");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>engin</td>");//exp_engin_id
	print ("<input type=hidden name=\"voir[23]\" value=\"49\">");
		print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>coup_protocole</td>");//protocole
	print ("<input type=hidden name=\"voir[22]\" value=\"50\">");	
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>qualite</td>");//exp_qualite_id
	print ("<input type=hidden name=\"voir[26]\" value=\"44\">");
		
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[21]\" value=\"51\"></td><td>coup_hdeb</td>");//heure debut
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[20]\" value=\"52\"></td><td>coup_hfin</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[29]\" value=\"41\"></td><td>coup_lat</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[30]\" value=\"40\"></td><td>coup_lon</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[25]\" value=\"47\"></td><td>coup_filet</td>");
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[27]\" value=\"43\"></td><td>coup_prof</td>");
	
	
	if($choix!=" Peuplement ")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[28]\" value=\"42\"></td><td>coup_memo</td>");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_coup').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");


if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == " Peuplement "))
	{

?></div> <?php
	print ("</td><td width=\"200\">");
	?>
	<div onclick="document.getElementById('vue_fraction').style.display = 'block';"><b>Fractions</b>
</div>

<div id="vue_fraction" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//print ("</tr><tr><td>Champs facultatifs des fractions</td>");
	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == " Peuplement "))print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>id_fraction</td>");
	print ("<input type=hidden name=\"voir[14]\" value=\"85\">");
	if(($choix!=" Peuplement ")||($choix!="Environnement"))print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[10]\" value=\"91\" ></td><td>nt_est</td>");
	if(($choix!=" Peuplement ")||($choix!="Environnement"))print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[11]\" value=\"88\" ></td><td>fraction_memo</td>");
	if(($choix!="Environnement")||($choix!="     Biologie     ")||($choix!="    Trophique     "))
	{print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>pt</td>");
print ("<input type=hidden name=\"voir[12]\" value=\"87\">");}
	else if ($choix!="Environnement")print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[12]\" value=\"87\" ></td><td>pt</td>");
	if($choix!="Environnement")
	{print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>nt</td>");
print ("<input type=hidden name=\"voir[13]\" value=\"86\">");}
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_fraction').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	?></div> <?php
	
}//fin du if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == " Peuplement "))
	print ("</td></tr></table>");






////////////////////////////////////////tables esp, famille, ordre sauf pour environnement
if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == " Peuplement "))
	{
	print ("<br><table BORDER=1 CELLPADDING=2><tr VALIGN=top align=center><td VALIGN=top  width=\"200\">");
	?>
	<div onclick="document.getElementById('vue_espece').style.display = 'block';"><b>Espèce</b>
</div>

<div id="vue_espece" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
		//print ("</tr><tr><td COLSPAN=2>Champs facultatifs des espèces</td>");
		print ("<tr ALIGN=center><td WIDTH=30>x</td><td>codesp</td>");
		print ("<input type=hidden name=\"voir[9]\" value=\"92\">");
				print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>espece</td>");
				print ("<input type=hidden name=\"voir[8]\" value=\"93\">");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[3]\" value=\"99\" ></td><td>coefb</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[4]\" value=\"98\" ></td><td>coefK</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>cat_troph</td>");//variable ref_cat_trophique oblig
		print ("<input type=hidden name=\"voir[5]\" value=\"97\">");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[110]\" value=\"105\"></td><td>cat_troph_lib</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>cat_ecol</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[111]\" value=\"103\"></td><td>cat_ecol_lib</td>");
		print ("<input type=hidden name=\"voir[6]\" value=\"96\">");
		print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_espece').style.display = 'none';\">fermer</td>");
		print ("</tr></table>");
	?></div> <?php
	print ("</td><td width=\"200\">");
	?>
	<div onclick="document.getElementById('vue_famille').style.display = 'block';"><b>Famille et ordre</b>
</div>

<div id="vue_famille" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
		print ("<tr ALIGN=center>");
		
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[0]\" value=\"109\" ></td><td>non_poisson</td>");//variable non_poisson
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[2]\" value=\"107\" ></td><td>famille</td>");
		
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
		print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[60]\" value=\"111\" ></td><td>ordre</td>");
		print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_famille').style.display = 'none';\">fermer</td>");
		print ("</tr></table>");
	?></div> <?php
	print ("</td></tr></table>");
	}
	
	
	
	
	//////////////////////////////tables engins, environnement, biologie
	print ("<br><table BORDER=1 CELLSPACING=2 CELLPADDING=1><tr VALIGN=top align=center>");
	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == " Peuplement "))
		{
	?>
	<td VALIGN=top WIDTH="200"><div onclick="document.getElementById('vue_engin').style.display = 'block';"><b>Engin</b>
</div>

<div id="vue_engin" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
	//print ("</tr><tr><td>Champs facultatifs des engins</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30>x</td><td>engin_lib</td>");//libelle
	print ("<input type=hidden name=\"voir[19]\" value=\"78\">");
	print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[15]\" value=\"82\" ></td><td>memo_engin</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[16]\" value=\"81\" ></td><td>engin_maille</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[17]\" value=\"80\" ></td><td>engin_chute</td>");
	print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[18]\" value=\"79\" ></td><td>engin_long</td>");
	print ("</tr><tr ALIGN=center><td colspan=2 onclick=\"document.getElementById('vue_engin').style.display = 'none';\">fermer</td>");
	print ("</tr></table>");
	?></div> <?php
	print ("</td>");
	}
	
	if(($choix == "    NT, PT    ")||($choix == "     Biologie     ")||($choix == "    Trophique     ")||($choix == "Environnement"))
		{
	?>
	
	<td WIDTH="200"><div onclick="document.getElementById('vue_envir').style.display = 'block';"><b>Environnement</b>
</div>

<div id="vue_envir" style="display:none">
<?php 
	print ("<table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
		print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[61]\" value=\"55\" ></td><td WIDTH=170>transp</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[76]\" value=\"70\" ></td><td>envir_memo</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[62]\" value=\"56\" ></td><td WIDTH=170>sals</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[63]\" value=\"57\" ></td><td WIDTH=170>salf</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[64]\" value=\"58\" ></td><td WIDTH=170>tmps</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[65]\" value=\"59\" ></td><td WIDTH=170>tmpf</td>");
		
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[66]\" value=\"60\" ></td><td>oxys</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[67]\" value=\"61\" ></td><td>oxyf</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[68]\" value=\"62\" ></td><td>chls</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[69]\" value=\"63\" ></td><td>chlf</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[70]\" value=\"64\" ></td><td>mots</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[72]\" value=\"66\" ></td><td>motf</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[71]\" value=\"65\" ></td><td>mops</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[73]\" value=\"67\" ></td><td>mopf</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[74]\" value=\"68\" ></td><td>conds</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[75]\" value=\"69\" ></td><td>condf</td>");
		
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[77]\" value=\"73\" ></td><td>scour</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[100]\" value=\"74\" ></td><td>scour_lib</td>");
		print ("</tr><tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[78]\" value=\"75\" ></td><td>fcour</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[101]\" value=\"76\" ></td><td>fcour_lib</td>");
		print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('vue_envir').style.display = 'none';\">fermer</td>");
		print ("</tr></table>");
	?></div> <?php
	print ("</td>");
	}
	if(($choix == "     Biologie     ")||($choix == "    Trophique     "))
		{
		?>
		<td WIDTH="200"><div onclick="document.getElementById('vue_biol').style.display = 'block';"><b>Biologie</b>
</div>

<div id="vue_biol" style="display:none">
<?php 
	print ("<br><table BORDER=1 CELLSPACING=2 CELLPADDING=1 WIDTH=\"200\">");
		print ("<tr ALIGN=center><td WIDTH=30>x</td><td>id_biologie</td>");
		print ("<input type=hidden name=\"voir[120]\" value=\"112\">");
		print ("<td WIDTH=30>x</td><td>long_lf</td>");//longueur
		print ("<input type=hidden name=\"voir[80]\" value=\"113\">");
		print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[81]\" value=\"114\" ></td><td>long_lt</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[82]\" value=\"115\" ></td><td>poids</td>");
		
		if($choix == "     Biologie     ")print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[130]\" value=\"128\" ></td><td>nb_indiv_mesur</td>");
		if($choix == "     Biologie     ")print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[131]\" value=\"129\" ></td><td>coef_extr</td>");
		
		print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[83]\" value=\"116\" ></td><td>sexe</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[88]\" value=\"123\" ></td><td>sexe_lib</td>");
		print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[84]\" value=\"117\" ></td><td>stade</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[90]\" value=\"127\" ></td><td>stade_lib</td>");
		if($choix == "     Biologie     ")print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[85]\" value=\"118\" ></td><td>rempl_stom</td>");
		else {print ("<tr ALIGN=center><td WIDTH=30>x</td><td>rempl_stom</td>");
		print ("<input type=hidden name=\"voir[85]\" value=\"118\">");}
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[89]\" value=\"125\" ></td><td>rempl_stom_lib</td>");
		if($choix == "    Trophique     ")print ("<tr ALIGN=center><td WIDTH=30>x</td><td>cont_stom</td>");
		if($choix == "    Trophique     ")print ("<input type=hidden name=\"voir[121]\" value=\"130\">");
		if($choix == "    Trophique     ")print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[122]\" value=\"133\" ></td><td>cont_stom_lib</td>");
		if($choix == "    Trophique     ")print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[123]\" value=\"131\" ></td><td>quant_stom</td>");
		
		//if($choix == "    Trophique     ")print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[222]\" value=\"130\" ></td><td>cont</td>");
		if($choix == "     Biologie     ")print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[86]\" value=\"120\" ></td><td>biologie_memo</td>");
		print ("<td WIDTH=30><input type=\"Checkbox\" name=\"voir[87]\" value=\"121\" ></td><td>mesure_estim</td>");
		if($choix == "    Trophique     ")print ("<tr ALIGN=center><td WIDTH=30><input type=\"Checkbox\" name=\"voir[86]\" value=\"120\" ></td><td>biologie_memo</td>");
		
		print ("</tr><tr ALIGN=center><td colspan=4 onclick=\"document.getElementById('vue_biol').style.display = 'none';\">fermer</td>");
		print ("</tr></table>");
	?></div> <?php
	print ("</td>");
}
	print ("</tr></table>");
print ("<input type=hidden name=\"voir[55]\" value=\"0\">");
	
	
	
	print ("<br><br><input type=\"submit\" name=\"\" value=\"    Valider    \">");
	print ("</form>");
	///////////////////////////////////////////////////////////////////////
	}
//else
else if (($requete_faite == 1)&&($selection_faite ==1)&&($colonnes_faites==1))
	{

	//on ajoute les colonnes supp contenues dans $arajouter
	$nombre_ligne=0;
	
	
	//////on enlève des colonnes en trop du fichier temp
	//$file="temp_selection_globale.txt";
	if ($choix==" Peuplement ")$file="selection_peupl.txt";
	else if ($choix == "Environnement")$file="selection_envir.txt";
	else if ($choix == "    NT, PT    ")$file="selection_nt_pt.txt";
	else if ($choix == "     Biologie     ")$file="selection_biolo.txt";
	else if ($choix == "    Trophique     ")$file="selection_troph.txt";
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
	
	$non_doublon=Array();
	$deb=0;
	//ouverture fichier pour ecriture en local?????
	//$file="temp_selection_globale.txt";
	if ($choix==" Peuplement ")$file="selection_peupl.txt";
	else if ($choix == "Environnement")$file="selection_envir.txt";
	else if ($choix == "    NT, PT    ")$file="selection_nt_pt.txt";
	else if ($choix == "     Biologie     ")$file="selection_biolo.txt";
	else if ($choix == "    Trophique     ")$file="selection_troph.txt";
	$fpm2 = fopen($file, "w+");
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

		//pour chaque deb, on vérifiera les doublons
		
		if ($ligne_contient[0]!=$deb)$non_doublon=Array();
		
		
		//on enleve les doublons
		if (!in_array ($nouveau,$non_doublon))
			{
			fputs($fpm2,$nouveau);
			$non_doublon [$nombre_ligne]= $nouveau;
			$nombre_ligne++;
			}
		$deb=$ligne_contient[0];
		}
	fclose($fpm2);
		


//compression du fichier pour le telechargement
//$filename = './temp_selection_globale.txt';
if ($choix==" Peuplement ")$filename="./selection_peupl.txt";
	else if ($choix == "Environnement")$filename="./selection_envir.txt";
	else if ($choix == "    NT, PT    ")$filename="./selection_nt_pt.txt";
	else if ($choix == "     Biologie     ")$filename="./selection_biolo.txt";
	else if ($choix == "    Trophique     ")$filename="./selection_troph.txt";



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
print ("<div align='center'><br><br><br>");

print ("<br>La sélection représente ".($nombre_ligne -1)." lignes dans le fichier de sortie.
<br>Vous devez sauvegarder ce fichier sur votre ordinateur pour ne pas perdre la sélection en cours.<br>Cliquez sur le lien pour l'enregistrement.");
//print ("<br><br><a href=\"http://vmppeao.mpl.ird.fr/extraction/temp_selection_globale.txt.gz\"<b>Enregistrement du fichier texte</b></a>");

//print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/temp_selection_globale.txt.gz\"<b>Enregistrement du fichier texte</b></a>");
if ($choix==" Peuplement ")print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_peupl.txt.gz\"<b>Enregistrement du fichier texte</b></a>");
else if ($choix == "Environnement")print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_envir.txt.gz\"<b>Enregistrement du fichier texte</b></a>");
else if ($choix == "    NT, PT    ")print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_nt_pt.txt.gz\"<b>Enregistrement du fichier texte</b></a>");
else if ($choix == "     Biologie     ")print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_biolo.txt.gz\"<b>Enregistrement du fichier texte</b></a>");
else if ($choix == "    Trophique     ")print ("<br><br><a href=\"https://devppeao.mpl.ird.fr/extraction/selection_troph.txt.gz\"<b>Enregistrement du fichier texte</b></a>");



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


}//fin du else

//pg_free_result($connection);
pg_close();
	

?>
</body>
</html>