<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getValidReff.php');

# Returns child URNs as specified by GetValidReff but without XML.
# Params: urn, (level)
$urn = trim(htmlspecialchars($_GET["urn"]));

(isset($_GET["level"])) ? $level = trim(htmlspecialchars($_GET["level"])) : $level = -1;

$urn = checkurn($urn,'');
$urnarr = explode(":",$urn);
if (strlen($urnarr[4]) == 0){echo validreff($urn,true,$level);}
else {echo validreff($urn,false,$level);};

?>