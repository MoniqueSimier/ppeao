<fieldset id="smalloginform">
<?php 
//*********************************************************************
// le formulaire de login / logout
//*********************************************************************


// affiche le formulaire de login si l'utilisateur n'est pas loggu�

//debug

if ( !isset($_SESSION['s_ppeao_login_status']) || ( isset($_SESSION['s_ppeao_login_status']) && $_SESSION['s_ppeao_login_status']!='good')) 
	{
	

	// on affiche le formulaire de login si l'utilisateur n'est pas connect�
	echo(showLoginForm());
	
} // end if ($_SESSION['s_ppeao_login_status']!='good'); 
		
	// sinon on affiche le formulaire de d�connexion
	else {
		
		echo(showLogoutForm($_SESSION['s_ppeao_longname']));
		} // end else
					

?>
	</fieldset>