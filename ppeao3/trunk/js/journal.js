/**
* Function called when the user clicks on the link
* to update the content of the theContent div
* using an AJAX call
*/
function deleteLog() {

var xhr = getXhr();


	// what to do when the response is received
	xhr.onreadystatechange = function(){
		// while waiting for the response, display the loading animation
		var theLoader=' <div align="center">effacement et archivage du journal...<img src="/assets/ajax-loader.gif" alt="effacement et archivage du journal..." title="effacement et archivage du journal..." valign="center"/></div>';
		if(xhr.readyState < 4) { document.getElementById("logMessage").innerHTML = theLoader;}
		// only do something if the whole response has been received and the server says OK
		if(xhr.readyState == 4 && xhr.status == 200){
			theMessage = xhr.responseText;
			document.getElementById("logMessage").innerHTML = theMessage;
			reloadLog();
		}// end function()
} // end ajaxCall


// using GET to send the request
xhr.open("GET","/journal/delete_log.php",true);
xhr.send(null);
}


/**
* Function called after the log has been deleted
* to update the content of the logTableDiv
* using an AJAX call
*/
function reloadLog() {

var xhr2 = getXhr();


	// what to do when the response is received
	xhr2.onreadystatechange = function(){
		// while waiting for the response, display the loading animation
		var theLoader2=' <div align="center">chargement du journal...<img src="/assets/ajax-loader.gif" alt="chargement du journal..." title="chargement du journal..." valign="center"/></div>';
		if(xhr2.readyState < 4) { document.getElementById("logTableDiv").innerHTML = theLoader2;}
		// only do something if the whole response has been received and the server says OK
		if(xhr2.readyState == 4 && xhr2.status == 200){
			theLog = xhr2.responseText;
			document.getElementById("logTableDiv").innerHTML = theLog;
		}// end function()
} // end ajaxCall


// using GET to send the request
xhr2.open("GET","/journal/reload_log.php",true);
xhr2.send(null);
}