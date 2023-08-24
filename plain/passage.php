<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage.php');


$urn = trim(htmlspecialchars($_GET["urn"]));

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo passage($urn,true);}
	elseif (strpos ($urnarr[4],"-")){echo spanningPassage($urn);}
	else {echo passage($urn,false);};
}
?>