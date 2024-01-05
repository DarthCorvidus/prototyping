#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
class InputNonBlocking {
	private Lazy $lazy;
	private int $count;
	private bool $counting = false;
	private int $last;
	function __construct() {
		$this->lazy = new Lazy();
	}
	
	private function count() {
		if($this->counting == false) {
			return;
		}
		$hr = hrtime();
		$now = $hr[0];
		if($now-$this->last == 1) {
			echo $this->count.PHP_EOL;
			$this->last = $now;
			$this->count++;
		}
		if($this->count == 10) {
			$this->counting = false;
		}
	}
	
	function getInput() {
		$prompted = false;
		while(true) {
			// Prevents the script from gobbling up more resources than it needs.
			usleep(2000);
			$this->count();
			if($prompted == false) {
				echo "> ";
				$prompted = true;
			}
			$input = fgets(STDIN);
			if($input == false) {
				continue;
			}
			if(trim($input)==="") {
				$prompted = false;
			}
		return trim($input);
		}
	}
	
	function loop() {
		echo "Enter 'help' for help!".PHP_EOL;
		stream_set_blocking(STDIN, false);
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
			
			if($input == "count") {
				$this->count = 0;
				$this->counting = true;
				$this->last = hrtime()[0];
			}
			
			if($input == "exit") {
				exit();
			}
		}
	}
}

$input = new InputNonBlocking();
$input->loop();