<?php
header('Content-Type: text/plain');

# Returns Entrypoint.
# Params: 

function editions(){
	$res = '{
  "@context": "https://dtsapi.org/context/v1.0.json",
  "dtsVersion": "1.0",
  "@id": "/dts/",
  "@type": "EntryPoint",
  "collection": "/dts/collection/{?id,page,nav}",
  "navigation" : "/dts/navigation/{?resource,ref,start,end,down,tree,page}",
  "document": "/dts/document/{?resource,ref,start,end,tree,mediaType}"
}';
	return $res;
}
echo trim(editions(),"\n");
?>