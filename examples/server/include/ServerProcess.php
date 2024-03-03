<?php
namespace Examples\Server;
class ServerProcess implements \plibv4\process\Timeshared, \plibv4\process\TimeshareObserver {
	private mixed $server;
	private mixed $clientId = 0;
	private \plibv4\process\Timeshare $timeshare;
	private \plibv4\process\Timeshare $parent;
	private $terminated = false;
	private int $connected = 0;
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
		$clientStream = new StreamBinary($client, new \Examples\Server\Mode\Main());
		$this->timeshare->addTimeshareObserver($this);
		$this->timeshare->addTimeshared($clientStream);
		echo "Client accepted, ".$this->timeshare->getProcessCount()." processes.".PHP_EOL;
	return true;
	}
	
	public function getClientCount(): int {
		return $this->connected;
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

	public function onAdd(\plibv4\process\Timeshare $timeshare, \plibv4\process\Timeshared $timeshared): void {
		
	}

	public function onRemove(\plibv4\process\Timeshare $timeshare, \plibv4\process\Timeshared $timeshared, int $status): void {
		if($timeshared instanceof \Examples\Server\StreamBinary) {
			$this->connected--;
			echo "Client disconnected, ".$this->connected." client(s).".PHP_EOL;
			echo "Client processes: ".$this->timeshare->getProcessCount().PHP_EOL;
		}
	}

	public function onStart(\plibv4\process\Timeshare $timeshare, \plibv4\process\Timeshared $timeshared): void {
		if($timeshared instanceof \Examples\Server\StreamBinary) {
			$this->connected++;
			$this->clientId++;
			echo "Client accepted as ".$this->clientId.", ".$this->connected." client(s).".PHP_EOL;
			echo "Client processes: ".$this->timeshare->getProcessCount().PHP_EOL;
		}
	}
}
