<?

// VARIOUS UTILITIES USED IN OTHER SCRIPTS

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
// does a substring to the right of $haystack after a certain string $needle
function substringAfter($haystack, $needle)
{
$result=substr(strchr($haystack,$needle),1);
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



?>