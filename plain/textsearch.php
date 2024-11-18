<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

if (isset($_GET["urn"]) && isset($_GET["snippet"])){
	$urn = $_GET["urn"];
	$urnarr = checkurn($urn);
	if ($urnarr !== false){
		$token = htmlspecialchars($_GET['token']);
		$query = "SELECT urn, text FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' and text LIKE '%".$_GET["snippet"]."%' LIMIT 1000";
		$res = "";
		$tab = "\t";
		$nl = "\n";
		foreach ($sql->query($query) as $row){
			$res = $res.$row['urn'].$tab.$row['text'].$nl;
		}
		if (isset($_GET['deletexml'])){
			$res = deletexml($res);
		}
		echo ($res);
	}
}
?>