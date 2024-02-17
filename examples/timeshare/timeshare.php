#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';

$timeshare = new \plibv4\process\Timeshare();
$termio = new TermIO(new ReadInput($timeshare));
$termio->addBuffer("Welcome to Timeshare experiment.");
$termio->addBuffer("Enter help for a set of commands.");
$timeshare->addTimeshared($termio);
$timeshare->run();