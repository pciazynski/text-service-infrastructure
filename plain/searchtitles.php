<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns Document titles matching the snippet from workdata table. 
# Params: snippet

function titles($snippet){
	global $sql;
	$query = 'SELECT title, count(title) as cd FROM workdata WHERE title IS NOT NULL and title LIKE ? GROUP BY title';
	(isset($_GET['sort'])) ? $query .= ' ORDER BY cd DESC,title' : NULL;

	$res = '';
	$nl = "\n";
	$tab = "\t";
	$stmt = $sql->prepare($query);
	if (isset($_GET['prefixsearch'])){
		$stmt->execute([$snippet.'%']);
	}else{
		if (isset($_GET['suffixsearch'])){
			$stmt->execute(['%'.$snippet]);
	}else{$stmt->execute(['%'.$snippet.'%']);}}	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['title'].$tab.$row['cd'].$nl;
	}
	return $res;
}

if (isset($_GET['snippet'])){
	echo trim(titles($_GET['snippet']),"\n");
}
?>