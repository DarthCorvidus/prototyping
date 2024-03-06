<?php
namespace Examples\Server;
class ClientMain implements \TermIOListener, StreamHandler, \plibv4\process\Timeshared {
	private \TermIO $termio;
	private \plibv4\process\Timeshare $timeshare;
	private StreamBinary $stream;
	private bool $active = true;
	private string $command = "";
	private ?StreamHandler $prepared = null;
	private ?StreamHandler $delegate = null;
	private int $started = 0;
	function __construct() {
		$this->termio = new \TermIO($this);
		$client = stream_socket_client("tcp://0.0.0.0:8000", $errno, $errstr);
		stream_set_blocking($client, false);
		$this->stream = new StreamBinary($client, $this);
		$this->timeshare = new \plibv4\process\Timeshare();
		$this->timeshare->addTimeshared($this);
		$this->timeshare->addTimeshared($this->termio);
		$this->timeshare->addTimeshared($this->stream);
		$this->started = time();
	}

	public function getData(): string {
		if($this->delegate != null) {
			return $this->delegate->getData();
		}
		// Create message.
		$msg = StreamBinary::putPayload($this->command, $this->getBlocksize());
		/**
		 * If there is a prepared delegate, set it as delegate and clear
		 * $this->prepared.
		 */
		if($this->prepared) {
			$this->delegate = $this->prepared;
			$this->prepared = null;
			echo "Switching to delegate ".$this->delegate::class.PHP_EOL;
		}
		/*
		 * Send command. If a delegate became active, it will only work now.
		 */
		
		$this->command = "";
	return $msg;
	}

	public function hasData(): bool {
		if($this->delegate != null) {
			return $this->delegate->hasData();
		}
		return $this->command != "";
	}

	public function isActive(): bool {
		if($this->delegate != null && $this->delegate->isActive() == false) {
			echo "Switching from delegate ".$this->delegate::class.PHP_EOL;
			$this->delegate->onTerminate();
			$this->delegate = null;
		}
		return true;
	}
	
	public function loop(): bool {
		return $this->isActive();
	}
	
	public function onConnect() {
		
	}

	public function rcvData(string $data) {
		if($this->delegate != null) {
			$this->delegate->onData($data);
		return;
		}
		$data = \Examples\Server\StreamBinary::getPayload($data);
		if($data == "quit") {
			$this->timeshare->terminate();
		return;
		}
		$this->termio->addBuffer($data);
	}

	public function onDisconnect(): void {
		
	}
	
	public function onTerminate(): void {
		;
	}

	public function onInput(\TermIO $termio, string $input) {
		if($input == "status") {
			$this->termio->addBuffer("Client status:");
			$convert = new \ConvertTime(\ConvertTime::SECONDS, \ConvertTime::HMS);
			$this->termio->addBuffer("  Runtime: ".$convert->convert(time()-$this->started));
			$this->termio->addBuffer("  Local process count: ".$this->timeshare->getProcessCount());
			$this->command = "status";
		return;
		}
		if($input == "put") {
			$this->termio->addBuffer("'put' needs filename");
		return;
		}
		$explode = explode(" ", $input, 2);
		if($explode[0]=="put" && !is_file($explode[1])) {
			$this->termio->addBuffer("no such file.");
		return;
		} 
		if($explode[0]=="put" && is_file($explode[1])) {
			$this->prepared = new SendFile($explode[1]);
			$this->command = "put";
		return;
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
		if($this->delegate) {
			return $this->delegate->getBlocksize();
		}
		return 512;
	}
}
