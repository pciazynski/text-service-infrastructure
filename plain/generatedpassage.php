<?php
header('Content-Type: text/plain');

require('../functions.php');
require('../config.php');
function passage($urn){
	global $sql;
	global $binary;
	global $dbtablename;
	
		
	#LIKE urn.% OR (exactly) LIKE urn
	if(str_ends_with($urn,":")){
		$query = "SELECT text FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn."%' ORDER BY urnid";
	}
	else {$query = "SELECT text FROM ".$dbtablename." WHERE urn LIKE ".$binary." '".$urn.".%' OR urn LIKE ".$binary." '".$urn."' ORDER BY urnid";}
	$res = "";
	(isset($_GET['nl'])) ? $sep = "\n" : $sep = " ";

	foreach ($sql->query($query) as $row) {
		$res = trim($res.$row['text']).$sep;
	}
	return $res;
}
if(isset($_GET['urn'])){
	$urnarr = checkurn($_GET['urn']);
	
	if ($urnarr !== false){
		if($dbtablename == "urndata"){
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
}

?>