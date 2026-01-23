<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage_restr.php');

$urn = checkurn($_GET['urn'],'');
$urnarr = explode(':',$urn);
$psg='';

(isset($_GET['alignpart'])) ? $alignpart = min($_GET['alignpart'],3) : $alignpart = 3;
$urndocpartarr = explode('.',$urnarr[3]);
$urndocpartarr[$alignpart] = '%';
$urnwc = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urndocpartarr[0].'.'.$urndocpartarr[1].'.'.$urndocpartarr[2].'.'.$urndocpartarr[3].':';


$tab = "\t";
$nl = "\n";
$res = '';
$urnrow = 'urn';
$mode = 0;
if (strpos ($urnarr[4],'-')){$mode = 1;}

$stmt = $sql->prepare('SELECT urn from workdata WHERE restricted = 0 AND URN LIKE ?');
$stmt->execute([$urnwc]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
	$urn = $row[$urnrow].$urnarr[4];
	($mode == 0) ? $psg = passage($urn, $deleteXML=true) : $psg = spanningPassage($urn, $deleteXML=true);
	$res.= $urn.$tab.$psg.$nl;
}

echo($res);

?>