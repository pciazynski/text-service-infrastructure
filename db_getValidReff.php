<?php
function validreff($urn,$level,$validreffcount=0){
	global $sql;
	global $binary;
	global $dbtablename;
	
	$res = '';
	if ($validreffcount>0){
		$stmt = $sql->prepare('SELECT urn FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND NOT urn = ? LIMIT ?');
		(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%',$urn.'%',intval($validreffcount)]):$stmt->execute([$urn.'.%',$urn.'.%',intval($validreffcount)]);
	}else{
		$stmt = $sql->prepare('SELECT urn FROM '.$dbtablename.' WHERE urn LIKE '.$binary.' ? AND NOT urn = ?');
		(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%',$urn]):$stmt->execute([$urn.'.%',$urn]);
	}
	if ($level > -1){
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
			$candiurn = $row['urn'];
			$candilvl = count(explode('.',explode ( ':', $candiurn)[4]));
			if($candilvl <= $level){
				$res = $res.$candiurn."\n";
			}
		}
	}
	else{
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
			$res = $res.$row['urn']."\n";
		}
	}
	return trim($res);
}
?>