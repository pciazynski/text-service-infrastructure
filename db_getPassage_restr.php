<?php

function getLeftOrRightUrnID($urn,$left=true){
	global $sql;
	global $binary;
	global $dbtablename;

	$res = '';

	#exact match
	$stmt = $sql->prepare('SELECT urnid FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND text IS NOT NULL');
	$stmt->execute([$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $row['urnid'];
	}
	
	#no exact match with text content -> search leftest or rightest child node
	if (strlen($res)==0){
		$res = '';
		($left) ? $ordering = 'ASC' : $ordering = 'DESC';
		$stmt = $sql->prepare('SELECT urnid FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND text IS NOT NULL ORDER BY urnid '.$ordering.' LIMIT 1');
		$stmt->execute([$urn.'.%']);
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
			$res = $row['urnid'];
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
	
	if($urnleftarr[0] === $urnrightarr[0]){
		$psg = passage($urnleftarr[0]);
		$passarr = explode($urnleftarr[1],$psg);
		$subpassl = $urnleftarr[1];
		$subpassarr = explode("[",$subpassl);
		$subpassl = $subpassarr[0];

		(count($subpassarr)==2) ? $subpasscl = rtrim($subpassarr[1],"]") : $subpasscl = 1;
		array_splice($passarr,0,$subpasscl);
		$psg = $subpassl.join($subpassl,$passarr);

		$subpassr = $urnrightarr[1];
		$subpassarr = explode("[",$subpassr);
		$subpassr = $subpassarr[0];
		(count($subpassarr)==2) ? $subpasscr = rtrim($subpassarr[1],"]") : $subpasscr = 1;
		$passarr = explode($subpassr,$psg);
		if($subpasscr<count($passarr)){
			$psg = "";
			for($i=0;$i<=$subpasscr-1;$i++){
				$psg.=$passarr[$i].$subpassr;
			}
		}else{
			return "";
		}
		
		return $psg;
	}
	$passleft = "";
	$passmiddle = "";
	$passright = "";
	$queryleft = "SELECT text,urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urnleftarr[0].".%' OR urn LIKE ".$binary." '".$urnleftarr[0]."' ORDER BY urnid";
	$queryright = "SELECT text,urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urnrightarr[0].".%' OR urn LIKE ".$binary." '".$urnrightarr[0]."' ORDER BY urnid";
	$urnidleft = 0;
	$urnidright = 0;
	
	$res = "";
	($newlines) ? $sep = "\n" : $sep = " ";

	
	foreach ($sql->query($queryleft) as $row) {
		$passleft = $passleft.$row['text'].$sep;
		$urnidleft = $row['urnid'];
	}
	$urnidleft++;
	foreach ($sql->query($queryright) as $row) {
		$passright = $passright.$row['text'].$sep;
		if($urnidright == 0){$urnidright = $row['urnid'];}
	}
	$querymiddle = "SELECT text FROM ".$dbtablename." WHERE urnid BETWEEN ". $urnidleft . " AND ".($urnidright-1)." ORDER BY urnid";
	foreach ($sql->query($querymiddle) as $row) {
		$passmiddle = $passmiddle.$row['text'].$sep;
	}
	
	if(count($urnleftarr)==2){
		$subpassl = $urnleftarr[1];
		$subpassarr = explode("[",$subpassl);
		$subpassl = $subpassarr[0];
		(count($subpassarr)==2) ? $subpasscl = rtrim($subpassarr[1],"]") : $subpasscl = 1;

		$passarr = explode($subpassl,$passleft);
		if($subpasscl<count($passarr)){
			array_splice($passarr,0,$subpasscl);
			$passleft = $subpassl.join($subpassl,$passarr);
		}else{
			return "";
		}
	}

	if(count($urnrightarr)==2){
		$subpassr = $urnrightarr[1];
		$subpassarr = explode("[",$subpassr);
		$subpassr = $subpassarr[0];
		(count($subpassarr)==2) ? $subpasscr = rtrim($subpassarr[1],"]") : $subpasscr = 1;
		$passarr = explode($subpassr,$passright);
		if($subpasscr<count($passarr)){
			$passright = "";
			for($i=0;$i<=$subpasscr-1;$i++){
				$passright.=$passarr[$i].$subpassr;
			}
		}else{
			return "";
		}
	}

	$res = $passleft.$passmiddle.$passright;
	
	($deleteXML) ? $res = deletexml($res) : NULL;

	return $res;
}


function spanningPassage($urn,$deleteXML = false,$newlines = false){
	if (str_contains($urn,'@')){return spanningSubPassage($urn,$deleteXML,$newlines);}
	global $sql;
	global $dbtablename;
	$urnarr = explode(':',$urn);
	$workurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3];
	$psgurn = explode("-",$urnarr[4]);
	$fromurnid = getLeftOrRightUrnID($workurn.":".$psgurn[0], true);
	$tournid = getLeftOrRightUrnID($workurn.":".$psgurn[1],false);
	$res = '';
	$sep = ' ';
	if($newlines){
		$sep = "\n";
	}
	$stmt = $sql->prepare('SELECT text FROM '.$dbtablename.' WHERE urnid BETWEEN ? AND ? ORDER BY urnid');
	$stmt->execute([$fromurnid,tournid]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = trim($res.$row['text']).$sep;
	}
	
	($deleteXML) ? $res = deletexml($res) : NULL;

	return $res;
}

function passage($urn,$deleteXML = false,$newlines = false){
	if (str_contains($urn,'@')){return subPassage($urn,$deleteXML,$newlines);}
	global $sql;
	global $binary;
	global $dbtablename;
	
	#LIKE urn.% OR (exactly) LIKE urn
	if(str_ends_with($urn,':') AND $newlines==0){
		if($dbtablename == 'urndatarestr') {
			$stmt = $sql->prepare('SELECT text FROM workurntextrestr WHERE urn = ?');
		}
		else{
			$stmt = $sql->prepare('SELECT text FROM workurntext WHERE urn = ?');
		}
		$stmt->execute([$urn]);
	}
	else {
		if(str_ends_with($urn,':')){
			$stmt = $sql->prepare('SELECT text FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
			$stmt->execute([$urn.'%']);
		}else{
			$stmt = $sql->prepare('SELECT text FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? OR urn LIKE '.$binary.' ? ORDER BY urnid');
			$stmt->execute([$urn.'%',$urn]);
		}
	}
	
	$res = '';
	$sep = ' ';
	if($newlines){
		$sep = "\n";
	}
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = trim($res.$row['text']).$sep;
	}
	
	($deleteXML) ? $res = deletexml($res) : NULL;

	return $res;
}


?>