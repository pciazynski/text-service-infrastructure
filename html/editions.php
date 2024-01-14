<?php
header('Content-Type: text/html');
require('../functions.php');
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
		$res = $res.'<a href="../plain/passage.php?urn='.$row['urn'].'&deletexml&nl">'.$row['urn'].'</a><br/>';
	}


	return $res;
}
echo editions();
?>