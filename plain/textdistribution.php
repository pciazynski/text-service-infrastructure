<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

$deletexml = isset($_GET['deletexml']);
if (isset($_GET['urn']) && isset($_GET['character'])){
	$urn = checkurn($_GET['urn'],'');
	$snippet =  trim(htmlspecialchars($_GET['character']));
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$stmt = $sql->prepare('SELECT urn, text FROM urndata WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
	(str_ends_with('urn',':')) ? $stmt->execute([$urn.'%']):$stmt->execute([$urn.'.%']);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		($deletexml) ? $psg = deletexml($row['text']):$psg = $row['text'];
		if(isset($_GET['lowercase'])){
			$snippet = strtolower($snippet);
			($multibyte) ? $psg = mb_strtolower($psg,'UTF-8') : $psg = strtolower($psg);
		}
		$occ = count(explode($snippet,$psg))-1;
		$res = $res.$row['urn'].$tab.$occ.$nl;
	}
	echo ($res);
}

else if (isset($_GET['urn']) && isset($_GET['snippet'])){
	$urn = checkurn($_GET['urn'],'');
	$snippet =  trim(htmlspecialchars($_GET['snippet']));
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$sp = ' ';
	$stmt = $sql->prepare('SELECT urn, text FROM urndata WHERE urn LIKE '.$binary.' ? ORDER BY urnid');
	(str_ends_with('urn',':')) ? $stmt->execute([$urn.'%']):$stmt->execute([$urn.'.%']);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		($deletexml) ? $psg = deletexml($row['text']):$psg = $row['text'];
		$psg = $sp.str_replace($replacearr, $sp,$psg).$sp;
		if(isset($_GET['lowercase'])){
			$snippet = strtolower($snippet);
			($multibyte) ? $psg = mb_strtolower($psg,'UTF-8') : $psg = strtolower($psg);
		}
		$occ = count(explode($sp.$snippet.$sp,$psg))-1;
		$res = $res.$row['urn'].$tab.$occ.$nl;
	}
	echo ($res);
}
?>