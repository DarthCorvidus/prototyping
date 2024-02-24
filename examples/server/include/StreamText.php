<?php
namespace Examples\Server;
class StreamText extends Stream {
	function __construct(mixed $conn, StreamListener $listener) {
		$this->conn = $conn;
		stream_set_blocking($this->conn, false);
		$this->listener = $listener;
	}

	protected function read(): bool {
		$input = fgets($this->conn);
		if($input === false) {
			return true;
		}
		$this->listener->onData(trim($input));
	return true;
	}

	protected function write(): bool {
		$data = $this->listener->getData();
		fwrite($this->conn, $data.PHP_EOL);
	return true;
	}

}
