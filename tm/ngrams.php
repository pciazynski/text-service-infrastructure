<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage_restr.php');

$ngramgoalsize = trim(htmlspecialchars($_GET['n']));

if (isset($_GET['urn']) and $ngramgoalsize>=2 and (!$restricteddocuments or $ngramgoalsize<10)){
	$rs = array();
	$urn = checkurn($_GET['urn'],'');
	$urnarr = explode(':',$urn);
	if($dbtablename == 'urndata'){
		if (strlen($urnarr[4]) == 0){$psg = passage($urn, $deleteXML=true);}
		elseif (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn, $deleteXML=true);}
		else {$psg = passage($urn, $deleteXML=true);};
	}
	else{
		if(restrictedAccess()){
			if (strlen($urnarr[4]) == 0){$psg = passage($urn, $deleteXML=true);}
			elseif (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn, $deleteXML=true);}
			else {$psg = passage($urn, $deleteXML=true);};
		}
		else{
			if (strlen($urnarr[4]) == 0){$psg = passage($urn, $deleteXML=true);}
		}
	}
	
	if (isset($_GET['lowercase'])){
		($multibyte) ? $psg = mb_strtolower($psg,'UTF-8') : $psg = strtolower($psg);
	}

	$psg = str_replace($punctarr, ' SENTENCESTOP ', $psg);
	$psg = str_replace($replacearr, ' ', $psg);

	$sentences = explode(' SENTENCESTOP ',$psg);
	foreach ($sentences as $sentence){
		$ngram = '';
		$psgarr = explode(' ',$sentence);
		foreach ($psgarr as $token){
			$token = trim($token);
			if(strlen($token) >0){
				$ngram.=' '.$token;
				$ngram = trim($ngram,' ');
				if(count(explode(' ',$ngram)) == $ngramgoalsize){
					(array_key_exists($ngram, $rs)) ? $rs[$ngram] = $rs[$ngram]+1 : $rs[$ngram] = 1;
					$ngram = explode(' ',$ngram,2)[1];
				}
			}
		}
	}
	(isset($_GET['sort'])) ? arsort($rs) : NULL;

	$tab = "\t";
	$nl = "\n";
	$res = '';
	foreach(array_keys($rs) as $key){
		$res.= $key.$tab.$rs[$key].$nl;
	}
	echo($res);
}
?>