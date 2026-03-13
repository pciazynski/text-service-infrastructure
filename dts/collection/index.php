<?php
header('Content-Type: text/plain');
require('../../config.php');
$_GET = array_filter($_GET);

# Returns Collection Endpoint.
# Params: 

function editions(){
	global $sql;
	$member = '
	"member": [';
	$lang = '';
	$tab = "\t";
	$nl = "\n";
	$count = 0;
	$stmt = $sql->prepare('SELECT urn,title,year,author,restricted,lang FROM workdata ORDER BY author,title,urn');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$lang .= $row['lang'].',';
		$member .= '{
             "@id" : "'.$row['urn'].'",
             "title" : "'.$row['title'].'",
             "description": "'.$row['author'].': '.$row['title'].' '.$row['year'].'",
             "@type" : "Collection",
             "collection": "/dts/collection/?id='.$row['urn'].'{&page,nav}",
             "totalParents": 1,
             "totalChildren": WIP
        },';
		$row['urn'].$tab.$row['title'].$tab.$row['year'].$tab.$row['author'].$tab.$row['restricted'].$tab.$row['lang'].$nl;
		$count+=1;
	}
	$host= 'Canonical Text Service: '.$_SERVER['HTTP_HOST'];
	$res = '{
    "@context": "https://dtsapi.org/context/v1.0.json",
    "@id": "general",
    "@type": "Collection",
    "collection": "/dts/collection/{?id,page,nav}",
    "dtsVersion": "1.0",
    "totalParents": 0,
    "totalChildren": '.$count.',
    "title": "'.$host.'",
    "dublinCore": {
        "publisher": ["'.$host.'", "'.$host.'"],
        "title": [
            {"lang": "'.$lang.'", "value": "'.$host.'"}
        ]
    },'.$member.'
    ]
}';
	return $res;
}
echo trim(editions(),"\n");
?>