<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns Document authors from workdata table. 
# Params: none

function authors(){
	global $sql;
	$query = "SELECT author, count(author) as cd FROM workdata WHERE author IS NOT NULL GROUP BY author";

	if (isset($_GET['sort'])){
		$query .= ' ORDER BY cd DESC,author';
	}

	$res = "";
	$nl = "\n";
	$tab = "\t";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['author'].$tab.$row['cd'].$nl;
	}
	return $res;
}
echo authors();
?>