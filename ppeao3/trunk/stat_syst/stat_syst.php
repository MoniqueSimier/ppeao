<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Statistiques du système</title>

<script type="text/javascript">
var id_pays='';
		//var id_pays='Liste1';
		//var id_systeme='Liste2';
var id_systeme='';
var id_secteur='';
var id_agglo='';
var id_annee='';
var id_mois='';
var id_liste='';

//var xxx =document.getElementByID('Liste1').value;
//var xxx = document.form.niv2.value;
//if (xxx !="")alert(xxx);
//if (id_pays !="")alert(id_pays);
//if (Liste1 !="")alert(Liste1);


function Valide_pays(val) {
	id_pays=val; 
	id_liste='2';//Utilisé dans la fonction ChargeLd() pour identifier la liste déroulante
	var LD_URL = 'choix_systeme.php?pays='+id_pays;
	ObjetXHR(LD_URL)
	// Réinitialisation de Ld3 et ld4 si modification de LD1 après passage en Ld2
	//if (id_systeme!='') {Validesysteme('');	}
	//if (id_secteur!='') {Validesecteur('');	}
	//if (id_agglo!='') {Valideagglo('');	}
	//alert (id_pays);
}
function Valide_systeme(val) {
	id_systeme=val;
	id_liste='3';
	var LD_URL = 'choix_secteur.php?pays='+id_pays+'&systeme='+id_systeme;
	// Réinitialisation de Ld3 si modification de LD1 après passage en Ld2
	//if (id_secteur!='') {Validesecteur('');	}
	//if (id_agglo!='') {Valideagglo('');	}
	ObjetXHR(LD_URL)
	//alert (id_systeme);
}
function Valide_secteur(val) {
	id_secteur=val;
	id_liste='4';
	var LD_URL = 'choix_annee.php?pays='+id_pays+'&systeme='+id_systeme+'&secteur='+id_secteur;
	//if (id_agglo!='') {Valideagglo('');	}
	//if (Ld2Id!='') {ValideLd3('');	}
	//if (Ld3Id!='') {ValideLd4('');	}
	//if (Ld4Id!='') {ValideLd5('');	}
	ObjetXHR(LD_URL)	
}


function Valide_annee(val) {
	id_annee=val;
	id_liste='5';
	var LD_URL = 'choix_mois.php?pays='+id_pays+'&systeme='+id_systeme+'&secteur='+id_secteur+'&annee='+id_annee;
	//if (id_agglo!='') {Valideagglo('');	}
	//if (Ld2Id!='') {ValideLd3('');	}
	//if (Ld3Id!='') {ValideLd4('');	}
	//if (Ld4Id!='') {ValideLd5('');	}
	ObjetXHR(LD_URL)
}

function Valide_mois(val) {
	id_mois=val;
	id_liste='6';
	var LD_URL = 'choix_agglo.php?pays='+id_pays+'&systeme='+id_systeme+'&secteur='+id_secteur+'&annee='+id_annee+'&mois='+id_mois;
	//if (id_agglo!='') {Valideagglo('');	}
	//if (Ld2Id!='') {ValideLd3('');	}
	//if (Ld3Id!='') {ValideLd4('');	}
	//if (Ld4Id!='') {ValideLd5('');	}
	ObjetXHR(LD_URL)
}


function Valide_agglo(val) {
	id_agglo=val;
	id_liste='7';	//variable pour résoudre pb mozilla
	var LD_URL = 'stat_syst.php?pays='+id_pays+'&systeme='+id_systeme+'&secteur='+id_secteur+'&annee='+id_annee+'&mois='+id_mois+'&agglo='+id_agglo;
	//if (id_agglo!='') {Valideagglo('');	}
	//if (Ld2Id!='') {ValideLd3('');	}
	//if (Ld3Id!='') {ValideLd4('');	}
	//if (Ld4Id!='') {ValideLd5('');	}
	ObjetXHR(LD_URL)
	
	Affiche_Btn();	
}


function ObjetXHR(LD_URL) {
	//creation de l'objet XMLHttpRequest
	if (window.XMLHttpRequest) { // Mozilla,...
		xmlhttp=new XMLHttpRequest();
		if (xmlhttp.overrideMimeType) {
			xmlhttp.overrideMimeType('text/xml');
		}	
		xmlhttp.onreadystatechange=ChargeLd;
		xmlhttp.open("GET", LD_URL, true);
		xmlhttp.send(null);
	} else if (window.ActiveXObject) { //IE 
		xmlhttp=new ActiveXObject('Microsoft.XMLHTTP'); 
		if (xmlhttp) {
			xmlhttp.onreadystatechange=ChargeLd;
			xmlhttp.open('GET', LD_URL, false);
			xmlhttp.send();
		}
	}
	// Bouton non apparent car modification de LD1 ou Ld2
	document.getElementById('buttons').style.display='none';
}

