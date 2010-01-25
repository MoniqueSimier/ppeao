<?php 

// SPECIFIC FUNCTIONS FOR PPEAO


//***************************************************************************************************
//ecrit une entree dans le journal
function logWriteTo($moduleId,$messageType,$message,$actionDo,$actionUndo,$logLevel)
// cette fonction ecrit les informations suivantes :
// $moduleId : l'id du module qui appelle la fonction (d�finie dans la table admin_log_modules)
// $messageType : le type de message (error, sql, warning, notice)
// $message : le message � �crire
// $actionDo : l'action qui est r�alis�e (syntaxe SQL dans le cas d'une op�ration sur la base)
// $actionUndo : l'action permettant d'annuler l'actionDo (syntaxe SQL "inverse" dans le cas d'une op�ration sur la base)
// $logLevel : si 0, entree de journal a stocker en production, si 1 a stocker uniquement en mode debug (variable globale $debug)

// les autres variables sont r�cup�r�es directement par la fonction :
// $timestamp : le timestamp pgsql ("YYYY-MM-DD HH:MM:SS") de l'instant o� le message est �crit (g�n�r�)
// $userId : l'id de l'utilisateur connect� qui a d�clench� le script (r�cup�r� via le cookie de session...)
// $scriptFile : le chemin du script PHP qui a appel� la fonction logWriteTo 
{
global $debug; // variable definie dans /variables.inc (0=production, 1=debug)
global $connectPPEAO; // la connexion a utiliser (on travaille avec deux bases : BD_PECHE et BD_PPEAO)
global $userId; // id de l'utilisateur connect�
global $logAutoArchiveEntriesNumber; // le nombre d'enregistrements au del� duquel on autoarchive le journal

if ($logLevel<=$debug) { // si le niveau du message ($logLevel) est inf�rieur au niveau de l'application ($debug) alors on �crit 
if (is_null($moduleId)) {$moduleId=0;} // si le moduleId n'a pas �t� d�fini, on lui assigne 0 (inconnu)

$timestamp=date('Y-m-d G:i:s'); // on assigne un UNIX timestamp

// on recupere le userId de la session, sinon on lui assigne 0 (zero)
if (isset($_SESSION['s_ppeao_user_id'])) {$userId=$_SESSION['s_ppeao_user_id'];} else {
		if (my_empty($userId))  {$userId=0;}; // if there is no $userId set, we assume it is a visitor
	} // end else

// on recupere le chemin du script actif
$scriptFile=$_SERVER['PHP_SELF'];


// avant d'�crire dans le log, on teste pour voir si il faut lancer l'autoarchivage
// on r�cup�re le nombre d'enregistrements dans la table de journal
$logCountSql='	SELECT COUNT(*) FROM admin_log
			';
$logCountResult=@pg_query($connectPPEAO,$logCountSql);
$logCountRow=@pg_fetch_row($logCountResult);

$logCountTotal=$logCountRow[0];
 /* Lib�ration du r�sultat */ 
 pg_free_result($logCountResult);
// on le compare au nombre maximum d�fini dans variables.inc
// s�curit� : si on n'a pas d�fini de valeur maxi, on utilise 1000
if (!isset($logAutoArchiveEntriesNumber) || !is_numeric($logAutoArchiveEntriesNumber) || my_empty($logAutoArchiveEntriesNumber)) {
	$logAutoArchiveEntriesNumber=1000;
}
if ($logCountTotal>=$logAutoArchiveEntriesNumber) {
	// si il est sup�rieur ou �gal, on autoarchive	
	logDelete("");	
}


// on teste si $messageType est dans la table admin_log_message_types
	$typesSql="	SELECT  count(log_message_type)
				FROM admin_log
				WHERE log_message_type='$messageType'
					";
	$typesResult = pg_query($connectPPEAO,$typesSql) or die('logWriteTo/typesResult dit - erreur dans la requete : ' . pg_last_error());
	$typesCount=pg_fetch_all($typesResult);
	if (count($typesCount)==0) {$messageType="notice";}

// on rend les chaines de carat�res "safe"
$message=addslashes($message);
$actionDo=addslashes($actionDo);
$actionUndo=addslashes($actionUndo);

$logWriteSql="	INSERT INTO admin_log (log_time,log_module_id,log_script_file,log_message,log_user_id,log_action_do,log_action_undo,log_message_type)
				VALUES ('$timestamp',$moduleId,'$scriptFile','$message',$userId,'$actionDo','$actionUndo', '$messageType')";
$logWriteResult = pg_query($connectPPEAO,$logWriteSql) or die("logWriteTo/logWriteResult dit - erreur dans la requete : $logWriteSql " . pg_last_error());
}



}



