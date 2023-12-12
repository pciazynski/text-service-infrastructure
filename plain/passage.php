<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage.php');


$urn = trim(htmlspecialchars($_GET["urn"]));
$deletexml = isset($_GET["deletexml"]);
$nl = isset($_GET["nl"]);
$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo passage($urn,true,$deletexml,$nl);}
	elseif (strpos ($urnarr[4],"-")){echo spanningPassage($urn,$deletexml,$nl);}
	else {echo passage($urn,false,$deletexml,$nl);};
}
?>