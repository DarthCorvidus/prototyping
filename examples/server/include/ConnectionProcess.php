<?php
namespace Examples\Server;
class ConnectionProcess implements \Timeshared {
	private mixed $conn;
	private int $id;
	private $output = [];
	private bool $quit = false;
	private int $requests = 0;
	function __construct(mixed $conn, int $connectionId) {
		$this->conn = $conn;
		stream_set_blocking($this->conn, false);
		$this->id = $connectionId;
		$this->output[] = "Welcome to echoing server, process id ".$this->id;
	}

	public function finish(): void {
		echo "Finished process ".$this->id.PHP_EOL;
	}

	public function kill(): void {
		
	}

	private function read() {
		if($this->quit) {
			return true;
		}
		$input = fgets($this->conn);
		if($input === false) {
			return true;
		}
		if(trim($input)==="quit") {
			$this->output[] = "Received quit at ".date("Y-m-d H:i:s");
			$this->output[] = "You wrote ".$this->requests." requests.";
			$this->output[] = "Good bye!";
			$this->quit = true;
		return true;
		}
		$this->output[] = "You wrote at ".date("Y-m-d H:i:s").": ".trim($input);
		$this->requests++;
	return true;
	}
	
	private function write() {
		fwrite($this->conn, array_shift($this->output).PHP_EOL);
		if($this->quit === true && empty($this->output)) {
			fclose($this->conn);
			return false;
		}
	return true;
	}
	
	public function loop(): bool {
		if($this->conn === false) {
			return false;
		}
		if(!empty($this->output)) {
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

	public function terminate(): void {
		
	}
}
