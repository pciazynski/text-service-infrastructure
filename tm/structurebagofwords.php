<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns every child URN of a given static URN plus the corresponding text snippet bagofwords. Dynamic URNs are not (yet) supported. 
# Params: urn, (deletexml)

function bagofwords($text){
	$wordbag = array();
	$psgarr = explode(' ',$text);
	foreach ($psgarr as $token){
		$token = trim($token);
		if(strlen($token) >0) {
			(array_key_exists($token, $wordbag)) ? $wordbag[$token] = $wordbag[$token]+1 : $wordbag[$token] = 1;
		}
	}
	arsort($wordbag);
	
	$colon = ':';
	$comma = ',';
	$res = '';

	foreach(array_keys($wordbag) as $key){
		$res.=$key.$colon.$wordbag[$key].$comma;
	}
	return rtrim($res,',');
}

function getDocStrct($urn){
	global $sql;
	global $binary;
	global $dbtablename;
	global $multibyte;
	global $replacearr;
	$stmt = $sql->prepare('SELECT urn,type,text FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%']):$stmt->execute([$urn.'.%']);

	$res = '';
	$tab = "\t";
	$nl = "\n";
	
	if (isset($_GET['lowercase'])){
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$text = preg_replace('/<[^>]+>/', '', $row['text']);
			$text = str_replace($replacearr, ' ', $text);
			($multibyte) ? $text = mb_strtolower($text,'UTF-8') : $text = strtolower($text);
			$res = $res.$row['urn'].$tab.$row['type'].$tab.bagofwords($text).$nl;
		}
	}
	else{
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$text = preg_replace('/<[^>]+>/', '', $row['text']);
			$text = str_replace($replacearr, ' ', $text);
			$res = $res.$row['urn'].$tab.$row['type'].$tab.bagofwords($text).$nl;
		}
	}

	return trim($res);
}
$urn = checkurn($_GET['urn'],'');

if($dbtablename == 'urndata'){
	echo getDocStrct($urn);
}
else{
	if(restrictedAccess()){
		echo getDocStrct($urn);
	}
	else{
		require('../errormsg/access.php');
	}
}


?>
