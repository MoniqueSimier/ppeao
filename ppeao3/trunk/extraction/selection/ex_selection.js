function replaceQueryString(url,param,value) {
    var re = new RegExp("([?|&])" + param + "=.*?(&|$)","i");
    if (url.match(re))
        return url.replace(re,'$1' + param + "=" + value + '$2');
    else
        return url + '&' + param + "=" + value;
}


function goToNextStep(step,url) {
	// step: le numero de l'etape ACTUELLE
	// url: l'url qui doit servir de base pour l'etape suivante
	url=replaceQueryString(url,'step',step+1)+"&"
	var theStepForm=document.getElementById("step_"+step+"_form");
	url+=theStepForm.toQueryString();
	document.location=url;
}

function refreshSystemes(liste_campagnes, liste_enquetes) {

// liste_campagnes: un tableau contenant la liste des id des campagnes deja filtrees
// liste_enquetes: un tableau contenant la liste des id des enquetes deja filtrees
	var paysSelect=$("pays");
	var systemesSelect=$("systemes");
	// si une valeur est sélectionnée
	if ((paysSelect.selectedIndex!=-1)) {
			//debug			alert('valeur sélectionnée');
		var xhr = getXhr();
		// what to do when the response is received
		xhr.onreadystatechange = function(){
			// only do something if the whole response has been received and the server says OK
			if(xhr.readyState == 4 && xhr.status == 200){
				//on récupère la réponse du serveur (les <options> du select)
				theNewOptions = xhr.responseText;
				// debug 	alert(theNewOptions);
				// on remplace le contenu du <select id=systemes>  :
				systemesSelect.innerHTML=theNewOptions;
			;} // end if xhr.readyState == 4
		} // end xhr.onreadystatechange
	
	// on passe les valeurs sélectionnées des SELECT dans l'URL
	var theString="&"+$('step_3_form').toQueryString();

	// using GET to send the request
	xhr.open("GET","/extraction/selection/refresh_systemes_ajax.php?&campagnes="+liste_campagnes+"&enquetes="+liste_enquetes+theString,true);
	xhr.send(null);

	}// end if ((currentSelect.selectedIndex!=-1)
	
	// else if no value is selected, we remove the next criteria select and update the edit link
	else {
		//debug		alert("plus de valeurs");
		systemesSelect.innerHTML='';
		
		;}

	
}