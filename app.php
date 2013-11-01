<?php

include "vendor/autoload.php";

$application = new \Symfony\Component\Console\Application();
$application->add(new \Burgov\PredisWrapper\Command\CompareCommandsCommand());
$application->run();