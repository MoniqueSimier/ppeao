<?

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

if ($logLevel<=$debug) { // si le niveau du message ($logLevel) est inf�rieur au niveau de l'application ($debug) alors on �crit 
if (is_null($moduleId)) {$moduleId=0;} // si le moduleId n'a pas �t� d�fini, on lui assigne 0 (inconnu)

$timestamp=date('Y-m-d G:i:s'); // on assigne un UNIX timestamp

// on recupere le userId de la session, sinon on lui assigne 0 (zero)
if (isset($_SESSION['s_ppeao_userId'])) {$userId=$_SESSION['s_ppeao_userId'];} else {$userId=0;}

// on recupere le chemin du script actif
$scriptFile=$_SERVER['PHP_SELF'];

// on teste si $messageType est dans la table admin_log_message_types
	$typesSql="	SELECT  count(log_message_type)
				FROM admin_log
				WHERE log_message_type=$messageType
					";
	$typesResult = pg_query($connectPPEAO,$typesSql) or die('erreur dans la requete : ' . pg_last_error());
	$typesCount=pg_fetch_all($typesResult);
	if (count($typesCount)==0) {$messageType="notice";}

// on rend les chaines de carat�res "safe"
$message=addslashes($message);
$actionDo=addslashes($actionDo);
$actionUndo=addslashes($actionUndo);

$logWriteSql="	INSERT INTO admin_log (log_time,log_module_id,log_script_file,log_message,log_user_id,log_action_do,log_action_undo,message_type)
				VALUES ('$timestamp',$moduleId,'$scriptFile','$message',$userId,'$actionDo','$actionUndo', '$messageType')";
$logWriteResult = pg_query($connectPPEAO,$logWriteSql) or die('erreur dans la requete : ' . pg_last_error());
}



}


//***************************************************************************************************
//efface le journal
function logClear()
// cette fonction archive le journal puis vide la table de journal
// $clearMessage : le message retourn� une fois l'effacement termin�

{

return $clearMessage;
}

//***************************************************************************************************
//archive le journal au format texte compress�
function logArchive($archivePath)
// cette fonction archive le journal sous forme de fichier texte compress�
// $archivePath : le chemin du dossier dans lequel archiver le log
// $archiveMessage : le message retourn� une fois l'archivage termin�
{


return $archiveMessage;
}





//***************************************************************************************************
// lit une section de journal
function logRead($date,$userId,$moduleId,$messageBit,$rowsNumber,$messageType)
// cette fonction lit une section de journal, �ventuellement filtr�e
// $date : format date (YYYY-MM-DD), si renseign�, affiche uniquement les entr�es pour la date d�finie
// $userId : si renseign�, affiche uniquement les entr�es pour l'utilisateur correspondant (table admin_users)
// $moduleId : si renseign�, affiche uniquement les entr�es pour le module correspondant (table admin_log_modules)
// $messageBit : si renseign�, affiche uniquement les entr�es dont le message (log_message) contient $messageBit
// $rowsNumber : nombre d'entr�es � retourner (si 0, tout retourner)
// $messageType : type de message (error, sql, warning, notice)
// retourne $logArray, le tableau contenant les entr�es de journal s�lectionn�es 

{
global $connectPPEAO; // la connexion a utiliser (on travaille avec deux bases : BD_PECHE et BD_PPEAO)

// on prepare un tableau contenant les elements a utiliser comme filtre
$filter=array();
	// on verifie que $date contient bien une date
	$dateExploded=explode("-",$date);
	//debug print_r($dateExploded);
	if (checkdate($dateExploded[1],$dateExploded[2],$dateExploded[0]))
		{
		$filter["date"]=' l.log_time LIKE \''.$date.'%\' ';
		}
	// on verifie que $userId contient bien un utilisateur inscrit dans la table admin_users
	if (is_int($userId)) {
		$userCheckSql="	SELECT  count(user_id)
						FROM admin_users
						WHERE user_id=$userId
						";
		$userCheckResult = pg_query($connectPPEAO,$userCheckSql) or die('erreur dans la requete : ' . pg_last_error());
		$userCount=pg_fetch_array($userCheckResult);
		if ($userCount[0]!=0) {$filter["userId"]=" l.user_id=".$userId."";}
	} // fin if (is_int($userId))
	
	// on verifie que $moduleId contient bien un module inscrit dans la table admin_log_modules
	if (is_int($moduleId)) {
		$moduleCheckSql="	SELECT  count(module_id)
							FROM admin_log_modules
							WHERE module_id=$moduleId
						";
		$moduleCheckResult = pg_query($connectPPEAO,$moduleCheckSql) or die('erreur dans la requete : ' . pg_last_error());
		$moduleCount=pg_fetch_array($moduleCheckResult);
		if ($moduleCount[0]!=0) {$filter["moduleId"]=" l.module_id=".$moduleId." ";}
	} // fin if (is_int($moduleId))
	
	// on verifie que $messageBit n'est pas vide
	if (!empty($messageBit)) {$filter["messageBit"]=" l.log_message LIKE '%".$messageBit."%' ";}
	
	// on v�rifie que $messageType n'est pas vide et si c'est une valeur qui existe;
	if (!empty($messageType)) {
		// on teste si $messageType est dans la table admin_log_message_types
		$typesSql="	SELECT  count(message_type)
					FROM admin_log_message_types
					WHERE message_type=$messageType
					";
			$typesResult = pg_query($connectPPEAO,$typesSql) or die('erreur dans la requete : ' . pg_last_error());
			$typesCount=pg_fetch_all($typesResult);
			if (count($typesCount)==0) {$filter["messageType"]=" l.log_message_type LIKE '".$messageType."' ";}
		}
	
	
	// on construit le segment sql pour le filtre
	if (count($filter)!=0) {
	$filterSql=" AND (".arrayToList($filter," AND ","").")";}
		else {$filterSql="";}
	// si $rowsNumber n'est pas nul, on limite le nombre de lignes retourn�es
	if (!is_null($rowsNumber) && $rowsNumber!=0 ) {$limit.=" LIMIT ".$rowsNumber." ";}


// on fait la requete pour recuperer les entrees du journal correspondantes
$logReadSql="	SELECT l.log_time, l.log_module_id, l.log_script_file, l.log_message, l.log_user_id, u.user_name, l.log_module_id, lm.module_name, l.log_action_do, l.log_action_undo, l.log_message_type
 				FROM admin_log l, admin_users u, admin_log_modules lm
			WHERE (l.log_module_id=lm.module_id) AND (l.log_user_id=u.user_id) $filterSql
			ORDER BY l.log_time	DESC				
			$limit	";
$logReadResult = pg_query($logReadSql) or die('erreur dans la requete : ' . pg_last_error());
$logArray=pg_fetch_all($logReadResult);

// debug echo($logReadSql);
// debug print_r($logArray);

return $logArray;
}

