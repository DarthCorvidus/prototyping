<?php
namespace Examples\Server;
class ServerMain implements \TermIOListener {
	private \plibv4\process\Timeshare $timeshare;
	private \TermIO $termio;
	private ServerProcess $server;
	function __construct() {
		$this->timeshare = new \plibv4\process\Timeshare();
		$this->termio = new \TermIO($this);
		$this->server = new ServerProcess($this->timeshare);
		$this->timeshare->addTimeshared($this->server);
		$this->timeshare->addTimeshared($this->termio);
	}
	
	function run() {
		$this->timeshare->run();
	}

	public function onInput(\TermIO $termio, string $input) {
		if($input === "halt") {
			$this->termio->addBuffer("Shutting down server.");
			$this->timeshare->terminate();
		}
		if($input === "status") {
			$this->termio->addBuffer("Process count: ".$this->timeshare->getProcessCount());
		}
	}
}
