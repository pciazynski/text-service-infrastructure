<?php

function autocomplete($urn){
	return $urn;
}


function checkurn($urn){
	$urn = autocomplete($urn);
	$urnarr = explode(":",$urn);
	if(strpos($urn,"urn:cts") !== 0){return false;}
	return $urnarr;
}
?>