<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

/*
Returns URNs and text. Limited to copyright-free documents. exactsearch.php returns only URNs and includes copyright-restricted documents.
*/


if (isset($_GET['urn']) && isset($_GET['snippet'])){
	$urn = checkurn($_GET['urn'],'');
	if($dbtablename == 'urndatarestr'){
		require('../errormsg/access.php');
	}
	
	(isset($_GET['limit'])) ? $limit = max(0,intval($_GET['limit'])) : $limit = 1000;
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$resrow1='urn';
	$resrow2='text';
	
	$stmt = $sql->prepare('SELECT urn, text FROM urndata WHERE urn LIKE '.$binary.' ? and text LIKE ? LIMIT '.$limit);
	$urn = str_replace("_","\_",$urn);
	$snippet = str_replace(array("_","%"),array("\_","\%"),$_GET['snippet']);
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%','%'.$snippet.'%']):$stmt->execute([$urn.'.%','%'.$snippet.'%']);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res .= $row[$resrow1].$tab.$row[$resrow2].$nl;
	}

	($deleteXML) ? $res = deletexml($res) : NULL;

	echo ($res);
}
?>