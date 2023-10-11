<?php
#header('Content-Type: text/plain');
require('../config.php');
require('../functions.php');

function getCapabilities(){
	global $sql;
	$query = "SELECT * FROM workdata";
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetCapabilities xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request>GetCapabilities</request><reply><TextInventory tiversion="5.0.rc.1">';
	$oldgroup = "";
	$isEmpty=true;
	foreach ($sql->query($query) as $row) {
		$isEmpty=false;
		$urn = $row['urn'];
		$textgroup = explode(".",$urn)[0];
			
		if ($textgroup!==$oldgroup){
			if(strlen($oldgroup)>0){$res=$res."</textgroup>";}
			$res=$res.'<textgroup><groupname>'.explode(":",$textgroup)[3].'</groupname>';
		}
		
		$res = $res.'<edition urn="'.$row['urn'].'">';
		$res = $res.'<title>'.$row['title'].'</title>';
		$res = $res.'<license>'.$row['license'].'</license>';
		$res = $res.'<source>'.$row['source'].'</source>';
		$res = $res.'<publicationDate>'.$row['year'].'</publicationDate>';
		$res = $res.'<language>'.$row['lang'].'</language>';
		$res = $res.'<contentType>'.$row['contentType'].'</contentType>';
		
		$res = $res.'</edition>';
		$oldgroup=$textgroup;
	}
	if (!$isEmpty){$res = $res."</textgroup>";}
	$res = $res."</TextInventory></reply></GetCapabilities>";
	return $res;
}

function GetPassage($urn){
	require('../db_getPassage.php');
	$urnarr = checkurn($urn);
	if ($urnarr !== false){
		if (strlen($urnarr[4]) == 0){$psg = passage($urn,true,isset($_GET["deletexml"]));}
		elseif (strpos ($urnarr[4],"-")){$psg = spanningPassage($urn,isset($_GET["deletexml"]));}
		else {$psg = passage($urn,false,isset($_GET["deletexml"]));};
	}

	$psg = htmlspecialchars($psg);
	if (isset($_GET["highlight"])){
		$psg = str_replace($_GET["highlight"],'<cts_highlight>'.$_GET["highlight"].'</cts_highlight>',$psg);
	}
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetPassage xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetPassage</requestName><requestUrn>'.$urn.'</requestUrn></request><reply>';
	$res = $res.$psg;
	$res = $res."</reply></GetPassage>";


	return $res;
}

function GetPassagePlus($urn){
	global $sql;
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetPassagePlus xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetPassagePlus</requestName><requestUrn>'.$urn.'</requestUrn></request><reply>';
	$res = $res."</reply></GetPassagePlus>";
	return $res;
}

function GetLabel($urn){
	require('../db_getLabel.php');
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetLabel xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetLabel</requestName><requestUrn>'.$urn.'</requestUrn></request><reply>';
	$res .= label($urn);
	$res = $res."</reply></GetLabel>";
	return $res;
}

function GetValidReff($urn,$level){
	require('../db_getValidReff.php');
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetValidReff xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetValidReff</requestName><requestUrn>'.$urn.'</requestUrn></request><reply><reff>';
	$urn = htmlspecialchars($_GET["urn"]);
	$urnarr = checkurn($urn);
	if ($urnarr !== false){
		if (strlen($urnarr[4]) == 0){$sqlreply = explode("\n",validreff($urn,true,$level));}
		else {$sqlreply = explode("\n",validreff($urn,false,$level));};
	}
	foreach ($sqlreply as $row) {
		$res .= "<urn>".$row."</urn>";
	}
	$res = $res."</reff></reply></GetValidReff>";
	return $res;
}

function GetPrevNextUrn($urn){
	global $sql;
	require('../db_getPrevNextUrn.php');
	$urn = htmlspecialchars($_GET["urn"]);
	$urnarr = checkurn($urn);
	if ($urnarr !== false){
		if (strlen($urnarr[4]) == 0){$sqlreply = explode("\n",prevnexturn($urn,true));}
		else {$sqlreply = explode("\n",prevnexturn($urn,false));};
	}
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetPrevNextUrn xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetPrevNextUrn</requestName><requestUrn>'.$urn.'</requestUrn></request><reply><prevnext>';
	if($sqlreply[0] == "NULL"){$res .= "<prev><urn></urn></prev>";}
	else{$res .= "<prev><urn>".$sqlreply[0]."</urn></prev>";}
	if($sqlreply[1] == "NULL"){$res .= "<next><urn></urn></next>";}
	else{$res .= "<next><urn>".$sqlreply[1]."</urn></next>";}
	$res .= "</prevnext></reply></GetPrevNextUrn>";
	return $res;
}

function GetFirstUrn($urn){
	global $sql;
	require('../db_getFirstUrn.php');
	$urn = htmlspecialchars($_GET["urn"]);
	$urnarr = checkurn($urn);
	if ($urnarr !== false){
	if (strlen($urnarr[4]) == 0){$sqlreply = firsturn($urn,true);}
	else {$sqlreply = firsturn($urn,false);};
	}
	$res = '<?xml version="1.0" encoding="UTF-8"?><GetFirstUrn xmlns="http://relaxng.org/ns/structure/1.0" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:ti="http://chs.harvard.edu/xmlns/cts"><request><requestName>GetFirstUrn</requestName><requestUrn>'.$urn.'</requestUrn></request><reply>';
	$res .= "<urn>".$sqlreply."</urn>";
	$res .= "</reply></GetFirstUrn>";
	return $res;
}

Header('Content-type: text/xml');
if (! isset($_GET["request"])){echo "";exit();}
$request = htmlspecialchars($_GET["request"]);
if ($request == "GetCapabilities"){print(getCapabilities());exit();}
if (! isset($_GET["urn"])){echo "";exit();}
$urn = htmlspecialchars($_GET["urn"]);
if ($request == "GetPassage"){print(GetPassage($urn));exit();}
if ($request == "GetPassagePlus"){print(GetPassagePlus($urn));exit();}
if ($request == "GetLabel"){print(GetLabel($urn));exit();}
if ($request == "GetPrevNextUrn"){print(GetPrevNextUrn($urn));exit();}
if ($request == "GetFirstUrn"){print(GetFirstUrn($urn));exit();}
$level = -1;
if (isset($_GET["level"])){$level = $_GET["level"];}
$urn = htmlspecialchars($_GET["urn"]);
if ($request == "GetValidReff"){print(GetValidReff($urn, $level));exit();}

?>