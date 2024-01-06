<?php
namespace input;

/**
 * Description of Input
 *
 * @author hm
 */
class StreamHub {
	private mixed $input;
	private mixed $output;
	private ReadListener $readListener;
	function __construct(ReadListener $readListener) {
		$this->input = STDIN;
		$this->output =STDOUT;
		$this->readListener = $readListener;
	}
	
	private function read(array $read): void {
		foreach($read as $value) {
			$input = trim(fgets($value));
			if($input === "") {
				return;
			}
			$this->readListener->onRead(new ReadEvent($input));
		}
	}

	function listen() {
		while(true) {
			$read = array(STDIN);
			#$write = array();
			$except = null;
			#if(!$this->outputBuffer->isEmpty()) {
			#	$write = array(STDOUT);
			#}
			if(stream_select($read, $write, $except, 0, 2000)<1) {
				continue;
			}
			$this->read($read);
			#$this->write($write);
		}

	}
}
