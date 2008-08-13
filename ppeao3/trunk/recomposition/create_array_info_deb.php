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

	print $query;

	//fin méthode 1
//méthode 2
/*$query="select count(*) From ref_systeme as RF, ref_secteur as RS, art_agglomeration as AA, art_debarquement as AD,
	art_fraction as AF 
	where RS.ref_systeme_id = RF.id 
	and AA.ref_secteur_id = RS.id 
	and AD.art_agglomeration_id = AA.id 
	and AD.id = AF.art_debarquement_id 
	and AF.debarquee = 1";

$result = pg_query($connection, $query);
*/
//fin méthode 2
//méthode 2
//$row = pg_fetch_row($result);
//$compteur=$row[0];
//fin méthode 2

$info_deb=array();
//méthode 2
/*for($index=0; $index<=$compteur; $index+=1000){
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
	print_debug($query);
*/
//fin méthode 2	
	
	$result = pg_query($connection, $query);


	while($row = pg_fetch_row($result)){
		$clef = $row[0];     
		$clef2 = $row[11];
	//Pour debug
	//if($clef==12206){
			
				
		$info_deb[$clef][$clef2][0] = $row[1];           //pays
		$info_deb[$clef][$clef2][1] = $row[2];           //secteur
		$info_deb[$clef][$clef2][2] = $row[3];           //agglomeration
		$info_deb[$clef][$clef2][3] = $row[4];           //mois
		$info_deb[$clef][$clef2][4] = $row[5];           //année
		$info_deb[$clef][$clef2][5] = $row[6];           //poid total du débarquement
		$info_deb[$clef][$clef2][6] = $row[7];           //engin de peche
		$info_deb[$clef][$clef2][7] = $row[8];           //espece péchée = espece de la fraction
		$info_deb[$clef][$clef2][8] = $row[9];           //poid de la fraction = Wfdbq
		$info_deb[$clef][$clef2][9] = $row[10];          //nombre poisson de la fraction = Nfdbq        
	//}elseif($clef>12206){
	//	break;
	//}
	
	}
//méthode 2
//}
//fin méthode 2
pg_free_result($result);
?>