<?php
namespace Examples\Server;
class ServerMain implements \TermIOListener {
	private \plibv4\process\Timeshare $timeshare;
	private \TermIO $termio;
	private ServerProcess $server;
	private static ?ServerMain $singleton = null;
	private function __construct() {
		if(!file_exists(self::getRepoDir())) {
			mkdir(self::getRepoDir());
		}
		$this->timeshare = new \plibv4\process\Timeshare();
		$this->termio = new \TermIO($this);
		$this->termio->addBuffer("Experimental file server 0.1");
		$this->termio->addBuffer("Use 'halt' to shut down server or 'status' for information.");
		$this->server = new ServerProcess($this->timeshare);
		$this->timeshare->addTimeshared($this->server);
		$this->timeshare->addTimeshared($this->termio);
	}
	
	public static function init(): ServerMain {
		if(self::$singleton != null) {
			throw new \RuntimeException(self::class." already initialized.");
		}
		self::$singleton = new ServerMain();
	return self::$singleton;
	}
	
	public static function halt() {
		self::$singleton->termio->addBuffer("Shutting down server.");
		self::$singleton->timeshare->terminate();
	}
	
	static function getRepoDir(): string {
		return "/tmp/prototyping";
	}
	
	function run() {
		$this->timeshare->run();
	}

	public function onInput(\TermIO $termio, string $input) {
		if($input === "halt") {
			self::halt();
			#$this->termio->addBuffer("Shutting down server.");
			#$this->timeshare->terminate();
		}
		if($input === "status") {
			$this->termio->addBuffer("Process count: ".$this->timeshare->getProcessCount());
		}
	}
}
