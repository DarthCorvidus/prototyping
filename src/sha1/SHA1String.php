<?php
class SHA1String extends SHA1 implements Timeshared {
	private string $message;
	function __construct(string $message) {
		$this->message = $message;
		parent::setSize(strlen($message));
	}
	
	static function prepareMessage(string $string): string {
		// length of message in bits
		$bitsize = strlen($string) * 8;
		// append 10000000 as bit
		$string .= chr(128);
		// Pad with \0 until $message + 8 is a multiple of 64 byte or 512 bit.
		while(((strlen($string) + 8) % 64) !== 0) {
			$string .= chr(0);
		}
		// length in bit as 64bit integer
		$string .= \IntVal::uint64BE()->putValue($bitsize);
	return $string;
	}

	function getData(int $chunk): string {
		return substr($this->message, $chunk*64, 64);
	}
}
