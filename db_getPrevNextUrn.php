<?php

function prevnexturn($urn,$isWorkurn){
	global $sql;
	$query = "SELECT urnid FROM urndata WHERE urn = '".$urn."'";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urnid'];
	}
	if($isWorkurn){$query = "SELECT urn FROM urndata WHERE urnid = ".($res+1);}
	else{$query = "SELECT urn FROM urndata WHERE urnid = ".($res-1)." OR urnid = ".($res+1);}
	if($res==0 | $isWorkurn){$res="NULL\n";}
	else{$res = "";}
	$urnarr=explode(":",$urn);
	$workurn = "urn:cts:".$urnarr[2].":".$urnarr[3].":";
	foreach ($sql->query($query) as $row) {
		$resurn = $row['urn'];
		if(!str_contains($resurn,$workurn)){$resurn="NULL";}
		$res = $res.$resurn."\n";
	}
	return trim($res);
}
?>