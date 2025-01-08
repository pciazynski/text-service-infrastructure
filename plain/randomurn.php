<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns a random low level URN e.g. for testing.
# Params: 

if(isset($_GET['offset'])){
	$offset = $_GET['offset'];
}
else{
	$offset=1;
}
$query = "SELECT urn from urndata WHERE urnid BETWEEN ".$offset." AND ".($offset+10)." AND TEXT IS NOT NULL LIMIT 1";
$res = '';
$nl = "\n";
foreach ($sql->query($query) as $row) {
	$res = $res.$row['urn'].$nl;
}

echo $res;

?>