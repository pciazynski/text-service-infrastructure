<?php
header('Content-Type: text/html');
require('../functions.php');
require('../config.php');
require('../db_getPrevNextUrn.php');


$urn = htmlspecialchars($_GET["urn"]);

$urn = checkurn($urn,'');
$urnarr = explode(":",$urn);
if (strlen($urnarr[4]) == 0){$arr = explode("\n",prevnexturn($urn,true));}
else {$arr = explode("\n",prevnexturn($urn,false));};

$html='<a href="prevnexturn.php?urn='.$arr[0].'">'.$arr[0].'</a><br>';
$html=$html.'<a href="prevnexturn.php?urn='.$arr[1].'">'.$arr[1].'</a>';
echo $html;

?>