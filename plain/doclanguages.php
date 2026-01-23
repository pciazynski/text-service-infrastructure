<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns distinct document languages and their number. 
# Params: none
function lang(){
	global $sql;
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$stmt = $sql->prepare('SELECT lang, count(lang) as cd FROM workdata WHERE lang IS NOT NULL GROUP BY lang ORDER BY lang');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res .= $row['lang'].$tab.$row['cd'].$nl;
	}
	return $res;
}
echo lang();
?>