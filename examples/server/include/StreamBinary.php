<?php
namespace Examples\Server;
class StreamBinary extends Stream {
	function __construct(mixed $conn, StreamHandler $handler) {
		$this->conn = $conn;
		stream_set_blocking($this->conn, false);
		$this->streamHandler = $handler;
	}

	protected function read(): bool {
		$input = fread($this->conn, $this->streamHandler->getBlocksize());
		if($input === "") {
			return true;
		}
		$this->streamHandler->rcvData($input);
	return true;
	}
	
	protected function write(): bool {
		$data = $this->streamHandler->getData();
		$written = fwrite($this->conn, $data);
		if($written === false) {
			throw new \RuntimeException("unable to write to stream");
		}
	return true;
	}
	
	static function putPayload(string $data, int $length) {
		$payloadLength = strlen($data);
		$header = \IntVal::uint16BE()->putValue($payloadLength);
		$padded = $header.$data.random_bytes($length - strlen($data)-2);
	return $padded;
	}
	
	static function getPayload(string $data): string {
		$payloadLength = \IntVal::uint16BE()->getValue(substr($data, 0, 2));
	return substr($data, 2, $payloadLength);
	}
}
