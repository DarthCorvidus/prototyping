<?php
namespace Examples\Server;
class Stream implements \plibv4\process\Timeshared {
	private mixed $conn;
	private StreamListener $listener;
	private $terminated = false;
	function __construct(mixed $conn, StreamListener $listener) {
		$this->conn = $conn;
		stream_set_blocking($this->conn, false);
		$this->listener = $listener;
	}

	public function finish(): void {
		fclose($this->conn);
	}

	public function kill(): void {
		
	}

	private function read() {
		$input = fgets($this->conn);
		if($input === false) {
			return true;
		}
		$this->listener->onData(trim($input));
	return true;
	}
	
	private function write() {
		$data = $this->listener->getData();
		fwrite($this->conn, $data.PHP_EOL);
	return true;
	}
	
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
			$this->listener->onDisconnect();
			$this->terminated = true;
		return false;
		}
		if(!$this->listener->hasData()) {
			return true;
		}
	return false;
	}
}