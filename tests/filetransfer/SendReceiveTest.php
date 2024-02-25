<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SendReceiveTest extends TestCase {
	// (8*4096)+321 = 33089
	const LARGE = 33089;
	// Size is exactly 4096 with header added
	const BLOCK = 4077;
	// Smaller than one block
	const SMALL = 309;
	const ZERO = 0;
	static function setUpBeforeClass(): void {
		file_put_contents(self::getSendPath()."/large.bin", random_bytes(self::LARGE));
		file_put_contents(self::getSendPath()."/block.bin", random_bytes(self::BLOCK));
		file_put_contents(self::getSendPath()."/small.bin", random_bytes(self::SMALL));
		touch(self::getSendPath()."/zero.bin");
	}
	
	static function tearDownAfterClass(): void {
		$files = array("large.bin", "block.bin", "small.bin", "zero.bin");
		foreach($files as $value) {
			unlink(self::getSendPath()."/".$value);
		}
	}
	
	function tearDown(): void {
		$files = array("large.bin", "block.bin", "small.bin", "zero.bin");
		foreach($files as $value) {
			if(file_exists(self::getReceivePath()."/".$value)) {
				unlink(self::getReceivePath()."/".$value);
			}
		}
	}
	
	static function getSendPath(): string {
		return __DIR__."/send";
	}
	
	static function getReceivePath(): string {
		return __DIR__."/receive";
	}
	
	function testLarge() {
		$send = new Examples\Server\SendFile(self::getSendPath()."/large.bin");
		$receive = new Examples\Server\ReceiveFile(self::getReceivePath());
		while($send->hasData() && $receive->loop()) {
			$receive->onData($send->getData());
		}
		$receive->onTerminate();
		$sendpath = self::getSendPath()."/large.bin";
		$recvpath = self::getReceivePath()."/large.bin";
		$this->assertSame(filesize($sendpath), filesize($recvpath));
		$this->assertSame(sha1_file(self::getSendPath()."/large.bin"), sha1_file(self::getReceivePath()."/large.bin"));
		$this->assertSame(false, $receive->loop());
	}
	
	function testBlock() {
		$sendpath = self::getSendPath()."/block.bin";
		$recvpath = self::getReceivePath()."/block.bin";
		$send = new Examples\Server\SendFile($sendpath);
		$receive = new Examples\Server\ReceiveFile(self::getReceivePath());
		while($send->hasData() && $receive->loop()) {
			$receive->onData($send->getData());
		}
		$receive->onTerminate();
		$this->assertSame(filesize($sendpath), filesize($recvpath));
		$this->assertSame(sha1_file($sendpath), sha1_file($recvpath));
		$this->assertSame(false, $receive->loop());
	}
	
	function testSmall() {
		$sendpath = self::getSendPath()."/small.bin";
		$recvpath = self::getReceivePath()."/small.bin";
		$send = new Examples\Server\SendFile($sendpath);
		$receive = new Examples\Server\ReceiveFile(self::getReceivePath());
		while($send->hasData() && $receive->loop()) {
			$receive->onData($send->getData());
		}
		$receive->onTerminate();
		$this->assertSame(filesize($sendpath), filesize($recvpath));
		$this->assertSame(sha1_file($sendpath), sha1_file($recvpath));
		$this->assertSame(false, $receive->loop());
	}
	
	function testZero() {
		$sendpath = self::getSendPath()."/zero.bin";
		$recvpath = self::getReceivePath()."/zero.bin";
		$send = new Examples\Server\SendFile($sendpath);
		$receive = new Examples\Server\ReceiveFile(self::getReceivePath());
		while($send->hasData() && $receive->loop()) {
			$receive->onData($send->getData());
		}
		$receive->onTerminate();
		$this->assertSame(filesize($sendpath), filesize($recvpath));
		$this->assertSame(sha1_file($sendpath), sha1_file($recvpath));
		$this->assertSame(false, $receive->loop());
	}

}