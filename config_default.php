<?php
require('constants.php');

#SQL information
$sql = new PDO('mysql:host=localhost;dbname=cts', 'ctsapi', 'pw');
$multibyte = false;

#normalization for tm. not relevant standard usage.
$replacearr = array("}","{","♦","†","‚","‘","‘","_","«","»","―","•","☚","☛","+","|","*","#","'","}","=","/","!","”","…",'"',",",".","?","(",")","[","]",";",":","—","“","„");
$punctarr = array(",",".","!","?","―");

#change this to "BINARY" if you want case sensitive URNs at the cost of performance
$binary = "";
	
?>