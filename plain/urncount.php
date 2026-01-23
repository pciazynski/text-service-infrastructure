<?php
header('Content-Type: text/plain');
require('../config.php');

# Retuens number of static URNs on text level. This includes edition level URNs

function urncount(){
	global $sql;
	$query = '';
	$res = '';
	$stmt = $sql->prepare('SELECT MAX(maxi) as c FROM (SELECT MAX(urnid) AS maxi FROM urndata UNION SELECT MAX(urnid) AS maxi FROM urndatarestr) a');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $row['c'];
	}
	return $res;
}
echo urncount();
?>