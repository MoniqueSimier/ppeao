<?php 
// definit a quelle section appartient la page
$section="gerer";
$subsection="export";
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';

$zone=7; // zone gerer (voir table admin_zones)
?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php 
		// les balises head communes  toutes les pages
		include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
	?>
	<script src="/js/ajaxExport.js" type="text/javascript" charset="iso-8859-15"></script>
	<title>ppeao::exporter param&eacute;trage vers ACCESS</title>

</head>

<body>


<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
?>

<div id="main_container" class="home">

<?php
	if (isset($_SESSION['s_ppeao_user_id'])){ 
		$userID = $_SESSION['s_ppeao_user_id'];
	} else {
		$userID=null;
	}

	// on teste à quelle zone l'utilisateur a accès
	if (userHasAccess($userID,$zone)) {


		$_SESSION['s_status_export'] = 'ko';

?>
			<h1>Export</h1>
	<p>Cette section permet d'exporter les donn&eacute;es.</p>
		<br/>
		<p>Ce traitement permet de cr&eacute;er les fichiers ACCESS .mdb qui serviront de source pour les bases ACCESS d&eacute;ploy&eacute;es sur le terrain. Pour chaque cas (p&ecirc;ches exp&eacute;rimentales ou p&ecirc;ches artisanales), le r&eacute;f&eacute;rentiel et le param&eacute;trage seront mis &agrave; jour et des fichiers zip contenant toutes les bases n&eacute;cessaires aux op&eacute;rateurs sur le terrain sera g&eacute;n&eacute;r&eacute;. </p>
		<br/>
		<div id="runProcess">
		<form id="formProcessAuto">
			<br/><h2>Choissisez le type de p&ecirc;che à exporter : </h2>
			 <input type="radio" id="typepecheexp" name="typepeche" value="exp" checked="checked" />&nbsp;P&ecirc;ches expérimentales<br />
			<input type="radio" id="typepecheart" name="typepeche" value="art" />&nbsp;P&ecirc;ches artisanales<br/><br/>
			<input id="startProcess" type="button" value="Lancer le traitement" onClick="runProcess()"/>

			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;G&eacute;n&eacute;rer un fichier de log compl&eacute;mentaire <input type="checkbox" name="logsupp" id="logsupp" checked="checked"/><br/>
		</form><br/>
		</div>
		<div id="titleProcess">D&eacute;tail des process.</div>
		<br/>
		
		<?php // for test include $_SERVER["DOCUMENT_ROOT"].'/export/export_access.php'; 
			
			$_SESSION['s_erreur_process'] = false;
			$_SESSION['s_status_export'] = 'ok';
			$_SESSION['s_CR_export'] = "";
			// Pour test: toutes les étapes ne sont pas obligatoires.
			// On ajoute une variable qui permet de les rendre globalement obligatoire
			//$boutDisabled = "disabled=\"disabled\""; // desactivés
			$boutDisabled = ""; // activés
		?>
		<div id="vidage">
			<div id="vidage_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="vidage_txt">Vidage de la base ACCESS de travail.</div>
			<div id="vidage_chk">Lancer vidage<input type="checkbox" id="videcheck" checked="checked" <?php echo $boutDisabled;?>/></div>
			<?php 	$navbarLevel = 1;
					$texteDiv = "Compte rendu du vidage de la base de travail";	
					include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>
		</div>
		<div id="copiePPEAO">
			<div id="copiePPEAO_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="copiePPEAO_txt">Copie des donnees depuis la base PPEAO (postgreSQL) de reference.</div>
			<div id="copiePPEAO_chk">Lancer copie PPEAO<input type="checkbox" id="copPPEAOcheck" checked="checked" <?php echo $boutDisabled;?>/></div>
			<?php 	$navbarLevel = 2;
					$texteDiv = "Compte rendu de copie depuis base PPEAO de reference.";	
					include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>
		</div>
		<div id="copieACCESS">
			<div id="copieACCESS_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="copieACCESS_txt">Copie des donnees depuis la base ACCESS de reference.</div>
			<div id="copieACCESS_chk">Lancer copie ACCESS<input type="checkbox" id="copAcccheck" checked="checked" <?php echo $boutDisabled;?>/></div>
			<?php 	$navbarLevel = 3;
					$texteDiv = "Compte rendu de copie depuis base ACCESS de reference.";	
					include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>

		</div>
		<div id="copieZip">
			<div id="copieZip_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="copieZip_txt">Zip des bases.</div>
			<div id="copieZip_chk">Lancer zip des bases<input type="checkbox" id="zipcheck" checked="checked" <?php echo $boutDisabled;?>/></div>
			<?php 	$navbarLevel = 4;
				$texteDiv = "Compte rendu du zip des bases.";	
				include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>
		</div>
		<div id="exportOK"><div id="exportOK_img"><img src="/assets/incomplete.png" alt=""/></div><div id="exportOK_txt">Status du portage automatique.</div></div>

<?php
// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}
?>
</div> <!-- end div id="main_container"-->

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
