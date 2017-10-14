<?php
$response=file_get_contents ('/tmp/CommonAPI');
$datac = json_decode($response);
$currentheight=$datac->height;
?>