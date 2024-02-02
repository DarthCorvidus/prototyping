#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';

$timeshare = new Timeshare();
$termio = new TermIO(new ReadInput($timeshare));
$timeshare->addTimeshared($termio);
while($timeshare->loop()) {
	
}
