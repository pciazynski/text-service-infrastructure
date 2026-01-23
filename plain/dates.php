<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns distinct publication dates and their number. 
# Params: none
function dates(){
	global $sql;
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$stmt = $sql->prepare('SELECT year, count(year) as cd FROM workdata WHERE year IS NOT NULL GROUP BY year ORDER BY year');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['year'].$tab.$row['cd'].$nl;
	}
	return $res;
}
echo trim(dates(),"\n");
?>