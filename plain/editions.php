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
		$query = 'SELECT urn,title,year,author,restricted,lang FROM workdata WHERE urn LIKE ? ';
	
		(isset($_GET['yearmin'])) ? $yearmin = $_GET['yearmin'] : $yearmin = 1;;
		(isset($_GET['yearmax'])) ? $yearmax = $_GET['yearmax'] : $yearmax = 30000;;
		(isset($_GET['urnfilter'])) ? $params=array($_GET['urnfilter']): $params=array('%');

		if (isset($_GET['restricted'])){
			intval($_GET['restricted']) == 1 ? $query .= ' AND restricted = 1' : $query = ' AND restricted = 0';
		}
		if(isset($_GET['lang'])){
			$query.= ' AND lang LIKE ?';
			array_push($params,$_GET['lang']);
		}
		if(isset($_GET['author'])){
			$query.= ' AND author LIKE ?';
			array_push($params,'%'.$_GET['author'].'%');
		}
		if(isset($_GET['title'])){
			$query.= ' AND title LIKE ?';
			array_push($params,'%'.$_GET['title'].'%');
		}

		if(isset($_GET['sortBy'])){
			$tmp = $_GET['sortBy'];
			switch($tmp){
				case 'author':
					$query .= ' ORDER BY author';
					break;
				case 'year':
					$query .= ' ORDER BY year';
					break;
				case 'author,year':
					$query .= ' ORDER BY author,year';
					break;
			}
		}
		
		$stmt = $sql->prepare($query);
		$stmt->execute($params);
	}
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$res = $res.$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$tab.$row['restricted'].$tab.$row['lang'].$nl;
	}
	return $res;
}
echo trim(editions(),"\n");
?>