<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns every child URN of a given static URN plus the corresponding text snippet. Dynamic URNs are not (yet) supported. 
# Params: urn, (deletexml)

function getDocStrct($urn){
	global $sql;
	global $binary;
	global $dbtablename;
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$resrow1='urn';
	$resrow2='type';
	$resrow3='text';
	
	$stmt = $sql->prepare('SELECT urn,type,text FROM urndata WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%']):$stmt->execute([$urn.'.%']);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $res.$row[$resrow1].$tab.$row[$resrow2].$tab.$row[$resrow3].$nl;
	}
	
	(isset($_GET['deletexml'])) ? $res = deletexml($res) : NULL;
	
	return trim($res,"\n");
}
$urn = checkurn($_GET['urn'],'');

if($dbtablename == 'urndata'){
	echo getDocStrct($urn);
}
else{
	if(restrictedAccess()){
		echo getDocStrct($urn);
	}
	else{
		require('../errormsg/access.php');
	}

}

?>
