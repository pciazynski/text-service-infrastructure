<?php
function validreff($urn,$isWorkurn,$level,$validreffcount=0){
	global $sql;
	if($isWorkurn){$query = "SELECT urn FROM urndata WHERE urn LIKE BINARY '".$urn."%' AND NOT urn = '".$urn."' ORDER BY urnid";}
	else {$query = "SELECT urn FROM urndata WHERE urn LIKE BINARY '".$urn.".%' ORDER BY urnid";}
	if ($validreffcount>0){$query = $query." LIMIT ".$validreffcount;}
	$res = "";
	if ($level >- 1){
		foreach ($sql->query($query) as $row) {
			$candiurn = $row['urn'];
			$candilvl = count(explode(".",explode ( ":", $candiurn)[4]));
			if($candilvl <= $level){
				$res = $res.$candiurn."\n";
			}
		}
	}
	else{
		foreach ($sql->query($query) as $row) {
			$res = $res.$row['urn']."\n";
		}
	}
	return trim($res);
}
?>