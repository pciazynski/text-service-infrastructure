<?php
#SQL information
$sql = new PDO('mysql:host=localhost;dbname=cts', 'ctsapi', 'pw');

#change this to "BINARY" if you want case sensitive URNs at the cost of performance
$binary = "";
?>