<?
// Mis à jour par Olivier ROUX, 29-07-2008

// démarrage de la session
session_start();

//debug print_r($_SESSION);
//end debug


// adds " active" to the style of each menu entry to highlight the current one in the navigation menu
switch ($section) {
	case "home" : $home=" active";
	break;
	case "edition" : $edition=" active";
	break;
	case "portage" : $portage=" active";
	break;
	case "contact" : $contact=" active";
	break;
	case "journal" : $journal=" active";
	break;
}

include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
include $_SERVER["DOCUMENT_ROOT"].'/variables.inc';
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
include $_SERVER["DOCUMENT_ROOT"].'/session/login_forms.php';

echo('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');


?>