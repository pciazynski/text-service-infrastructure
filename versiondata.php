<?php
header('Content-Type: text/plain');
require('functions.php');
require('config.php');

# Returns the date of last change in db files.
# Params: 

$dbname = file_get_contents('config.php');
$dbname = explode("=",explode(",", explode(";",$dbname)[1])[0])[1];
$dbname = trim($dbname,' "\'');

$res = '';
$tab = "\t";
$stmt = $sql->prepare('SELECT UPDATE_TIME FROM information_schema.tables WHERE  table_schema = "'.$dbname.'";');
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
	$res .= $row['UPDATE_TIME'];
}

echo $res;

?>