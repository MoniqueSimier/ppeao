<?php
/***********
/ fonctions utilisees dans la selection en vue de l'extraction
*/



//******************************************************************************
// compte le nombre de campagnes ou enquetes a supprimer - evolution de countMatchingUnits()
function countMatchingUnits2($domaine) {

global $connectPPEAO;

if ($domaine=='exp') {
$sql="SELECT DISTINCT id FROM exp_campagne WHERE TRUE ";
	
	// si des valeurs de familles ont ete passees dans l'url
	if (!empty($_GET["familles"])) {
		$sql.=' AND exp_campagne.id IN (
			SELECT exp_coup_peche.exp_campagne_id FROM exp_coup_peche WHERE exp_coup_peche.id 
			IN (
			SELECT DISTINCT exp_fraction.exp_coup_peche_id 
			FROM exp_fraction WHERE exp_fraction.ref_espece_id 
			IN (
				SELECT ref_espece.id 
				FROM ref_espece 
				WHERE ref_espece.ref_famille_id 
				IN ('.arrayToList($_GET["familles"],',','').')
				)
			)
			)';
	} // fin de if (!empty($_GET["familles"]))
		// si des valeurs d'especes ont ete passees dans l'url
	if (!empty($_GET["especes"]) && $_GET["step"]>2) {
		$sql.=' AND exp_campagne.id IN (
			SELECT exp_coup_peche.exp_campagne_id FROM exp_coup_peche WHERE exp_coup_peche.id 
			IN (
				SELECT DISTINCT exp_fraction.exp_coup_peche_id 
				FROM exp_fraction WHERE exp_fraction.ref_espece_id 
				IN (\''.arrayToList($_GET["especes"],'\',\'','\'').')
				)
			)';
	} // fin de if (!empty($_GET["familles"]))
	
	// si des valeurs de pays ont ete passees dans l'url
	if (!empty($_GET["pays"]) && $_GET["step"]>3) {
		$sql.=' AND exp_campagne.ref_systeme_id IN (SELECT DISTINCT ref_systeme.id FROM ref_systeme WHERE ref_systeme.ref_pays_id IN (\''.arrayToList($_GET["pays"],'\',\'','\'').')) ';
		}
	// si des valeurs de systeme ont ete passees dans l'url
	if (!empty($_GET["systemes"])  && $_GET["step"]>3) {
		$sql.=' AND exp_campagne.ref_systeme_id IN (\''.arrayToList($_GET["systemes"],'\',\'','\'').')';
		}
	// si une valeur de debut_annee a ete passee dans l'url
	if (!empty($_GET["d_a"])  && $_GET["step"]>4) {
		$debut_annee=$_GET["d_a"];
		// si aucun mois n'a ete passe, on utilise janvier soit 1
		if (empty($_GET["d_m"])) {$debut_mois=1;} else {$debut_mois=$_GET["d_m"];}
		// on construit une date a partir de l'annee et du mois
		$debut_date=$debut_annee.'-'.$debut_mois.'-01';
		$sql.=' AND exp_campagne.date_debut>=\''.$debut_date.'\' ';
		}
	// si une valeur de fin_annee a ete passee dans l'url
	if (!empty($_GET["f_a"]) && $_GET["step"]>4) {
		$fin_annee=$_GET["f_a"];
		// si aucun mois n'a ete passe, on utilise janvier soit 1
		if (empty($_GET["f_m"])) {$fin_mois=1;} else {$fin_mois=$_GET["f_m"];}
		// on construit une date a partir de l'annee et du mois
		$fin_date=$fin_annee.'-'.$fin_mois.'-'.days_in_month($fin_annee,$fin_mois);
		$sql.=' AND exp_campagne.date_debut<=\''.$fin_date.'\' ';
		}
	// si des valeurs de campagnes ont ete passees dans l'url
	if (!empty($_GET["camp"]) && $_GET["step"]>8) {
		$sql.=' AND exp_campagne.id IN (\''.arrayToList($_GET["camp"],'\',\'','\'').')';
	}
	// si des valeurs d'engins ont ete passees dans l'url
	if (!empty($_GET["eng"])) {
		$sql.=' AND exp_campagne.id IN (
			SELECT exp_coup_peche.exp_campagne_id FROM exp_coup_peche WHERE exp_coup_peche.exp_engin_id IN
				(\''.arrayToList($_GET["eng"],'\',\'','\'').')
			)';
	}
} // fin de if ($domaine=='exp') 

