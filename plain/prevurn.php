<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns the next URN in document order or NULL if the specified URN if there is none. 
# Params: urn

function prevurn($urn){
	global $sql;
	global $dbtablename;
	global $binary;
	$urnarr=explode(':',$urn);
	$workurn = 'urn:cts:'.$urnarr[2].':'.$urnarr[3].':';
	$query = 'SELECT urnid FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' "'.$urn.'"';
	$res = "";
	(isset($_GET['range'])) ? $range = $_GET['range'] : $range = 1;
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urnid'];
	}
	
	$query = 'SELECT urn FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' "'.$workurn.'%" AND urnid BETWEEN '.($res-$range).' AND '.($res-1);
	$res = '';
	$nl = "\n";
	$resrow = 'urn';
	foreach ($sql->query($query) as $row) {
		$res = $res.$row[$resrow].$nl;
	}
	return trim($res);
}

$urn = checkurn($_GET['urn'],'');
echo(prevurn($urn));

?>