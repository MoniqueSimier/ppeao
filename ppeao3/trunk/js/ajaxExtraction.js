// Fonctions javascript pour la gestion de l'extraction, pahse II (lecture des données apres la selection
// creation : Y. Laurent le 30/06/2009
// 

// Definition des variables globales
var xmlHttp;
var fenID;
var numProcess = 1;
var progPhp;
var nomFen;
var nomURL ;
var AddURL;
var finTrt = false;
var portageOK = "ok";
// Variable pour lancement recomposition données
var checkLog;
var globaltypepeche = "";
var globalTableEnCours = "" ;
var globaltypestat = "";

var globalAction = "";
var numElementExplode = 0;
// Pour les tabulations, numtab =
// 1 = critères généraux
// 2 = catégories écologiques
// 3 = catégories trophiques
// 4 = Colonnes

function explode( delimiter, string, limit ) {
	// http://kevin.vanzonneveld.net
    // +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: kenneth
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: d3x
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
 
    var emptyArray = { 0: '' };
    
    // third argument is not required
    if ( arguments.length < 2 ||
        typeof arguments[0] == 'undefined' ||
        typeof arguments[1] == 'undefined' )
    {
        return null;
    }
 
    if ( delimiter === '' ||
        delimiter === false ||
        delimiter === null )
    {
        return false;
    }
 
    if ( typeof delimiter == 'function' ||
        typeof delimiter == 'object' ||
        typeof string == 'function' ||
        typeof string == 'object' )
    {
        return emptyArray;
    }
 
    if ( delimiter === true ) {
        delimiter = '1';
    }
    
    if (!limit) {
        return string.toString().split(delimiter.toString());
    } else {
        // support for limit argument
        var splitted = string.toString().split(delimiter.toString());
        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;
    }
}

function recupereSelection(limSel,Selection){
	listeVal = "";
	for (i=1;i<=limSel;i++) {
		if 	(document.getElementById(Selection+i).checked) {
			if (listeVal=="") {
				listeVal = document.getElementById(Selection+i).value;
			} else {
				listeVal = listeVal+","+document.getElementById(Selection+i).value;
			}
		}
	}
	return listeVal;
}


