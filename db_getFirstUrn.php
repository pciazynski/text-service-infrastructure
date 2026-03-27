<?php

function firsturn($urn){
	global $sql;
	global $binary;
	global $dbtablename;

	$res = '';
	$stmt = $sql->prepare('SELECT urn FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND text IS NOT NULL LIMIT 1');
	$urn = str_replace("_","\_",$urn);
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%']):$stmt->execute([$urn.'.%']);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res .= $row['urn'];
	}
	return trim($res);
}
?>