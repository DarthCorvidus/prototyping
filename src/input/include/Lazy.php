<?php
class Lazy {
	private int $start;
	function __construct() {
		$this->start = hrtime(true);
	}
	
	function getUptime(): array {
		return array((hrtime(true)-$this->start)/1000000000);
	}
	
	function getDate(): array {
		return array(date("Y-m-d H:i:s"));
	}
	
	function getHelp(): array {
		$result[] = "help - this help";
		$result[] = "exit - end program";
		$result[] = "time - current time";
		$result[] = "uptime - runtime since start of loop";
	return $result;
	}
}
