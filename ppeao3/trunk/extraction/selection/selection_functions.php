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
	
	
} // fin de if ($domaine=='art')

// debug echo($sql);
	$result=pg_query($connectPPEAO,$sql) or die('erreur dans la requete : '.$sql. pg_last_error());
	$totalArray=pg_fetch_all($result);
	pg_free_result($result);
	
	//debug 	echo('<pre>xxxx');print_r($totalArray);echo('yyyy</pre>');
	

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
	$sql='SELECT exp_coup_peche.id FROM exp_coup_peche WHERE exp_campagne_id IN (\''.arrayToList($unites["ids"],'\',\'','\'').')';
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



// on compte les campagnes
$campagnes=countMatchingUnits2('exp');
$total_campagnes=$campagnes["total"];
// on compte les periodes d'enquete
$enquetes=countMatchingUnits2('art');
$total_enquetes=$enquetes["total"];

//debug echo('<pre>');print_r($enquetes);echo('</pre>');


// on prepare le compteur

if ($total_campagnes>0) {$texte_coups=' &ndash; '.$campagnes["coups"]["coups_total"].' coup(s) de p&ecirc;che)'; } else {$texte_coups='';}
if ($total_enquetes>0) {$texte_deb_act=' &ndash;'.$enquetes["debarquements"]["debarquements_total"].' d&eacute;barquement(s) et '.$enquetes["activites"]["activites_total"].' activit&eacute;(s).'; } else {$texte_deb_act='';}

switch ($_GET['donnees']) {
	case "exp":
	// Peches experimentales
	$compteur=array("campagnes_ids"=>$campagnes["ids"],
				"campagnes_total"=>$total_campagnes,
				"coups_ids"=>$campagnes["coups"]["coups_ids"],
				"coups_total"=>$campagnes["coups"]["coups_total"],
				"enquetes_ids"=>$enquetes["ids"],
				"enquetes_total"=>$total_enquetes,
				"debarquements_total"=>$campagnes["debarquements"]["debarquements_total"],
				"debarquements_ids"=>$campagnes["debarquements"]["debarquements_ids"],
				"texte"=>'<div id="ex_compteur"><p>Votre s&eacute;lection correspond &agrave; :</p><p>'.$total_campagnes.' campagne(s)'.$texte_coups.'</p></div>');
	break;
	case "art":
	$compteur=array("campagnes_ids"=>$campagnes["ids"],
				"campagnes_total"=>$total_campagnes,
				"coups_ids"=>$campagnes["coups"]["coups_ids"],
				"coups_total"=>$campagnes["coups"]["coups_total"],
				"enquetes_ids"=>$enquetes["ids"],
				"enquetes_total"=>$total_enquetes,
				"debarquements_total"=>$campagnes["debarquements"]["debarquements_total"],
				"debarquements_ids"=>$campagnes["debarquements"]["debarquements_ids"],
				"texte"=>'<div id="ex_compteur"><p>Votre s&eacute;lection correspond &agrave; :</p><p>'.$total_enquetes.' p&eacute;riode(s) d&#x27;enqu&ecirc;te'.$texte_deb_act.'</li></p></div>');
	break;
	default:
	$compteur=array("campagnes_ids"=>$campagnes["ids"],
				"campagnes_total"=>$total_campagnes,
				"coups_ids"=>$campagnes["coups"]["coups_ids"],
				"coups_total"=>$campagnes["coups"]["coups_total"],
				"enquetes_ids"=>$enquetes["ids"],
				"enquetes_total"=>$total_enquetes,
				"debarquements_total"=>$campagnes["debarquements"]["debarquements_total"],
				"debarquements_ids"=>$campagnes["debarquements"]["debarquements_ids"],
				"texte"=>'<div id="ex_compteur"><p>Votre s&eacute;lection correspond &agrave; :</p><ul><li>'.$total_campagnes.' campagne(s)'.$texte_coups.'</li><li>'.$total_enquetes.' p&eacute;riode(s) d&#x27;enqu&ecirc;te'.$texte_deb_act.'</li></ul></div>');
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

	

// on recupere la liste des pays correspondant aux campagnes et enquetes correspondant a la selection precedente
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
		// si on a deja filtre les enquetes (par especes ou familles
			if (!empty($enquetes_ids[0])) {$sql_systemes.=' AND art_periode_enquete.id IN( 
												\''.arrayToList($enquetes_ids,'\',\'','\'').')';}
	$sql_systemes.=('))');
	
	//debug	echo($sql_systemes);
	
	$result_systemes=pg_query($connectPPEAO,$sql_systemes) or die('erreur dans la requete : '.$sql_systemes. pg_last_error());
	$array_systemes=pg_fetch_all($result_systemes);
	pg_free_result($result_systemes);
	
		return $array_systemes;
	} // end function displaySelectSystemes()

