<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SHA1FileTest extends TestCase implements HashFileObserver {
	private string $observerHash = "";
	private ?SHA1File $observerObject = null;
	function setUp(): void {
		#file_put_contents(__DIR__."/test.bin", random_bytes(1024*1024*10));
		file_put_contents(__DIR__."/test.bin", random_bytes(1024));
	}
	
	public function onHashed(SHA1File $sha1, string $hash): void {
		$this->observerHash = $hash;
		$this->observerObject = $sha1;
	}

	
	function tearDown(): void {
		$this->observerHash = "";
		$this->observerObject = null;
		unlink(__DIR__."/test.bin");
	}
	
	function testGetHash() {
		$expected = sha1_file(__DIR__."/test.bin");
		$sha1 = new SHA1File(new SplFileObject(__DIR__."/test.bin"));
		$hash = $sha1->getHash();
		$this->assertEquals($expected, $hash);
	}
	
	function testHashObserver() {
		$expected = sha1_file(__DIR__."/test.bin");
		$sha1 = new SHA1File(new SplFileObject(__DIR__."/test.bin"));
		$sha1->setHashObserver($this);
		$scheduler = new \plibv4\process\Timeshare();
		$scheduler->addTask($sha1);
		$scheduler->run();
		$this->assertEquals($expected, $this->observerHash);
		$this->assertEquals($sha1, $this->observerObject);
	}
}