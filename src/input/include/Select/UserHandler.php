<?php
class UserHandler implements StreamHandler {
	private Lazy $lazy;
	private $buffer = array();
	private $ended = false;
	function __construct() {
		$this->lazy = new Lazy();
	}
	public function getBlockSize(): int {
		return 1024;
	}

	public function getData(): string {
		return array_shift($this->buffer);
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
