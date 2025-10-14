<?php
Header('Content-type: text/xml');
echo('<CTSError><message>Restricted Access</message><urn>'.$_GET['urn'].'</urn><code>7</code></CTSError>');
exit();
?>

