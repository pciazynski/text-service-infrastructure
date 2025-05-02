<?php
function autocomplete($urn){
	global $sql;
	global $binary;
	global $dbtablename;

	$urnarr = explode(":",$urn);
	$testurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3];
	$query = "SELECT urn, restricted FROM workdata WHERE urn LIKE ".$binary." '".$testurn."%' LIMIT 1";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'];
		if ($row['restricted'] == 1){
			$dbtablename = "urndatarestr";
		}
		else{
			$dbtablename = "urndata";
		}
	}
	return trim($res.$urnarr[4]);
}

function restrictedAccess(){
	global $copyrighttoken;
	if(isset($_GET['copyrighttoken']) && strlen($copyrighttoken)>0 && $_GET['copyrighttoken'] == $copyrighttoken){
		return true;
	}
	return false;
}

function deletexml($res){
	$res = preg_replace('/<lb[^>]+>/', " \n", $res);
	$res = preg_replace('/<[^>]+>/', '', $res);
	return $res;
}

function checkurn($urn){
	if(strpos($urn,"urn:cts") !== 0){return false;}
	$urn = autocomplete($urn);
	$urnarr = explode(":",$urn);
	return $urnarr;
}
?>