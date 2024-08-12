<?php

function passage($urn){
	global $sql;
	global $binary;
	
	#LIKE urn.% OR (exactly) LIKE urn
	if(str_ends_with($urn,":")){$query = "SELECT text,type,urn FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid LIMIT 1,18446744073709551615";}
	else {$query = "SELECT text,type,urn FROM urndata WHERE urn LIKE ".$binary." '".$urn.".%' OR urn LIKE ".$binary." '".$urn."' ORDER BY urnid";}
	$res = "";
	$stack = [];
	$oldurnpartcount = 0;
	$nl = "<br>";
	foreach ($sql->query($query) as $row) {
		$newurnpartcount = count(explode(".",explode(":",$row['urn'])[4]));
		if ($newurnpartcount==1){
			$res = $res.'<hr>';
		}
		
		while ($newurnpartcount<=count($stack)){
			$res = $res.'</'.array_pop($stack).'>';
		}
		$type = str_replace(["head","list","item","lg","l"],["h".$newurnpartcount,"ul","li","ul","li"],$row['type']);
		$res = preg_replace('/<lb[^>]+>/', $nl, $res);
		$res = $res.'<'.$type.'>';
		array_push($stack,$type);
		$res = $res.preg_replace('/<[^>]+>/', "", $row['text']).$nl;
		
	}
	return $res;
}
?>