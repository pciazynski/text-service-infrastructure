<?php
Header('Content-type: text/xml');
echo ('<CTSError><message>Invalid request name</message><request>'.$_GET['request'].'</request><code>6</code></CTSError>');
exit();
?>

