<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPrevNextUrn.php');


$urn = htmlspecialchars($_GET["urn"]);

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo prevnexturn($urn,true);}
	else {echo prevnexturn($urn,false);};
}
?>