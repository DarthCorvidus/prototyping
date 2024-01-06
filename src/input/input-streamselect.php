#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
class InputStreamSelect {
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
	
	function loop() {
		echo "Enter 'help' for help!".PHP_EOL;
		stream_set_blocking(STDIN, false);
		while(true) {
			$read = array(STDIN);
			$write = array();
			$except = null;
			#$write = array(STDOUT);
			if(stream_select($read, $write, $except, 0, 2000)<1) {
				continue;
			}
			$input = trim(fgets(STDIN));
			if($input === "") {
				continue;
			}
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

$input = new InputStreamSelect();
$input->loop();