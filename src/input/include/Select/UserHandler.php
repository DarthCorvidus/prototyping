<?php
class UserHandler implements StreamHandler, Timer {
	private Lazy $lazy;
	private $buffer = array();
	private $ended = false;
	private Timers $timers;
	private $count = 0;
	function __construct(Timers $timer) {
		$this->lazy = new Lazy();
		$this->timers = $timer;
	}
	public function getBlockSize(): int {
		return 1024;
	}

	public function getData(): string {
		return array_shift($this->buffer);
	}
	
	function onEvent() {
		$this->count++;
		$this->buffer[] = $this->count;
		if($this->count == 10) {
			$this->timers->removeTimer($this);
			$this->count = 0;
		}
	}

	public function handleData(string $data): void {
		if($data === "date") {
			$this->buffer += $this->lazy->getDate();
			return;
		}
		if($data === "uptime") {
			$this->buffer += $this->lazy->getUptime();
			return;
		}
		if($data === "help") {
			$this->buffer += $this->lazy->getHelp();
			return;
		}
		
		if($data == "count") {
			$this->timers->addTimer(1000000, $this);
		return;
		}

		if($data === "exit") {
			$this->buffer[] = "Goodbye";
			$this->ended = true;
		return;
		}

	$this->buffer[] = "Invalid command.";
	}

	public function hasData(): bool {
		return !empty($this->buffer);
	}

	public function hasEnded(): bool {
		return $this->ended;
	}

	public function isBinary(): bool {
		return false;
	}
}
