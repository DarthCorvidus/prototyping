<?php
namespace Examples\Server;
class ServerProcess implements \Timeshared {
	private mixed $server;
	private mixed $clientId = 0;
	private \Timeshare $timeshare;
	function __construct(\Timeshare $timeshare) {
		$this->server = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);
		$this->timeshare = $timeshare;
	}
	public function finish(): void {
		
	}

	public function kill(): void {
		
	}

	public function loop(): bool {
		$read = array($this->server);
		$write = array();
		if(stream_select($read, $write, $except, 0) < 1) {
			return true;
		}
		echo "Client accepted.".PHP_EOL;
		$client = stream_socket_accept($this->server);
		$connProc = new ConnectionProcess($client, $this->clientId);
		$this->timeshare->addTimeshared($connProc);
		$this->clientId++;
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