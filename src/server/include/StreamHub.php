<?php
class StreamHub {
	private $server;
	private array $clients;
	#private int $blocksize = 1024*1;
	private int $clientCount = 0;
	private array $emptyCount;
	private ServerListener $serverListener;
	private array $clientListeners;
	private $shutdown = false;
	function __construct() {
		$this->clients = array();
	}
	
	public function setServer(mixed $socket, ServerListener $listener) {
		$this->server = $socket;
		$this->serverListener = $listener;
	}
	
	public function addClient(mixed $socket, ClientListener $listener) {
		$this->clients[$this->clientCount] = $socket;
		stream_set_blocking($this->clients[$this->clientCount], false);
		$this->emptyCount[$this->clientCount] = 0;
		$this->clientListeners[$this->clientCount] = $listener;
		$this->clientCount++;
	}
	
	private function read(int $key): void  {
		$data = fread($this->clients[$key], $this->clientListeners[$key]->getBlockSize());
		if($data==="") {
			$this->emptyCount[$key]++;
		}
		if($data !== false and $data !== "") {
			$this->emptyCount[$key] = 0;
			$this->clientListeners[$key]->onRead($data);
		return;
		}
		
		if($data === false or ($data === "" and $this->emptyCount[$key]==100)) {
			$this->disconnect($key);
		}
	}
	
	private function write($key): void {
		$data = $this->clientListeners[$key]->getWrite($this->clientListeners[$key]->getBlocksize());
		fwrite($this->clients[$key], $data);
	}
	
	private function connect() {
		$socket = stream_socket_accept($this->server);
		$listener = $this->serverListener->onConnect($this->clientCount);
		$this->addClient($socket, $listener);
	}
	
	private function disconnect(int $key) {
		echo "Client $key disconnected.".PHP_EOL;
		fclose($this->clients[$key]);
		$this->clientListeners[$key]->onDisconnect();
		unset($this->clients[$key]);
		unset($this->emptyCount[$key]);
		unset($this->clientListeners[$key]);
	}
	
	public function shutdown() {
		foreach($this->clients as $key => $value) {
			$this->disconnect($key);
		}
		$this->shutdown = true;
	}
	
	function listen() {
		$i = 0;
		while(TRUE) {
			$read = array();
			$write = array();
			if($this->server!=null) {
				$read["server"] = $this->server;
			}
			foreach($this->clients as $key => $value) {
				$read[$key] = $value;
				if($this->clientListeners[$key]->hasWrite()) {
					$write[$key] = $value;
				}
			}
			if($this->shutdown) {
				return;
			}
			try {
				if(@stream_select($read, $write, $except, $tv_sec = 5) < 1) {
					#echo "No activity".PHP_EOL;
					continue;
				}
			} catch(TypeError $e) {
				echo $e->getMessage().PHP_EOL;
				exit();
			}
			foreach($read as $key => $value) {
				#echo "Activity on ".$key.PHP_EOL;
				if($value===$this->server) {
					$this->connect();
					continue;
				}
				$this->read($key);
			}
			foreach($write as $key => $value) {
				$this->write($key);
			}
		}
	}
}
