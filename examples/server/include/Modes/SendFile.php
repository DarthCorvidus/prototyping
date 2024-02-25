<?php
namespace Examples\Server;
class SendFile implements StreamListener {
	private string $path;
	private int $size;
	private mixed $handle;
	private int $left;
	private int $block = 0;
	function __construct(string $path) {
		$this->path = $path;
		$this->size = filesize($path);
		$this->left = $this->size;
		$this->handle = fopen($path, "r");
	}
	
	public function getBlocksize(): int {
		return 4096;
	}

	public function getData(): string {
		if($this->block == 0) {
			$writer = new \plibv4\Binary\StringWriter(\plibv4\Binary\StringWriter::BE);
			$writer->addUInt64($this->size);
			$writer->addIndexedString(16, basename($this->path));
			$header = $writer->getBinary();
			$headerlength = strlen($header);
			$data = fread($this->handle, 4096-$headerlength);
			$this->left -= 4096-$headerlength;
			$this->block++;
		return $header.$data;
		}
		if($this->left<4096) {
			$data = fread($this->handle, 4096);
			$this->left -= 4096;
		return $data.random_bytes(4096-strlen($data));
		}
		$data = fread($this->handle, 4096);
		$this->left -= 4096;
	return $data;
	}

	public function hasData(): bool {
		return $this->left > 0;
	}

	public function loop(): bool {
		return $this->left > 0;
	}

	public function onConnect() {
		
	}

	public function onData(string $data) {
		
	}

	public function onDisconnect() {
		
	}

	public function onTerminate() {
		fclose($this->handle);
	}
}
