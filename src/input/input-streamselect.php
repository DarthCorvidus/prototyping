#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
class InputStreamSelect {
	private StreamHandler $streamHandler;
	private OutputBuffer $outputBuffer;
	private Timers $timers;
	function __construct() {
		$this->timers = new Timers(1000000);
		$this->outputBuffer = new OutputBuffer();
		$this->streamHandler = new UserHandler($this->timers);
	}
	
	private function read(array $read): void {
		foreach($read as $value) {
			$input = trim(fgets($value));
			if($input === "") {
				return;
			}
			$this->streamHandler->handleData($input);
		}
	}
	
	public function write(array $write): void {
		foreach($write as $value) {
			$line = $this->streamHandler->getData();
			fwrite($value, $line.PHP_EOL);
		}
	}
	
	function loop() {
		$this->outputBuffer->addln("Enter 'help' for help!");
		$this->outputBuffer->add("> ");
		stream_set_blocking(STDIN, false);
		stream_set_blocking(STDOUT, false);
		while(true) {
			$read = array(STDIN);
			$write = array();
			$except = null;
			/*
			 * write will be ready most of the time, defeating the purpose of
			 * stream_select, as it would trigger most of the time. So we only
			 * add STDOUT to $write if there is something to be written.
			 * NOTE: from another project I know that doing it wrong has a
			 * measurable performance impact.
			 */
			if(!$this->streamHandler->hasData() && $this->streamHandler->hasEnded()) {
				return;
			}
			
			if($this->streamHandler->hasData()) {
				$write = array(STDOUT);
			}
			
			if(stream_select($read, $write, $except, 0, $this->timers->getLowest())<1) {
				$this->timers->execute();
				continue;
			}
			$this->timers->execute();
			$this->read($read);
			$this->write($write);
		}
	}
}

$input = new InputStreamSelect();
$input->loop();