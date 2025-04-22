<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns distinct document languages and their number. 
# Params: none
function lang(){
	global $sql;
	$query = "SELECT lang, count(lang) as cd FROM workdata WHERE lang IS NOT NULL GROUP BY lang ORDER BY lang";
	$res = "";
	$tab = "\t";
	$nl = "\n";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['lang'].$tab.$row['cd'].$nl;
	}
	return $res;
}
echo lang();
?>