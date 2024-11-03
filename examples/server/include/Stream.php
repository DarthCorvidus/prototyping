<?php
namespace Examples\Server;
use plibv4\process\Task;
abstract class Stream implements Task {
	protected mixed $conn;
	private bool $quit = false;
	protected bool $terminated = false;
	protected StreamHandler $streamHandler;
	public function finish(): void {
		fclose($this->conn);
	}

	public function __tsKill(): void {
		
	}

	protected abstract function read(): bool;
	
	protected abstract function write(): bool;
	
	function close() {
		$this->quit = true;
	}
	
	public function __tsLoop(): bool {
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
	
	public function __tsPause(): void {
		
	}

	public function __tsResume(): void {
		
	}

	public function __tsStart(): void {
		
	}

	public function __tsTerminate(): bool {
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
	
	public function __tsFinish(): void {
		;
	}
	
	public function __tsError(\Exception $e, int $step): void {
		;
	}
}