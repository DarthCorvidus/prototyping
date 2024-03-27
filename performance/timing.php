#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
/**
 * Comparing hrtime() to microtime() for time measurement.
 * hrtime-int is fastest, but microtime-float and hrtime are only off by a small
 * margin.
 * microtime is...evil (took about 22 to 23% in several runs).
 */

for($i=0;$i<pow(2, 24);$i++) {
	\plibv4\profiler\Profiler::startTimer("hrtime");
	hrtime();
	\plibv4\profiler\Profiler::endTimer("hrtime");

	\plibv4\profiler\Profiler::startTimer("hrtime-int");
	hrtime(true);
	\plibv4\profiler\Profiler::endTimer("hrtime-int");
	
	\plibv4\profiler\Profiler::startTimer("microtime");
	microtime();
	\plibv4\profiler\Profiler::endTimer("microtime");

	\plibv4\profiler\Profiler::startTimer("microtime-float");
	microtime(true);
	\plibv4\profiler\Profiler::endTimer("microtime-float");
}

\plibv4\profiler\Profiler::printTimers();

