<?php
namespace Examples\Server;
abstract class Stream implements \plibv4\process\Timeshared {
	protected mixed $conn;
	private bool $quit = false;
	protected $terminated = false;
	public function finish(): void {
		fclose($this->conn);
	}

	public function kill(): void {
		
	}

	protected abstract function read(): bool;
	
	protected abstract function write(): bool;
	
	function close() {
		$this->quit = true;
	}
	
	public function loop(): bool {
		if($this->conn === false) {
			return false;
		}
		$loop = $this->listener->loop();
		if(!$loop) {
			$this->listener->onDisconnect();
		return false;
		}
		$hasData = $this->listener->hasData();
		if($hasData) {
			return $this->write();
		}
		$this->read();
	return true;
	}
	
	public function pause(): void {
		
	}

	public function resume(): void {
		
	}

	public function start(): void {
		
	}

	public function terminate(): bool {
		if(!$this->terminated) {
			$this->listener->onTerminate();
			$this->terminated = true;
		return false;
		}
		if(!$this->listener->hasData()) {
			return true;
		}
	return false;
	}
}