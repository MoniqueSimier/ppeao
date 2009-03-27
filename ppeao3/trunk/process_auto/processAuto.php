<?php 
//*****************************************
// processAuto.php
//*****************************************
// Created by Yann Laurent
// 2008-07-15 : creation
//*****************************************
// Ce programme lance les processus automatiques de recomposition de donn�es


// Variable de test
$pasdetraitement = true;
$continueTrait = true;
// Includes standard
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/functions.php';


$traitRecompOk = false;
$CRexecution = "";
$affichageDetail = false; // Pour afficher ou non le detail des traitements � l'�cran
session_start();
// On identifie si le traitement est ex�cutable ou non
if (isset($_GET['exec'])) {
	if ($_GET['exec'] == "false") {
		$pasdetraitement =  true; 
		$Labelpasdetraitement ="non";
	} else {
		$pasdetraitement =  false;
		$Labelpasdetraitement ="oui";
	}
}

// Si le traitement pr�c�dent a �chou�, arr�t du traitement
if (isset($_GET['pg'])) {
	$typeAction = $_GET['pg'];
	switch($typeAction){
		case "rec":
			// Comparaison du referentiel / parametrage 
			$nomFenetre = "processAutoRec";
			$nomAction = "recomposition des donn&eacute;es";
			$numFen = 5;
			 break;
		case "stat":
			// Comparaison du parametrage de BDPECHE
			$nomFenetre = "processAutoStat";
			$nomAction = "estimation des statistiques de p&ecirc;che";
			$numFen = 6;
			 break;
	}
} else {
	$_SESSION['s_status_process_auto'] == 'ko';
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">	Il manque le parametre pg. Contactez votre admin PPEAO</div>" ;
	exit;
}

if (isset($_SESSION['s_status_process_auto'])) {
	if ($_SESSION['s_status_process_auto'] == 'ko') {
		logWriteTo(7,"error","**- ARRET du traitement car le processus precedent est en erreur.","","","0");
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">ARRET du traitement Recomposition / calcul statistique car le processus precedent est en erreur</div>" ;
		exit;
	}
}




if (! $pasdetraitement ) { // test pour debug lors du lancement de la chaine compl�te de traitement automatique (saute cette etape)
	// le processus de recomposition des donn�es
	$messageinfo = "";
	switch($typeAction){
		case "rec":
			// Contr�le pr�liminaire
				// On contr�le art_debarquement
				$connectionTest =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
				$queryTest = "select count(id) FROM art_debarquement";
				$resultTest = pg_query($connectionTest, $queryTest);
				$rowTest= pg_fetch_row($resultTest);
				$nb_deja_recTest = $rowTest[0];
				if ($nb_deja_recTest == 0){
					$continueTrait = false;
					$messageinfo = "art_debarquement est vide. Pas de recomposition possible";
				}
				pg_free_result($resultTest);
				
				if ($continueTrait) {
					// On contr�le maintenant art_activite
					$queryTest = "select count(id) FROM art_activite";
					$resultTest = pg_query($connectionTest, $queryTest);
					$rowTest= pg_fetch_row($resultTest);
					$nb_deja_recTest = $rowTest[0];
					if ($nb_deja_recTest == 0){
						$continueTrait = false;
						$messageinfo = "art_activite est vide. Pas de recomposition possible";
					}
					pg_free_result($resultTest);
				}
			if ($continueTrait) {
				$queryDelete = "delete from art_debarquement_rec";
				$resultTest = pg_query($connectionTest, $queryDelete);				
				if (!$resultTest) {
					$messageinfo .= "<b>Erreur</b> vidage art_debarquement_rec <br/>";
				} else {
					if ($affichageDetail) {
						$messageinfo .= "<b>art_debarquement_rec</b> vid&eacute;e.<br/>";
					}
				}
				$queryDelete2 = "delete from art_fraction_rec";
				$resultTest2 = pg_query($connectionTest, $queryDelete2);				
				if (!$resultTest2) {
					$messageinfo .= "<b>Erreur</b> vidage art_fraction_rec <br/>";
				} else {
					if ($affichageDetail) {
						$messageinfo .= "<b>art_fraction_rec</b> vid&eacute;e.<br/>";
					}
				}
				include $_SERVER["DOCUMENT_ROOT"].'/recomposition/recomposition_pas_a_pas.php';
				$query = "select count(id) FROM art_debarquement_rec";
				$result = pg_query($connectionTest, $query);
				$row= pg_fetch_row($result);
				$nb_deja_rec = $row[0];
				if ($nb_deja_rec == 0){
					$messageinfo .= " pas d'enqu&ecirc;te recompos&eacute;e. ";
				} else {
					$traitRecompOk = true;
					$messageinfo .= $nb_deja_rec . " enqu&ecirc;te(s) recompos&eacute;e(s). ";
				}
			}
			break;
			
		case "stat":
			include $_SERVER["DOCUMENT_ROOT"].'/statistiques/statistiques.php';	
			$messageinfo .= " Traitement effectu&eacute; (c'est toujours le cas, statistiques.php ne renvoie jamais d'erreur)";
			$traitRecompOk = true;
			break;
	} 
	// Lancement de l'�tape de calcul statistique
	// Pour renvoyer au javascript l'etat du traitement
	echo "<form id=\"formtrt\"> 
		<input id=\"trtok".$numFen."\" 	type=\"hidden\" value=\"".$_SESSION['s_status_process_auto']."\"/>
		</form>";
	if ($traitRecompOk) {
		// Traitement OK
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Processus ".$nomAction." ex&eacute;cut&eacute; avec succ&egrave;s : </div><div id=\"".$nomFenetre."_chk\">Exec= ".$Labelpasdetraitement."</div>";	
						//echo"<div class=\"marginCR\">Compte Rendu&nbsp;<a id=\"v_slidein".$numFen."\" href=\"#\"> Afficher </a>|<a id=\"v_slideout".$numFen."\" href=\"#\"> Fermer </a>| <strong>status</strong>: <span id=\"vertical_status".$numFen."\">open</span>				</div>";
		echo"<div id=\"vertical_slide".$numFen."\">".$messageinfo."</div>";
	} else {
		// Erreur dans le traitement
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Erreur dans le processus ".$nomAction." des donn&eacute;es </div><div id=\"".$nomFenetre."_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
						//echo"<div class=\"marginCR\">Compte Rendu&nbsp;<a id=\"v_slidein".$numFen."\" href=\"#\"> Afficher </a>|<a id=\"v_slideout".$numFen."\" href=\"#\"> Fermer </a>| <strong>status</strong>: <span id=\"vertical_status".$numFen."\">open</span>				</div>";
		echo"<div id=\"vertical_slide".$numFen."\">".$messageinfo."</div>";
	}
	// Le processus de recalcul des stats



} else {
	echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Etape ".$nomAction." non ex&eacute;cut&eacute;e par choix de l'utilisateur</div><div id=\"".$nomFenetre."_chk\">Exec= ".$Labelpasdetraitement."</div>";
} // end if (! $pasdetraitement )
exit;

?>