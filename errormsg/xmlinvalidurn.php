<?php
Header('Content-type: text/xml');
echo('<CTSError><message>Syntactically valid URN refers in invalid value</message><urn>'.$_GET['urn'].'</urn><code>3</code></CTSError>');
exit();
?>

