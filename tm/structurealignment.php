<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage_restr.php');

$urn = trim(htmlspecialchars($_GET["urn"]));
$psg="";

(isset($_GET['alignpart'])) ? $alignpart = min($_GET['alignpart'],3) : $alignpart = 3;
$urnarr = checkurn($urn);
if ($urnarr !== false){
	$urndocpartarr = explode(".",$urnarr[3]);
	$urndocpartarr[$alignpart] = '%';
	$urnwc = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urndocpartarr[0].'.'.$urndocpartarr[1].'.'.$urndocpartarr[2].'.'.$urndocpartarr[3].':';

	$query = 'SELECT urn from workdata WHERE restricted = 0 AND URN LIKE "'.$urnwc.'"';
	$tab = "\t";
	$nl = "\n";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$urn = $row['urn'].$urnarr[4];
		if (strlen($urnarr[4]) == 0){$psg = passage($urn, $deleteXML=true);}
		elseif (strpos ($urnarr[4],"-")){$psg =  spanningPassage($urn, $deleteXML=true);}
		else {$psg = passage($urn, $deleteXML=true);};
		$res.= $urn.$tab.$psg.$nl;
	}

	echo($res);
}
?>