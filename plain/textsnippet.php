<?php
header('Content-Type: text/plain');

require('../functions.php');
require('../config.php');

function snippet($urn, $length){
	global $sql;
	global $binary;
	global $dbtablename;
	
	if(str_ends_with($urn,":")){
		$query = "SELECT urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."%' LIMIT 1";
	}
	else {$query = "SELECT urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn.".%' OR urn LIKE ".$binary." '".$urn."' LIMIT 1";}
	
	foreach ($sql->query($query) as $row) {
		$urnid = $row['urnid'];
	}

	#LIKE urn.% OR (exactly) LIKE urn
	if(str_ends_with($urn,":")){
		$query = 'SELECT text FROM '.$dbtablename.' WHERE urnid BETWEEN '.$urnid.' AND '.($urnid+1000).' ORDER BY urnid';
	}
	else {$query = 'SELECT text FROM '.$dbtablename.' WHERE urnid BETWEEN '.$urnid.' AND '.($urnid+1000).' ORDER BY urnid';}

	$res = "";
	(isset($_GET['nl'])) ? $sep = "\n" : $sep = " ";

	foreach ($sql->query($query) as $row) {
		if(strlen($res.$row['text'])>$length){continue;}
		$res = trim($res.$sep.$row['text']).$sep;
	}
	(isset($_GET['deletexml'])) ? $res = deletexml($res) : NULL;
	return $res;
}

if(isset($_GET['urn'])){
	$urn = checkurn($_GET['urn'],'');
	(isset($_GET['length'])) ? $length = min($_GET['length'],100000):$length = 1000;
	if($dbtablename == "urndata"){
		print(snippet($urn, $length));
	}
	else{
		if(restrictedAccess()){
			print(snippet($urn, $length));
		}
		else{
			require('../errormsg/access.php');
		}
	}
}

?>