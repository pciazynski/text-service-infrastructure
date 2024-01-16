<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getValidReff.php');

# Returns child URNs as specified by GetValidReff but without XML.
# Params: urn, (level)
$urn = htmlspecialchars($_GET["urn"]);
if (isset($_GET["level"])){
	$level = htmlspecialchars($_GET["level"]);
}else{
	$level = -1;
}

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo validreff($urn,true,$level);}
	else {echo validreff($urn,false,$level);};
}
?>