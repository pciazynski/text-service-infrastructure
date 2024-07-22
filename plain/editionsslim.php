<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns edition level URN list.
# Params: none

function editions(){
	global $sql;
	$offset = 0;
	if (isset($_GET['offset'])){
		$offset = $_GET['offset'];
	}
	$query = "SELECT urn FROM workdata ORDER BY urn";
	$res = "";
	$nl = "\n";
	$key = 'urn';
	foreach ($sql->query($query) as $row) {
		$res = $res.$row[$key].$nl;
	}
	return $res;
}
echo editions();
?>