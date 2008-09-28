<?php
//*****************************************
// back.inc
//*****************************************
// Created by Yann Laurent
// 2008-09-25 : creation
//*****************************************
			
echo "<br/>";
echo "<form name=\"fortion\" method=\"post\" action=\"preselection.php\">";
echo "<input type=\"submit\" name=\"\" value=\"  Retour  \">";
echo "<input type=hidden name=\"type\" value=\"".$type."\">";
echo "<input type=hidden name=\"type_donnees\" value=\"".$type_donnees."\">";
echo "</form><br/>";
echo "<form name=\"fortion2\" method=\"post\" action=\"preselection.php\">";
echo "<input type=\"submit\" name=\"\" value=\"      Fin     \" onClick= \"return confirm('Etes vous sûr ?')\"></form>";

?>