//***************************************************************************************************
// lit une section de journal
function logRead($date,$userId,$moduleId,$messageBit,$rowsNumber,$messageType,$paginate)
// cette fonction lit une section de journal, �ventuellement filtr�e
// $date : format date (YYYY-MM-DD), si renseign�, affiche uniquement les entr�es pour la date d�finie
// $userId : si renseign�, affiche uniquement les entr�es pour l'utilisateur correspondant (table admin_users)
// $moduleId : si renseign�, affiche uniquement les entr�es pour le module correspondant (table admin_log_modules)
// $messageBit : si renseign�, affiche uniquement les entr�es dont le message (log_message) contient $messageBit
// $rowsNumber : nombre d'entr�es � retourner (si 0, tout retourner)
// $messageType : type de message (error, sql, warning, notice)
// $paginate : si 'paginate', on pagine la tablea de r�sultats
// retourne $logArray, le tableau contenant les entr�es de journal s�lectionn�es 

{
global $connectPPEAO; // la connexion a utiliser (on travaille avec deux bases : BD_PECHE et BD_PPEAO)


$limit="";
$rowsPerPage=0;
$countPages=0;
$startRow=0;
$currentPage=0;
// on prepare un tableau contenant les elements a utiliser comme filtre
$filter=array();
	// on verifie que $date contient bien une date
	$dateExploded=explode("-",$date);
	if (@checkdate($dateExploded[1],$dateExploded[2],$dateExploded[0]))
		{
		$filter["date"]=' l.log_time LIKE \''.$date.'%\' ';
		}
	// on verifie que $userId contient bien un utilisateur inscrit dans la table admin_users
	if (is_int($userId)) {
		$userCheckSql="	SELECT  count(user_id)
						FROM admin_users
						WHERE user_id=$userId
						";
		$userCheckResult = pg_query($connectPPEAO,$userCheckSql) or die('logRead dit - erreur dans la requete : ' . pg_last_error());
		$userCount=pg_fetch_array($userCheckResult);
		if ($userCount[0]!=0) {$filter["userId"]=" l.user_id=".$userId."";}
	} // fin if (is_int($userId))
	
	// on verifie que $moduleId contient bien un module inscrit dans la table admin_log_modules
	if (is_int($moduleId)) {
		$moduleCheckSql="	SELECT  count(module_id)
							FROM admin_log_modules
							WHERE module_id=$moduleId
						";
		$moduleCheckResult = pg_query($connectPPEAO,$moduleCheckSql) or die('logRead erreur dans la requete : ' . pg_last_error());
		$moduleCount=pg_fetch_array($moduleCheckResult);
		if ($moduleCount[0]!=0) {$filter["moduleId"]=" l.module_id=".$moduleId." ";}
	} // fin if (is_int($moduleId))
	
	// on verifie que $messageBit n'est pas vide
	if (!my_empty($messageBit)) {$filter["messageBit"]=" l.log_message LIKE '%".$messageBit."%' ";}
	
	// on v�rifie que $messageType n'est pas vide et si c'est une valeur qui existe;
	if (!my_empty($messageType)) {
		// on teste si $messageType est dans la table admin_log_message_types
		$typesSql="	SELECT  count(message_type)
					FROM admin_log_message_types
					WHERE message_type=$messageType
					";
			$typesResult = pg_query($connectPPEAO,$typesSql) or die('logRead dit - erreur dans la requete : ' . pg_last_error());
			$typesCount=pg_fetch_all($typesResult);
			if (count($typesCount)==0) {$filter["messageType"]=" l.log_message_type LIKE '".$messageType."' ";}
		}
	
	
	// on construit le segment sql pour le filtre
	if (count($filter)!=0) {
	$filterSql=" AND (".arrayToList($filter," AND ","").")";}
		else {$filterSql="";}
	// si $rowsNumber n'est pas nul, on limite le nombre de lignes retourn�es
	if (!is_null($rowsNumber) && $rowsNumber!=0 ) {$limit.=" LIMIT ".$rowsNumber." ";}

	// sinon, on pagine
	else { 
		if ($paginate=='paginate') {

		// on construit la requ�te SQL pour obtenir le nombre total de valeurs de la table � afficher
		$countSql="	SELECT COUNT(*) FROM admin_log l, admin_users u, admin_log_modules lm
					WHERE (l.log_module_id=lm.module_id) AND (l.log_user_id=u.user_id) $filterSql
					";
		$countResult=pg_query($connectPPEAO,$countSql) or die('erreur dans la requete : '.$countSql. pg_last_error());
		$countRow=pg_fetch_row($countResult);
		$countTotal=$countRow[0];
		 /* Lib�ration du r�sultat */ 
		 pg_free_result($countResult);	

		/* D�claration des variables */ 
		    $rowsPerPage = 50; // nombre d'entr�es � afficher par page (entries per page) 
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
		
		$limit=' LIMIT '.$rowsPerPage.' OFFSET '.$startRow;}
	}


// on fait la requete pour recuperer les entrees du journal correspondantes
$logReadSql="	SELECT l.log_time, l.log_module_id, l.log_script_file, l.log_message, l.log_user_id, u.user_name, l.log_module_id, lm.module_name, l.log_action_do, l.log_action_undo, l.log_message_type, u.user_email
 				FROM admin_log l, admin_users u, admin_log_modules lm
			WHERE (l.log_module_id=lm.module_id) AND (l.log_user_id=u.user_id) $filterSql
			ORDER BY l.log_time	DESC				
			$limit	";
			
$logReadResult = pg_query($connectPPEAO,$logReadSql) or die('logRead dit - erreur dans la requete : ' . pg_last_error());
$logEntriesArray=pg_fetch_all($logReadResult);


$logArray=array("pagination"=>array("rowsPerPage"=>$rowsPerPage,"startRow"=>$startRow,"countPages"=>$countPages,"currentPage"=>$currentPage),"logRows"=>$logEntriesArray);

return $logArray;
}

