<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getLabel.php');

# Returns the label for a specified URN. The result is based on db_GetLabel.php
# Params: urn

echo label(trim(htmlspecialchars($_GET["urn"])));
?>