function runFilieresExp(typePeche,typeAction,numtab,tableEnCours,validation,selToutesCol,selToutescat,selToutEsp) {
// fonction runFilieresExp : permet de gerer l'affichage des différents tabs des differentes filieres pour les peches exp
// Y compris de recuperer toutes les valeurs saisies

	// On récupère les valeurs des différents paramètres entrée
	checkLog = document.getElementById("logsupp").checked;
	globaltypepeche = typePeche;
	qual = "";
	rest = "";
	poiss = "";
	listCE = "";
	listCT = "";
	listCol = "";
	ListEsp = "";
	EvalCE = false;
	EvalCT = false;
	if  (globalAction == typeAction) {
		changementAction = '';
		// On charge les données modifiées
		// Recuperation des selections sur qualite coup de peche
		qual = recupereSelection(5,"qualiteCP");
		// Recuperation des selections sur restriction aux coups du protocole
		rest  = recupereSelection(2,"restreindre");
		// Recuperation des selections sur choix des poissons
		poiss  = recupereSelection(4,"poisson");
		// Recuperation des categories ecologiques / trophiques
		switch (selToutescat) {
			case "tout-CEco" :
				listCE = "XtoutX";
				EvalCE = true;
				break;
			case "aucun-CEco" :
				listCE = "XpasttX";
				EvalCE = true;
				break;
			case "tout-CTro" :
				listCT = "XtoutX";
				EvalCT = true;
				break;
			case "aucun-CTro" :
				listCT = "XpasttX";
				EvalCT = true;
				break;
		}
		if (!EvalCE) {
			limCE = document.getElementById("numCEco").value;
			listCE  = recupereSelection(limCE,"CEco");
		}
		if (!EvalCT) {
			limCT = document.getElementById("numCTro").value;
			listCT  = recupereSelection(limCT,"CTro");
		}
		// Recuperation des especes
		limEsp = document.getElementById("numEsp").value;
		if (!(selToutEsp == '')) {
			if 	(selToutEsp == 'tout') {
				ListEsp = 'XtoutX';
			} 	else {
				ListEsp = 'XpasttX';
			}
		} else {
			ListEsp  = recupereSelection(limEsp,"Esp");
		}
		// Recuperation des colonnes en plus selectionnees
		TEC = "&TEC="+tableEnCours;
		TPEC = document.getElementById("tableEC").value;
		// Recuperation des colonnes
		limFac = document.getElementById("numFac").value;
		DeselCol = false;
		if (!(selToutesCol == '')) {
			if 	(selToutesCol == 'tout') {
				listCol = 'XtoutX';
			} 	else {
				listCol = 'XpasttX';
			}
		} else {
			for (i=1;i<=limFac;i++) {
				IDEnCours = TPEC+"fac"+i;
				if 	(document.getElementById(IDEnCours).checked) {
					if (listCol=="") {
						listCol = document.getElementById(IDEnCours).value+"-X";
					} else {
						listCol = listCol+","+document.getElementById(IDEnCours).value+"-X";
					}
				} 	else {
					if (listCol=="") {
						listCol = document.getElementById(IDEnCours).value+"-N";
					} else {
						listCol = listCol+","+document.getElementById(IDEnCours).value+"-N";
					}
					// On déselectionne la case a cocher tout
					document.getElementById('facTout').checked = false;
				}
			}
		}
		if (listCE == "") {
			addCE = "" ;
		} else {
			addCE = "&CE="+listCE;
		}
		if (listCT == "") {
			addCT = "" ;
		} else {
			addCT = "&CT="+listCT;
		}
		if (listCol == "") {
			addCol = "" ;
		} else {
			addCol = "&Col="+listCol;
		}
		if (ListEsp == "") {
			addEsp = "" ;
		} else {
			addEsp = "&Esp="+ListEsp;
		}
		
		addURL = "&qual="+qual+"&rest="+rest+"&pois="+poiss+addCE+addCT+TEC+addCol+addEsp;
	} else {
		globalAction = typeAction;
		changementAction = '&chgA=y';
		addURL = "";
	}
	ExpFic = '';
	if (validation == 'y') {
		if 	(document.getElementById("ExpFic").checked) {
			ExpFic = '&exf=y' ;
		} else {
			ExpFic = '';
		}
		var url="/extraction/extraction/extraction_resultat_exp.php?log="+checkLog+"&action="+typeAction+"&tp="+typePeche+ExpFic+addURL;
		window.location.replace(url);
	} else {
		//if (typeAction =='peuplement') {
		// Demander si on veut exporter sous forme de fichier.
		//	var url="/extraction/extraction/extraction_resultat_exp.php?log="+checkLog+"&action="+typeAction+"&tp="+typePeche
		//	window.location.replace(url)
		//} else {
		xmlHttp=GetXmlHttpObject();
		if (xmlHttp==null){
			alert ("Your browser does not support AJAX!");
			return;
		} 	
		switch (numtab) {
			case '1' :
						try{
						document.getElementById("exportFic").style.position = 'absolute';
						document.getElementById("exportFic").style.top = '550px';
						}
						catch (e) { }
						document.getElementById("footer").style.position = 'absolute';
						document.getElementById("footer").style.top = '550px';
						break;
			case '2' :
						try{
						document.getElementById("exportFic").style.position = 'absolute';
						document.getElementById("exportFic").style.top = '530px';
						}
						catch (e) { }
						document.getElementById("footer").style.position = 'absolute';
						document.getElementById("footer").style.top = '600px';
						break;
			case '3' :
						try{
						document.getElementById("exportFic").style.position = 'absolute';
						document.getElementById("exportFic").style.top = '530px';
						}
						catch (e) { }
						document.getElementById("footer").style.position = 'absolute';
						document.getElementById("footer").style.top = '600px';
						break;
			case '4' :
						try{
						document.getElementById("exportFic").style.position = 'absolute';
						document.getElementById("exportFic").style.top = '750px';
						}
						catch (e) { }
						document.getElementById("footer").style.position = 'absolute';
						document.getElementById("footer").style.top = '750px';
						break;
			case '5' :
						try{
						document.getElementById("exportFic").style.position = 'absolute';
						document.getElementById("exportFic").style.top = '760px';
						}
						catch (e) { }
						document.getElementById("footer").style.position = 'absolute';
						document.getElementById("footer").style.top = '760px';
						break;	
		}
		fenID = "resultfiliere";
		var url="/extraction/extraction/extraction_gestion_filieres_exp.php?log="+checkLog+"&action="+typeAction+"&tp="+typePeche+"&tab="+numtab+changementAction+addURL;
		xmlHttp.onreadystatechange=stateChanged2;
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
		//}
	}
} 


