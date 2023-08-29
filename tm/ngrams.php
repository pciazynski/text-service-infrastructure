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

$psg = strtolower($psg);

$punctarr = array(",",".","!","?","―");
$psg = str_replace($punctarr, " SENTENCESTOP ", $psg);
$replacearr = array("―","¼","%","⅕","⅙","•","⅓","☚","☛","+","|","*","¾","#","'","}","=","/","!","”","½","1","2","3","4","5","6","7","8","9","0","…",'"',"–",",",".","?","(",")","[","]",";",":","—","“","-","„");
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
foreach(array_keys($rs) as $key){
	echo $key."\t".$rs[$key]."\n";
}

?>