<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns a random low level URN e.g. for testing.
# Params: 

$query = "SELECT urn from workdata LIMIT 1";
$res = '';
foreach ($sql->query($query) as $row) {
	$res = $res.$row['urn'];
}

echo $res;

?>