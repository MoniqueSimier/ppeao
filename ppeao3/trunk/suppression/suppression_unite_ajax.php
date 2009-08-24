<?php
session_start();

 
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions_generic.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions_SQL.php';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';



global $tablesDefinitions;
global $suppression_cascades;
global $connectPPEAO;




//debug sleep(10);
// quelle action est en cours?
$action=$_GET["action"];
// le domaine concerne
$domaine=$_GET["domaine"];
// l'unite concernee
$unite=$_GET["unite"];
// le niveau de dialogue modal
$level=$_GET["level"];

// si l'on veut supprimer une campagne
if ($domaine=="exp") {

// on recupere des informations sur l'unite a supprimer
$sql="SELECT DISTINCT c.id, c.numero_campagne, c.date_debut, c.date_fin, c.libelle as campagne, c.ref_systeme_id, s.libelle as systeme, s.ref_pays_id, p.nom as pays  FROM exp_campagne c, ref_systeme s, ref_pays p WHERE c.id='$unite' AND s.id=c.ref_systeme_id AND p.id=s.ref_pays_id";
$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
$resultArray=pg_fetch_all($result);
$lUnite=$resultArray[0];
pg_free_result($result);

// on recupere le nombre et les id des enregistrements affectes dans les diverses tables dependantes

// coup de peche
$sqlCoups='SELECT DISTINCT id,exp_environnement_id FROM exp_coup_peche WHERE exp_campagne_id=\''.$lUnite["id"].'\'';
$resultCoups=pg_query($connectPPEAO,$sqlCoups) or die('erreur dans la requete : '.$sqlCoups. pg_last_error());
$coups=pg_fetch_all($resultCoups);
pg_free_result($resultCoups);
if (!empty($coups)) {$coupsNombre=count($coups);} else {$coupsNombre=0;}


// environnement
// on extrait les listes d'id des enregistrements lies aux coups de peche a supprimer
foreach ($coups as $coup) {
	if (!empty($coup["exp_environnement_id"])) {$enviro[]=$coup["exp_environnement_id"];}
	if (!empty($coup["id"])) {$coups_id[]=$coup["id"];}
}
// la liste des environnements lies aux coups de peche a supprimer
$enviroListe='\''.arrayToList($enviro,'\',\'','\'');
$sqlEnviro='SELECT DISTINCT id FROM exp_environnement WHERE id IN ('.$enviroListe.')';
$resultEnviro=pg_query($connectPPEAO,$sqlEnviro) or die('erreur dans la requete : '.$sqlEnviro. pg_last_error());
$enviro=pg_fetch_all($resultEnviro);
pg_free_result($resultEnviro);
if (!empty($enviro)) {$enviroNombre=count($enviro);} else {$enviroNombre=0;}

// fractions
// la liste des id des coups de peche a supprimer
$coupsListe='\''.arrayToList($coups_id,'\',\'','\'');
$sqlFraction='SELECT DISTINCT id FROM exp_fraction WHERE exp_coup_peche_id IN ('.$coupsListe.')';
$resultFraction=pg_query($connectPPEAO,$sqlFraction) or die('erreur dans la requete : '.$sqlFraction. pg_last_error());
$fractions=pg_fetch_all($resultFraction);
pg_free_result($resultFraction);
if (!empty($fractions)) {$fractionNombre=count($fractions);} else {$fractionNombre=0;}


// biologie
// on extrait les listes d'id des fractions lies aux coups de peche a supprimer
foreach ($fractions as $fraction) {
	if (!empty($fraction["id"])) {$fractions_id[]=$fraction["id"];}
}
// la liste des id des fraction a supprimer
$fractionsListe='\''.arrayToList($fractions_id,'\',\'','\'');
$sqlBio='SELECT DISTINCT id FROM exp_biologie WHERE exp_fraction_id IN ('.$fractionsListe.')';
$resultBio=pg_query($connectPPEAO,$sqlBio) or die('erreur dans la requete : '.$sqlBio. pg_last_error());
$biologies=pg_fetch_all($resultBio);
pg_free_result($resultBio);
$biologieNombre=count($biologies);
if (!empty($biologies)) {$biologieNombre=count($biologies);} else {$biologieNombre=0;}

// trophique
// on extrait les listes d'id des biologie lies aux coups de peche a supprimer
foreach ($biologies as $biologie) {
	if (!empty($biologie["id"])) {$biologies_id[]=$biologie["id"];}
}
// la liste des id des biologies a supprimer
$biologiesListe='\''.arrayToList($biologies_id,'\',\'','\'');
$sqlTrophique='SELECT DISTINCT id FROM exp_trophique WHERE exp_biologie_id IN ('.$biologiesListe.')';
$resultTrophique=pg_query($connectPPEAO,$sqlTrophique) or die('erreur dans la requete : '.$sqlTrophique. pg_last_error());
$trophiques=pg_fetch_all($resultTrophique);
pg_free_result($resultTrophique);
$trophiqueNombre=count($trophiques);
if (!empty($trophiques)) {$trophiqueNombre=count($trophiques);
	// on extrait les listes d'id des trophiques lies aux coups de peche a supprimer
	foreach ($trophiques as $trophique) {
	if (!empty($trophique["id"])) {
		$trophiques_id[]=$trophique["id"]; 
		// la liste des id des trophiques a supprimer
		$trophiquesListe='\''.arrayToList($trophiques_id,'\',\'','\'');}
	} // end foreach $trophiques
	} else {$trophiqueNombre=0;}


// on compose le message a afficher et on realise les actions a faire
$theMessage="<div>";
// si on en est au stade de la confirmation de la suppression
if ($action=="ask") {
	$theMessage.='<h1 align="center" id="delete_title">supprimer la campagne suivante?</h1>';
	$theMessage.='<h2>'.$lUnite["campagne"].'</h2>';
	$theMessage.='<h3>pays : '.$lUnite["pays"].'</h3>';
	$theMessage.='<h3>systeme : '.$lUnite["systeme"].'</h3>';
	$theMessage.='<h3>num&eacute;ro : '.$lUnite["numero_campagne"].'</h3>';
	$theMessage.='<br /><h2>cela supprimera &eacute;galement : </h2>';
	$theMessage.='<ul>';
	$theMessage.='<li>'.$coupsNombre.' coup(s) de p&ecirc;che</li>';
	$theMessage.='<li>'.$enviroNombre.' environnement(s)</li>';
	$theMessage.='<li>'.$biologieNombre.' enregistrement(s) de biologie</li>';
	$theMessage.='<li>'.$fractionNombre.' fraction(s)</li>';
	$theMessage.='<li>'.$trophiqueNombre.' enregistrement(s) trophique(s)</li>';

} // fin de if $action==ask
// si on en est au stade de la suppression effective
if ($action=="delete") {

// on realise les divers DELETE selon les listes d'id obtenues plus haut

// trophique
if ($trophiqueNombre!=0) {
$sqlDelete='DELETE FROM exp_trophique WHERE id IN ('.$trophiquesListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}
// biologie
if ($biologieNombre!=0) {
$sqlDelete='DELETE FROM exp_biologie WHERE id IN ('.$biologiesListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}
// fraction
if ($fractionNombre!=0) {
$sqlDelete='DELETE FROM exp_fraction WHERE id IN ('.$fractionsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}
// environnement
if ($enviroNombre!=0) {
$sqlDelete='DELETE FROM exp_fraction WHERE id IN ('.$enviroListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}
// coups de peche
if ($coupsNombre!=0) {
$sqlDelete='DELETE FROM exp_coup_peche WHERE id IN ('.$coupsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}
// campagne
$sqlDelete='DELETE FROM exp_campagne WHERE id=\''.$lUnite["id"].'\'';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);


	$theMessage.='<h1 align="center" id="delete_title">campagne supprim&eacute;e :</h1>';
	$theMessage.='<h2>'.$lUnite["campagne"].'</h2>';
	$theMessage.='<h3>pays : '.$lUnite["pays"].'</h3>';
	$theMessage.='<h3>systeme : '.$lUnite["systeme"].'</h3>';
	$theMessage.='<h3>num&eacute;ro : '.$lUnite["numero_campagne"].'</h3>';
	$theMessage.='<br /><h2>enregistrements d&eacute;pendants supprim&eacute;s : </h2>';
	$theMessage.='<ul>';
	$theMessage.='<li>'.$coupsNombre.' coup(s) de p&ecirc;che</li>';
	$theMessage.='<li>'.$enviroNombre.' environnement(s)</li>';
	$theMessage.='<li>'.$biologieNombre.' enregistrement(s) de biologie</li>';
	$theMessage.='<li>'.$fractionNombre.' fraction(s)</li>';
	$theMessage.='<li>'.$trophiqueNombre.' enregistrement(s) trophique(s)</li>';

} // fin de if ($action=="delete")

