<?php
header('Content-Type: text/plain');

require('../functions.php');
require('../config.php');
function passage($urn){
	global $sql;
	global $binary;
	global $dbtablename;

	$res = '';
	(isset($_GET['nl'])) ? $sep = "\n" : $sep = ' ';

	if(str_ends_with($urn,':')){
		$stmt = $sql->prepare('SELECT text FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
		$stmt->execute([$urn.'%']);
	}
	else {
		#LIKE urn.% OR (exactly) LIKE urn
		$stmt = $sql->prepare('SELECT text FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? OR urn LIKE '.$binary.' ? ORDER BY urnid');
		$stmt->execute([$urn.'.%',$urn]);
	}
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = trim($res.$row['text']).$sep;
	}
	return $res;
}

if(isset($_GET['urn'])){
	$urn = checkurn($_GET['urn'],'');
	$urnarr = explode(':',$urn);
	if($dbtablename == 'urndata'){
		print(passage($_GET['urn']));
	}
	else{
		if(restrictedAccess()){
			print(passage($_GET['urn']));
		}
		else{
			require('../errormsg/access.php');
		}
	}
}

?>