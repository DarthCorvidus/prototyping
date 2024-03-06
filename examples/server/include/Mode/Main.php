<?php
namespace Examples\Server\Mode;
class Main implements \Examples\Server\StreamHandler {
	private bool $active = true;
	private array $buffer = array();
	private int $requests = 0;
	private ?\Examples\Server\StreamHandler $delegate = null;
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

	public function isActive(): bool {
		if($this->delegate) {
			if(!$this->delegate->isActive()) {
				echo "Switching from delegate ".$this->delegate::class.PHP_EOL;
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

	public function rcvData(string $data) {
		if($this->delegate) {
			$this->delegate->rcvData($data);
		return;
		}
		$data = \Examples\Server\StreamBinary::getPayload($data);
		echo "got command ".$data.PHP_EOL;
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
			$this->buffer[] = "put <filename>   upload file";
			$this->buffer[] = "quit             end connection";
			$this->buffer[] = "help             this help";
			$this->buffer[] = "halt             shutdown server";
			$this->buffer[] = "status           server status";
		return;
		}
		
		if($data == "count") {
			$this->delegate = new \Examples\Server\Counter(15, 1);
		return;
		}
		if($data == "put") {
			$this->delegate = new \Examples\Server\ReceiveFile("/tmp/");
			echo "Switching to delegate ".$this->delegate::class.PHP_EOL;
			#$this->buffer[] = "send";
		return;
		}
		if($data == "halt") {
			\Examples\Server\ServerMain::halt();
		}
		if($data == "status") {
			$this->buffer = array_merge($this->buffer, \Examples\Server\ServerMain::status());
		return;
		}
	$this->buffer[] = "Unknown command.";
	}

	public function onDisconnect(): void {
	}
	
	public function onTerminate(): void {
		if($this->delegate) {
			$this->delegate->onTerminate();
			$this->delegate = null;
		}
		$this->buffer[] = "Server side shutdown.";
		$this->buffer[] = "quit";
	}

	public function getBlocksize(): int {
		if($this->delegate) {
			return $this->delegate->getBlocksize();
		}
		return 512;
	}
}
