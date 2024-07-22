<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns edition level URN list.
# Params: none

function editions(){
	global $sql;
	if (file_exists("editions.cache")){
		return file_get_contents("editions.cache");
	}
	else
	{
		$offset = 0;
		if (isset($_GET['offset'])){
			$offset = $_GET['offset'];
		}
		$query = "SELECT urn,title,year,author FROM workdata ORDER BY urn";
		$res = "";
		$tab = "\t";
		$nl = "\n";
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$nl;
		}
		$cache = fopen("editions.cache", "w") or die("Unable to open file!");
		fwrite($cache, $res);
		fclose($cache);
		return $res;
	}
}
echo editions();
?>