//******************************************************************************
// affiche le bloc permettant d'indiquer si l'on veut choisir ou non des especes
function afficheChoixEspeces() {
/* on numerote les etapes :
1 = selectionner ou non des especes
2 = selection des especes
3 = selection pays/systemes
4= selection periode
5= selection type exploitation
*/

// on determine a quelle etape on en est (si step est vide on suppose que on est au step 1)
if (empty($_GET["step"])) {$step=1;} else {$step=$_GET["step"];}
// si l'on en est a la premiere etape, on affiche le choix
if ($step==1) {
	echo('<div id="step_1">');
	echo("<h2>1. voulez-vous commencer par s&eacute;lectionner des esp&egrave;ces?</h2>");
	echo('<p><a href="/extraction/selection/selection.php?choix_especes=1&step=2" class="">oui</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/extraction/selection/selection.php?choix_especes=0&step=3" class="">non</a></p>');
	echo('</div>');

// on reinitialise les parametres de selection stockes dans la session
$_SESSION["selection_1"]=array();

}

else {
	echo('<div id="step_1">');
	echo('<p><a href="/extraction/selection/selection.php">1. recommencer la s&eacute;lection du d&eacute;but</a></p>');
	echo('</div>');
}

}


//******************************************************************************
// affiche le bloc permettant de selectionner des famille et/ou des especes
function afficheTaxonomie() {
/* on numerote les etapes :
1 = selectionner ou non des especes
2 = selection des especes
3 = selection pays/systemes
4= selection periode
5= selection type exploitation
*/

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
		echo("<h2>2. s&eacute;lectionner des familles et/ou des esp&egrave;ces</h2>");
		
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
		echo('<div class="level_div"> ou </div>');
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
	echo('<p class="clear"><a href="#" onclick="javascript:goToNextStep(2,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection spatiale...</a></p>');
	echo('</div>');// end div id="step_2"
	// on reinitialise les parametres de selection stockes dans la session
	$_SESSION["selection_1"]=array();
	break;
	default:
	// on en est a une etape ulterieure, on affiche le resume textuel
	echo('<div id="step_2">');
		echo("<h2>2. familles et/ou esp&egrave;ces</h2>");
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
	// le lien permettant d'éditer la selection des especes
	$edit_link=replaceQueryParam ($_SERVER['FULL_URL'],'step',2);
	$edit_link=removeQueryStringParam($edit_link, 'pays\[\]');
	$edit_link=removeQueryStringParam($edit_link, 'systemes\[\]');
	$edit_link=removeQueryStringParam($edit_link, 'd_a');
	$edit_link=removeQueryStringParam($edit_link, 'd_m');
	$edit_link=removeQueryStringParam($edit_link, 'f_a');
	$edit_link=removeQueryStringParam($edit_link, 'f_m');
	$edit_link=removeQueryStringParam($edit_link, 'exploit');
	$edit_link=removeQueryStringParam($edit_link, 'donnees');
	$edit_link=removeQueryStringParam($edit_link, 'secteurs\[\]');

	echo('<p id="edit_especes"><a href="'.$edit_link.'">recommencer la sélection des esp&egrave;ces...</a></p>');	
	echo('</div>');
	break;
		
	} // fin de switch $_GET[step]


} // fin de if ($_GET["choix_especes"]==1)
}



