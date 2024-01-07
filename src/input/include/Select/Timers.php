<?php
class Timers {
	private array $timers = array();
	private int $index = 0;
	private array $passed = array();
	private array $nanoseconds = array();
	private int $last;
	private int $lowestTime;
	private int $delta;
	function __construct(int $defaultTime) {
		$this->last = floor(hrtime(true)/1000);
		$this->lowestTime = $defaultTime;
	}
	
	function addTimer(int $nanoseconds, Timer $timer) {
		$this->timers[$this->index] = $timer;
		$this->nanoseconds[$this->index] = $nanoseconds;
		$this->passed[$this->index] = 0;
		$this->lowestTime = $this->calcLowest();
		$this->index++;
	}
	
	function removeTimer(Timer $timer) {
		$key = array_search($timer, $this->timers);
		unset($this->timers[$key]);
		unset($this->nanoseconds[$key]);
		unset($this->passed[$key]);
	}
	
	private function calcLowest(): int {
		$nano = $this->lowestTime;
		foreach($this->nanoseconds as $value) {
			if($value<$nano) {
				$nano = $value;
			}
		}
	return $nano;
	}
	
	function getLowest(): int {
		return $this->lowestTime;
	}
	
	function hasTimers() {
		return !empty($this->timers);
	}
	
	private function executeTimer(int $key) {
		if($this->passed[$key] >= $this->nanoseconds[$key]-$this->delta) {
			$this->passed[$key] = 0;
			$this->timers[$key]->onEvent();
		} else {
			$this->passed[$key] += $this->delta;
		}
	}
	
	function execute() {
		$now = floor(hrtime(true)/1000);
		$this->delta = $now-$this->last;
		foreach($this->timers as $key => $value) {
			$this->executeTimer($key);
		}
		$this->last = $now;
	}
}
