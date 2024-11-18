<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns edition level URN list.
# Params: offset, urnfilter, author, title, year

function editions(){
	global $sql;
	$offset = 0;
	if (isset($_GET['offset'])){
		$offset = $_GET['offset'];
	}
	$condi = " WHERE TRUE";
	if (isset($_GET['urnfilter'])){
		$condi .= ' AND urn LIKE "%'.$_GET['urnfilter'].'%"';
	}
	if (isset($_GET['author'])){
		$condi .= ' AND author LIKE "%'.$_GET['author'].'%"';
	}
	if (isset($_GET['title'])){
		$condi .= ' AND title LIKE "%'.$_GET['title'].'%"';
	}
	if (isset($_GET['year'])){
		$condi .= ' AND year '.$_GET['year'];
	}

	$query = "SELECT urn,title,year,author,restricted FROM workdata".$condi." ORDER BY urn";
	$res = "";
	$tab = "\t";
	$nl = "\n";
	foreach ($sql->query($query) as $row) {
		$res = $res.$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$tab.$row['restricted'].$nl;
	}
	return $res;
}
echo editions();
?>