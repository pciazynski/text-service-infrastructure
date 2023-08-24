<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');


function getDocStrct($urn,$isWorkurn){
	global $sql;
	if($isWorkurn){$query = "SELECT urn,type,CHAR_LENGTH(text) AS len FROM urndata WHERE urn LIKE '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT urn,type,CHAR_LENGTH(text)  AS len FROM urndata WHERE urn LIKE '".$urn.".%' ORDER BY urnid";}

	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn']."\t".$row['type']."\t".$row['len']."\n";
	}
	return trim($res);
}
$urn = trim(htmlspecialchars($_GET["urn"]));

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo getDocStrct($urn,true);}
	elseif (strpos ($urnarr[4],"-")){echo getSpanningPassage($urn);}
	else {echo getDocStrct($urn,false);};
}

?>