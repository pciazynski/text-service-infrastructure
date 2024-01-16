<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns Document authors from workdata table. 
# Params: none

function authors(){
	global $sql;
	$query = "SELECT author, count(author) as cd FROM workdata GROUP BY author ORDER BY author";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['author']."\t".$row['cd']."\n";
	}
	return $res;
}
echo authors();
?>