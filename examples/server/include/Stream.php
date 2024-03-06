<?php
namespace Examples\Server;
abstract class Stream implements \plibv4\process\Timeshared {
	protected mixed $conn;
	private bool $quit = false;
	protected bool $terminated = false;
	protected StreamHandler $streamHandler;
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
		$loop = $this->streamHandler->isActive();
		if(!$loop) {
			#$this->listener->onDisconnect();
		return false;
		}
		/**
		 * 
		 */
		$read = array($this->conn);
		$write = array();
		if($this->streamHandler->hasData()) {
			$write[] = $this->conn;
		}
		stream_select($read, $write, $except, 0);
		if(!empty($write)) {
			return $this->write();
		}
		if(!empty($read)) {
			$this->read();
		}
		
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
			$this->streamHandler->onTerminate();
			$this->terminated = true;
		return false;
		}
		if(!$this->streamHandler->hasData()) {
			return true;
		}
	return false;
	}
}