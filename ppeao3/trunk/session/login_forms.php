<?php 
function showLoginForm() {
$theLoginForm='<form action="/session/login.php" method="post" id="sloginform" name="sloginform">
<dl>
<dt><label for="login">nom d\'utilisateur</label></dt>
	<dd><input type="text" name="slogin" id="slogin" size="12" maxlength="250" /></dd>
<dt><label for="pass">mot de passe</label></dt>
<dd><input type="password" name="spass" id="spass" size="12" maxlength="12" /></dd>
<dd><a href="#" onclick="ajaxLogin();">connecter</a></dd>
</dl>
</form>';

return $theLoginForm;
}



function showLogoutForm($userLongname) {
if (isset($_SESSION['s_ppeao_longname']) && $_SESSION['s_ppeao_longname']!='') {$theName=$_SESSION['s_ppeao_longname'];} else {$theName=$userLongname;}


$theLogOutForm='<div><a href="#" onclick="javascript:ajaxLogout();">d&eacute;connecter </a> '.$theName.'</div>';

return $theLogOutForm;
}





?>