<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns every child URN with corresponding type and textlength. Dynamic URNs are not (yet) supported. 
# Params: urn

function getDocStruct($urn){
	global $sql;
	global $binary;
	
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$resrow1='urn';
	$resrow2='type';
	$resrow3='len';
	
	$stmt = $sql->prepare('SELECT urn,type,CHAR_LENGTH(text) AS len FROM urndata WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
	$urn = str_replace("_","\_",$urn);
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%']):$stmt->execute([$urn.'.%']);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row[$resrow1].$tab.$row[$resrow2].$tab.$row[$resrow3].$nl;
	}
	return trim($res);
}
$urn = checkurn($_GET['urn'],'');
$urnarr = explode(':',$urn);

if (strpos ($urnarr[4],'-')){echo '';}
else {echo getDocStruct($urn);};

?>