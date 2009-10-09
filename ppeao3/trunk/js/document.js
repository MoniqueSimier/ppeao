/**
* Javascript pour gérer les effets slide verticaux.
*/

window.addEvent('domready', function() {
	var mySlide = new Fx.Slide('vertical_slide');
	mySlide.hide();
	$('v_slidein').addEvent('click', function(e){
		e = new Event(e);
		mySlide.slideIn();
		e.stop();
	});
	 
	$('v_slideout').addEvent('click', function(e){
		e = new Event(e);
		mySlide.slideOut();
		e.stop();
	});

});
	
/**
* Fonction qui affiche la fenetre de dialogue modale pour ajouter un nouveau document
*/
function modalDialogManagedoc(theLevel,theDocument,theAction) {
		
	// on crée le nouvel élément
	
	var theOverlay=new Element ('div', {
		'id': "overlay_"+theLevel,
		'class': "overlay",
		'style': "z-index:"+theLevel*1000
	}
	);
	
	var theOverlayWindow= new  Element ('div', {
		'id': "overlay_"+theLevel+"_window",
		'class': "overlay_window"
	}
	);
	
	var theOverlayContent= new  Element ('div', {
		'id': "overlay_"+theLevel+"_content",
		'class': "overlay_content"
	}
	);
		
	var theOverlayButtons= new  Element ('div', {
		'id': "overlay_"+theLevel+"_buttons",
		'class': "overlay_buttons"
	}
	);
	
	var theOverlayLoaderDiv =new  Element ('div', {
		'id': "overlay_"+theLevel+"_loader",
		'class': "overlay_loader"
	}
	); 
	
	theOverlayButtons.innerHTML='<a id="overlay_'+theLevel+'_close" href="#" onclick="javascript:modalDialogClose(\'overlay_'+theLevel+'\',\'\')" class="small link_button">fermer</a>';
	
	
	theOverlay.injectInside($E('body'));
	theOverlayWindow.injectInside(theOverlay);
	theOverlayLoaderDiv.injectInside(theOverlayWindow);
	theOverlayContent.injectInside(theOverlayWindow);
	theOverlayButtons.injectInside(theOverlayWindow);
	
	
	// on initialise l'objet AJAX	
	var xhr = getXhr();
	// what to do when the response is received
	xhr.onreadystatechange = function(){
			// en attendant la réponse, on remplace les boutons d'enregistrement/annulation par un loader
		var theLoader='<div align="center" id="the_loader_'+theLevel+'"><img src="/assets/ajax-loader.gif" alt="chargement en cours..." title="chargement en cours..." valign="center"/></div>';
		if(xhr.readyState < 4) { theOverlayContent.innerHTML = theLoader;}
		// only do something if the whole response has been received and the server says OK
		if(xhr.readyState == 4 && xhr.status == 200){
			var theResponseText = xhr.responseText;
			
			// on affiche les champs de saisie pour le nouvel enregistrement
			theOverlayContent.innerHTML=theResponseText;
			
			// on affiche le bouton "enregistrer"			
			var theSaveButton=new Element('a', {
			    'class': 'small link_button',
			    'href': '#',
				'id': "overlay_"+theLevel+"_save",
				'onclick': 'sendRecordToSave(\'add_record_'+theLevel+'_form\',\'add_field\','+theLevel+',\''+theTable+'\')'
			});
			theSaveButton.innerHTML="enregistrer";
			theSaveButton.injectBefore("overlay_"+theLevel+"_close");
			
			// on met à jour la hauteur de l'overlay au cas où le dialogue soit plus haut que l'écran
			// si on ne fait pas ça, l'overlay ne couvre qu'une partie de la page
			window.addEvent('domready', function(){
			// il faudrait trouver comment declarer correctement la hauteur de l'overlay
			//	var theHeight=$E('body').height;
			//	alert(theHeight);
				$('overlay'+"_"+theLevel).setStyle('height','100%');
				});
			
			}  
	} // end xhr.onreadystatechange

	// using GET to send the request
	xhr.open("GET","/documentation/gerer_doc.php?rep="+theDocument+"&action="+theAction,true);
	xhr.send(null);
}