// fonction pour manipuler l'appel asynchrone
function ChargeLd() {
	if (xmlhttp.readyState==4) { 
		if (xmlhttp.status==200) { 
			//span id="niv2" ou "niv3"
			document.getElementById('niv'+id_liste).innerHTML=xmlhttp.responseText; 
			if (xmlhttp.responseText.indexOf('disabled')<=0) {
				//focus sur liste déroulante 2, 3...
				document.getElementById('Liste'+id_liste).focus(); 
			}	
		}
	}
}

function Affiche_Btn() {
	document.getElementById('buttons').style.display='inline';
	//alert (id_pays);
	//alert (id_systeme);
}

function OuvrirPopup(page,nom,option) {
       window.open(page,nom,option);
    }




</script>

<style>
#buttons {
	display: none;
}
</style>
</head>

<body>
<noscript>
<p>Cette page nécessite que JavaScript soit activé; dans votre navigateur
</noscript>




<?php 
$Liste1 = $_GET['Liste1'];
$Liste2 =  $_GET['Liste2'];
$Liste3 =  $_GET['Liste3'];
$Liste4 =  $_GET['Liste4'];
$Liste5 =  $_GET['Liste5'];
$Liste6 =  $_GET['Liste6'];
$effort = $_GET['effort'];


//print("******".$Liste1." ,".$Liste2." ,".$Liste3." ,".$Liste4." ,".$Liste5." ,".$Liste6." ,".$effort);

$id_pays = $Liste1;
?>

<div align='center'>
<Font Color ="#333366">
<br><br><b>LES STATISTIQUES DE PECHE DU SYSTEME</b><br>
</div>
</Font>

<br>
<div align='center'>
<Font Color ="#333366">
<b>Cette opération se déroule en deux phases : </b><br>
<table>
<tr align ="left"><td>
1. Introduire les données sur le serveur
</td></tr><tr align ="left"><td>
2. Traitements statistiques
</td></tr></table>


<br>
</font>
</div>





<div id="Lesmenus" align='center'>
  <p>Pour renter les données, selectionnez votre choix dans les listes d&eacute;roulantes:</p>
  <form method="get" action="stat_syst.php" name = "form">
    <? 
    include 'choix_pays.php'; ?>&nbsp; <!--Pour remplir la liste déroulante 1-->
    <span id="niv2">
    <? include 'choix_systeme.php'; ?></span>&nbsp; <!--Pour remplir la liste déroulante 2-->
    <span id="niv3">
    <? include 'choix_secteur.php'; ?></span>&nbsp; 
    <span id="niv4">
    <? include 'choix_annee.php'; ?></span>&nbsp; 
    <span id="niv5">
    <? include 'choix_mois.php'; ?></span>&nbsp; 
    <span id="niv6">
    <? include 'choix_agglo.php'; ?></span>&nbsp; 
    <span id="buttons">
    			<input type="hidden" name="yyy" value="98989898">
    <input type="submit" value="Valider">
    </span>
  </form>
</div>


<br><br>
<div align='center'>
La dernière ligne remplie est : 
<br><br>
<Font Color ="#808080">
<?php 


if (($Liste1 != "")&&($Liste2 != "")&&($Liste3 != "")&&($Liste4 != "")&&($Liste5 != "")&&($Liste6 != "")&&($effort != ""))
	{
	$file="temp.txt";
	
	//si pas de mois renseigné
	//if ($Liste5 == "aucun")$Liste5 = "";
	//si pas de secteur ni village renseigné
	if ($Liste3 == "aucun")$Liste3 = "";
	if ($Liste6 == "aucun")$Liste6 = "";
	
	
	$contenu=$Liste1."\t".$Liste2."\t".$Liste3."\t".$Liste4."\t".$Liste5."\t".$Liste6."\t".$effort."\n";
	
	$fpm = fopen($file, "a+");
	
	fputs($fpm,$contenu);
	fclose($fpm);
	echo $contenu;
}


?>
</Font>

<br><br>

<div align='center'>
<A href="javascript:OuvrirPopup('voir_fichier_temp.php','','top=10,left=400,resizable=yes, width=600, height=150, scrollbars=yes ')">
<Font Color ="#204080">
Visualisation ou modification du fichier temporaire du serveur :
</Font>
</A>
</div>



<br><br>
</div>
<div id="valid" align='center'>
<form method="get" action="stat_syst_traitement.php"><br>
Lancement des traitement statistiques :<br><br>
<input type="submit" name="valid" value="Traitement">
</form>
</div>



</body>
</html>