/*
* preloading of images (used for preloading the "loading..." animated GIF displayed while AJAX is doing its thing)
*/
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}


/*
* function used to create an AJAX object
*/
function getXhr(){
                    var xhr = null; 
	if(window.XMLHttpRequest) // Firefox et autres
	   xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // Internet Explorer 
	   try {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
	}
	else { // XMLHttpRequest not supported by browser 
	   xhr = false; 
	} 
                    return xhr;
}

/* 
* function that allows to create named elements in Internet Explorer
* IE doesn't allow to name a created element using the standard theElement.setAttribute("name","theElementName");
* so you need to try it first the IE way using the IE-only createElement('<input name="">...')
*/
function createNamedElement(type, name) {
   var element = null;
   // Try the IE way; this fails on standards-compliant browsers
   try {
      element = document.createElement('<'+type+' name="'+name+'">');
   } catch (e) {
   }
   if (!element || element.nodeName != type.toUpperCase()) {
      // Non-IE browser; use canonical method to create named element
      element = document.createElement(type);
      element.name = name;
   }
   return element;
}


/**
* fonction appel�e lorsque l'utilisateur se connecte via le formulaire de login
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
			// si le login est r�ussi, on rafraichit la page
			if(theMessage == 'success') {document.location.reload(true);}
			// sinon on affiche le formulaire de login avec un message d'erreur
			else {theLoginForm.innerHTML = theMessage;}

		}// end function()
	} // end ajaxLogin


	// using GET to send the request
					xhr.open("GET","/session/login.php?login="+document.getElementById("slogin").value+"&pass="+document.getElementById("spass").value,true);
	xhr.send(null);
}


/**
* fonction appel�e lorsque'l'utilisateur se d�connecte via le formulaire de login
*/
function ajaxLogout(){

	var xhr2 = getXhr();


	// what to do when the response is received
	xhr2.onreadystatechange = function(){
		// while waiting for the response, display the loading animation
		var theLoader=' <div align="center">d&eacute;connexion...<img src="/assets/ajax-loader.gif" alt="d&eacute;connexion..." title="d&eacute;connexion..." valign="center"/></div>';

		var theLoginForm=document.getElementById("smalloginform");

		if(xhr2.readyState < 4) { theLoginForm.innerHTML = theLoader;}
		// only do something if the whole response has been received and the server says OK
		if(xhr2.readyState == 4 && xhr2.status == 200){
			theMessage = xhr2.responseText;
			// une fois la d�connexion faite, on rafraichit la page
			if(theMessage == 'success') {document.location.reload(true);}
		}// end function()
	} // end ajaxLogout


	// using GET to send the request
	xhr2.open("GET","/session/logout.php",true);
	xhr2.send(null);
}



/**
* Fonction utilis�e pour r�cup�rer la valeur d'un param�tre dans une URL
*/

function gup( urlParam ) {
// urlParam : le nom du param�tre d'URL dont on veut r�cup�rer la valeur
  var regexS = "[\\?&]"+urlParam+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var tmpURL = window.location.href;
  var results = regex.exec( tmpURL );
  if( results == null )
    return "";
  else
    return results[1];
}

/**
* Fonction utilis�e pour r�cup�rer les valeurs des OPTIONS s�lectionn�es dans un SELECT
*/

function getMultipleSelect(theSelect) {
// theSelect : objet DOM de type SELECT dont on veut r�cup�rer les valeurs s�lectionn�es
var selected = new Array(); 
for (var i = 0; i < theSelect.options.length; i++) if (theSelect.options[i].selected) selected.push(theSelect.options[i].value);
return selected;
}


/**
* Fonction g�n�rique qui permet de s�lectionner ou d�s�lectionner toutes les valeurs d'un SELECT
*/
function toggleSelectSelection(select,what) {
	// select : l'id du SELECT � utiliser
	// what : que s�lectionner ('all' ou 'none')
	
	// on pointe le SELECT � utiliser
	var theSelect=$(select);
	// on r�cup�re les OPTIONS de ce SELECT
	var theOptions=theSelect.getElements('option');
	
	//debug 	alert(theOptions.length);
	
	// on boucle sur les options
	for (i=0;i<theOptions.length;i++) {
		// si on veut tout s�lectionner
		if (what=='all') {theOptions[i].setProperty('selected','selected');}
		// si on veut tout d�s�lectionner
		if (what=='none') {theOptions[i].removeProperty('selected');}
	} // end for
	
}
