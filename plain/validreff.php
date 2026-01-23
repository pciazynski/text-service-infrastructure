<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getValidReff.php');

# Returns child URNs as specified by GetValidReff but without XML.
# Params: urn, (level)
$urn = checkurn($_GET['urn'],'');
$urnarr = explode(':',$urn);

(isset($_GET['level'])) ? $level = trim(htmlspecialchars($_GET['level'])) : $level = -1;

if (strlen($urnarr[4]) == 0){echo validreff($urn,true,$level);}
else {echo validreff($urn,false,$level);};

?>