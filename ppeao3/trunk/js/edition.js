/*
* Fonction qui affiche un SELECT listant les tables de parametrage suite au choix d'un domaine (p�che, biologie...)
*/

function showCodageTablesSelect(typePeche){
	// typePeche : le type de p�che concern� : 'artisanale' ou 'scientifique'
	//alert(typePeche);
	var theDomainSelect=$("codage_"+typePeche+"_select");
	// si une valeur autre que "-choisissez un domaine-" est s�lectionn�e
	// on ins�re un SELECT avec la liste des tables correspondantes
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
			// on ajoute le nouveau DIV � la fin du DIV "peche_"
			document.getElementById("peche_"+typePeche).appendChild(newDiv);
			}			
			
		}  
	}

	// using GET to send the request
	xhr.open("GET","/edition/edition_codage_ajax.php?domaine="+theDomainSelect.value,true);
	xhr.send(null);}
	
	// "else" si la valeur  "-choisissez un domaine-" est s�lectionn�e, on efface le SELECT des tables
	else {$("codage_"+typePeche+"_tables_select").remove();}
	
}


/**
* Fonction utilis�e pour supprimer les DIVs suivants quand on change les valeurs d'un DIV parent
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
* Fonction utilis�e pour ins�rer un nouveau <div><select> correspondant � une nouvelle table
*/

