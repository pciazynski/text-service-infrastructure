<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns a random low level URN e.g. for testing.
# Params: 

$res = '';
$stmt = $sql->prepare('SELECT urn from workdata LIMIT 1');
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
	$res = $res.$row['urn'];
}

echo $res;

?>