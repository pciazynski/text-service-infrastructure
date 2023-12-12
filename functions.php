<?php


function autocomplete($urn){
	global $sql;
	$urnarr = explode(":",$urn);
	$testurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3];
	$query = "SELECT urn FROM workdata WHERE urn LIKE BINARY '".$testurn."%' LIMIT 1";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'];
	}
	

	return trim($res.$urnarr[4]);
}


function checkurn($urn){
	$urn = autocomplete($urn);
	$urnarr = explode(":",$urn);
	if(strpos($urn,"urn:cts") !== 0){return false;}
	return $urnarr;
}
?>