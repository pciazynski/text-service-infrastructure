<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns document content for document for cloning. Limited to copyright free data.
# Params: none
if(isset($_GET['urn']) and str_ends_with($_GET['urn'],":")){
	$docurn = $_GET['urn'];
	$tab = "\t";
	$nl = "\n";
	$stmt = $sql->prepare('SELECT urn, text, lang, type FROM urndata WHERE urn LIKE ?');
	$docurn = str_replace("_","\_",$docurn);
	$stmt->execute([$docurn."%"]);
	$res = '';
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res .= $row['urn'].$tab.$row['text'].$tab.$row['lang'].$tab.$row['type'].$nl;
	}
	echo $res;
}


?>