#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
/**
 * Testing the performance of different ways to count all odd and even numbers
 * in a given range.
 * Spoiler alert: "call" is slowest, classic02 fastest, name is acceptable.
 */
class Task {
	public int $even = 0;
	public int $odd = 1;
	public string $next = "incrOdd";
	public int $i = 0;
	function __construct() {
		
	}
	
	function incrOdd() {
		$this->odd += 2;
		$this->next = "incrEven";
	}
	
	function incrEven() {
		$this->even += 2;
		$this->next = "incrOdd";
	}
	
	/*
	 * call either calls Task::incrOdd() or Task::incrEven, and every call sets
	 * the value of Task::next to the other method. 
	 */
	public function call() {
		call_user_func(array($this, $this->next));
	}
	
	/**
	 * Task::name() calls Task::incrEven() and Task::incrOdd() using a variable
	 * as function name.
	 */
	public function name() {
		$next = $this->next;
		$this->$next();
	}
	
	/**
	 * Classic approach: modulo 2 and if/else.
	 */
	public function classic01() {
		if($this->i % 2 == 0) {
			$this->even += 2;
		} else {
			$this->odd += 2;
		}
	$this->i++;
	}

	/**
	 * Modified approach, modulo 2 and one if with return.
	 */
	public function classic02() {
		if($this->i % 2 == 0) {
			$this->even += 2;
			$this->i++;
		return;
		}
	$this->odd += 2;
	$this->i++;
	}

}
$call = new Task();
$if = new Task();
$name = new Task();

for($i=0;$i<pow(2, 24);$i++) {
	\plibv4\profiler\Profiler::startTimer("call");
	$call->call();
	\plibv4\profiler\Profiler::endTimer("call");

	\plibv4\profiler\Profiler::startTimer("classic01");
	$call->classic01();
	\plibv4\profiler\Profiler::endTimer("classic01");

	\plibv4\profiler\Profiler::startTimer("classic02");
	$call->classic02();
	\plibv4\profiler\Profiler::endTimer("classic02");

	\plibv4\profiler\Profiler::startTimer("name");
	$name->name();
	\plibv4\profiler\Profiler::endTimer("name");
}

\plibv4\profiler\Profiler::printTimers();