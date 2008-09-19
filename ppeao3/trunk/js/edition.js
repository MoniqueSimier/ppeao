/*
* Fonction qui affiche un SELECT listant les tables de codage suite au choix d'un domaine (pêche, biologie...)
*/

function showCodageTablesSelect(typePeche){
	// typePeche : le type de pêche concerné : 'artisanale' ou 'scientifique'
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
	else {$("codage_"+typePeche+"_tables_select").remove();}
	
}


/**
* Fonction utilisée pour supprimer les DIVs suivants quand on change les valeurs d'un DIV parent
*/
function removedependentSelects(level) {
	
	// uses the "getElements" method from mootools.js
	var theSelects = $('selector_content').getElements('div[id^=select_]');
	//debug 	alert(theSelects);
	var ln=theSelects.length;
	for (var i=0; i<ln; i++) {
		//cuts the id value after "level_" to get the level number
		theLevel=theSelects[i].id.substring(7);
		//debug 		alert('theLevel'+theLevel);
		// removes the div from the dom if it level is higher than the level of the div that was onchanged
		// uses the "remove" method from mootools.js
		if (theLevel>level) {$(theSelects[i].innerHTML='');
		;}
	}
	
}


/*
* Fonction utilisée pour insérer un nouveau <div><select> correspondant à une nouvelle table
*/

function showNewLevel(newLevel,theParentTable) {
	// newLevel: the level of the new div to create (used in <div id="level_n">)
	// theParentTable: la table à partir de laquelle on crée le nouveau select
	level=parseInt(newLevel)-1; // les niveaux sont 1,2,3 etc alors que les tableaux sont indexés à partir de 0
	//debug	alert(level);
	var theLevel='level_'+level;
	var theValues=$(theLevel);
	var select=$(theParentTable);

	
	// on teste d'abord si on n'est pas arrivé à la fin du sélecteur (nouvelle table à ajouter)
	var selecteurLength=$('selector_content').getElements('div[id^=level_]').length;
	// debug	alert('longueur='+selecteurLength+'next level='+newLevel);
	if (newLevel<=selecteurLength) {
	// si une valeur est sélectionnée
	//debug	alert(select.selectedIndex);
	if ((select.selectedIndex!=-1)) {
		//debug			alert('valeur sélectionnée');
		var xhr = getXhr();
		// what to do when the response is received
		xhr.onreadystatechange = function(){
			// only do something if the whole response has been received and the server says OK
			if(xhr.readyState == 4 && xhr.status == 200){
				//on récupère la réponse du serveur (le SELECT)
				theNewDivCode = xhr.responseText;
				// debug				alert(theNewDivCode);
				// on sélectionne le DIV  :
				var theDiv=$('level_'+newLevel);
				theDiv.innerHTML = '';
				theDiv.innerHTML=theNewDivCode;
			;} // end ifxhr.readyState == 4
		} // end xhr.onreadystatechange
	
	// on met à jour le lien "edit_link"
	updateEditLink(level);
	// on appelle cette fonction pour supprimer d'éventuels SELECTs suivants	
	removedependentSelects(parseInt(newLevel)-1);
	// on passe les valeurs sélectionnées des SELECT dans l'URL
	var theString="&"+$('selector_form').toQueryString();
		
	// using GET to send the request
	// on récupère les valeurs des paramètres de l'URL (fonction gup() définie dans basic.js)
	var type=gup('type');
	var hierarchy=gup('hierarchy');
	var targetTable=gup('targetTable');
	xhr.open("GET","addTableSelect_ajax.php?type="+type+"&hierarchy="+hierarchy+"&targetTable="+targetTable+"&level="+newLevel+theString,true);
	xhr.send(null);

	}// end xhr.onreadystatechange
	
	// else if no value is selected, we remove the next criteria select and update the edit link
	else {
		//debug		alert("plus de valeurs");
		removedependentSelects(parseInt(newLevel)-1);
		updateEditLink(level)
		;}
	} // end if 
	else {
		//debug		alert('on ne fait rien');
	// si on est à la fin du sélecteur, on se contente de mettre à jour le lien edit_link
	updateEditLink(level);
	}
}

