<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SHA1FileTest extends TestCase {
	function setUp(): void {
		file_put_contents(__DIR__."/test.bin", random_bytes(1024*1024*10));
	}

	function tearDown(): void {
		unlink(__DIR__."/test.bin");
	}
	
	function testGetHash() {
		$expected = sha1_file(__DIR__."/test.bin");
		$sha1 = new SHA1File(new SplFileObject(__DIR__."/test.bin"));
		$hash = $sha1->getHash();
		$this->assertEquals($expected, $hash);
	}
}