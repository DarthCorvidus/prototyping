<?php
namespace Examples\Timeshared;
class Timer implements \Timeshared {
	private int $seconds;
	private int $start = 0;
	private int $stopped;
	function __construct(int $seconds) {
		$this->seconds = $seconds*10000;
		$this->start = microtime(true)*10000;
	}
	
	public function loop(): bool {
		$microtime = microtime(true)*10000;
		if($microtime-$this->start>$this->seconds) {
			$this->stopped = $microtime;
			return false;
		}
	return true;
	}

	public function start(): void {
		
	}

	public function stop(): void {
		echo "Timer with ".($this->seconds/10000)." seconds stopped after ".(($this->stopped-$this->start)/10000).PHP_EOL;
	}
}
