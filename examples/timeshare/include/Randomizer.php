<?php
class Randomizer implements plibv4\process\Timeshared {
	private int $steps;
	private int $count = 0;
	private int $startTime;
	function __construct($steps) {
		$this->steps = $steps;
		$this->startTime = hrtime(true);
	}

	public function loop(): bool {
		if($this->count < $this->steps) {
			sha1(random_bytes(65535));
			$this->count++;
		return true;
		}
	return false;
	}

	public function finish(): void {
		echo "Values: ".$this->steps.PHP_EOL;
		echo "Time:   ".round((hrtime(true)-$this->startTime)/1000000000, 2).PHP_EOL;
	}

	public function kill(): void {
		
	}

	public function pause(): void {
		
	}

	public function resume(): void {
		
	}

	public function start(): void {
		
	}

	public function terminate(): bool {
		return true;
	}
}
