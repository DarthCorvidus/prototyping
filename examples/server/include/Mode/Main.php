<?php
namespace Examples\Server\Mode;
class Main implements \Examples\Server\StreamListener {
	private bool $active = true;
	private array $buffer = array();
	private int $requests = 0;
	function __construct() {
		$this->buffer[] = "Welcome to experimental server 0.1, use 'help' for help.";
	}
	public function getData(): string {
		return array_shift($this->buffer);
	}

	public function hasData(): bool {
		if(!empty($this->buffer)) {
			return true;
		}
	return false;
	}

	public function loop(): bool {
		if($this->active) {
			return true;
		}
		if(!empty($this->buffer)) {
			return true;
		}
	return false;
	}

	public function onConnect() {
		
	}

	public function onData(string $data) {
		$this->requests++;
		if($data == "quit") {
			$this->buffer[] = "Received quit at ".date("Y-m-d H:i:s");
			$this->buffer[] = "You wrote ".$this->requests." requests.";
			$this->buffer[] = "Good bye!";
			$this->buffer[] = "quit";
			$this->active = false;
		return;
		}
		if($data == "help") {
			$this->buffer[] = "quit - end connection";
			$this->buffer[] = "help - this help";
		return;
		}
	$this->buffer[] = "Unknown command.";
	}

	public function onDisconnect() {
		$this->buffer[] = "Server side shutdown.";
		$this->buffer[] = "quit";
	}
}
