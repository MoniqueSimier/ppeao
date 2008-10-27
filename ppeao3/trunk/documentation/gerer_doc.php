<?php 
// Créé par Yann Laurent, 18-10-2008
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="gerer";
$subsection="documentation";
$zone=6; // zone edition (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::creation/modification de la documentation</title>
	


</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';

// on teste à quelle zone l'utilisateur a accès
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>

<div id="main_container" class="home">

<?php

//Include for documentation
include $_SERVER["DOCUMENT_ROOT"].'/documentation/functions_doc.php';

?>


<!-- gestion de la documentation -->
<?php 
	$labelModify = "modifier";
	$labelDelete = "supprimer";
	$labelBrowse = "telecharger";
	$repDoc ="";
	$action ="";
	$actionLabel="";
	if (isset($_GET['rep'])) {
		$repDoc = $_GET['rep'];
	}
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}
	switch ($action) {
		case "modify" : $actionLabel = "modifier"; break;
		case "create" : $actionLabel = "cr&eacute;er"; break;
	}
	$nomRep = str_replace("\\" ," ", $repDoc); // A revoir
	$nomRep = str_replace("/" ," ", $nomRep); // A revoir
	echo "<h1 >".$actionLabel." de la documentation pour ".$nomRep."</h1><br/>";
	echo "<div id=\"listeDoc\">";
	switch ($action){
		case "modify" :
			// On recupere les informations pour les corriger eventuellement
			$RawListDoc = getDocumentation($repDoc,"raw","n","");
			if ( $RawListDoc =="") {
				echo "erreur de lecture du r&eacute;pertoire ".$repDoc;
			} else {
				// On analyse le répertoire pour créer les différents inputs
				//Test echo"<br/>".$RawListDoc;
				$listeDoc = explode(",",$RawListDoc);
				$nbDoc = count($listeDoc) - 1;
				echo "<h2>".count($listeDoc)." documents disponibles pour ".$nomRep."</h2>";
				echo "<ul class=\"listDoc\">";
				for ($cpt = 0; $cpt <= $nbDoc; $cpt++) {
				echo "<li class=\"selDoc\">		
				<span class=\"doccol1\" >".$listeDoc[$cpt]."</span><span class=\"doccol2\" >&nbsp;<a id=\"Action[".$cpt."]\" class=\"link_button\" href=\"\">".$labelModify."</a>&nbsp;<a id=\"Action[".$cpt."]\" class=\"link_button\" href=\"\">".$labelDelete."</a></span></li>"
				
				;
				
				}
				echo "</ul>";
			}
			break;
		case "create" : 
			echo "veuillez s&eacute;lectionner le fichier à importer.&nbsp;
			
			<form id=\"gestdoc\" method=\"POST\" action=\"/documentation/upload.php\" enctype=\"multipart/form-data\">
				 <input type=\"hidden\" name=\"max_file_size\" value=\"100000\">
				 <input type=\"hidden\" name=\"repName\" value=\"".$repDoc."\">
				 Fichier : <input type=\"file\" name=\"avatar\">
				 <input type=\"submit\" name=\"envoyer\" value=\"".$labelBrowse."\">
			</form>";
			break;

		
	} 
	echo "</div>";

	
?>
<br/>
<form id="retour" action="/gestion_doc.php">
<input type="hidden" name ="" value=""/>
<input type="submit" value="Retour"/>
</form>

</div>
<?php 
// note : on termine la boucle testant si l'utilisateur a accès à la page demandée

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas accès ou n'est pas connecté, on affiche un message l'invitant à contacter un administrateur pour obtenir l'accès
else {userAccessDenied($zone);}

?>

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
