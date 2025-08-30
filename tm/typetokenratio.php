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

($multibyte) ? $psg = mb_strtolower($psg,'UTF-8') : $psg = strtolower($psg);

$psg = str_replace($replacearr, " ", $psg);

$psgarr = explode(" ",$psg);
$toksum = 0;
$typesum = 0;
foreach ($psgarr as $token){
	$token = trim($token);
	$toksum = $toksum + 1; 
	if(strlen($token) >0) {
		if (!array_key_exists($token, $wordbag)){
			$wordbag[$token] = 1;
			$typesum = $typesum + 1;
		}
	}
}

echo($typesum."/".$toksum."\t".$typesum/$toksum);


?>