<?php
////////////////////////////////////////////////////////////////////////////////////////////////
//                   Récupération des informations nécessaires aux calculs                    //
//                        des données manquantes (pour chaque débarquement)                   // 
//                                    tableau $info_deb                                       // 
//                 et tableau $FT receuillant les infos sur les tailles par fraction          //
////////////////////////////////////////////////////////////////////////////////////////////////



//if(! ini_set("memory_limit", "256M")) {echo "échec";}

//méthode 1
$query = "select AD.id, RF.ref_pays_id, RS.nom, AA.nom, AD.mois, AD.annee, AD.poids_total,
	AD.art_grand_type_engin_id, AF.ref_espece_id, AF.poids, AF.nbre_poissons, AF.id 
	from ref_systeme as RF, ref_secteur as RS, art_agglomeration as AA, art_debarquement as AD,
	art_fraction as AF 
	where RS.ref_systeme_id = RF.id 
	and AA.ref_secteur_id = RS.id 
	and AD.art_agglomeration_id = AA.id 
	and AD.id = AF.art_debarquement_id 
	and AF.debarquee = 1 
	order by AD.id";
//print_debug($query);	

//fin méthode 1
//méthode 2
/*$query="select count(*) From ref_systeme as RF, ref_secteur as RS, art_agglomeration as AA, art_debarquement as AD,
	art_fraction as AF 
	where RS.ref_systeme_id = RF.id 
	and AA.ref_secteur_id = RS.id 
	and AD.art_agglomeration_id = AA.id 
	and AD.id = AF.art_debarquement_id 
	and AF.debarquee = 1";

$result = pg_query($connection, $query);*/
//fin méthode 2
//méthode 2
/*$row = pg_fetch_row($result);
$compteur=$row[0];*/
//print_debug("compteur ==".$compteur);
//fin méthode 2

$info_deb=array();
//méthode 2
/*for($index=1; $index<=$compteur; $index+=1000){
	$query = "select AD.id, RF.ref_pays_id, RS.nom, AA.nom, AD.mois, AD.annee, AD.poids_total,
	AD.art_grand_type_engin_id, AF.ref_espece_id, AF.poids, AF.nbre_poissons, AF.id 
	from ref_systeme as RF, ref_secteur as RS, art_agglomeration as AA, art_debarquement as AD,
	art_fraction as AF 
	where RS.ref_systeme_id = RF.id 
	and AA.ref_secteur_id = RS.id 
	and AD.art_agglomeration_id = AA.id 
	and AD.id = AF.art_debarquement_id 
	and AF.debarquee = 1 
	order by AD.id
	LIMIT 1000 OFFSET ".$index."";
*/
//fin méthode 2	
	
	$result = pg_query($connection, $query);


	while($row = pg_fetch_row($result)){
		
		                           //cle = identifiant du débarquement
		/*print_debug($row[0]);
		print_debug($row[1]);
		print_debug($row[2]);
		print_debug($row[3]);
	 	print_debug($row[4]);
		print_debug($row[5]);
		print_debug($row[6]);
		print_debug($row[7]);    
		print_debug($row[8]);                          //cle2 = identifiant de la fraction
		print_debug($row[9]);                          //cle2 = identifiant de la fraction
		print_debug($row[10]);    */                      //cle2 = identifiant de la fraction
		//print_debug($row[11]);                          //cle2 = identifiant de la fraction
		
		
		$clé = $row[0];     
		$cle2 = $row[11];   
		$info_deb[$clé][$cle2][0] = $row[1];           //pays
		$info_deb[$clé][$cle2][1] = $row[2];           //secteur
		$info_deb[$clé][$cle2][2] = $row[3];           //agglomeration
		$info_deb[$clé][$cle2][3] = $row[4];           //mois
		$info_deb[$clé][$cle2][4] = $row[5];           //année
		$info_deb[$clé][$cle2][5] = $row[6];           //poid total du débarquement
		$info_deb[$clé][$cle2][6] = $row[7];           //engin de peche
		$info_deb[$clé][$cle2][7] = $row[8];           //espece péchée = espece de la fraction
		$info_deb[$clé][$cle2][8] = $row[9];           //poid de la fraction = Wfdbq
		$info_deb[$clé][$cle2][9] = $row[10];          //nombre poisson de la fraction = Nfdbq        
	
	
	}
//méthode 2
//}
//fin méthode 2
pg_free_result($result);
?>