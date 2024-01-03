<?php
class StreamHub {
	private $server;
	private array $clients;
	#private int $blocksize = 1024*1;
	private int $clientCount = 0;
	private array $emptyCount;
	private ServerListener $serverListener;
	private array $clientListeners;
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
			echo "Client $key disconnected.".PHP_EOL;
			fclose($this->clients[$key]);
			unset($this->clients[$key]);
			unset($this->emptyCount[$key]);
			unset($this->clientListeners[$key]);
		}
	}
	
	private function connect() {
		$socket = stream_socket_accept($this->server);
		$listener = $this->serverListener->onConnect($this->clientCount);
		$this->addClient($socket, $listener);
	}
	
	function listen() {
		$i = 0;
		while(TRUE) {
			$read = array();
			if($this->server!=null) {
				$read["server"] = $this->server;
			}
			foreach($this->clients as $key => $value) {
				$read[$key] = $value;
			}
			$write = array();
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
		}
	}
}
