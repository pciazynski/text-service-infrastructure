<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../constants.php');
require('../stopwords.php');
require('../db_getPassage.php');


$urn = trim(htmlspecialchars($_GET["urn"]));
$wordbag = array();
$entitybag = array();
$psg="";

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){$psg = passage($urn,true, $deleteXML=true);}
	elseif (strpos ($urnarr[4],"-")){$psg =  spanningPassage($urn, $deleteXML=true);}
	else {$psg = passage($urn, false, $deleteXML=true);};
}


$psg = str_replace($replacearr, " ", $psg);

$psgarr = explode(" ",$psg);


foreach ($psgarr as $token){
	if(strlen(trim($token)) >3 && preg_match('~^\p{Ll}~u', $token)) 
	{
		if (! in_array(strtolower($token), $stopwords)){
			$wordbag[$token] = 1;
		}
	}
}

foreach ($psgarr as $token){
	if(strlen(trim($token)) >3 && !preg_match('~^\p{Ll}~u', $token)) 
	{
		if (! in_array(strtolower($token), $stopwords) && ! array_key_exists(strtolower($token), $wordbag)){
		if (array_key_exists($token, $entitybag)){
				$entitybag[$token] = $entitybag[$token]+1;
			}else{
				$entitybag[$token] = 1;
			}
		}
	}
}

arsort($entitybag);
foreach(array_keys($entitybag) as $key){
	echo $key."\t".$entitybag[$key]."\n";

}

?>