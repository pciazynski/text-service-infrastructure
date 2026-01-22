<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns edition level URN list.
# Params: offset, urnfilter, author, title, year

function editions(){
	global $sql;
	$res = "";
	$tab = "\t";
	$nl = "\n";
	if(empty($_GET)){
		if (file_exists("editions.cache")){
			return file_get_contents("editions.cache");
		}
		else{
			$stmt = $sql->prepare('SELECT urn,title,year,author,restricted,lang FROM workdata ORDER BY author,title,urn');
			$stmt->execute();
		}
	}
	else{
		if(isset($_GET['year'])){
			$yearmin = $_GET['year'];
			$yearmax  = $_GET['year'];
		}
		else{
			$yearmin = 1;
			$yearmax = 30000;
		}
		(isset($_GET['yearmin'])) ? $yearmin = $_GET['yearmin'] : NULL;
		(isset($_GET['yearmax'])) ? $yearmax = $_GET['yearmax'] : NULL;
		(isset($_GET['urnfilter'])) ? $urnfilter = $_GET['urnfilter'] : $urnfilter = '';
		(isset($_GET['urnfilter'])) ? $urnfilter = $_GET['urnfilter'] : $urnfilter = '';
		(isset($_GET['author'])) ? $author = $_GET['author'] : $author = '';
		(isset($_GET['title'])) ? $title = $_GET['title'] : $title = '';
		(isset($_GET['sortBy'])) ? $sortBy = $_GET['sortBy'] : $sortBy = 'urn';
		
		
		$stmt = $sql->prepare('SELECT urn,title,year,author,restricted,lang FROM workdata WHERE urn LIKE ? AND author LIKE ? and title LIKE ? AND year BETWEEN ? AND ? ORDER BY ?');
		$stmt->execute(["%".$urnfilter."%","%".$author."%","%".$title."%",$yearmin, $yearmax,$sortBy]);
	}
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$res = $res.$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$tab.$row['restricted'].$tab.$row['lang'].$nl;
	}
	return $res;
}
echo trim(editions(),"\n");
?>