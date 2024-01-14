<?php
header('Content-Type: text/plain');
require('../config.php');


function editions(){
	global $sql;
	$offset = 0;
	if (isset($_GET['offset'])){
		$offset = $_GET['offset'];
	}
	$query = "SELECT urn,title,year,author FROM workdata ORDER BY urn LIMIT 25000 OFFSET ".$offset;
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn']."\t".$row['title']."\t".$row['year']."\t".$row['author']."\n";
	}
	return $res;
}
echo editions();
?>