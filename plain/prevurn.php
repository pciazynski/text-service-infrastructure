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
	(isset($_GET['range'])) ? $range = max(intval($_GET['range']),0) : $range = 1;
	$nl = "\n";
	$urnarr=explode(':',$urn);
	$workurn = 'urn:cts:'.$urnarr[2].':'.$urnarr[3].':';
	$res = '';
	$stmt = $sql->prepare('SELECT urnid FROM '.$dbtablename.' WHERE urn = '.$binary.' ? LIMIT 1');
	$stmt->execute([$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $row['urnid'];
	}
	
	$stmt = $sql->prepare('SELECT urn FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND urnid BETWEEN ? AND ?');
	$stmt->execute([$workurn.'%',$res-$range,$res-1]);
	$res = '';
	$resrow = 'urn';
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res .= $row[$resrow].$nl;
	}
	return trim($res);
}

$urn = checkurn($_GET['urn'],'');
echo(prevurn($urn));

?>