<?php 

// VARIOUS UTILITIES USED IN OTHER SCRIPTS


//***************************************************************************************************
// stocke l'URL complète de la page courante dans le tableau de variables superglobales
function storeUrl() {
	
	$_SERVER['FULL_URL'] = 'http';
	$script_name = '';
	if(isset($_SERVER['REQUEST_URI'])) {
	    $script_name = $_SERVER['REQUEST_URI'];
	} else {
	    $script_name = $_SERVER['PHP_SELF'];
	    if($_SERVER['QUERY_STRING']>' ') {
	        $script_name .=  '?'.$_SERVER['QUERY_STRING'];
	    }
	}
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
	    $_SERVER['FULL_URL'] .=  's';
	}
	$_SERVER['FULL_URL'] .=  '://';
	
	if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {$theHost=$_SERVER['HTTP_X_FORWARDED_HOST'];} else {$theHost=$_SERVER['HTTP_HOST'];}
	
	$_SERVER['FULL_URL'] .=  $theHost.$script_name;
	
}


//***************************************************************************************************
//returns a human-readable file size
function readableFileSize($file_size)
// this function takers a filesize in bytes and converts it into something readable : KB, MB, GB etc.
{


if ($file_size >= 1073741824) {
       $show_filesize = number_format(($file_size / 1073741824),2) . " GB";
} elseif ($file_size >= 1048576) {
       $show_filesize = number_format(($file_size / 1048576),2) . " MB";
} elseif ($file_size >= 1024) {
       $show_filesize = number_format(($file_size / 1024),0) . " KB";
} elseif ($file_size >= 0) {
       $show_filesize = $file_size . " bytes";
} else {
       $show_filesize = "0 bytes";
}


return $show_filesize;
}


//***************************************************************************************************
// creates a scandir function in case of PHP version<5 on the server
if(!function_exists('scandir')) {
    function scandir($dir, $sortorder = 0) {
        if(is_dir($dir) && $dirlist = @opendir($dir)) {
            while(($file = readdir($dirlist)) !== false) {
                $files[] = $file;
            }
            closedir($dirlist);
            ($sortorder == 0) ? asort($files) : rsort($files); // arsort was replaced with rsort
            return $files;
        } else return false;
    }
}


//***************************************************************************************************
// deletes a directory and all its content (recursively)
function RecursiveFolderDelete ( $folderPath )
{
    if ( is_dir ( $folderPath ) )
    {
        foreach ( scandir ( $folderPath )  as $value )
        {
            if ( $value != "." && $value != ".." )
            {
                $value = $folderPath . "/" . $value;

                if ( is_dir ( $value ) )
                {
                    RecursiveFolderDelete ( $value );
                }
                elseif ( is_file ( $value ) )
                {
                    @unlink ( $value );
                }
            }
        }

        return rmdir ( $folderPath );
    }
    else
    {
        return FALSE;
    }
}



//***************************************************************************************************
// takes a string and transforms it into a "DOS-like" filename
function filename_safe($filename) { 
    $temp = $filename; 

    // Lower case 
    $temp = strtolower($temp); 

    // Replace spaces with a '_' 
    $temp = str_replace(" ", "_", $temp); 

    // Loop through string 
    $result = ''; 
    for ($i=0; $i<strlen($temp); $i++) { 
        if (preg_match('([0-9]|[a-z]|_|-)', $temp[$i])) { 
            $result = $result . $temp[$i]; 
        }     
    } 

    // Return filename 
    return $result; 
} 



//***************************************************************************************************
// creates an array_intersect_key in case of PHP version<5 on the server
if (!function_exists('array_intersect_key'))
{
  function array_intersect_key($isec, $keys)
  {
    $argc = func_num_args();
    if ($argc > 2)
    {
      for ($i = 1; !empty($isec) && $i < $argc; $i++)
      {
        $arr = func_get_arg($i);
        foreach (array_keys($isec) as $key)
        {
          if (!isset($arr[$key]))
          {
            unset($isec[$key]);
          }
        }
      }
      return $isec;
    }
    else
    {
      $res = array();
      foreach (array_keys($isec) as $key)
      {
        if (isset($keys[$key]))
        {
          $res[$key] = $isec[$key];
        }
      }
      return $res;
    }
  }
}

