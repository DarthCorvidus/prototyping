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
	
	function testPrepareMessage512() {
		$message = random_bytes(512);
		$prepared = SHA1String::prepareMessage($message);
		$pad = chr(128). str_repeat("\0", 55);
		$size = str_repeat("\0", 6).chr(16).chr(0);
		$expected = $message.$pad.$size;
		
		$this->assertEquals(576, strlen($prepared));
		$this->assertEquals(576, strlen($expected));
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
}

