<?php
namespace Examples\Server\Mode;
class Main implements \Examples\Server\StreamListener {
	private bool $active = true;
	private array $buffer = array();
	private int $requests = 0;
	private ?\Examples\Server\StreamListener $delegate = null;
	function __construct() {
		$this->buffer[] = "Welcome to experimental server 0.1, use 'help' for help.";
	}
	public function getData(): string {
		if($this->delegate) {
			return $this->delegate->getData();
		}
		return \Examples\Server\StreamBinary::putPayload(array_shift($this->buffer), $this->getBlocksize());
	}
	
	public function hasData(): bool {
		if($this->delegate) {
			return $this->delegate->hasData();
		}
		if(!empty($this->buffer)) {
			return true;
		}
	return false;
	}

	public function loop(): bool {
		if($this->delegate) {
			if(!$this->delegate->loop()) {
				$this->delegate = null;
			}
		}
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
		$data = \Examples\Server\StreamBinary::getPayload($data);
		if($this->delegate) {
			$this->delegate->onData($data);
		return;
		}
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
		
		if($data == "count") {
			$this->delegate = new \Examples\Server\Counter(15, 1);
		return;
		}
	$this->buffer[] = "Unknown command.";
	}

	public function onDisconnect() {
	}
	
	public function onTerminate() {
		if($this->delegate) {
			$this->delegate->onTerminate();
			$this->delegate = null;
		}
		$this->buffer[] = "Server side shutdown.";
		$this->buffer[] = "quit";
	}

	public function getBlocksize(): int {
		return 512;
	}
}
