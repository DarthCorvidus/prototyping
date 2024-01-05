#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
class InputBlocking {
	private Lazy $lazy;
	function __construct() {
		$this->lazy = new Lazy();
	}
	
	function getInput() {
		while(true) {
			echo "> ";
			$input = trim(fgets(STDIN));
			if($input=="") {
				continue;
			}
		break;
		}
	return $input;
	}
	
	function loop() {
		echo "Enter 'help' for help!".PHP_EOL;
		while(true) {
			$input = $this->getInput();
			if($input == "help") {
				echo implode(PHP_EOL, $this->lazy->getHelp()).PHP_EOL;
			}
			if($input == "date") {
				echo implode(PHP_EOL, $this->lazy->getDate()).PHP_EOL;
			}
			if($input == "uptime") {
				echo implode(PHP_EOL, $this->lazy->getUptime()).PHP_EOL;
			}
			if($input == "exit") {
				exit();
			}
		}
	}
}

$input = new InputBlocking();
$input->loop();