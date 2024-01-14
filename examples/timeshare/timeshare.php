#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';

#$timeshare = new Timeshare();
$input = new ReadInput();
Timeshare::addTimeshared($input);
Timeshare::run();
echo "End of program.".PHP_EOL;