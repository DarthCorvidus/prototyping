<?php
namespace Examples\Server;
class ServerProcess implements \plibv4\process\Timeshared {
	private mixed $server;
	private mixed $clientId = 0;
	private \plibv4\process\Timeshare $timeshare;
	private \plibv4\process\Timeshare $parent;
	private $terminated = false;
	function __construct(\plibv4\process\Timeshare $timeshare) {
		$this->server = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);
		$this->timeshare = new \plibv4\process\Timeshare();
		$this->parent = $timeshare;
	}
	public function finish(): void {
		
	}

	public function kill(): void {
		
	}

	public function loop(): bool {
		if($this->terminated) {
			return $this->timeshare->loop();
		}
		$this->timeshare->loop();
		$read = array($this->server);
		$write = array();
		if(stream_select($read, $write, $except, 0) < 1) {
			return true;
		}
		$client = stream_socket_accept($this->server);
		$clientStream = new StreamText($client, new \Examples\Server\Mode\Main());
		$this->timeshare->addTimeshared($clientStream);
		echo "Client accepted, ".$this->timeshare->getProcessCount()." processes.".PHP_EOL;
		$this->clientId++;
	return true;
	}

	public function pause(): void {
		
	}

	public function resume(): void {
		
	}

	public function start(): void {
		
	}

	public function terminate(): bool {
		$this->terminated = true;
		return $this->timeshare->terminate();
	}
}
