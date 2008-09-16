<?php 
// Cr�� par Olivier ROUX, 02-08-2008
// code commun � toutes les pages (demarrage de session, doctype etc.)
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

<!-- l'effet "tiroir" pour afficher/masquer le s�lecteur -->
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

// on teste � quelle zone l'utilisateur a acc�s
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
	// insertion du s�lecteur, en mode "page de selection"
	createSelector("selection");
?>
</div> <!-- end div selector_container -->


<!-- l'�DITEUR -->
<div id="editor_container">
<?php

$editTable=$_GET["editTable"];
if (isset($_GET[$editTable])) {
	$theTableValues=implode($_GET[$editTable],"','");
	$whereClause=' AND '.$tablesDefinitions[$editTable]["id_col"].' IN (\''.$theTableValues.'\') ';
	}
	else {$whereClause=NULL;}
	
// on construit la requ�te SQL pour obtenir le nombre total de valeurs de la table � afficher
$countSql='	SELECT COUNT(*) FROM '.$tablesDefinitions[$editTable]["table"].'
				WHERE  TRUE '.$whereClause.'
			';

$countResult=pg_query($connectPPEAO,$countSql) or die('erreur dans la requete : '.$countSql. pg_last_error());
$countRow=pg_fetch_row($countResult);

$countTotal=$countRow[0];
 /* Lib�ration du r�sultat */ 
 pg_free_result($countResult);


// on prend en compte la pagination

/* D�claration des variables */ 
    $rowsPerPage = 15; // nombre d'entr�es � afficher par page (entries per page) 
    $countPages = ceil($countTotal/$rowsPerPage); // calcul du nombre de pages $countPages (on arrondit � l'entier sup�rieur avec la fonction ceil() ) 
 
    /* R�cup�ration du num�ro de la page courante depuis l'URL avec la m�thode GET */ 
    if(!isset($_GET['page']) || !is_numeric($_GET['page']) ) // si $_GET['page'] n'existe pas OU $_GET['page'] n'est pas un nombre (petite s�curit� suppl�mentaire) 
        $currentPage = 1; // la page courante devient 1 
    else 
    { 
        $currentPage = intval($_GET['page']); // stockage de la valeur enti�re uniquement 
        if ($currentPage < 1) $currentPage=1; // cas o� le num�ro de page est inf�rieure 1 : on affecte 1 � la page courante 
        elseif ($currentPage > $countPages) $currentPage=$countPages; //cas o� le num�ro de page est sup�rieur au nombre total de pages : on affecte le num�ro de la derni�re page � la page courante 
        else $currentPage=$currentPage; // sinon la page courante est bien celle indiqu�e dans l'URL 
    } 
 
    /* $start est la valeur de d�part du LIMIT dans notre requ�te SQL (est fonction de la page courante) */ 
    $startRow = ($currentPage * $rowsPerPage - $rowsPerPage);


// on construit la requ�te SQL pour obtenir les valeurs de la table � afficher

$tableSql='	SELECT * FROM '.$tablesDefinitions[$editTable]["table"].'
				WHERE  TRUE '.$whereClause.' 
				ORDER BY '.$tablesDefinitions[$editTable]["id_col"].'
				LIMIT '.$rowsPerPage.' OFFSET '.$startRow.'  
			';

$tableResult=pg_query($connectPPEAO,$tableSql) or die('erreur dans la requete : '.$tableSql. pg_last_error());
$tableArray=pg_fetch_all($tableResult);

// lib�ration du r�sultat
pg_free_result($tableResult);


// on pr�pare l'affichage du titre
$paginationString='';
if ($countTotal>$rowsPerPage) {
	$from=intval($startRow)+1; // +1 car les tableaux commencent � z�ro
	$to=intval($startRow)+intval($rowsPerPage);
		// si on arrive � la fin du tableau
		if ($to>$countTotal) {$to=$countTotal;}
	$paginationString=' ('.$from.'-'.$to.')';
	}

?>
<h1>votre s&eacute;lection : <?php echo($countTotal.' '.$tablesDefinitions[$editTable]["label"].$paginationString);?></h1>
<?php 
// on affiche la table

echo('<table id="la_table" border="0" cellspacing="0" cellpadding="5">');

// on affiche l'en-t�te de table
$theHeads=array_keys($tableArray[0]);
echo('<tr>');
foreach ($theHeads as $oneHead) {echo('<td class="small" style="font-weight:bold;">'.$oneHead.'</td>');}
echo('<td class="small">action</td>');
echo('</tr>');

$i=0;
foreach ($tableArray as $theRow) {
	// affiche la ligne avec un style diff�rent si c'est un rang pair ou impair 
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
// note : on termine la boucle testant si l'utilisateur a acc�s � la page demand�e

;} // end if (userHasAccess($_SESSION['user_id'],$zone))

// si l'utilisateur n'a pas acc�s ou n'est pas connect�, on affiche un message l'invitant � contacter un administrateur pour obtenir l'acc�s
else {userAccessDenied($zone);}

?>

<?php 
include $_SERVER["DOCUMENT_ROOT"].'/footer.inc';

?>
</body>
</html>
