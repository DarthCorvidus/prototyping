<?php
class SHA1String extends SHA1 {
	private string $message;
	function __construct(string $message) {
		$this->message = $message;
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
	
	function getHash() {
		$this->v0 = self::H0;
		$this->v1 = self::H1;
		$this->v2 = self::H2;
		$this->v3 = self::H3;
		$this->v4 = self::H4;
		$prepared = self::prepareMessage($this->message);
		$chunks = str_split($prepared, 64);
		foreach($chunks as $chunk) {
			// initialize $a to $e for this round with hashes from last round.
			$a = $this->v0;
			$b = $this->v1;
			$c = $this->v2;
			$d = $this->v3;
			$e = $this->v4;
			// expand $chunk from 16 32bit words to 80.
			$w = self::expand($chunk);

			for($i=0;$i<80;$i++) {
				$f = self::getSHA($i, $b, $c, $d);
				$k = self::getK($i);
				$tmp = (self::rotateLeft($a, 5) + $f + $e + $k + $w[$i]) & 0xffffffff;
				$e = $d;
				$d = $c;
				$c = self::rotateLeft($b, 30);
				$b = $a;
				$a = $tmp;
			}
			$this->v0 = ($this->v0 + $a) & 0xffffffff;
			$this->v1 = ($this->v1 + $b) & 0xffffffff;
			$this->v2 = ($this->v2 + $c) & 0xffffffff;
			$this->v3 = ($this->v3 + $d) & 0xffffffff;
			$this->v4 = ($this->v4 + $e) & 0xffffffff;
		}
	return sprintf('%08x%08x%08x%08x%08x', $this->v0, $this->v1, $this->v2, $this->v3, $this->v4);
	}
}
