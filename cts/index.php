<?php
#header('Content-Type: text/plain');
require('../config.php');
require('../functions.php');

function getShortCapabilities(){
	global $sql;
	$offset = 0;
		if (isset($_GET['offset'])){
		$offset = $_GET['offset'];
	}
	$query = 'SELECT * FROM workdata ORDER BY urn LIMIT 10000 OFFSET '.$offset;
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetCapabilities xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetCapabilities</requestName><param>smallinventory</param></request><reply>';
	$urnrow = 'urn';
	$edop = '<urn>';
	$edcl = '</urn>';
	foreach ($sql->query($query) as $row) {
		$res = $res.$edop.$row[$urnrow].$edcl;
	}
	$res = $res.'</reply></GetCapabilities>';
	return $res;
}


function getCapabilities(){
	global $sql;
	$offset = 0;
		if (isset($_GET['offset'])){
		$offset = $_GET['offset'];
	}
	$query = 'SELECT * FROM workdata ORDER BY urn LIMIT 10000 OFFSET '.$offset;
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetCapabilities xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetCapabilities</requestName></request><reply><TextInventory tiversion="5.0.rc.1">';
	$oldgroup = '';
	$isEmpty=true;
	$serverurl = ' retrieved via Canonical Text Service '.(empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]';
	$serverurl = str_replace('?request=GetCapabilities','',$serverurl);
	foreach ($sql->query($query) as $row) {
		$isEmpty=false;
		$urn = $row['urn'];
		$textgroup = explode(".",$urn)[0];
			
		if ($textgroup!==$oldgroup){
			if(strlen($oldgroup)>0){$res=$res.'</textgroup>';}
			$res=$res.'<textgroup><groupname>'.explode(':',$textgroup)[3].'</groupname>';
		}
		
		$res = $res.'<edition urn="'.$row['urn'].'">';
		$res = $res.'<title>'.htmlspecialchars($row['title'], ENT_XML1, 'UTF-8').'</title>';
		$res = $res.'<license>'.$row['license'].'</license>';
		$res = $res.'<source>'.htmlspecialchars($row['source'], ENT_XML1, 'UTF-8').$serverurl.'</source>';
		$res = $res.'<publicationDate>'.$row['year'].'</publicationDate>';
		$res = $res.'</edition>';
		$oldgroup=$textgroup;
	}
	if (!$isEmpty){$res = $res.'</textgroup>';}
	$res = $res.'</TextInventory></reply></GetCapabilities>';
	return $res;
}

function GetPassage($urn){
	require('../db_getPassage_restr.php');
	global $dbtablename;
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetPassage xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetPassage</requestName><requestUrn>'.$urn.'</requestUrn></request><reply>';
	$urnarr = explode(':',$urn);
	$res = $res.'<urn>'.$urn.'</urn><passage>';
	if($dbtablename == 'urndata'){
		if (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn,isset($_GET['deletexml']),$nl);}
		else {$psg =  passage($urn,isset($_GET['deletexml']),$nl);};
	}
	else{
		if(restrictedAccess()){
			if (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn,isset($_GET['deletexml']),$nl);}
			else {$psg =  passage($urn,isset($_GET['deletexml']),$nl);};
		}
		else{
			require('../errormsg/xml_access.php');
		}
	}

	$psg = htmlspecialchars($psg);
	if (isset($_GET['highlight'])){
		$hl = $_GET['highlight'];
		$psg = str_replace($hl,'<cts_highlight>'.$hl.'</cts_highlight>',$psg);
		$hl = ucwords($_GET['highlight']);
		if($hl != $_GET['highlight']){
			$psg = str_replace($hl,'<cts_highlight>'.$hl.'</cts_highlight>',$psg);
		}
	}
	$res = $res.$psg;
	$res = $res.'</passage></reply></GetPassage>';


	return $res;
}

function GetPassagePlus($urn){
	require('../db_getPassage_restr.php');
	global $dbtablename;
	if($dbtablename == 'urndata'){
		if (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn,isset($_GET['deletexml']),$nl);}
		else {$psg =  passage($urn,isset($_GET['deletexml']),$nl);};
	}
	else{
		if(restrictedAccess()){
			if (strpos ($urnarr[4],'-')){$psg =  spanningPassage($urn,isset($_GET['deletexml']),$nl);}
			else {$psg =  passage($urn,isset($_GET['deletexml']),$nl);};
		}
		else{
			require('../errormsg/xml_access.php');
		}
	}
	$psg = htmlspecialchars($psg);
	require('../db_getLabel.php');
	require('../db_getValidReff.php');
	require('../db_getPrevNextUrn.php');
	global $sql;
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetPassagePlus xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetPassagePlus</requestName><requestUrn>'.$urn.'</requestUrn></request><reply>';
	$res .= '<GetLabel>'.label($urn).'</GetLabel>';
	$res .= '<GetValidReff>';
	$urnarr = explode(':',$urn);
	$urnop = '<urn>';
	$urncl = '</urn>';
	if (strlen($urnarr[4]) == 0){$sqlreply = explode("\n",validreff($urn,true,1000));}
	else {$sqlreply = explode("\n",validreff($urn,false,1000));};
	foreach ($sqlreply as $row) {
		$res .= $urnop.$row.$urncl;
	}
	$res .='</GetValidReff>';
	$firsturn = $sqlreply[0];
	$res .= '<GetFirstUrn><urn>'.$firsturn.'</urn></GetFirstUrn>';
	if (strlen($urnarr[4]) == 0){$sqlreply = explode("\n",prevnexturn($urn,true));}
	else {$sqlreply = explode("\n",prevnexturn($urn,false));};
	if (count($sqlreply)>1){
		$res .= '<GetPrevNextUrn>';
		if($sqlreply[0] == 'NULL'){$res .= '<prev><urn></urn></prev>';}
		else{$res .= '<prev><urn>'.$sqlreply[0].'</urn></prev>';}
		if($sqlreply[1] == 'NULL'){$res .= '<next><urn></urn></next>';}
		else{$res .= '<next><urn>'.$sqlreply[1].'</urn></next>';}
		$res .= '</GetPrevNextUrn>';
	}
	
	$res .= '<GetPassage>'.$psg.'</GetPassage>';
	$res .= '</reply></GetPassagePlus>';
	
	return $res;
}

