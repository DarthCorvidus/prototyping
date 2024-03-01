<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class ReceiveFileTest extends TestCase {
	// (8*4096)+321 = 33089
	const SIZE = 33089;
	#static function setUpBeforeClass(): void {
	#	file_put_contents(self::getPath(), random_bytes(self::SIZE));
	#}
	
	#static function getPath(): string {
	#	return __DIR__."/test.bin";
	#}
	
	function tearDown(): void {
		#if(file_exists(__DIR__."/test.bin")) {
		#	unlink(__DIR__."/test.bin");
		#}
	}
	
	function testConstruct() {
		$receive = new Examples\Server\ReceiveFile(__DIR__);
		$this->assertInstanceOf(Examples\Server\ReceiveFile::class, $receive);
		$receive->onTerminate();
	}
	
	function testFirstBlock() {
		$random = random_bytes(self::SIZE);
		$first = chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(129).chr(65);
		$first .= chr(0).chr(8);
		$first .= "test.bin";
		$first .= substr($random, 18, 4096-18);
		
		$receive = new Examples\Server\ReceiveFile(__DIR__);
		$receive->onData($first);
		$receive->loop();
		$this->assertFileExists(__DIR__."/test.bin");
		$receive->onTerminate();
	}
	
	function testCheckFilename() {
		$random = random_bytes(self::SIZE);
		$first = chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(129).chr(65);
		$first .= chr(0).chr(14);
		$first .= "../../test.bin";
		$first .= substr($random, 18, 4096-18);

		$receive = new Examples\Server\ReceiveFile(__DIR__);
		$receive->onData($first);
		
		$reflection = new ReflectionClass($receive);
		$name = $reflection->getProperty("filename");
		$name->setAccessible(true);
		
		$this->assertSame("test.bin", $name->getValue($receive));
		
				
				
		#$receive->loop();
		#$this->assertFileExists(__DIR__."/test.bin");
		#$receive->onTerminate();
	}
	
	function testFile() {
		$random = random_bytes(self::SIZE);
		$first = chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(129).chr(65);
		$first .= chr(0).chr(8);
		$first .= "test.bin";
		$first .= substr($random, 18, 4096-18);
		
		$receive = new Examples\Server\ReceiveFile(__DIR__);
		$receive->onData($first);
		$receive->loop();
		$this->assertFileExists(__DIR__."/test.bin");
		$receive->onTerminate();
	}
}
