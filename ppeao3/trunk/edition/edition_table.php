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
<?php

$editTable=$_GET["editTable"];
if (isset($_GET[$editTable])) {
	$theTableValues=implode($_GET[$editTable],"','");
	$whereClause=' AND '.$tablesDefinitions[$editTable]["id_col"].' IN (\''.$theTableValues.'\') ';
	}
	else {$whereClause=NULL;}
	
// on construit la requête SQL pour obtenir le nombre total de valeurs de la table à afficher
$countSql='	SELECT COUNT(*) FROM '.$tablesDefinitions[$editTable]["table"].'
				WHERE  TRUE '.$whereClause.'
			';

$countResult=pg_query($connectPPEAO,$countSql) or die('erreur dans la requete : '.$countSql. pg_last_error());
$countRow=pg_fetch_row($countResult);

$countTotal=$countRow[0];
 /* Libération du résultat */ 
 pg_free_result($countResult);


// on prend en compte la pagination

/* Déclaration des variables */ 
    $rowsPerPage = 15; // nombre d'entrées à afficher par page (entries per page) 
    $countPages = ceil($countTotal/$rowsPerPage); // calcul du nombre de pages $countPages (on arrondit à l'entier supérieur avec la fonction ceil() ) 
 
    /* Récupération du numéro de la page courante depuis l'URL avec la méthode GET */ 
    if(!isset($_GET['page']) || !is_numeric($_GET['page']) ) // si $_GET['page'] n'existe pas OU $_GET['page'] n'est pas un nombre (petite sécurité supplémentaire) 
        $currentPage = 1; // la page courante devient 1 
    else 
    { 
        $currentPage = intval($_GET['page']); // stockage de la valeur entière uniquement 
        if ($currentPage < 1) $currentPage=1; // cas où le numéro de page est inférieure 1 : on affecte 1 à la page courante 
        elseif ($currentPage > $countPages) $currentPage=$countPages; //cas où le numéro de page est supérieur au nombre total de pages : on affecte le numéro de la dernière page à la page courante 
        else $currentPage=$currentPage; // sinon la page courante est bien celle indiquée dans l'URL 
    } 
 
    /* $start est la valeur de départ du LIMIT dans notre requête SQL (est fonction de la page courante) */ 
    $startRow = ($currentPage * $rowsPerPage - $rowsPerPage);


// on construit la requête SQL pour obtenir les valeurs de la table à afficher

$tableSql='	SELECT * FROM '.$tablesDefinitions[$editTable]["table"].'
				WHERE  TRUE '.$whereClause.' 
				ORDER BY '.$tablesDefinitions[$editTable]["id_col"].'
				LIMIT '.$rowsPerPage.' OFFSET '.$startRow.'  
			';

$tableResult=pg_query($connectPPEAO,$tableSql) or die('erreur dans la requete : '.$tableSql. pg_last_error());
$tableArray=pg_fetch_all($tableResult);

// libération du résultat
pg_free_result($tableResult);


// on prépare l'affichage du titre
$paginationString='';
if ($countTotal>$rowsPerPage) {
	$from=intval($startRow)+1; // +1 car les tableaux commencent à zéro
	$to=intval($startRow)+intval($rowsPerPage);
		// si on arrive à la fin du tableau
		if ($to>$countTotal) {$to=$countTotal;}
	$paginationString=' ('.$from.'-'.$to.')';
	}

?>
<h1>votre s&eacute;lection : <?php echo($countTotal.' '.$tablesDefinitions[$editTable]["label"].$paginationString);?></h1>
<?php 
// on affiche la table

echo('<table id="la_table" border="0" cellspacing="0" cellpadding="5">');

// on affiche l'en-tête de table
$theHeads=array_keys($tableArray[0]);
echo('<tr>');
foreach ($theHeads as $oneHead) {echo('<td class="small" style="font-weight:bold;">'.$oneHead.'</td>');}
echo('<td class="small">action</td>');
echo('</tr>');

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
echo('</table>');

// on affiche la pagination
echo paginate($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'], '&amp;page=', $countPages, $currentPage);
	

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
