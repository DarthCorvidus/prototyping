<?php
namespace Examples\Server;
class ClientMain implements \TermIOListener, StreamListener, \Timeshared {
	private \TermIO $termio;
	private \Timeshare $timeshare;
	private Stream $stream;
	private bool $active = true;
	private string $command = "";
	function __construct() {
		$this->termio = new \TermIO($this);
		$client = stream_socket_client("tcp://0.0.0.0:8000", $errno, $errstr);
		stream_set_blocking($client, false);
		$this->stream = new Stream($client, $this);
		$this->timeshare = new \Timeshare();
		$this->timeshare->addTimeshared($this);
		$this->timeshare->addTimeshared($this->termio);
		$this->timeshare->addTimeshared($this->stream);
	}

	public function getData(): string {
		$command = $this->command;
		$this->command = "";
	return $command;
	}

	public function hasData(): bool {
		return $this->command != "";
	}

	public function loop(): bool {
	return $this->active;
	}

	public function onConnect() {
		
	}

	public function onData(string $data) {
		if($data == "quit") {
			$this->timeshare->terminate();
			$this->active = false;
		return;
		}
		$this->termio->addBuffer($data);
	}

	public function onDisconnect() {
		
	}

	public function onInput(\TermIO $termio, string $input) {
		if($input == "status") {
			$this->termio->addBuffer("Local process count: ".$this->timeshare->getProcessCount());
		}
		$this->command = $input;
		echo $this->termio->addBuffer("Your input: ".$input);
	}
	
	function run() {
		while($this->timeshare->loop()) {
			
		}
	}

	public function finish(): void {
		
	}

	public function kill(): void {
		
	}

	public function pause(): void {
		
	}

	public function resume(): void {
		
	}

	public function start(): void {
		
	}

	public function terminate(): void {
		$this->active = false;
	}
}