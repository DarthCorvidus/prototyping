<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SendFileTest extends TestCase {
	// (8*4096)+321 = 33089
	const SIZE = 33089;
	static function setUpBeforeClass(): void {
		file_put_contents(self::getPath(), random_bytes(self::SIZE));
	}
	
	static function tearDownAfterClass(): void {
		unlink(self::getPath());
	}
	
	static function getPath(): string {
		return __DIR__."/test.bin";
	}
	
	function testConstruct() {
		$send = new Examples\Server\SendFile(self::getPath());
		$this->assertInstanceOf(Examples\Server\SendFile::class, $send);
		$send->onTerminate();
	}
	
	function testHeader() {
		$send = new Examples\Server\SendFile(self::getPath());
		$xheader = chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(129).chr(65);
		$xheader .= chr(0).chr(8);
		$xheader .= "test.bin";
		$send->loop();
		$this->assertSame(true, $send->hasData());
		$header = $send->getData();
		$this->assertEquals($xheader, substr($header, 0, 18));
		$this->assertSame(4096, strlen($header));
	}
	
	function testBlocksize() {
		$send = new Examples\Server\SendFile(self::getPath());
		while($send->loop() && $send->hasData()) {
			$this->assertSame(4096, strlen($send->getData()));
		}
	}
	
	function testData() {
		$send = new Examples\Server\SendFile(self::getPath());
		$expected = file_get_contents(self::getPath());
		$data = "";
		while($send->loop() && $send->hasData()) {
			$data .= $send->getData();
		}
		$this->assertSame($expected, substr($data, 18, self::SIZE));
	}
	
}
