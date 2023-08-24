<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

function getSpanningPassage($urn){
	return "Work In Progress";
}

function getLabel($urn){
	global $sql;
	$query = "SELECT type FROM urndata WHERE urn LIKE '".$urn."%' LIMIT 1";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['type'];
	}
	return $res;
}
$urn = trim(htmlspecialchars($_GET["urn"]));

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strpos ($urnarr[4],"-")){echo getSpanningPassage($urn);}
	else {echo getLabel($urn);};
}


?>