<?php
function autocomplete($urn){
	global $sql;
	global $binary;
	global $dbtablename;

	$urnarr = explode(":",$urn);
	$testurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3];
	$query = "SELECT urn, restricted FROM workdata WHERE urn LIKE ".$binary." '".$testurn."%' LIMIT 1";
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'];
		if ($row['restricted'] == 1){
			$dbtablename = "urndatarestr";
		}
		else{
			$dbtablename = "urndata";
		}
	}
	if (strlen($res)>0){return trim($res.$urnarr[4]);}else{return "";}
}

function restrictedAccess(){
	global $copyrighttoken;
	if(isset($_GET['copyrighttoken']) and strlen($copyrighttoken)>0 and $_GET['copyrighttoken'] == $copyrighttoken){
		return true;
	}
	return false;
}

function deletexml($res){
	$res = preg_replace('/<lb[^>]+>/', " \n", $res);
	$res = preg_replace('/<[^>]+>/', '', $res);
	return $res;
}

function urnexists($urn){
	global $sql;
	global $binary;
	global $dbtablename;
	$query = 'SELECT urnid FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' "'.$urn.'" LIMIT 1';
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $row['urnid'];
	}
	return (strlen($res)>0);
}

function checkurn($urn,$format){
	$urn = trim(htmlspecialchars($urn));
	if(strpos($urn,"urn:cts") !== 0){require('../errormsg/'.$format.'invalidurnsyntax.php');}
	$urn = autocomplete($urn);
	if(strlen($urn) == 0){require('../errormsg/'.$format.'invalidurn.php');}
	$urnarr = explode(":",$urn);
	if(strpos ($urnarr[4],"-")){
		$workurn = $urnarr[0].":".$urnarr[1].":".$urnarr[2].":".$urnarr[3].":";
		$psgarr = explode("-",$urnarr[4]);
		if(urnexists($workurn.$psgarr[0])==0){require('../errormsg/'.$format.'invalidurn.php');}
		if(urnexists($workurn.$psgarr[1])==0){require('../errormsg/'.$format.'invalidurn.php');}
	}else{
		if(urnexists($urn)==0){require('../errormsg/'.$format.'invalidurn.php');}
	}
	return $urn;
}

?>