<?php
namespace Examples\Timeshared;
class Timer implements \Timeshared {
	private int $seconds;
	private int $start = 0;
	private int $stopped;
	private int $spent;
	private TimerListener $listener;
	function __construct(int $seconds, TimerListener $listener) {
		$this->seconds = $seconds*10000;
		$this->start = microtime(true)*10000;
		$this->listener = $listener;
	}
	
	public function loop(): bool {
		$microtime = microtime(true)*10000;
		if($microtime-$this->start>$this->seconds) {
			$this->stopped = $microtime;
			$this->spent = $this->stopped-$this->start;
		return false;
		}
	return true;
	}

	public function start(): void {
		
	}

	function getMicroseconds(): int {
		return $this->seconds;
	}
	
	function getSpent(): int {
		return $this->spent;
	}
	
	public function stop(): void {
		$this->listener->onEnd($this);
	}
}
