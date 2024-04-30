<?php
header('Content-Type: text/html');
require('../functions.php');
require('../config.php');

# Returns edition level URN list.
# Params: none

function editions(){
	global $sql;
	$offset = 0;
	if (isset($_GET['offset'])){
		$offset = $_GET['offset'];
	}
	$query = "SELECT urn,title FROM workdata ORDER BY urn LIMIT 10000 OFFSET ".$offset;
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.'<a href="../plain/passage.php?urn='.$row['urn'].'&deletexml&nl">'.$row['title'].'</a><br/>';
	}
	return $res;
}
echo editions();
?>