<?php
header('Content-Type: text/html');

require('../config.php');
require('../functions.php');

$snippet = trim(urldecode($_GET["snippet"]));

function textsearch($snippet){
	global $sql;
	$nl = "<br>";
	$limit = "10000";
	if (isset($_GET["limit"])){
		$limit = $_GET["limit"];
	}
	$query = "SELECT urn FROM urndata WHERE text LIKE '%".$snippet."%' ORDER BY urnid LIMIT ".$limit;
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.'<a href="../plain/passage.php?urn='.$row['urn'].'">'.$row['urn'].'</a>'.$nl;
	}
	
	return trim($res);
}

echo(textsearch($snippet));
?>