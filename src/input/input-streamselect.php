#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
class InputStreamSelect {
	private Lazy $lazy;
	private int $count;
	private bool $counting = false;
	private int $last;
	private array $writeBuffer = array();
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
	
	private function read(array $read): void {
		foreach($read as $value) {
			$input = trim(fgets($value));
			if($input === "") {
				return;
			}
			if($input == "help") {
				$this->writeBuffer += $this->lazy->getHelp();
			}
			if($input == "date") {
				$this->writeBuffer += $this->lazy->getDate();
			}
			if($input == "uptime") {
				$this->writeBuffer += $this->lazy->getUptime();
			}
			
			#if($input == "count") {
			#	$this->count = 0;
			#	$this->counting = true;
			#	$this->last = hrtime()[0];
			#}

			if($input == "exit") {
				exit();
			}
		}
	}
	
	public function write(array $write): void {
		foreach($write as $value) {
			$line = array_shift($this->writeBuffer);
			fwrite($value, $line.PHP_EOL);
		}
	}
	
	function loop() {
		echo "Enter 'help' for help!".PHP_EOL;
		stream_set_blocking(STDIN, false);
		while(true) {
			$read = array(STDIN);
			$write = array();
			$except = null;
			/*
			 * write will be ready most of the time, defeating the purpose of
			 * stream_select, as it would trigger most of the time. So we only
			 * add STDIN to $write if there is something to be written.
			 * NOTE: from another project I know that doing it wrong has a
			 * measurable performance impact.
			 */
			if(count($this->writeBuffer)>0) {
				$write = array(STDOUT);
			}
			if(stream_select($read, $write, $except, 0, 2000)<1) {
				continue;
			}
			$this->read($read);
			$this->write($write);
		}
	}
}

$input = new InputStreamSelect();
$input->loop();