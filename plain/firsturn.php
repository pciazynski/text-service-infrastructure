<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getFirstUrn.php');

# Return first child URN of a speciified URN.
# Params: urn

$urn = checkurn($_GET['urn'],'');
$urnarr = explode(":",$urn);

echo firsturn($urn);

?>