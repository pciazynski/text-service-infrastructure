<?php
header('Content-Type: text/html');
require('../functions.php');
require('../config.php');
require('db_getFancyPassage.php');

$urn = checkurn($_GET['urn']),'');
echo passage($urn);
?>