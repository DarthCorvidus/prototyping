<?php
abstract class SHA1 implements Timeshared {
	const H0 = 0x67452301;
	const H1 = 0xEFCDAB89;
	const H2 = 0x98BADCFE;
	const H3 = 0x10325476;
	const H4 = 0xC3D2E1F0;
	protected int $v0 = self::H0;
	protected int $v1 = self::H1;
	protected int $v2 = self::H2;
	protected int $v3 = self::H3;
	protected int $v4 = self::H4;
	private int $chCount = 0;
	protected int $chunks;
	protected bool $overpad = false;
	protected int $length;
	static function rotateLeft(int $rl, int $n): int {
		// Shift the integer to the left:
		$left = ($rl << $n);
		/*
		 * Shift the integer by 32 - n to the right, which moves $n amount of bit
		 * to the end.
		 */
		
		$right = $rl >> (32-$n);
		/**
		 * Join both using or, which keeps the bits shifted to the left and the
		 * bits shifted to the right.
		 */
		$union = $right | $left;
		/*
		 * Problem is that PHP int is a 64bit signed integer, which means that
		 * bits shifted to the left from the 32bit range are kept, not dropped.
		 * We have to remove them. The first solution was to essentially use
		 * pack and unpack. Which was bad, but gave a working solution and
		 * baseline for tests:
		 */
		#$rid = IntVal::uint32BE()->getValue(IntVal::uint32BE()->putValue($union));
		/*
		 * The better solution is to do an AND operation of $union against a
		 * full 32bit integer - it will become more clear if you read the full
		 * 32bit integer as 0x00000000FFFFFFFF. This leads to the 64bit part
		 * being dropped.
		 */
		$rid = $union & 0xFFFFFFFF;
	return $rid;
	}
	/**
	 * @param string $string 64bytes/512 bit
	 */
	static function expand(string $string): array {
		$split = str_split($string, 4);
		$w = [];
		foreach ($split as $value) {
			$w[] = IntVal::uint32BE()->getValue($value);
		}
		for($i=16;$i<80;$i++) {
			$w[] = self::rotateLeft($w[$i-3] ^ $w[$i-8] ^ $w[$i-14] ^ $w[$i-16], 1);
		}
	return $w;
	}
	
	static function getSHA(int $i, int $b, int $c, int $d) {
		if($i>=0 and $i<=19) {
			return ($b & $c) | (~$b & $d);
		}
		if($i>=20 and $i<=39) {
			return $b ^ $c ^ $d;
		}
		if($i>=40 and $i<=59) {
			return ($b & $c) | ($b & $d) | ($c & $d);
		}
		if($i>=60 and $i<=79) {
			return $b ^ $c ^ $d;
		}
	}
	
	static function getK(int $i): int {
		if($i>=0 and $i<=19) {
			return 0x5A827999;
		}
		if($i>=20 and $i<=39) {
			return 0x6ED9EBA1;
		}
		if($i>=40 and $i<=59) {
			return 0x8F1BBCDC;
		}
		if($i>=60 and $i<=79) {
			return 0xCA62C1D6;
		}
	}
	
	abstract function getData(int $chunk): string;
	function getChunk(): string {
		if($this->chCount<$this->chunks) {
			$chunk = $this->getData($this->chCount);
			$this->chCount++;
		return $chunk;
		}
		
		if($this->chCount == $this->chunks && $this->overpad == false) {
			$chunk = $chunk = $this->getData($this->chCount);
			$chunk .= chr(128).str_repeat("\0", 64-strlen($chunk)-9). IntVal::uint64BE()->putValue($this->length*8);
			$this->chCount++;
		return $chunk;
		}

		if($this->chCount == $this->chunks && $this->overpad == true) {
			$chunk = $chunk = $this->getData($this->chCount);
			$chunk = str_pad($chunk.chr(128), 64, "\0", STR_PAD_RIGHT);
			$this->chCount++;
		return $chunk;
		}
		
		if($this->chCount == $this->chunks+1 && $this->overpad == true) {
			$chunk = str_repeat("\0", 56).IntVal::uint64BE()->putValue($this->length*8);
			$this->chCount++;
		return $chunk;
		}
	return "";
	}

}
