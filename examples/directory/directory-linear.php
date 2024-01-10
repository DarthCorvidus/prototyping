#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
\plibv4\profiler\Profiler::startTimer("linear");
$do = new DirectorySize();
$dir = new DirectoryLinear($argv[1]);
$dir->addDirectoryObserver($do);
$dir->run();
\plibv4\profiler\Profiler::endTimer("linear");
\plibv4\profiler\Profiler::printTimers();

echo "Size: ".number_format($do->getSize()).PHP_EOL;
echo "Files: ".number_format($do->getFileCount()).PHP_EOL;