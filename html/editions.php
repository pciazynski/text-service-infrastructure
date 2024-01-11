<?php
header('Content-Type: text/html');
require('../functions.php');
require('../config.php');


function editions(){
	global $sql;
	$query = "SELECT urn,title,year,author FROM workdata";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.'<a href="../plain/passage.php?urn='.$row['urn'].'&deletexml&nl">'.$row['urn'].'</a><br/>';
	}


	return $res;
}
echo editions();
?>