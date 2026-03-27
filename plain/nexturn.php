<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns the next URN in document order or NULL if the specified URN if there is none. 
# Params: urn

function nexturn($urn){
	global $sql;
	global $dbtablename;
	global $binary;
	(isset($_GET['range'])) ? $range = max(intval($_GET['range']),0) : $range = 1;
	$urnarr=explode(':',$urn);
	$res = '';
	$nl = "\n";
	$workurn = 'urn:cts:'.$urnarr[2].':'.$urnarr[3].':';
	$stmt = $sql->prepare('SELECT urnid FROM '.$dbtablename.' WHERE urn = '.$binary.' ? LIMIT 1');
	$urn = str_replace("_","\_",$urn);
	$stmt->execute([$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $row['urnid'];
	}
	
	$stmt = $sql->prepare('SELECT urn FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND urnid BETWEEN ? AND ?');
	$workurn = str_replace("_","\_",$workurn);
	$stmt->execute([$workurn.'%',$res+1,$res+$range]);
	$res = '';
	$resrow = 'urn';
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res .= $row[$resrow].$nl;
	}
	return trim($res);
}

$urn = checkurn($_GET['urn'],'');
echo(nexturn($urn));

?>