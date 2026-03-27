<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns Document authors matching the snippet from workdata table. 
# Params: snippet [prefixsearch, suffixsearch]

function authors($snippet){
	global $sql;
	if (isset($_GET['sort'])){
		$query = 'SELECT DISTINCT author FROM workdata WHERE author IS NOT NULL and author LIKE ? ORDER BY author';
	}
	else{
		$query = 'SELECT author, count(author) as cd FROM workdata WHERE author IS NOT NULL and author LIKE ? GROUP BY author ORDER BY cd';
	}
	
	$res = '';
	$nl = "\n";
	$stmt = $sql->prepare($query);
	$snippet = str_replace(array("_","%"),array("\_","\%"),$_GET['snippet']);
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