<?php
Header('Content-type: text/xml');

$msg = "<CTSError>";
$msg.="<message>Restricted Access</message><urn>".$_GET['urn']."</urn><code>7</code></CTSError>";
echo ($msg);
exit();
?>

