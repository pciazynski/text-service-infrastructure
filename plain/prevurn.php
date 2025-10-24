<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns the next URN in document order or NULL if the specified URN if there is none. 
# Params: urn

function prevurn($urn){
	global $sql;
	global $dbtablename;
	global $binary;
	$urnarr=explode(':',$urn);
	$workurn = 'urn:cts:'.$urnarr[2].':'.$urnarr[3].':';
	$query = 'SELECT urnid FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' "'.$urn.'"';
	$res = "";
	(isset($_GET['range'])) ? $range = $_GET['range'] : $range = 1;
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urnid'];
	}
	if($res==""){
		return false;
	}
	
	if (strlen($urnarr[4]) == 0){$query = 'SELECT urn FROM '.$dbtablename.' WHERE urnid = '.($res+$range);}
	else{$query = 'SELECT urn FROM '.$dbtablename.' WHERE urnid BETWEEN '.($res-$range).' AND '.($res-1);}
	if($res==0 | $isWorkurn){$res="NULL\n";}
	else{$res = '';}
	foreach ($sql->query($query) as $row) {
		$resurn = $row['urn'];
		if(!str_contains($resurn,$workurn)){$resurn='NULL';}
		$res = $res.$resurn."\n";
	}
	return trim($res);
}

$urn = checkurn($_GET['urn'],'');
echo(prevurn($urn));

?>