<?php

function passage($urn){
	global $sql;
	global $binary;
	
	#LIKE urn.% OR (exactly) LIKE urn
	if(str_ends_with($urn,":")){$query = "SELECT text,type FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT text,type FROM urndata WHERE urn LIKE ".$binary." '".$urn.".%' OR urn LIKE ".$binary." '".$urn."' ORDER BY urnid";}
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.'<'.$row['type'].'>'.preg_replace('/<[^>]+>/', "", $row['text']).'</'.$row['type'].'>';
	}
	

	return $res;
}
?>