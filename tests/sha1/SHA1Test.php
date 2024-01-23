<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SHA1Test extends TestCase {
	function testRotateLeft() {
		/**
		 * We use literal 32bit strings here to make the test more transparent.
		 */
		$this->assertEquals("00000000000000000000000000000001", sprintf("%032b", SHA1::rotateLeft(bindec("10000000000000000000000000000000"), 1)));
		$this->assertEquals("00000000000000000000000000000010", sprintf("%032b", SHA1::rotateLeft(bindec("00000000000000000000000000000001"), 1)));
		$this->assertEquals("11111111111111111111111111111111", sprintf("%032b", SHA1::rotateLeft(bindec("11111111111111111111111111111111"), 1)));
		$this->assertEquals("00000000000010001001100010000101", sprintf("%032b", SHA1::rotateLeft(bindec("01000000000000100010011000100001"), 2)));
		$this->assertEquals("01000000000000100010011000100001", sprintf("%032b", SHA1::rotateLeft(bindec("01000000000000100010011000100001"), 32)));
		$this->assertEquals("00100110001000010100000000000010", sprintf("%032b", SHA1::rotateLeft(bindec("01000000000000100010011000100001"), 16)));

	}
}
