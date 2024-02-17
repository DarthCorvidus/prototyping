#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';

$timeshare = new \plibv4\process\Timeshare();
$termio = new TermIO(new ReadInput($timeshare));
$timeshare->addTimeshared($termio);
$timeshare->run();