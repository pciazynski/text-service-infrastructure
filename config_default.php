<?php

#SQL information
$sql = new PDO('mysql:host=localhost;dbname=cts', 'ctsapi', 'pw');
$multibyte = false;

#normalization for tm. not relevant standard usage.
$replacearr = array("—","}","{","♦","†","‚","‘","‘","_","«","»","•","☚","☛","+","|","*","#","'","}","=","/","!","”","…",'"',",",".","?","(",")","[","]",";",":","“","„");
$punctarr = array(",",".","!","?","―");

#change this to "BINARY" if you want case sensitive URNs at the cost of performance
$binary = "";

#change to true if restricted documents should be made available. If set to false, then the unnecessary database checks for restrictions are skipped and no restricted document content is served.
$restricteddocuments = false
	
?>