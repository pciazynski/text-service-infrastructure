<?php
Header('Content-type: text/xml');
require('../../config.php');
require('functions.php');
require('../../db_getPassage_restr.php');
$_GET = array_filter($_GET);

# Returns Document Endpoint.
# Params: 

function document($urn){
	global $dbtablename;
	$urn = checkurn($urn,'dts_');
	$urnarr = explode(':',$urn);
	$deletexml = isset($_['deletexml']);
	$nl = isset($_['newlines']);
	if($dbtablename == 'urndata'){
		if (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn,$deletexml,$nl);}
		else {$psg = passage($urn,$deletexml,$nl);};
	}
	else{
		if(restrictedAccess()){
			if (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn,$deletexml,$nl);}
			else {$psg =  passage($urn,$deletexml,$nl);};
		}
		else{
			require('../../errormsg/access.php');
		}
	}
	$res= '<?xml version="1.0" encoding="UTF-8"?>
	<TEI xmlns="http://www.tei-c.org/ns/1.0">
		<dts:wrapper xmlns:dts="https://w3id.org/api/dts#">
			'.$psg.'
		</dts:wrapper>
	</TEI>';
	return $res;
}


if(isset($_GET['id'])){
	echo trim(document($_GET['id']),"\n");
}else{
	require('../../errormsg/dts_missingparameters.php');

}
?>