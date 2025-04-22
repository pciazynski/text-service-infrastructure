<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns edition level URN list.
# Params: urnfilter

function editions(){
	global $sql;
	$condi = '';
	
	(isset($_GET['urnfilter']) and strlen(trim($_GET['urnfilter']))>0) ? $condi .= ' AND urn LIKE "%'.$_GET['urnfilter'].'%"': NULL;
	
	$query = 'SELECT urn FROM workdata WHERE true '.$condi.' ORDER BY urn';
	$res = '';
	$nl = "\n";
	$key = 'urn';
	foreach ($sql->query($query) as $row) {
		$res = $res.$row[$key].$nl;
	}
	return $res;
}
echo trim(editions(),"\n");
?>