function stateChanged2() { 
// Fonction qui gere l'apres selection dans le tableau des filieres pour les peches experimentales
	if (xmlHttp.readyState==4)	{ 		
		peuActif = "";
		envActif = "";
		ntActif = "";
		bioActif = "";
		trophActif = "";
		
		if (globalAction=="peuplement") {peuActif=" active";}
		if (globalAction=="environnement") {envActif=" active";}
		if (globalAction=="NtPt") {ntActif=" active";}
		if (globalAction=="biologie") {bioActif=" active";}
		if (globalAction=="trophique") {trophActif=" active";}
		
		document.getElementById(fenID).innerHTML=xmlHttp.responseText;
		document.getElementById("runProcess").innerHTML="<b>Choix de la fili&egrave;re :</b>&nbsp;<a href=\"#\" onClick=\"runFilieresExp('"+globaltypepeche+"','peuplement','1','"+globalTableEnCours+"','n','','','')\" class=\"peuplement"+peuActif+"\">peuplement</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresExp('"+globaltypepeche+"','environnement','1','"+globalTableEnCours+"','n','','','')\" class=\"environnement"+envActif+"\">environnement</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresExp('"+globaltypepeche+"','NtPt','1','"+globalTableEnCours+"','n','','','')\" class=\"NtPt"+ntActif+"\">Nt/Pt</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresExp('"+globaltypepeche+"','biologie','1','"+globalTableEnCours+"','n','','','')\" class=\"biologie"+bioActif+"\">biologie</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresExp('"+globaltypepeche+"','trophique','1','"+globalTableEnCours+"','n','','','')\" class=\"trophique"+trophActif+"\">trophique</a>";
		document.getElementById("exportFic").innerHTML= "<input type=\"button\" id=\"validation\" onClick=\"runFilieresExp('"+globaltypepeche+"','"+globalAction+"','1','','y','','','')\" value=\"Afficher le resultat\"/><br/><input type=\"checkbox\" id=\"ExpFic\" checked=\"checked\"/>Exporter en fichier";
	}
}


