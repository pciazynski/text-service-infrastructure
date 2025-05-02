<?php
Header('Content-type: text/xml');

$msg = "<CTSError xmlns=\"http://relaxng.org/ns/structure/1.0\" xmlns:a=\"http://relaxng.org/ns/compatibility/annotations/1.0\" xmlns:cts=\"http://chs.harvard.edu/xmlns/cts\" xmlns:tei=\"http://www.tei-c.org/ns/1.0\">";
$msg.="<message>Invalid URN reference:".$_GET['urn']."</message><code>3</code></CTSError>";
echo ($msg);
exit();
?>

