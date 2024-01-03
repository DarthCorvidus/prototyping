<?php
class FileReceiver implements ClientListener {
	private bool $receive = FALSE;
	private int $size = 0;
	private int $left = 0;
	function __construct() {
		
	}

	public function getBlocksize(): int {
		if($this->receive == true) {
			return 1024*8;
		} else {
			return 1024;
		}
	}

	public function getWrite(): string {
		
	}

	public function hasWrite(): bool {
		
	}
	private function onReadCommand(string $data): void {
		
	}
	public function onRead(string $data): void {
		if($this->receive == false) {
			$this->onReadCommand($data);
		} else {
			$this->onReadFile($data);
		}
	}
}