//***************************************************************************************************
// affiche sous forme de table formatt�e une section de journal
function logTable($logArray,$format)
// cette fonction g�n�re une table pour l'affichage d'une section du journal
// $logArray : le tableau contenant les entr�es de journal � tabuler (retourn� par la fonction logRead)
// $format : "html" pour l'affichage et "csv" pour l'exportation
// $paginate : si 'paginate', on pagine la tablea de r�sultats
// returns $logTable : la table HTML g�n�r�e par la fonction, pr�te � �tre affich�e
{

$logTable="";
global $debug; // si $debug=1, alors on affiche des infos de d�bug dans le log (comme le script php)

if (!my_empty($logArray["logRows"])) { // si le log n'est pas vide

switch ($format) {

	// we generate for a CSV file
	case "csv":
	$logTable='"date";"utilisateur";"module";"message";"action";"annulation";"script"
';
	//on parcourt le tableau du journal pour generer une ligne  par entree
	foreach ($logArray["logRows"] as $logRow) {
			$logTable.=$logRow["log_time"].';"'.$logRow["user_name"].'";"'.$logRow["module_name"].'";"'.$logRow["log_message"].'";"'.$logRow["log_action_do"].'";"'.$logRow["log_action_undo"].'";"'.$logRow["log_script_file"].'";"'.$logRow["log_message_type"].'"
';
		} // end foreach $logArray
	;
	break;
	
	
	// default case : we generate an HTML table
	default:
	$logTable.='<table id="logTable" cellpadding="0" cellspacing="0" border="0">';
	// les en-tete de la table
	$logTable.='<tr class="logTableHeaderRow">';
	$logTable.='<td class="logTableTime">date</td><td class="logTableUser">utilisateur</td><td class="logTablemodule">module</td><td class="logTableMessage">message</td><td class="logTableDo">action</td><td class="logTableUndo">annulation</td>';
	if ($debug) {$logTable.='<td class="logTableScript">script</td><td>type message</td>';}
	$logTable.='</tr>';
	
	$j=0; // counter used to differentiate odd and even rows
	//on parcourt le tableau du journal pour generer un <tr>  par entree
	foreach ($logArray["logRows"] as $logRow) {
		if ( $j&1 ) {$rowStyle='logTableRowOdd';} else {$rowStyle='logTableRowEven';}
		$logTable.='<tr class="'.$rowStyle.'">';
		
		// si on a un email pour l'utilisateur, on transforme son nom en lien mailto:
		// on inscrit la connexion dans le journal
		if (!my_empty($logRow['user_email'])) {$theName='<a href="mailto:'.$logRow['user_email'].'">'.$logRow["user_name"].'</a>';} else {$theName=$logRow["user_name"];}
		
		$logTable.='<td class="logTableTime">'.$logRow["log_time"].'</td><td class="logTableUser">'.$theName.'</td><td class="logTableModule">'.$logRow["module_name"].'</td><td class="logTableMessage">'.$logRow["log_message"].'</td><td class="logTableDo">'.$logRow["log_action_do"].'</td><td class="logTableUndo">'.$logRow["log_action_undo"].'</td>';
		if ($debug) {$logTable.='<td class="logTableScript">'.$logRow["log_script_file"].'</td><td>'.$logRow["log_message_type"].'</td>';}
		$logTable.='</tr>';
		$j++;
	
	} // end foreach $logArray["logRow"]
	
	// si on doit paginer, on ins�re la pagination
	if (!my_empty($logArray["pagination"])) {
		
			// on ins�re la pagination
		$logTable.='<tr><td colspan="8">';
		$logTable.= paginate($_SERVER['PHP_SELF'].'?'.removeQueryStringParam($_SERVER['QUERY_STRING'],'page'), '&amp;page=', $logArray["pagination"]["countPages"], $logArray["pagination"]["currentPage"]);
		$logTable.='</td></tr>';
		
	}
	
	
	$logTable.= '</table>';

	
	;
	break;
}
} // fin de (count($logArray!=0))

else {
	$logTable="le journal est vide";
}

return $logTable;
}

