<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">

<SCRIPT type="text/javaScript">

function confirmer() {
if (document.getElementById('alerte').value=='1')
return confirm('Etes-vous sûr(e) ?');
else
return true;
}

function fenetre(){
fenetre = open("ppeao1.php","Resultats","scrollbars=1,menubar=1, status=1, height=600,width=400,left=10,top=20,resizable=yes");
}

dimensions="width="+(screen.width/3)+",height="+(screen.width/3)+",top=50,left=100,screenY=50, screenX=100, resizable=no";



function pop_it3(the_form) {
   my_form = eval(the_form);
   window.open("blanc.html", "popup", "height=300,width=500,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes");
   my_form.target = "popup";
   my_form.submit();
}






</script>
</head>
<body>

<?php
$bdd = $_POST['base'];
//$bdd = "jerome_manant";
//print("travail sur la base : ".$bdd);
?>

<div align='center'>
<Font Color ="#333366">
<br><b><h3>Recomposition automatique des données d’enquêtes.</h3></b><br>
</div>
</Font>


<align = left>
<Font face = "arial" size ="2" Color="#555555">
<dd>Une enquête de pêche est l'opération élémentaire d'observation des débarquements. Dans un cas idéal, toutes les informations demandées sont relevées par l'enquêteur. Dans la plupart des cas, une partie de l'information manque.
Le but de ce module est de recomposer toutes les enquêtes, une par une, pour obtenir des enquêtes idéales. Cette recomposition comprend 3 phases : 
<dd><LI>une estimation du nombre et du poids des poissons d'une fraction dite débarquée
<dd><LI>une comparaison des poids des fractions débarquées avec le poids total du débarquement annoncé par l'enquêteur
<dd><LI>la prise en compte éventuelle de fractions non observées directement par l'enquêteur.
</Font>


<br><br>
<div align='center'>
<Font Color ="#333366">
<b>La Base PPEAO contient :</b>
</div>
</Font>


<?php

$user="devppeao";                      // Le nom d'utilisateur 
$passwd="2devppe!!";                   // Le mot de passe 
$host= "vmppeao.mpl.ird.fr";  // L'hôte (ordinateur sur lequel le SGBD est installé) 
//$bdd = "BD2_Peche";
//$bdd = $_POST['base'];

//print($bdd);


//////////////////////////////////////////////////////////////////////////////////////////////
//                      Récupération du nb d'enregistrements à traiter                      //
//                                 et du nb déjà traités                                    //
//////////////////////////////////////////////////////////////////////////////////////////////

$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
if (!$connection) {  echo "Une erreur de connection au serveur s'est produite"; exit;}

// Creation et envoi de la requete
$query = "select count(art_debarquement.id) FROM art_debarquement";

$result = pg_query($connection, $query);
if (!$result) {  echo "Une erreur s'est produite";  exit;}


// Recuperation du resultat
$row= pg_fetch_row($result);
$nb_enr = $row[0];
print ("<div align='center'><br>".$nb_enr . " enquêtes à traiter dont");

// Deconnexion de la base de donnees
pg_close();



$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
$query = "select count(id) FROM art_debarquement_rec";
$result = pg_query($connection, $query);
$row= pg_fetch_row($result);
$nb_deja_rec = $row[0];
if ($nb_deja_rec == 0){print (" ".$nb_deja_rec . " enquête déjà recomposée. </div>");}
else {print (" ".$nb_deja_rec . " enquêtes déjà recomposées. </div>");}
pg_close();
?>

<div align='center'>

<form name="form" method="post" action="test_recompose.php" >
  <p>
    Vous pouvez entrer une adresse mail.<br>
    <INPUT type=text name="adresse">
    <br><br>
    Si vous rentrez une adresse valide, 
    il vous sera envoyé un mail de confirmation à la fin de la recomposition des données.<br>
    Vous pouvez fermer la fenêtre suivante pendant le traitement.<br><br>
    
    <input type=hidden name="nb_enr" value="<?php print($nb_enr);?>" >
    <input type=hidden name="base" value="<?php print($bdd);?>" >
    <input type="submit" name="Recomposition" value="        Recomposer les données        " onclick="pop_it3(form);">
 
  </p>
</form>

<form name="form2" method="post" action="test_efface.php" onsubmit="return confirmer(this);">
    <input type="hidden" id="alerte" value="0">
    <input type=hidden name="base" value="<?php print($bdd);?>" >
<p> <input type="submit" name="Effacement" value="Effacer les données recomposées" onClick="document.getElementById('alerte').value='1';"/></p>
</form>
</div>
<div align='center'>
<form name="fo" method="post" action="../page_accueil.php">
<p><input type="submit" name="Retour" value="   Retour   "></p>
</form>
</div>


</body>
</html>



