#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
\plibv4\profiler\Profiler::startTimer("recursive");
$dir = new DirectoryRecursive($argv[1]);
$dir->run();
\plibv4\profiler\Profiler::endTimer("recursive");
\plibv4\profiler\Profiler::printTimers();