<?php
class StreamHub {
	private $server;
	private array $clients;
	private int $blocksize = 1024*1;
	private int $clientCount = 0;
	private array $emptyCount;
	private array $listener;
	function __construct() {
		$this->clients = array();
	}
	
	public function setServer(mixed $socket) {
		$this->server = $socket;
	}
	
	private function read(int $key): void  {
		$data = fread($this->clients[$key], $this->listener[$key]->getBlockSize());
		if($data==="") {
			$this->emptyCount[$key]++;
		}
		if($data !== false and $data !== "") {
			$this->emptyCount[$key] = 0;
			$this->listener[$key]->onRead($data);
		return;
		}
		
		if($data === false or ($data === "" and $this->emptyCount[$key]==100)) {
			echo "Client $key disconnected.".PHP_EOL;
			fclose($this->clients[$key]);
			unset($this->clients[$key]);
			unset($this->emptyCount[$key]);
			print_r($this);
		}
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
					$this->clients[$this->clientCount] = stream_socket_accept($this->server);
					stream_set_blocking($this->clients[$this->clientCount], false);
					$this->emptyCount[$this->clientCount] = 0;
					$this->listener[$this->clientCount] = new FileReceiver();
					echo "Accepted connection with id ".$this->clientCount.".".PHP_EOL;
					$this->clientCount++;
					continue;
				}
				$this->read($key);
			}
		}
	}
}
