<?php

function worklabel($urn){
	global $sql;
	global $binary;
	$query = "SELECT urn, title, year, lang FROM workdata WHERE urn LIKE ".$binary." '".$urn."'";
	foreach ($sql->query($query) as $row) {
		$res = $row['title']." (".$row['year'].", ".$row['lang'].")";
	}
	return $res;
}

function passagelabel($urn){
	global $sql;
	global $binary;
	$urnarr = explode(":",$urn);
	$workurn = $urnarr[0].":".$urnarr[1].":".$urnarr[2].":".$urnarr[3].":";
	$psgurn = $urnarr[4];
	$psgurnarr = explode(".",$psgurn);
	$requesturns = "urn LIKE ".$binary."'".$workurn."'";
	foreach ($psgurnarr as $psgpart){
		if (!str_ends_with($workurn,":")){
			$workurn .= ".";
		}
		$workurn .= $psgpart;
		$requesturns .= " OR urn LIKE ".$binary." '".$workurn."'";
	}
	$res = "";
	$query = "SELECT urn, type FROM urndata WHERE ".$requesturns." ORDER BY urnid";
	foreach ($sql->query($query) as $row) {
		if(!str_ends_with($row['urn'],":")){
			$urnarr = explode(".",explode(":",$row['urn'])[4]);
			$res .= $row['type'] ." " . $urnarr[count($urnarr)-1]." ";
		}
	}
	return $res;
}

function label($urn){
	$urnarr = checkurn($urn);
	if ($urnarr !== false){
		if (strlen($urnarr[4]) == 0){return trim(worklabel($urn));}
		else {
			$psgurnarr = explode("-",$urnarr[4]);
			if(count($psgurnarr) == 2){
				$workurn = $urnarr[0].":".$urnarr[1].":".$urnarr[2].":".$urnarr[3].":";
				return trim(worklabel($workurn).": ".passagelabel($workurn.$psgurnarr[0])." to ".passagelabel($workurn.$psgurnarr[1]));
			}
			else{
				$workurn = $urnarr[0].":".$urnarr[1].":".$urnarr[2].":".$urnarr[3].":";
				return trim(worklabel($workurn).":".passagelabel($urn));
			}
		};
	}

}
?>