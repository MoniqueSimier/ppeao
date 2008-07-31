var xmlHttp
var fenID
var numProcess = 1
var progPhp
var nomFen
var nomURL 
var AddURL
var finTrt = false;
var portageOK = false;
// Variable pour lancement recomposition données
var adresse ;
var DBname;
var nbEnreg;

function runProcess()
{
	adresse = document.getElementById("adresse").value;
	DBname = document.getElementById("BDName").value;
	nbEnreg = document.getElementById("NBEnr").value;

	document.getElementById('titleProcess').innerHTML="Portage automatique en cours";
	document.getElementById("sauvegarde_img").innerHTML="<img src='/assets/ajax-loader.gif' alt=''/>";
	document.getElementById("sauvegarde_txt").innerHTML="Sauvegarde en cours";
	document.getElementById('portageOK_img').innerHTML="<img src='/assets/ajax-loader.gif' alt=''/>";
	document.getElementById('portageOK_txt').innerHTML="Status du portage = en cours de traitement";
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

function runProcessNext(phpProcess,locFenID,locURL,locTexte)
{
	
	fenID = locFenID;
	fenIDImg = locFenID+"_img";
	fenIDText = locFenID+"_txt";
	document.getElementById(fenIDImg).innerHTML="<img src='/assets/ajax-loader.gif' alt=''/>";
	document.getElementById(fenIDText).innerHTML=locTexte;
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  	alert ("Your browser does not support AJAX!");
	  	return;
	  } 
	if (locURL == "") {
		AddURL = "";
	} else {
		AddURL = "?"+locURL;
	}
	var url=phpProcess+AddURL;
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
		  	progPhp = "/process_auto/comparaison.php" ;
			nomFen = "comparaison" ;
			nomURL = "action=comp";
			texte = "Comparaison des base en cours...";
		  break;    
		case 2:
		  	progPhp = "/process_auto/comparaison.php" ;
			nomFen = "copieScientifique" ;
			nomURL = "action=maj";
			texte = "Copie des donn&eacute;es scientifiques en cours...";
		  break;
		  case 3:
		  	progPhp = "/process_auto/processAuto.php" ;
			nomFen = "processAuto" ;
			nomURL = "base="+DBname+"&nb_enr="+nbEnreg+"&adresse="+adresse;
			texte = "Recalcul automatique des données en cours ...";
		  break;
		case 4:
		  finTrt = true;
		}
		if (!finTrt) {
			numProcess++;
			suivant=runProcessNext(progPhp,nomFen,nomURL,texte);
		} else {
			// fin du traitement 
			document.getElementById('titleProcess').innerHTML="Portage automatique termin&eacute;";
			if (!portageOK) {
				document.getElementById('portageOK_img').innerHTML="<img src='/assets/incomplete.png' alt=''/>";
				document.getElementById('portageOK_txt').innerHTML="Status du portage = en erreur. Merci de consulter les logs.";
			} else {
				document.getElementById('portageOK_txt').innerHTML="<img src='/assets/completed.png' alt=''/>";
				document.getElementById('portageOK_txt').innerHTML="Status du portage = r&eacute;ussi et valid&eacute;";
			}
			return;
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