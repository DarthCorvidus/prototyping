<?php
class FileReceiver implements ClientListener {
	private bool $receive = FALSE;
	private int $size = 0;
	private int $left = 0;
	private int $received = 0;
	function __construct() {
		
	}

	public function getBlocksize(): int {
		if($this->receive == true) {
			return 1024*8;
		} else {
			return 1024;
		}
	}

	public function getWrite(int $amount): string {
		
	}

	public function hasWrite(): bool {
		return false;
	}
	private function onReadCommand(string $data): void {
		
	}
	public function onRead(string $data): void {
		$this->received += strlen($data);
	return;
		if($this->receive == false) {
			$this->onReadCommand($data);
		} else {
			$this->onReadFile($data);
		}
	}
	
	public function onDisconnect() {
		echo "Bytes received: ".$this->received.PHP_EOL;
	}
}