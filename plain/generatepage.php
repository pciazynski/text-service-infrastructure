<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns a suggested page length spanning urn based on the given urn.
# Params: urn, (length)

function generatepage($urn,$length){
	global $sql;
	global $binary;
	global $dbtablename;
	
	$docurn = substr($urn, 0, strrpos($urn, ':')+1);

	$urnid1 = -1;
	$psgpart = "";
	$stmt = $sql->prepare('SELECT urnid FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? LIMIT 1');
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%']):$stmt->execute([$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$urnid1 = intval($row['urnid'])-1;
	}
	
	$snippettext = '';
	$stmt = $sql->prepare('SELECT urn,urnid,text FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND urnid BETWEEN ? AND ? ORDER BY urnid');
	$stmt->execute([$docurn.'%',$urnid1,($urnid1+101)]);
	$count = 0;
	$nexturn = "";
	$prevurn = "";
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		if($count==0){$prevurn=$row['urn'];}
		if(str_ends_with($urn,':')){$urn=$row['urn'];}
		if(strlen($snippettext)<$length and $count < 100){
			$psgpart = substr($row['urn'], strrpos($row['urn'], ':')+1);
			$snippettext.=$row['text'];
			$count+=1;
		}
		if($count == 100 or strlen($snippettext)>=$length){$nexturn = $row['urn'];break;}
	}

	$urn .= '-'.$psgpart;
	
	return trim($prevurn."\t".$urn."\t".$nexturn);
}

$urn = checkurn($_GET['urn'],'');
$urnarr = explode(':',$urn);

(isset($_GET['length'])) ? $length = intval($_GET['length']) : $length = 1000;

echo generatepage($urn,$length);

?>