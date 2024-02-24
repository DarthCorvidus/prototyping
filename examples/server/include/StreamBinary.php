<?php
namespace Examples\Server;
class StreamBinary extends Stream {
	function __construct(mixed $conn, StreamListenerBinary $listener) {
		$this->conn = $conn;
		stream_set_blocking($this->conn, false);
		$this->listener = $listener;
	}

	protected function read(): bool {
		$input = fread($this->conn, $this->listener->getBlocksize());
		if($input === false) {
			return true;
		}
		$this->listener->onData($input);
	return true;
	}

	protected function write(): bool {
		$data = $this->listener->getData();
		fwrite($this->conn, $data);
	return true;
	}
}
