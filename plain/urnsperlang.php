<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

# Returns URNs with the requested lang value.
# Params: lang, urn
if (isset($_GET['urn']) and isset($_GET['lang'])){
	global $dbtablename;
	$lang = trim(htmlspecialchars($_GET['lang']));
	$urn = checkurn($_GET['urn'],'');
	$res = '';
	$nl = "\n";
	$tab = "\t";
	$stmt = $sql->prepare('SELECT urn,lang FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND lang = ? ORDER BY urnid');
	(str_ends_with('urn',':')) ? $stmt->execute([$urn.'%',$lang]):$stmt->execute([$urn.'.%',$lang]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res .= $row['urn'].$tab.$row['lang'].$nl;
	}

	echo trim($res,"\n");
}
?>