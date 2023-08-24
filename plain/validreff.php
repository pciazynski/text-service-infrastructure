<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getValidReff.php');


$urn = htmlspecialchars($_GET["urn"]);
if (isset($_GET["validreffcount"])){
	$validreffcount = htmlspecialchars($_GET["validreffcount"]);
}else{
	$validreffcount = 0;
}

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo validreff($urn,true,$validreffcount);}
	else {echo validreff($urn,false,$validreffcount);};
}
?>