/**
* Fonction qui ferme la fenetre de dialogue modale 
*/
function modalDialogClose(theDialogOverlay,refresh) {
	//theDialogOverlay : id de l'élément "overlay"
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

var theSaveButton=$('overlay_'+theLevel+'_save');
var theLoader=$("overlay_"+theLevel+"_loader");
// on initialise l'objet AJAX	
var xhr = getXhr();
// what to do when the response is received
xhr.onreadystatechange = function(){
		
	if(xhr.readyState < 4) {
		theSaveButton.setStyle("visibility","hidden");
		// en attendant la réponse, on remplace les boutons d'enregistrement/annulation par un loader
		theLoader.innerHTML='<img src="/assets/ajax-loader.gif" alt="validation en cours..." title="validation en cours..." valign="center"/>';
	}
	// only do something if the whole response has been received and the server says OK
	if(xhr.readyState == 4 && xhr.status == 200){
		theLoader.innerHTML='';
		var theResponseNode = xhr.responseXML.documentElement;
		var theNodes=theResponseNode.childNodes;
		//debug alert(theNodes.length);
		// la validité des valeurs saisies
		var isValid=theResponseNode.attributes.getNamedItem("validity").value;
		//debug alert(theResponseNode.attributes.getNamedItem("validity").value);
		// si la saisie n'est pas valide, on réactive le bouton enregistrer
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
					if (theError==$('add_record_'+theLevel+'_'+theKey+'_error')) {
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
				theConfirmation='enregistrement ajouté dans la table "'+theResponseNode.attributes.getNamedItem("table").value+'"';
				theH1.innerHTML=theConfirmation;
				for (var i=0; i<theNodes.length; i++) {
					var theNode=theNodes[i];
					var theKey=theNode.attributes.getNamedItem("key").value;
					// on traite différemment les valeurs "normales" et les "sequences"
					if (theNode.attributes.getNamedItem("sequence").value=="sequence") {
						var theValue=$('add_record_'+theLevel+'_'+theKey).innerHTML;
					} else 
					{
						var theValue=$('add_record_'+theLevel+'_'+theKey).value;
					}
					var theValidity=theNode.attributes.getNamedItem("valid").value;
					//debug					alert(theKey+':'+theValue);
					// on enlève le message d'erreur si il existe
					if ($('add_record_'+theLevel+'_'+theKey+'_error')) {$('add_record_'+theLevel+'_'+theKey+'_error').remove();}
					// on crée un nouvel élément contenant seulement la valeur à afficher
					var theNewElement=new Element('div',{
						'class' : 'small',
						'id' : 'add_record_'+theLevel+'_'+theKey
					}
					);
					theNewElement.innerHTML=theValue;
					// on sélectionne l'élément de formulaire qu'il doit remplacer
					//debug alert('a_'+theKey);
					var theOldElement=$('add_record_'+theLevel+'_'+theKey);
					// on remplace l'ancien élément par le nouveau
					theOldElement.replaceWith(theNewElement);
				}// end for
				
			// maintenant, on change le comportement du bouton "fermer"
			var theCloseButton=$('overlay_'+theLevel+'_close');
			var over='overlay_'+theLevel;
			theCloseButton.setProperty("onclick","javascript:modalDialogClose(\'"+over+"\',\'refresh\')")
			} // end else isValid
		} // end if xhr.readyState == 4 && xhr.status == 200
	
} // end xhr.onreadystatechange


// using GET to send the request
xhr.open("GET","/edition/edition_ajouter_enregistrement_validation_ajax.php?&table="+theTable+"&level="+theLevel+theUrl,true);
xhr.send(null);

}