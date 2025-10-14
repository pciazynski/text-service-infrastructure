<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

if (isset($_GET["urn"]) && isset($_GET["snippet"])){
	$urn = trim(htmlspecialchars($_GET["urn"]));
	(isset($_GET["limit"])) ? $limit = $_GET["limit"] : $limit = "1000";
	$urn = checkurn($urn,'');
	$token = trim(htmlspecialchars($_GET['token']));
	$query = "SELECT urn, text FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' and text LIKE '%".$_GET["snippet"]."%' LIMIT ".$limit;
	$res = "";
	$tab = "\t";
	$nl = "\n";
	foreach ($sql->query($query) as $row){
		$res = $res.$row['urn'].$tab.$row['text'].$nl;
	}

	($deleteXML) ? $res = deletexml($res) : NULL;

	echo ($res);
}
?>