<?php
Header('Content-type: text/xml');
echo('<CTSError><message>Invalid URN syntax</message><urn>'.$_GET['urn'].'</urn><code>2</code></CTSError>');
exit();
?>

