/*
* Fonction qui affiche un SELECT listant les tables de codage suite au choix d'un domaine (pêche, biologie...)
*/

function showCodageTablesSelect(typePeche){
	// typePeche : le type de pêche concerné : 'experimentale' ou 'scientifique'
	//alert(typePeche);
	var theDomainSelect=$("codage_"+typePeche+"_select");
	// si une valeur autre que "-choisissez un domaine-" est sélectionnée
	// on insère un SELECT avec la liste des tables correspondantes
	if ((theDomainSelect.selectedIndex!=0)) {
	
	// on initialise l'objet AJAX	
	var xhr = getXhr();
	// what to do when the response is received
	xhr.onreadystatechange = function(){
		// only do something if the whole response has been received and the server says OK
		if(xhr.readyState == 4 && xhr.status == 200){
			theCodageTablesSelect = xhr.responseText;
			// checks whether the newlevel div already exists or not
			if (document.getElementById("codage_"+typePeche+"_tables_select")) {
				// if so, replaces its content
				document.getElementById("codage_"+typePeche+"_tables_select").innerHTML=theCodageTablesSelect;
				}
			else {
			// if not, creates a new div
			
			// we need to use the *&*$# createNamedElement custom function to create a named element in $#@*&* Internet Explorer
			var newDiv=createNamedElement("div", "codage_"+typePeche+"_tables_select")
			newDiv.id="codage_"+typePeche+"_tables_select";
			//this is to fix IE problem with newDiv.setAttribute("class","level");
			newDiv.className = "table_select";
			// using innerHTML to fill the SELECT
			newDiv.innerHTML = theCodageTablesSelect;
			// for juxtaposed divs
			// on ajoute le nouveau DIV à la fin du DIV "peche_"
			document.getElementById("peche_"+typePeche).appendChild(newDiv);
			}			
			
		}  
	}

	// using GET to send the request
	xhr.open("GET","/edition/edition_codage_ajax.php?domaine="+theDomainSelect.value,true);
	xhr.send(null);}
	
	// "else" si la valeur  "-choisissez un domaine-" est sélectionnée, on efface le SELECT des tables
	else {$("codage_"+typePeche+"_tables_select").dispose();}
	
}