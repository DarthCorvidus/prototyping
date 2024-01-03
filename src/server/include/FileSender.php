<?php
class FileSender implements ClientListener {
	private $handler;
	private int $size = 0;
	private int $left = 0;
	function __construct(string $path) {
		$this->handler = fopen($path, "r");
		$this->size = filesize($path);
		$this->left = $this->size;
	}
	public function getBlocksize(): int {
		return 1024;
	}

	public function getWrite(int $amount): string {
		$data = fread($this->handler, $amount);
		$this->left -= $amount;
		if($this->left % ($amount*8) == 0) {
			echo $this->left.PHP_EOL;
		}
	return $data;
	}

	public function hasWrite(): bool {
		return $this->left>0;
	}

	public function onRead(string $data): void {
		
	}
}
