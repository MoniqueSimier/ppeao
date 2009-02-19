<?php 
// Mis � jour par Olivier ROUX, 29-07-2008
// definit a quelle section appartient la page
$section="gerer";
$subsection="";
// code commun � toutes les pages (demarrage de session, doctype etc.)
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
	<h1>Export</h1>
	<p>Cette section permet d'exporter les donn&eacute;es.</p>
<?php
//debug echo('+'.$_SESSION['s_ppeao_user_id'].'+');

// on teste � quelle zone l'utilisateur a acc�s
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {


		$_SESSION['s_status_export'] = 'ko';

?>
		<br/>
		<p>La description du traitement xxxxxxxxxxxxxxxxxxxxx</p>
		<br/>
		<div id="runProcess">
		<form id="formProcessAuto">
			<br/><h2>Choissisez le type de p&ecirc;che � exporter : </h2>
			 <input type="radio" id="typepecheexp" name="typepeche" value="exp" />&nbsp;P&ecirc;ches exp�rimentales<br />
			<input type="radio" id="typepecheart" name="typepeche" value="art" checked="checked" />&nbsp;P&ecirc;ches artisanales<br/><br/>
			<input id="startProcess" type="button" value="Lancer le traitement" onClick="runProcess()"/>

			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;G&eacute;n&eacute;rer un fichier de log compl&eacute;mentaire <input type="checkbox" name="logsupp" id="logsupp" checked="checked"/><br/>
		</form><br/>
		</div>
		<div id="titleProcess">D&eacute;tail des process.</div>
		<br/>
		
		<?php // for test include $_SERVER["DOCUMENT_ROOT"].'/export/export_access.php'; ?>
		
		<div id="controleBase">
			<div id="controleBase_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="controleBase_txt">Controle base de reference ACCESS.</div>
			<div id="controleBase_chk">Controler base<input type="checkbox" id="ctrlcheck" checked="checked"/></div>
			<?php 	$navbarLevel = 1;
					$texteDiv = "Compte rendu du controle de la base.";	
					include $_SERVER["DOCUMENT_ROOT"].'/export/navbarCR.inc'; ?>
		</div>

		<div id="vidage">
			<div id="vidage_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="vidage_txt">Vidage de la base ACCESS de travail.</div>
			<div id="vidage_chk">Lancer vidage<input type="checkbox" id="videcheck" checked="checked"/></div>
			<?php 	$navbarLevel = 2;
					$texteDiv = "Compte rendu du vidage de la base de travail";	
					include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>
		</div>
		<div id="copiePPEAO">
			<div id="copiePPEAO_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="copiePPEAO_txt">Copie des donnees depuis la base PPEAO (postgreSQL) de reference.</div>
			<div id="copiePPEAO_chk">Lancer copie PPEAO<input type="checkbox" id="copPPEAOcheck" checked="checked"/></div>
			<?php 	$navbarLevel = 3;
					$texteDiv = "Compte rendu de copie depuis base PPEAO de reference.";	
					include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>
		</div>
		<div id="copieACCESS">
			<div id="copieACCESS_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="copieACCESS_txt">Copie des donnees depuis la base ACCESS de reference.</div>
			<div id="copieACCESS_chk">Lancer copie ACCESS<input type="checkbox" id="copAcccheck" checked="checked"/></div>
			<?php 	$navbarLevel = 4;
					$texteDiv = "Compte rendu de copie depuis base ACCESS de reference.";	
					include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>

		</div>
		<!--<div id="controleBD">
			<div id="controleBD_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="controleBD_txt">Controle de la base de travail.</div>
			<div id="controleBD_chk">Lancer controle base<input type="checkbox" id="testcheck" checked="checked"/></div>
			<?php// 	$navbarLevel = 5;
				//$texteDiv = "Compte rendu de controle de la base de travail.";	
				//include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>				
		</div>-->
	
		<div id="copieZip">
			<div id="copieZip_img"><img src="/assets/incomplete.png" alt=""/></div>
			<div id="copieZip_txt">Zip des bases.</div>
			<div id="copieZip_chk">Lancer zip des bases<input type="checkbox" id="zipcheck" checked="checked"/></div>
			<?php 	$navbarLevel = 5;
				$texteDiv = "Compte rendu du zip des bases.";	
				include $_SERVER["DOCUMENT_ROOT"].'/process_auto/navbarCR.inc'; ?>
		</div>
		<div id="exportOK"><div id="exportOK_img"><img src="/assets/incomplete.png" alt=""/></div><div id="exportOK_txt">Status du portage automatique.</div></div>

<?php
// note : on termine la boucle testant si l'utilisateur a acc�s � la page demand�e

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas acc�s ou n'est pas connect�, on affiche un message l'invitant � contacter un administrateur pour obtenir l'acc�s
else {userAccessDenied($zone);}
?>
</div> <!-- end div id="main_container"-->

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