function GetLabel($urn){
	require('../db_getLabel.php');
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetLabel xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetLabel</requestName><requestUrn>'.$urn.'</requestUrn></request><reply>';
	$res .= label($urn);
	$res = $res.'</reply></GetLabel>';
	return $res;
}

function GetValidReff($urn,$level){
	require('../db_getValidReff.php');
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetValidReff xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetValidReff</requestName><requestUrn>'.$urn.'</requestUrn></request><reply><reff>';
	$urnarr = explode(':',$urn);
	if (strlen($urnarr[4]) == 0){$sqlreply = explode("\n",validreff($urn,true,$level));}
	else {$sqlreply = explode("\n",validreff($urn,false,$level));};
	$urnop = '<urn>';
	$urncl = '</urn>';
	foreach ($sqlreply as $row) {
		$res .= $urnop.$row.$urncl;
	}
	$res = $res.'</reff></reply></GetValidReff>';
	return $res;
}

function GetPrevNextUrn($urn){
	global $sql;
	require('../db_getPrevNextUrn.php');
	$urnarr = explode(':',$urn);
	if (strlen($urnarr[4]) == 0){$sqlreply = explode("\n",prevnexturn($urn,true));}
	else {$sqlreply = explode("\n",prevnexturn($urn,false));};
	if (count($sqlreply)>1){
		$res = '<?xml version="1.0" encoding="UTF-8"?><GetPrevNextUrn xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetPrevNextUrn</requestName><requestUrn>'.$urn.'</requestUrn></request><reply><prevnext>';
		if($sqlreply[0] == 'NULL'){$res .= '<prev><urn></urn></prev';}
		else{$res .= '<prev><urn>'.$sqlreply[0].'</urn></prev>';}
		if($sqlreply[1] == 'NULL'){$res .= '<next><urn></urn></next>';}
		else{$res .= '<next><urn>'.$sqlreply[1].'</urn></next>';}
		$res .= '</prevnext></reply></GetPrevNextUrn>';
		return $res;
	}
}

function GetFirstUrn($urn){
	global $sql;
	require('../db_getFirstUrn.php');
	$urnarr = explode(':',$urn);
	if (strlen($urnarr[4]) == 0){$sqlreply = firsturn($urn,true);}
	else {$sqlreply = firsturn($urn,false);};
	if (strlen($sqlreply)>1){
		$res = '<?xml version="1.0" encoding="UTF-8"?><GetFirstUrn xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetFirstUrn</requestName><requestUrn>'.$urn.'</requestUrn></request><reply>';
		$res .= '<urn>'.$sqlreply.'</urn>';
		$res .= '</reply></GetFirstUrn>';
		return $res;
	}
}

Header('Content-type: text/xml');

$request = htmlspecialchars($_GET['request']);
switch($request){
	case 'GetCapabilities':
	(isset($_GET['smallinventory'])) ? print(getShortCapabilities()): print(getCapabilities());break;
	case 'GetPassage' : $urn = checkurn($_GET['urn'],'xml');print(GetPassage($urn));break;
	case 'GetPassagePlus' : $urn = checkurn($_GET['urn'],'xml');print(GetPassagePlus($urn));break;
	case 'GetLabel' : $urn = checkurn($_GET['urn'],'xml');print(GetLabel($urn));break;
	case 'GetFirstUrn' : $urn = checkurn($_GET['urn'],'xml');print(GetFirstUrn($urn));break;
	case 'GetPrevNextUrn' : $urn = checkurn($_GET['urn'],'xml');print(GetPrevNextUrn($urn));break;
	case 'GetValidReff' : 
	$urn = checkurn($_GET['urn'],'xml');
	(isset($_GET['level'])) ? print(GetValidReff($urn, $_GET['level'])):print(GetValidReff($urn, -1));break;
	default:require('../errormsg/xmlinvalidrequest.php');
}


?>