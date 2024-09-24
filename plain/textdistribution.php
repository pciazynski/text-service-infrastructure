<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');

if (isset($_GET["urn"]) && isset($_GET["snippet"])){
	$urn = $_GET["urn"];
	$urnarr = checkurn($urn);
	if ($urnarr !== false){
		$snippet = htmlspecialchars($_GET['snippet']);
		$query = "SELECT urn, text, text FROM urndata WHERE urn LIKE ".$binary." '".$urn."%' AND text IS NOT NULL";
		$res = "";
		$tab = "\t";
		$nl = "\n";
		foreach ($sql->query($query) as $row){
			$psg = " ".str_replace($replacearr, " ",deletexml(strtolower($row['text'])))." ";
			if ($multibyte){
				$psg = mb_strtolower($psg,'UTF-8');
			}
			else{
				$psg = strtolower($psg);
			}
			$occ = count(explode(" ".$snippet." ",$psg))-1;

			$res = $res.$row['urn'].$tab.$occ.$nl;
		}
		echo ($res);
	}
}
?>