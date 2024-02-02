<?php
class TermIO implements Timeshared {
	private TermIOListener $listener;
	public function __construct(TermIOListener $listener) {
		$this->listener = $listener;
	}
	
	public function loop(): bool {
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
