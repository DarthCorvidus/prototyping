<?php
namespace Examples\Server;
class ReceiveFile implements StreamListener {
	private int $size = 0;
	private bool $started = false;
	private int $left = 0;
	private string $path;
	private mixed $handle = null;
	private string $filename;
	public function __construct(string $path) {
		$this->path = $path;
	}
	
	public function getBlocksize(): int {
		return 4096;
	}

	public function getData(): string {
	}

	public function hasData(): bool {
		return false;
	}

	public function loop(): bool {
		if($this->started == false) {
			return true;
		}
		if($this->left > 0) {
			return true;
		}
	return false;
	}

	public function onConnect() {
		
	}

	public function onData(string $data) {
		if($this->started==false) {
			$reader = new \plibv4\Binary\StringReader($data, \plibv4\Binary\StringReader::BE);
			$this->size = $reader->getUInt64();
			$this->left = $this->size;
			/*
			 * Returns the basename, to prevent a malicious client from writing
			 * outside the designated target folder by using ../ or an absolute
			 * path.
			 */
			$this->filename = basename($reader->getIndexedString(16));
			$this->started = true;
			$data = substr($data, 10+strlen($this->filename));
			$this->handle = fopen($this->path."/".$this->filename, "w");
			fwrite($this->handle, $data);
			$this->left -= strlen($data);
		return;
		}
		if($this->left >= 4096) {
			$written = fwrite($this->handle, $data);
			$this->left -= $written;
		} else {
			$written = fwrite($this->handle, substr($data, 0, $this->left));
			$this->left -= $written;
		}
	}

	public function onDisconnect() {
		
	}

	public function onTerminate() {
		if($this->handle!=null) {
			fclose($this->handle);
		}
	}
}
