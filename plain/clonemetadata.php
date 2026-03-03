<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns metadata for document for cloning
# Params: none
if(isset($_GET['urn']) and str_ends_with($_GET['urn'],":")){
	$docurn = $_GET['urn'];
	$tab = "\t";
	$stmt = $sql->prepare('SELECT restricted, license, title, contentType,author,year,lang FROM workdata WHERE urn = ?');
	$stmt->execute([$docurn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$res = $docurn.$tab.$row['restricted'].$tab.$row['license'].$tab.$row['title'].$tab.$row['contentType'].$tab.$row['author'].$tab.$row['year'].$tab.$row['lang'];
	}
	echo $res;
}


?>