//******************************************************************************
// affiche le bloc permettant de selectionner des systemes
function afficheGeographie() {
/* on numerote les etapes :
1 = selectionner ou non des especes
2 = selection des especes
3 = selection pays/systemes
4= selection periode
5= selection type exploitation
*/

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
	echo("<h2>3. s&eacute;lectionner des syst&egrave;mes</h2>");
	
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
	echo('<p class="clear"><a href="#" onclick="javascript:goToNextStep(3,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection temporelle...</a></p>');
	echo('</div>'); // end div id=step_3
	// on reinitialise les parametres de selection stockes dans la session
	$_SESSION["selection_1"]=array();
	break;
	default:
	// on en est a une etape ulterieure, on affiche le resume textuel
	echo('<div id="step_3">');
		echo("<h2>3. syst&egrave;mes</h2>");
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
	// le lien permettant d'éditer la selection des systemes
	$edit_link=replaceQueryParam ($_SERVER['FULL_URL'],'step',3);
	$edit_link=removeQueryStringParam($edit_link, 'd_a');
	$edit_link=removeQueryStringParam($edit_link, 'd_m');
	$edit_link=removeQueryStringParam($edit_link, 'f_a');
	$edit_link=removeQueryStringParam($edit_link, 'f_m');
	$edit_link=removeQueryStringParam($edit_link, 'exploit');
	$edit_link=removeQueryStringParam($edit_link, 'donnees');
	$edit_link=removeQueryStringParam($edit_link, 'secteurs\[\]');
	echo('<p id="edit_systemes"><a href="'.$edit_link.'">recommencer la sélection des syst&egrave;mes...</a></p>');	
	echo('</div>');
	break;

} // end switch($_GET["step"])

}
// on affiche le selecteur de periode
function affichePeriode() {
/* on numerote les etapes :
1 = selectionner ou non des especes
2 = selection des especes
3 = selection pays/systemes
4= selection periode
5= selection type exploitation
*/
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
	echo("<h2>4. s&eacute;lectionner une periode d&#x27;int&eacute;r&ecirc;t</h2>");
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
	$from='';
	$to='';
	if ($array_c[0]["campagne_debut"]<$array_e[0]["enquete_debut"]) {$from=date_parse($array_c[0]["campagne_debut"]);} else {$from=date_parse($array_c[0]["enquete_debut"]);}
	if ($array_c[0]["campagne_fin"]>$array_e[0]["enquete_fin"]) {$to=date_parse($array_c[0]["campagne_fin"]);} else {$to=date_parse($array_c[0]["enquete_fin"]);}

	

	$debut["annee"]=$from["year"];
	$debut["mois"]=$from["month"];
	$debut["jour"]=$from["day"];
	$fin["annee"]=$to["year"];
	$fin["mois"]=$to["month"];
	$fin["jour"]=$to["day"];

	//debug 	echo('<pre>');print_r($debut);echo('</pre>');
	//debug 		echo('<pre>');print_r($fin);echo('</pre>');
	echo('<p>(p&eacute;riode couverte : de '.$debut["annee"].'-'.$debut["mois"].'-'.$debut["jour"].' &agrave; '.$fin["annee"].'-'.$fin["mois"].'-'.$fin["jour"].')</p>');

	
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
	echo('<p id="step_4_link"  class="clear"><a href="#" onclick="javascript:goToNextStep(4,\''.$url.'\');">ajouter et choisir un type d&#x27;exploitation ...</a></p>');}
	echo('</div>'); // fin de div id="step_4"
	// on reinitialise les parametres de selection stockes dans la session
	$_SESSION["selection_1"]=array();
	$_SESSION["selection_0"]=replaceQueryParam ($_SERVER['FULL_URL'],'step',4);
	break;
	default:
	// on a depasse cette etape, on affiche le resume textuel
	echo('<div id="step_4">');
	echo("<h2>4. p&eacute;riode d&#x27;int&eacute;r&ecirc;t</h2>");
	echo('de '.number_pad($_GET["d_m"],2).'/'.$_GET["d_a"].' &agrave; '.number_pad($_GET["f_m"],2).'/'.$_GET["f_a"]);
	// le lien permettant d'éditer la selection de la periode
	$edit_link=replaceQueryParam ($_SERVER['FULL_URL'],'step',4);
	$edit_link=removeQueryStringParam($edit_link, 'exploit');
	$edit_link=removeQueryStringParam($edit_link, 'donnees');
	$edit_link=removeQueryStringParam($edit_link, 'secteurs\[\]');
	echo('<p id="edit_periode"><a href="'.$edit_link.'">recommencer la sélection de la p&eacute;riode...</a></p>');	
	echo('</div>');
	break;

} // end switch $_GET["step"]
}
// on affiche le choix du type d'exploitation
function afficheTypeExploitation() {
	
/* on numerote les etapes :
1 = selectionner ou non des especes
2 = selection des especes
3 = selection pays/systemes
4= selection periode
5= selection type exploitation
6= selection type de donnees (exp ou art)
*/	

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
	echo('<h2>5. s&eacute;lectionner un type d&#x27;exploitation</h2>');
	echo('<ul>');
		echo('<li><a href="selection.php?exploit=donnees&step=6">extraction de donn&eacute;es</a></li>');
		echo('<li><a href="selection.php?exploit=stats&step=6">statistiques de p&ecirc;che</a></li>');
		echo('<li><a href="selection.php?exploit=cartes&step=6">fonds de cartes</a></li>');
		/*echo('<li>graphiques</li>');
		echo('<li>indicateurs &eacute;cologiques</li>');*/
	echo('</ul>');
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
	echo('<h2>5. type d&#x27;exploitation</h2>');
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
	// le lien permettant d'éditer la selection du type d'exploitation
	$edit_link=$_SESSION["selection_0"];
	echo('<p id="edit_exploit"><a href="'.$edit_link.'">recommencer la s&eacute;lection du type d&#x27;exploitation...</a></p>');	
	echo('</div>');
} // end switch
}