//***************************************************************************************************
// creates an http_build_query function in case of PHP version<5 on the server
if(!function_exists('http_build_query')) {
    function http_build_query($data,$prefix=null,$sep='',$key='') {
        $ret    = array();
            foreach((array)$data as $k => $v) {
                $k    = urlencode($k);
                if(is_int($k) && $prefix != null) {
                    $k    = $prefix.$k;
                };
                if(!empty($key)) {
                    $k    = $key."[".$k."]";
                };

                if(is_array($v) || is_object($v)) {
                    array_push($ret,http_build_query($v,"",$sep,$k));
                }
                else {
                    array_push($ret,$k."=".urlencode($v));
                };
            };

        if(empty($sep)) {
            $sep = ini_get("arg_separator.output");
        };

        return    implode($sep, $ret);
    };
};

//***************************************************************************************************
function arrayToList($listarray,$separator,$end)
// turns the values of an array into a list of values separated by a $separator and ending with a $end
{
$list='';
foreach ($listarray as $value)
			{
			$list.=stripslashes($value);
			if (next($listarray)) {$list.=$separator.'';} else {$list.=$end;}
			}
return $list;
}

//***************************************************************************************************
// parses an array and unsets records with a value of $needle
function cleanArray($array,$needle) {
        foreach ($array as $key => $value) {
            if ($value == $needle) unset($array[$key]);
        }
        return $array;
    }


//***************************************************************************************************
function subDays($date,$days)
//This function takes a date in, and substracts a number of DAYS from it, returning the resulting date
{
$newdate=@ date("Y-m-d",strtotime($date)-$days*3600*24);
return $newdate;
}


//***************************************************************************************************
function addDays($date,$days)
//This function takes a date in, and adds a number of DAYS from it, returning the resulting date
{
$newdate=@ date("Y-m-d",strtotime($date)+$days*3600*24);
return $newdate;
}

//***************************************************************************************************
function subDates($date1,$date2) {
// this function takes in two formatted dates and returns the difference between the second one and the first one, in number of seconds
	$d1=strtotime($date1);
	$d2=strtotime($date2);
	$diff=$d2-$d1;
	return $diff;
	
}

//***************************************************************************************************
function removeYear($aDate)
// this function takes an "Y-m-d" date and removes the year part, returning "m-d"
{
$newDate=substr($aDate,5);
return $newDate;
}

//***************************************************************************************************
// cette fonction retourne le dernier jour d'un mois donne pour une annee donnee
function days_in_month($year, $month) {
    return( date( "t", mktime( 0, 0, 0, $month, 1, $year) ) ); 
}

//***************************************************************************************************
// does a substring to the right of $haystack after a certain string $needle
function substringAfter($haystack, $needle)
{
$result=substr(strchr($haystack,$needle),1);
if ($result=='') {$result=$haystack;}
return $result;
}

//***************************************************************************************************
// does a substring to the left of $haystack after a certain string $needle
function substringBefore($haystack,$needle)
{
$explode=explode($needle,$haystack);
$result=$explode[0];
if ($result=='') {$result=$haystack;}
return $result;
}

//***************************************************************************************************
function IfEmpty($string,$stringOut,$replace)
//This function checks whether a $string is empty or NULL
// and if yes, returns some defined value $replace. If no, returns $stringOut
{
if ($string=='' || is_null($string)) {$stringOut=$replace;}
return $stringOut;
}

//***************************************************************************************************
//does a lookup for a &queryParam=value pair in a string $vars holding $_SERVER["QUERY_STRING"]
// and replaces the old queryParam value with the $newValue

function replaceQueryParam ($queryString,$queryParam,$newValue) {

$queryParam2=$queryParam.'=';
$newQueryString='';
$string=split('&',$queryString);
$i=0;
foreach($string as $pair)
{
if (ereg($queryParam2,$pair)) {
$trim=strstr($pair,'=');
$newpair=ereg_replace($trim,'',$pair);
$pair=$newpair.'='.$newValue;
$found=1;
}
$string[$i]=$pair;
$newQueryString.=$pair.'&';
$i++;
}

if ($found==1) {$newQueryString=substr($newQueryString, 0, strlen($newQueryString)-1); } else {$newQueryString.=$queryParam2.$newValue;}
return $newQueryString;
}


//***************************************************************************************************
// fonction permettant de supprimer un paramètre et sa/ses valeur(s) d'une URL
// 

