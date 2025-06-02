<?php
Header('Content-type: text/xml');

$msg = "<CTSError>";
$msg.="<message>Invalid URN reference:".$_GET['urn']."</message><code>3</code></CTSError>";
echo ($msg);
exit();
?>

