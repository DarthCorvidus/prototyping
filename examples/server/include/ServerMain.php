<?php
namespace Examples\Server;
class ServerMain implements \TermIOListener {
	private \plibv4\process\Timeshare $timeshare;
	private \TermIO $termio;
	private ServerProcess $server;
	private static ?ServerMain $singleton = null;
	private $started;
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
		$this->started = time();
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

	public static function status(): array {
		$status[] = "Server status:";
		$convert = new \ConvertTime(\ConvertTime::SECONDS, \ConvertTime::HMS);
		$status[] = "  Runtime: ".$convert->convert(time()-self::$singleton->started);
		$status[] = "  Connected clients: ".self::$singleton->server->getClientCount();
	return $status;
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
		}
		if($input === "status") {
			foreach(self::status() as $value) {
				$this->termio->addBuffer($value);
			}
			
		}
	}
}
