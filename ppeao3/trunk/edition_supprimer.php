<?php 
// Cr�� par Olivier ROUX, 10-08-2009
// code commun � toutes les pages (demarrage de session, doctype etc.)
include $_SERVER["DOCUMENT_ROOT"].'/top.inc';
// definit a quelle section appartient la page
$section="gerer";
$subsection="donnees";

$zone=1; // zone edition (voir table admin_zones)

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	
<?php 
	// les balises head communes  toutes les pages
	include $_SERVER["DOCUMENT_ROOT"].'/head.inc';
?>
<?php
// on d�termine le type d'unites a supprimer en fonction du parametre passe en url

$domaine=$_GET["domaine"];
switch ($domaine) {
	case "art": $domaine_unite="p&eacute;riode d&#x27;enqu&ecirc;te"; $domaine_unites="p&eacute;riodes d&#x27;enqu&ecirc;te";
	break;
	default: $domaine_unite="campagne";$domaine_unites="campagnes";$domaine="exp";
	break;
}

?>
	<title>ppeao::g&eacute;rer::donn�es::supprimer une <?php echo($domaine_unite); ?></title>
	
<script src="/js/edition.js" type="text/javascript"  charset="iso-8859-15"></script>
<script src="/js/suppression.js" type="text/javascript"  charset="iso-8859-15"></script>

</head>

<body>

<?php 
// le menu horizontal
include $_SERVER["DOCUMENT_ROOT"].'/top_nav.inc';
?>

<div id="main_container" class="edition">
<h1>G&eacute;rer les tables de donn�es : supprimer une <?php echo($domaine_unite); ?></h1>
<!-- �dition des tables de r�f�rence -->
<?php

