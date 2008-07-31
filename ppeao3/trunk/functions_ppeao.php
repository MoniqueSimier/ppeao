<?

// SPECIFIC FUNCTIONS FOR PPEAO


//***************************************************************************************************
//ecrit une entree dans le journal
function logWriteTo($moduleId,$messageType,$message,$actionDo,$actionUndo,$logLevel)
// cette fonction ecrit les informations suivantes :
// $moduleId : l'id du module qui appelle la fonction (définie dans la table admin_log_modules)
// $messageType : le type de message (error, sql, warning, notice)
// $message : le message à écrire
// $actionDo : l'action qui est réalisée (syntaxe SQL dans le cas d'une opération sur la base)
// $actionUndo : l'action permettant d'annuler l'actionDo (syntaxe SQL "inverse" dans le cas d'une opération sur la base)
// $logLevel : si 0, entree de journal a stocker en production, si 1 a stocker uniquement en mode debug (variable globale $debug)

// les autres variables sont récupérées directement par la fonction :
// $timestamp : le timestamp pgsql ("YYYY-MM-DD HH:MM:SS") de l'instant où le message est écrit (généré)
// $userId : l'id de l'utilisateur connecté qui a déclenché le script (récupéré via le cookie de session...)
// $scriptFile : le chemin du script PHP qui a appelé la fonction logWriteTo 
{
global $debug; // variable definie dans /variables.inc (0=production, 1=debug)
global $connectPPEAO; // la connexion a utiliser (on travaille avec deux bases : BD_PECHE et BD_PPEAO)

if ($logLevel<=$debug) { // si le niveau du message ($logLevel) est inférieur au niveau de l'application ($debug) alors on écrit 
if (is_null($moduleId)) {$moduleId=0;} // si le moduleId n'a pas été défini, on lui assigne 0 (inconnu)

$timestamp=date('Y-m-d G:i:s'); // on assigne un UNIX timestamp

// on recupere le userId de la session, sinon on lui assigne 0 (zero)
if (isset($_SESSION['s_ppeao_user_id'])) {$userId=$_SESSION['s_ppeao_user_id'];} else {$userId=0;}

// on recupere le chemin du script actif
$scriptFile=$_SERVER['PHP_SELF'];

// on teste si $messageType est dans la table admin_log_message_types
	$typesSql="	SELECT  count(log_message_type)
				FROM admin_log
				WHERE log_message_type='$messageType'
					";
	$typesResult = pg_query($connectPPEAO,$typesSql) or die('logWriteTo/typesResult dit - erreur dans la requete : ' . pg_last_error());
	$typesCount=pg_fetch_all($typesResult);
	if (count($typesCount)==0) {$messageType="notice";}

// on rend les chaines de caratères "safe"
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
function logRead($date,$userId,$moduleId,$messageBit,$rowsNumber,$messageType)
// cette fonction lit une section de journal, éventuellement filtrée
// $date : format date (YYYY-MM-DD), si renseigné, affiche uniquement les entrées pour la date définie
// $userId : si renseigné, affiche uniquement les entrées pour l'utilisateur correspondant (table admin_users)
// $moduleId : si renseigné, affiche uniquement les entrées pour le module correspondant (table admin_log_modules)
// $messageBit : si renseigné, affiche uniquement les entrées dont le message (log_message) contient $messageBit
// $rowsNumber : nombre d'entrées à retourner (si 0, tout retourner)
// $messageType : type de message (error, sql, warning, notice)
// retourne $logArray, le tableau contenant les entrées de journal sélectionnées 

{
global $connectPPEAO; // la connexion a utiliser (on travaille avec deux bases : BD_PECHE et BD_PPEAO)

// on prepare un tableau contenant les elements a utiliser comme filtre
$filter=array();
	// on verifie que $date contient bien une date
	$dateExploded=explode("-",$date);
	//debug print_r($dateExploded);
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
	if (!empty($messageBit)) {$filter["messageBit"]=" l.log_message LIKE '%".$messageBit."%' ";}
	
	// on vérifie que $messageType n'est pas vide et si c'est une valeur qui existe;
	if (!empty($messageType)) {
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
	// si $rowsNumber n'est pas nul, on limite le nombre de lignes retournées
	if (!is_null($rowsNumber) && $rowsNumber!=0 ) {$limit.=" LIMIT ".$rowsNumber." ";}


// on fait la requete pour recuperer les entrees du journal correspondantes
$logReadSql="	SELECT l.log_time, l.log_module_id, l.log_script_file, l.log_message, l.log_user_id, u.user_name, l.log_module_id, lm.module_name, l.log_action_do, l.log_action_undo, l.log_message_type, u.user_email
 				FROM admin_log l, admin_users u, admin_log_modules lm
			WHERE (l.log_module_id=lm.module_id) AND (l.log_user_id=u.user_id) $filterSql
			ORDER BY l.log_time	DESC				
			$limit	";
$logReadResult = pg_query($connectPPEAO,$logReadSql) or die('logRead dit - erreur dans la requete : ' . pg_last_error());
$logArray=pg_fetch_all($logReadResult);

// debug echo($logReadSql);
// debug print_r($logArray);

return $logArray;
}

//***************************************************************************************************
// affiche sous forme de table formattée une section de journal
function logTable($logArray,$format)
// cette fonction génère une table pour l'affichage d'une section du journal
// $logArray : le tableau contenant les entrées de journal à tabuler (array(timestamp,userName,moduleName,scriptFile,message))
// $format : "html" pour l'affichage et "csv" pour l'exportation
// $logTable : la table HTML générée par la fonction, prête à être affichée
{

$logTable="";
global $debug; // si $debug=1, alors on affiche des infos de débug dans le log (comme le script php)

if (!empty($logArray)) { // si le log n'est pas vide

switch ($format) {

	// we generate for a CSV file
	case "csv":
	$logTable='"date";"utilisateur";"module";"message";"action";"annulation";"script"
	';
	//on parcourt le tableau du journal pour generer une ligne  par entree
	foreach ($logArray as $logRow) {
			$logTable.=''.$logRow["log_time"].';"'.$logRow["user_name"].'";"'.$logRow["module_name"].'";"'.$logRow["log_message"].'";"'.$logRow["log_action_do"].'";"'.$logRow["log_action_undo"].'";"'.$logRow["log_script_file"].'";"'.$logRow["log_message_type"].'"
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
	foreach ($logArray as $logRow) {
		if ( $j&1 ) {$rowStyle='logTableRowOdd';} else {$rowStyle='logTableRowEven';}
		$logTable.='<tr class="'.$rowStyle.'">';
		
		// si on a un email pour l'utilisateur, on transforme son nom en lien mailto:
		// on inscrit la connexion dans le journal
		if (!empty($logRow['user_email'])) {$theName='<a href="mailto:'.$logRow['user_email'].'">'.$logRow["user_name"].'</a>';} else {$theName=$logRow["user_name"];}
		
		$logTable.='<td class="logTableTime">'.$logRow["log_time"].'</td><td class="logTableUser">'.$theName.'</td><td class="logTableModule">'.$logRow["module_name"].'</td><td class="logTableMessage">'.$logRow["log_message"].'</td><td class="logTableDo">'.$logRow["log_action_do"].'</td><td class="logTableUndo">'.$logRow["log_action_undo"].'</td>';
		if ($debug) {$logTable.='<td class="logTableScript">'.$logRow["log_script_file"].'</td><td>'.$logRow["log_message_type"].'</td>';}
		$logTable.='</tr>';
		$j++;
	
	} // end foreach $logArray
	
	$logTable.= '</table>'; //
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
// récupere sous forme de tableau long une section de journal
function logDisplayFull($date,$userId,$moduleId,$messageBit,$messageType)
// cette fonction crée un tableau contenant une section de journal, éventuellement filtrée
// pour affichage complet avec pagination, champs de tri/filtres etc.
// $date : format date (YYYY-MM-DD), si renseigné, affiche uniquement les entrées pour la date définie
// $userId : si renseigné, affiche uniquement les entrées pour l'utilisateur correspondant (table admin_users)
// $moduleId : si renseigné, affiche uniquement les entrées pour le module correspondant (table admin_log_modules)
// $messageBit : si renseigné, affiche uniquement les entrées dont le message (log_message) contient $messageBit
// $messageType : type de message (error, sql, warning, notice)
// cette fonction appelle la fonction logRead() pour extraire les entrées de journal adéquates
// et la fonction logTable() pour générer la table html de journal

{

// archivage et exportation du journal
echo('<script src="/js/journal.js" type="text/javascript" charset="utf-8"></script>');

// on recupere les entrees de journal souhaitees
$logArray=logRead($date,$userId,$moduleId,$messageBit,0,$messageType);

$logBlock='<div id="logMessage"></div>';
$logBlock.='<div id="logTableDiv">';
$logBlock.='<a href="javascript:deleteLog();">effacer le journal</a> (une version en sera archiv&eacute;e sur le serveur...)';
$logBlock.=logTable($logArray,'');
$logBlock.='</div>'; // end div id="logTableDiv"

return $logBlock;
}

//***************************************************************************************************
// récupere sous forme de tableau court une section de journal
function logDisplayShort($date,$userId,$moduleId,$messageBit,$rowsNumber,$messageType)
// cette fonction crée un tableau contenant une section de journal, éventuellement filtrée
// pour affichage court, sans pagination, champs de tri/filtres etc.
// $date : format date (YYYY-MM-DD), si renseigné, affiche uniquement les entrées pour la date définie
// $userId : si renseigné, affiche uniquement les entrées pour l'utilisateur correspondant (table admin_users)
// $moduleId : si renseigné, affiche uniquement les entrées pour le module correspondant (table admin_log_modules)
// $messageBit : si renseigné, affiche uniquement les entrées dont le message (log_message) contient $messageBit
// $rowsNumber : nombre d'entrées à retourner (si 0, tout retourner)
// $messageType : type de message (error, sql, warning, notice)
// cette fonction appelle la fonction logRead() pour extraire les entrées de journal adéquates
// et la fonction logTable() pour générer la table html de journal

{

// on recupere les entrees de journal souhaitees
$logArray=logRead($date,$userId,$moduleId,$messageBit,$rowsNumber,$messageType);
// on stocke le tableau dans une variable
$logBlock='<div id="logTableDiv">';

// le titre
echo('<h2>Activit&eacute; r&eacute;cente</h2>');
	
$logBlock.=logTable($logArray,'');

$logBlock.='</div>'; // end div id="logTableDiv"
$logBlock.='<div id="logTableDivLink"><a href="/journal.php" alt="consulter le journal" title="consulter le journal">consulter le journal</div>';

return $logBlock;
}


//***************************************************************************************************
//archive le journal au format texte compressé
function logArchive($archivePath)
// cette fonction archive le journal sous forme de fichier texte compressé
// $archivePath : le chemin du dossier dans lequel archiver le log
// $archiveMessage : le message retourné une fois l'archivage terminé
{

	// si $archivePath est vide, on utilise le chemin par defaut défini dans /variables.inc ($logArchivePath)
	global $logArchivePath; 
	if (empty($archivePath)) {$archivePath=$logArchivePath;}
	// le nom de l'archive qui sera creee
	$theArchiveName="journal_archive_".date("Y-m-d_G-i-s");
	// le chemin de l'archive qui sera creee
	$theArchivePath=$_SERVER["DOCUMENT_ROOT"].$archivePath.$theArchiveName.".csv.gz";

	// on cree le fichier .gz
	if ($theFile=@gzopen($theArchivePath,"w")) {

	// on genere le contenu du fichier
	$theContent=logTable(logRead("","","","","",""),'csv');

	// on écrit le contenu du journal dans le fichier (csv)
	if (gzwrite($theFile,$theContent)) {$success=1;} else {$success=0;$error="gzwrite";}
	// on ferme le fichier
	gzclose($theFile);

	// le chemin pour telecharger le fichier archive cree
	$downloadPath=$archivePath.$theArchiveName.".csv.gz";} else {$success=0;$error="gzopen";}
	
	if ($success==1) {logWriteTo(4,"notice","journal archiv&eacute;","","",0);} else {logWriteTo(4,"error","impossible d'archiver le journal - $error","","",0);
	;}
	
$return=array("success"=>$success,"error"=>$error,"downloadPath"=>$downloadPath);
return $return;
}

//***************************************************************************************************
//efface le journal
function logDelete($archivePath)
// cette fonction archive le journal puis vide la table de journal
// $clearMessage : le message retourné une fois l'effacement terminé

{
	global $connectPPEAO;
	// si $archivePath est vide, on utilise le chemin par defaut défini dans /variables.inc ($logArchivePath)
	global $logArchivePath; 
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

return $clearMessage;
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


foreach($groupsArray as $group)
	{$groupsArray2[]=$group['group_id'];}

return $groupsArray2;

}


//***************************************************************************************************
//retourne la liste des zones auxquelles l'utilisateur $user_id a accès
function userGetAuthorizedZones($user_id) {
// cette fonction collecte les zones auxquelles l'utilisateur et le(s) groupe(s) au(x)quel(s) il appartient ont accès
// $zonesArray : le tableau contenant la liste des zones auxquelles l'utilisateur a accès

global $connectPPEAO;

// si aucun utilisateur n'est indiqué, on considère que on a affaire à un visiteur
if (empty($user_id)) {$user_id=0;}

// on collecte la liste des zones auxquelles l'utilisateur a accès
$zonesArray1=array();
$zonesSql1='SELECT DISTINCT zone_id
			FROM admin_j_user_zone juz
			WHERE juz.user_id='.$user_id.'
			';
$zonesResult1 = pg_query($connectPPEAO,$zonesSql1) or die('erreur dans la requete : '.$zonesSql1. pg_last_error());
while($data1=pg_fetch_array($zonesResult1)) {$zonesArray1[]=$data1['zone_id'];}
// si aucun résultat, on considère que l'utilisateur n'a accès qu'aux zones publiques
if (empty($zonesArray1)) {$zonesArray1=array();}


//debug echo('$zonesArray1=');echo('<pre>');print_r($zonesArray1);echo('</pre>');

// on collecte la liste des groupes auxquels appartient l'utilisateur
$groupsArray=userGetGroups($user_id);

//debug print_r($groupsArray);

// on collecte la liste des zones auxquelles les groupes de l'utilisateur ont accès
$zonesArray2=array();
$zonesSql2='SELECT DISTINCT zone_id
			FROM admin_j_group_zone jgz
			WHERE jgz.group_id IN ('.implode(",",$groupsArray).')
			';
$zonesResult2 = pg_query($connectPPEAO,$zonesSql2) or die('erreur dans la requete : '.$zonesSql2. pg_last_error());
while($data2=pg_fetch_array($zonesResult2)) {$zonesArray2[]=$data2['zone_id'];}
// si aucun résultat, on considère que l'utilisateur n'a accès qu'aux zones publiques
if (is_null($zonesArray2)) {$zonesArray2=array();}

//debug echo('$zonesArray2=');echo('<pre>');print_r($zonesArray2);echo('</pre>');


// on fusionne les deux listes en éliminant les valeurs dupliquées
$zonesArray=array_unique(array_merge($zonesArray1,$zonesArray2));

//debug echo('$zonesArray=');echo('<pre>');print_r($zonesArray);echo('</pre>');

return $zonesArray;

}

//***************************************************************************************************
//permet de vérifier si un utilisateur a accès à une zone
function userHasAccess($user_id,$zone_id) {
// $user_id : id de l'utilisateur
// $zone_id : l'id de la zone à tester
// on teste à quelle zone l'utilisateur a accès
//debug echo('zone_id='.$zone_id);
$access=false;
if ($_SESSION['s_ppeao_login_status']=='good') {
	// on récupère la liste des zones auxquelles l'utilisateur a accès
	$lesZones=userGetAuthorizedZones($user_id);
	
	
	//debug 	echo('$lesZones=');	echo('<pre>');	print_r($lesZones);	echo('</pre>');
	
	
	// on teste si la zone de la page appelée fait partie de la liste des zones autorisées pour l'utilisateur
	// si la zone est 0 (publique) ou si l'utilisateur a accès à la zone 9999 ("toutes zones"), on donne l'accès
	// si oui, alors on exécute le code de la page
	if (in_array($zone_id,$lesZones) || is_null($zone_id) || in_array(9999,$lesZones)) {$access=true;}
}// end if ($_SESSION['s_ppeao_login_status']=='good')
// si la zone est "vide" on considère que c'est une zone publique
if ($zone_id=='') {$access=true;}

return $access;
}

//***************************************************************************************************
//affiche un message adéquat lorsque un utilisateur n'a pas accès à une zone
function userAccessDenied($zone_id) {
// $zone_id : id de la zone concernée

global $connectPPEAO;

// si on n'a pas spécifié de zone, on considère que c'est la zone publique
if ($zone_id=='') {$zone_id=0;}

// on récupère le nom de la zone concernée
$zoneSql='SELECT DISTINCT zone_name
			FROM admin_zones
			WHERE zone_id='.$zone_id.'
			';
$zoneResult = pg_query($connectPPEAO,$zoneSql) or die('erreur dans la requete : '.$zonesSql. pg_last_error());
$zoneArray=pg_fetch_array($zoneResult);

$zoneName=$zoneArray['zone_name'];

// on teste le statut de connexion
switch ($_SESSION['s_ppeao_login_status']) {
	case 'good' : $message='<div id="access_denied">Vous n\'avez pas les droits d\'acc&egrave;s à la section "'.$zoneName.'". <br />Contactez un administrateur si vous souhaitez y acc&eacute;der.</div>';
	break;
	default : $message='<div id="access_denied">Vous devez vous connecter pour acc&eacute;der &agrave; la section "'.$zoneName.'".</div>';
	break;
} // end switch

echo($message);

}

?>