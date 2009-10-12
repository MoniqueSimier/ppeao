function goToNextStep(current_step,url) {
	// current_step: le numero de l'etape ACTUELLE
	// url: l'url qui doit servir de base pour l'etape suivante
	url=replaceQueryString(url,'step',current_step+1);
	var theStepForm=document.getElementById("step_"+current_step+"_form");
	var formValues=theStepForm.toQueryString();
	if (formValues=='') {var separator='';} else {var separator='&';}
	url=url+separator+formValues;
	
	//debug	alert(url);
	
	document.location=url;
}

// fonction qui effectue une requete AJAX pour mettre a jour le selecteur de systemes quand on change le pays selectionne
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

// fonction qui effectue une requete AJAX pour mettre a jour le selecteur de secteurs quand on change le systeme selectionne
function refreshSecteurs(liste_enquetes) {
// liste_enquetes: un tableau contenant la liste des id des enquetes deja filtrees
	var systemes2Select=$("systemes2");
	var secteursSelect=$("secteurs");
	// si une valeur est sélectionnée
	if ((systemes2Select.selectedIndex!=-1)) {
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
				secteursSelect.innerHTML=theNewOptions;
			;} // end if xhr.readyState == 4
		} // end xhr.onreadystatechange
	
	// on passe les valeurs sélectionnées des SELECT dans l'URL
	var theString="&"+$('step_7_form').toQueryString();

	// using GET to send the request
	xhr.open("GET","/extraction/selection/refresh_secteurs_ajax.php?&enquetes="+liste_enquetes+theString,true);
	xhr.send(null);

	}// end if ((currentSelect.selectedIndex!=-1)
	
	// else if no value is selected, we remove the next criteria select and update the edit link
	else {
		//debug		alert("plus de valeurs");
		secteursSelect.innerHTML='';
		
		;}
}

// fonction qui effectue une requete AJAX pour mettre a jour le selecteur de periode quand on change une des selections
function refreshPeriode(selection,debut_annee,debut_mois,fin_annee,fin_mois) {

// element : quelle selection a change
// valeurs possibles : d_a pour annee de debut, d_m pour mois de debut, f_a pour annee de fin
// debut_annee : annee de debut de la periode complete 
// debut_mois : mois de debut de la periode complete
//fin_annee : annee de fin de la periode complete
// fin_mois : mois de fin de la periode complete
	// quel est le <select>  concerne?
	var theSelect=$(selection);
	// quelle est la valeur selectionnee?
	var theValue=theSelect.value;
	var theResponseText="";
	
	//debug 	alert(theValue);
	
	// si la valeur selectionnee est -1 on ne fait rien
	if (theValue!=-1) {
	// on declare les variables
	var d_a=""; var d_m=""; var f_a=""; var f_m="";
	
	if (selection=='d_a') {d_a=theValue;}
	if (selection=='d_m') {d_a=$("d_a").value;d_m=theValue}
	if (selection=='f_a') {d_a=$("d_a").value; d_m=$("d_m").value;f_a=theValue;}
	if (selection=='f_m') {d_a=$("d_a").value; d_m=$("d_m").value;f_a=$("f_a").value;f_m=theValue;}


		//debug			alert('valeur sélectionnée');
		var xhr = getXhr();
		// what to do when the response is received
		xhr.onreadystatechange = function(){
			// only do something if the whole response has been received and the server says OK
			if(xhr.readyState == 4 && xhr.status == 200){
				//on récupère la réponse du serveur (les <options> du select)
				theResponseText = xhr.responseText;
				// debug 	alert(theResponseText);
				// on remplace le <select> concerne et on efface les suivants  :
				if (selection=='d_a') 
					{$("div_d_m").innerHTML=theResponseText; $("div_f_a").innerHTML=""; $("div_f_m").innerHTML="";}
				if (selection=='d_m') 
					{$("div_f_a").innerHTML=theResponseText;  $("div_f_m").innerHTML="";}
				if (selection=='f_a') 
					{$("div_f_m").innerHTML=theResponseText;}
				// cas particulier: si on vient de choisir un mois de fin, on affiche le lien pour passer a la suite
				if (selection=='f_m') {
					if (!$("step_4_link")) {
					var link= new Element('p', {
						'events': {
							'change':function() {
								refreshPeriode('f_m','','','','');
								}
						},
						'class':'clear',
						'id':'step_4_link'
					}
					);
					
					link.injectInside($("step_4"));
					link.innerHTML=theResponseText;
					}
				}
			;} // end if xhr.readyState == 4
		} // end xhr.onreadystatechange
	
	// on passe les valeurs sélectionnées des SELECT dans l'URL
	var theString="&"+$('step_4_form').toQueryString();
	
	//debug	alert(theString);

	// using GET to send the request
	xhr.open("GET","/extraction/selection/refresh_periode_ajax.php?selection="+selection+"&d_a="+d_a+"&d_m="+d_m+"&f_a="+f_a+"&debut_annee="+debut_annee+"&debut_mois="+debut_mois+"&fin_annee="+fin_annee+"&fin_mois="+fin_mois,true);
	xhr.send(null);

	}
}