/**
* Fonction qui met à jour le lien permettant d'éditer une table ou ses valeurs sous chaque sélecteur
*/
function updateEditLink(level) {
	// level : le niveau du sélecteur pour lequel mettre le lien à jour
	
	// on sélectionne le lien à mettre à jour
	var theLink=$('editlink_'+level);
	var theLevel=$('select_'+level);
	var theSelect=theLevel.firstChild;
	var targetTable=gup('targetTable');
	// on récupère ls valeurs des tables déjà sélectionnées
	var theSelection='';
	for (var i = 1; i <= level; i++) {theSelection+='&'+$('select_'+i).toQueryString();}
	//debug 	alert(theSelection);
	// on récupère le nom de la table
	var editTable=theSelect.name.replace("[]","");
	// on récupère les valeurs sélectionnées dans theSelect et on en faire une chaine pour URL
	var selectedValues=getMultipleSelect(theSelect);	
	// on met à jour le lien
	// si aucune valeur n'est sélectionnée on insère un lien "éditer la table"
	if (selectedValues=='') {
		var theUrl= "/edition/edition_table.php?type="+gup('type')+"&hierarchy="+gup('hierarchy')+"&targetTable="+gup('targetTable')+"&editTable="+editTable+theSelection;
		var theLinkText="&eacute;diter la table";
		}
	// si au moins une valeur est sélectionnée on insère un lien "éditer la sélection"
	else {
		var theUrl= "/edition/edition_table.php?type="+gup('type')+"&hierarchy="+gup('hierarchy')+"&targetTable="+gup('targetTable')+"&editTable="+editTable+theSelection;
		var theLinkText="&eacute;diter la s&eacute;lection";
		;}
		
	// on change le contenu de la balise <p id="editlink_n">
	theLink.innerHTML='<a id="edita_'+level+'" class="link_button" href="'+theUrl+'">'+theLinkText+'</a>';
	
	// on attire l'attention de l'utilisateur sur le fait que le lien a changé
	theEditA=$("edita_"+level);
	var backgroundChange = new Fx.Style(theEditA, 'background-color', {duration:1500, transition: Fx.Transitions.linear,onComplete:function() {theEditA.setStyle('background-color','');}});
	
	backgroundChange.start('#FF8000','#FFF');
	

}


/**
* Fonction qui permet de sélectionner ou désélectionner toutes les valeurs d'un SELECT dans le sélecteur de tables PPEAO
*/
function toggleSelect(level,select,what) {
	// level : le niveau du SELECT dans le sélecteur de tables PPEAO
	// select : l'id du SELECT à utiliser
	// what : que sélectionner ('all' ou 'none')
	
	// on sélectionne ou désélectionne les OPTIONS du SELECT
	toggleSelectSelection(select,what);
	
	
	// on provoque le rafraichissement des éventuels SELECT suivants dans le sélecteur de tables
	var theLevel=parseInt(level)+1;
	showNewLevel(theLevel,select);
}

/**
* Fonction qui permet de filtrer la table à éditer
*/
function filterTable(theUrl) {
	// the Url : l'URL de la page courante (sans les paramètres de pagination ou de filtre)
	// cette fonction retourne une Url composée de theUrl et des paramètres de filtre, et redirige la page courante
	// vers cette Url
	
	// l'URL de redirection
	var newUrl=theUrl;
	//debug 	alert(newUrl);
	
	// on sélectionne tous les champs du filtre 
	var theParams=$('la_table').getElements('.filterField');
		
	// pour chaque champ, si une valeur non nulle est sélectionnée, on l'ajoute à l'url newUrl
	
	var ln=theParams.length;
	for (var i=0; i<ln; i++) {
		// si on a affaire à un input
		theElement=theParams[i];
		//debug alert(theElement.nodeName);
		if (theParams[i].nodeName=='INPUT') {
			if (theParams[i].value!='') {newUrl+='&'+theParams[i].name+'='+theParams[i].value;}
		}
		//si on a affaire à un select
		if (theParams[i].nodeName=='SELECT') {
			if (theParams[i].selectedIndex!=0) {newUrl+='&'+theParams[i].name+'='+theParams[i].value;}
		}
	}
	
	//debug 	alert(newUrl);
	document.location=newUrl;
	
}
/**
* Cette fonction déclenche filterTable() lorsque l'utilisateur appuie sur la touche ENTER
*/
function filterTableOnEnter(theUrl) {
// permet de déclencher le filtrage de la table quand l'utilisateur entre une valeur dans un <input text> et appuie sur ENTER
	if(event.keyCode == 13) {filterTable(theUrl);}
}


/**
* Fonction permettant de limiter le nombre de caractères saisis dans un élément TEXTAREA
*/

function fieldTextLimiter(field,cntspan,maxlimit) {
// field : le champ de formulaire à controler
// cntspan : le <span> dans lequel on affiche le compteur de caractères restants
// maxlimit : le nombre maximum de caractères 
if (field.value.length > maxlimit) // si c'est trop long, on coupe!
field.value = field.value.substring(0, maxlimit);
// sinon on met à jour le compteur de caractères restants
else 
cntspan.innerHTML = (maxlimit - field.value.length)+' caract&egrave;res restants';

}