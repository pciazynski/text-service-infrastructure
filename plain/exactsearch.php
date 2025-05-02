<?php
header('Content-Type: text/plain');

require('../config.php');
require('../functions.php');

function textsearch($urn, $snippet){
	global $sql;
	global $dbtablename;
	global $binary;

	(isset($_GET["limit"])) ? $limit = $_GET["limit"] : $limit = "10000";
	(str_ends_with($urn,":")) ? : $urn = $urn.".";

	$query = "SELECT urn FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."%' AND text LIKE '%".$snippet."%' ORDER BY urnid LIMIT ".$limit;
	$res = "";
	$nl = "\n";
	
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'].$nl;
	}
	
	return trim($res);
}

if (isset($_GET["urn"]) and isset($_GET["snippet"])){
	$urn = trim(htmlspecialchars($_GET["urn"]));
	$snippet = trim(urldecode($_GET["snippet"]));
	$urnarr = checkurn($urn);

	if ($urnarr !== false){
		if($dbtablename == "urndata"){
			echo textsearch($urn,"&snippet=".$snippet);
		}
		else{
			if(restrictedAccess()){
				echo textsearch($urn,"&snippet=".$snippet);
			}
			else{
				require('../errormsg/access.php');
			}
		}
	}
}
?>