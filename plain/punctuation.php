<?php
header('Content-Type: text/plain');
require('../config.php');

# Returns the characters that are considered as special chars

print(implode("\n",array_unique(array_merge($replacearr, $punctarr))));
?>