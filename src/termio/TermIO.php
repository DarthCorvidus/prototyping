<?php
class TermIO implements \plibv4\process\Task {
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
	
	public function __tsLoop(): bool {
		if(!empty($this->outputBuffer)) {
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

	public function __tsStart(): void {
		
	}

	public function __tsFinish(): void {
		
	}

	public function __tsKill(): void {
		
	}

	public function __tsPause(): void {
		
	}

	public function __tsResume(): void {
		
	}

	public function __tsTerminate(): bool {
		return empty($this->outputBuffer);
	}
	
	public function __tsError(\Exception $e, int $step): void {
		;
	}
}
