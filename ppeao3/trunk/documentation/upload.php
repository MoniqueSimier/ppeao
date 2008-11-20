<?php
$ContentDiv .="<div id=\"upload\"><h2 >Compte-rendu du chargement</h2>";
if (isset($_POST['repName'])) {
	$pathUpload = "documentation/data/".$_POST['repName']."/";
} 

if (! file_exists($pathUpload)) {
	if (! mkdir($pathUpload)) {
		$ContentDiv .= 'Impossible de cr&eacute;er le r&eacute;pertoire '.$pathUpload."<br/>";
	}
}		

if (isset($_POST['max_file_size'])) {
	$taille_maxi = intval($_POST['max_file_size']);
}
$fichier = basename($_FILES['avatar']['name']);
$taille = filesize($_FILES['avatar']['tmp_name']);
$extensions = array('.png', '.gif', '.jpg', '.jpeg');
$extension = strrchr($_FILES['avatar']['name'], '.');

//Début des vérifications de sécurité...
if ( !is_uploaded_file ($_FILES['avatar']['tmp_name']) ) {
	$erreur = "Le fichier n'a pas ete telecharge";
}
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
}
if($taille>$taille_maxi)
{
     $erreur = 'Le fichier est trop gros...';
}
if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
{
     //On formate le nom du fichier ici...
     $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜİàáâãäåçèéêëìíîïğòóôõöùúûüıÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     if(move_uploaded_file($_FILES['avatar']['tmp_name'], $pathUpload . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
          $ContentDiv .="Upload effectu&eacute; avec succ&egrave;s !<br/>";
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          $ContentDiv .="Echec de l'upload !<br/>";
     }
}
else
{
     $ContentDiv .= $erreur."<br/>";
}
$ContentDiv .="fin upload<br/>";
$ContentDiv .="</div>";

?>