function afficheTypeDonnees() {
	
/* on numerote les etapes :
1 = selectionner ou non des especes
2 = selection des especes
3 = selection pays/systemes
4= selection periode
5= selection type exploitation
6= selection type de donnees (exp ou art)
*/	

global $connectPPEAO; // la connexion a la base
global $campagnes_ids; // la liste des campagnes deja selectionnees
global $enquetes_ids; // la liste des enquetes deja selectionnees
global $compteur;

	switch($_GET["step"]) {
		case 6:
		// on en est a cette etape, on affiche le selecteur
		echo('<div id="step_6">');
		echo('<h2>6. s&eacute;lectionner le type de donn&eacute;es &agrave; extraire</h2>');
		echo('<ul>');
		//si il reste des campagnes
		if ($compteur["campagnes_total"]!=0) {
		echo('<li><a href="selection.php?exploit=donnees&donnees=exp&step=7">donn&eacute;es de p&ecirc;che exp&eacute;rimentale</a></li>');}
		//si il reste des enquetes
		if ( $compteur["enquetes_total"]!=0) {
		echo('<li><a href="selection.php?exploit=donnees&donnees=art&step=7">donn&eacute;es de p&ecirc;che artisanale</a></li>');}
		echo('</ul>');
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
		// le lien permettant d'éditer la selection du type de donnees a extraire
		$edit_link=replaceQueryParam ($_SERVER['FULL_URL'],'step',6);
		$edit_link=removeQueryStringParam($edit_link, 'donnees');
		$edit_link=removeQueryStringParam($edit_link, 'secteurs\[\]');
		echo('<p id="edit_donnees"><a href="'.$edit_link.'">6. recommencer la s&eacute;lection du type de donn&eacute;es &agrave; extraire...</a></p>');	
		echo('</div>');
		break;
	}
}


function afficheSecteurs($donnees) {
	
	global $connectPPEAO;
	global $campagnes_ids;
	global $enquetes_ids;
	
	switch($_GET["step"]) {
		case 7: 
		// on en est a cette etape, on affiche le selecteur
		echo('<div id="step_7">');
			echo('<form id="step_7_form" name="step_7_form" target="/extraction/selection/selection.php" method="GET">');
		echo('<h2>7. s&eacute;lectionner des secteurs</h2>');
		
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
			$sql='SELECT DISTINCT ref_secteur.id, ref_secteur.nom as secteur, ref_systeme.libelle as systeme, ref_pays.nom as pays FROM ref_secteur, ref_systeme,ref_pays WHERE ref_secteur.ref_systeme_id IN 
				(SELECT DISTINCT ref_systeme_id FROM art_agglomeration WHERE art_agglomeration.id IN (
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
			echo('<p class="clear"><a href="#" onclick="javascript:goToNextStep(7,\''.$url.'\');">ajouter et passer &agrave; la s&eacute;lection des '.$nextSelectionStep.'...</a></p>');
		echo('</form>');
		echo('</div>');
		break; // end case step=7
		
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
		foreach ($array as $secteur) {$secteurs_noms[]='<span class="grey">('.$secteur["pays"].'/'.$secteur["systeme"].')</span> '.$secteur["secteur"];}
		$liste_secteurs=arrayToList($secteurs_noms,', ','.');}
		else {
			$liste_secteurs="tous";
		}
		echo("<p>secteurs : $liste_secteurs</p>");
		// le lien permettant d'éditer la selection des secteurs
		$edit_link=replaceQueryParam ($_SERVER['FULL_URL'],'step',7);
		echo('<p id="edit_secteurs"><a href="'.$edit_link.'">7. recommencer la sélection des secteurs...</a></p>');
		echo('</div>');
		// on a depasse cette etape, on affiche le resume textuel
		break;
	}
}

function afficheCampagnes() {
	
}

function afficheEngins() {
	
}

function afficheAgglomerations() {
	
}

function affichePeriodeEnquetes() {
	
}

function afficheGrandsTypesEngins() {
	
}
?>