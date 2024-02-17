<?php
namespace Examples\Server;
class Counter implements StreamListener {
	private $seconds = 0;
	private $times = 0;
	private $last = 0;
	private $current = 0;
	private $delta = 0;
	function __construct(int $times, int $seconds) {
		$this->times = $times;
		$this->seconds = $seconds;
		$this->last = microtime(true);
	}

	public function getData(): string {
		$this->current++;
		return $this->current;
	}

	public function hasData(): bool {
		if($this->current < $this->times && $this->delta >= 1) {
			$this->last = microtime(true);
			$this->delta = 0;
			return true;
		}
	return false;
		#$now = microtime(true);
		#if(microtime(true)>=$this->last+$this->seconds) {
		#	return true;
		#}
		#$this->last = $now;
	#return false;
	}

	public function onConnect() {
		
	}

	public function onData(string $data) {
		echo "Ignoring data.".PHP_EOL;
	}

	public function onDisconnect() {
		
	}

	public function loop(): bool {
		$this->delta = microtime(true)-$this->last;
		if($this->current < $this->times) {
			return true;
		}
	return false;
	}

	public function onTerminate() {
		
	}
}
