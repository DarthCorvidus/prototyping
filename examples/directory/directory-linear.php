#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
$do = new DirectorySize();
$dir = new DirectoryLinear($argv[1]);
$dir->addDirectoryObserver($do);
$dir->run();