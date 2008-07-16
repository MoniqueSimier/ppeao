<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">

<script type="text/javascript">


function Envoi(val)
{
a_eff=val; 
document.Formulaire.method = 'GET';
document.Formulaire.action = 'voir_fichier_temp.php';
document.Formulaire.ligne.value = a_eff;
document.Formulaire.submit();
//alert(document.Formulaire.action);
}


</script> 
</head>


<body>
<div align='center'>
Contenu du fichier :

<?php

/////////////////////////////////Pour vider le fichier temp
$vider = $_POST['vider'];
if ($vider == "vider")
	{
	$file="temp.txt";
	$fpm = fopen($file, "w");
	fclose($fpm);
	}


///////////////////////////////affichage des lignes contenues dans le fichier temp
$file="temp.txt";
$fpm = fopen($file, "a+");


//creation du tableau $tab_ligne contenant les lignes du fichier temp
$i=0;
$tab_ligne = array();
while ($ligne=fgets($fpm,255))
	{
			if (in_array ($ligne, $tab_ligne)) {continue;}	//on enleve le doublon si il existe
			else {$tab_ligne[$i]=$ligne; $i ++;}
	}
fclose($fpm);



$file="temp.txt";
$fpm = fopen($file, "w");


////////////////////////////si il existe une ligne à enlever désigné par l'utilisateur
if (isset($_GET['ligne'])) 
	{
	$a_eff = $_GET['ligne'];
	unset($tab_ligne[$a_eff]);
	}


print ("<form name=\"Formulaire\">");
reset ($tab_ligne);
print ("<table>");

$j=0;
while (list($key, $val) = each($tab_ligne))
	{
	print ("<tr><td>ligne ".$j. " : ".$val .
	"</td><td><input type=\"button\" name=\"lig\" value=\"Effacer ligne\" onClick=\"Envoi("
	.$j.");\"></td></tr>");
	fputs ($fpm, $val);
	$j++;
	}
print ("<input type=\"hidden\" name=\"ligne\" >");
print ("</table>");
print ("</form>");
print ("</div>");

fclose($fpm);



?>
<div id="vider" align='center'>
<form method="post" action="voir_fichier_temp.php"><br>
Vider le fichier temporaire :<br><br>
<input type=hidden name="vider" value="vider" >
<input type="submit" name="vide" value="Reset">
</form>
</div>
</body>
</HTML>