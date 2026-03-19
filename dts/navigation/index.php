<?php
header('Content-Type: text/text');
require('../../config.php');
$_GET = array_filter($_GET);


# Returns Collection Endpoint.
# Params: 

function navigation($urn){
	global $sql;
	global $binary;
	
	$res = '';
	$tab = "\t";
	$nl = "\n";
	$resrow1='urn';
	$resrow2='type';
	$resrow3='len';
	$member = '"member": [
	';
	$stmt = $sql->prepare('SELECT urn,type FROM urndata WHERE urn LIKE '.$binary.' ? AND NOT urn = ? ORDER BY urnid');
	(str_ends_with($urn,':')) ? $stmt->execute([$urn.'%',$urn]):$stmt->execute([$urn.'.%',$urn]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
		$member = $member.$row[$resrow1].$tab.$row[$resrow2].$tab.$row[$resrow3].$nl;
	}
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
    "WIPcitationTrees": [
      {
        "@type": "CitationTree",
        "citeStructure": [
          {
            "@type": "CiteStructure",
            "citeType": "Chapter",
            "citeStructure": [
              {
                "@type": "CiteStructure",
                "citeType": "Journal Entry",
                "citeStructure": [
                  {
                    "@type": "CiteStructure",
                    "citeType": "Paragraph"
                  }
                ]
              }
            ]
          }
        ]
      }
    ]
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