//***************************************************************************************************
// r�cupere sous forme de tableau long une section de journal
function logDisplayFull($date,$userId,$moduleId,$messageBit,$messageType,$paginate)
// cette fonction cr�e un tableau contenant une section de journal, �ventuellement filtr�e
// pour affichage complet avec pagination, champs de tri/filtres etc.
// $date : format date (YYYY-MM-DD), si renseign�, affiche uniquement les entr�es pour la date d�finie
// $userId : si renseign�, affiche uniquement les entr�es pour l'utilisateur correspondant (table admin_users)
// $moduleId : si renseign�, affiche uniquement les entr�es pour le module correspondant (table admin_log_modules)
// $messageBit : si renseign�, affiche uniquement les entr�es dont le message (log_message) contient $messageBit
// $paginate : si 'paginate', on pagine la tablea de r�sultats
// $messageType : type de message (error, sql, warning, notice)
// cette fonction appelle la fonction logRead() pour extraire les entr�es de journal ad�quates
// et la fonction logTable() pour g�n�rer la table html de journal

{

// archivage et exportation du journal
echo('<script src="/js/journal.js" type="text/javascript"  charset="iso-8859-15"></script>');

// on recupere les entrees de journal souhaitees


$logArray=logRead($date,$userId,$moduleId,$messageBit,0,$messageType,$paginate);

$logBlock='<div id="logMessage"></div>';
$logBlock.='<div id="logTableDiv">';
$logBlock.='<a href="javascript:deleteLog();">effacer le journal</a> (une version en sera archiv&eacute;e sur le serveur...)';
$logBlock.=logTable($logArray,'',$paginate);
$logBlock.='</div>'; // end div id="logTableDiv"

return $logBlock;
}

