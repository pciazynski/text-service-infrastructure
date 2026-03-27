<?php

function worklabel($urn){
	global $sql;
	global $binary;
	$stmt = $sql->prepare('SELECT urn, title, year, lang FROM workdata WHERE urn LIKE '.$binary.' ?');
	$urn = str_replace("_","\_",$urn);
	$stmt->execute([$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $row['title'].' ('.$row['year'].', '.$row['lang'].')';
	}
	return $res;
}

function passagelabel($urn){
	global $sql;
	global $binary;
	$urnarr = explode(':',$urn);
	$workurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3].':';
	$workurn = str_replace("_","\_",$workurn);
	$psgurn = $urnarr[4];
	$psgurnarr = explode('.',$psgurn);
	$requesturns = 'urn LIKE '.$binary.' ?';
	$paramarr = [$workurn];
	
	foreach ($psgurnarr as $psgpart){
		if (!str_ends_with($workurn,':')){
			$workurn .= '.';
		}
		$workurn .= $psgpart;
		$requesturns .= ' OR urn LIKE '.$binary.' ?';
		array_push($paramarr,$workurn);
	}
	$res = '';
	$query = 'SELECT urn, type FROM urndata WHERE '.$requesturns.' ORDER BY urnid';
	$stmt = $sql->prepare($query);
	$stmt->execute($paramarr);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		if(!str_ends_with($row['urn'],':')){
			$urnarr = explode('.',explode(':',$row['urn'])[4]);
			$res .= $row['type'] .' ' . $urnarr[count($urnarr)-1].' ';
		}
	}
	return $res;
}

function label($urn){
	$urnarr = explode(':',$urn);
	if (strlen($urnarr[4]) == 0){return trim(worklabel($urn));}
	else {
		$psgurnarr = explode('-',$urnarr[4]);
		if(count($psgurnarr) == 2){
			$workurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3].':';
			return trim(worklabel($workurn).': '.passagelabel($workurn.$psgurnarr[0]).' to '.passagelabel($workurn.$psgurnarr[1]));
		}
		else{
			$workurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3].':';
			return trim(worklabel($workurn).':'.passagelabel($urn));
		}
	};

}
?>