<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns child URNs and their type fur a given URN.
# Params: urn

if (isset($_GET['urn'])){
	$urn = checkurn($_GET['urn'],'');
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$stmt = $sql->prepare('SELECT urn,type FROM urndata WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%']):$stmt->execute([$urn.'.%']);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['urn'].$tab.$row['type'].$nl;
	}

	echo $res;
}
?>