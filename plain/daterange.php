<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns minimum, maximum values of publication dates and number of distinct date values in workdata table. 
# Params: none

function daterange(){
	global $sql;
	$res = '';
	$stmt = $sql->prepare('SELECT MIN(year) as min, MAX(year) as max, count(DISTINCT year) as c FROM workdata;');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $row['min']."\t".$row['max']."\t".$row['c']."\n";
	}
	return $res;
}
echo daterange();
?>