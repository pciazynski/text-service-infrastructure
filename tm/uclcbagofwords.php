<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../constants.php');
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

if(isset($_GET["upc"])){$lc = false;}else{$lc=true;}

$psg = str_replace($replacearr, " ", $psg);

$psgarr = explode(" ",$psg);
foreach ($psgarr as $token){
	if(strlen(trim($token)) >0 && $lc== preg_match('~^\p{Ll}~u', $token)) 
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