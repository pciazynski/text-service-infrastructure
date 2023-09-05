<?php
header('Content-Type: text/plain');
require('../config.php');

function dates(){
	global $sql;
	$query = "SELECT year, count(year) as cd FROM workdata GROUP BY year ORDER BY year";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['year']."\t".$row['cd']."\n";
	}
	return $res;
}
echo dates();
?>