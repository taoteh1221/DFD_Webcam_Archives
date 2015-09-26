<?php


$version = '0.9.5';  // 2015/Sept/26th

$title = 'Webcam - Automated Archiving (last 4 days)';

/*
Set your amplitude minimum here.
/cron/stop-seccam.sh has a base setting on line 23 of greater than 0.024,
this setting here lets you quickly tweak chart graphs without reprocessing video.
*/
$amplitude_minimum = 0.040;



// Do not edit below this line unless you know what you are doing
require_once 'init.php';

?>