function showNewLevel(newLevel,theParentTable) {
	// newLevel: the level of the new div to create (used in <div id="level_n">)
	// theParentTable: la table � partir de laquelle on cr�e le nouveau select
	level=parseInt(newLevel)-1; // les niveaux sont 1,2,3 etc alors que les tableaux sont index�s � partir de 0
	//debug		alert(level);
	var theLevel='level_'+level;
	var theValues=$(theLevel);
	var select=$(theParentTable);

	
	// on teste d'abord si on n'est pas arriv� � la fin du s�lecteur (nouvelle table � ajouter)
	var selecteurLength=$('selector_content').getElements('div[id^=level_]').length;
	// debug	alert('longueur='+selecteurLength+'next level='+newLevel);
	if (newLevel<=selecteurLength) {
	// si une valeur est s�lectionn�e
	//debug	alert(select.selectedIndex);
	if ((select.selectedIndex!=-1)) {
		//debug			alert('valeur s�lectionn�e');
		var xhr = getXhr();
		// what to do when the response is received
		xhr.onreadystatechange = function(){
			// only do something if the whole response has been received and the server says OK
			if(xhr.readyState == 4 && xhr.status == 200){
				//on r�cup�re la r�ponse du serveur (le SELECT)
				theNewDivCode = xhr.responseText;
				// debug				alert(theNewDivCode);
				// on s�lectionne le DIV  :
				var theDiv=$('level_'+newLevel);
				theDiv.innerHTML = '';
				theDiv.innerHTML=theNewDivCode;
			;} // end ifxhr.readyState == 4
		} // end xhr.onreadystatechange
	
	// on met � jour le lien "edit_link"
	updateEditLink(level);
	// on appelle cette fonction pour supprimer d'�ventuels SELECTs suivants	
	removedependentSelects(parseInt(newLevel)-1);
	// on passe les valeurs s�lectionn�es des SELECT dans l'URL
	var theString="&"+$('selector_form').toQueryString();
		
	// using GET to send the request
	// on r�cup�re les valeurs des param�tres de l'URL (fonction gup() d�finie dans basic.js)
	var targetTable=gup('targetTable');
	var editTable=gup('editTable');
	xhr.open("GET","addTableSelect_ajax.php?&parentTable="+theParentTable+"&editTable="+editTable+"&targetTable="+targetTable+"&level="+newLevel+theString,true);
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
	// si on est � la fin du s�lecteur, on se contente de mettre � jour le lien edit_link
	updateEditLink(level);
	}
}

/**
* Fonction qui met � jour le lien permettant d'�diter une table ou ses valeurs sous chaque s�lecteur
*/
function updateEditLink(level) {
	// level : le niveau du s�lecteur pour lequel mettre le lien � jour
	
	// on s�lectionne le lien � mettre � jour
	var theLink=$('editlink_'+level);
	var theLevel=$('select_'+level);
	var theSelect=theLevel.firstChild;
	var targetTable=gup('targetTable');
	// on r�cup�re les valeurs des tables d�j� s�lectionn�es
	var theSelection='';
	for (var i = 1; i <= level; i++) {theSelection+='&'+$('select_'+i).toQueryString();}
	//debug 	alert(theSelection);
	// on r�cup�re le nom de la table
	var editTable=theSelect.name.replace("[]","");
	// on r�cup�re les valeurs s�lectionn�es dans theSelect et on en faire une chaine pour URL
	var selectedValues=getMultipleSelect(theSelect);	
	// on met � jour le lien
	// si aucune valeur n'est s�lectionn�e on ins�re un lien "�diter la table"
	if (selectedValues=='') {
		var theUrl= "/edition/edition_table.php?targetTable="+gup('targetTable')+"&editTable="+editTable+theSelection;
		var theLinkText="&eacute;diter la table";
		}
	// si au moins une valeur est s�lectionn�e on ins�re un lien "�diter la s�lection"
	else {
		var theUrl= "/edition/edition_table.php?targetTable="+gup('targetTable')+"&editTable="+editTable+theSelection;
		var theLinkText="&eacute;diter la s&eacute;lection";
		;}
		
	// on change le contenu de la balise <p id="editlink_n">
	theLink.innerHTML='<a id="edita_'+level+'" class="link_button" href="'+theUrl+'">'+theLinkText+'</a>';
	
	// on attire l'attention de l'utilisateur sur le fait que le lien a chang�
	theEditA=$("edita_"+level);
	var backgroundChange = new Fx.Style(theEditA, 'background-color', {duration:1500, transition: Fx.Transitions.linear,onComplete:function() {theEditA.setStyle('background-color','');}});
	
	backgroundChange.start('#FF8000','#FFF');
	

}


/**
* Fonction qui permet de s�lectionner ou d�s�lectionner toutes les valeurs d'un SELECT dans le s�lecteur de tables PPEAO
*/
function toggleSelect(level,select,what) {
	// level : le niveau du SELECT dans le s�lecteur de tables PPEAO
	// select : l'id du SELECT � utiliser
	// what : que s�lectionner ('all' ou 'none')
	
	// on s�lectionne ou d�s�lectionne les OPTIONS du SELECT
	toggleSelectSelection(select,what);
	
	
	// on provoque le rafraichissement des �ventuels SELECT suivants dans le s�lecteur de tables
	var theLevel=parseInt(level)+1;
	showNewLevel(theLevel,select);
}

/**
* Fonction qui permet de filtrer la table � �diter
*/
function filterTable(theUrl) {
	// the Url : l'URL de la page courante (sans les param�tres de pagination ou de filtre)
	// cette fonction retourne une Url compos�e de theUrl et des param�tres de filtre, et redirige la page courante
	// vers cette Url
	
	// l'URL de redirection
	var newUrl=theUrl;
	//debug 	alert(newUrl);
	
	// on s�lectionne tous les champs du filtre 
	var theParams=$('la_table').getElements('.filter_field');
		
	// pour chaque champ, si une valeur non nulle est s�lectionn�e, on l'ajoute � l'url newUrl
	
	var ln=theParams.length;
	for (var i=0; i<ln; i++) {
		// si on a affaire � un input
		theElement=theParams[i];
		//debug alert(theElement.nodeName);
		if (theParams[i].nodeName=='INPUT') {
			if (theParams[i].value!='') {newUrl+='&'+theParams[i].name+'='+theParams[i].value;}
		}
		//si on a affaire � un select
		if (theParams[i].nodeName=='SELECT') {
			if (theParams[i].selectedIndex!=0) {newUrl+='&'+theParams[i].name+'='+theParams[i].value;}
		}
	}
	
	//debug 		alert(newUrl);
	document.location=newUrl;
	
}


/**
* Fonction permettant de limiter le nombre de caract�res saisis dans un �l�ment TEXTAREA
*/

function fieldTextLimiter(field,cntelement,maxlimit) {
// field : le champ de formulaire � controler
// cntspan : l'�l�ment dans lequel on affiche le compteur de caract�res restants
// maxlimit : le nombre maximum de caract�res 
if (field.value.length > maxlimit) // si c'est trop long, on coupe!
field.value = field.value.substring(0, maxlimit);
// sinon on met � jour le compteur de caract�res restants
else 
cntelement.innerHTML = (maxlimit - field.value.length)+' caract&egrave;re(s) restant(s)';

}

/**
* Fonction qui rend une valeur �ditable dans le module d'�dition des tables
*/
function makeEditable(table,column,record,action) {
// table : la table concern�e (son pointeur dans le tableau $tablesDefinitions)
// column : la colonne concern�e (son nom dans la base)
// record : l'identifiant unique de l'enregistrement concern�
// action : l'action � faire (edit/save/cancel)
	
	//debug	alert(record);
	
// la cellule concern�e
var theCell=$("edit_cell_"+column+"_"+record);
	
// on initialise l'objet AJAX	
var xhr = getXhr();
// what to do when the response is received
xhr.onreadystatechange = function(){
		// while waiting for the response, display the loading animation
	var theLoader=' <div align="center"><img src="/assets/ajax-loader.gif" alt="en cours..." title="en cours..." valign="center"/></div>';
	if(xhr.readyState < 4) { theCell.innerHTML = theLoader;}
	// only do something if the whole response has been received and the server says OK
	if(xhr.readyState == 4 && xhr.status == 200){
		theCellContent = xhr.responseText;
		theCell.innerHTML=theCellContent;
		// si on passe en mode �dition, on met toute la ligne <tr> en surbrillance
		var theRow=$("row_"+record); // la ligne
		if (action=='edit') {
			theRow.addClass("edit_field_row");
		}
		else {
			theRow.removeClass("edit_field_row");
		}
	}  
} // end xhr.onreadystatechange

// using GET to send the request
xhr.open("GET","/edition/edition_rendre_editable_ajax.php?editAction="+action+"&editTable="+table+"&editColumn="+column+"&editRecord="+record,true);
xhr.send(null);	
}


/**
* Fonction qui v�rifie la valeur saisie et la stocke dans la base si elle est valide (dans le module d'�dition des tables)
*/
function saveChange(table,column,record) {
// table : la table concern�e (son pointeur dans le tableau $tablesDefinitions)
// column : la colonne concern�e (son nom dans la base)

// record : l'identifiant unique dans la table de l'enregistrement concern�


// la cellule concern�e
var theCell=$("edit_cell_"+column+"_"+record);
// le div contenant les boutons d'enregistrement/annulation
//debug alert("edit_buttons_"+column+"_"+record)
var theEditButtons=$("edit_buttons_"+column+"_"+record);
var theEditButtonsContent=theEditButtons.innerHTML;
// le champ concern�
var theField=$("e_"+column+"_"+record);
var newValue=theField.value;
//debug alert (newValue);


// on initialise l'objet AJAX	
var xhr = getXhr();
// what to do when the response is received
xhr.onreadystatechange = function(){
		// en attendant la r�ponse, on remplace les boutons d'enregistrement/annulation par un loader
	var theLoader=' <div align="center"><img src="/assets/ajax-loader.gif" alt="validation en cours..." title="validation en cours..." valign="center"/></div>';
	if(xhr.readyState < 4) { theEditButtons.innerHTML = theLoader;}
	// only do something if the whole response has been received and the server says OK
	if(xhr.readyState == 4 && xhr.status == 200){
		var theResponseNode = xhr.responseXML.documentElement;
		var isValid=theResponseNode.attributes.getNamedItem("valid").value;
		
		//debug		alert(isValid);
		
		// si la valeur soumise est non valide, on affiche un message d'erreur
		if (isValid=='invalid') {
			// note : le firstChild est l� car le contenu de l'�l�ment <responseContent> est un TEXTNODE
			var theMessage=theResponseNode.getElementsByTagName('responseContent')[0].firstChild.nodeValue.replace(/^\[CDATA\[/,'')
			.replace(/\]\]$/,'');
			//debug			alert(theMessage);
			// si le message d'erreur existe d�j�, on le remplace
			if ($('e_'+column+'_'+record+'_error')) {$('e_'+column+'_'+record+'_error').innerHTML=theMessage} else {
			// sinon cr�e l'�l�ment pour afficher le message d'erreur
			var theMessageDiv= new Element('div', {'id':'e_'+column+'_'+record+'_error','class' : 'small error'});
			theMessageDiv.innerHTML=theMessage;
			// on injecte l'objet contenant le message
			theMessageDiv.injectInside(theCell);
			} // end if $('e_'+column+'_'+record+'_error')
			// on restaure les boutons enregistrer/annuler
			theEditButtons.innerHTML=theEditButtonsContent;
		}

		// sinon, on affiche le champ avec la nouvelle valeur
		else {
			var theNewCellContent=theResponseNode.getElementsByTagName('responseContent')[0].firstChild.nodeValue.replace(/^\[CDATA\[/,'')
			.replace(/\]\]$/,'');
			theCell.innerHTML=theNewCellContent;
			// on remet le style de la ligne �dit�e � sa valeur initiale
			var theRow=$("row_"+record); // la ligne
			theRow.removeClass("edit_field_row");
		}
		
	}  
} // end xhr.onreadystatechange

//debug alert("/edition/edition_enregistrer_modification_ajax.php?&editTable="+table+"&editColumn="+column+"&editRecord="+record+"&newValue="+newValue+"&oldValue="+oldValue);

// using GET to send the request
xhr.open("GET","/edition/edition_enregistrer_modification_ajax.php?&editTable="+table+"&editColumn="+column+"&editRecord="+record+"&newValue="+escape(newValue),true);
xhr.send(null);	

}

/**
* Fonction qui bascule la visibilit� d'un �lement entre affich� et masqu�
* theHideClass : le nom de la classe permettant de masquer l'el�ment
* theElementId : attribut id de l'�l�ment DOM � afficher/masquer
* theLinkId : attribut id de l'�l�ment DOM qui contient le lien permettant d'afficher/masquer
* theDisplayText : texte � afficher dans le lien quand l'�l�ment est masqu�
* theHideText : texte � afficher dans le lien quand l'�l�ment est affich�

*/
function toggleElementVisibility(theHideClass,theElementId,theDisplayLinkId,theHideLinkId,theDisplayText,theHideText) {
	var theElement=$(theElementId);
	theElement.toggleClass(theHideClass);
	var theDisplayLink=$(theDisplayLinkId);
	var theHideLink=$(theHideLinkId);
	if (theElement.hasClass(theHideClass)) {
	theDisplayLink.innerHTML=theDisplayText;
	theHideLink.innerHTML='';}
	else {
	theHideLink.innerHTML=theHideText;
	theDisplayLink.innerHTML='';	
	}
}
	
	
/**
* Fonction qui affiche la fenetre de dialogue modale pour ajouter un nouvel enregistrement
* table : la table � laquelle on veut ajouter un enregistrement
*/
function modalDialogAddRecord(theLevel,theTable) {
		
	// on cr�e le nouvel �l�ment
	
	var theOverlay=new Element ('div', {
		'id': "overlay_"+theLevel,
		'class': "overlay",
		'style': "z-index:"+theLevel*1000,
	}
	);
	
	var theOverlayWindow= new  Element ('div', {
		'id': "overlay_"+theLevel+"_window",
		'class': "overlay_window",
	}
	);
	
	var theOverlayContent= new  Element ('div', {
		'id': "overlay_"+theLevel+"_content",
		'class': "overlay_content",
	}
	);
		
	var theOverlayButtons= new  Element ('div', {
		'id': "overlay_"+theLevel+"_buttons",
		'class': "overlay_buttons",
	}
	);
	
	var theOverlayLoaderDiv =new  Element ('div', {
		'id': "overlay_"+theLevel+"_loader",
		'class': "overlay_loader",
	}
	); 
	
	theOverlayButtons.innerHTML='<a id="overlay_'+theLevel+'_close" href="#" onclick="javascript:modalDialogClose(\'overlay_'+theLevel+'\',\'\')" class="small link_button">annuler</a>';
	
	
	theOverlay.injectInside($E('body'));
	theOverlayWindow.injectInside(theOverlay);
	theOverlayLoaderDiv.injectInside(theOverlayWindow);
	theOverlayContent.injectInside(theOverlayWindow);
	theOverlayButtons.injectInside(theOverlayWindow);
	
	
	// on initialise l'objet AJAX	
	var xhr = getXhr();
	// what to do when the response is received
	xhr.onreadystatechange = function(){
			// en attendant la r�ponse, on remplace les boutons d'enregistrement/annulation par un loader
		var theLoader='<div align="center" id="the_loader_'+theLevel+'"><img src="/assets/ajax-loader.gif" alt="chargement en cours..." title="chargement en cours..." valign="center"/></div>';
		if(xhr.readyState < 4) { theOverlayContent.innerHTML = theLoader;}
		// only do something if the whole response has been received and the server says OK
		if(xhr.readyState == 4 && xhr.status == 200){
			var theResponseText = xhr.responseText;
			
			// on affiche les champs de saisie pour le nouvel enregistrement
			//debug			alert(theResponseText);
			
			theOverlayContent.innerHTML=theResponseText;
			
			// on affiche le bouton "enregistrer"			
			var theSaveButton=new Element('a', {
			    'class': 'small link_button',
			    'href': '#',
				'id': "overlay_"+theLevel+"_save",
				'onclick': 'sendRecordToSave(\'add_record_'+theLevel+'_form\',\'add_field\','+theLevel+',\''+theTable+'\')',
			});
			theSaveButton.innerHTML="enregistrer";
			theSaveButton.injectBefore("overlay_"+theLevel+"_close");
			
			// on met � jour la hauteur de l'overlay au cas o� le dialogue soit plus haut que l'�cran
			// si on ne fait pas �a, l'overlay ne couvre qu'une partie de la page
			window.addEvent('domready', function(){
			// il faudrait trouver comment declarer correctement la hauteur de l'overlay
			//	var theHeight=$E('body').height;
			//	alert(theHeight);
				$('overlay'+"_"+theLevel).setStyle('height','100%');
				});
			
			}  
	} // end xhr.onreadystatechange

	// using GET to send the request
	xhr.open("GET","/edition/edition_ajouter_enregistrement_ajax.php?&editTable="+theTable+"&level="+theLevel,true);
	xhr.send(null);
}


/**
* Fonction qui ferme la fenetre de dialogue modale 
*/
function modalDialogClose(theDialogOverlay,refresh) {
	//theDialogOverlay : id de l'�l�ment "overlay"
	// refresh : si "refresh", on force le refresh de la page
	$(theDialogOverlay).remove();
	if (refresh=='refresh') {
		window.location.reload(true);
	}
}

/**
* Fonction pour enregistrer les valeurs lors de l'ajout d'un nouvel enregistrement
*/
function sendRecordToSave(theFormId,theFormFieldClass,theLevel,theTable) {
 var theUrl=formToUrl(theFormId,theFormFieldClass);

//debug alert(theUrl);

var theSaveButton=$('overlay_'+theLevel+'_save');
var theLoader=$("overlay_"+theLevel+"_loader");
// on initialise l'objet AJAX	
var xhr = getXhr();
// what to do when the response is received
xhr.onreadystatechange = function(){
		
	if(xhr.readyState < 4) {
		theSaveButton.setStyle("visibility","hidden");
		// en attendant la r�ponse, on remplace les boutons d'enregistrement/annulation par un loader
		theLoader.innerHTML='<img src="/assets/ajax-loader.gif" alt="validation en cours..." title="validation en cours..." valign="center"/>';
	}
	// only do something if the whole response has been received and the server says OK
	if(xhr.readyState == 4 && xhr.status == 200){
		theLoader.innerHTML='';
		var theResponseNode = xhr.responseXML.documentElement;
		var theNodes=theResponseNode.childNodes;
		// la validit� des valeurs saisies
		var isValid=theResponseNode.attributes.getNamedItem("validity").value;
		//debug alert(theResponseNode.attributes.getNamedItem("validity").value);
		// si la saisie n'est pas valide, on r�active le bouton enregistrer
		if (isValid=='invalid') {
			theSaveButton.setStyle("visibility","visible");
			// et on affiche les messages d'erreur sous chaque champ non valide
			for (var i=0; i<theNodes.length; i++) {
				var theNode=theNodes[i];
				var theKey=theNode.attributes.getNamedItem("key").value;
				var theValue=$('add_record_'+theLevel+'_'+theKey).value;
				var theValidity=theNode.attributes.getNamedItem("valid").value;
				if (theNode.firstChild) {
					var theMessage=theNode.firstChild.nodeValue.replace(/^\[CDATA\[/,'').replace(/\]\]$/,'');
					}
				
				// si la valeur n'est pas valide et que on a un message d'erreur
				if (theValidity==0 && theMessage!='') {
					//debug alert(theMessage);
					if (theError=$('add_record_'+theLevel+'_'+theKey+'_error')) {
						theError.innerHTML=theMessage;
						} 
					else {
						var theError=new Element('div',{
						'id' : 'add_record_'+theLevel+'_'+theKey+'_error',
						'class' : 'small error'
						}
						);
						theError.innerHTML=theMessage;
						theError.injectAfter('add_record_'+theLevel+'_'+theKey);
					} // end else theError
				} // end if theValidity==0
				
				} // end for
				
				
			} // end if isValid=='invalid'
			else {
				// on change le texte du <h1>
				var theH1=$('overlay_'+theLevel).getElement('h1');
				theConfirmation='enregistrement ajout� dans la table "'+theResponseNode.attributes.getNamedItem("table").value+'"';
				theH1.innerHTML=theConfirmation;
				for (var i=0; i<theNodes.length; i++) {
					var theNode=theNodes[i];
					var theKey=theNode.attributes.getNamedItem("key").value;
					// on traite diff�remment les valeurs "normales" et les "sequences"
					if (theNode.attributes.getNamedItem("sequence").value=="sequence") {
						var theValue=$('add_record_'+theLevel+'_'+theKey).innerHTML;
					} else 
					{
						var theValue=$('add_record_'+theLevel+'_'+theKey).value;
					}
					var theValidity=theNode.attributes.getNamedItem("valid").value;
					//debug					alert(theKey+':'+theValue);
					// on enl�ve le message d'erreur si il existe
					if ($('add_record_'+theLevel+'_'+theKey+'_error')) {$('add_record_'+theLevel+'_'+theKey+'_error').remove();}
					// on cr�e un nouvel �l�ment contenant seulement la valeur � afficher
					var theNewElement=new Element('div',{
						'class' : 'small',
						'id' : 'add_record_'+theLevel+'_'+theKey,
					}
					);
					theNewElement.innerHTML=theValue;
					// on s�lectionne l'�l�ment de formulaire qu'il doit remplacer
					//debug alert('a_'+theKey);
					var theOldElement=$('add_record_'+theLevel+'_'+theKey);
					// on remplace l'ancien �l�ment par le nouveau
					theOldElement.replaceWith(theNewElement);
					// on efface les �ventuels �l�ments de message ou les selecteurs en cascade
					// message d'erreur de validation
					if ($('add_record_'+theLevel+'_'+theKey+'_error')) {$('add_record_'+theLevel+'_'+theKey+'_error').remove();};
					// compteur du nombre de caracteres restants
					if ($('add_record_'+theLevel+'_'+theKey+'_counter'))
					{$('add_record_'+theLevel+'_'+theKey+'_counter').remove();};
					// selecteur en cascade
					if ($('add_record_'+theLevel+'_'+theKey+'_foreign_key_cascade'))
					{$('add_record_'+theLevel+'_'+theKey+'_foreign_key_cascade').remove();};
				}// end for
				
			// maintenant, on change le comportement du bouton "fermer"
			var theCloseButton=$('overlay_'+theLevel+'_close');
			var over='overlay_'+theLevel;
			theCloseButton.setProperty("onclick","javascript:modalDialogClose(\'"+over+"\',\'refresh\')");
			theCloseButton.innerHTML='fermer';
			} // end else isValid
		} // end if xhr.readyState == 4 && xhr.status == 200
	
} // end xhr.onreadystatechange


// using GET to send the request
xhr.open("GET","/edition/edition_ajouter_enregistrement_validation_ajax.php?&table="+theTable+"&level="+theLevel+theUrl,true);
xhr.send(null);

}


/**
* Fonction qui affiche la fenetre de dialogue modale pour supprimer un enregistrement
* theTable : la table dont on veut supprimer un enregistrement
* theRecord : l'identifiant unique de l'enregistrement � supprimer
* theLevel  : le niveau de l'overlay (utilie si on veut afficher plusieurs overlays les uns devant les autres)
*/
function modalDialogDeleteRecord(theLevel,theTable,theRecord) {
		
	// on cr�e le nouvel �l�ment
	
	var theOverlay=new Element ('div', {
		'id': "overlay_"+theLevel,
		'class': "overlay",
		'style': "z-index:"+theLevel*1000,
	}
	);
	
	var theOverlayWindow= new  Element ('div', {
		'id': "overlay_"+theLevel+"_window",
		'class': "overlay_window",
	}
	);
	
	var theOverlayContent= new  Element ('div', {
		'id': "overlay_"+theLevel+"_content",
		'class': "overlay_content",
	}
	);
	theOverlayContent.innerHTML='<div align="center"><h1>supprimer l&#x27;enregistrement &quot;'+theRecord+'&quot;</h1><h2>recherche des enregistrements utilisant l&#x27;enregistrement &agrave; supprimer comme cl&eacute; &eacute;trang&egrave;re</h2></div>';
		
	var theOverlayButtons= new  Element ('div', {
		'id': "overlay_"+theLevel+"_buttons",
		'class': "overlay_buttons",
	}
	);
	
	var theOverlayLoaderDiv =new  Element ('div', {
		'id': "overlay_"+theLevel+"_loader",
		'class': "overlay_loader",
	}
	); 
	
	theOverlayButtons.innerHTML='<a id="overlay_'+theLevel+'_close" href="#" onclick="javascript:modalDialogClose(\'overlay_'+theLevel+'\',\'\')" class="small link_button">annuler</a>';
	
	
	theOverlay.injectInside($E('body'));
	theOverlayWindow.injectInside(theOverlay);
	theOverlayLoaderDiv.injectInside(theOverlayWindow);
	theOverlayContent.injectInside(theOverlayWindow);
	theOverlayButtons.injectInside(theOverlayWindow);
	
	
	// on initialise l'objet AJAX	
	var xhr = getXhr();
	// what to do when the response is received
	xhr.onreadystatechange = function(){
			// en attendant la r�ponse, on remplace les boutons d'enregistrement/annulation par un loader
		var theLoader='<div align="center" id="the_loader_'+theLevel+'"><img src="/assets/ajax-loader.gif" alt="chargement en cours..." title="chargement en cours..." valign="center"/></div>';
		if(xhr.readyState < 4) { theOverlayLoaderDiv.innerHTML = theLoader;}
		// only do something if the whole response has been received and the server says OK
		if(xhr.readyState == 4 && xhr.status == 200){
			var theResponseText = xhr.responseText;
			
			// on affiche le message de confirmation de la suppression de l' enregistrement
			theOverlayLoaderDiv.innerHTML='';
			theOverlayContent.innerHTML=theResponseText;
			
			// on affiche le bouton "supprimer"			
			var theDeleteButton=new Element('a', {
			    'class': 'small link_button',
			    'href': '#',
				'id': "overlay_"+theLevel+"_delete",
				'onclick': 'sendRecordToDelete('+theLevel+',\''+theTable+'\',\''+theRecord+'\')',
			});
			theDeleteButton.innerHTML="supprimer";
			theDeleteButton.injectBefore("overlay_"+theLevel+"_close");
			
			// on met � jour la hauteur de l'overlay au cas o� le dialogue soit plus haut que l'�cran
			// si on ne fait pas �a, l'overlay ne couvre qu'une partie de la page
			window.addEvent('domready', function(){
			// il faudrait trouver comment declarer correctement la hauteur de l'overlay
			//	var theHeight=$E('body').height;
			//	alert(theHeight);
				$('overlay'+"_"+theLevel).setStyle('height','100%');
				});
			
			}  
	} // end xhr.onreadystatechange

	// using GET to send the request	
	xhr.open("GET","/edition/edition_supprimer_enregistrement_ajax.php?&table="+theTable+"&level="+theLevel+"&record="+theRecord,true);
	xhr.send(null);
}


/**
* Fonction pour supprimer l'enregistrement choisi
*/
function sendRecordToDelete(theLevel,theTable,theRecord) {

var theDeleteButton=$('overlay_'+theLevel+'_delete');
var theLoader=$("overlay_"+theLevel+"_loader");
var theOverlayContent=$("overlay_"+theLevel+"_content");
var theCloseButton=$('overlay_'+theLevel+'_close');
var theTitle=$('delete_title').innerHTML;
// on initialise l'objet AJAX	
var xhr = getXhr();
// what to do when the response is received
xhr.onreadystatechange = function(){
		
	if(xhr.readyState < 4) {
		theDeleteButton.setStyle("visibility","hidden");
		theCloseButton.setStyle("visibility","hidden");
		// en attendant la r�ponse, on remplace les boutons d'enregistrement/annulation par un loader
		theLoader.innerHTML='<h1>'+theTitle+'</h1><h2>suppression de l&#x27;enregistrement en cours</h2><img src="/assets/ajax-loader.gif" alt="suppression en cours..." title="suppression en cours..." valign="center"/>';
		theOverlayContent.innerHTML='';
	}
	// only do something if the whole response has been received and the server says OK
	if(xhr.readyState == 4 && xhr.status == 200){
		theLoader.innerHTML='';
		var theResponseText = xhr.responseText;
		// on affiche le r�sultat de la suppression
		theOverlayContent.innerHTML=theResponseText;
				
			// maintenant, on change le comportement du bouton "fermer" : on raffraichit l'affichage
			var over='overlay_'+theLevel;
			theCloseButton.setStyle("visibility","visible");
			theCloseButton.innerHTML='fermer';
			theCloseButton.setProperty("onclick","javascript:modalDialogClose(\'"+over+"\',\'refresh\')");
		} // end if xhr.readyState == 4 && xhr.status == 200
	
} // end xhr.onreadystatechange


// using GET to send the request
xhr.open("GET","/edition/edition_supprimer_enregistrement_validation_ajax.php?&table="+theTable+"&record="+theRecord,true);
xhr.send(null);

}




/**
* Fonction qui d�clenche une op�ration de maintenance sur la base et en retourne le r�sultat
*/
function doMaintenance(action) {
// action : l'action � faire (sequences_ref_param, sequences_donnees, vacuum, reindex)

var outputDiv=$('maintenance_output');
// on initialise l'objet AJAX	
var xhr = getXhr();
// what to do when the response is received
xhr.onreadystatechange = function(){
		// while waiting for the response, display the loading animation
	var theLoader='<div align="left"><h2>maintenance de la base...<img src="/assets/ajax-loader.gif" alt="maintenance en cours..." title="maintenance en cours..." valign="center"/></h2></div>';
	if(xhr.readyState < 4) { outputDiv.innerHTML = theLoader;}
	// only do something if the whole response has been received and the server says OK
	if(xhr.readyState == 4 && xhr.status == 200){
		maintenanceResult = xhr.responseText;
		outputDiv.innerHTML=maintenanceResult;
	}  
} // end xhr.onreadystatechange

// using GET to send the request
xhr.open("GET","/edition/edition_maintenance_ajax.php?action="+action,true);
xhr.send(null);	
}


/**
* Fonction pour rafraichir les SELECT dependants en mode edition d'une cle etrangere avec cascade
*/
function updateEditSelects(theId,theLevel,theTable,theKey,theCascade) {
	
// theId : l'id du SELECT dont on veut sauver la valeur
// theLevel : le niveau du SELECT declenchant le refresh dans la cascade (p.e. : 1 pour systeme dans pays,systeme,secteur)
// theTable : la table du SELECT declencheur
// theKey : la colonne du SELECT declencheur
// theCascade : la cascade de SELECT comme d�finie dans la table de dictionnaire 

var theSelects=$$('select[name^='+theId+']');
// la valeur selectionnee dans le SELECT declencheur
var theKeyValue=theSelects[theLevel].value;

var ln=theSelects.length;
//alert(ln);

// on initialise l'objet AJAX	
var xhr = getXhr();
// what to do when the response is received
xhr.onreadystatechange = function(){
		
	if(xhr.readyState < 4) {
		// en attendant la r�ponse, on "vide" les SELECTs suivants
		for (i=parseInt(theLevel)+1;i<ln;i++) {
		//alert(i);
		theSelects[i].innerHTML = '<option value="">&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</option>';
		}
		
	}
	// only do something if the whole response has been received and the server says OK
	if(xhr.readyState == 4 && xhr.status == 200){
		var theResponseText = xhr.responseText;
		theSelects[parseInt(theLevel)+1].innerHTML=theResponseText;

		} // end if xhr.readyState == 4 && xhr.status == 200
	
} // end xhr.onreadystatechange


// using GET to send the request
xhr.open("GET","/edition/edition_rafraichir_cascade_ajax.php?theId="+theId+"&theLevel="+theLevel+"&theTable="+theTable+"&theKey="+theKey+"&theKeyValue="+theKeyValue+"&theCascade="+theCascade,true);
xhr.send(null);

}

