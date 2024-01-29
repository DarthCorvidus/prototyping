<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SHA1StringTest extends TestCase {
	function testPrepareMessage() {
		$message = random_bytes(953);
		$prepared = SHA1String::prepareMessage($message);
		$pad = chr(128). str_repeat("\0", 62);
		$size = str_repeat("\0", 6).chr(29).chr(200);
		$expected = $message.$pad.$size;
		
		$this->assertEquals(1024, strlen($prepared));
		$this->assertEquals(1024, strlen($expected));
		$this->assertEquals($expected, $prepared);
	}
	
	function testPrepareMessage512Bit() {
		$message = random_bytes(64);
		$prepared = SHA1String::prepareMessage($message);
		$pad = chr(128). str_repeat("\0", 55);
		$size = str_repeat("\0", 6).chr(2).chr(0);
		$expected = $message.$pad.$size;
		
		$this->assertEquals(128, strlen($prepared));
		$this->assertEquals(128, strlen($expected));
		$this->assertEquals($expected, $prepared);
	}
	
	function testPrepareMessageEmpty() {
		$message = "";
		$prepared = SHA1String::prepareMessage($message);
		$pad = chr(128). str_repeat("\0", 55);
		$size = str_repeat("\0", 8);
		$expected = $message.$pad.$size;
		
		$this->assertEquals(64, strlen($prepared));
		$this->assertEquals(64, strlen($expected));
		$this->assertEquals($expected, $prepared);
	}
	
	function testGetHashEmpty() {
		$sha1 = new SHA1String("");
		$expected = sha1("");
		$this->assertEquals($expected, $sha1->getHash());
	}
	
	function testGetHash() {
		$sha1 = new SHA1String('password');
		$expected = sha1("password");
		$this->assertEquals($expected, $sha1->getHash());
	}
	/**
	 * Test lowest hash that 'overpads' to the next chunk, ie expands the
	 * message by another 64 bytes just for padding.
	 */
	function testGetHash56() {
			$message = random_bytes(56);
			$sha1 = new SHA1String($message);
			$this->assertEquals(sha1($message), $sha1->getHash());

	}
	/*
	 * Test on strings from 1 to 1024 bytes
	 */
	function testGetHashStress() {
		for($i=1;$i<=1024;$i++) {
			$message = random_bytes($i);
			$sha1 = new SHA1String($message);
			$this->assertEquals(sha1($message), $sha1->getHash());
		}
	}
}

