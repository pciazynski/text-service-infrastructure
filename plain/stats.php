<?php
header('Content-Type: text/plain');

echo "Document URNs: ";
require('doccount.php');

echo "\nStatic Text URNs: ";
require('urncount.php');
?>