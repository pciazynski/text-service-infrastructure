<?php
header('Content-Type: text/html');
require('../functions.php');
require('../config.php');

# Returns child URNs and their type fur a given URN.
# Params: urn
if (isset($_GET['urn'])){
	$urn = htmlspecialchars($_GET['urn']);
	$query = "SELECT urn, type FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";
	$res = '';
	$nl = '<br>';
	
	foreach ($sql->query($query) as $row) {
		$tmp = substr($row['urn'],strrpos($row['urn'],":")+1);
		$res = $res.'<a href="../plain/passage.php?deletexml&nl&urn='.$row['urn'].'">'.$tmp.' '.$row['type'].'</a>'.$nl;
	}

	echo $res;
}
?>