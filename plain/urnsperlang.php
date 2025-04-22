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

	$query = "SELECT urn,lang FROM ".$dbtablename." WHERE lang LIKE '%".$lang."%' AND urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";
	$res = "";
	$nl = "\n";
	$tab = "\t";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'].$tab.$row['lang'].$nl;
	}

	echo trim($res,"\n");
}
?>