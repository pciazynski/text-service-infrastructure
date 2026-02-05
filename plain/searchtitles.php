<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns Document titles matching the snippet from workdata table. 
# Params: snippet

function titles($snippet){
	global $sql;
	if (isset($_GET['sort'])){
		$query = 'SELECT DISTINCT title FROM workdata WHERE title IS NOT NULL and title LIKE ? ORDER BY title';
	}
	else{
		$query = 'SELECT title, count(title) as cd FROM workdata WHERE title IS NOT NULL and title LIKE ? GROUP BY title ORDER BY cd';
	}

	$res = '';
	$nl = "\n";
	$stmt = $sql->prepare($query);
	if (isset($_GET['prefixsearch'])){
		$stmt->execute([$snippet.'%']);
	}else{
		if (isset($_GET['suffixsearch'])){
			$stmt->execute(['%'.$snippet]);
	}else{$stmt->execute(['%'.$snippet.'%']);}}	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['title'].$nl;
	}
	return $res;
}

if (isset($_GET['snippet']) and strlen($_GET['snippet'])>1){
	echo trim(titles($_GET['snippet']),"\n");
}
?>