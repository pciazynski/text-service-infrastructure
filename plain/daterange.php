<?php
header('Content-Type: text/plain');
require('../config.php');

function daterange(){
	global $sql;
	$query = "SELECT MIN(year) as min,MAX(year) as max, count(DISTINCT year) as c FROM workdata;";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['min']."\t".$row['max']."\t".$row['c']."\n";
	}
	return $res;
}
echo daterange();
?>