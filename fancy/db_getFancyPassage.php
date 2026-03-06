<?php

function getLeftOrRightUrnID($urn,$left=true){
	global $sql;
	global $binary;

	$res = '';

	#exact match
	$stmt = $sql->prepare('SELECT urnid FROM urndata WHERE urn LIKE '.$binary.' ? AND text IS NOT NULL');
	$stmt->execute([$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $row['urnid'];
	}
	
	#no exact match with text content -> search leftest or rightest child node
	if (strlen($res)==0){
		$res = '';
		($left) ? $ordering = 'ASC' : $ordering = 'DESC';
		$stmt = $sql->prepare('SELECT urnid FROM urndata WHERE urn LIKE '.$binary.' ? AND text IS NOT NULL ORDER BY urnid '.$ordering.' LIMIT 1');
		$stmt->execute([$urn.'.%']);
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
			$res = $row['urnid'];
		}
	}
	return trim($res);
}

function spanningPassage($urn){
	global $sql;
	$urnarr = explode(':',$urn);
	$workurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3];
	$psgurn = explode('-',$urnarr[4]);
	$fromurnid = getLeftOrRightUrnID($workurn.':'.$psgurn[0], true);
	$tournid = getLeftOrRightUrnID($workurn.':'.$psgurn[1],false);
	$res = '';
	$nl = "\n";
	$stmt = $sql->prepare('SELECT text,type,urn FROM urndata WHERE urnid BETWEEN ? AND ? ORDER BY urnid');
	$stmt->execute([$fromurnid,$tournid]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$newurnpartcount = count(explode('.',explode(':',$row['urn'])[4]));
		if ($newurnpartcount==1){
			$res = $res.'<hr>';
		}
		
		$type = str_replace(['head','list','item','lg','l'],['h'.$newurnpartcount,'ul','li','ul','li'],$row['type']);
		$res = $res.'<'.$type.'>';
		$psgpart = $row['text'];
		$psgpart = preg_replace('/<lb[^>]+>/', $nl, $psgpart);
		$res = $res.preg_replace('/<[^>]+>/', '', $psgpart).$nl;
		$res = $res.'</'.$type.'>';
	}
	$res = str_replace($nl,'<br/>',$res);

	return $res;
}

function passage($urn){
	global $sql;
	global $binary;
	
	if(str_ends_with($urn,':')){
		$stmt = $sql->prepare('SELECT text,type,urn FROM urndata WHERE urn LIKE '.$binary.' ? ORDER BY urnid LIMIT 1,18446744073709551615');
		$stmt->execute([$urn.'%']);
	}
	else {
		#LIKE urn.% OR (exactly) LIKE urn
		$stmt = $sql->prepare('SELECT text,type,urn FROM urndata WHERE urn LIKE '.$binary.' ? OR urn LIKE '.$binary.' ? ORDER BY urnid');
		$stmt->execute([$urn.'.%',$urn]);
	}
	
	$res = '';
	$oldurnpartcount = 0;
	$nl = "\n";
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$newurnpartcount = count(explode('.',explode(':',$row['urn'])[4]));
		if ($newurnpartcount==1){
			$res = $res.'<hr>';
		}
		
		$type = str_replace(['head','list','item','lg','l'],['h'.$newurnpartcount,'ul','li','ul','li'],$row['type']);
		$res = $res.'<'.$type.'>';
		$psgpart = $row['text'];
		$psgpart = preg_replace('/<lb[^>]+>/', $nl, $psgpart);
		$res = $res.preg_replace('/<[^>]+>/', '', $psgpart).$nl;
		$res = $res.'</'.$type.'>';
	}
	$res = str_replace($nl,'<br/>',$res);
	return $res;
}

?>