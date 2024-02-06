<?php
namespace Examples\Server;
class ServerMain {
	private \Timeshare $timeshare;
	function __construct() {
		$this->timeshare = new \Timeshare();
		$this->timeshare->addTimeshared(new ServerProcess($this->timeshare));
	}
	
	function run() {
		while($this->timeshare->loop()) {
			
		}
	}
}