function runFilieresArt(typePeche,typeAction,numtab,tableEnCours,validation,selToutesCol,selToutescat,selToutEsp) {
// fonction runFilieresArt : permet de gerer l'affichage des différents tabs des differentes filieres pour les peches art
// Y compris de recuperer toutes les valeurs saisies

	// On récupère les valeurs des différents paramètres entrée
	//document.getElementById("resultfiliere").innerHTML="<img src='/assets/ajax-loader_32px.gif' alt=''/>";
	checkLog = document.getElementById("logsupp").checked;
	globaltypepeche = typePeche;
	qual = "";
	rest = "";
	poiss = "";
	listCE = "";
	listCT = "";
	listCol = "";
	ListEsp = "";
	EvalCE = false;
	EvalCT = false;
	if  (globalAction == typeAction) {
		changementAction = '';
		// On charge les données modifiées
		// Recuperation des selections sur choix des poissons
		poiss  = recupereSelection(4,"poisson");
		// Recuperation des categories ecologiques
		if ( typeAction == 'NtPt' || typeAction == 'taille' ) {
			// Recuperation des categories ecologiques / trophiques
			switch (selToutescat) {
				case "tout-CEco" :
					listCE = "XtoutX";
					EvalCE = true;
					break;
				case "aucun-CEco" :
					listCE = "XpasttX";
					EvalCE = true;
					break;
				case "tout-CTro" :
					listCT = "XtoutX";
					EvalCT = true;
					break;
				case "aucun-CTro" :
					listCT = "XpasttX";
					EvalCT = true;
					break;
			}
			if (!EvalCE) {
				limCE = document.getElementById("numCEco").value;
				listCE  = recupereSelection(limCE,"CEco");
			}
			if (!EvalCT) {
				limCT = document.getElementById("numCTro").value;
				listCT  = recupereSelection(limCT,"CTro");
			}
			// Recuperation des especes
			limEsp = document.getElementById("numEsp").value;
			if (!(selToutEsp == '')) {
				if 	(selToutEsp == 'tout') {
					ListEsp = 'XtoutX';
				} 	else {
					ListEsp = 'XpasttX';
				}
			} else {
				ListEsp  = recupereSelection(limEsp,"Esp");
			}
		}
		// Recuperation des colonnes en plus selectionnees
		TEC = "&TEC="+tableEnCours;
		TPEC = document.getElementById("tableEC").value;
		// Recuperation des colonnes
		limFac = document.getElementById("numFac").value;
		DeselCol = false;
		if (!(selToutesCol == '')) {
			if 	(selToutesCol == 'tout') {
//				if (listCol=="") {
					listCol = 'XtoutX';
//				} else {
//					listCol = listCol+',XtoutX'
//				}
			} 	else {
//				if (listCol=="") {
					listCol = 'XpasttX'
//				} else {
//					listCol = listCol+',XpasttX'
//				}
			}
		} else {
			for (i=1;i<=limFac;i++) {
				IDEnCours = TPEC+"fac"+i
				if 	(document.getElementById(IDEnCours).checked) {
					if (listCol=="") {
						listCol = document.getElementById(IDEnCours).value+"-X"
					} else {
						listCol = listCol+","+document.getElementById(IDEnCours).value+"-X"
					}
				} 	else {
					if (listCol=="") {
						listCol = document.getElementById(IDEnCours).value+"-N"
					} else {
						listCol = listCol+","+document.getElementById(IDEnCours).value+"-N"
					}
					// On déselectionne la case a cocher tout
					document.getElementById('facTout').checked = false
					DeselCol = true
				}
			}
		}
		if (listCE == "") {
			addCE = "" ;
		} else {
			addCE = "&CE="+listCE;
		}
		if (listCT == "") {
			addCT = "" ;
		} else {
			addCT = "&CT="+listCT;
		}
		if (listCol == "") {
			addCol = "" ;
		} else {
			addCol = "&Col="+listCol;
		}
		if (ListEsp == "") {
			addEsp = "" ;
		} else {
			addEsp = "&Esp="+ListEsp;
		}
		addURL = "&pois="+poiss+addCE+addCT+TEC+addCol+addEsp;
	} else {
		globalAction = typeAction;
		changementAction = '&chgA=y';
		addURL = "";
	}
	if (validation == 'y') {
			if 	(document.getElementById("ExpFic").checked) {
				ExpFic ="&exf=y" ;

			} else {
				ExpFic ="";
			}
			var url="/extraction/extraction/extraction_resultat_art.php?log="+checkLog+"&action="+typeAction+"&tp="+typePeche+ExpFic+addURL;
			window.location.replace(url);
		} else {
		if (typeAction =='peuplement') {
			// Demander si on veut exporter sous forme de fichier.
			var url="/extraction/extraction/extraction_resultat_art.php?log="+checkLog+"&action="+typeAction+"&tp="+typePeche;
			window.location.replace(url);
		} else {
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null){
				alert ("Your browser does not support AJAX!");
				return;
			} 	
			switch (numtab) {
				case '1' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '530px';
	  						}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '550px';
							break;
				case '2' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '570px';
							}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '600px';
							break;
				case '3' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '600px';
							}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '600px';
							break;
				case '4' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '770px';
							}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '800px';
							break;		
				case '5' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '770px';
							}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '800px';
							break;
			}
			fenID = "resultfiliere";
			var url="/extraction/extraction/extraction_gestion_filieres_art.php?log="+checkLog+"&action="+typeAction+"&tp="+typePeche+"&tab="+numtab+changementAction+addURL;
			xmlHttp.onreadystatechange=stateChanged3;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}
	}
} 

