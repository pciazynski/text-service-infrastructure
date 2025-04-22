<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns distinct publication dates and their number. 
# Params: none
function dates(){
	global $sql;
	$query = "SELECT year, count(year) as cd FROM workdata WHERE year IS NOT NULL GROUP BY year ORDER BY year";
	$res = "";
	$tab = "\t";
	$nl = "\n";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['year'].$tab.$row['cd'].$nl;
	}
	return $res;
}
echo trim(dates(),"\n");
?>