<?php
namespace Examples\Server;
class ClientMain implements \TermIOListener, StreamListener, \plibv4\process\Timeshared {
	private \TermIO $termio;
	private \plibv4\process\Timeshare $timeshare;
	private StreamBinary $stream;
	private bool $active = true;
	private string $command = "";
	function __construct() {
		$this->termio = new \TermIO($this);
		$client = stream_socket_client("tcp://0.0.0.0:8000", $errno, $errstr);
		stream_set_blocking($client, false);
		$this->stream = new StreamBinary($client, $this);
		$this->timeshare = new \plibv4\process\Timeshare();
		$this->timeshare->addTimeshared($this);
		$this->timeshare->addTimeshared($this->termio);
		$this->timeshare->addTimeshared($this->stream);
	}

	public function getData(): string {
		$command = $this->command;
		$this->command = "";
	return StreamBinary::putPayload($command, $this->getBlocksize());
	}

	public function hasData(): bool {
		return $this->command != "";
	}

	public function loop(): bool {
		return true;
	}

	public function onConnect() {
		
	}

	public function onData(string $data) {
		$data = \Examples\Server\StreamBinary::getPayload($data);
		if($data == "quit") {
			$this->timeshare->terminate();
		return;
		}
		$this->termio->addBuffer($data);
	}

	public function onDisconnect() {
		
	}
	
	public function onTerminate() {
		;
	}

	public function onInput(\TermIO $termio, string $input) {
		if($input == "status") {
			$this->termio->addBuffer("Local process count: ".$this->timeshare->getProcessCount());
		}
		$this->command = $input;
		echo $this->termio->addBuffer("Your input: ".$input);
	}
	
	function run() {
		$this->timeshare->run();
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

	public function terminate(): bool {
		return true;
	}

	public function getBlocksize(): int {
		return 512;
	}
}
