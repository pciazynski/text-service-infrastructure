<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../stopwords.php');
require('../db_getPassage_restr.php');

$urn = checkurn($_GET['urn'],'');
$wordbag = array();
$entitybag = array();
$psg='';

$urn = checkurn($urn,'');
$urnarr = explode(':',$urn);

if (strlen($urnarr[4]) == 0){$psg = passage($urn, $deleteXML=true);}
elseif (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn, $deleteXML=true);}
else {$psg = passage($urn, $deleteXML=true);};

$psg = str_replace($replacearr, ' ', $psg);
$psgarr = explode(' ',$psg);

foreach ($psgarr as $token){
	$token = trim($token);
	if(strlen($token) >2 && preg_match('~^\p{Ll}~u', $token)) {
		(! in_array(strtolower($token), $stopwords)) ? $wordbag[$token] = 1 : NULL;
	}
}

foreach ($psgarr as $token){
	$token = trim($token);
	if(strlen($token) >3 && !preg_match('~^\p{Ll}~u', $token)) {
		if (! in_array(strtolower($token), $stopwords) && ! array_key_exists(strtolower($token), $wordbag)){
			(array_key_exists($token, $entitybag)) ? $entitybag[$token] = $entitybag[$token]+1 : $entitybag[$token] = 1;
		}
	}
}
$tab = "\t";
$nl = "\n";
$res = '';

(isset($_GET['sort'])) ? arsort($entitybag) : NULL;

foreach(array_keys($entitybag) as $key){
	$res.= $key.$tab.$entitybag[$key].$nl;
}
echo($res);
?>