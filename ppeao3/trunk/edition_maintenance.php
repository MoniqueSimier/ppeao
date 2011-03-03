<?php 
// Créé par Olivier ROUX, 02-08-2008
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="gerer";
$subsection="maintenance";

$zone=1; // zone gerer (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::g&eacute;rer::maintenance de la base</title>
	
<script src="/js/edition.js" type="text/javascript"  charset="iso-8859-15"></script>

</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
?>

<div id="main_container" class="home">
<!-- édition des tables de référence -->
<?php

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
// affiche un avertissement concernant l'utilisation de IE pour les outils d'administration
IEwarning();
?>


<?php

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';

?>


<h1>Opérations de maintenance de la base : "<?php echo($base_principale) ?>"</h1>

<ul>
	<li><h2><a href="javascript:doMaintenance('sequences_ref_param');">mise &agrave; jour des s&eacute;quences des tables de r&eacute;f&eacute;rence et de param&eacute;trage</a></h2>
	<p>utilisez cet outil apr&egrave;s avoir import&eacute; en batch des donn&eacute;es de r&eacute;f&eacute;rence et de param&eacute;trage, pour mettre &agrave; jour la prochaine valeur de la s&eacute;quence des tables ayant des identifiants uniques g&eacute;n&eacute;r&eacute;s automatiquement.</p>
	</li>
	<li><h2><a href="javascript:doMaintenance('sequences_donnees');">mise &agrave; jour des s&eacute;quences des tables de données</a></h2>
	<p>utilisez cet outil apr&egrave;s avoir import&eacute; en batch des donn&eacute;es de p&ecirc;che artisanale ou exp&eacute;rimentale, pour mettre &agrave; jour la prochaine valeur de la s&eacute;quence des tables ayant des identifiants uniques g&eacute;n&eacute;r&eacute;s automatiquement.</p>
	</li>
	<li><h2><a href="javascript:doMaintenance('vacuum');">VACUUM</a></h2>
	<p>utilisez cet outil apr&egrave;s avoir effectu&eacute; un grand nombre de suppressions dans la base de donn&eacute;es.</p>
	</li>
	<li><h2><a href="javascript:doMaintenance('reindex');">REINDEX</a></h2>
	<p>utilisez cet outil pour recréer les index apr&egrave;s avoir effectu&eacute; un grand nombre d&#x27;insertions ou de suppressions dans la base de donn&eacute;es.</p>
	</li></ul>
	<h1>Opérations de maintenance de la base : "BDPeche"</h1>
	<ul>
	<li><h2><a href="javascript:doMaintenance('disable_trigger');">D&eacute;sactiver des contraintes sur BDPeche</a></h2>
	<p>utilisez cet outil pour supprimer temporairement les contraintes sur la base (BDPECHE) contenant les donn&eacute;es &agrave; porter dans la base de r&eacute;f&eacute;rence (PPEAO).</p>
	</li>
	<li><h2><a href="javascript:doMaintenance('enable_trigger');">Activer les contraintes sur BDPeche</a></h2>
	<p>utilisez cet outil pour r&eacute;activer les contraintes sur la base (BDPECHE) contenant les donn&eacute;es &agrave; porter  dans la base de r&eacute;f&eacute;rence (PPEAO).</p>
	</li>	
    <li><h2><a href="javascript:doMaintenance('empty_bdpeche');">Vider la base BDPeche</a></h2>
    <p>utilisez cet outil pour vider la base bdpeche en supprimant les contraintes. </p>
    </li>
    <?php	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {?>
        <li>
        <h2><a href="javascript:doMaintenance('empty_ACCESS');">Vider les bases PostgreSQL au format ACCESS</a></h2>
        <p>utilisez cet outil pour vider les bases exp2003_bdd, pechart et les bases pays situées sur le serveur.</p>
        </li>	
     <?php } ?>
</ul>	

<div id="maintenance_output"></div>

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
