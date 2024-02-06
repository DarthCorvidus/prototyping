<?php
namespace Examples\Server;
class ConnectionProcess implements \Timeshared {
	private mixed $conn;
	private int $id;
	private $output = [];
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

	public function loop(): bool {
		if($this->conn === false) {
			return false;
		}
		if(!empty($this->output)) {
			fwrite($this->conn, array_shift($this->output).PHP_EOL);
		return true;
		}
		$input = fgets($this->conn);
		if($input === false) {
			return true;
		}
		$this->output[] = "You wrote at ".date("Y-m-d H:i:s").": ".trim($input);
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
