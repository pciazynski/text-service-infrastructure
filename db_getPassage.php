<?php

function getLeftOrRightUrnID($urn,$left=true){
	global $sql;
	global $binary;

	if($left){$ordering = "ASC";}else{$ordering = "DESC";}
	$query = "SELECT urnid FROM urndata WHERE urn LIKE ".$binary." '".$urn."' AND text IS NOT NULL";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urnid'];
	}
	if (strlen($res)==0){
		$query = "SELECT urnid FROM urndata WHERE urn LIKE ".$binary." '".$urn.".%'  AND text IS NOT NULL ORDER BY urnid ".$ordering." LIMIT 1";
		$res = "";
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['urnid'];
		}
	}
	return trim($res);
}

function subPassage($urn,$deleteXML = false){
	global $sql;
	global $binary;
	
	$subpassarr = explode("@",$urn);
	$urn = $subpassarr[0];
	$subpass = $subpassarr[1];
	$subpassarr = explode("[",$subpass);
	$subpass = $subpassarr[0];
	if(count($subpassarr)==2){
		$subpassc = rtrim($subpassarr[1],"]");
	}else{$subpassc = 1;}
	if(str_ends_with($urn,":")){$query = "SELECT text FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT text FROM urndata WHERE urn LIKE ".$binary." '".$urn.".%' OR urn LIKE ".$binary." '".$urn."' ORDER BY urnid";}
	
	$res = "";
	
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['text'];
	}
	
	if($deleteXML){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	if(substr_count($res, $subpass)>=$subpassc){$res = $subpass;}else{$res=$query;}
	
	
	return $res;
}

function spanningSubPassage($urn){
	global $sql;
	$res = "Work in Progress";
	return $res;
}


function spanningPassage($urn,$deleteXML = false,$newlines = false){
	global $sql;
	$urnarr = explode(":",$urn);
	$workurn = $urnarr[0].":".$urnarr[1].":".$urnarr[2].":".$urnarr[3];
	$psgurn = explode("-",$urnarr[4]);
	$fromurnid = getLeftOrRightUrnID($workurn.":".$psgurn[0], true);
	$tournid = getLeftOrRightUrnID($workurn.":".$psgurn[1],false);
	$query = "SELECT text FROM urndata WHERE urnid BETWEEN ".$fromurnid." AND ".$tournid." ORDER BY urnid";
	$res = "";
	if($newlines){
		$nl = "\n";
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['text'].$nl;
		}
	}
	else{
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['text'];
		}
	}
	
	if($deleteXML){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	return $res;
}

function passage($urn,$deleteXML = false,$newlines=false){
	if (str_contains($urn,"@")){return subPassage($urn,$deleteXML);}
	global $sql;
	global $binary;
	
	#LIKE urn.% OR (exactly) LIKE urn
	if(str_ends_with($urn,":")){$query = "SELECT text FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT text FROM urndata WHERE urn LIKE ".$binary." '".$urn.".%' OR urn LIKE ".$binary." '".$urn."' ORDER BY urnid";}
	$res = "";
	if($newlines){
		$nl = "\n";
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['text'].$nl;
		}
	}
	else{foreach ($sql->query($query) as $row) {
		$res = $res.$row['text'];
	}}
	
	if($deleteXML){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	return $res;
}
?>