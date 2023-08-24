<?php
header('Content-Type: text/plain');
require('../config.php');


function doccount(){
	global $sql;
	$query = "SELECT count(urn) as c FROM workdata";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['c'];
	}
	return $res;
}
echo doccount();
?>