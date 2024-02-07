<?php
class TermIO implements Timeshared {
	private TermIOListener $listener;
	private array $outputBuffer = array();
	private bool $terminated = false;
	private mixed $stdin;
	public function __construct(TermIOListener $listener) {
		$this->listener = $listener;
		$this->stdin = STDIN;
		stream_set_blocking($this->stdin, false);
	}
	
	public function addBuffer(string $output) {
		$this->outputBuffer[] = $output;
	}
	
	public function loop(): bool {
		$empty = empty($this->outputBuffer);
		if($empty && $this->terminated) {
			return false;
		}
		if(!$empty) {
			fwrite(STDOUT, array_shift($this->outputBuffer).PHP_EOL);
		return true;
		}
		$input = fgets($this->stdin);
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

	public function finish(): void {
		
	}

	public function kill(): void {
		
	}

	public function pause(): void {
		
	}

	public function resume(): void {
		
	}

	public function terminate(): void {
		$this->terminated = true;
	}
}
