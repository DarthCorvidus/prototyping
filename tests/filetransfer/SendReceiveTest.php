<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SendReceiveTest extends TestCase {
	// (8*4096)+321 = 33089
	const LARGE = 33089;
	static function setUpBeforeClass(): void {
		file_put_contents(self::getSendPath()."/large.bin", random_bytes(self::LARGE));
	}
	
	static function tearDownAfterClass(): void {
		unlink(self::getSendPath()."/large.bin");
	}
	
	function tearDown(): void {
		if(file_exists(self::getReceivePath()."/large.bin")) {
			unlink(self::getReceivePath()."/large.bin");
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
		while($send->hasData()) {
			$receive->onData($send->getData());
		}
		$receive->onTerminate();
		$sendpath = self::getSendPath()."/large.bin";
		$recvpath = self::getReceivePath()."/large.bin";
		$this->assertSame(filesize($sendpath), filesize($recvpath));
		$this->assertSame(sha1_file(self::getSendPath()."/large.bin"), sha1_file(self::getReceivePath()."/large.bin"));
	}
}