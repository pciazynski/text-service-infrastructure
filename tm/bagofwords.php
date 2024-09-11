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


if (isset($_GET["lowercase"])){
	if ($multibyte){
		$psg = mb_strtolower($psg,'UTF-8');
	}
	else{
		$psg = strtolower($psg);
	}
}

$psg = str_replace($replacearr, " ", $psg);

$psgarr = explode(" ",$psg);
foreach ($psgarr as $token){
	$token = trim($token);
	if(strlen($token) >0) 
	{
		if (array_key_exists($token, $wordbag)){
			$wordbag[$token] = $wordbag[$token]+1;
		}else{
			$wordbag[$token] = 1;
		}
	}
}

arsort($wordbag);
$tab = "\t";
$nl = "\n";
foreach(array_keys($wordbag) as $key){
	echo $key.$tab.$wordbag[$key].$nl;

}

?>