//***************************************************************************************************
// r�cupere sous forme de tableau court une section de journal
function logDisplayShort($date,$userId,$moduleId,$messageBit,$rowsNumber,$messageType)
// cette fonction cr�e un tableau contenant une section de journal, �ventuellement filtr�e
// pour affichage court, sans pagination, champs de tri/filtres etc.
// $date : format date (YYYY-MM-DD), si renseign�, affiche uniquement les entr�es pour la date d�finie
// $userId : si renseign�, affiche uniquement les entr�es pour l'utilisateur correspondant (table admin_users)
// $moduleId : si renseign�, affiche uniquement les entr�es pour le module correspondant (table admin_log_modules)
// $messageBit : si renseign�, affiche uniquement les entr�es dont le message (log_message) contient $messageBit
// $rowsNumber : nombre d'entr�es � retourner (si 0, tout retourner)
// $messageType : type de message (error, sql, warning, notice)
// cette fonction appelle la fonction logRead() pour extraire les entr�es de journal ad�quates
// et la fonction logTable() pour g�n�rer la table html de journal

{

// on recupere les entrees de journal souhaitees
$logArray=logRead($date,$userId,$moduleId,$messageBit,$rowsNumber,$messageType,'');
// on stocke le tableau dans une variable
$logBlock='<div id="logTableDiv">';

// le titre
echo('<h2>Activit&eacute; r&eacute;cente</h2>');
	
$logBlock.=logTable($logArray,'','');

$logBlock.='</div>'; // end div id="logTableDiv"
$logBlock.='<div id="logTableDivLink"><a href="/journal.php" alt="consulter le journal" title="consulter le journal">consulter le journal</a></div>';

return $logBlock;
}


//***************************************************************************************************
//archive le journal au format texte compress�
function logArchive($archivePath)
// cette fonction archive le journal sous forme de fichier texte compress�
// $archivePath : le chemin du dossier dans lequel archiver le log
// $archiveMessage : le message retourn� une fois l'archivage termin�
{

	// si $archivePath est vide, on utilise le chemin par defaut d�fini dans /variables.inc ($logArchivePath)
	global $logArchivePath; 
	$error="";
	if (empty($archivePath)) {$archivePath=$logArchivePath;}
	// le nom de l'archive qui sera creee
	$theArchiveName="journal_archive_".date("Y-m-d_G-i-s");
	// le chemin de l'archive qui sera creee
	$theArchivePath=$_SERVER["DOCUMENT_ROOT"].$archivePath.$theArchiveName.".csv.gz";

	// on cree le fichier .gz
	if ($theFile=@gzopen($theArchivePath,"w")) {

	// on genere le contenu du fichier
	$theContent=logTable(logRead("","","","","","",""),'csv');

	// on �crit le contenu du journal dans le fichier (csv)
	if (gzwrite($theFile,$theContent)) {$success=1;} else {$success=0;$error="gzwrite";}
	// on ferme le fichier
	gzclose($theFile);

	// le chemin pour telecharger le fichier archive cree
	$downloadPath=$archivePath.$theArchiveName.".csv.gz";} else {$success=0;$error="gzopen";}
	
	
$return=array("success"=>$success,"error"=>$error,"downloadPath"=>$downloadPath);
return $return;
}


