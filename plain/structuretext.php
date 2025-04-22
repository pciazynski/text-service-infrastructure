<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns every child URN of a given static URN plus the corresponding text snippet. Dynamic URNs are not (yet) supported. 
# Params: urn, (deletexml)

function getDocStrct($urn){
	global $sql;
	global $binary;
	global $dbtablename;
	$query = "SELECT urn,type,text FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";
	$res = "";
	$tab = "\t";
	$nl = "\n";

	if (isset($_GET["lowercase"])){
		$text=$row['text'];
		($multibyte) ? $text = mb_strtolower($text,'UTF-8') : $text = strtolower($text);
		$res = $res.$row['urn'].$tab.$row['type'].$text.$nl;
	}
	else{
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['urn'].$tab.$row['type'].$tab.$row['text'].$nl;
		}
	}
	
	if(isset($_GET["deletexml"])){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	return trim($res,"\n");
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
