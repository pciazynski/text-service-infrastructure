<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getLabel.php');

# Returns the label for a specified URN. The result is based on db_GetLabel.php
# Params: urn

if(isset($_GET["urn"])){
	$urn = trim(htmlspecialchars($_GET["urn"]));
	$urn = checkurn($urn,'');
	echo label($urn);
}
?>