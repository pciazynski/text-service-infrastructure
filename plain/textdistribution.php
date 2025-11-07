<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

if (isset($_GET["urn"]) && isset($_GET["character"])){
	$urn = checkurn($_GET['urn'],'');
	$snippet =  trim(htmlspecialchars($_GET['character']));
	$query = "SELECT urn, text, text FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' AND text IS NOT NULL ORDER BY urnid";
	$res = "";
	$tab = "\t";
	$nl = "\n";
	foreach ($sql->query($query) as $row){
		$psg = deletexml($row['text']);
		if(isset($_GET['lowercase'])){
			$snippet = strtolower($snippet);
			($multibyte) ? $psg = mb_strtolower($psg,'UTF-8') : $psg = strtolower($psg);
		}
		$occ = count(explode($snippet,$psg))-1;
		$res = $res.$row['urn'].$tab.$occ.$nl;
	}
	echo ($res);
}

else if (isset($_GET["urn"]) && isset($_GET["snippet"])){
	$urn = checkurn($_GET['urn'],'');
	$snippet =  trim(htmlspecialchars($_GET['snippet']));
	$query = "SELECT urn, text, text FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' AND text IS NOT NULL ORDER BY urnid";
	$res = "";
	$tab = "\t";
	$nl = "\n";
	$sp = " ";
	foreach ($sql->query($query) as $row){
		$psg = $sp.str_replace($replacearr, $sp,deletexml($row['text'])).$sp;
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