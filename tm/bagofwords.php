<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage_restr.php');


$urn = trim(htmlspecialchars($_GET["urn"]));
$wordbag = array();
$psg = "";

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){
		$psg = passage($urn,$deleteXML = true, $newlines=false);
	}
	elseif (strpos ($urnarr[4],"-")){$psg =  spanningPassage($urn, $deleteXML=true);}
	else {$psg = passage($urn, $deleteXML = true, $newlines=false);};
}

if (isset($_GET["lowercase"])){
	($multibyte) ? $psg = mb_strtolower($psg,'UTF-8') : $psg = strtolower($psg);
}

$psg = str_replace($replacearr, " ", $psg);

$psgarr = explode(" ",$psg);
foreach ($psgarr as $token){
	$token = trim($token);
	if(strlen($token) >0) {
		(array_key_exists($token, $wordbag)) ? $wordbag[$token] = $wordbag[$token]+1 : $wordbag[$token] = 1;
	}
}


(isset($_GET["sort"])) ? arsort($wordbag) : NULL;

$tab = "\t";
$nl = "\n";
foreach(array_keys($wordbag) as $key){
	echo $key.$tab.$wordbag[$key].$nl;

}


?>