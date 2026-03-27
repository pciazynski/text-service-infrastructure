<?php

function prevnexturn($urn){
	global $sql;
	global $dbtablename;
	global $binary;
	$isWorkurn = str_ends_with($urn,':');
	$res = '';
	$stmt = $sql->prepare('SELECT urnid FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ?');
	$urn = str_replace("_","\_",$urn);
	$stmt->execute([$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = intval($row['urnid']);
	}

	$urnarr=explode(':',$urn);
	$workurn = 'urn:cts:'.$urnarr[2].':'.$urnarr[3].':';

	if($isWorkurn){
		$stmt = $sql->prepare('SELECT urn FROM '.$dbtablename.' WHERE urnid = ?');
		$stmt->execute([($res+1)]);
	}
	else{
		$stmt = $sql->prepare('SELECT urn FROM '.$dbtablename.' WHERE urnid = ? OR urnid = ?');
		$stmt->execute([($res-1),($res+1)]);
	}
	$res='';
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$resurn = $row['urn'];
		if(!str_contains($resurn,$workurn)){$resurn='NULL';}
		$res = $res.$resurn."\n";
	}
	return trim($res);
}
?>