function removeQueryStringParam($url, $key) {
// note : dans le cas de paramètres à valeurs multiples (&param[]=value1&param[]=value2)
//        il convient de passer $key en protégeant les [] : 'param\[\]'
	$previousUrl='';
	$newUrl=$url;
	$i=0;
	// tant que le résultat du preg_replace est différent de l'url source, on continue à l'effectuer récursivement
	while ($newUrl!=$previousUrl) {
		$previousUrl=$newUrl;
		$newUrl = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $previousUrl . '&');
    	$newUrl = substr($newUrl, 0, -1);
	
		// note : ceci est une sécurité au cas où le while pête un cable
		$i++;
		if ($i>200) {break;}
	
	} // end while

    return ($newUrl);
}

//***************************************************************************************************
// fonction permettant de d'ajouter un paramètre et sa valeur à une URL
// si le paramètre est déjà présent dans l'URL, sa valeur est remplacée par la nouvelle
// note : dans le cas de paramètres à valeurs multiples (param[]), la fonction ajoute toujours une nouvelle valeur
// 		  pour supprimer les valeurs existantes, utiliser la fonction removeQueryStringParam() ci-dessus
//
function addQueryStringParam($url, $key, $value) {
    $url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
    $url = substr($url, 0, -1);
    if (strpos($url, '?') === false) {
        return ($url . '?' . $key . '=' . $value);
    } else {
        return ($url . '&' . $key . '=' . $value);
    }
}


//***************************************************************************************************
// cette fonction affiche une barre de pagination du type de celle utilisée par flickr
/**
 * paginate($url, $param, $total, $current [, $adj]) appelée à chaque affichage de la pagination
 * @param string $url - URL ou nom de la page appelant la fonction, ex: 'index.php' ou 'http://example.com/'
 * @param string $param - paramètre à ajouter à l'URL, ex: '?page=' ou '&amp;p='
 * @param int $total - nombre total de pages
 * @param int $current - numéro de la page courante
 * @param int $adj (facultatif) - nombre de numéros de chaque côté du numéro de la page courante (défaut : 3)
 * @return string $pagination
 */