if ($domaine=='art') {
$sql="SELECT DISTINCT id FROM art_periode_enquete WHERE TRUE ";
	
	// si des valeurs d'especes ont ete passees dans l'url
if (!empty($_GET["especes"]) && $_GET["step"]>2) {
	$sql.=' AND art_periode_enquete.art_agglomeration_id IN(
		SELECT d.art_agglomeration_id 
		FROM art_debarquement d 
		WHERE d.id IN (
			SELECT f.art_debarquement_id 
			FROM art_fraction f 
			WHERE f.ref_espece_id IN (\''.arrayToList($_GET["especes"],'\',\'','\'').')
		)
	) 
	AND art_periode_enquete.annee IN (
	SELECT d.annee 
	FROM art_debarquement d 
	WHERE d.id IN (
		SELECT f.art_debarquement_id 
		FROM art_fraction f 
		WHERE f.ref_espece_id IN (\''.arrayToList($_GET["especes"],'\',\'','\'').')
		)
	) 
	AND art_periode_enquete.mois IN (
	SELECT d.mois 
	FROM art_debarquement d 
	WHERE d.id IN (
		SELECT f.art_debarquement_id 
		FROM art_fraction f 
		WHERE f.ref_espece_id IN (\''.arrayToList($_GET["especes"],'\',\'','\'').')
		)
	)';
} // fin de if (!empty($_GET["especes"]))

// si des valeurs de familles ont ete passees dans l'url
if (!empty($_GET["familles"]) && $_GET["step"]>2) {
	$sql.=' AND art_periode_enquete.art_agglomeration_id IN(
		SELECT d.art_agglomeration_id 
		FROM art_debarquement d 
		WHERE d.id IN (
			SELECT f.art_debarquement_id 
			FROM art_fraction f 
			WHERE f.ref_espece_id IN (
				SELECT e.id FROM ref_espece e WHERE e.ref_famille_id IN (\''.arrayToList($_GET["familles"],'\',\'','\'').')
				)
		)
	) 
	AND art_periode_enquete.annee IN (
	SELECT d.annee 
	FROM art_debarquement d 
	WHERE d.id IN (
		SELECT f.art_debarquement_id 
		FROM art_fraction f 
		WHERE f.ref_espece_id IN (
			SELECT e.id FROM ref_espece e WHERE e.ref_famille_id IN (\''.arrayToList($_GET["familles"],'\',\'','\'').')
			)
		)
	) 
	AND art_periode_enquete.mois IN (
	SELECT d.mois 
	FROM art_debarquement d 
	WHERE d.id IN (
		SELECT f.art_debarquement_id 
		FROM art_fraction f 
		WHERE f.ref_espece_id IN (
			SELECT e.id FROM ref_espece e WHERE e.ref_famille_id IN (\''.arrayToList($_GET["familles"],'\',\'','\'').')
			)
		)
	)';
} // fin de if (!empty($_GET["especes"]))
	
	// si des valeurs de pays ont ete passees dans l'url
	if (!empty($_GET["pays"]) && $_GET["step"]>3) {
		$sql.=' AND art_periode_enquete.art_agglomeration_id IN (SELECT DISTINCT art_agglomeration.id FROM art_agglomeration WHERE
 art_agglomeration.ref_secteur_id IN (SELECT DISTINCT ref_secteur.id FROM ref_secteur WHERE ref_secteur.id IN (SELECT DISTINCT ref_secteur.id FROM ref_secteur WHERE ref_secteur.ref_systeme_id IN (SELECT DISTINCT ref_systeme.id FROM ref_systeme WHERE ref_systeme.ref_pays_id IN (\''.arrayToList($_GET["pays"],'\',\'','\'').')))))';
		}
	// si des valeurs de systeme ont ete passees dans l'url
	if (!empty($_GET["systemes"]) && $_GET["step"]>3) {
		$sql.=' AND art_periode_enquete.art_agglomeration_id IN (SELECT DISTINCT art_agglomeration.id FROM art_agglomeration WHERE
 art_agglomeration.ref_secteur_id IN (SELECT DISTINCT ref_secteur.id FROM ref_secteur WHERE ref_secteur.id IN (SELECT DISTINCT ref_secteur.id FROM ref_secteur WHERE ref_secteur.ref_systeme_id IN  (\''.arrayToList($_GET["systemes"],'\',\'','\'').'))))';
		}
	// si des valeurs de secteur ont ete passees dans l'url
	if (!empty($_GET["secteurs"]) && $_GET["step"]>7) {
		$sql.=' AND art_periode_enquete.art_agglomeration_id IN (SELECT DISTINCT art_agglomeration.id FROM art_agglomeration WHERE
 art_agglomeration.ref_secteur_id IN (SELECT DISTINCT ref_secteur.id FROM ref_secteur WHERE ref_secteur.id IN  (\''.arrayToList($_GET["secteurs"],'\',\'','\'').')))';
		}
		// si des valeurs d'agglomeration ont ete passees dans l'url
	if (!empty($_GET["agglomerations"]) && $_GET["step"]>8) {
		$sql.=' AND art_periode_enquete.art_agglomeration_id IN (\''.arrayToList($_GET["agglomerations"],'\',\'','\'').')';
		}
		// si une valeur de debut_annee a ete passee dans l'url
	if (!empty($_GET["d_a"]) && $_GET["step"]>4) {
		$debut_annee=$_GET["d_a"];
		// si aucun mois n'a ete passe, on utilise janvier soit 1
		if (empty($_GET["d_m"])) {$debut_mois=1;} else {$debut_mois=$_GET["d_m"];}
		// on construit une date a partir de l'annee et du mois
		$debut_date=$debut_annee.'-'.$debut_mois.'-01';
		$sql.=' AND art_periode_enquete.date_debut>=\''.$debut_date.'\' ';
		}
	// si une valeur de fin_annee a ete passee dans l'url
	if (!empty($_GET["f_a"]) && $_GET["step"]>4) {
		$fin_annee=$_GET["f_a"];
		// si aucun mois n'a ete passe, on utilise janvier soit 1
		if (empty($_GET["f_m"])) {$fin_mois=1;} else {$fin_mois=$_GET["f_m"];}
		// on construit une date a partir de l'annee et du mois
		$fin_date=$fin_annee.'-'.$fin_mois.'-'.days_in_month($fin_annee,$fin_mois);
		$sql.=' AND art_periode_enquete.date_debut<=\''.$fin_date.'\' ';
		}
		
	// si des valeurs de periodes d'enquete ont ete passees dans l'url
	if (!empty($_GET["enq"]) && $_GET["step"]>9) {
		$sql.=' AND art_periode_enquete.id IN (\''.arrayToList($_GET["enq"],'\',\'','\'').')';
	}
	
	// si des valeurs de grands types d'engins ont ete passees dans l'url
	// le step differe selon que l'on a affaire aux stats par agglo (10) ou generales (8)
	if ($_GET["stats"]=='gen') {$theStep=8;} else {$theStep=10;}
	if (!empty($_GET["gteng"]) && $_GET["step"]>$theStep) {
		$sql.=' 
		 AND art_periode_enquete.id IN (
			SELECT DISTINCT pe.id  
			FROM art_periode_enquete pe WHERE 
				pe.art_agglomeration_id IN (
					SELECT DISTINCT d.art_agglomeration_id FROM art_debarquement d WHERE d.art_grand_type_engin_id IN (
						\''.arrayToList($_GET["gteng"],'\',\'','\'').')
				) 
				AND pe.annee IN (
					SELECT DISTINCT d.annee FROM art_debarquement d WHERE d.art_grand_type_engin_id IN (
						\''.arrayToList($_GET["gteng"],'\',\'','\'').')
				) AND pe.mois IN (
					SELECT DISTINCT d.mois FROM art_debarquement d WHERE d.art_grand_type_engin_id IN (
						\''.arrayToList($_GET["gteng"],'\',\'','\'').')
				)
			)
		';
	}
	
} // fin de if ($domaine=='art')

// debug echo($sql);
	$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
	$totalArray=pg_fetch_all($result);
	pg_free_result($result);	

if (empty($totalArray)) {$total=0;$ids=array();} 
	else {
		$total=count($totalArray);
		foreach($totalArray as $row) {
			$ids[]=$row["id"];
		}
	}
$unites=array("total"=>$total,"ids"=>$ids);

// maintenant on calcule le nombre de coups de peche (exp) ou de periodes d'enquete/activites
$coups=array();$debarquements=array();$activites=array();

if ($domaine=='exp') {
	if (!empty($ids)) {
	// exp : on cherche les coups de peche
	$sql='SELECT DISTINCT exp_coup_peche.id FROM exp_coup_peche WHERE exp_campagne_id IN (\''.arrayToList($unites["ids"],'\',\'','\'').')';
	// si on a passe des secteurs dans l'url
	if (!empty($_GET["secteurs"])) {
		$sql.=' AND exp_station_id IN (
			SELECT exp_station.id FROM exp_station 
			WHERE exp_station.ref_secteur_id IN (\''.arrayToList($_GET["secteurs"],'\',\'','\'').')
			)';
	}
	// si on a passe des engins dans l'url
	if (!empty($_GET["eng"])) {
		$sql.=' AND exp_engin_id IN  (\''.arrayToList($_GET["eng"],'\',\'','\'').') ';
	}
	$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
	$coups_array=pg_fetch_all($result);
	pg_free_result($result);
	
if (empty($coups_array)) {$coups_total=0;$coups_ids=array();} 
	else {
		$coups_total=count($coups_array);
		foreach($coups_array as $row) {
			$coups_ids[]=$row["id"];
		}
	}	
	$coups=array("coups_total"=>$coups_total,"coups_ids"=>$coups_ids);}
else {
	$coups=array("coups_total"=>0,"coups_ids"=>array());
	}

} // fin de if ($domaine=='exp')

if ($domaine=='art') {
	// art : on cherche les debarquements
	if (!empty($ids)) {
	$debarquements_array=array();
	foreach($unites["ids"] as $enquete) {
	$sql='SELECT art_debarquement.id 
	FROM art_debarquement 
	WHERE art_agglomeration_id IN 
		(SELECT art_agglomeration_id FROM art_periode_enquete WHERE art_periode_enquete.id='.$enquete.') 
		AND art_debarquement.annee=(SELECT annee  FROM art_periode_enquete WHERE art_periode_enquete.id='.$enquete.') 
		AND art_debarquement.mois=(SELECT mois  FROM art_periode_enquete WHERE art_periode_enquete.id='.$enquete.')
		';
	// si on a passe des grands types d'engins dans l'url
		// le step differe selon que l'on a affaire aux stats par agglo (10) ou generales (8)
	if ($_GET["stats"]=='gen') {$theStep=8;} else {$theStep=10;}
	if (!empty($_GET["gteng"]) && $_GET["step"]>$theStep)
 		{
		$sql.=' AND art_grand_type_engin_id IN (\''.arrayToList($_GET["gteng"],'\',\'','\'').') ';
	}
	$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
	$array=pg_fetch_all($result);
	if (!empty($array)) {foreach($array as $row) {$debarquements_array[]=$row["id"];}}
	pg_free_result($result);
	} // end foreach $unite[ids]
if (empty($debarquements_array)) {$debarquements_total=0;$debarquements_ids=array();} 
	else {
		$debarquements_total=count($debarquements_array);
		foreach($debarquements_array as $row) {
			$debarquements_ids[]=$row;
		}
	$debarquements=array("debarquements_total"=>$debarquements_total,"debarquements_ids"=>$debarquements_ids);
	}
	
	
	// art : on cherche les activites
	$activites_array=array();
	foreach($unites["ids"] as $enquete) {
	$sql='SELECT art_activite.id 
	FROM art_activite 
	WHERE art_agglomeration_id IN 
		(SELECT art_agglomeration_id FROM art_periode_enquete WHERE art_periode_enquete.id='.$enquete.') 
		AND art_activite.annee=(SELECT annee  FROM art_periode_enquete WHERE art_periode_enquete.id='.$enquete.') 
		AND art_activite.mois=(SELECT mois  FROM art_periode_enquete WHERE art_periode_enquete.id='.$enquete.')
		';	
	// si on a passe des grands types d'engins dans l'url
		// le step differe selon que l'on a affaire aux stats par agglo (10) ou generales (8)
	if ($_GET["stats"]=='gen') {$theStep=8;} else {$theStep=10;}
	if (!empty($_GET["gteng"]) && $_GET["step"]>$theStep) {
		$sql.=' AND art_grand_type_engin_id IN (\''.arrayToList($_GET["gteng"],'\',\'','\'').') ';	
	}
	$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
	$array=pg_fetch_all($result);
	if (!empty($array)) {foreach($array as $row) {$activites_array[]=$row["id"];}}
	pg_free_result($result);
	} // end foreach $unite[ids]
if (empty($activites_array)) {$activites_total=0;$activites_ids=array();} 
	else {
		$activites_total=count($activites_array);
		foreach($activites_array as $row) {
			$activites_ids[]=$row;
		}
	}

$activites=array("activites_total"=>$activites_total,"activites_ids"=>$activites_ids);
	}
	else {
	$debarquements=array("debarquements_total"=>0,"debarquements_ids"=>array());
	$activites=array("activites_total"=>0,"activites_ids"=>array());
	}
} // fin de if ($domaine=='art')

	
// on ajoute les resultats au tableau $unites
$unites["coups"]=$coups;
$unites["debarquements"]=$debarquements;
$unites["activites"]=$activites;

//debug echo('<pre>');print_r($unites);echo('</pre>');

return $unites;

}



//******************************************************************************
// prepare le compteur indiquant le nombre de campagnes/periodes d'enquete correspondant a la selection en cours
function prepareCompteur() {

// on prepare le compteur

// on commence par savoir si on se base sur un modele de peches exp ou art
$peches='';
if ($_GET["donnees"]=="exp") {$peches='exp';}
if ($_GET["donnees"]=="art") {$peches='art';}
// les statistiques ne sont realisees que sur les peches artisanales
if ($_GET["exploit"]=="stats") {$peches='art';}

// si on a depasse la premiere etape, on affiche le lien permettant d'afficher ou masquer la selection
// et on affiche "votre selection correspond a :"
if ($_GET["step"]>1) {
	$link='<span class="showHide"><a id="selection_precedente_toggle" onclick="javascript:toggleSelection();" title="afficher ou masquer la selection" href="#">[afficher/modifier/masquer la s&eacute;lection]</a></span>';
	$text='votre s&eacute;lection correspond &agrave; :';
} else {
	$link='';
	$text='donn&eacute;es disponibles :';
	}

switch ($peches) {
	case "exp":
	// Peches experimentales
	// on compte les campagnes
	$campagnes=countMatchingUnits2('exp');
	$total_campagnes=$campagnes["total"];
	if ($total_campagnes>0) {$texte_coups=' &ndash; '.$campagnes["coups"]["coups_total"].' coup(s) de p&ecirc;che)'; } 
	else {$texte_coups='';}
	$compteur=array("campagnes_ids"=>$campagnes["ids"],
				"campagnes_total"=>$total_campagnes,
				"coups_ids"=>$campagnes["coups"]["coups_ids"],
				"coups_total"=>$campagnes["coups"]["coups_total"],
				"texte"=>'<div id="ex_compteur"><p>'.$text.$link.'</p><ul><li>'.$total_campagnes.' campagne(s)'.$texte_coups.'</li></ul></div>');
	break;
	case "art":
	// on compte les periodes d'enquete
	$enquetes=countMatchingUnits2('art');
	$total_enquetes=$enquetes["total"];
	if ($total_enquetes>0) {$texte_deb_act=' &ndash;'.$enquetes["debarquements"]["debarquements_total"].' d&eacute;barquement(s) et '.$enquetes["activites"]["activites_total"].' activit&eacute;(s).'; } 
	else {$texte_deb_act='';}
	$compteur=array("enquetes_ids"=>$enquetes["ids"],
				"enquetes_total"=>$total_enquetes,
				"debarquements_total"=>$enquetes["debarquements"]["debarquements_total"],
				"debarquements_ids"=>$enquetes["debarquements"]["debarquements_ids"],
				"activites_total"=>$enquetes["activites"]["activites_total"],
				"activites_ids"=>$enquetes["activites"]["activites_ids"],
				"texte"=>'<div id="ex_compteur"><p>'.$text.$link.'</p><ul><li>'.$total_enquetes.' p&eacute;riode(s) d&#x27;enqu&ecirc;te'.$texte_deb_act.'</li></ul></div>');
	break;
	default:
	// avant le choix de exp ou art : 
	// on compte les campagnes et les enquetes
		$campagnes=countMatchingUnits2('exp');
	$total_campagnes=$campagnes["total"];
	if ($total_campagnes>0) {$texte_coups=' &ndash; '.$campagnes["coups"]["coups_total"].' coup(s) de p&ecirc;che)'; } 
	else {$texte_coups='';}
		$enquetes=countMatchingUnits2('art');
	$total_enquetes=$enquetes["total"];
	if ($total_enquetes>0) {$texte_deb_act=' &ndash;'.$enquetes["debarquements"]["debarquements_total"].' d&eacute;barquement(s) et '.$enquetes["activites"]["activites_total"].' activit&eacute;(s).'; } 
	else {$texte_deb_act='';}
	$compteur=array("campagnes_ids"=>$campagnes["ids"],
				"campagnes_total"=>$total_campagnes,
				"coups_ids"=>$campagnes["coups"]["coups_ids"],
				"coups_total"=>$campagnes["coups"]["coups_total"],
				"enquetes_ids"=>$enquetes["ids"],
				"enquetes_total"=>$total_enquetes,
				"debarquements_total"=>$enquetes["debarquements"]["debarquements_total"],
				"debarquements_ids"=>$enquetes["debarquements"]["debarquements_ids"],
				"activites_total"=>$enquetes["activites"]["activites_total"],
				"activites_ids"=>$enquetes["activites"]["activites_ids"],
				"texte"=>'<div id="ex_compteur"><p>'.$text.$link.'</p><ul><li>'.$total_campagnes.' campagne(s)'.$texte_coups.'</li><li>'.$total_enquetes.' p&eacute;riode(s) d&#x27;enqu&ecirc;te'.$texte_deb_act.'</li></ul></div>');
	break;
				
	} // end switch $exploit
				


return $compteur;


}

//******************************************************************************
// recupere la liste des id des systemes correspondant aux campagnes, enquetes et pays selectionnes
function listSelectSystemes($pays,$campagnes_ids,$enquetes_ids) {
	// la connextion a la base
	global $connectPPEAO;
	//$pays: la liste des id des pays selectionnes
	//$campagnes_ids: la liste des id des campagnes filtrees
	//$enquetes_ids: la liste des id des enquetes filtrees

	

// on recupere la liste des systemes correspondant aux campagnes et enquetes correspondant a la selection precedente
	$sql_systemes='	SELECT DISTINCT ref_systeme.id, ref_systeme.libelle 
				FROM ref_systeme
				WHERE TRUE';
	// si on a choisi des valeurs de pays
	if (!empty($pays)) {
	$sql_systemes.=' AND ref_systeme.ref_pays_id IN (\''.arrayToList($pays,'\',\'','\'').')';
	}
	$sql_systemes.=' AND ref_systeme.id IN ';
	$sql_systemes.=' (SELECT DISTINCT exp_campagne.ref_systeme_id FROM exp_campagne WHERE TRUE ';
		// si on a deja filtre les campagnes (par especes ou familles)
		//debug 		echo('<pre>');print_r($campagnes_ids);echo('</pre>');
		
		if (!empty($campagnes_ids[0])) {
		$sql_systemes.=' AND exp_campagne.id IN (\''.arrayToList($campagnes_ids,'\',\'','\'').')';
		}
	$sql_systemes.=') ';
		$sql_systemes.=' OR ref_systeme.id IN (
		SELECT DISTINCT art_agglomeration.ref_secteur_id 
		FROM art_agglomeration 
		WHERE art_agglomeration.id IN (
			SELECT DISTINCT art_periode_enquete.art_agglomeration_id 
			FROM art_periode_enquete 
			WHERE TRUE ';
		// si on a deja filtre les enquetes (par especes ou familles)
			if (!empty($enquetes_ids[0])) {$sql_systemes.=' AND art_periode_enquete.id IN( 
												\''.arrayToList($enquetes_ids,'\',\'','\'').')';}
	$sql_systemes.='))';
	
	//debug	echo($sql_systemes);
	
	$result_systemes=pg_query($connectPPEAO,$sql_systemes) or die('erreur dans la requete : '.$sql_systemes. pg_last_error());
	$array_systemes=pg_fetch_all($result_systemes);
	pg_free_result($result_systemes);
	
		return $array_systemes;
	} // end function listSelectSystemes()



//******************************************************************************
// recupere la liste des id des secteurs correspondant aux enquetes et systemes
function listSelectSecteurs($systemes,$enquetes_ids) {
	// la connextion a la base
	global $connectPPEAO;
	//$enquetes_ids (array): la liste des id des enquetes filtrees
	//$systemes (array): les systemes selectionnes
	
	$sql='SELECT rs.id, rs.nom as secteur, rsy.libelle as systeme FROM ref_secteur rs, ref_systeme as  rsy
			WHERE rs.id IN (
				SELECT aa.ref_secteur_id FROM art_agglomeration aa 
				WHERE aa.id IN (
					SELECT DISTINCT art_agglomeration_id FROM art_periode_enquete ape 
					WHERE ape.id IN (\''.arrayToList($enquetes_ids,'\',\'','\'').')
					)
			)';
	if (!empty($systemes)) {
	$sql.=' AND rs.ref_systeme_id IN (\''.arrayToList($systemes,'\',\'','\'').'
		)';
	}
	
	$sql.='AND rs.ref_systeme_id=rsy.id';
	
	
	
	//debug 	echo('<pre>');print_r($sql);echo('</pre>');
	
	
	$result_secteurs=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
	$array_secteurs=pg_fetch_all($result_secteurs);
	pg_free_result($result_secteurs);
	
		return $array_secteurs;
} // end function listSelectSecteurs()

//******************************************************************************
// affiche le bloc permettant d'indiquer si l'on veut choisir ou non des especes

function prepareSelectionEditLink($step) {
//$step: l'etape a laquelle on veut revenir

// on remplace le param step avec l'etape cible
$edit_link=replaceQueryParam ($_SERVER['FULL_URL'],'step',$step);	


if ($step<10) {
	$edit_link=removeQueryStringParam($edit_link,'g_t_eng\[\]');
}

if ($step<9) {
	$edit_link=removeQueryStringParam($edit_link,'eng\[\]');
	$edit_link=removeQueryStringParam($edit_link,'p_enq\[\]');
}

if ($step<8) {
	$edit_link=removeQueryStringParam($edit_link,'camp\[\]');
	$edit_link=removeQueryStringParam($edit_link,'agglo\[\]');
}

if ($step<7) {
	$edit_link=removeQueryStringParam($edit_link,'secteurs\[\]');
	$edit_link=removeQueryStringParam($edit_link,'systemes2\[\]');
	$edit_link=removeQueryStringParam($edit_link,'g_t_eng\[\]');

}

if ($step<=6) {
	$edit_link=removeQueryStringParam($edit_link,'donnees');
	$edit_link=removeQueryStringParam($edit_link,'stats');
}

if ($step<=5) {
	$edit_link=removeQueryStringParam($edit_link,'exploit');
}

if ($step<4) {
	$edit_link=removeQueryStringParam($edit_link, 'd_a');
	$edit_link=removeQueryStringParam($edit_link, 'd_m');
	$edit_link=removeQueryStringParam($edit_link, 'f_a');
	$edit_link=removeQueryStringParam($edit_link, 'f_m');
}

if ($step<3) {
	$edit_link=removeQueryStringParam($edit_link,'pays\[\]');
	$edit_link=removeQueryStringParam($edit_link,'systemes\[\]');
}


return $edit_link;


}


//******************************************************************************
// affiche le texte d'aide a la selection selon l'etape actuelle
function afficheAide($topic) {
//$topic: le "theme" de l'etape de selection ("taxonomie", "geographie", "periode", "type_exploitation", "type_donnees", "exp", "art", "campagnes", "engins", "secteurs", "agglomerations", "periodes_enquete", "grands_types_engins", "type_stats", "stats_agglo", "stats_gen", "secteurs2", "filieres")
$hint='<div class="hint clear small"><span class="hint_label">aide : </span><span class="hint_text">';
$hint_multiple='vous pouvez s&eacute;lectionner ou d&eacute;s&eacute;lectionner plusieurs valeurs en cliquant tout en tenant la touche &quot;CTRL&quot; (Windows, Linux) ou &quot;CMD&quot; (Mac) enfonc&eacute;e';
$hint_empty='si vous ne choisissez aucune valeur, ce crit&egrave;re ne sera pas pris en compte dans la s&eacute;lection<br />';
	switch ($topic) {
	case "taxonomie":
		$hint.="s&eacute;lectionnez les familles et/ou esp&egrave;ces qui vous int&eacute;ressent<br />";
		$hint.=$hint_empty;
		$hint.=$hint_multiple;
	break;
	case "geographie":
		$hint.="pour s&eacute;lectionner les syst&egrave;mes qui vous int&eacute;ressent, commencez par s&eacute;lectionner un ou plusieurs pays<br />";
		$hint.=$hint_empty;
		$hint.=$hint_multiple;
	break;
	case "periode":
		$hint.="s&eacute;lectionnez les ann&eacute;es et mois de d&eacute;but et de fin de la p&eacute;riode qui vous int&eacute;resse";
	break;
	case "type_exploitation":
		$hint.="choisissez le type d&#x27;exploitation que vous voulez appliquer aux donn&eacute;es s&eacute;lectionn&eacute;es";
	break;
	case "type_donnees":
		$hint.="choisissez le type de donn&eacute;es &agrave; extraire";
	break;
	case "secteurs":
		$hint.="s&eacute;lectionnez les secteurs qui vous int&eacute;ressent<br />";
		$hint.=$hint_empty;
		$hint.=$hint_multiple;
	break;
	case "campagnes":
		$hint.="s&eacute;lectionnez les campagnes de p&ecirc;che exp&eacute;rimentale qui vous int&eacute;ressent<br />";
		$hint.=$hint_empty;
		$hint.=$hint_multiple;
	break;
	case "engins":
		$hint.="s&eacute;lectionnez les engins de p&ecirc;che qui vous int&eacute;ressent<br />";
		$hint.=$hint_empty;
		$hint.=$hint_multiple;
	break;
	case "filieres":
		$hint.="votre s&eacute;lection est termin&eacute;e, vous allez maintenant choisir une fili&egrave;re d&#x27;extraction";
	break;
	case "agglomerations":
		$hint.="s&eacute;lectionnez les agglomérations qui vous int&eacute;ressent<br />";
		$hint.=$hint_empty;
		$hint.=$hint_multiple;
	break;
	case "periodes_enquete":
		$hint.="s&eacute;lectionnez les p&eacute;riodes d&#x27;enqu&ecirc;te qui vous int&eacute;ressent<br />";
		$hint.=$hint_empty;
		$hint.=$hint_multiple;
	break;
	case "grands_types_engins":
		$hint.="s&eacute;lectionnez les grands types d&#x27;engins qui vous int&eacute;ressent<br />";
		$hint.=$hint_empty;
		$hint.=$hint_multiple;
	break;
	case "type_stats":
		$hint.="choisissez le type de statistiques qui vous int&eacute;ressent";
	break;
		
	default:
		$hint.='';
	break;
	}
$hint.='</span></div>';
echo $hint;
}

//******************************************************************************
// affiche le bloc permettant d'indiquer si l'on veut choisir ou non des especes
function afficheChoixEspeces() {

// on determine a quelle etape on en est (si step est vide on suppose que on est au step 1)
if (empty($_GET["step"])) {$step=1;} else {$step=$_GET["step"];}
// si l'on en est a la premiere etape, on affiche le choix
if ($step==1) {
	echo('<div id="step_1">');
	echo("<h2>voulez-vous commencer par s&eacute;lectionner des esp&egrave;ces?</h2>");
	echo('<p><a href="/extraction/selection/selection.php?choix_especes=1&step=2" class="">oui</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/extraction/selection/selection.php?choix_especes=0&step=3" class="">non</a></p>');
	echo('</div>');

// on reinitialise les parametres de selection stockes dans la session
$_SESSION["selection_1"]=array();

}

else {
	echo('<div id="step_1">');
	echo('<h2><a href="/extraction/selection/selection.php">recommencer la s&eacute;lection du d&eacute;but</a></h2>');
	echo('</div>');
}

// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==2) {
echo('</div></div>');}

}


//******************************************************************************
// affiche le bloc permettant de selectionner des famille et/ou des especes
function afficheTaxonomie() {
// la connexion a la base
global $connectPPEAO;

//juste pour etre sur, on ne fait rien si choix_especes!=1

if ($_GET["choix_especes"]==1) {

// si l'on en est a l'etape en question, on affiche le selecteur
switch ($_GET["step"]) {
	case 1: 
	// on n'est pas encore arrive a cette etape, on n'affiche rien 
	break;
	case 2:
	// on est a cette etape, on affiche le selecteur 
	echo('<div id="step_2">');
	echo('<form id="step_2_form" name="step_2_form" target="/extraction/selection/selection.php?choix_especes=1" method="GET">');
		echo("<h2>s&eacute;lectionner des familles et/ou des esp&egrave;ces</h2>");
		
		// on recupere la liste des especes qui sont presentes dans les campagnes ou les enquetes
		$sql_especes='	SELECT DISTINCT id, libelle 
						FROM ref_espece 
						WHERE id IN (
									SELECT DISTINCT ref_espece_id 
									FROM exp_fraction 
									WHERE exp_coup_peche_id IN (
										SELECT DISTINCT exp_coup_peche.id 
										FROM exp_coup_peche, exp_campagne 
										WHERE exp_coup_peche.exp_campagne_id=exp_campagne.id))
							OR id IN (
									SELECT DISTINCT ref_espece_id 
									FROM art_fraction 
									WHERE TRUE
								)
						ORDER BY libelle';
		$result_especes=pg_query($connectPPEAO,$sql_especes) or die('erreur dans la requete : '.$sql_especes. pg_last_error());
		$array_especes=pg_fetch_all($result_especes);
		pg_free_result($result_especes);		
		
		// on recupere la liste des familles dont des especes sont presentes dans les campagnes ou les enquetes
		// on utilise pour cela la liste des especes recuperee ci-dessus
		foreach($array_especes as $espece) {$especes_id[]=$espece["id"];}
		
		$liste_especes='\''.arrayToList($especes_id,'\',\'','\'');
		$sql_familles='	SELECT DISTINCT id, libelle 
						FROM ref_famille 
						WHERE id IN (
									SELECT ref_famille_id 
									FROM ref_espece 
									WHERE id IN ('.$liste_especes.')
									)
						ORDER BY libelle';
		$result_familles=pg_query($connectPPEAO,$sql_familles) or die('erreur dans la requete : '.$sql_familles. pg_last_error());
		$array_familles=pg_fetch_all($result_familles);
		pg_free_result($result_familles);
		
		
		// on affiche le selecteur de familles
		echo('<div id="step_2_familles" class="level_div">');
		echo('<p>familles</p>');
		echo('<select id="familles" name="familles[]" size="10" multiple="multiple" class="level_select" >');
			foreach($array_familles as $famille) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($famille["id"],$_GET["familles"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$famille["id"].'" '.$selected.'>'.$famille["libelle"].'</option>');
			}
		echo('</select>');
		echo('</div>');
		echo('<div class="level_div"> et/ou </div>');
		// on affiche le selecteur d'especes
		echo('<div id="step_2_especes" class="level_div">');
		echo('<p>esp&egrave;ces</p>');
		echo('<select id="especes" name="especes[]" size="10" multiple="multiple" class="level_select" >');
			foreach($array_especes as $espece) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($espece["id"],$_GET["especes"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$espece["id"].'" '.$selected.'>'.$espece["libelle"].'</option>');
			}
		echo('</select>');
		echo('</div>');
	echo('</form>');
	// on affiche le lien permettant de passer a la selection geographique
	// on prepare l'url pour construire le lien : on enleve les familles et especes eventuellement selectionnees
	$url=$_SERVER["FULL_URL"];
	$url=removeQueryStringParam($url,'familles\[\]');
	$url=removeQueryStringParam($url,'especes\[\]');
	echo('<p class="clear"><a href="#" class="next_step" onclick="javascript:goToNextStep(2,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection spatiale...</a></p>');
	// on affiche le texte d'aide
	afficheAide("taxonomie");
	echo('</div>');// end div id="step_2"
	// on met a jour les parametres de selection stockes dans la session
	$_SESSION["selection_1"]=array();
	break;
	default:
	// on en est a une etape ulterieure, on affiche le resume textuel
	echo('<div id="step_2">');
		echo("<h2>familles et/ou esp&egrave;ces</h2>");
		if (!empty($_GET["familles"])) {
			// on recupere la liste des noms des familles selectionnees
			$familles_id='\''.arrayToList($_GET["familles"],'\',\'','\'');
			$sql='SELECT DISTINCT libelle FROM ref_famille WHERE id IN ('.$familles_id.')';
			$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
			$array=pg_fetch_all($result);
			pg_free_result($result);
			foreach ($array as $famille) {$familles_noms[]=$famille["libelle"];}
			$liste_familles=arrayToList($familles_noms,', ','.');
			
			echo("<p>familles : $liste_familles</p>");
		}
		if (!empty($_GET["especes"])) {
			// on recupere la liste des noms des especes selectionnees
			$especes_id='\''.arrayToList($_GET["especes"],'\',\'','\'');
			$sql='SELECT DISTINCT libelle FROM ref_espece WHERE id IN ('.$especes_id.')';
			$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
			$array=pg_fetch_all($result);
			pg_free_result($result);
			foreach ($array as $espece) {$especes_noms[]=$espece["libelle"];}
			$liste_especes=arrayToList($especes_noms,', ','.');
			echo("<p>esp&egrave;ces : $liste_especes</p>");
		}
	// le lien permettant d'editer la selection des especes
	$edit_link=prepareSelectionEditLink(2);
	echo('<p id="edit_especes" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection des esp&egrave;ces...</a></p>');	
	echo('</div>');
	
	break;
		
	} // fin de switch $_GET[step]

} // fin de if ($_GET["choix_especes"]==1)

// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==3) {
echo('</div></div>');}

}



//******************************************************************************
// affiche le bloc permettant de selectionner des systemes
function afficheGeographie() {

global $connectPPEAO; // la connexion a la base
global $campagnes_ids; // la liste des campagnes deja selectionnees
global $enquetes_ids; // la liste des enquetes deja selectionnees

// on determine si on a commence par choisir des especes
if ($_GET["choix_especes"]==1) {$choix=1;} else {$choix=0;}

// si l'on en est a l'etape en question, on affiche le selecteur
switch ($_GET["step"]) {
	case '':
	case 1:
	case 2: 
	// on n'est pas encore arrive a cette etape, on n'affiche rien 
	break;
	case 3:
	// on est a cette etape, on affiche le selecteur 
	echo('<div id="step_3">');
	echo('<form id="step_3_form" name="step_3_form" target="/extraction/selection/selection.php?choix_especes='.$choix.'" method="GET">');
	echo("<h2>s&eacute;lectionner des syst&egrave;mes</h2>");
	
	// on recupere la liste des pays correspondant aux campagnes et enquetes correspondant a la selection precedente
	$sql_pays='	SELECT DISTINCT ref_pays.id, ref_pays.nom 
				FROM ref_pays, ref_systeme 
				WHERE ref_systeme.ref_pays_id=ref_pays.id AND ref_systeme.id IN ';
	$sql_pays.=' (SELECT DISTINCT exp_campagne.ref_systeme_id FROM exp_campagne WHERE TRUE ';
		// si on a deja filtre les campagnes (par especes ou familles)
		if (!empty($campagnes_ids)) {
		$sql_pays.=' AND exp_campagne.id IN (\''.arrayToList($campagnes_ids,'\',\'','\'').')';
		}
	$sql_pays.=') ';
		$sql_pays.=' OR ref_systeme.id IN (
		SELECT DISTINCT art_agglomeration.ref_secteur_id 
		FROM art_agglomeration 
		WHERE art_agglomeration.id IN (
			SELECT DISTINCT art_periode_enquete.art_agglomeration_id 
			FROM art_periode_enquete 
			WHERE TRUE ';
		// si on a deja filtre les enquetes (par especes ou familles)
			if (!empty($enquetes_ids)) {$sql_pays.=' AND art_periode_enquete.id IN( 
												\''.arrayToList($enquetes_ids,'\',\'','\'').')';}
	$sql_pays.=('))');
	
	$result_pays=pg_query($connectPPEAO,$sql_pays) or die('erreur dans la requete : '.$sql_pays. pg_last_error());
	$array_pays=pg_fetch_all($result_pays);
	pg_free_result($result_pays);
		
	// on affiche le selecteur de pays
		echo('<div id="step_3_pays" class="level_div">');
		echo('<p>pays</p>');
		echo('<select id="pays" name="pays[]" size="10" multiple="multiple" class="level_select" style="min-width:10em"
			onchange="javascript:refreshSystemes([\''.arrayToList($campagnes_ids,'\',\'','').'\'], [\''.arrayToList($enquetes_ids,'\',\'','').'\'])"
			>');
			foreach($array_pays as $pays) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($pays["id"],$_GET["pays"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$pays["id"].'" '.$selected.'>'.$pays["nom"].'</option>');
			} // end foreach
		echo('</select>');
		echo('</div>');
		echo('<div class="level_div"> &gt; </div>');
		// on affiche le selecteur de systemes
		echo('<div id="step_3_systemes" class="level_div">');
		echo('<p>syst&egrave;mes</p>');
		// si aucun pays n'est selectionne on affiche un select vide mais on doit definir
		//if (empty($_GET["pays"])) {}
		echo('<select id="systemes" name="systemes[]" size="10" multiple="multiple" class="level_select" style="min-width:10em">');
			// on n'affiche le contenu de ce select que si des valeurs de pays ont ete passees dans l'url
			if (!empty($_GET["pays"])) {
			
			$array_systemes=listSelectSystemes($_GET["pays"],$campagnes_ids,$enquetes_ids);
			foreach($array_systemes as $systeme) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($systeme["id"],$_GET["systemes"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$systeme["id"].'" '.$selected.'>'.$systeme["libelle"].'</option>');
			} // end foreach
		}// fin de  if if (!empty($_GET["pays"]))
		echo('</select>');
		
		echo('</div>');
	echo('</form>');
	// on affiche le lien permettant de passer a la selection temporelle
	// on prepare l'url pour construire le lien : on enleve les pays et systemes eventuellement selectionnes
	$url=$_SERVER["FULL_URL"];
	$url=removeQueryStringParam($url,'pays\[\]');
	$url=removeQueryStringParam($url,'systemes\[\]');
	echo('<p class="clear"><a href="#" class="next_step" onclick="javascript:goToNextStep(3,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection temporelle...</a></p>');
		// on affiche le texte d'aide
	afficheAide("geographie");
	echo('</div>'); // end div id=step_3
	// on reinitialise les parametres de selection stockes dans la session
	$_SESSION["selection_1"]=array();
	break;
	default:
	// on en est a une etape ulterieure, on affiche le resume textuel
	echo('<div id="step_3">');
		echo("<h2>syst&egrave;mes</h2>");
		if (!empty($_GET["systemes"])) {
			// on recupere la liste des systemes selectionnes
			$systeme_id='\''.arrayToList($_GET["systemes"],'\',\'','\'');
			$sql='SELECT DISTINCT ref_systeme.id, ref_systeme.libelle, ref_systeme.ref_pays_id, ref_pays.nom FROM ref_systeme,ref_pays WHERE ref_systeme.id IN ('.$systeme_id.') AND (ref_pays_id=ref_pays.id) ORDER BY ref_pays_id';
			$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
			$array=pg_fetch_all($result);
			pg_free_result($result);
			
			$lePays='';
			$array_pays_systemes=array();
			$array_pays=$_GET["pays"];
			// on recupere les noms des pays
			$sql='SELECT id,nom FROM ref_pays WHERE id IN (\''.arrayToList($_GET["pays"],'\',\'','\'').')';
			$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
			$array2=pg_fetch_all($result);
			pg_free_result($result);
			foreach($array2 as $row) {
				$pays[$row["id"]]=$row["nom"];
			}
		
			// on groupe les systemes d'un meme pays
			foreach($array as $systeme) {
				if ($systeme["ref_pays_id"]!=$lePays) {
					$lePays=$systeme["ref_pays_id"];
					// on stocke la liste des pays pour lesquels des systemes ont ete selectionnes
					$array_pays_systemes[]=$lePays;
					}
				$array_systemes[$lePays][]=$systeme["libelle"];
			}
			
			$liste_systemes='';
			foreach($array_systemes as $key=>$value) {				
				if (empty($value)) {$liste_systemes.=$pays[$key].' : tous; ';}
				else {$liste_systemes.=$pays[$key].' : '.arrayToList($value,', ','; ');}
			}
						
			echo("<p>syst&egrave;mes : $liste_systemes</p>");
		}
	// le lien permettant d'editer la selection des systemes
	$edit_link=prepareSelectionEditLink(3);
	echo('<p id="edit_systemes" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection des syst&egrave;mes...</a></p>');	
	echo('</div>');
	break;

	} // end switch($_GET["step"])
// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==4) {
echo('</div></div>');}
}


// on affiche le selecteur de periode
function affichePeriode() {

global $connectPPEAO; // la connexion a la base
global $campagnes_ids; // la liste des campagnes deja selectionnees
global $enquetes_ids; // la liste des enquetes deja selectionnees	

// si l'on en est a l'etape en question, on affiche le selecteur
switch ($_GET["step"]) {
	case NULL:
	case 0:
	case 1:
	case 2: 
	case 3:
	// on n'est pas encore arrive a cette etape, on n'affiche rien 
	break;
	case 4:
	// on est arrive a cette etape, on affiche le formulaire	
	echo('<div id="step_4">');	
	echo('<form id="step_4_form" name="step_4_form" target="/extraction/selection/selection.php?choix_especes='.$choix.'" method="GET">');
	echo("<h2>s&eacute;lectionner une periode d&#x27;int&eacute;r&ecirc;t</h2>");
	// on determine les periodes couvertes par les campagnes filtrees
	if (!empty($campagnes_ids[0])) {
	$sql_c='SELECT MIN(c.date_debut) as campagne_debut, MAX(c.date_fin) as campagne_fin 
			FROM exp_campagne c 
			WHERE c.id IN (\''.arrayToList($campagnes_ids,'\',\'','').'\')';
	$result_c=pg_query($connectPPEAO,$sql_c) or die('erreur dans la requete : '.$sql_c. pg_last_error());
	$array_c=pg_fetch_all($result_c);
	pg_free_result($result_c);} else {$array_c[]=array("campagne_debut"=>'9999-99-99',"campagne_fin"=>'0000-00-00');}
	//debug 		echo('<pre>');print_r($array_c);echo('</pre>');
	
	// on determine les periodes couvertes par les campagnes filtrees
	if (!empty($enquetes_ids[0])) {
	$sql_e='SELECT MIN(e.date_debut) as enquete_debut, MAX(e.date_fin) as enquete_fin 
			FROM art_periode_enquete e 
			WHERE e.id IN (\''.arrayToList($enquetes_ids,'\',\'','').'\')';
	$result_e=pg_query($connectPPEAO,$sql_e) or die('erreur dans la requete : '.$sql_e. pg_last_error());
	$array_e=pg_fetch_all($result_e);
	pg_free_result($result_e);} else {$array_e[]=array("enquete_debut"=>'9999-99-99',"enquete_fin"=>'0000-00-00');}
	//debug 	echo('<pre>');print_r($array_e);echo('</pre>');
	// on choisit la date de debut la plus ancienne et la date de fin la plus recente
	$from=array();
	$to=array();
	/*if ($array_c[0]["campagne_debut"]<$array_e[0]["enquete_debut"]) {$from=date_parse($array_c[0]["campagne_debut"]);} else {$from=date_parse($array_c[0]["enquete_debut"]);}
	if ($array_c[0]["campagne_fin"]>$array_e[0]["enquete_fin"]) {$to=date_parse($array_c[0]["campagne_fin"]);} else {$to=date_parse($array_c[0]["enquete_fin"]);}*/
	if ($array_c[0]["campagne_debut"]<$array_e[0]["enquete_debut"]) {$from=getdate(strtotime($array_c[0]["campagne_debut"]));} else {$from=getdate(strtotime($array_c[0]["enquete_debut"]));}
	if ($array_c[0]["campagne_fin"]>$array_e[0]["enquete_fin"]) {$to=getdate(strtotime($array_c[0]["campagne_fin"]));} else {$to=getdate(strtotime($array_c[0]["enquete_fin"]));}

	

	$debut["annee"]=$from["year"];
	$debut["mois"]=$from["mon"];
	$debut["jour"]=$from["mday"];
	$fin["annee"]=$to["year"];
	$fin["mois"]=$to["mon"];
	$fin["jour"]=$to["mday"];

	echo('<p>(p&eacute;riode couverte : de '.$debut["annee"].'-'.number_pad($debut["mois"],2).'-'.$debut["jour"].' &agrave; '.$fin["annee"].'-'.number_pad($fin["mois"],2).'-'.$fin["jour"].')</p>');

	
	// la ligne pour la date de debut
	echo('<div id="debut">de ');
	// les annees
	echo('<div id="div_d_a">');
	echo('<select id="d_a", name="d_a", onchange="javascript:refreshPeriode(\'d_a\',\''.$debut["annee"].'\',\''.$debut["mois"].'\',\''.$fin["annee"].'\',\''.$fin["mois"].'\');">');
	// la premiere ligne est "vide"
	echo('<option value="-1">-ann&eacute;e-</option>');
	$i=$debut["annee"];$end=$fin["annee"];
	// on cree un <option>  par annee
	while ($i<=$end) {
		// si l'annee a ete passee dans l'url
		if ($i==$_GET["d_a"]) {$selected=' selected="selected" ';} else {$selected='';} 
		echo('<option value="'.$i.'" '.$selected.'>'.$i.'</option>');
		$i++;
	}
	echo('</select>');
	echo("</div>"); // fin de div div_d_a
	// les mois sont affiches uniquement si une annee a ete choisie
	echo('<div id="div_d_m">');
	if (!empty($_GET["d_a"])) {
	echo('<select id="d_m" name="d_m" onchange="javascript:refreshPeriode(\'d_m\',\''.$debut["annee"].'\',\''.$debut["mois"].'\',\''.$fin["annee"].'\',\''.$fin["mois"].'\');"">');
	
	// la premiere ligne est "vide"
	echo('<option value="-1">-mois-</option>');	
	$premier_mois=1;$dernier_mois=12;
	// cas particuliers des annees limites : il se peut que les douze mois de ces annees ne soient pas disponibles
	if ($_GET["d_a"]==$fin["annee"]) {$premier_mois=1;$dernier_mois=$fin["mois"];}
	if ($_GET["d_a"]==$debut["annee"]) {$premier_mois=$debut["mois"];$dernier_mois=12;}
	if ($_GET["d_a"]==$fin["annee"] && $_GET["d_a"]==$debut["annee"])
		{$premier_mois=$debut["mois"];$dernier_mois=$fin["mois"];}
	$i=$premier_mois;
	while ($i<=$dernier_mois) {
		// si le mois a ete passe dans l'url
		if ($i==$_GET["d_m"]) {$selected=' selected="selected" ';} else {$selected='';} 
		echo('<option value="'.$i.'" '.$selected.'>'.number_pad($i,2).'</option>');
		$i++;
	}
	echo('</select>');
} // fin de if (!empty($_GET["d_a"]))
	echo('</div>'); // fin de div div_d_m
	echo('</div>'); // fin de div debut
	
	// la ligne pour la date de fin, dont on n'on n'affiche le contenu que si une annee et un mois de debut ont ete choisis
	echo('<div id="fin">&nbsp;&nbsp;&agrave; ');
	echo('<div id="div_f_a">');
	if (!empty($_GET["d_a"]) && !empty($_GET["d_m"])) {
	// les annees
	echo('<select id="f_a", name="f_a" onchange="javascript:refreshPeriode(\'f_a\',\''.$debut["annee"].'\',\''.$debut["mois"].'\',\''.$fin["annee"].'\',\''.$fin["mois"].'\');"">');
	// la premiere ligne est "vide"
	echo('<option value="-1">-ann&eacute;e-</option>');
	$i=$_GET["d_a"];$end=$fin["annee"];
	while ($i<=$end) {
		// si l'annee a ete passee dans l'url
		if ($i==$_GET["f_a"]) {$selected=' selected="selected" ';} else {$selected='';} 
		echo('<option value="'.$i.'" '.$selected.'>'.$i.'</option>');
		$i++;
	}
	echo('</select>');
	} //fin de 	if (!empty($_GET["d_a"]) && !empty($_GET["d_m"]))
	echo ("</div>"); //	div id="div_f_a"
	
	// les mois sont affiches uniquement si une annee a ete choisie
	echo('<div id="div_f_m">');
	if (!empty($_GET["d_a"]) && !empty($_GET["d_m"]) && !empty($_GET["f_a"])) {
	echo('<select id="f_m" name="f_m" onchange="refreshPeriode(\'f_m\',\'\',\'\',\'\',\'\');">');
	// la premiere ligne est "vide"
	echo('<option value="-1">-mois-</option>');
	$premier_mois=1;$dernier_mois=12;
	// cas particuliers des annees limites : il se peut que les douze mois de ces annees ne soient pas disponibles
	if ($_GET["f_a"]==$fin["annee"]) {$premier_mois=1;$dernier_mois=$fin["mois"];}
	if ($_GET["f_a"]==$debut["annee"]) {$premier_mois=$debut["mois"];$dernier_mois=12;}
	if ($_GET["f_a"]==$fin["annee"] && $_GET["f_a"]==$debut["annee"])
		{$premier_mois=$_GET["d_m"];$dernier_mois=$fin["mois"];}
	if ($_GET["f_a"]==$_GET["d_a"])
		{$premier_mois=$_GET["d_m"];$dernier_mois=$fin["mois"];}
	$i=$premier_mois;
	while ($i<=$dernier_mois) {
		// si le mois a ete passe dans l'url
		if ($i==$_GET["f_m"]) {$selected=' selected="selected" ';} else {$selected='';} 
		echo('<option value="'.$i.'" '.$selected.'>'.number_pad($i,2).'</option>');
		$i++;
	}
	echo('</select>');
	} // fin de  if (!empty($_GET["d_a"]) && !empty($_GET["d_m"]) && !empty($_GET["f_a"]))
	echo("</div>"); // fin de div id="div_f_m"
	echo('</div>'); // fin de div id=fin
	echo('</form>');
	// si la selection de periode est terminee (i.e. une valeur de f_m est choisie)
	// on affiche le lien permettant de passer a la suite	
	$url=$_SERVER['FULL_URL'];
	$url=removeQueryStringParam($url,'d_a');
	$url=removeQueryStringParam($url,'d_m');
	$url=removeQueryStringParam($url,'f_a');
	$url=removeQueryStringParam($url,'f_m');

	if (!empty($_GET["f_m"])) {
	echo('<p id="step_4_link"  class="clear"><a href="#" class="next_step" onclick="javascript:goToNextStep(4,\''.$url.'\');">ajouter et choisir un type d&#x27;exploitation ...</a></p>');}
	// on affiche le texte d'aide
	afficheAide("periode");
	echo('</div>'); // fin de div id="step_4"
	echo('<br class="clear" />');
	break;
	default:
	// on a depasse cette etape, on affiche le resume textuel
	echo('<div id="step_4">');
	echo("<h2>p&eacute;riode d&#x27;int&eacute;r&ecirc;t</h2>");
	echo('<p>de '.number_pad($_GET["d_m"],2).'/'.$_GET["d_a"].' &agrave; '.number_pad($_GET["f_m"],2).'/'.$_GET["f_a"].'</p>');
	// le lien permettant d'editer la selection de la periode
	$edit_link=prepareSelectionEditLink(4);
	echo('<p id="edit_periode" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection de la p&eacute;riode...</a></p>');	
	echo('</div>');
	break;

	} // end switch $_GET["step"]
// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==5) {
echo('</div></div>');}
}



// on affiche le choix du type d'exploitation
function afficheTypeExploitation() {


global $connectPPEAO; // la connexion a la base
global $campagnes_ids; // la liste des campagnes deja selectionnees
global $enquetes_ids; // la liste des enquetes deja selectionnees

// si l'on en est a l'etape en question, on affiche le selecteur
switch ($_GET["step"]) {
	case NULL:
	case 0:
	case 1:
	case 2: 
	case 3:
	case 4:
	// on n'est pas encore arrive a cette etape, on n'affiche rien 
	break;
	case 5:
	// on en est a cette etape, on affiche le selecteur
	echo('<div id="step_5">');
	echo('<h2>s&eacute;lectionner un type d&#x27;exploitation</h2>');
	echo('<ul>');
	$donnees_link=replaceQueryParam($_SERVER["FULL_URL"],'step',6);
	$donnees_link.='&exploit=donnees';
	$stats_link=replaceQueryParam($_SERVER["FULL_URL"],'step',6);
	$stats_link.='&exploit=stats';
	$cartes_link=replaceQueryParam($_SERVER["FULL_URL"],'step',6);
	$cartes_link.='&exploit=cartes';
		echo('<li><a href="'.$donnees_link.'">extraction de donn&eacute;es</a></li>');
		echo('<li><a href="'.$stats_link.'">statistiques de p&ecirc;che</a></li>');
		echo('<li><a href="'.$cartes_link.'">fonds de cartes</a></li>');
		/*echo('<li>graphiques</li>');
		echo('<li>indicateurs &eacute;cologiques</li>');*/
	echo('</ul>');
	// on affiche le texte d'aide
	afficheAide("type_exploitation");
	echo('</div>');
	// et on stocke les paramètres de l'URL actuelle dans une variable de session pour les passer au script suivant
	$_SESSION["selection_1"]=$_GET;
	// on enleve le param "step" puisque on le passe via l'url
	unset($_SESSION["selection_1"]["step"]);
	//debug 	echo('<pre>');print_r($_SESSION["selection_1"]);echo('</pre>');
	
	break;
	default:
	// on en est a une etape ulterieure, on affiche le resume textuel
	echo('<div id="step_5">');
	echo('<h2>type d&#x27;exploitation</h2>');
	switch ($_GET["exploit"]) {
		case "donnees":
			echo("<p>extraction de donn&eacute;es</p>");
		break;
		case "stats":
			echo("<p>statistiques de p&ecirc;che</p>");
		break;
		case "cartes":
			echo("<p>fonds de cartes</p>");
		break;
		case "graphes":
			echo("<p>graphiques</p>");
		break;
		case "indics":
			echo("<p>indicateurs &eacute;cologiques</p>");
		break;
	}
	// le lien permettant d'editer la selection du type d'exploitation
	$edit_link=prepareSelectionEditLink(5);
	echo('<p id="edit_exploit" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection du type d&#x27;exploitation...</a></p>');	
	echo('</div>');
	} // end switch

// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==6) {
echo('</div></div>');}
}

// on affiche le choix du type de donnees
function afficheTypeDonnees() {
	
global $connectPPEAO; // la connexion a la base
global $campagnes_ids; // la liste des campagnes deja selectionnees
global $enquetes_ids; // la liste des enquetes deja selectionnees
global $compteur;
	switch($_GET["step"]) {
		// on n'est pas encore a cette etape, on n'affiche rien
		case ($_GET["step"]<6):
		break;
		case 6:
		// on en est a cette etape, on affiche le selecteur
		echo('<div id="step_6">');
		echo('<h2>s&eacute;lectionner le type de donn&eacute;es &agrave; extraire</h2>');
		if ($compteur["campagnes_total"]!=0 || $compteur["enquetes_total"]!=0) {
		echo('<ul>');
		//si il reste des campagnes
		if ($compteur["campagnes_total"]!=0) {
			$exp_link=replaceQueryParam($_SERVER["FULL_URL"],'step',7);
			$exp_link.='&donnees=exp';
		echo('<li><a href="'.$exp_link.'">donn&eacute;es de p&ecirc;che exp&eacute;rimentale</a></li>');}
		//si il reste des enquetes
		if ($compteur["enquetes_total"]!=0) {
			$art_link=replaceQueryParam($_SERVER["FULL_URL"],'step',7);
			$art_link.='&donnees=art';
		echo('<li><a href="'.$art_link.'">donn&eacute;es de p&ecirc;che artisanale</a></li>');}
		echo('</ul>');
		} else {echo('<p>aucune campagne ou p&eacute;riode d&#x27;enqu&ecirc;te disponible, veuillez modifier votre s&eacute;lection.</p>');} 
		// on affiche le texte d'aide
		afficheAide("type_donnees");
		break;
		// on a depasse cette etape, on affiche le resume textuel
		default:
		echo('<div id="step_6">');
		echo('<h2>type de donn&eacute;es &agrave; extraire</h2>');
		switch($_GET["donnees"]) {
			case "exp":
			echo('<p>donn&eacute;es de p&ecirc;che exp&eacute;rimentale</p>');
			break;
			case "art":
			echo('<p>donn&eacute;es de p&ecirc;che artisanale</p>');
			break;
		}
		// le lien permettant d'editer la selection du type de donnees a extraire
		$edit_link=prepareSelectionEditLink(6);
		echo('<p id="edit_donnees" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection du type de donn&eacute;es &agrave; extraire...</a></p>');	
		echo('</div>');
		break;
	}
// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==7) {
echo('</div></div>');}	
	
}

// on affiche le choix des secteurs, en fonction du type de donnees a extraire (exp ou art)
function afficheSecteurs($donnees) {
	
	global $connectPPEAO;
	global $campagnes_ids;
	global $enquetes_ids;
	
	switch($_GET["step"]) {
		case 7: 
		// on en est a cette etape, on affiche le selecteur
		echo('<div id="step_7">');
			echo('<form id="step_7_form" name="step_7_form" target="/extraction/selection/selection.php" method="GET">');
		echo('<h2>s&eacute;lectionner des secteurs</h2>');
		
		// on recupere la liste des secteurs pour les campagnes ou periodes d'enquetes selectionnees
		switch($donnees) {
			case "exp":
			$sql='SELECT DISTINCT ref_secteur.id, ref_secteur.nom as secteur, ref_systeme.libelle as systeme, ref_pays.nom as pays FROM ref_secteur, ref_systeme,ref_pays WHERE ref_secteur.ref_systeme_id IN 
				(SELECT DISTINCT ref_systeme_id FROM exp_campagne WHERE exp_campagne.id IN
					(\''.arrayToList($campagnes_ids,'\',\'','\'').')
				) 
				 AND ref_systeme.id=ref_secteur.ref_systeme_id AND ref_pays.id=ref_systeme.ref_pays_id 
				 ORDER BY ref_pays.nom, ref_systeme.libelle, ref_secteur.nom
				';
			//debug 			echo('<pre>');print_r($sql);echo('</pre>');
			$nextSelectionStep='campagnes';
			break; // end case exp
			case "art":
			$sql='SELECT DISTINCT ref_secteur.id, ref_secteur.nom as secteur, ref_systeme.libelle as systeme, ref_pays.nom as pays FROM ref_secteur, ref_systeme,ref_pays WHERE ref_secteur.id IN 
				(SELECT DISTINCT ref_secteur_id FROM art_agglomeration WHERE art_agglomeration.id IN (
					SELECT DISTINCT art_periode_enquete.art_agglomeration_id FROM art_periode_enquete WHERE art_periode_enquete.id IN (
					\''.arrayToList($enquetes_ids,'\',\'','\'').'
						)
					)
				) 
				 AND ref_systeme.id=ref_secteur.ref_systeme_id AND ref_pays.id=ref_systeme.ref_pays_id 
				 ORDER BY ref_pays.nom, ref_systeme.libelle, ref_secteur.nom
				';
			//debug 			echo('<pre>');print_r($sql);echo('</pre>');
			$nextSelectionStep='agglom&eacute;rations';
			break; // end case art
			
		}
			$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
			$secteurs=pg_fetch_all($result);
			pg_free_result($result);
			
			//debug 			echo('<pre>');print_r($array);echo('</pre>');
			
			// on affiche le select
			echo('<select id="secteurs" name="secteurs[]" size="10" multiple="multiple" class="level_select">');
			foreach($secteurs as $secteur) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($secteur["id"],$_GET["secteurs"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$secteur["id"].'" '.$selected.'>('.$secteur["pays"].'/'.$secteur["systeme"].') '.$secteur["secteur"].'</option>');
			} // end foreach
			echo('</select>');
			
			// on affiche le lien permettant de passer a la selection temporelle
			// on prepare l'url pour construire le lien : on enleve les secteurs eventuellement selectionnes
			$url=$_SERVER["FULL_URL"];
			$url=removeQueryStringParam($url,'secteurs\[\]');
			echo('<p class="clear"><a href="#" class="next_step" onclick="javascript:goToNextStep(7,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection des '.$nextSelectionStep.'...</a></p>');
		echo('</form>');
		// on affiche le texte d'aide
		afficheAide("secteurs");
		echo('</div>');
		break; // end case step=7
		// on a depasse cette etape, on affiche le resume textuel
		case ($_GET["step"]>7):
		echo('<div id="step_7">');
		echo('<h2>secteurs</h2>');
		if (!empty($_GET["secteurs"])) {
		$secteurs_id='\''.arrayToList($_GET["secteurs"],'\',\'','\'');
		$sql='SELECT DISTINCT ref_secteur.nom as secteur, ref_systeme.libelle as systeme, ref_pays.nom as pays 
				FROM ref_secteur , ref_systeme, ref_pays
				WHERE ref_secteur.id IN ('.$secteurs_id.') 
				 AND ref_systeme.id=ref_secteur.ref_systeme_id AND ref_pays.id=ref_systeme.ref_pays_id 
				 ORDER BY ref_pays.nom, ref_systeme.libelle, ref_secteur.nom';
		$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
		$array=pg_fetch_all($result);
		pg_free_result($result);
		foreach ($array as $secteur) {$secteurs_noms[]=$secteur["secteur"].' ('.$secteur["pays"].'/'.$secteur["systeme"].')';}
		$liste_secteurs=arrayToList($secteurs_noms,', ','.');}
		else {
			$liste_secteurs="tous";
		}
		echo("<p>$liste_secteurs</p>");
		// le lien permettant d'editer la selection des secteurs
		$edit_link=prepareSelectionEditLink(7);
		echo('<p id="edit_secteurs" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection des secteurs...</a></p>');
		echo('</div>');
		break;
	}
	
// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==8) {
echo('</div></div>');}	
	
}

// on affiche le choix des campagnes
function afficheCampagnes() {

global $compteur;
global $connectPPEAO;
	switch ($_GET["step"]) {
		// on n'est pas encore la, on n'affiche rien
		case ($_GET["step"]<8):
		break;
		// on en est a cette etape on affiche le selecteur de campagnes
		case 8:
		echo('<div id="step_8">');
			echo('<form id="step_8_form" name="step_8_form" target="/extraction/selection/selection.php" method="GET">');
				echo('<h2>s&eacute;lectionner des campagnes</h2>');
				//debug 				echo('<pre>');print_r($compteur["campagnes_ids"]);echo('</pre>');
				// on selectionne les campagnes disponibles
				$sql='SELECT DISTINCT c.id, c.numero_campagne, c.date_debut, c.date_fin, c.libelle as campagne, s.libelle as systeme, lower(s.libelle) as lower_systeme, p.nom as pays, lower(p.nom) as lower_pays 
				FROM exp_campagne c, ref_systeme s, ref_pays p 
				WHERE c.id IN (\''.arrayToList($compteur["campagnes_ids"],'\',\'','\'').') 
				AND c.ref_systeme_id=s.id AND s.ref_pays_id=p.id 
				ORDER BY lower_pays,lower_systeme,date_debut, date_fin';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				//debug 				echo('<pre>');print_r($array);echo('</pre>');
				// on affiche le select
			if (count($array)>15) {$size=15;} else {$size=10;}
			echo('<select id="campagnes" name="camp[]" size="'.$size.'" multiple="multiple" class="level_select">');
			foreach($array as $campagne) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($campagne["id"],$_GET["camp"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$campagne["id"].'" '.$selected.'>'.$campagne["pays"].':'.$campagne["systeme"].':'.$campagne["date_debut"].' au '.$campagne["date_fin"].'</option>');
			} // end foreach
			echo('</select>');
			// on affiche le lien permettant de passer a la selection des engins de peche
			// on prepare l'url pour construire le lien : on enleve les campagnes eventuellement selectionnees
			$url=$_SERVER["FULL_URL"];
			$url=removeQueryStringParam($url,'camp\[\]');
			echo('<p class="clear"><a href="#" class="next_step" onclick="javascript:goToNextStep(8,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection des engins de p&ecirc;che...</a></p>');
			echo('</form>');
		// on affiche le texte d'aide
		afficheAide("campagnes");
		echo('</div>'); // end div step_8
		break;
		// on a depasse cette etape, on affiche le resume textuel
		default:
		echo('<div id="step_8">');
			echo('<h2>campagnes</h2>');
			if (!empty($_GET["camp"])) {
				$sql='SELECT DISTINCT c.id, c.numero_campagne, c.date_debut, c.date_fin, c.libelle as campagne, s.libelle as systeme, lower(s.libelle) as lower_systeme, p.nom as pays, lower(p.nom) as lower_pays 
				FROM exp_campagne c, ref_systeme s, ref_pays p 
				WHERE c.id IN (\''.arrayToList($_GET["camp"],'\',\'','\'').') 
				AND c.ref_systeme_id=s.id AND s.ref_pays_id=p.id 
				ORDER BY lower_pays,lower_systeme,date_debut, date_fin';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				echo('<ul>');
				foreach($array as $campagne) {
				// si la valeur est dans l'url, on la selectionne
				echo('<li>'.$campagne["pays"].':'.$campagne["systeme"].':'.$campagne["date_debut"].' au '.$campagne["date_fin"].'</li>');
			} // end foreach
			echo('</ul>');
			} // end if !empty($_GET["camp"])
			else {
				echo('<p>toutes</p>');
			}
		// le lien permettant d'editer la selection des campagnes
		$edit_link=prepareSelectionEditLink(8);
		echo('<p id="edit_campagnes"><a href="'.$edit_link.'">modifier la s&eacute;lection des campagnes...</a></p>');
		echo('</div>'); // end div step_8
		break;
		
		
	} // end switch $step
	
// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==9) {
echo('</div></div>');}

}

// on affiche le choix des engins
function afficheEngins() {
global $compteur;
global $connectPPEAO;

	switch($_GET["step"]) {
		// on n'est pas encore la, on n'affiche rien
		case ($_GET["step"]<9):
		break;
		case 9:
		echo('<div id="step_9">');
			echo('<form id="step_9_form" name="step_9_form" target="/extraction/selection/selection.php" method="GET">');
				echo('<h2>s&eacute;lectionner des engins de p&ecirc;che</h2>');
				$sql='SELECT e.id, e.libelle FROM exp_engin e 
				WHERE e.id IN (
					SELECT exp_engin_id FROM exp_coup_peche 
					WHERE exp_coup_peche.id IN (\''.arrayToList($compteur["coups_ids"],'\',\'','\'').')
				)';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				//debug 				echo('<pre>');print_r($array);echo('</pre>');
				echo('<select id="engins" name="eng[]" size="10" multiple="multiple" class="level_select">');
			foreach($array as $engin) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($engin["id"],$_GET["eng"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$engin["id"].'" '.$selected.'>'.$engin["libelle"].'</option>');
			} // end foreach
			echo('</select>');
			// on affiche le lien permettant de passer au choix des filieres
			// on prepare l'url pour construire le lien : on enleve les campagnes eventuellement selectionnees
			$url=$_SERVER["FULL_URL"];
			$url=removeQueryStringParam($url,'eng\[\]');
			echo('<p class="clear"><a href="#" class="last_step" onclick="javascript:goToNextStep(9,\''.$url.'\');">finaliser la s&eacute;lection...</a></p>');
			echo('</form>');
		// on affiche le texte d'aide
		afficheAide("engins");
		echo('</div>'); // end div step_9
		break;
		// on a depasse cette etape, on affiche le resume textuel
		default:
		echo('<div id="step_9">');
			echo('<h2>engins de p&ecirc;che</h2>');
			$sql='SELECT e.id, e.libelle FROM exp_engin e 
				WHERE e.id IN (
					SELECT exp_engin_id FROM exp_coup_peche 
					WHERE exp_coup_peche.id IN (\''.arrayToList($compteur["coups_ids"],'\',\'','\'').')
				)';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				
				foreach($array as $row) {$engins[]=$row["libelle"];}
				$engins_liste=arrayToList($engins,', ','.');
				echo('<p>'.$engins_liste.'</p>');
		echo('</div>'); // end div step_9
		// on stocke l'URL de la selection dans une variable de session
		$_SESSION["selection_url"]=$_SERVER["FULL_URL"];
		// le lien permettant d'editer la selection des campagnes
		$edit_link=prepareSelectionEditLink(9);
		echo('<p id="edit_campagnes" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection des engins de p&ecirc;che...</a></p>');
		break;
	} // end switch step
	
// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==10) {
echo('</div></div>');}
if ($_GET["step"]>9) {echo('<div id="choix_filiere"><a id="link_filieres" href="/extraction/selection/selection_finalisation.php?'.$_SERVER["QUERY_STRING"].'" class="last_step">choisir une fili&egrave;re d&#x27;exploitation...</a>');
// on affiche le texte d'aide
afficheAide("filieres");
echo('</div>');}

}

function afficheAgglomerations() {
global $compteur;
global $connectPPEAO;
	switch($_GET["step"]) {
	// on n'est pas encore la, on n'affiche rien
		case ($_GET["step"]<8):
		break;
		// on en est a cette etape, on affiche le selecteur d'agglomerations
		case 8:
		echo('<div id="step_8">');
			
			if (!empty($compteur["enquetes_ids"])) {
			echo('<form id="step_8_form" name="step_8_form" target="/extraction/selection/selection.php" method="GET">');
				echo('<h2>s&eacute;lectionner des agglom&eacute;rations</h2>');
				$sql='SELECT DISTINCT a.nom as agglo, a.id, p.nom as pays, s.nom as secteur, sy.libelle as systeme
				FROM art_agglomeration a, ref_pays p, ref_secteur s, ref_systeme sy
				WHERE a.id IN (
					SELECT art_agglomeration_id FROM art_periode_enquete 
					WHERE art_periode_enquete.id IN (\''.arrayToList($compteur["enquetes_ids"],'\',\'','\'').')
				)
				AND a.ref_secteur_id=s.id AND s.ref_systeme_id=sy.id AND sy.ref_pays_id=p.id 
				';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				//debug 	echo('<pre>');print_r($array);echo('</pre>');
				echo('<select id="agglo" name="agglo[]" size="10" multiple="multiple" class="level_select">');
			foreach($array as $agglo) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($agglo["id"],$_GET["agglo"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$agglo["id"].'" '.$selected.'>('.$agglo["pays"].'/'.$agglo["systeme"].'/'.$agglo["secteur"].') '.$agglo["agglo"].'</option>');
			} // end foreach
			echo('</select>');
			// on affiche le lien permettant de passer au choix des filieres
			// on prepare l'url pour construire le lien : on enleve les campagnes eventuellement selectionnees
			$url=$_SERVER["FULL_URL"];
			$url=removeQueryStringParam($url,'agglo\[\]');
			echo('<p class="clear"><a href="#" class="next_step" onclick="javascript:goToNextStep(8,\''.$url.'\');">ajouter et passer au choix des p&eacute;riodes d&#x27;enqu&ecirc;te...</a></p>');
			echo('</form>');}
			// sinon on demande a l'utilisateur de modifier sa selection
			else {
				echo('<p>aucune p&eacute;riode d&#x27;enqu&ecirc;te ne correspond, veuillez modifier votre s&eacute;lection.</p>');
			}
			// on affiche le texte d'aide
		afficheAide("agglomerations");
		echo('</div>'); // end div step_8
		break;
		// on a depasse cette etape, on affiche le resume textuel
		default:
		echo('<div id="step_8">');
			echo('<h2>agglom&eacute;rations</h2>');
			if (!empty($_GET["agglo"])) {
			$sql='SELECT DISTINCT a.nom as agglo, a.id, p.nom as pays, s.nom as secteur, sy.libelle as systeme
				FROM art_agglomeration a, ref_pays p, ref_secteur s, ref_systeme sy
				WHERE a.id IN (
					SELECT art_agglomeration_id FROM art_periode_enquete 
					WHERE art_periode_enquete.id IN (\''.arrayToList($compteur["enquetes_ids"],'\',\'','\'').')
				)
				AND a.ref_secteur_id=s.id AND s.ref_systeme_id=sy.id AND sy.ref_pays_id=p.id 
				';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				//debug echo('<pre>');print_r($array);echo('</pre>');
				;
				foreach ($array as $agglo) 
					{$agglo_noms[]=$agglo["agglo"].' ('.$agglo["pays"].'/'.$agglo["systeme"].'/'.$agglo["secteur"].')';}
				$liste_agglos=arrayToList($agglo_noms,', ','.');
			}
			else {
			$liste_agglos="toutes";
			}
		echo('<p>'.$liste_agglos.'</p>');
			// le lien permettant d'editer la selection des agglos
		$edit_link=prepareSelectionEditLink(8);
		echo('<p id="edit_agglos" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection des agglom&eacute;rations...</a></p>');
		echo('</div>'); // end div step_8
		break;
		
	}
// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==9) {
echo('</div></div>');}
}

function affichePeriodeEnquetes() {
global $compteur;
global $connectPPEAO;
	switch ($_GET["step"]) {
		// on n'est pas encore la, on n'affiche rien
		case ($_GET["step"]<9):
		break;
		// on en est a cette etape on affiche le selecteur de campagnes
		case 9:
		echo('<div id="step_9">');
			echo('<form id="step_9_form" name="step_9_form" target="/extraction/selection/selection.php" method="GET">');
				echo('<h2>s&eacute;lectionner des p&eacute;riodes d&#x27;enqu&ecirc;te</h2>');
				//debug 				echo('<pre>');print_r($compteur["enquetes_ids"]);echo('</pre>');
				// on selectionne les enquetes disponibles
				$sql='SELECT DISTINCT e.id, e.description, e.annee, e.mois, a.nom as agglo, lower(a.nom) as lower_agglo, s.nom as secteur, lower(s.nom) as lower_secteur, sy.libelle as systeme, lower(sy.libelle) as lower_systeme, p.nom as pays, lower(p.nom) as lower_pays FROM art_periode_enquete e, ref_pays p, ref_systeme sy, ref_secteur s, art_agglomeration a 
				WHERE e.id IN (\''.arrayToList($compteur["enquetes_ids"],'\',\'','\'').') 
				AND e.art_agglomeration_id=a.id AND a.ref_secteur_id=s.id 
				AND s.ref_systeme_id=sy.id AND sy.ref_pays_id=p.id  
				ORDER BY lower_pays,lower_systeme, lower_secteur, annee, mois';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				//debug 				echo('<pre>');print_r($array);echo('</pre>');
				// on affiche le select
			if (count($array)>15) {$size=15;} else {$size=10;}
			echo('<select id="enquetes" name="enq[]" size="'.$size.'" multiple="multiple" class="level_select">');
			foreach($array as $enquete) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($enquete["id"],$_GET["enq"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$enquete["id"].'" '.$selected.'>'.$enquete["pays"].':'.$enquete["systeme"].':'.$enquete["secteur"].':'.$enquete["agglo"].':'.$enquete["annee"].'-'.number_pad($enquete["mois"],2).'</option>');
			} // end foreach
			echo('</select>');
			// on affiche le lien permettant de passer a la selection des grands types d'engins de peche
			// on prepare l'url pour construire le lien : on enleve les enquetes eventuellement selectionnees
			$url=$_SERVER["FULL_URL"];
			$url=removeQueryStringParam($url,'enq\[\]');
			echo('<p class="clear"><a href="#" class="next_step" onclick="javascript:goToNextStep(9,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection des grands types d&#x27;engins de p&ecirc;che...</a></p>');
			echo('</form>');
			// on affiche le texte d'aide
		afficheAide("periodes_enquete");
		echo('</div>'); // end div step_8
		break;
		// on a depasse cette etape, on affiche le resume textuel
		default:
		echo('<div id="step_9">');
			echo('<h2>p&eacute;riodes d&#x27;enqu&ecirc;te</h2>');
			if (!empty($_GET["enq"])) {
				// on selectionne les enquetes disponibles
				$sql='SELECT DISTINCT e.id, e.description, e.annee, e.mois, a.nom as agglo, lower(a.nom) as lower_agglo, s.nom as secteur, lower(s.nom) as lower_secteur, sy.libelle as systeme, lower(sy.libelle) as lower_systeme, p.nom as pays, lower(p.nom) as lower_pays FROM art_periode_enquete e, ref_pays p, ref_systeme sy, ref_secteur s, art_agglomeration a 
				WHERE e.id IN (\''.arrayToList($_GET["enq"],'\',\'','\'').') 
				AND e.art_agglomeration_id=a.id AND a.ref_secteur_id=s.id 
				AND s.ref_systeme_id=sy.id AND sy.ref_pays_id=p.id  
				ORDER BY lower_pays,lower_systeme, lower_secteur, annee, mois';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				foreach ($array as $enquete) {$enquetes_noms[]=$enquete["pays"].':'.$enquete["systeme"].':'.$enquete["secteur"].':'.$enquete["agglo"].':'.$enquete["annee"].'-'.number_pad($enquete["mois"],2);}
				$liste_enquetes=arrayToList($enquetes_noms,', ','.');
			}
			else {
			$liste_enquetes="toutes";
			}
		echo('<p>'.$liste_enquetes.'</p>');
			// le lien permettant d'editer la selection des enquetes
		$edit_link=prepareSelectionEditLink(9);
		echo('<p id="edit_enquetes"><a href="'.$edit_link.'">modifier la s&eacute;lection des p&eacute;riodes d&#x27;enqu&ecirc;te...</a></p>');
		echo('</div>'); // end div step_9
		break;
	} // end switch $_GET

// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==10) {
echo('</div></div>');}
}

function afficheGrandsTypesEngins($exploit) {
// $ exploit : le type d'exploitation choisi (donnees, stats, cartes)
global $compteur;
global $connectPPEAO;

// le step differe selon que l'on a affaire aux stats par agglo (10) ou generales (8)
if ($_GET["stats"]=='gen') {$theStep=8;} else {$theStep=10;}

	switch ($_GET["step"]) {
		// on n'est pas encore la, on n'affiche rien
		case ($_GET["step"]<$theStep):
		break;
		// on en est a cette etape on affiche le selecteur de grands types d'engins
		case $theStep:
		echo('<div id="step_'.$theStep.'">');
			echo('<form id="step_'.$theStep.'_form" name="step_'.$theStep.'_form" target="/extraction/selection/selection.php" method="GET">');
				echo('<h2>'.$theStep.'. s&eacute;lectionner des grands types d&#x27;engins</h2>');
				// on recupere la liste des grands types d'engins correspondants aux periodes d'enquete
				$sql='SELECT DISTINCT g.id, g.libelle FROM art_grand_type_engin g, art_activite a, art_debarquement d WHERE 
				a.id IN (\''.arrayToList($compteur["activites_ids"],'\',\'','\'').') 
				AND d.id IN (\''.arrayToList($compteur["debarquements_ids"],'\',\'','\'').') 
				AND a.art_grand_type_engin_id=g.id AND d.art_grand_type_engin_id=g.id
				ORDER BY g.libelle
				';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				//debug 				echo('<pre>');print_r($array);echo('</pre>');
				echo('<select id="gteng" name="gteng[]" size="10" multiple="multiple" class="level_select">');
			foreach($array as $gteng) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($gteng["id"],$_GET["gteng"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$gteng["id"].'" '.$selected.'>'.$gteng["libelle"].'</option>');
			} // end foreach
			echo('</select>');
			// on affiche le lien permettant de passer au choix des filieres
			// on prepare l'url pour construire le lien : on enleve les campagnes eventuellement selectionnees
			$url=$_SERVER["FULL_URL"];
			$url=removeQueryStringParam($url,'gteng\[\]');
			echo('<p class="clear"><a href="#" class="last_step" onclick="javascript:goToNextStep('.$theStep.',\''.$url.'\');">finaliser la s&eacute;lection...</a></p>');
			echo('</form>');
			// on affiche le texte d'aide
		afficheAide("grands_types_engins");
		echo('</div>'); // end div step_'.$theStep.'
		break;
		// on a depasse cette etape, on affiche le resume textuel
		default:
		echo('<div id="step_'.$theStep.'">');
			echo('<h2>'.$theStep.'. grands types d&#x27;engins de p&ecirc;che</h2>');
			if (!empty($_GET["gteng"])) {
			$sql='SELECT DISTINCT g.id, g.libelle FROM art_grand_type_engin g, art_activite a, art_debarquement d WHERE 
				a.id IN (\''.arrayToList($compteur["activites_ids"],'\',\'','\'').') 
				AND d.id IN (\''.arrayToList($compteur["debarquements_ids"],'\',\'','\'').') 
				AND a.art_grand_type_engin_id=g.id AND d.art_grand_type_engin_id=g.id
				ORDER BY g.libelle
				';
				$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
				$array=pg_fetch_all($result);
				pg_free_result($result);
				
				foreach($array as $row) {$gtengins[]=$row["libelle"];}
				$gtengins_liste=arrayToList($gtengins,', ','.');}
			else {
				$gtengins_liste="tous";
			}
				echo('<p>'.$gtengins_liste.'</p>');
		// le lien permettant d'editer la selection des grands types d'engins
		$edit_link=prepareSelectionEditLink($theStep);
		echo('<p id="edit_gteng" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection des grands types d&#x27;engins...</a></p>');	
		echo('</div>'); // end div step_'.$theStep.'
		

		echo('</p></div>');
		// on stocke l'URL de la selection dans une variable de session
		$_SESSION["selection_url"]=$_SERVER["FULL_URL"];	
		break; 
	} // end switch $_GET

// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
$theNextStep=$theStep+1;
if ($_GET["step"]==$theNextStep) {
echo('</div></div>');}
if ($_GET["step"]>$theStep) {
echo('<div id="choix_tables_stats"><p>');
		switch ($exploit) {
			case "donnees" : 
			echo('<a id="link_filieres" class="last_step"  href="/extraction/selection/selection_finalisation.php?'.$_SERVER["QUERY_STRING"].'">choisir une fili&egrave;re d&#x27;extraction...</a>');
			break;
			case "stats":
			echo('<a id="link_filieres"  class="last_step"  href="/extraction/selection/selection_finalisation.php?'.$_SERVER["QUERY_STRING"].'">choisir les tables de statistiques...</a>');
			break;
		} // end switch $exploit
		// on affiche le texte d'aide
		afficheAide("filieres");
}
}
	
// on affiche le choix du type de statistiques
function afficheTypeStats() {

global $connectPPEAO; // la connexion a la base
global $campagnes_ids; // la liste des campagnes deja selectionnees
global $enquetes_ids; // la liste des enquetes deja selectionnees
global $compteur;

	switch($_GET["step"]) {
		// on n'est pas encore a cette etape, on n'affiche rien
		case ($_GET["step"]<6):
		break;
		case 6:
		// on en est a cette etape, on affiche le selecteur
		echo('<div id="step_6">');
		echo('<h2>s&eacute;lectionner le type de statistiques &agrave; extraire</h2>');
		// si il reste des enquetes
		if ($compteur["enquetes_total"]!=0) {
			$stats_link=replaceQueryParam($_SERVER["FULL_URL"],'step',7);
			$stats_gen_link=$stats_link.'&stats=gen';
			$stats_agglo_link=$stats_link.'&stats=agglo';
			echo('<ul>');
				echo('<li><a href="'.$stats_agglo_link.'">statistiques par agglom&eacute;rations</a></li>');
				echo('<li><a href="'.$stats_gen_link.'">statistiques g&eacute;n&eacute;rales</a></li>');
			echo('</ul>');
		} else {echo('<p>aucune p&eacute;riode d&#x27;enqu&ecirc;te disponible, veuillez modifier votre s&eacute;lection.</p>');} 
		// on affiche le texte d'aide
		afficheAide("type_stats");
		break;
		// on a depasse cette etape, on affiche le resume textuel
		default:
		echo('<div id="step_6">');
		echo('<h2>type de statistiques &agrave; extraire</h2>');
		switch($_GET["stats"]) {
			case "gen":
			echo('<p>statistiques g&eacute;n&eacute;rales</p>');
			break;
			case "agglo":
			echo('<p>statistiques par agglom&eacute;rations</p>');
			break;
		}
		// le lien permettant d'editer la selection du type de donnees a extraire
		$edit_link=prepareSelectionEditLink(6);
		echo('<p id="edit_stats" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection du type de statistiques &agrave; extraire...</a></p>');	
		echo('</div>');
		break;
	}

// si on est au step suivant, on ferme le div id=selection_precedente, ouvert dans selection.php
if ($_GET["step"]==7) {
echo('</div></div>');}
}


function afficheSecteurs2() {
	
	global $connectPPEAO;
	global $enquetes_ids;
	
	switch($_GET["step"]) {
		case 7: 
		// on en est a cette etape, on affiche le selecteur
		echo('<div id="step_7">');
			echo('<form id="step_7_form" name="step_7_form" target="/extraction/selection/selection.php" method="GET">');
		echo('<h2>s&eacute;lectionner des syst&egrave;mes ou des secteurs</h2>');
		
		// on recupere la liste des systemes selectionnes
			$sql='SELECT DISTINCT ref_systeme.id, ref_systeme.libelle as systeme, ref_pays.nom as pays FROM ref_systeme, ref_pays WHERE TRUE
				';
			if (!empty($_GET["systemes"])) {
				$sql.=' AND ref_systeme.id IN (\''.arrayToList($_GET["systemes"],'\',\'','\'').')';
				}
			// on filtre la liste des systemes selectionnes en fonction des enquetes selectionnees
			if (!empty($enquetes_ids)) {
				$sql.=' AND ref_systeme.id IN 
				(SELECT DISTINCT ref_systeme_id FROM ref_secteur rs 
				WHERE rs.id IN (
					SELECT DISTINCT ref_secteur_id FROM art_agglomeration aa 
						WHERE aa.id IN (
							SELECT DISTINCT art_agglomeration_id FROM art_periode_enquete ape 
							WHERE ape.id IN (\''.arrayToList($enquetes_ids,'\',\'','\'').')
							)
				)
				)
';
			}
			$sql.=' AND ref_systeme.ref_pays_id=ref_pays.id 
				';
			//debug 				echo('<pre>');print_r($sql);echo('</pre>');				exit;
			$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
			$systemes2=pg_fetch_all($result);
			pg_free_result($result);
			
			//debug 			echo('<pre>');print_r($systemes2);echo('</pre>');
			// on affiche le selecteur de systemes
			echo('<div id="step_7_systemes" class="level_div">');
			echo('<p>syst&egrave;mes</p>');
			echo('<select id="systemes2" name="systemes2[]" size="10" multiple="multiple" class="level_select" style="min-width:10em" onchange="javascript:refreshSecteurs([\''.arrayToList($enquetes_ids,'\',\'','').'\']);">');
			foreach($systemes2 as $systeme2) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($systeme2["id"],$_GET["systemes2"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$systeme2["id"].'" '.$selected.'>('.$systeme2["pays"].') '.$systeme2["systeme"].'</option>');
			} // end foreach
			echo('</select>');
			echo('</div>');
			echo('<div class="level_div"> &gt; </div>');
			
			// on affiche le selecteur de secteurs
			echo('<div id="step_7_secteurs" class="level_div">');
			echo('<p>secteurs</p>');
			// si aucun secteur n'est selectionne on affiche un select vide
			echo('<select id="secteurs" name="secteurs[]" size="10" multiple="multiple" class="level_select" style="min-width:10em">');
			// on n'affiche le contenu de ce select que si des valeurs de systemes2 ont ete passees dans l'url
			if (!empty($_GET["systemes2"])) {
				$array_secteurs=listSelectSecteurs($_GET["systemes2"],$enquetes_ids);
				//debug 								echo('<option>xx');print_r($array_secteurs);echo('</option>');
				
				foreach($array_secteurs as $secteur) {
				// si la valeur est dans l'url, on la selectionne
				if (in_array($secteur["id"],$_GET["secteurs"])) {$selected='selected="selected" ';} else {$selected='';}
				echo('<option value="'.$secteur["id"].'" '.$selected.'>('.$secteur["systeme"].') '.$secteur["secteur"].'</option>');
			} // end foreach
			}
			
			echo('</select>');
			
			echo('</div>');
			// on affiche le lien permettant de passer a la selection temporelle
			// on prepare l'url pour construire le lien : on enleve les systèmes et secteurs eventuellement selectionnes
			$url=$_SERVER["FULL_URL"];
			$url=removeQueryStringParam($url,'systemes2\[\]');
			$url=removeQueryStringParam($url,'secteurs\[\]');
			echo('<p class="clear"><a href="#" class="next_step" onclick="javascript:goToNextStep(7,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection des grands types d&#x27;engins...</a></p>');
		echo('</form>');
		echo('</div>');
		break; // end case step=7
		
		// on a depasse cette etape, on affiche le resume textuel
		case ($_GET["step"]>7):
		echo('<div id="step_7">');
		echo('<h2>syst&egrave;mes ou secteurs</h2>');
		
		if (!empty($_GET["systemes2"])) {
		$systemes2_id='\''.arrayToList($_GET["systemes2"],'\',\'','\'');
		$sql='SELECT DISTINCT ref_systeme.libelle as systeme, ref_pays.nom as pays 
				FROM ref_systeme, ref_pays
				WHERE ref_systeme.id IN ('.$systemes2_id.') 
				  AND ref_pays.id=ref_systeme.ref_pays_id 
				 ORDER BY ref_pays.nom, ref_systeme.libelle';
		$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
		$array=pg_fetch_all($result);
		pg_free_result($result);
		foreach ($array as $systeme2) {$systemes2_noms[]=$systeme2["systeme"].' ('.$systeme2["pays"].')';}
		$liste_systemes2=arrayToList($systemes2_noms,', ','.');}
		else {
			$liste_systemes2="tous";
		}
		echo("<p>syst&egrave;mes : $liste_systemes2</p>");
		
		if (!empty($_GET["secteurs"])) {
		$secteurs_id='\''.arrayToList($_GET["secteurs"],'\',\'','\'');
		$sql='SELECT DISTINCT ref_secteur.nom as secteur, ref_systeme.libelle as systeme, ref_pays.nom as pays 
				FROM ref_secteur , ref_systeme, ref_pays
				WHERE ref_secteur.id IN ('.$secteurs_id.') 
				 AND ref_systeme.id=ref_secteur.ref_systeme_id AND ref_pays.id=ref_systeme.ref_pays_id 
				 ORDER BY ref_pays.nom, ref_systeme.libelle, ref_secteur.nom';
		$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
		$array=pg_fetch_all($result);
		pg_free_result($result);
		foreach ($array as $secteur) {$secteurs_noms[]=$secteur["secteur"].' ('.$secteur["pays"].'/'.$secteur["systeme"].')';}
		$liste_secteurs=arrayToList($secteurs_noms,', ','.');}
		else {
			$liste_secteurs="tous";
		}
		echo("<p>secteurs : $liste_secteurs</p>");
		// le lien permettant d'editer la selection des systemes et secteurs
		$edit_link=prepareSelectionEditLink(7);
		echo('<p id="edit_secteurs" class="edit_selection"><a href="'.$edit_link.'">modifier la s&eacute;lection des syst&egrave;mes et des secteurs...</a></p>');
		echo('</div>');
		break;
	}
}

?>