	/**
	* fonction appelée lorsque'l'utilisateur se connecte via le formulaire de login
	*/
	function ajaxLogin(){

		var xhr = getXhr();


		// what to do when the response is received
		xhr.onreadystatechange = function(){
			// while waiting for the response, display the loading animation
			var theLoader='<div align="center">connexion...<img src="/assets/ajax-loader.gif" alt="connexion..." title="connexion..." valign="center"/></div>';
			
			var theLoginForm=document.getElementById("smalloginform");
			
			if(xhr.readyState < 4) { theLoginForm.innerHTML = theLoader;}
			// only do something if the whole response has been received and the server says OK
			if(xhr.readyState == 4 && xhr.status == 200){
				theMessage = xhr.responseText;
				
				theLoginForm.innerHTML = theMessage;

			}// end function()
		} // end ajaxLogin


		// using GET to send the request
						xhr.open("GET","session/login.php?login="+document.getElementById("slogin").value+"&pass="+document.getElementById("spass").value,true);
		xhr.send(null);
	}


		/**
		* fonction appelée lorsque'l'utilisateur se déconnecte via le formulaire de login
		*/
		function ajaxLogout(){

			var xhr2 = getXhr();


			// what to do when the response is received
			xhr2.onreadystatechange = function(){
				// while waiting for the response, display the loading animation
				var theLoader=' <div align="center">déconnexion...<img src="/assets/ajax-loader.gif" alt="déconnexion..." title="déconnexion..." valign="center"/></div>';

				var theLoginForm=document.getElementById("smalloginform");

				if(xhr2.readyState < 4) { theLoginForm.innerHTML = theLoader;}
				// only do something if the whole response has been received and the server says OK
				if(xhr2.readyState == 4 && xhr2.status == 200){
					theMessage = xhr2.responseText;

					theLoginForm.innerHTML = theMessage;

				}// end function()
			} // end ajaxLogout


			// using GET to send the request
			xhr2.open("GET","session/logout.php",true);
			xhr2.send(null);
		}