//***************************************************************************************************
//affiche la liste des journaux archiv�s sur le serveur
function logArchivesList($archivePath)
// $archivePath : le chemin du dossier dans sont archiv�s les journaux
// $archiveMessage : le message retourn� une fois l'archivage termin�
{

	// si $archivePath est vide, on utilise le chemin par defaut d�fini dans /variables.inc ($logArchivePath)
	global $logArchivePath; 
	if (empty($archivePath)) {$archivePath=$_SERVER["DOCUMENT_ROOT"].$logArchivePath;}

	// on r�cup�re la liste des fichiers dans le dossier d'archivage
	
	if ($handle = @opendir($archivePath)) {

	    /* Ceci est la fa�on correcte de traverser un dossier. */
	    while (false !== ($file = readdir($handle))) {
	        // on filtre les fichiers autres que .csv.gz
			$thePos=strpos($file,".csv.gz")+7;
			if ($thePos==strlen($file)) {
				$logArchiveFiles[]=$file;}
	    		}
	    closedir($handle);
	}
				

	
	if (!empty($logArchiveFiles)) {
		//on trie le tableau pour avoir les fichiers les plus r�cents en haut
		rsort($logArchiveFiles);
		$archiveList='<div id="archives_list">';
		$archiveList.='<p><a id="showHideArchives">afficher la liste des archives</a></p>';
		$archiveList.='<div id="archives_list_div">';
			$archiveList.='<p>cliquez sur une des archives pour la t&eacute;l&eacute;charger</p>';
			$archiveList.='<p id="efface_log"><a href="javascript:deleteArchivedLogs();">effacer tous les fichiers archiv�s du serveur</a><div id="efface_log_message"></div></p>';
			$archiveList.='<ul>';
			foreach ($logArchiveFiles as $file) {
				$archiveList.='<li><a href="'.$logArchivePath.$file.'">'.$file.'</a></li>';
			}
		$archiveList.='</ul></div>';
		$archiveList.='</div>';		
	}
	
	

	
return $archiveList;
}

//***************************************************************************************************
//efface le journal
function logDelete($archivePath)
// cette fonction archive le journal puis vide la table de journal
// $errorMessage : le message retourn� une fois l'effacement termin�

{
	global $connectPPEAO;
	// si $archivePath est vide, on utilise le chemin par defaut d�fini dans /variables.inc ($logArchivePath)
	global $logArchivePath; 
	$errorMessage="";
	if (empty($archivePath)) {$archivePath=$logArchivePath;}
	
	
	
$archived=logArchive($archivePath);


$success=$archived["success"];
$error=$archived["error"];


if ($success==1) { // si l'archivage s'est passe correctement, on efface la table
	// on efface la table de log puisque l'archivage s'est bien passe
	$logDeleteSql="DELETE FROM admin_log";
	if (pg_query($connectPPEAO,$logDeleteSql)) {$success=1;} else {$success=0;$error="sql";}
	}

if ($success==1) { // si tout a bien fonctionne, on indique a l'utilisateur l'URL pour recuperer l'archive

$archiveUrl=$archived["downloadPath"];	
	
echo('journal effac&eacute; - <a href="'.$archiveUrl.'" alt="t&eacute;l&eacute;charger la version archiv&eacute;e" title="t&eacute;l&eacute;charger la version archiv&eacute;e">t&eacute;l&eacute;charger la version archiv&eacute;e</a>');


logWriteTo(4,"notice","journal effac&eacute;","","",0);
}
else {
	
	switch ($error) {
	case 'gzwrite' : $errorMessage="impossible d'c&eacute;crire l'archive sur le serveur, effacement annulc&eacute;.";
	case 'sql' : $errorMessage="impossible de vider la table de journal, effacement annulc&eacute;.";
	case 'gzopen' : $errorMessage="impossible de crc&eacute;er le fichier archive sur le serveur, effacement annulc&eacute;.";
	
	}

logWriteTo(4,"error",$errorMessage,"","",0);
}

return $errorMessage;
}
//***************************************************************************************************
//retourne la liste des groupes auxquels l'utilisateur $user_id appartient
function nettoieLogExport(){
// cette fonction lance la suppression des fichiers de log d'extraction ou tout autre

	// Suppression des logs.
	$hier  = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	VideRepData($_SERVER["DOCUMENT_ROOT"]."/log", $hier,"log");
	
	// Suppression des fichiers d'extraction
	VideRepData($_SERVER["DOCUMENT_ROOT"]."/work/extraction/", $hier,"txt,zip");	
	
}
//***************************************************************************************************
// vide un repertoire de son contenu selon la date des fichiers
function VideRepData($dir, $dateLimite,$listeExtension) {
	logWriteTo(4,"notice","Suppression des fichiers ".$listeExtension." date de dernier acces inferieure a ".date("d-F-Y",$dateLimite)." pour ".$dir,"","",0);
    if(!$dh = @opendir($dir)) return;
    while (false !== ($obj = readdir($dh))) {
        if($obj=='.' || $obj=='..') continue;
		$path_parts = pathinfo($dir.'/'.$obj);
		$extFic = $path_parts['extension'];
		if (strpos($listeExtension,$extFic) === false ) {
		} else {
			if (fileatime($dir.'/'.$obj) < $dateLimite) { 
				unlink($dir.'/'.$obj);			
			} 
		}
    }
    closedir($dh);
}

