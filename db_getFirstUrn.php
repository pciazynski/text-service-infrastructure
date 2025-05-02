<?php

function firsturn($urn,$isWorkurn){
	global $sql;
	global $binary;
	global $dbtablename;

	if($isWorkurn){$query = "SELECT urn FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."%' AND text IS NOT NULL ORDER BY urnid  LIMIT 1";}
	else {$query = "SELECT urn FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn.".%'  AND text IS NOT NULL ORDER BY urnid LIMIT 1";}
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'];
	}
	return trim($res);
}
?>