<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns URNs with the requested lang value.
# Params: lang, (urn)
$lang = htmlspecialchars($_GET["lang"]);
if (isset($_GET["urn"])){
	$urn = htmlspecialchars($_GET["urn"]);
}else{
	$urn = "urn:cts:";
}

$query = "SELECT urn FROM urndata WHERE lang = '".$lang."' AND urn LIKE ".$binary." '".$urn."%' ORDER BY urnid LIMIT 10000";
$res = "";
foreach ($sql->query($query) as $row) {
	$res = $res.$row['urn']."\n";
}

echo $res;

?>