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
	$tab = "\t";
	$nl = "\n";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'].$tab.$row['type'].$tab.$row['len'].$nl;
	}
	return trim($res);
}
$urn = checkurn($_GET['urn']),'');
$urnarr = explode(":",$urn);

if (strlen($urnarr[4]) == 0){echo getDocStruct($urn,true);}
elseif (strpos ($urnarr[4],"-")){echo "";}
else {echo getDocStruct($urn,false);};

?>