function runFilieresStat(typeStat,typeAction,numtab,tableEnCours,validation,selToutesCol,selToutescat,selToutEsp) {
// fonction runFilieresStat : permet de gerer l'affichage des différents tabs des differentes filieres pour les statistiques
// C'est moins complique pour les peches, on ne gere que 2 tabs.
// Y compris de recuperer toutes les valeurs saisies

	// On récupère les valeurs des différents paramètres entrée
		
	document.getElementById("resultfiliere").innerHTML="<img src='/assets/ajax-loader_32px.gif' alt=''/>";
	checkLog = document.getElementById("logsupp").checked;
	globaltypestat = typeStat;
	listCol = "";
	if  (globalAction == typeAction) {
		changementAction = ''
		// On charge les données modifiées
		// Recuperation des selections sur choix des especes
		// Recuperation des especes
		limEsp = document.getElementById("numEsp").value;
		ListEsp  = recupereSelection(limEsp,"Esp");
		// Recuperation des colonnes en plus selectionnees
		TEC = "&TEC="+tableEnCours;
		TPEC = document.getElementById("tableEC").value;
		// Recuperation des colonnes
		limFac = document.getElementById("numFac").value;
		if (!(selToutesCol == '')) {
			if 	(selToutesCol == 'tout') {
				listCol = 'XtoutX'
			} 	else {
				listCol = 'XpasttX'
			}
		}
		for (i=1;i<=limFac;i++) {
			IDEnCours = TPEC+"fac"+i
			if 	(document.getElementById(IDEnCours).checked) {
				if (listCol=="") {
					listCol = document.getElementById(IDEnCours).value
				} else {
					listCol = listCol+","+document.getElementById(IDEnCours).value
				}
			}
		}

		if (listCol == "") {
			addCol = "" ;
		} else {
			addCol = "&Col="+listCol;
		}
		if (ListEsp == "") {
			addEsp = "" ;
		} else {
			addEsp = "&Esp="+ListEsp;
		}
		addURL = TEC+addCol+addEsp;
	} else {
		globalAction = typeAction;
		changementAction = '&chgA=y';
		addURL = "";
	}
	if (validation == 'y') {
			if 	(document.getElementById("ExpFic").checked) {
				ExpFic ="&exf=y" ;

			} else {
				ExpFic ="";
			}
			var url="/extraction/extraction/extraction_resultat_stat.php?log="+checkLog+"&action="+typeAction+"&ts="+typeStat+ExpFic+addURL;
			window.location.replace(url);
		} else {
		if (typeAction =='peuplement') {
			// Demander si on veut exporter sous forme de fichier.
			var url="/extraction/extraction/extraction_resultat_stat.php?log="+checkLog+"&action="+typeAction+"&ts="+typeStat;
			window.location.replace(url);
		} else {
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null){
				alert ("Your browser does not support AJAX!");
				return;
			} 	
			switch (numtab) {
				case '1' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '530px';
	  						}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '550px';
							break;
				case '2' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '570px';
							}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '600px';
							break;
				case '3' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '570px';
							}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '600px';
							break;
				case '4' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '770px';
							}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '800px';
							break;		
				case '5' :
							try{
							document.getElementById("exportFic").style.position = 'absolute';
							document.getElementById("exportFic").style.top = '770px';
							}
							catch (e) { }
							document.getElementById("footer").style.position = 'absolute';
							document.getElementById("footer").style.top = '800px';
							break;
			}
			fenID = "resultfiliere";
			var url="/extraction/extraction/extraction_gestion_filieres_stat.php?log="+checkLog+"&action="+typeAction+"&ts="+typeStat+"&tab="+numtab+changementAction+addURL;
			xmlHttp.onreadystatechange=stateChanged4;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}
	}
} 



