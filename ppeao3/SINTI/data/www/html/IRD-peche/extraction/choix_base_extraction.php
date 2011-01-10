<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<META NAME="author" CONTENT="Jérome Fauchier">
</script>
</head>
<body background="fond.png">

<div align='center'>
<Font Color ="#333366">
<br><br><b>EXTRACTION DE DONNEES</b><br>
</div>
</Font>

<?php
$login = $_POST['login'];
$passe = $_POST['passe'];

?>


<div align='center'>
<Font Color ="#333366">
<br><br><b>Choix de la base :</b><br>
</div>
</Font>

<br><br>
<div align='center'>
Entrez le nom de la Base.
<br>
<form name="form" method="post" action="preselection.php" >
  <p>
    <?php
    print ("<input type=hidden name=\"login\" value=\"".$login."\">");
    print ("<input type=hidden name=\"passe\" value=\"".$passe."\">");
    ?>
    <INPUT type=text name="base">
 <br><br>
 <input type="submit" value="valider">
  </p>
</form>

</div>


</body>
</html>