//***************************************************************************************************
//retourne la liste des groupes auxquels l'utilisateur $user_id appartient
function userGetGroups($user_id) {
// cette fonction collecte liste des groupes auxquels l'utilisateur $user_id appartient
// $groupsArray : tableau contenant la liste des groupes auxquels l'utilisateur $user_id appartient

global $connectPPEAO;


$groupsSql='	SELECT DISTINCT group_id
				FROM admin_j_user_group jug
				WHERE jug.user_id='.$user_id.'
			';
$groupsResult = pg_query($connectPPEAO,$groupsSql) or die('erreur dans la requete : '.$groupsSql. pg_last_error());
$groupsArray=pg_fetch_all($groupsResult);

// si l'utilisateur n'est pas d�fini comme appartenant a un groupe, on considere que c'est un visiteur (groupe 0)
if (empty($groupsArray)) {$groupsArray=array("group_id"=>"0");}

foreach($groupsArray as $group)
	{$groupsArray2[]=$group['group_id'];}


return $groupsArray2;

}


//***************************************************************************************************
//retourne la liste des zones auxquelles l'utilisateur $user_id a acc�s
function userGetAuthorizedZones($user_id) {
// cette fonction collecte les zones auxquelles l'utilisateur et le(s) groupe(s) au(x)quel(s) il appartient ont acc�s
// $zonesArray : le tableau contenant la liste des zones auxquelles l'utilisateur a acc�s

global $connectPPEAO;

// si aucun utilisateur n'est indiqu�, on consid�re que on a affaire � un visiteur
if (my_empty($user_id)) {$user_id=0;}

// on collecte la liste des zones auxquelles l'utilisateur a acc�s
$zonesArray1=array();
$zonesSql1='SELECT DISTINCT zone_id
			FROM admin_j_user_zone juz
			WHERE juz.user_id='.$user_id.'
			';
$zonesResult1 = pg_query($connectPPEAO,$zonesSql1) or die('erreur dans la requete : '.$zonesSql1. pg_last_error());
while($data1=pg_fetch_array($zonesResult1)) {$zonesArray1[]=$data1['zone_id'];}
// si aucun r�sultat, on consid�re que l'utilisateur n'a acc�s qu'aux zones publiques
if (my_empty($zonesArray1)) {$zonesArray1=array();}


// on collecte la liste des groupes auxquels appartient l'utilisateur
$groupsArray=userGetGroups($user_id);


// on collecte la liste des zones auxquelles les groupes de l'utilisateur ont acc�s
$zonesArray2=array();
$zonesSql2='SELECT DISTINCT zone_id
			FROM admin_j_group_zone jgz
			WHERE jgz.group_id IN ('.implode(",",$groupsArray).')
			';
$zonesResult2 = pg_query($connectPPEAO,$zonesSql2) or die('erreur dans la requete : '.$zonesSql2. pg_last_error());
while($data2=pg_fetch_array($zonesResult2)) {$zonesArray2[]=$data2['zone_id'];}
// si aucun r�sultat, on consid�re que l'utilisateur n'a acc�s qu'aux zones publiques
if (is_null($zonesArray2)) {$zonesArray2=array();}



// on fusionne les deux listes en �liminant les valeurs dupliqu�es
$zonesArray=array_unique(array_merge($zonesArray1,$zonesArray2));

return $zonesArray;

}

