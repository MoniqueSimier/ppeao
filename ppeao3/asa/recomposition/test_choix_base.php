<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">


<script type="text/javascript">

function pop_it3(the_form) {
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
<br><br><b>Recomposition des données</b><br>
</div>
</Font>


<div align='center'>
<Font Color ="#333366">
<br><br><b>Choix de la base :</b><br>
</div>
</Font>

<br><br>
<div align='center'>
Entrez le nom de la Base.
<br>
<form name="form" method="post" action="test_appel.php" >
  <p>
    
    <INPUT type=text name="base">
 <br><br>
 <input type="submit" name="sss" value="valider">
  </p>
</form>

</div>


</body>
</html>