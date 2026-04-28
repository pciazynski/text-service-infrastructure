<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns number of static URNs on text level. This includes edition level URNs

function urncount(){
	global $sql;
	$query = '';
	$res = '';
	$stmt = $sql->prepare('SELECT count(urn) as c FROM urndata');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = intval($row['c']);
	}
	
	if($restricteddocuments){
		$stmt = $sql->prepare('SELECT count(urn) as c FROM urndatarestr');
		$stmt->execute();
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
			$res = $res + intval($row['c']);
		}
	}
	return $res;
}
echo urncount();
?>