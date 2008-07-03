<?

$timestamp=time();

echo("timestamp: ".$timestamp."<br />");


$theDate="2008-07-01";

echo("theDate: ".$theDate."<br />");

$exDate=explode("-",$theDate);

echo("timestamp pour theDate: ".mktime(0,0,0,$exDate[1],$exDate[2],$exDate[0])."<br />")

?>