<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPrevNextUrn.php');

# Returns the previous and next (or left and right) URN in document order or NULL if the specified URN if there is none. 
# Params: urn

$urn = checkurn($_GET['urn'],'');
$urnarr = explode(":",$urn);

if (strlen($urnarr[4]) == 0){echo(prevnexturn($urn,true));}
else {echo(prevnexturn($urn,false));};

?>