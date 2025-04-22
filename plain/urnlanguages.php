<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns distinct urn languages. 
# Params: none
function lang(){
	global $sql;
	global $dbtablename;
	$res = "";
	$tab = "\t";
	$nl = "\n";
	$query = "SELECT DISTINCT lang FROM urndata";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['lang'].$nl;
	}
	$query = "SELECT DISTINCT lang FROM urndatarestr";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['lang'].$nl;
	}
	return $res;
}
echo lang();
?>