var xmlHttp

function runRecomp(nbEnreg,DBname)
{
	adresse = document.getElementById("adresse").value;
	
	if (! confirm('Confirmez-vous l excécution de la recomposition des donnnées ?')) {
		exit;
	}
	
	document.getElementById("formRecomp").innerHTML="<br/><img src='/assets/ajax-loader-comp.gif' alt=''/>";
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  	alert ("Your browser does not support AJAX!");
	  	return;
	  } 
	var url="recomposition.php";
	xmlHttp.onreadystatechange=stateChanged2;
	url=url+"?base="+DBname+"&nb_enr="+nbEnreg+"&adresse="+adresse;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 



function runClear(DBname)
{

	if (! confirm('Confirmez-vous la suppression des donnnées ?')) {
		exit;
	}
	
	document.getElementById("formEfface").innerHTML="<br/><img src='/assets/ajax-loader-comp.gif' alt=''/>";
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  	alert ("Your browser does not support AJAX!");
	  	return;
	  } 
	var url="rec_efface.php";
	xmlHttp.onreadystatechange=stateChanged1;
	url=url+"?base="+DBname;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 

function stateChanged1() 
{ 

	if (xmlHttp.readyState==4)
	{ 
		document.getElementById("formEfface").innerHTML=xmlHttp.responseText;
	}
}
function stateChanged2() 
{ 

	if (xmlHttp.readyState==4)
	{ 
		document.getElementById("formRecomp").innerHTML=xmlHttp.responseText;
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