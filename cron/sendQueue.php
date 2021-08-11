<?php
set_time_limit(0);
require __DIR__ . "/../vendor/autoload.php";

$emailQueue = new \Source\Support\Email();
$emailQueue->sendQueue();