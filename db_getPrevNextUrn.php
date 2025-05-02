<?php

function prevnexturn($urn,$isWorkurn){
	global $sql;
	global $dbtablename;
	global $binary;
	$query = "SELECT urnid FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."'";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urnid'];
	}
	if($res==""){
		return false;
	}
	
	if($isWorkurn){$query = "SELECT urn FROM ".$dbtablename." WHERE urnid = ".($res+1);}
	else{$query = "SELECT urn FROM ".$dbtablename." WHERE urnid = ".($res-1)." OR urnid = ".($res+1);}
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