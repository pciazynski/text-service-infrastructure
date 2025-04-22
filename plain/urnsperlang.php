<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns URNs with the requested lang value.
# Params: lang, urn
if (isset($_GET["urn"]) and isset($_GET["lang"])){
	$urn = htmlspecialchars($_GET["urn"]);
	$lang = trim(htmlspecialchars($_GET["lang"]));

	global $dbtablename;

	$urn = trim(htmlspecialchars($_GET["urn"]));
	$urnarr = checkurn($urn);

	$query = "SELECT urn FROM ".$dbtablename." WHERE lang = '".$lang."' AND urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";
	$res = "";
	$nl = "\n";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'].$nl;
	}

	echo $res;
}
?>