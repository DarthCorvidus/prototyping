#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
/*
 * Strict comparison (===) is not only more secure, but way faster if types do
 * not match.
 */
$string = "mouse";
$int = 256;

for($i=0;$i<pow(2, 24);$i++) {
	\plibv4\profiler\Profiler::startTimer("string");
	if($string === "mouse") {
		
	}
	
	\plibv4\profiler\Profiler::endTimer("string");

	\plibv4\profiler\Profiler::startTimer("int");
	if($int === 256) {
		
	}
	\plibv4\profiler\Profiler::endTimer("int");
	
	\plibv4\profiler\Profiler::startTimer("loose");
	if($int == $string) {
		
	}
	\plibv4\profiler\Profiler::endTimer("loose");
	
	\plibv4\profiler\Profiler::startTimer("strict");
	if($int === $string) {
		
	}
	\plibv4\profiler\Profiler::endTimer("strict");

}

\plibv4\profiler\Profiler::printTimers();

