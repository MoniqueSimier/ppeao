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
	$OSType="";
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$OSType="WIN";
	} else {
		$OSType="OTHER";
	}
	// on teste à quelle zone l'utilisateur a accès
	if (userHasAccess($userID,$zone)) {


		$_SESSION['s_status_export'] = 'ko';

?>
		<h1>Export</h1>
		<p>Cette section permet de pr&eacute;parer et de r&eacute;aliser l'exportation des donn&eacute;es en format ACCESS.</p>
		<br/>

		<p>Vous allez pouvoir r&eacute;aliser les op&eacute;rations pr&eacute;paratoires &agrave; l'export des donn&eacute;es en format ACCESS, puis lancer cette export une fois la base de r&eacute;f&eacute;rence mise &agrave; jour.</p>
		<br/>

		  <h2>Op&eacute;rations pour la mise &agrave; jour de la base de r&eacute;f&eacute;rence bdppeo sur le PC</h2>
            <ul>
              <li><a href="javascript:doExportSelect('exportBaseRef');">Exporter la base de r&eacute;f&eacute;rence apr&egrave;s s&eacute;lection des donn&eacute;es &agrave; extraire pour pr&eacute;parer la mise &agrave; jour de la base de r&eacute;f&eacute;rence sur le PC</a>
              <div id="exportSelComp"></div>
              </li>
               <?php if ($OSType=="WIN") { ?>
              <li><a href="javascript:doExport('videbdppeaoPC','exportOutputVide','Base cible en cours de nettoyage ...');">Vider la base de r&eacute;f&eacute;rence bdppeao (pour les tests bdppeao_test) du PC pour pr&eacute;parer sa mise &agrave; jour</a>
              <div id="exportOutputVide"></div>
              </li>
              <li><a href="javascript:doExport('majbdppeaoPC','exportOutputInt','Base cible en cours de mise a jour...');">Lancer l'int&eacute;gration des donn&eacute;es de r&eacute;f&eacute;rence dans la base bdppeao (pour les tests bdppeao_test) sur le PC depuis le fichier /work/export/SQL-bdppeao/bdppeao_a_importer.sql</a>
              <div id="exportOutputInt"></div>
              </li>
              <?php } ?>
            </ul>
            <?php if ($OSType=="WIN") { ?>
            <h2>Export des donn&eacute;es en format ACCESS</h2>
  Vous pouvez acc&eacute;der &agrave; l'outil d'export des donn&eacute;es ici :<a href="export_process.php" target="_blank"> exporter les donn&eacute;es en format ACCESS</a>.
			<?php } ?>
		<br/>
        <br/>
        <br/>
        
<?php



// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}
?>
</div> <!-- end div id="main_container"-->

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
