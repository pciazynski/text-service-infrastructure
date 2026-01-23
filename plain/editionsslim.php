<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns edition level URN list.
# Params: urnfilter

function editions(){
	global $sql;
	$res = '';
	$nl = "\n";
	$key = 'urn';
	if(isset($_GET['urnfilter']) and strlen(trim($_GET['urnfilter']))>0){
		$stmt = $sql->prepare('SELECT urn FROM workdata WHERE urn LIKE ? ORDER BY urn');
		$stmt->execute(['%'.$_GET['urnfilter'].'%']);
	}
	else{
		$stmt = $sql->prepare('SELECT urn FROM workdata ORDER BY urn');
		$stmt->execute();
	}
	
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res .= $row[$key].$nl;
	}
	return $res;
}
echo trim(editions(),"\n");
?>