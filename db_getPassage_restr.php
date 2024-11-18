<?php

function getLeftOrRightUrnID($urn,$left=true){
	global $sql;
	global $binary;
	global $dbtablename;

	if($left){$ordering = "ASC";}else{$ordering = "DESC";}
	$query = "SELECT urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."' AND text IS NOT NULL";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urnid'];
	}
	if (strlen($res)==0){
		$query = "SELECT urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn.".%'  AND text IS NOT NULL ORDER BY urnid ".$ordering." LIMIT 1";
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
	global $dbtablename;
	
	$subpassarr = explode("@",$urn);
	$urn = $subpassarr[0];
	$subpass = $subpassarr[1];
	$subpassarr = explode("[",$subpass);
	$subpass = $subpassarr[0];
	if(count($subpassarr)==2){
		$subpassc = rtrim($subpassarr[1],"]");
	}else{$subpassc = 1;}
	if(str_ends_with($urn,":")){$query = "SELECT text FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT text FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn.".%' OR urn LIKE ".$binary." '".$urn."' ORDER BY urnid";}
	
	$res = "";
	
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['text'];
	}
	
	if ($deleteXML){
		$res = deletexml($res);
	}
	
	if(substr_count($res, $subpass)>=$subpassc){$res = $subpass;}else{$res="";}
	return $res;
}

function spanningSubPassage($urn, $deleteXML = false,$newlines = false){
	global $sql;
	global $binary;
	global $dbtablename;
	
	$urnarr = explode(":",$urn);
	$workurn = $urnarr[0].":".$urnarr[1].":".$urnarr[2].":".$urnarr[3].":";
	$passurnarr = explode("-",$urnarr[4]);
	$urnleftarr = explode("@",$workurn.$passurnarr[0]);
	$urnrightarr = explode("@",$workurn.$passurnarr[1]);
	$passleft = "";
	$passmiddle = "";
	$passright = "";
	$queryleft = "SELECT text,urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urnleftarr[0].".%' OR urn LIKE ".$binary." '".$urnleftarr[0]."' ORDER BY urnid";
	$queryright = "SELECT text,urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urnrightarr[0].".%' OR urn LIKE ".$binary." '".$urnrightarr[0]."' ORDER BY urnid";
	$urnidleft = 0;
	$urnidright = 0;
	$res = "";
	$sep = " ";
	if($newlines){
		$sep = "\n";
	}
	foreach ($sql->query($queryleft) as $row) {
		$passleft = $passleft.$row['text'].$sep;
		$urnidleft = $row['urnid'];
	}
	$urnidleft++;
	foreach ($sql->query($queryright) as $row) {
		$passright = $passright.$row['text'].$sep;
		if($urnidright == 0){$urnidright = $row['urnid'];}
	}
	$querymiddle = "SELECT text FROM ".$dbtablename." WHERE urnid BETWEEN ". $urnidleft . " AND ".$urnidright." ORDER BY urnid";
	foreach ($sql->query($querymiddle) as $row) {
		$passmiddle = $passmiddle.$row['text'].$sep;
	}

	$res = $passleft." ........... ". $passmiddle." ........... ". $passright;
	
	if ($deleteXML){
		$res = deletexml($res);
	}
	return $res;
}


function spanningPassage($urn,$deleteXML = false,$newlines = false){
	if (str_contains($urn,"@")){return spanningSubPassage($urn,$deleteXML,$newlines);}
	global $sql;
	global $dbtablename;
	$urnarr = explode(":",$urn);
	$workurn = $urnarr[0].":".$urnarr[1].":".$urnarr[2].":".$urnarr[3];
	$psgurn = explode("-",$urnarr[4]);
	$fromurnid = getLeftOrRightUrnID($workurn.":".$psgurn[0], true);
	$tournid = getLeftOrRightUrnID($workurn.":".$psgurn[1],false);
	$query = "SELECT text FROM ".$dbtablename." WHERE urnid BETWEEN ".$fromurnid." AND ".$tournid." ORDER BY urnid";
	$res = "";
	$sep = " ";
	if($newlines){
		$sep = "\n";
	}
	foreach ($sql->query($query) as $row) {
		$res = trim($res.$row['text']).$sep;
	}
	
	if ($deleteXML){
		$res = deletexml($res);
	}

	return $res;
}

function passage($urn,$deleteXML = false,$newlines=false){
	if (str_contains($urn,"@")){return subPassage($urn,$deleteXML);}
	global $sql;
	global $binary;
	global $dbtablename;
	#LIKE urn.% OR (exactly) LIKE urn
	if(str_ends_with($urn,":")){$query = "SELECT text FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT text FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn.".%' OR urn LIKE ".$binary." '".$urn."' ORDER BY urnid";}
	$res = "";
	$sep = " ";
	if($newlines){
		$sep = "\n";
	}
	
	foreach ($sql->query($query) as $row) {
		$res = trim($res.$row['text']).$sep;
	}
	
	if ($deleteXML){
		$res = deletexml($res);
	}
	return $res;
}
?>