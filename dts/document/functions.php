<?php
function autocomplete($urn){
	global $sql;
	global $binary;
	global $dbtablename;

	$urnarr = explode(':',$urn);
	$testurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3];
	$res = '';
	$stmt = $sql->prepare('SELECT urn, restricted FROM workdata WHERE urn LIKE '.$binary.' ? LIMIT 1');
	$stmt->execute([$testurn.'%']);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row['urn'];
		if ($row['restricted'] == 1){
			$dbtablename = 'urndatarestr';
		}
		else{
			$dbtablename = 'urndata';
		}
	}
	if (strlen($res)>0){return trim($res.$urnarr[4]);}else{return '';}
}

function restrictedAccess(){
	global $copyrighttoken;
	if(isset($_GET['copyrighttoken']) and strlen($copyrighttoken)>0 and $_GET['copyrighttoken'] == $copyrighttoken){
		return true;
	}
	return false;
}

function deletexml($res){
	$res = preg_replace('/<[^>]+>/', ' ', $res);
	while(str_contains($res,'  ')){
		$res = str_replace('  ',' ',$res);
	}
	return $res;
}

function urnexists($urn){
	global $sql;
	global $binary;
	global $dbtablename;
	$res = '';
	$stmt = $sql->prepare('SELECT urnid FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? LIMIT 1');
	$stmt->execute([$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $row['urnid'];
	}
	return (strlen($res)>0);
}

function checkurn($urn,$format){
	$urn = trim(htmlspecialchars($urn));
	if(strpos($urn,'urn:cts') !== 0){require('../../errormsg/'.$format.'invalidurnsyntax.php');}
	$urn = autocomplete($urn);
	if(strlen($urn) == 0){require('../../errormsg/'.$format.'invalidurn.php');}
	$urnarr = explode(':',$urn);
	if(strpos ($urnarr[4],'-')){
		$workurn = $urnarr[0].':'.$urnarr[1].':'.$urnarr[2].':'.$urnarr[3].':';
		$psgarr = explode('-',$urnarr[4]);
		if(strpos ($psgarr[0],'@')){
			$psgarr[0] = explode('@',$psgarr[0])[0];
		}
		if(strpos ($psgarr[1],'@')){
			$psgarr[1] = explode('@',$psgarr[1])[0];
		}

		if(urnexists($workurn.$psgarr[0])==0){require('../../errormsg/'.$format.'invalidurn.php');}
		if(urnexists($workurn.$psgarr[1])==0){require('../../errormsg/'.$format.'invalidurn.php');}
	}else{
		(strpos ($urnarr[4],'@')) ? $testurn = explode('@',$urn)[0] : $testurn=$urn;
		if(urnexists($testurn)==0){require('../../errormsg/'.$format.'invalidurn.php');}
	}
	return $urn;
}

?>