$theMessage.='</ul></div>';


} // fin de if $domaine==exp

if ($domaine=='art') {

// on recupere des informations sur la periode d'enquete a supprimer
$sql='SELECT DISTINCT description as enquete, annee, mois, date_debut, date_fin, art_agglomeration.id as agglo_id, art_agglomeration.nom as agglomeration, ref_secteur.nom as secteur, ref_systeme.libelle as systeme, ref_pays.nom as pays 
	FROM art_periode_enquete, art_agglomeration, ref_secteur, ref_systeme, ref_pays 
	WHERE art_periode_enquete.id='.$unite;
$sql.=' AND art_periode_enquete.art_agglomeration_id=art_agglomeration.id AND art_agglomeration.ref_secteur_id=ref_secteur.id AND ref_secteur.ref_systeme_id=ref_systeme.id AND ref_systeme.ref_pays_id=ref_pays.id';
$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
$resultArray=pg_fetch_all($result);
$lUnite=$resultArray[0];
pg_free_result($result);

// on recupere le nombre et les id des enregistrements affectes dans les diverses tables dependantes

// debarquement
$sqlLandings='SELECT id FROM art_debarquement d WHERE d.art_agglomeration_id=\''.$lUnite["agglo_id"].'\' AND d.annee=\''.$lUnite["annee"].'\' AND d.mois=\''.$lUnite["mois"].'\'';
$resultLandings=pg_query($connectPPEAO,$sqlLandings) or die('erreur dans la requete : '.$sqlLandings. pg_last_error());
$landings=pg_fetch_all($resultLandings);
pg_free_result($resultLandings);
if (!empty($landings)) {$landingsNombre=count($landings);
	foreach ($landings as $landing) {$landing_id[]=$landing["id"];}
	$landingsListe='\''.arrayToList($landing_id,'\',\'','\'');
	
	// debarquement_rec
	$sqlLandingsRec='SELECT id FROM art_debarquement_rec d WHERE d.art_debarquement_id IN ('.$landingsListe.')';
	$resultLandingsRec=pg_query($connectPPEAO,$sqlLandingsRec) or die('erreur dans la requete : '.$sqlLandingsRec. pg_last_error());
	$landingsrec=pg_fetch_all($resultLandingsRec);
	pg_free_result($resultLandingsRec);
	if (!empty($landingsrec)) {$landingsrecNombre=count($landingsrec);} else {$landingsrecNombre=0;}
	
	// engin_peche
	$sqlEngins='SELECT id FROM art_engin_peche e WHERE e.art_debarquement_id IN ('.$landingsListe.')';
	$resultEngins=pg_query($connectPPEAO,$sqlEngins) or die('erreur dans la requete : '.$sqlEngins. pg_last_error());
	$engins=pg_fetch_all($resultEngins);
	pg_free_result($resultEngins);
	if (!empty($engins)) {$enginsNombre=count($engins);} else {$enginsNombre=0;}
	
	// fractions
	$sqlFractions='SELECT id FROM art_fraction f WHERE f.art_debarquement_id IN ('.$landingsListe.')';
	$resultFractions=pg_query($connectPPEAO,$sqlFractions) or die('erreur dans la requete : '.$sqlFractions. pg_last_error());
	$fractions=pg_fetch_all($resultFractions);
	pg_free_result($resultFractions);
	if (!empty($fractions)) {$fractionsNombre=count($fractions);
		foreach ($fractions as $fraction) {$fraction_id[]=$fraction["id"];}
		$fractionsListe='\''.arrayToList($fraction_id,'\',\'','\'');
		
		// poisson_mesure
		$sqlPoissons='SELECT id FROM art_poisson_mesure p WHERE p.art_fraction_id IN ('.$fractionsListe.')';
		$resultPoissons=pg_query($connectPPEAO,$sqlPoissons) or die('erreur dans la requete : '.$sqlPoissons. pg_last_error());
		$poissons=pg_fetch_all($resultPoissons);
		pg_free_result($resultPoissons);
		if (!empty($poissons)) {$poissonsNombre=count($poissons);} else {$poissonsNombre=0;}
		
		}
		// fin de if (!empty($fractions)) 
		else {$fractionsNombre=0;$poissonsNombre=0;}
	}
	// fin de (!empty($landings))
	else 
	{$landingsNombre=0; $landingsRecNombre=0; $enginsNombre=0; $fractionsNombre=0;$poissonsNombre=0;}
	
// activite
$sql_activites='SELECT id FROM art_activite a WHERE a.art_agglomeration_id=\''.$lUnite["agglo_id"].'\' AND a.annee=\''.$lUnite["annee"].'\' AND a.mois=\''.$lUnite["mois"].'\'';
$result_activites=pg_query($connectPPEAO,$sql_activites) or die('erreur dans la requete : '.$sql_activites. pg_last_error());
$activites=pg_fetch_all($result_activites);
pg_free_result($result_activites);
if (!empty($activites)) {$activitesNombre=count($activites);
	foreach ($activites as $activite) {$activite_id[]=$activite["id"];}
	$activitesListe='\''.arrayToList($activite_id,'\',\'','\'');
	
	// engin_activite
	$sqlEnginsactivite='SELECT id FROM art_engin_activite e WHERE e.art_activite_id IN ('.$activitesListe.')';
	$resultEnginsactivite=pg_query($connectPPEAO,$sqlEnginsactivite) or die('erreur dans la requete : '.$sqlEnginsactivite. pg_last_error());
	$enginsactivite=pg_fetch_all($resultEnginsactivite);
	pg_free_result($resultEnginsactivite);
	if (!empty($enginsactivite)) {$enginsactiviteNombre=count($enginsactivite);} else {$enginsactiviteNombre=0;}
	} 
	// fin de  (!empty($activites))
	else {$activitesNombre=0; $enginsactiviteNombre=0;}
	
// stat_totale
$sqlStat='SELECT id FROM art_stat_totale s WHERE s.art_agglomeration_id=\''.$lUnite["agglo_id"].'\' AND s.annee=\''.$lUnite["annee"].'\' AND s.mois=\''.$lUnite["mois"].'\'';
$resultStat=pg_query($connectPPEAO,$sqlStat) or die('erreur dans la requete : '.$sqlStat. pg_last_error());
$stats=pg_fetch_all($resultStat);
pg_free_result($resultStat);
if (!empty($stats)) {$statsNombre=count($stats);
	foreach ($stats as $stat) {$stat_id[]=$stat["id"];}
	$statsListe='\''.arrayToList($stat_id,'\',\'','\'');
	
	// stat_gt
	$sqlStatgt='SELECT id FROM art_stat_gt s WHERE s.art_stat_totale_id IN ('.$statsListe.')';
	$resultStatgt=pg_query($connectPPEAO,$sqlStatgt) or die('erreur dans la requete : '.$sqlStatgt. pg_last_error());
	$statgts=pg_fetch_all($resultStatgt);
	pg_free_result($resultStatgt);
	if (!empty($statgts)) {$statgtNombre=count($statgts);
		foreach ($statgts as $statgt) {$statgt_id[]=$statgt["id"];}
		$statgtsListe='\''.arrayToList($statgt_id,'\',\'','\'');
		// stat_gt_sp
		$sqlStatgtsp='SELECT id FROM art_stat_gt_sp s WHERE s.art_stat_gt_id IN ('.$statgtsListe.')';
		$resultStatgtsp=pg_query($connectPPEAO,$sqlStatgtsp) or die('erreur dans la requete : '.$sqlStatgtsp. pg_last_error());
		$statgtsps=pg_fetch_all($resultStatgtsp);
		pg_free_result($resultStatgtsp);
		if (!empty($statgtsps)) {$statgtspNombre=count($statgts);
			foreach ($statgtsps as $statgtsp) {$statgtsp_id[]=$statgtsp["id"];}
			$statgtspsListe='\''.arrayToList($statgtsp_id,'\',\'','\'');
			
			// taille_gt_sp
			$sqlTaillegtsps='SELECT id FROM art_taille_gt_sp t WHERE t.art_stat_gt_sp_id IN ('.$statgtspsListe.')';
			$resultTaillegtsps=pg_query($connectPPEAO,$sqlTaillegtsps) or die('erreur dans la requete : '.$sqlTaillegtsps. pg_last_error());
			$taillegtsps=pg_fetch_all($resultTaillegtsps);
			pg_free_result($resultTaillegtsps);
			if (!empty($taillegtsps)) {$taillegtspNombre=count($taillegtsps);} else {$taillegtspNombre=0;}
			}
			// fin de if (!empty($statgtsps))
			 else {$statgtspNombre=0;$taillegtspNombre=0;}
		}
		// end if (!empty($statgts))
		 else {$statgtNombre=0;$statgtspNombre=0;$taillegtspNombre=0;}
	
	// stat_sp
	$sqlStatsp='SELECT id FROM art_stat_sp s WHERE s.art_stat_totale_id IN ('.$statsListe.')';
	$resultStatsp=pg_query($connectPPEAO,$sqlStatsp) or die('erreur dans la requete : '.$sqlStatsp. pg_last_error());
	$statsps=pg_fetch_all($resultStatsp);
	pg_free_result($resultStatsp);
	if (!empty($statsps)) {$statspNombre=count($statsps);
		foreach ($statsps as $statsp) {$statsp_id[]=$statsp["id"];}
		$statspsListe='\''.arrayToList($statsp_id,'\',\'','\'');
		
		// taille_sp
		$sqlTaillesps='SELECT id FROM art_taille_sp s WHERE s.art_stat_sp_id IN ('.$statspsListe.')';
		$resultTaillesps=pg_query($connectPPEAO,$sqlTaillesps) or die('erreur dans la requete : '.$sqlTaillesps. pg_last_error());
		$taillesps=pg_fetch_all($resultTaillesps);
		pg_free_result($resultTaillesps);
		if (!empty($taillesps)) {$taillespNombre=count($taillesps);} else {$taillespNombre=0;}
		}
		// end if (!empty($statsps))
		else {$statspNombre=0;$taillespNombre=0;}
	
	}
	// fin de if (!empty($stats))
	
	 else {$statsNombre=0;$statgtNombre=0;$statgtspNombre=0;$taillegtspNombre=0;$statspNombre=0;$taillespNombre=0;}

// si on en est au stade de la confirmation de la suppression
if ($action=='ask') {
// on compose le message a afficher et on realise les actions a faire
	$theMessage="<div>";
	$theMessage.='<h1 align="center" id="delete_title">supprimer la p&eacute;riode d&#x27;enqu&ecirc;te suivante?</h1>';
	$theMessage.='<h2>'.$lUnite["enquete"].'</h2>';
	$theMessage.='<h3>pays : '.$lUnite["pays"].'</h3>';
	$theMessage.='<h3>systeme : '.$lUnite["systeme"].'</h3>';
	$theMessage.='<h3>secteur : '.$lUnite["secteur"].'</h3>';
	$theMessage.='<h3>agglom&eacute;ration : '.$lUnite["agglomeration"].'</h3>';
	$theMessage.='<br /><h2>cela supprimera &eacute;galement : </h2>';
	$theMessage.='<ul>';
	$theMessage.='<li>'.$landingsNombre.' d&eacute;barquement(s)</li>';
	$theMessage.='<li>'.$landingsrecNombre.' d&eacute;barquement(s) recompos&eacute;(s)</li>';
	$theMessage.='<li>'.$enginsNombre.' enregistrements d\'engins/p&ecirc;che</li>';
	$theMessage.='<li>'.$fractionsNombre.' fraction(s)</li>';
	$theMessage.='<li>'.$poissonsNombre.' poisson(s) mesur&eacute;(s)</li>';
	$theMessage.='<li>'.$activitesNombre.' activit&eacute;(s)</li>';
	$theMessage.='<li>'.$enginsactiviteNombre.' enregistrement(s) d&#x27;engin/activit&eacute;</li>';
	$theMessage.='<li>'.$statsNombre.' enregistrement(s) de statistique(s) totale(s)</li>';
	$theMessage.='<li>'.$statgtNombre.' enregistrement(s) de art_stat_gt</li>';
	$theMessage.='<li>'.$statgtspNombre.' enregistrement(s) de art_stat_gt_sp</li>';
	$theMessage.='<li>'.$taillegtspNombre.' enregistrement(s) de art_taille_gt_sp</li>';
	$theMessage.='<li>'.$statspNombre.' enregistrement(s) de art_stat_sp</li>';
	$theMessage.='<li>'.$taillespNombre.' enregistrement(s) de art_taille_sp</li>';



} 
// fin de if $action==ask

// si on en est au stade de la suppression effective
if ($action=='delete') {
// on realise les divers DELETE selon les listes d'id obtenues plus haut

// taille_sp
if ($taillespsNombre!=0) {
$sqlDelete='DELETE FROM art_taille_sp t WHERE s.art_stat_sp_id IN ('.$statspsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// art_stat_sp
if ($statspsNombre!=0) {
$sqlDelete='DELETE FROM art_stat_sp s WHERE s.art_stat_totale_id IN ('.$statsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

//art_taille_gt_sp
if ($taillegtspsNombre!=0) {
$sqlDelete='DELETE FROM art_taille_gt_sp t WHERE s.art_stat_gt_sp_id IN ('.$statgtspsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

//art_stat_gt_sp
if ($statgtspsNombre!=0) {
$sqlDelete='DELETE FROM art_stat_gt_sp s WHERE s.art_stat_gt_id IN ('.$statgtsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

//art_stat_gt
if ($statgtsNombre!=0) {
$sqlDelete='DELETE FROM art_stat_gt s WHERE s.art_stat_totale_id IN ('.$statsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// stat_totale
if ($statsNombre!=0) {
$sqlDelete='DELETE FROM art_stat_totale s WHERE s.art_agglomeration_id=\''.$lUnite["agglo_id"].'\' AND s.annee=\''.$lUnite["annee"].'\' AND s.mois=\''.$lUnite["mois"].'\'';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// engin_activite
if ($enginsactiviteNombre!=0) {
$sqlDelete='DELETE FROM art_engin_activite e WHERE e.art_activite_id IN ('.$activitesListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// activite
if ($enginsactiviteNombre!=0) {
$sqlDelete='DELETE FROM art_activite a WHERE a.art_agglomeration_id=\''.$lUnite["agglo_id"].'\' AND a.annee=\''.$lUnite["annee"].'\' AND a.mois=\''.$lUnite["mois"].'\'';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// poisson mesure
if ($poissonsNombre!=0) {
$sqlDelete='DELETE FROM art_poisson_mesure p WHERE p.art_fraction_id IN ('.$fractionsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// fraction
if ($fractionsNombre!=0) {
$sqlDelete='DELETE FROM art_fraction f WHERE f.art_debarquement_id IN ('.$landingsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// engin_peche
if ($enginsNombre!=0) {
$sqlDelete='DELETE FROM art_engin_peche e WHERE e.art_debarquement_id IN ('.$landingsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// debarquement_rec
if ($landingsrecNombre!=0) {
$sqlDelete='DELETE FROM art_debarquement_rec d WHERE d.art_debarquement_id IN ('.$landingsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// art_debarquement
if ($landingsNombre!=0) {
$sqlDelete='DELETE FROM art_debarquement WHERE id IN ('.$landingsListe.')';
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);}

// periode_enquete
$sqlDelete='DELETE FROM art_periode_enquete WHERE id='.$unite;
$resultDelete=pg_query($connectPPEAO,$sqlDelete) or die('erreur dans la requete : '.$sqlDelete. pg_last_error());
pg_free_result($resultDelete);


// on affiche le recapitulatif de ce qui a ete supprime
	$theMessage.='<h1 align="center" id="delete_title">p&eacute;riode d&#x27;enqu&ecirc;te supprim&eacute;e :</h1>';
	$theMessage.='<h2>'.$lUnite["enquete"].'</h2>';
	$theMessage.='<h3>pays : '.$lUnite["pays"].'</h3>';
	$theMessage.='<h3>systeme : '.$lUnite["systeme"].'</h3>';
	$theMessage.='<h3>secteur : '.$lUnite["secteur"].'</h3>';
	$theMessage.='<h3>agglom&eacute;ration : '.$lUnite["agglomeration"].'</h3>';
	$theMessage.='<br /><h2>enregistrements d&eacute;pendants supprim&eacute;s : </h2>';
	$theMessage.='<ul>';
	$theMessage.='<li>'.$landingsNombre.' d&eacute;barquement(s)</li>';
	$theMessage.='<li>'.$landingsrecNombre.' d&eacute;barquement(s) recompos&eacute;(s)</li>';
	$theMessage.='<li>'.$enginsNombre.' engins</li>';
	$theMessage.='<li>'.$fractionsNombre.' fraction(s)</li>';
	$theMessage.='<li>'.$poissonsNombre.' poisson(s) mesur&eacute;(s)</li>';
	$theMessage.='<li>'.$activitesNombre.' activit&eacute;(s)</li>';
	$theMessage.='<li>'.$enginsactiviteNombre.' enregistrement(s) d&#x27;engin/activit&eacute;</li>';
	$theMessage.='<li>'.$statsNombre.' enregistrement(s) de statistique(s) totale(s)</li>';
	$theMessage.='<li>'.$statgtNombre.' enregistrement(s) de art_stat_gt</li>';
	$theMessage.='<li>'.$statgtspNombre.' enregistrement(s) de art_stat_gt_sp</li>';
	$theMessage.='<li>'.$taillegtspNombre.' enregistrement(s) de art_taille_gt_sp</li>';
	$theMessage.='<li>'.$statspNombre.' enregistrement(s) de art_stat_sp</li>';
	$theMessage.='<li>'.$taillespNombre.' enregistrement(s) de art_taille_sp</li>';

} 
// fin de if ($action=='delete')

$theMessage.='</ul></div>';
} 
// fin de if ($domaine=='art')

// on renvoie le message de résultat
echo($theMessage);
?>