//***************************************************************************************************
// affiche sous forme de table formatt�e une section de journal
function logTable($logArray,$format)
// cette fonction g�n�re une table pour l'affichage d'une section du journal
// $logArray : le tableau contenant les entr�es de journal � tabuler (array(timestamp,userName,moduleName,scriptFile,message))
// $format : "html" pour l'affichage et "csv" pour l'exportation
// $logTable : la table HTML g�n�r�e par la fonction, pr�te � �tre affich�e
{
global $debug; // si $debug=1, alors on affiche des infos de d�bug dans le log (comme le script php)

switch ($format) {

	// we generate for a CSV file
	case "csv":
	$logTable='date;utilisateur;module;message;action;annulation;script\r\n';
	//on parcourt le tableau du journal pour generer un <tr>  par entree
	foreach ($logArray as $logRow) {
			$logTable.=$logRow["log_time"].';'.$logRow["user_name"].';"'.$logRow["module_name"].'";"'.$logRow["log_message"].'";"'.$logRow["log_action_do"].'";"'.$logRow["log_action_undo"].'";"'.$logRow["log_script_file"].'"\r\n';
		} // end foreach $logArray
	;
	break;
	
	
	// default case : we generate an HTML table
	default:
	$logTable.='<table id="logTable" cellpadding="0" cellspacing="0" border="0">';
	// les en-tete de la table
	$logTable.='<tr class="logTableHeaderRow">';
	$logTable.='<td class="logTableTime">date</td><td class="logTableUser">utilisateur</td><td class="logTablemodule">module</td><td class="logTableMessage">message</td><td class="logTableDo">action</td><td class="logTableUndo">annulation</td>';
	if ($debug) {$logTable.='<td class="logTableScript">script</td>';}
	$logTable.='</tr>';
	
	$j=0; // counter used to differentiate odd and even rows
	//on parcourt le tableau du journal pour generer un <tr>  par entree
	foreach ($logArray as $logRow) {
		if ( $j&1 ) {$rowStyle='logTableRowOdd';} else {$rowStyle='logTableRowEven';}
		$logTable.='<tr class="'.$rowStyle.'">';
		$logTable.='<td class="logTableTime">'.$logRow["log_time"].'</td><td class="logTableUser">'.$logRow["user_name"].'</td><td class="logTableModule">'.$logRow["module_name"].'</td><td class="logTableMessage">'.$logRow["log_message"].'</td><td class="logTableDo">'.$logRow["log_action_do"].'</td><td class="logTableUndo">'.$logRow["log_action_undo"].'</td>';
		if ($debug) {$logTable.='<td class="logTableScript">'.$logRow["log_script_file"].'</td>';}
		$logTable.='</tr>';
		$j++;
	
	} // end foreach $logArray
	
	$logTable.= '</table>'; //
	;
	break;
}

return $logTable;
}

//***************************************************************************************************
// r�cupere sous forme de tableau long une section de journal
function logDisplayFull($date,$userId,$moduleId,$messageBit,$messageType)
// cette fonction cr�e un tableau contenant une section de journal, �ventuellement filtr�e
// pour affichage complet avec pagination, champs de tri/filtres etc.
// $date : format date (YYYY-MM-DD), si renseign�, affiche uniquement les entr�es pour la date d�finie
// $userId : si renseign�, affiche uniquement les entr�es pour l'utilisateur correspondant (table admin_users)
// $moduleId : si renseign�, affiche uniquement les entr�es pour le module correspondant (table admin_log_modules)
// $messageBit : si renseign�, affiche uniquement les entr�es dont le message (log_message) contient $messageBit
// $messageType : type de message (error, sql, warning, notice)
// cette fonction appelle la fonction logRead() pour extraire les entr�es de journal ad�quates
// et la fonction logTable() pour g�n�rer la table html de journal

{

// A FAIRE : les fonctions p�riph�riques du type pagination, filtre interactif etc n'ont pas encore �t� r�alis�es

// on recupere les entrees de journal souhaitees
$logArray=logRead($date,$userId,$moduleId,$messageBit,$messageType);

$logBlock='<div id="logTableDiv">';
$logBlock.=logTable($logArray,'');
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
$logArray=logRead($date,$userId,$moduleId,$messageBit,$rowsNumber,$messageType);

$logBlock='<div id="logTableDiv">';
$logBlock.=logTable($logArray,'');

// A FAIRE : ins�rer un lien permettant d'acc�der � la page de consultation du journal, utilisant logDisplayFull()

$logBlock.='</div>'; // end div id="logTableDiv"

return $logBlock;
}

?>