<?php
header('Content-Type: text/plain');
require('../config.php');

# Retuens number of static URNs on text level. This includes edition level URNs

function urncount(){
	global $sql;
	$query = "SELECT max(urnid) as c FROM urndata";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['c'];
	}
	return $res;
}
echo urncount();
?>