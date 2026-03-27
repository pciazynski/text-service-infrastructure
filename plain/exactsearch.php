<?php
header('Content-Type: text/plain');

require('../config.php');
require('../functions.php');
/*
Returns only URNs, not the text. Text is returned by textsearch.php but limited to copyright free documents.
*/


function textsearch($urn, $snippet){
	global $sql;
	global $dbtablename;
	global $binary;

	$res = '';
	$nl = "\n";
	(str_ends_with($urn,':')) ? : $urn = $urn.'.';

	(isset($_GET['limit'])) ? $limit = intval($_GET['limit']) : $limit = 10000;

	$stmt = $sql->prepare('SELECT urn FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND text LIKE ? ORDER BY urnid LIMIT '.$limit);
	$urn = str_replace("_","\_",$urn);
	$snippet = str_replace("_","\_",$snippet);
	$stmt->execute([$urn.'%','%'.$snippet.'%']);

	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['urn'].$nl;
	}
	
	return trim($res);
}

if (isset($_GET['snippet'])){
	$urn = checkurn($_GET['urn'],'');
	$urnarr = explode(':',$urn);
	$snippet = trim(urldecode($_GET['snippet']));
	
	if($dbtablename == 'urndata'){
		echo textsearch($urn,$snippet);
	}
	else{
		if(restrictedAccess()){
			echo textsearch($urn,$snippet);
		}
		else{
			require('../errormsg/access.php');
		}
	}
}
?>