function stateChanged1() {
	if (xmlHttp.readyState==4) {
		document.getElementById(fenID).innerHTML=xmlHttp.responseText;
	}
}


function stateChanged3() {
// Fonction qui gere l'apres selection dans le tableau des filieres pour les peches artisanales
	if (xmlHttp.readyState==4)	{ 		
		actActif = "";
		capActif = "";
		ntActif = "";
		strActif = "";
		engActif = "";
		
		if (globalAction=="activite") {actActif=" active";}
		if (globalAction=="capture") {capActif=" active";}
		if (globalAction=="NtPt") {ntActif=" active";}
		if (globalAction=="taille") {strActif=" active";}
		if (globalAction=="engin") {engActif=" active";}
		
		document.getElementById(fenID).innerHTML=xmlHttp.responseText;
		document.getElementById("runProcess").innerHTML="<b>Choix de la fili&egrave;re :</b>&nbsp;<a href=\"#\" onClick=\"runFilieresArt('"+globaltypepeche+"','activite','1','"+globalTableEnCours+"','n','','','')\" class=\"activite"+actActif+"\">activit&eacute</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresArt('"+globaltypepeche+"','capture','1','"+globalTableEnCours+"','n','','','')\" class=\"capture"+capActif+"\">captures totales</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresArt('"+globaltypepeche+"','NtPt','1','"+globalTableEnCours+"','n','','','')\" class=\"NtPt"+ntActif+"\">Nt/Pt</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresArt('"+globaltypepeche+"','taille','1','"+globalTableEnCours+"','n','','','')\" class=\"structure"+strActif+"\">structure de taille</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresArt('"+globaltypepeche+"','engin','1','"+globalTableEnCours+"','n','','','')\" class=\"engin"+engActif+"\">engin de p&ecirc;che</a>";
		document.getElementById("exportFic").innerHTML= "<input type=\"button\" id=\"validation\" onClick=\"runFilieresArt('"+globaltypepeche+"','"+globalAction+"','1','','y','','','')\" value=\"Afficher le resultat\"/><input type=\"checkbox\" id=\"ExpFic\" checked=\"checked\"/>Exporter en fichier";
		
	}
}


function stateChanged4() {
// Fonction qui gere l'apres selection dans le tableau des filieres pour les statistiques
	if (xmlHttp.readyState==4)	{ 		
		gloActif = "";
		gtActif = "";
		
		if (globalAction=="globale") {gloActif=" active";}
		if (globalAction=="GT") {gtActif=" active";}
		
		document.getElementById(fenID).innerHTML=xmlHttp.responseText;
		document.getElementById("runProcess").innerHTML="<b>Choix type statistique &agrave; extraire :</b>&nbsp;<a href=\"#\" onClick=\"runFilieresStat('"+globaltypestat+"','globale;','1','"+globalTableEnCours+"','n','','','')\" class=\"globale"+gloActif+"\">statistiques globales</a>&nbsp;-&nbsp;<a href=\"#\" onClick=\"runFilieresStat('"+globaltypestat+"','GT','1','"+globalTableEnCours+"','n','')\" class=\"GT"+gtActif+"\">statistiques par Grand Type</a>";
		document.getElementById("exportFic").innerHTML= "<input type=\"button\" id=\"validation\" onClick=\"runFilieresArt('"+globaltypepeche+"','"+globalAction+"','1','','y','','','')\" value=\"Afficher le resultat\"/><input type=\"checkbox\" id=\"ExpFic\" checked=\"checked\"/>Exporter en fichier";
		
	}
}


function GetXmlHttpObject(){
	var xmlHttp=null;
	try{
	  // Firefox, Opera 8.0+, Safari
	  xmlHttp=new XMLHttpRequest();
	  }
	catch (e) {
	  // Internet Explorer
	  try{
		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
	  catch (e)	{
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	  }
	return xmlHttp;
}