<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns edition level URN list.
# Params: offset, urnfilter, author, title, year

function editions(){
	global $sql;
	$res = '';
	$tab = "\t";
	$nl = "\n";
	if(empty($_GET)){
		if (file_exists('editions.cache')){
			return file_get_contents('editions.cache');
		}
		else{
			$stmt = $sql->prepare('SELECT urn,title,year,author,restricted,lang FROM workdata ORDER BY author,title,urn');
			$stmt->execute();
		}
	}
	else{
		if(isset($_GET['year']) and strlen($_GET['year'])>1){
			$yearmin = $_GET['year'];
			$yearmax  = $_GET['year'];
		}
		else{
			$yearmin = 1;
			$yearmax = 30000;
		}
		if (isset($_GET['restricted'])){
			intval($_GET['restricted']) == 1 ? $restricted = ' AND restricted = 1' : $restricted = ' AND restricted = 0';
		}
		else{
			$restricted = '';
		}
		(isset($_GET['yearmin'])) ? $yearmin = $_GET['yearmin'] : $yearmin = 1;;
		(isset($_GET['yearmax'])) ? $yearmax = $_GET['yearmax'] : $yearmax = 30000;;
		(isset($_GET['urnfilter'])) ? $urnfilter = '%'.$_GET['urnfilter'].'%' : $urnfilter = '%';
		(isset($_GET['lang'])) ? $lang = $_GET['urnfilter'] : $lang = '%';
		(isset($_GET['author'])) ? $author = '%'.$_GET['author'].'%' : $author = '%';
		(isset($_GET['title'])) ? $title = '%'.$_GET['title'].'%' : $title = '%';

		$sortBy = 'urn';
		if(isset($_GET['sortBy'])){
			$tmp = $_GET['sortBy'];
			switch($tmp){
				case 'author':
					$sortBy = 'author';
					break;
				case 'year':
					$sortBy = 'year';
					break;
				case 'author,year':
					$sortBy = 'author,year';
					break;
			}
		}
		
		
		$stmt = $sql->prepare('SELECT urn,title,year,author,restricted,lang FROM workdata WHERE urn LIKE ? '.$restricted.' AND author LIKE ? and title LIKE ? and lang LIKE ? ORDER BY '.$sortBy);
		$stmt->execute([$urnfilter, $author, $title, $lang]);
	}
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$res = $res.$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$tab.$row['restricted'].$tab.$row['lang'].$nl;
	}
	return $res;
}
echo trim(editions(),"\n");
?>