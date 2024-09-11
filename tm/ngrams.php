<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage.php');

$urn = trim(htmlspecialchars($_GET["urn"]));
$ngramgoalsize = trim(htmlspecialchars($_GET["n"]));
$rs = array();

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

$psg = str_replace($punctarr, " SENTENCESTOP ", $psg);
$psg = str_replace($replacearr, " ", $psg);


$sentences = explode(" SENTENCESTOP ",$psg);
foreach ($sentences as $sentence){
	$ngram = "";
	$psgarr = explode(" ",$sentence);
	foreach ($psgarr as $token){
		$token = trim($token);
		if(strlen($token) >0)
		{
			$ngram.=" ".$token;
			$ngram = trim($ngram);
			if(count(explode(" ",$ngram)) == $ngramgoalsize){
				if (array_key_exists($ngram, $rs)){
					$rs[$ngram] = $rs[$ngram]+1;
				}else{
					$rs[$ngram] = 1;
				}
				$ngram = explode(" ",$ngram,2)[1];
			}
		}
	}
}

if(isset($_GET["sort"])){
	arsort($rs);
}

$tab = "\t";
$nl = "\n";
foreach(array_keys($rs) as $key){
	echo $key.$tab.$rs[$key].$nl;
}

?>