// on teste � quelle zone l'utilisateur a acc�s
if (userHasAccess($_SESSION['s_ppeao_user_id'],$zone)) {
?>


<?php

include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_config.inc';
include $_SERVER["DOCUMENT_ROOT"].'/edition/edition_functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/suppression/suppression_functions.php';

?>


<?php
// on determine si on est en mode selection ou en mode affichage des unites selectionnees
$mode=$_GET["mode"];
if (empty($mode)) {$mode="selection";}

// on determine si on a selectionne des valeurs des criteres de selection dans l'url
// et on stocke ces valeurs dans des variables portant le nom du critere (ex.: pays)
foreach ($suppression_cascades[$domaine] as $critere) {
	if (array_key_exists($critere,$_GET)) {
		$$critere='\''.arrayToList($_GET[$critere],'\',\'','\'');}
}


// on construit le selecteur : pays>systeme>annee (date debut)
echo('<div id="unite_selecteur">');
echo('<h2>s�lection des '.$domaine_unites.'</h2>');

	echo('<div id="selector_content">');
	echo('<form id="selector_form">');
	
	// on prepare le premier <select> et on insere les conteneurs vides pour les suivants
	$i=1;
	foreach ($suppression_cascades[$domaine] as $critere) {
	$theSelectCode.='<div id="level_'.$i.'" class="level_div">';
	// si c'est le premier select
	// ou si on a des valeurs passees dans l'url pour les autres <select>, on les affiche

	if ($i==1 || @array_key_exists($suppression_cascades[$domaine][$i-1],$_GET)) {
	if ($i>1) {$previousSelection='\''.arrayToList($_GET[$suppression_cascades[$domaine][$i-2]],'\',\'','\'');}
	$theSelectCode.=iconv('UTF-8','ISO-8859-15',insertDeleteSelect($domaine,$i,$previousSelection));
	}

	$theSelectCode.="</div>";
	$i++;
} // end foreach $suppression_cascades[$domaine]

echo($theSelectCode);
	
	
	echo('</form>'); //end form unite_selecteur_form
	echo('</div>'); // end div unite_selecteur_contenu

echo('</div>'); // end div unite_selecteur

echo('<br class="clear" />');

// le lien permettant de d�clencher l'affichage des campagnes/enquetes, mis a jour via ajax a chaque selection

// on calcule le nombre total de campagnes/enquetes
// pour les campagnes
if ($domaine=='exp') {
	// on compte le nombre de camapgnes correspondant a la selection
	$total=countMatchingUnits($domaine);
	// si il n'y a pas de r�sultats
	if ($total==0) {
		echo('<div id="affiche_unites">il n&#x27;existe aucune campagne dans la base de donn&eacute;es</div>');
		}
	else
	 {
		echo('<div id="affiche_unites"><a href="/edition_supprimer.php?'.$_SERVER["QUERY_STRING"].'&mode=liste" id="affiche_unites_lien">afficher '.$total.' campagne(s) correspondante(s)</a></div>');
		}
	;} // end if domaine=exp
// pour les periodes d'enquete
if ($domaine=='art') {

	;} // end if domaine=art
	
// fin de la construction du selecteur

?>

<?php
// on affiche maintenant la table de resultats correspondants a la requete, si on est en mode=liste
if ($mode=='liste') {


// on construit la requ�te SQL pour obtenir les valeurs de la table � afficher si il y en a
if ($total!=0) {
	
// on prend en compte la pagination
/* D�claration des variables */ 
    $rowsPerPage = 15; // nombre d'entr�es � afficher par page (entries per page) 
    $countPages = ceil($total/$rowsPerPage); // calcul du nombre de pages $countPages (on arrondit � l'entier sup�rieur avec la fonction ceil() ) 
 
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

if ($domaine=='exp') {

$tableSql="SELECT DISTINCT c.id, c.numero_campagne, c.date_debut, c.date_fin, c.libelle as campagne, s.libelle as systeme, lower(s.libelle) as lower_systeme, p.nom as pays, lower(p.nom) as lower_pays FROM exp_campagne c, ref_systeme s, ref_pays p WHERE TRUE ";
if (!empty($pays)) {
	$tableSql.=" AND c.ref_systeme_id IN (
	SELECT DISTINCT s.id FROM ref_systeme s WHERE s.ref_pays_id IN ($pays))";}
if (!empty($systeme)) {
	$tableSql.=" AND c.ref_systeme_id IN ($systeme)";}
if (!empty($annee_debut)) {
	$tableSql.=" AND EXTRACT(YEAR FROM c.date_debut) in ($annee_debut)";}
$tableSql.=" AND c.ref_systeme_id=s.id AND s.ref_pays_id=p.id";
$tableSql.=" ORDER BY lower_pays,lower_systeme,date_debut, date_fin";
// la pagination
$tableSql.='	LIMIT '.$rowsPerPage.' OFFSET '.$startRow;

//debug echo('pays='.$pays.'--'.'systeme='.$systeme.'--'.'debut='.$annee_debut.'--');echo($tableSql);

$tableResult=pg_query($connectPPEAO,$tableSql) or die('erreur dans la requete : '.$tableSql. pg_last_error());
$tableArray=pg_fetch_all($tableResult);
//debug echo('<pre>');print_r($tableArray);echo('</pre>');
// lib�ration du r�sultat
pg_free_result($tableResult);

// on affiche la table de resultats
echo ('<div id="supprimer_container">');

echo('<table id="la_table" border="0" cellspacing="0" cellpadding="0">');
// la ligne d'en-tetes
echo('<tr id="the_headers">');
echo('<td class="small">&nbsp;</td><td class="small">pays</td><td class="small">systeme</td><td class="small">campagne</td><td class="small">n&ordm;</td><td class="small">debut</td><td class="small">fin</td><td class="small">coups de p&ecirc;che</td>');
echo('</tr>');

// les donnees
$i=0;
foreach ($tableArray as $theRow) {
	// affiche la ligne avec un style diff�rent si c'est un rang pair ou impair 
	if ( $i&1 ) {$rowStyle='edit_row_odd';} else {$rowStyle='edit_row_even';}
	// on recupere le nombre de coups de peche concernes
	$coup_sql='SELECT count(id) as coups FROM exp_coup_peche cp WHERE cp.exp_campagne_id='.$theRow["id"];
	$coup_result=pg_query($connectPPEAO,$coup_sql) or die('erreur dans la requete : '.$coup_sql. pg_last_error());
	$coup_array=pg_fetch_all($coup_result);
	$coups=$coup_array[0]["coups"];
	pg_free_result($coup_result);

	
	
	echo('<tr class="'.$rowStyle.'">');
	echo('<td class="small"><a href="#" onclick="javascript:modalDialogDeleteUnite(1,\''.$domaine.'\',\''.$theRow["id"].'\')" class="small link_button">supprimer</a></td><td class="small">'.$theRow["pays"].'</td><td class="small">'.$theRow["systeme"].'</td><td class="small">'.$theRow["campagne"].'</td><td class="small">'.$theRow["numero_campagne"].'</td><td class="small">'.$theRow["date_debut"].'</td><td class="small">'.$theRow["date_fin"].'</td><td class="small">'.$coups.'</td>');
	echo('</tr>');
	$i++;
	} //fin foreach $tableArray
echo('<tr><td colspan="8" class="pagination_td">');
// on affiche la pagination
echo paginate($_SERVER['PHP_SELF'].'?'.removeQueryStringParam($_SERVER['QUERY_STRING'],'page'), '&amp;page=', $countPages, $currentPage);
echo('</td></tr>');
echo('</table>'); // fin de la table de resultats

} // fin de if total!=0
} // fin de if ($domaine=='exp') 


} // end if $mode==liste

echo('</div>');
// fin de l'affichage des resultats
?>


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
