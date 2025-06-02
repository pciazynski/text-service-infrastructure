<?php
Header('Content-type: text/xml');

$msg = "<CTSError>";
$msg.="<message>Invalid request name:".$_GET['request']."</message><code>6</code></CTSError>";
echo ($msg);
exit();

#case 1:msg="Request missing one or more required parameters"+urn;break;
#case 2:msg="Invalid URN syntax:"+urn;break;
#case 3:msg="Invalid URN reference:"+urn;break;
#case 4:msg="Invalid value for level parameter in GetValidReff request";break;
#case 5:msg="Invalid value for context parameter in GetPassage or GetPassagePlus request";break;
#case 6:msg="Invalid request name:"+reqName;break;
?>

