<?php

#SQL information
$sql = new PDO('mysql:host=localhost;dbname=cts', 'ctsapi', 'pw');
$multibyte = false;

#normalization for tm.
#tokenization
$replacearr = array(".",",","!","?",'"');
#sentence segmentation
$punctarr = array(".",",","!","?",'"');

#change this to "BINARY" if you want case sensitive URNs at the cost of performance
$binary = "";

#change to true if restricted documents are included. If set to false, then unnecessary database checks are skipped and no restricted document content is served.
$restricteddocuments = false;
$copyrighttoken = "";

?>