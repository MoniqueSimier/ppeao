var xmlHttp
var fenID
var numProcess = 1
var progPhp
var nomFen
var nomURL 
var AddURL
var finTrt = false;
var portageOK = "ok";
// Variable pour lancement recomposition données
var adresse ;
var DBname;
var nbEnreg;
var checkLog;
var relancetrt = false;
// Variables identifiant les différents process a executer ou non
var svgExec = true; 	// sauvegarde
var compExec = true; 	// Comparaison entre devppeao et bdpeche pour param et ref
var compInvExec = true;	// Comparaison entre bdpeche et devppeao  pour param art
var majscExec = true;	// Copie données scientifiques
var majrecExec = true;	// Copie données recomposées
var recExec = true;		// Exécution recompistion données
var statExec = true;	// Exécution calcul stats
var purgeExec = true;	// Exécution purge

function runProcess()
{
	// On récupère les valeurs des différents paramètres entrée
	adresse = document.getElementById("adresse").value;
	DBname = document.getElementById("BDName").value;
	nbEnreg = document.getElementById("NBEnr").value;
	checkLog = document.getElementById("logsupp").checked;
	// On identifie les processus à exécuter.
	svgExec = document.getElementById("svgcheck").checked;
	compExec = document.getElementById("compcheck").checked;
	compInvExec = document.getElementById("compinvcheck").checked;
	majscExec = document.getElementById("majsccheck").checked;
	majrecExec = document.getElementById("majreccheck").checked;
	recExec = document.getElementById("reccheck").checked;
	statExec = document.getElementById("statcheck").checked;
	purgeExec = document.getElementById("purgecheck").checked;
	
	// Initialisation des affichages en début de traitement.
	document.getElementById('titleProcess').innerHTML="Portage automatique en cours";
	document.getElementById("sauvegarde_img").innerHTML="<img src='/assets/ajax-loader_32px.gif' alt=''/>";
	document.getElementById("sauvegarde_txt").innerHTML="Sauvegarde en cours";
	document.getElementById('portageOK_img').innerHTML="<img src='/assets/ajax-loader_32px_gris.gif' alt=''/>";
	document.getElementById('portageOK_txt').innerHTML="Status du portage = en cours de traitement";
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  	alert ("Your browser does not support AJAX!");
	  	return;
	  } 
	var url="/process_auto/sauvegarde.php?exec="+svgExec;
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
	document.getElementById(fenIDImg).innerHTML="<img src='/assets/ajax-loader_32px.gif' alt=''/>";	
	if (!relancetrt) {
		document.getElementById(fenIDText).innerHTML=locTexte;
	}
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

function runProcessEnd()
{
	
	fenID = "portageOK";
	fenIDImg = fenID+"_img";
	fenIDText = fenID+"_txt";
	document.getElementById(fenIDImg).innerHTML="<img src='/assets/ajax-loader_32px.gif' alt=''/>";
	document.getElementById(fenIDText).innerHTML="Analyse Status process";
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  	alert ("Your browser does not support AJAX!");
	  	return;
	  } 
	xmlHttp.onreadystatechange=stateChanged2;
	xmlHttp.open("GET","/process_auto/finProcessAuto.php",true);
	xmlHttp.send(null);
}


function stateChanged1() 
{ 

	if (xmlHttp.readyState==4)
	{ 		
		document.getElementById(fenID).innerHTML=xmlHttp.responseText;
		URLSupp = "";
		if (document.getElementById("nomtable")) {
			// One of the processes needs to be restarted with some parameters because of server TIMEOUT
			numProcess = parseInt(document.getElementById("numproc").value);
			URLSupp = "&table="+document.getElementById("nomtable").value+"&numenreg="+document.getElementById("numID").value;
			relancetrt = true;
		} else {
			relancetrt = false;
		}

			
		switch(numProcess)
		{
			case 1:

				progPhp = "/process_auto/comparaison.php" ;
				nomFen = "comparaison" ;
				nomURL = "action=comp&log="+checkLog+"&numproc="+numProcess+URLSupp+"&exec="+compExec;
				texte = "Comparaison du referentiel / parametrage de reference en cours...";
			  break;    
			case 2:
				progPhp = "/process_auto/comparaison.php" ;
				nomFen = "comparaisonInv" ;
				nomURL = "action=compinv&log="+checkLog+"&numproc="+numProcess+URLSupp+"&exec="+compInvExec;
				texte = "Comparaison du parametrage de BDPECHE en cours...";
			  break;
			case 3:
				progPhp = "/process_auto/comparaison.php" ;
				nomFen = "copieScientifique" ;
				nomURL = "action=majsc&log="+checkLog+"&numproc="+numProcess+URLSupp+"&exec="+majscExec;
				texte = "Copie des donn&eacute;es scientifiques en cours...";
			  break;
			case 4:
				progPhp = "/process_auto/processAuto.php" ;
				nomFen = "processAutoRec" ;
				nomURL = "base="+DBname+"&nb_enr="+nbEnreg+"&adresse="+adresse+"&numproc="+numProcess+"&pg=rec&exec="+recExec;
				texte = "Recalcul automatique des données en cours ...";
			  break;
			case 5:
				progPhp = "/process_auto/processAuto.php" ;
				nomFen = "processAutoStat" ;
				nomURL = "base="+DBname+"&nb_enr="+nbEnreg+"&adresse="+adresse+"&numproc="+numProcess+"&pg=stat&exec="+statExec;
				texte = "Calcul statistique automatique des données en cours ...";
			  break;
			case 6:
				progPhp = "/process_auto/comparaison.php" ;
				nomFen = "copieRecomp" ;
				nomURL = "action=majrec&log="+checkLog+"&numproc="+numProcess+URLSupp+"&exec="+majrecExec;
				texte = "Copie des donn&eacute;es recompos&eacute;es en cours...";
			  break;
			case 7:
			  finTrt = true;
			  break;
			case 8:
			  return;
		}
		if (!finTrt) {
			numProcess++;
			suivant=runProcessNext(progPhp,nomFen,nomURL,texte);
		} else {
			// fin du traitement 
			runProcessEnd();
			return;
		}
		
	}
}
function stateChanged2() 
{ 

	if (xmlHttp.readyState==4)
	{ 		
		document.getElementById(fenID).innerHTML=xmlHttp.responseText;
		runClean("");
	}
}

function runClean(locURL)
{
	
	fenID = "purge";
	fenIDImg = fenID+"_img";
	fenIDText = fenID+"_txt";
	document.getElementById(fenIDImg).innerHTML="<img src='/assets/ajax-loader_32px.gif' alt=''/>";
	document.getElementById(fenIDText).innerHTML="Purge en cours";
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  	alert ("Your browser does not support AJAX!");
	  	return;
	  } 
	xmlHttp.onreadystatechange=stateChanged3;
	if (locURL== "") {
		addURL = "";
	} else {
		addURL = "&"+locURL;
	}
	xmlHttp.open("GET","/process_auto/purgeTable.php?exec="+purgeExec+addURL,true);
	xmlHttp.send(null);
}

function stateChanged3() 
{ 

	if (xmlHttp.readyState==4)
	{ 		
		document.getElementById(fenID).innerHTML=xmlHttp.responseText;
		URLSupp = "";
		if (document.getElementById("nomtable")) {
			// One of the processes needs to be restarted with some parameters because of server TIMEOUT
			numProcess = parseInt(document.getElementById("numproc").value);
			URLSupp = "&table="+document.getElementById("nomtable").value;
			relancetrt = true;
		} else {
			URLSupp ="";
			relancetrt = false;
		}
		if (relancetrt) {
			runClean(URLSupp);
			
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