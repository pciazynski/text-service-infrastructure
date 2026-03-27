<?php
header('Content-Type: text/text');
require('../../config.php');
$_GET = array_filter($_GET);

# Returns Collection Endpoint.
# Params: 

function generalcollection(){
	global $sql;
	$member = '
	"member": [';
	$lang = '';
	$tab = "\t";
	$nl = "\n";
	$count = 0;
	$stmt = $sql->prepare('SELECT DISTINCT lang FROM workdata');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$lang .= $row['lang'].',';
	}
	$stmt = $sql->prepare('SELECT urn,title,year,author,restricted,lang FROM workdata ORDER BY author,title,urn');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$member .= '{
             "@id" : "'.$row['urn'].'",
             "title" : "'.$row['title'].'",
             "description": "'.$row['author'].': '.$row['title'].' '.$row['year'].'",
             "@type" : "Collection",
             "collection": "/dts/collection/?id='.$row['urn'].'{&page,nav}",
             "totalParents": 1,
             "totalChildren": WIP
        },';
		$count+=1;
	}
	$lang = substr($lang,0,-1);
	$host= 'Distributed Text Service: '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
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


# Returns Collection Endpoint.
# Params: 

function childcollection($urn){
	global $sql;
	$member = '
	"member": [';
	$lang = '';
	$tab = "\t";
	$nl = "\n";
	$count = 0;
	$stmt = $sql->prepare('SELECT DISTINCT lang FROM workdata');
	$stmt->execute();
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$lang .= $row['lang'].',';
	}
	$stmt = $sql->prepare('SELECT urn,title,year,author,restricted,lang FROM workdata WHERE urn LIKE ?');
	$urn = str_replace("_","\_",$urn);
	$stmt->execute([$urn.".%"]);
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$member .= '{
             "@id" : "'.$row['urn'].'",
             "title" : "'.$row['title'].'",
             "description": "'.$row['author'].': '.$row['title'].' '.$row['year'].'",
             "@type" : "Collection",
             "collection": "/dts/collection/?id='.$row['urn'].'{&page,nav}",
             "totalParents": 1,
             "totalChildren": WIP
        },';
		$count+=1;
	}
	$lang = substr($lang,0,-1);
	$host= 'Distributed Text Service: '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
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


if(isset($_GET['id'])){
	echo trim(childcollection($_GET['id']),"\n");
}else{
	echo trim(generalcollection(),"\n");
}
?>