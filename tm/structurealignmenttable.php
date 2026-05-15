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
$initdocurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3].':';
$urndocpartarr[$alignpart] = '%';
$urnwc = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urndocpartarr[0].'.'.$urndocpartarr[1].'.'.$urndocpartarr[2].'.'.$urndocpartarr[3].':';

$tab = "\t";
$nl = "\n";
$res = '';
$urnrow = 'urn';
$mode = 0;
if (strpos ($urnarr[4],'-')){$mode = 1;}

$stmt = $sql->prepare('SELECT urn from workdata WHERE restricted = 0 AND URN LIKE ?');
$urnwc = str_replace("_","\_",$urnwc);
$stmt->execute([$urnwc]);
$header = '';
$headerarr = array();
$resarr = array();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
	$col = array();
	$docurn= $row[$urnrow];
	array_push($headerarr,$docurn);
	$urn = $docurn.$urnarr[4];
	$urn = str_replace("_","\_",$urn);
	$stmt2 = $sql->prepare('SELECT urn,text FROM urndata WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
	(str_ends_with($urn,':')) ? $stmt2->execute([$urn.'%']):$stmt2->execute([$urn.'.%']);
	foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $row2) {
		$alignpart = explode(':',$row2['urn'])[4];
		$col[$alignpart] = $row2['text'];
	}
	$resarr[$docurn] = $col;
}
foreach($headerarr as $docurn){
	$header .= $tab.$docurn;
}

$header.=$nl;

foreach(array_keys($resarr[$initdocurn]) as $psgid){
	$res.=$psgid.$tab;
	foreach($headerarr as $docurn){
		$res.=$resarr[$docurn][$psgid].$tab;
	}
	$res.=$nl;
}

echo($header.$res);

?>