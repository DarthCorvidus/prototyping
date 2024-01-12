#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';

$timeshare = new Timeshare();
$timeshare->addTimeshared(new ReadInput($timeshare));
$timeshare->run();
echo "End of program.".PHP_EOL;