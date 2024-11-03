<?php
class FileSender implements ClientListener {
	private $handler;
	private int $size = 0;
	private int $left = 0;
	private StreamHub $hub;
	function __construct(string $path, StreamHub $hub) {
		$this->handler = fopen($path, "r");
		$this->size = filesize($path);
		$this->left = $this->size;
		$this->hub = $hub;
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
		if($this->left<=0) {
			$this->hub->shutdown();
			echo "Shutting down and returning false on hasWrite".PHP_EOL;
			return false;
		}
		return $this->left>0;
	}

	public function onRead(string $data): void {
		
	}
	
	public function onDisconnect() {
		;
	}
}
