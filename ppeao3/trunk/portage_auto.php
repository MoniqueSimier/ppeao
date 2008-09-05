<?php 
// Mis à jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="portage";
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
$zone=3; // zone portage (voir table admin_zones)
?>

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/process_auto/config.php';
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<?php 
			// les balises head communes  toutes les pages
			include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
		?>
		<title>PPEAO Portage automatique</title>
		<script src="/js/ajaxProcessAuto.js" type="text/javascript" charset="iso-8859-1"></script>
	</head> 
	<body>
<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
	$_SESSION['s_cpt_champ_total'] = 0;	// Lecture d'une table, nombre d'enregistrements lus total
	$_SESSION['s_cpt_champ_diff'] = 0;	// Lecture d'une table, nombre d'enregistrements différents
	$_SESSION['s_cpt_champ_egal'] = 0;	// Lecture d'une table, nombre d'enregistrements identiques
	$_SESSION['s_cpt_champ_vide'] = 0;	// Lecture d'une table, nombre d'enregistrements vide
	$_SESSION['s_cpt_table_total'] = 0;	// Nombre global de tables lues
	$_SESSION['s_cpt_table_diff'] = 0;	// Nombre global de tables différentes entre reference et cible
	$_SESSION['s_cpt_table_egal'] = 0;	// Nombre global de tables identiques entre reference et cible
	$_SESSION['s_cpt_table_vide'] = 0;	// Nombre global de tables vides dans cible 
	$_SESSION['s_cpt_table_manquant'] = 0;	// Nombre global de tables avec des enreg manquants dans cible 
	$_SESSION['s_num_encours_fichier_SQL'] = 1; // Numero du fichier SQL en cours
	$_SESSION['s_cpt_lignes_fic_sql'] = 0;		// Nombre de lignes dans le fichier SQL en cours
	$_SESSION['s_cpt_table_diff_manquant'] = 0;
	$_SESSION['s_erreur_process'] = false;
	$_SESSION['s_cpt_erreurs_sql'] = 0;
	$_SESSION['s_CR_processAuto'] = "";

?>
		<div id="main_container" class="home">
			<div id="BDDetail">
				<?php  $subsection="auto"; include $_SERVER["DOCUMENT_ROOT"].'/left_navbar.inc'; ?>
				<?php  include $_SERVER["DOCUMENT_ROOT"].'/version.inc'; ?>
			</div>
			<div id="subContent">
				<h1>Base de donn&eacute;es PPEAO</h1>
				<p>Peuplements de poissons et P&ecirc;che artisanale des Ecosyst&egrave;mes estuariens,
				lagunaires ou continentaux d'Afrique de l'Ouest</p>
				<br/>
				<p>Ce processus permet un portage automatique des bases issues des bases access dans la base principale PPEAO.</p>
				<br/>
				<?php 
					logWriteTo(4,"notice","*** Ouverture page portage automatique","","","0");
					// Récupération du nombre d'enquête à traiter pour la recomposition auto
					$query = "select count(art_debarquement.id) FROM art_debarquement";
					$result = pg_query($connectPPEAO, $query);
					if (!$result) {  echo "Une erreur s'est produite";  exit;}
					// Recuperation du resultat
					$row= pg_fetch_row($result);
					$nb_enr = $row[0];
				?>
				<div id="runProcess">
				<form id="formProcessAuto">
					Vous pouvez saisir une adresse mail pour recevoir le compte-rendu de traitement.<br/>
					<input type="text" name="adresse" id="adresse"/>
					<br/>
					<input type="checkbox" name="logsupp" id="logsupp"/>G&eacute;n&eacute;rer un fichier de log sp&eacute;cial (attention, peut ralentir notablement le processus)<br/><br/>
					<input id="startProcess" type="button" value="Lancer le traitement" onClick="runProcess()"/>
					<?php  // Input pour recomposition automatique ?>
					<input type="hidden" id="BDName" value="<?php  echo "$bdd"; ?>">
					<input type="hidden" id="NBEnr" value="<?php   echo "$nb_enr";?>" >
				</form><br/>
				</div>
				<div id="titleProcess">D&eacute;tail des process.</div>
				<br/>
				<div id="sauvegarde"><div id="sauvegarde_img"><img src="/assets/incomplete.png" alt=""/></div><div id="sauvegarde_txt">Sauvegarde.</div></div>
				<div id="comparaison"><div id="comparaison_img"><img src="/assets/incomplete.png" alt=""/></div><div id="comparaison_txt">Comparaison.</div></div>
				<div id="copieScientifique"><div id="copieScientifique_img"><img src="/assets/incomplete.png" alt=""/></div>
				<div id="copieScientifique_txt">Copie des donn&eacute;es scientifiques.</div>
				</div>
				<div id="processAuto"><div id="processAuto_img"><img src="/assets/incomplete.png" alt=""/></div>
				<div id="processAuto_txt">Process recalcul donn&eacute;es.</div>
				</div>
				<div id="copieRecomp"><div id="copieRecomp_img"><img src="/assets/incomplete.png" alt=""/></div>
				<div id="copieRecomp_txt">Copie des donn&eacute;es recompos&eacute;es.</div>
				</div>
				<div id="portageOK"><div id="portageOK_img"><img src="/assets/incomplete.png" alt=""/></div><div id="portageOK_txt">Status du portage automatique.</div></div>
				<div id="purge"><div id="purge_img"><img src="/assets/incomplete.png" alt=""/></div>
				<div id="purge_txt">Purge des donn&eacute;es.</div>
				</div>
				<?php // Un formulaire bidon pour renvoyer l'etat du traitement au javascript*/ ?>
				<form id="statusProcess">
					<input id="valStatusProc" value="ok" type="hidden"/>
				</form>
			</div>
		</div>	<!-- end div id="main_container"-->


<?php 
// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}

?>
	
	</body>
</html>
