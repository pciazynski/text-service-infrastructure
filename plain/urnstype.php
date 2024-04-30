<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns child URNs and their type fur a given URN.
# Params: urn

if (isset($_GET['urn'])){
	$urn = htmlspecialchars($_GET['urn']);
	$query = "SELECT urn, type FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";
	$res = '';
	$tab = '\t';
	$nl = '\n';
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'].$tab.$row['type'].$nl;
	}

	echo $res;
}
?>