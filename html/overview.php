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
	$query = "SELECT urn,title,year,author FROM workdata ORDER BY urn LIMIT 5000 OFFSET ".$offset;
	$res = "<table>";
	foreach ($sql->query($query) as $row) {
		$res = $res.'<tr><td><a href="../plain/passage.php?urn='.$row['urn'].'&deletexml&nl">Passage</a></td><td><a href="../html/structure.php?urn='.$row['urn'].'">Structure</a></td><td>'.$row['urn'].'</td><td>'.$row['title'].'</td><td>'.$row['year'].'</td><td>'.$row['author']."</td></tr>";
	}
	$res = $res."</table>";
	return $res;
}
echo editions();
?>