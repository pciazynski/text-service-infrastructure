<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns every child URN of a given static URN plus the corresponding text snippet bagofwords. Dynamic URNs are not (yet) supported. 
# Params: urn, (deletexml)

function bagofwords($text){
	$wordbag = array();
	$psgarr = explode(" ",$text);
	foreach ($psgarr as $token){
		$token = trim($token);
		if(strlen($token) >0) {
			(array_key_exists($token, $wordbag)) ? $wordbag[$token] = $wordbag[$token]+1 : $wordbag[$token] = 1;
		}
	}
	(isset($_GET["sort"])) ? arsort($wordbag) : NULL;

	$tab = ":";
	$nl = ",";
	$res = "";

	foreach(array_keys($wordbag) as $key){
		$res.=$key.$tab.$wordbag[$key].$nl;
	}
	return($res);
}

function getDocStrct($urn){
	global $sql;
	global $binary;
	global $dbtablename;
	$query = "SELECT urn,type,text FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";
	$res = "";
	$tab = "\t";
	$nl = "\n";
	
	if (isset($_GET["lowercase"])){
		$text=bagofwords($row['text']);
		($multibyte) ? $text = mb_strtolower($text,'UTF-8') : $text = strtolower($text);
		$res = $res.$row['urn'].$tab.$row['type'].$text.$nl;
	}
	else{
		foreach ($sql->query($query) as $row) {
			$text=bagofwords($row['text']);
			$res = $res.$row['urn'].$tab.$row['type'].$text.$nl;
		}
	}
	
	if(isset($_GET["deletexml"])){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	
	return trim($res);
}
$urn = trim(htmlspecialchars($_GET["urn"]));
$urnarr = checkurn($urn);

if ($urnarr !== false){
	if($dbtablename == "urndata"){
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
}

?>
