<?php 
// Créé par Olivier ROUX, 02-08-2008
// code commun à toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="edition";
$zone=2; // zone edition (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
	<title>ppeao::&eacute;dition des donn&eacute;es::&eacute;dition de la table s&eacute;lectionn&eacute;e</title>

<script src="/js/edition.js" type="text/javascript"></script>

<!-- l'effet "tiroir" pour afficher/masquer le sélecteur -->
<script type="text/javascript" charset="iso-8859-1">
/* <![CDATA[ */
	window.addEvent('domready', function(){
				// note: the onComplete is there to set an automatic height to the wrapper div
				var selectorSlide = new Fx.Slide('selector_content',{duration: 500, mode: 'vertical', onComplete: function(){if(this.wrapper.offsetHeight != 0) this.wrapper.setStyle('height', 'auto');}});
				// when the result page loads, the selector is displayed, then it slides out and is hidden
				selectorSlide.slideOut.delay(500, selectorSlide);
				//since the selector hides away, display a "show" link
				$('showHideSelect').innerHTML='[afficher la s&eacute;lection]';
				// when the user clicks on the hide/show button, the slider's visibility is toggled
				$('showHideSelect').addEvent('click', function(e){
					e = new Event(e);
					selectorSlide.toggle();
					e.stop();
					// if the selector is displayed, the link reads "hide",
					//if it is hidden, the link reads "show"
					if(selectorSlide.wrapper.offsetHeight==0) {$('showHideSelect').innerHTML='[masquer la s&eacute;lection]';} else {$('showHideSelect').innerHTML='[masquer la s&eacute;lection]';}
				});
			});	

		
/* ]]> */
</script>

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

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';


?>

<!-- le SELECTEUR -->
<div id="selector_container">

<?php
	// insertion du sélecteur, en mode "page de selection"
	createSelector("selection");
?>
</div> <!-- end div selector_container -->


<!-- l'ÉDITEUR -->
<div id="editor_container">
<h1>votre s&eacute;lection : </h1>
<?php 
// on construit la requête SQL pour obtenir les valeurs de la table à afficher
$editTable=$_GET["editTable"];
if (isset($_GET[$editTable])) {
	$theTableValues=implode($_GET[$editTable],"','");
	$whereClause=' AND '.$tablesDefinitions[$editTable]["id_col"].' IN (\''.$theTableValues.'\') ';
	}
	else {$whereClause=NULL;}
$tableSql='	SELECT * FROM '.$tablesDefinitions[$editTable]["table"].'
				WHERE  TRUE '.$whereClause.' 
				ORDER BY '.$tablesDefinitions[$editTable]["id_col"].'
			';

//debug 		echo($tableSql);

$tableResult=pg_query($connectPPEAO,$tableSql) or die('erreur dans la requete : '.$tableSql. pg_last_error());
$tableArray=pg_fetch_all($tableResult);

// on affiche la table
/*debug 
echo('<pre>');
	print_r($tableArray);
echo('</pre>'); */

echo('<table id="la_table" border="0" cellspacing="0" cellpadding="5">');

// on affiche l'en-tête de table
$theHeads=array_keys($tableArray[0]);
echo('<hr>');
foreach ($theHeads as $oneHead) {echo('<td class="small">'.$oneHead.'</td>');}
echo('<td class="small">action</td>');
echo('</hr>');

$i=0;
foreach ($tableArray as $theRow) {
	// affiche la ligne avec un style différent si c'est un rang pair ou impair 
	if ( $i&1 ) {$rowStyle='edit_row_odd';} else {$rowStyle='edit_row_even';}
	echo('<tr id="row_'.$theRow["id"].'" class="'.$rowStyle.'">');
		foreach ($theRow as $theColumn) {
			echo('<td class="'.$rowStyle.' small">');
			echo($theColumn);
			echo('</td>');
		}
	// la colonne d'outils
	echo('<td><a href="" class="small link_button">supprimer</a></td>');
	echo('</tr>');
	$i++;
}
echo('</table>')
	

?>
</div> <!-- end div id="editor_container" -->


<?php

$theType=$_GET["type"];
$theHierarchy=$_GET["hierarchy"];
$theTable=$_GET["table"];

switch ($theType) {
	case "reference" : $theTypeString=" de r&eacute;f&eacute;rence"; $theSelectorType="tableSelectors";
	break;
	case "codage" : $theTypeString=" de codage"; $theSelectorType="tableSelectors";
	break;
	default: $theTypeString="";
	break;
		}
$theLogString=' : '.$theTypeString.' '.${$theSelectorType}[$theHierarchy][$theTable]["label"];


logWriteTo(2,'notice','acc&egrave;s &agrave; l\'&eacute;dition de la table de '.$theLogString,'','',0);

?>
	
</div> <!-- end div id="main_container"-->


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
