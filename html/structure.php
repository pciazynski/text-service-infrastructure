<?php
header('Content-Type: text/html');
require('../functions.php');
require('../config.php');


function getDocStrct($urn,$isWorkurn){
	global $sql;
	if($isWorkurn){$query = "SELECT urn,type,CHAR_LENGTH(text) AS len FROM urndata WHERE urn LIKE '".$urn."%' ORDER BY urnid";}
	else {$query = "SELECT urn,type,CHAR_LENGTH(text)  AS len FROM urndata WHERE urn LIKE '".$urn.".%' ORDER BY urnid";}

	$res = "<table><tr><th>URN</th><th>Type</th><th>Text length incl. XML</th>";
	foreach ($sql->query($query) as $row) {
		$res = $res.'<tr><td><a href="../plain/passage.php?urn='.$row['urn'].'&deletexml&nl">'.$row['urn'].'</a>'."</td><td>".$row['type']."</td><td>".$row['len']."</td></tr>";
	}
	$res = $res."</table>";

	return trim($res);
}
$urn = trim(htmlspecialchars($_GET["urn"]));

$urnarr = checkurn($urn);
if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){echo getDocStrct($urn,true);}
	elseif (strpos ($urnarr[4],"-")){echo getSpanningPassage($urn);}
	else {echo getDocStrct($urn,false);};
}

?>