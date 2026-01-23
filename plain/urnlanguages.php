<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns distinct urn languages. 
# Params: none
function lang(){
	global $sql;
	global $dbtablename;
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$stmt = $sql->prepare('SELECT DISTINCT lang FROM urndata');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['lang'].$nl;
	}
	$stmt = $sql->prepare('SELECT DISTINCT lang FROM urndatarestr');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		if(! str_contains($res,$row['lang'])){
			$res .= $row['lang'].$nl;
		}
	}
	return $res;
}
echo lang();
?>