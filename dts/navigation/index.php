<?php
header('Content-Type: text/text');
require('../../config.php');
$_GET = array_filter($_GET);


# Returns Collection Endpoint.
# Params: 

function buildcitationTree($citarch){
	$cittree = '';
	$tmparr = explode(".",$citarch);
	$closingbrackets = "}]";
	for($j=0;$j<count($tmparr);$j++){
		$cittree .= '
"@type": "CiteStructure",
"citeType": "'.$tmparr[$j].'"';
		if($j<count($tmparr)-1){
			$cittree .= ',
"citeStructure": [{';
			$closingbrackets.='}]';
		}
	}
	return $cittree.$closingbrackets;
}


function navigation($urn){
	global $sql;
	global $binary;
	
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$resrow1='urn';
	$resrow2='type';
	$member = '"member": [
	';
	$stmt = $sql->prepare('SELECT urn,type FROM urndata WHERE urn LIKE '.$binary.' ? AND NOT urn = ? ORDER BY urnid');
	$urn = str_replace("_","\_",$urn);
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%',$urn]):$stmt->execute([$urn.'.%',$urn]);
	$psgTrees = [];
	
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		#print(explode(":",$row[$resrow1])[4]);
		$psgpart = explode(":",$row[$resrow1])[4];
		$psgpartarr = explode(".",$psgpart);
		$psgpartcount = count($psgpartarr);
		$urn = $row[$resrow1];
		if($psgpartcount==1){$psgTrees[$psgpart] = $row[$resrow2];}
		else{
			$parenttype = "";
			$parentpsg = "";
			for($i = 0; $i<count($psgpartarr)-1;$i++){
				$parentpsg = $psgpartarr[$i].'.';
			}
			$parenttype .= $psgTrees[rtrim($parentpsg,".")];
			$psgTrees[$psgpart] = $parenttype.'.'.$row[$resrow2];
		}
		if($psgpartcount == 1){
			$parent = 'null';
		}else{
			$parent = substr($urn, 0, strrpos( $urn, '.') );
		}
		$member .= '
{
"identifier":"'.$urn.'"
"@type": "CitableUnit",
 "level": '.$psgpartcount.',
"parent": '.$parent.',
"citeType": "'.$row[$resrow2].'
},';
	}
	$member = rtrim($member,",");
	$oldcount = -1;
	$citeTrees = [];
	
	foreach (array_reverse($psgTrees) as $ct => $y) {
		$psgpartarr = explode(".",$ct);
		$psgpartcount = count($psgpartarr);
		if($psgpartcount>=$oldcount){$citeTrees[$y] = 1;}
		$oldcount = $psgpartcount;
	}
	
	$citationTrees = '"citationTrees": [{
"@type": "CitationTree",
"citeStructure": [{';

	foreach ($citeTrees as $ct => $y) {
		$citationTrees .= buildcitationTree($ct);
	}
	$citationTrees.='
}]';
	$member.=']
	';

	$res = '{
  "@context": "https://dtsapi.org/context/v1.0.json",
  "dtsVersion": "1.0",
  "@type": "Navigation",
  "@id": "'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'",
  "resource": {
    "@id": "'.$urn.'",
    "@type": "Resource",
    "document": "'.$_SERVER['HTTP_HOST'].str_replace('/navigation/','/document/',$_SERVER['REQUEST_URI']).'",
    "collection": "'.$_SERVER['HTTP_HOST'].str_replace('/navigation/','/collection/',$_SERVER['REQUEST_URI']).'",
    "navigation": "'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'",
    '.$citationTrees.'
  },
'.$member.'
}';

	return trim($res);
}


if(isset($_GET['id'])){
	echo trim(navigation($_GET['id']),"\n");
}else{
	require('../../errormsg/dts_missingparameters.php');
}
?>