<?php
header('Content-Type: text/plain');
require('../functions.php');
require('../config.php');
require('../db_getPassage_restr.php');

$deletexml = isset($_GET['deletexml']);
$nl = isset($_GET['nl']);
$urn = checkurn($_GET['urn'],'');
$urnarr = explode(':',$urn);
if($dbtablename == 'urndata'){
	if (strpos ($urnarr[4],'-')){echo spanningPassage($urn,$deletexml,$nl);}
	else {echo passage($urn,$deletexml,$nl);};
}
else{
	if(restrictedAccess()){
		if (strpos ($urnarr[4],'-')){echo spanningPassage($urn,$deletexml,$nl);}
		else {echo passage($urn,$deletexml,$nl);};
	}
	else{
		require('../errormsg/access.php');
	}
}

?>