<?php
Header('Content-type: text/xml');

$msg = "<CTSError>";
$msg.="<message>Invalid URN syntax:".$_GET['urn']."</message><code>2</code></CTSError>";
echo ($msg);
exit();
?>

