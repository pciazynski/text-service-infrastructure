<?php
header('Content-Type: text/plain');
require('../config.php');

# Retuens number of static URNs on text level. This includes edition level URNs

function urncount(){
	global $sql;
	$query = "SELECT MAX(maxi) as c FROM (SELECT MAX(urnid) AS maxi FROM urndata UNION SELECT MAX(urnid) AS maxi FROM urndatarestr) a";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res .= $row['c'];
	}
	return $res;
}
echo urncount();
?>