<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>basic AJAX call/response</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Olivier ROUX">
	
		<link href="/styles/mainstyles.css" title="mainstyles" rel="stylesheet" type="text/css" />
		<script src="/js/basic.js" type="text/javascript" charset="utf-8"></script></head>
		
		
		<script type="text/javascript" charset="utf-8">
			/**
			* Function called when the user clicks on the link
			* to update the content of the theContent div
			* using an AJAX call
			*/
			function ajaxCall(message){

				var xhr = getXhr();


				// what to do when the response is received
				xhr.onreadystatechange = function(){
					// while waiting for the response, display the loading animation
					var theLoader=' on attend la r&eacute;ponse ...';
					if(xhr.readyState < 4) { document.getElementById("theContent").innerHTML = theLoader;}
					// only do something if the whole response has been received and the server says OK
					if(xhr.readyState == 4 && xhr.status == 200){
						theMessage = xhr.responseText;
						
						document.getElementById("theContent").innerHTML = theMessage;

					}// end function()
				} // end ajaxCall


				// using GET to send the request
				xhr.open("GET","ajax_response.php?message="+message,true);
				xhr.send(null);
			}
		</script>
		
		
<body>

<div id="theCallDiv">
<div id="theContent">here is the content div</div>
<a href="javascript:ajaxCall('bonjour, monde!')">click here to trigger the ajax call</a>
</div>

</body>
</html>
