<?php
class TermIO implements Timeshared {
	private TermIOListener $listener;
	private $outputBuffer = array();
	public function __construct(TermIOListener $listener) {
		$this->listener = $listener;
	}
	
	public function addBuffer(string $output) {
		$this->outputBuffer[] = $output;
	}
	
	public function loop(): bool {
		if(!empty($this->outputBuffer)) {
			fwrite(STDOUT, array_shift($this->outputBuffer).PHP_EOL);
		return true;
		}
		$input = fgets(STDIN);
		if($input === false) {
			return true;
		}
		$trimmed = trim($input);
		if($trimmed === "") {
			return true;
		}
		$this->listener->onInput($this, $trimmed);
	return true;
	}

	public function start(): void {
		
	}

	public function stop(): void {
		
	}
}
