<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getFirstUrn.php');

# Return first child URN of a speciified URN.
# Params: urn

$urn = htmlspecialchars($_GET["urn"]);

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo firsturn($urn,true);}
	else {echo firsturn($urn,false);};
}
?>