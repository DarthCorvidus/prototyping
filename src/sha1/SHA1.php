<?php
abstract class SHA1 {
	protected int $h0 = 0x67452301;
	protected int $h1 = 0xEFCDAB89;
	protected int $h2 = 0x98BADCFE;
	protected int $h3 = 0x10325476;
	protected int $h4 = 0xC3D2E1F0;
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

}
