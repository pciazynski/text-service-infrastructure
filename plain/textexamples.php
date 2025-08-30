<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

if ( isset($_GET["snippet"])){
	(isset($_GET["limit"])) ? $limit = $_GET["limit"] : $limit = "10";
	$token = trim(htmlspecialchars($_GET['token']));
	$res = "";
	$nl = "\n";
	$i = 0;
	$query = "SELECT text FROM urndata WHERE text LIKE '% ".$_GET["snippet"]." %' LIMIT ".$limit;
	foreach ($sql->query($query) as $row){
		$i = $i + 1;
		$res = $res.$row['text'].$nl;
	}
	
	#Less than 5 found and copyrighted documents exist
	if($limit>5){
		if($restricteddocuments){
			$limit = 5;
			if($i<$limit){
				$limit = $limit - $i;
				if($limit>0){
					$query = "SELECT text FROM urndatarestr WHERE text LIKE '% ".$_GET["snippet"]." %' ORDER BY text LIMIT ".$limit;
					foreach ($sql->query($query) as $row){
						$res = $res.$row['text'].$nl;
					}
				}
			}
		}
	}
	(isset($_GET["deletexml"])) ? $res = deletexml($res) : NULL;
	echo ($res);
}
?>