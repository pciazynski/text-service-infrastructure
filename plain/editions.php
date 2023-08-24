<?php
header('Content-Type: text/plain');
require('../config.php');


function editions(){
	global $sql;
	$query = "SELECT urn,title,year,author FROM workdata";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn']."\t".$row['title']."\t".$row['year']."\t".$row['author']."\n";
	}
	return $res;
}
echo editions();
?>