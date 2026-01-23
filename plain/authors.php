<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns Document authors from workdata table. 
# Params: none

function authors(){
	global $sql;
	$query = 'SELECT author, count(author) as cd FROM workdata WHERE author IS NOT NULL GROUP BY author';
	(isset($_GET['sort'])) ? $query .= ' ORDER BY cd DESC,author' : NULL;

	$res = '';
	$nl = "\n";
	$tab = "\t";
	$stmt = $sql->prepare($query);
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['author'].$tab.$row['cd'].$nl;
	}
	return $res;
}

echo trim(authors(),"\n");
?>