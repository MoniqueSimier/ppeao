<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">

</head>
<body>

<?php
/*$user="devppeao";                      // Le nom d'utilisateur 
$passwd="2devppe!!";                   // Le mot de passe 
$host= "vmppeao.mpl.ird.fr";  // L'hôte (ordinateur sur lequel le SGBD est installé) 
*/
//$bdd = "BD2_Peche";


$bdd = $_POST['base'];
include("../connexion.php");
print("travail sur la base : ".$bdd);



//////////////////////////////////////////////////////////////////////////////////////////////
//                                   EFFACEMENT DES DONNEES DE                              //
//                                     ART_DEBARQUEMENT_REC ET                              //
//                                      DE ART_FRACTION_REC                                 //
//////////////////////////////////////////////////////////////////////////////////////////////

//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
//if (!$connection) {  echo "pas de connection"; exit;}

$query = "delete from art_fraction_rec;";

$result = pg_exec($connection, $query);
if (!$result) {  echo "Une erreur s'est produite  "; print($query);  exit;}

//$connection =pg_connect ("host=".$host." dbname=".$bdd." user=".$user." password=".$passwd);
//if (!$connection) {  echo "pas de connection"; exit;}

$query = "delete from art_debarquement_rec;";

$result = pg_exec($connection, $query);
if (!$result) {  echo "Une erreur s'est produite  "; print($query);  exit;}


pg_free_result($result);
pg_close();

?>

<br><br>
<div align='center'>
<Font Color ="#333366">
<br><br><b>Données effacées avec succès</b><br>
</div>
</Font>


<div align='center'>
<form name="form_eff" method="post" action="test_appel.php">
  <p>
    <input type=hidden name="base" value="<?php print($bdd); ?>">
    <input type="submit" name="Recomposer les données" value="Retour" onClick= "confirm("Etes vous sûr ?")">
  	
  </p>
</form>
</div>
</body>
</html>