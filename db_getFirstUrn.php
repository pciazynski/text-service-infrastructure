<?php

function firsturn($urn,$isWorkurn){
	global $sql;
	if($isWorkurn){$query = "SELECT urn FROM urndata WHERE urn LIKE BINARY '".$urn."%' AND text IS NOT NULL ORDER BY urnid  LIMIT 1";}
	else {$query = "SELECT urn FROM urndata WHERE urn LIKE BINARY '".$urn.".%'  AND text IS NOT NULL ORDER BY urnid LIMIT 1";}
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'];
	}
	return trim($res);
}
?>