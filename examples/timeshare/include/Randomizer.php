<?php
class Randomizer implements Timeshared{
	private int $steps;
	private int $count = 0;
	private int $startTime;
	function __construct($steps) {
		$this->steps = $steps;
		$this->startTime = hrtime(true);
	}

	public function loop(): bool {
		if($this->count < $this->steps) {
			$random = sha1(random_bytes(65535));
			$this->count++;
		return true;
		}
	echo "Values: ".$this->steps.PHP_EOL;
	echo "Time:   ".round((hrtime(true)-$this->startTime)/1000000000, 2).PHP_EOL;
	return false;
	}

	public function start(): void {
		
	}

	public function stop(): void {
		
	}
}
