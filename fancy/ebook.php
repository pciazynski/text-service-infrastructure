<?php
header('Content-Type: text/html');
require('../functions.php');
require('../config.php');
require('db_getFancyPassage.php');

function ebookeditions(){
	global $sql;
	
	$offset = 0;
	if (isset($_GET['offset'])){
		$offset = $_GET['offset'];
	}
	$query = "SELECT urn,title,year,author FROM workdata ORDER BY urn LIMIT 10000 OFFSET ".$offset;
	$res = "<ul>";
	$tab = "\t";
	$nl = "\n";
	$space=" ";
	foreach ($sql->query($query) as $row) {
		$res .= '<li><a href="?urn='.$row['urn'].'">';
		$title = $row['title'];
		if(trim(strlen($title))==0){
			$title = str_replace("_"," ",explode(":",$row['urn'])[3]);
			$title = ucwords($title);
		}
		$res .= $title;
		$res .= '</a></li>';
	}
	$res = $res."</ul>";
	return $res;
}


if (isset($_GET["urn"])){
	$urn = checkurn($_GET['urn']),'');
	$html = '<!DOCTYPE html><link rel="stylesheet" href="ebook.css">';
	$html.=passage($urn);
	
	echo $html;
}else{
	echo ebookeditions();
}
?>