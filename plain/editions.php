<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns edition level URN list.
# Params: offset, urnfilter, author, title, year

function editions(){
	global $sql;
	if(empty($_GET)){
		if (file_exists("editions.cache")){
			return file_get_contents("editions.cache");
		}
		else{
			$query = "SELECT urn,title,year,author,restricted FROM workdata ORDER BY author,title,urn";
			$res = "";
			$tab = "\t";
			$nl = "\n";
			foreach ($sql->query($query) as $row) {
				$res = $res.$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$tab.$row['restricted'].$nl;
			}
#			$cache = fopen("editions.cache", "w") or die("Unable to open file!");
#			fwrite($cache, $res);
#			fclose($cache);
			return $res;
		}
	}
	else{
		$offset = 0;
		$sortBy = "urn";
		if (isset($_GET['sortBy']) and strlen(trim($_GET['sortBy']))>0){
			$sortBy = $_GET['sortBy'];
		}
		if (isset($_GET['offset'])){
			$offset = $_GET['offset'];
		}
		$condi = " WHERE TRUE";
		if (isset($_GET['urnfilter']) and strlen(trim($_GET['urnfilter']))>0){
			$condi .= ' AND urn LIKE "%'.$_GET['urnfilter'].'%"';
		}
		if (isset($_GET['author']) and strlen(trim($_GET['author']))>0){
			$condi .= ' AND author LIKE "%'.$_GET['author'].'%"';
		}
		if (isset($_GET['title']) and strlen(trim($_GET['title']))>0){
			$condi .= ' AND title LIKE "%'.$_GET['title'].'%"';
		}
		if (isset($_GET['year']) and strlen(trim($_GET['year']))>0){
			$condi .= ' AND year '.$_GET['year'];
		}

		$query = "SELECT urn,title,year,author,restricted FROM workdata".$condi." ORDER BY ".$sortBy;
		$res = "";
		$tab = "\t";
		$nl = "\n";
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$tab.$row['restricted'].$nl;
		}
		return $res;
	}
}
echo trim(editions(),"\n");
?>