<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

/*
Returns URNs and text. Limited to copyright-free documents. exactsearch.php returns only URNs and includes copyright-restricted documents.
*/


if (isset($_GET["urn"]) && isset($_GET["snippet"])){
	$urn = checkurn($_GET['urn'],'');
	(isset($_GET["limit"])) ? $limit = $_GET["limit"] : $limit = "1000";
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