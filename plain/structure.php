<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns every child URN with corresponding type and textlength. Dynamic URNs are not (yet) supported. 
# Params: urn

function getDocStruct($urn,$isWorkurn){
	global $sql;
	global $binary;
	
	if($isWorkurn){$query = "SELECT urn,type,CHAR_LENGTH(text) AS len FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT urn,type,CHAR_LENGTH(text) AS len FROM urndata WHERE urn LIKE ".$binary." '".$urn.".%' ORDER BY urnid";}
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn']."\t".$row['type']."\t".$row['len']."\n";
	}
	return trim($res);
}
$urn = trim(htmlspecialchars($_GET["urn"]));

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo getDocStruct($urn,true);}
	elseif (strpos ($urnarr[4],"-")){echo "";}
	else {echo getDocStruct($urn,false);};
}
?>