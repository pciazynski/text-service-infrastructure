<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns Document authors matching the snippet from workdata table. 
# Params: snippet [prefixsearch, suffixsearch]

function authors($snippet){
	global $sql;
	$query = 'SELECT author, count(author) as cd FROM workdata WHERE author IS NOT NULL and author LIKE ? GROUP BY author';
	(isset($_GET['sort'])) ? $query .= ' ORDER BY cd DESC,author' : NULL;

	$res = '';
	$nl = "\n";
	$stmt = $sql->prepare($query);
	if (isset($_GET['prefixsearch'])){
		$stmt->execute([$snippet.'%']);
	}else{
		if (isset($_GET['suffixsearch'])){
			$stmt->execute(['%'.$snippet]);
	}else{$stmt->execute(['%'.$snippet.'%']);}}
		
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['author'].$nl;
	}
	return $res;
}

if (isset($_GET['snippet']) and strlen($_GET['snippet'])>0){
	echo trim(authors($_GET['snippet']),"\n");
}
?>