//***************************************************************************************************
//permet de v�rifier si un utilisateur a acc�s � une zone
function userHasAccess($user_id,$zone_id) {
// $user_id : id de l'utilisateur
// $zone_id : l'id de la zone � tester
// on teste � quelle zone l'utilisateur a acc�s
$access=false;
if (isset($_SESSION['s_ppeao_login_status']) && $_SESSION['s_ppeao_login_status']=='good') {
	// on r�cup�re la liste des zones auxquelles l'utilisateur a acc�s
	$lesZones=userGetAuthorizedZones($user_id);
	$_SESSION["zones_autorisees"]=$lesZones;	
	
	// on teste si la zone de la page appel�e fait partie de la liste des zones autoris�es pour l'utilisateur
	// si la zone est 0 (publique) ou si l'utilisateur a acc�s � la zone 9999 ("toutes zones"), on donne l'acc�s
	// si oui, alors on ex�cute le code de la page
	if (in_array($zone_id,$lesZones) || is_null($zone_id) || in_array(9999,$lesZones)) {$access=true;}
}// end if ($_SESSION['s_ppeao_login_status']=='good')
// si la zone est "vide" on consid�re que c'est une zone publique
if ($zone_id=='') {$access=true;}

return $access;
}

//***************************************************************************************************
//affiche un message ad�quat lorsque un utilisateur n'a pas acc�s � une zone
function userAccessDenied($zone_id) {
// $zone_id : id de la zone concern�e

global $connectPPEAO;

// si on n'a pas sp�cifi� de zone, on consid�re que c'est la zone publique
if ($zone_id=='') {$zone_id=0;}

// on r�cup�re le nom de la zone concern�e
$zoneSql='SELECT DISTINCT zone_name
			FROM admin_zones
			WHERE zone_id='.$zone_id.'
			';
$zoneResult = pg_query($connectPPEAO,$zoneSql) or die('erreur dans la requete : '.$zonesSql. pg_last_error());
$zoneArray=pg_fetch_array($zoneResult);

$zoneName=$zoneArray['zone_name'];

// on teste le statut de connexion
if (isset($_SESSION['s_ppeao_login_status'])) {
	switch ($_SESSION['s_ppeao_login_status']) {
		case 'good' : $message='<div id="access_denied">Vous n\'avez pas les droits d\'acc&egrave;s � la section "'.$zoneName.'". <br />Contactez un administrateur si vous souhaitez y acc&eacute;der.</div>';
		break;
		default : $message='<div id="access_denied">Vous devez vous connecter pour acc&eacute;der &agrave; la section "'.$zoneName.'".</div>';
		break;
	} // end switch
} else {
	// on personnalise le message selon la section
	switch ($zoneName) {
case "extraction": $message="En fonction des autorisations qui vous sont accord&eacute;es (<a href=\"/contact.php\">demander un compte d&#x27;acc&egrave;s</a>), il est possible de consulter et d&rsquo;extraire des informations issues des p&ecirc;ches scientifiques, des p&ecirc;ches artisanales ou des donn&eacute;es de statistiques de p&ecirc;che. 
Le r&eacute;sultat de cette consultation pourra &ecirc;tre export&eacute; vers votre poste de travail sous forme de fichiers texte compact&eacute;s.
";
break;
default:$message='<div id="access_denied">Vous devez vous connecter pour acc&eacute;der &agrave; la section "'.$zoneName.'".</div>';
break;
}
}

echo($message);

}

//***************************************************************************************************
// trie un tableau selon un autre tableau
function sortArrayByArray($array,$orderArray) {
//$array : le tableau associatif a trier p.ex array("toto"=>"1","tutu"=>"2","tata"=>"3")
//$orderArray: le tableau contenant l'ordre des colonnes  p.ex array("tata","titi","toto","tutu")
// retourne array("tata"=>3,"toto"=>1,"tutu"=>2)
	$ordered = array();
    foreach($orderArray as $key) {
        if(array_key_exists($key,$array)) {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
        }
    }
    return $ordered + $array;
}

?>