<?php
//************************************************
//script used to handle user login
//************************************************

//debug sleep(30);

global $zone;

session_start();
// includes the file containing the db connection script
include $_SERVER["DOCUMENT_ROOT"].'/connect.inc';
// inclut le fichier contenant le fonctions php communes
include $_SERVER["DOCUMENT_ROOT"].'/functions.php';
// inclut le fichier contenant le formulaire de login
include $_SERVER["DOCUMENT_ROOT"].'/session/login_forms.php';



if(isset($_GET) && !empty($_GET['login']) && !empty($_GET['pass'])) {
  extract($_GET);
  // on recupère le password de la table qui correspond au login du visiteur, 
  $loginSql = "	SELECT user_id, user_password, user_longname,  user_active, user_email
			FROM admin_users where user_name LIKE '".$login."'";
  $loginReq = pg_query($loginSql) or die('Erreur  dans la requête<br>'.$loginSql.'<br>'.mysql_error());

  $loginData = pg_fetch_assoc($loginReq);

// il faut encrypter la valeur $_POST['pass'] pour la comparer avec le mot de passe encrypté stocké dans la bdd

	// on utilise les deux premiers caractères du login comme "salt"
	$salt=substr($login,0,2);
	//on encrypte le mot de passe soumis
	$challenge_password=crypt($pass,$salt);
	
	//debug 	echo('challenge_password: '.$challenge_password);	print_r($loginData);

  // si le log/pass n'est pas le bon
  if($loginData['user_password'] != $challenge_password) {
    $_SESSION['s_ppeao_login_status']='bad';
  }
	// si le log/pass est le bon, on connecte l'utilisateur 
  else {
	// si son compte est toujours actif
	if ($loginData['user_active']==true) {
    session_start();
	
	// on inscrit un certain nombre d'informations sur l'utilisateur dans la session
	$_SESSION['s_ppeao_user_id'] = $loginData['user_id'];    
	$_SESSION['s_ppeao_name'] = $login;
	$_SESSION['s_ppeao_email'] = $loginData["user_email"];
	$_SESSION['s_ppeao_longname'] = $loginData["user_longname"];
    $_SESSION['s_ppeao_login_status']='good';
	$_SESSION['s_ppeao_user_active']=$loginData['user_active'];
	
	// on inscrit la connexion dans le journal
	$loginMessage='connexion de '.$_SESSION['s_ppeao_longname'];
	
	$zoneName=$zoneArray['zone_name'];
	logWriteTo(5,"notice",$loginMessage,'','',0);
	
	} // end if ($data['user_active']=='true')
	
	// sinon on ne le connecte pas
	else {$_SESSION['s_ppeao_login_status']='deactivated';}
} // end else
	
} // end if(isset...)



switch ($_SESSION['s_ppeao_login_status']) {
	case 'good': $loginResponse='success';
		break;
	case 'bad': $loginResponse=showLoginForm().'<div id="login_message">Erreur de connexion, veuillez essayer &agrave; nouveau.</div>';
		break;
	case 'deactivated': $loginResponse=showLoginForm().'<div id="login_message">Votre compte a &eacute;t&eacute; d&eacute;sactiv&eacute;, veuillez contacter l\'administrateur.</div>';
		break;
	} // end switch $_SESSION['s_ppeao_login_status']
	

echo $loginResponse;

?>