function paginate($url, $param, $total, $current, $adj=3)
{
	/* Déclaration des variables */
	$prev = $current - 1; // numéro de la page précédente
	$next = $current + 1; // numéro de la page suivante
	$n2l = $total - 1; // numéro de l'avant-dernière page (n2l = next to last)

	/* Initialisation : s'il n'y a pas au moins deux pages, l'affichage reste vide */
	$pagination = '';

	/* Sinon ... */
	if ($total > 1)
	{
		/* Concaténation du <div> d'ouverture à $pagination */
		$pagination .= "<div class=\"pagination\">\n";

		/* ////////// Début affichage du bouton [précédent] ////////// */
		if ($current == 2) // la page courante est la 2, le bouton renvoit donc sur la page 1, remarquez qu'il est inutile de mettre ?p=1
			$pagination .= "<a href=\"{$url}\"><</a>";
		elseif ($current > 2) // la page courante est supérieure à 2, le bouton renvoit sur la page dont le numéro est immédiatement inférieur
			$pagination .= "<a href=\"{$url}{$param}{$prev}\"><</a>";
		else // dans tous les autres, cas la page est 1 : désactivation du bouton [précédent]
			$pagination .= '<span class="inactive"><</span>';
		/* Fin affichage du bouton [précédent] */

		/* ///////////////
		Début affichage des pages, l'exemple reprend le cas de 3 numéros de pages adjacents (par défaut) de chaque côté du numéro courant
		- CAS 1 : il y a au plus 12 pages, insuffisant pour faire une troncature
		- CAS 2 : il y a au moins 13 pages, on effectue la troncature pour afficher 11 numéros de pages au total
		/////////////// */

		/* CAS 1 */
		if ($total < 7 + ($adj * 2))
		{
			/* Ajout de la page 1 : on la traite en dehors de la boucle pour n'avoir que index.php au lieu de index.php?p=1 et ainsi éviter le duplicate content */
			$pagination .= ($current == 1) ? '<span class="active_page">1</span>' : "<a href=\"{$url}\">1</a>"; // Opérateur ternaire : (condition) ? 'valeur si vrai' : 'valeur si fausse'

			/* Pour les pages restantes on utilise une boucle for */
			for ($i = 2; $i<=$total; $i++)
			{
				if ($i == $current) // Le numéro de la page courante est mis en évidence (cf fichier CSS)
				$pagination .= "<span class=\"active_page\">{$i}</span>";
				else // Les autres sont affichés normalement
				$pagination .= "<a href=\"{$url}{$param}{$i}\">{$i}</a>";
			}
		}

		/* CAS 2 : au moins 13 pages, troncature */
		else
		{
			/*
			Troncature 1 : on se situe dans la partie proche des premières pages, on tronque donc la fin de la pagination.
			l'affichage sera de neuf numéros de pages à gauche ... deux à droite (cf figure 1)
			*/
			if ($current < 2 + ($adj * 2))
			{
				/* Affichage du numéro de page 1 */
				$pagination .= ($current == 1) ? "<span class=\"active_page\">1</span>" : "<a href=\"{$url}\">1</a>";

				/* puis des huit autres suivants */
				for ($i = 2; $i < 4 + ($adj * 2); $i++)
				{
				if ($i == $current)
					$pagination .= "<span class=\"active_page\">{$i}</span>";
					else
					$pagination .= "<a href=\"{$url}{$param}{$i}\">{$i}</a>";
				}

				/* ... pour marquer la troncature */
				$pagination .= ' ... ';

				/* et enfin les deux derniers numéros */
				$pagination .= "<a href=\"{$url}{$param}{$n2l}\">{$n2l}</a>";
				$pagination .= "<a href=\"{$url}{$param}{$total}\">{$total}</a>";
			}

			/*
			Troncature 2 : on se situe dans la partie centrale de notre pagination, on tronque donc le début et la fin de la pagination.
			l'affichage sera deux numéros de pages à gauche ... sept au centre ... deux à droite (cf figure 2)
			*/
			elseif ( (($adj * 2) + 1 < $current) && ($current < $total - ($adj * 2)) )
			{
				/* Affichage des numéros 1 et 2 */
				$pagination .= "<a href=\"{$url}\">1</a>";
				$pagination .= "<a href=\"{$url}{$param}2\">2</a>";

				$pagination .= ' ... ';

				/* les septs du milieu : les trois précédents la page courante, la page courante, puis les trois lui succédant */
				for ($i = $current - $adj; $i <= $current + $adj; $i++)
				{
					if ($i == $current)
					$pagination .= "<span class=\"active_page\">{$i}</span>";
					else
					$pagination .= "<a href=\"{$url}{$param}{$i}\">{$i}</a>";
				}

				$pagination .= ' ... ';

				/* et les deux derniers numéros */
				$pagination .= "<a href=\"{$url}{$param}{$n2l}\">{$n2l}</a>";
				$pagination .= "<a href=\"{$url}{$param}{$total}\">{$total}</a>";
			}

			/*
			Troncature 3 : on se situe dans la partie de droite, on tronque donc le début de la pagination.
			l'affichage sera deux numéros de pages à gauche ... neuf à droite (cf figure 3)
			*/
			else
			{
				/* Affichage des numéros 1 et 2 */
				$pagination .= "<a href=\"{$url}\">1</a>";
				$pagination .= "<a href=\"{$url}{$param}2\">2</a>";

				$pagination .= ' ... ';

				/* puis des neufs dernières */
				for ($i = $total - (2 + ($adj * 2)); $i <= $total; $i++)
				{
					if ($i == $current)
						$pagination .= "<span class=\"active_page\">{$i}</span>";
					else
						$pagination .= "<a href=\"{$url}{$param}{$i}\">{$i}</a>";
				}
			}
		}
		/* Fin affichage des pages */

		/* ////////// Début affichage du bouton [suivant] ////////// */
		if ($current == $total)
			$pagination .= "<span class=\"inactive_page\"> > </span>\n";
		else
			$pagination .= "<a href=\"{$url}{$param}{$next}\"> > </a>\n";
		/* Fin affichage du bouton [suivant] */

		/* </div> de fermeture */
		$pagination .= "</div>\n";
	}

	/* Fin de la fonction, renvoi de $pagination au programme */
	return ($pagination);
}

//***************************************************************************************************
// fonction transformant les balises <br /> d'une chaine en sauts de ligne (inverse de nl2br)
function br2nl( $data ) {
// $data : la chaine de caractères à traiter
   return preg_replace( '!&lt;br /&gt;!iU', "\n", $data );
}
//***************************************************************************************************
// fonction testant si une variable est vide mais ne considerant pas que 0 est "vide"
// la fonction PHP empty() considère que $var=0 est vide!
function my_empty($val) {
    return empty($val) && $val !==0 && $val!=='0'; 
}

?>