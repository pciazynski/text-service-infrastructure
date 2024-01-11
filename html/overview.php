<?php
header('Content-Type: text/html');
require('../functions.php');
require('../config.php');


function editions(){
	global $sql;
	$query = "SELECT urn,title,year,author FROM workdata";
	$res = "<table><tr><th>URN</th><th>Passage</th><th>Structure</th><th>Label</th><th>Date</th>";
	foreach ($sql->query($query) as $row) {
		$res = $res.'<tr><td>'.$row['urn'].'</td><td>'.'<a href="../plain/passage.php?urn='.$row['urn'].'&deletexml&nl">Passage</a>'.'</td><td>'.'<a href="../html/structure.php?urn='.$row['urn'].'">Structure</a>'.'</td><td>'.$row['title'].'</td><td>'.$row['year'].'</td><td>'.$row['author']."</td></tr>";
	}
	$res = $res."</table>";
	return $res;
}
echo editions();
?>