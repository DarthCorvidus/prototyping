#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
\plibv4\profiler\Profiler::startTimer("linear");
$dir = new DirectoryLinear($argv[1]);
$dir->run();
\plibv4\profiler\Profiler::endTimer("linear");
\plibv4\profiler\Profiler::printTimers();