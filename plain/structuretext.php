<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns every child URN of a given static URN plus the corresponding text snippet. Dynamic URNs are not (yet) supported. 
# Params: urn, (deletexml)

function getDocStrct($urn,$isWorkurn){
	global $sql;
	if($isWorkurn){$query = "SELECT urn,type,text FROM urndata WHERE urn LIKE BINARY '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT urn,type,CHAR_LENGTH(text)  AS len FROM urndata WHERE urn LIKE BINARY '".$urn.".%' ORDER BY urnid";}
	$res = "";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn']."\t".$row['type']."\t".$row['text']."\n";
	}
	if(isset($_GET["deletexml"])){
		$res = preg_replace('/<[^>]+>/', "", $res);
	}
	return trim($res);
}
$urn = trim(htmlspecialchars($_GET["urn"]));
$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo getDocStrct($urn,true);}
	elseif (strpos ($urnarr[4],"-")){echo "";}
	else {echo getDocStrct($urn,false);};
}

?>
