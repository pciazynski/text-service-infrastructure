<?php
require('config.php');
require('functions.php');


function passage($urn,$isWorkurn,$deleteXML = false){
	global $sql;
	if($isWorkurn){$query = "SELECT text FROM urndata WHERE urn LIKE BINARY '".$urn."%' ORDER BY urnid";}
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['text'];
	}
	
	if($deleteXML){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	return trim($res);
}

print(passage("urn:cts:dsb:bramborski.zassnik.1906.30:", true));exit();


?>