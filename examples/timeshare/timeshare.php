#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';

$timeshare = new Timeshare();
$input = new ReadInput();
$timeshare->addTimeshared($input);
while($timeshare->loop()) {
	
}
