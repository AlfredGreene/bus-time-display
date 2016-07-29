<?php

require_once "vendor/autoload.php";

set_time_limit(0);
ini_set('max_execution_time', 0);

$application = new \Symfony\Component\Console\Application();
$application->add(new Buses\Command\BusStopCommand());
$application->run();