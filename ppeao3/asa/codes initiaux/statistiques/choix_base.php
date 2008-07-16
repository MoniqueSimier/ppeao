<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">

<script type="text/javascript">

function pop_it2(the_form) {
   my_form = eval(the_form);
   window.open("blanc.html", "popup", "height=300,width=500,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes");
   my_form.target = "popup";
   my_form.submit();
}


</script>


</head>
<body>

<div align='center'>
<Font Color ="#333366">
<br><br><b><h3>Calcul des statistiques de pêche par agglomération enquêtée.</h3></b><br>
</div>
</Font>



<div align='center'>




<form name="form" method="post" action="statistiques3_ppeao.php" >
  <p>
    <br><br>
    Entrez le nom de la base de données à traiter.<br>
    <INPUT type=text name="base">
    <br><br>
    
    
    Entrez une adresse mail.<br>
    
    
    <INPUT type=text name="adresse">
    <br><br>
    Si vous rentrez une adresse valide, 
    il vous sera envoyé un mail de confirmation à la fin de la création des statistiques de pêche.<br>
    Vous pouvez fermer la fenêtre suivante pendant le traitement.
 <br><br>
 <input type="submit" value="valider" onclick="pop_it2(form);">
  </p>
</form>

</div>


</body>
</html>