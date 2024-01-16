<?php
header('Content-Type: text/plain');

# Compiles overview information about the CTS instance based on a number of requests. See source code for detailed info.

echo "Document URNs: ";
require('doccount.php');

echo "\nStatic Text URNs: ";
require('urncount.php');

echo "\nDaterange: ";
require('daterange.php');
?>