<?php

function getLeftOrRightUrnID($urn,$left=true){
	global $sql;
	if($left){$ordering = "ASC";}else{$ordering = "DESC";}
	$query = "SELECT urnid FROM urndata WHERE urn LIKE BINARY '".$urn."' AND text IS NOT NULL";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urnid'];
	}
	if (strlen($res)==0){
		$query = "SELECT urnid FROM urndata WHERE urn LIKE BINARY '".$urn.".%'  AND text IS NOT NULL ORDER BY urnid ".$ordering." LIMIT 1";
		$res = "";
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['urnid'];
		}
	}
	return trim($res);
}

function subPassage($urn){
	global $sql;
	$res = "Work in Progress";
	return $res;
}

function spanningSubPassage($urn){
	global $sql;
	$res = "Work in Progress";
	return $res;
}


function spanningPassage($urn,$deleteXML = false){
	global $sql;
	$urnarr = explode(":",$urn);
	$workurn = $urnarr[0].":".$urnarr[1].":".$urnarr[2].":".$urnarr[3];
	$psgurn = explode("-",$urnarr[4]);
	$fromurnid = getLeftOrRightUrnID($workurn.":".$psgurn[0], true);
	$tournid = getLeftOrRightUrnID($workurn.":".$psgurn[1],false);
	$query = "SELECT text FROM urndata WHERE urnid BETWEEN ".$fromurnid." AND ".$tournid." ORDER BY urnid";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['text'];
	}
	if($deleteXML){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	return $res;
}

function passage($urn,$isWorkurn,$deleteXML = false){
	global $sql;
	if($isWorkurn){$query = "SELECT text FROM urndata WHERE urn LIKE BINARY '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT text FROM urndata WHERE urn LIKE BINARY '".$urn.".%' OR urn LIKE BINARY '".$urn."' ORDER BY urnid";}
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['text']."\n";
	}
	
	if($deleteXML){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	return trim($res);
}
?>