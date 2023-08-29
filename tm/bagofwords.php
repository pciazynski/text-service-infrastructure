<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage.php');


$urn = trim(htmlspecialchars($_GET["urn"]));
$wordbag = array();
$psg="";

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){$psg = passage($urn,true, $deleteXML=true);}
	elseif (strpos ($urnarr[4],"-")){$psg =  spanningPassage($urn, $deleteXML=true);}
	else {$psg = passage($urn, false, $deleteXML=true);};
}


$psg = strtolower($psg);

$replacearr = array("―","¼","%","⅕","⅙","•","⅓","☚","☛","+","|","*","¾","#","'","}","=","/","!","”","½","1","2","3","4","5","6","7","8","9","0","…",'"',"–",",",".","?","(",")","[","]",";",":","—","“","-","„");
$psg = str_replace($replacearr, " ", $psg);

$psgarr = explode(" ",$psg);
foreach ($psgarr as $token){
	if(strlen(trim($token)) >0) 
	{
		if (array_key_exists($token, $wordbag)){
			$wordbag[$token] = $wordbag[$token]+1;
		}else{
			$wordbag[$token] = 1;
		}
	}
}

arsort($wordbag);
foreach(array_keys($wordbag) as $key){
	echo $key."\t".$wordbag[$key]."\n";

}

?>