<?php
//*****************************************
// gestion_doc.php
//*****************************************
// Created by Yann Laurent
// 2008-10-18 : creation
//*****************************************
// Cet include permet de gérer les actions sur les fichiers
 	
$action = $_GET['action'];
$labelModify = "modifier";
$labelDelete = "supprimer";
$labelBrowse = "telecharger";
$labelAdd = "Ajouter";
$repDoc ="";
$action ="";
$actionLabel="";
$checkRepOK = false;
if (isset($_GET['rep'])) {
	$repDoc = $_GET['rep'];
}
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}
if (isset($_GET['do'])) {
	// On est en train de faire quelquechose sur ces fichiers, on ne va pas les rendre disponibles pour une autre action
	$doAction = $_GET['do'];
	$numRemp = intval($_GET['num']);
	switch ($doAction) {
	case "mod" : 
		$doActionLabel = "modification"; break;
	case "del" : 
		$doActionLabel = "suppression"; break;
	}
} else {
	$doAction = "";
	$numRemp = -1;
	$doActionLabel ="";
}
switch ($action) {
	case "modify" : 
		$actionLabel = "modifier"; break;
	case "create" : 
		$actionLabel = "cr&eacute;er"; break;
}
$nomRep = str_replace("\\" ," ", $repDoc); // A revoir
$nomRep = str_replace("/" ," ", $nomRep); // A revoir

$RawListDoc = getDocumentation($repDoc,"raw","n","");
if ( $RawListDoc =="") {
	$ContentDiv .="Ce r&eacute;pertoire ne contient pas de documents (".$repDoc.")<br/>";
} else {
	$checkRepOK = true;
}
$ContentDiv .= "<h2 >".$actionLabel." de la documentation pour ".$nomRep."</h2><br/>";
$ContentDiv .= "<div id=\"listeDocATraiter\">";
switch ($action){
	case "modify" :
		// On recupere les informations pour les corriger eventuellement
		// On analyse le répertoire pour créer les différents inputs
		//Test echo"<br/>".$RawListDoc;
		if ($checkRepOK) {
			$listeDoc = explode(",",$RawListDoc);
			$nbDoc = count($listeDoc) - 1;
			$ContentDiv .= "<h3>".count($listeDoc)." documents disponibles pour ".$nomRep."</h3>";
			$ContentDiv .= "<ul class=\"sublistDoc\">";
			$Rang = 1;
			for ($cpt = 0; $cpt <= $nbDoc; $cpt++) {
				if ($cpt == ($numRemp-1)) {
				// On est sur l'enregistrement en train d'être traité
					$ContentDiv .= "<li class=\"subselDoc\">		
					<span class=\"doccol3\" >".$listeDoc[$cpt]."</span><span class=\"doccol4\" >En cours de ".$doActionLabel." &nbsp;</span></li>";	
				} else {
					$ContentDiv .= "<li class=\"subselDoc\">		
					<span class=\"doccol1\" >".$listeDoc[$cpt]."</span><span class=\"doccol2\" >&nbsp;<a id=\"Action[".$cpt."]\" class=\"link_button\" href=\"/gestion_doc.php?rep=".$repDoc."&amp;action=".$action."&amp;num=".$Rang."&amp;do=mod\">".$labelModify."</a>&nbsp;<a id=\"Action[".$cpt."]\" class=\"link_button\" href=\"/gestion_doc.php?rep=".$repDoc."&amp;action=".$action."&amp;num=".$Rang."&amp;do=del\">".$labelDelete."</a></span></li>";
				}
				$Rang++;
			}
			$ContentDiv .= "</ul>";
		}
		break;
	case "create" : 
		$ContentDiv .= "veuillez s&eacute;lectionner le fichier à importer.&nbsp;
		
		<form id=\"gestdoc\" method=\"POST\" action=\"/gestion_doc.php\" enctype=\"multipart/form-data\">
			<input type=\"hidden\" name=\"upload\" value=\"yes\">	
			 <input type=\"hidden\" name=\"max_file_size\" value=\"100000\">
			 <input type=\"hidden\" name=\"repName\" value=\"".$repDoc."\">
			 Fichier : <input type=\"file\" name=\"avatar\">
			 <input type=\"submit\" name=\"envoyer\" value=\"".$labelBrowse."\">
		</form>";
		break;

} 
$ContentDiv .= "</div>";

?>