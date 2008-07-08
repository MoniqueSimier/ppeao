var xmlHttp
var fenID
var numProcess = 1
var progPhp
var nomFen
var nomURL 
var AddURL
var finTrt = false;
function runProcess()
{
	
	document.getElementById("sauvegarde_img").innerHTML="<img src='/assets/ajax-loader-comp.gif' alt=''/>";
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  	alert ("Your browser does not support AJAX!");
	  	return;
	  } 
	var url="/process_auto/sauvegarde.php";
	fenID = "sauvegarde";
	xmlHttp.onreadystatechange=stateChanged1;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 

function runProcessNext(phpProcess,locFenID,locURL)
{
	
	fenID = locFenID;
	fenIDImg = locFenID+"_img";
	document.getElementById(fenIDImg).innerHTML="<img src='/assets/ajax-loader-comp.gif' alt=''/>";
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  	alert ("Your browser does not support AJAX!");
	  	return;
	  } 
	if (locURL == "") {
		AddURL = "";
	} else {
		AddURL = "?action="+locURL;
	}
	var url="/process_auto/"+phpProcess+AddURL;
	
	xmlHttp.onreadystatechange=stateChanged1;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}


function stateChanged1() 
{ 

	if (xmlHttp.readyState==4)
	{ 		
		document.getElementById(fenID).innerHTML=xmlHttp.responseText;
		// Test si précédent process en erreur.
		//...
		switch(numProcess)
		{
		case 1:
		  	progPhp = "comparaison.php" ;
			nomFen = "comparaison" ;
			nomURL = "comp";
		  break;    
		case 2:
		  	progPhp = "comparaison.php" ;
			nomFen = "copieScientifique" ;
			nomURL = "maj";
		  break;
		case 3:
		  finTrt = true;
		}
		if (!finTrt) {
			numProcess++;
			suivant=runProcessNext(progPhp,nomFen,nomURL);
		} 
	}
}


function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
	  {
	  // Firefox, Opera 8.0+, Safari
	  xmlHttp=new XMLHttpRequest();
	  }
	catch (e)
	  {
	  // Internet Explorer
	  try
		{
		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
	  catch (e)
		{
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	  }
	return xmlHttp;
}