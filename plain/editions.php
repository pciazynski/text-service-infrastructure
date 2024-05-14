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
	$query = "SELECT urn,title,year,author FROM workdata ORDER BY urn LIMIT 10000 OFFSET ".$offset;
	$res = "";
	$tab = "\t";
	$nl = "\n";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$nl;
	}
	return $res;
}
echo editions();
?>