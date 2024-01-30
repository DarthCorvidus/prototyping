<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SHA1StringTest extends TestCase {
	function testGetChunk() {
		$message = random_bytes(3814);
		$sha1 = new SHA1String($message);
	
		$expected = str_split($message, 64);
		$pad = chr(128). str_repeat("\0", 17);
		$size = str_repeat("\0", 6).chr(119).chr(48);
		$expected[59] .= $pad.$size;
		
		$chunks = array();
		while($chunk = $sha1->getChunk()) {
			$chunks[] = $chunk;
		}
		$this->assertEquals(60, count($chunks));
		$this->assertEquals(60, count($expected));
		$this->assertEquals($expected, $chunks);
	}
	function testGetChunk56() {
		$message = random_bytes(56);
		$sha1 = new SHA1String($message);
	
		$expected[] = $message.chr(128). str_repeat("\0", 7);
		$pad = str_repeat("\0", 56);
		$size = str_repeat("\0", 6).chr(1).chr(192);
		$expected[] .= $pad.$size;
		
		$chunks = array();
		while($chunk = $sha1->getChunk()) {
			$chunks[] = $chunk;
		}
		$this->assertEquals(2, count($chunks));
		#$this->assertEquals(2, count($expected));
		$this->assertEquals($expected, $chunks);
	}

	function testGetChunk64() {
		$message = random_bytes(64);
		$sha1 = new SHA1String($message);
	
		$expected[] = $message;
		$pad = chr(128). str_repeat("\0", 55);
		$size = str_repeat("\0", 6).chr(2).chr(0);
		$expected[] .= $pad.$size;
		
		$chunks = array();
		while($chunk = $sha1->getChunk()) {
			$chunks[] = $chunk;
		}
		$this->assertEquals(2, count($chunks));
		$this->assertEquals($expected, $chunks);
	}

	function testGetChunk63() {
		$message = random_bytes(63);
		$sha1 = new SHA1String($message);
	
		$expected[] = $message.chr(128);
		$pad = str_repeat("\0", 56);
		$size = str_repeat("\0", 6).chr(1).chr(248);
		$expected[] .= $pad.$size;
		
		$chunks = array();
		while($chunk = $sha1->getChunk()) {
			$chunks[] = $chunk;
		}
		$this->assertEquals(2, count($chunks));
		$this->assertEquals($expected, $chunks);
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

