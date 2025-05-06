<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

if ( isset($_GET["snippet"])){
	(isset($_GET["limit"])) ? $limit = $_GET["limit"] : $limit = "100";
	$token = trim(htmlspecialchars($_GET['token']));
	$query = "SELECT text FROM urndata WHERE text LIKE '% ".$_GET["snippet"]." %' LIMIT ".$limit;
	$res = "";
	$nl = "\n";
	foreach ($sql->query($query) as $row){
		$res = $res.$row['text'].$nl;
	}
	(isset($_GET["deletexml"])) ? $res = deletexml($res) : NULL;
	echo ($res);
}
?>