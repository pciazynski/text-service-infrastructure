<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns minimum, maximum values of publication dates and number of distinct date values in workdata table. 
# Params: none

function daterange(){
	global $sql;
	$query = "SELECT MIN(year) as min, MAX(year) as max, count(DISTINCT year) as c FROM workdata;";
	$res = "";
	$nl = "\n";
	$tab = "\t";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['min'].$tab.$row['max'].$tab.$row['c'].$nl;
	}
	return $res;
}
echo daterange();
?>