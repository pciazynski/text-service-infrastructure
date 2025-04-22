<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns URNs with the requested lang value.
# Params: lang, (urn)
$lang = trim(htmlspecialchars($_GET["lang"]));
(isset($_GET["urn"])) ? $urn = htmlspecialchars($_GET["urn"]) : $urn = "urn:cts:";

$dbtablename;

$query = "SELECT urn FROM ".$dbtablename." WHERE lang = '".$lang."' AND urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";
$res = "";
$nl = "\n";
foreach ($sql->query($query) as $row) {
	$res = $res.$row['urn'].$nl;
}

echo $res;

?>