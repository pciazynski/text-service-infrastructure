<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns number of distinct work URNs.
# Params: none

function doccount(){
	global $sql;
	$res = '';
	$stmt = $sql->prepare('SELECT count(urn) as c FROM workdata');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['c'];
	}
	return $res;
}
echo doccount();
?>