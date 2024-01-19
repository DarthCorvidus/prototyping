<?php
class SHA1String extends SHA1 {
	static function prepareMessage(string $string): string {
		// length of message in bits
		$bitsize = strlen($string) * 8;
		// append 10000000 as bit
		$string .= chr(128);
		// Pad with \0 until $message + 8 is a multiple of 64 byte or 512 bit.
		while(((strlen($string) + 8) % 64) !== 0) {
			$string .= chr(0);
		}
		$string .= \IntVal::uint64BE()->putValue($bitsize);
	return $string;
	}
}
