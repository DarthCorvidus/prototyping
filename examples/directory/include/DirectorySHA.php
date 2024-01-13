<?php
class DirectorySHA implements DirectoryObserver {
	private int $startTime;
	private int $inspected = 0;
	function __construct() {
		;
	}

	public function onDirectory(\SplFileInfo $directory): void {
		
	}

	public function onEnd(): void {
		echo "Inspected:  ".$this->inspected.PHP_EOL;
		echo "Time spent: ".round((hrtime(true)-$this->startTime)/1000000000, 2).PHP_EOL;
	}

	public function onError(\RuntimeException $e): void {
		
	}

	public function onFile(\SplFileInfo $file): void {
		sha1_file($file->getRealPath());
		$this->inspected++;
	}

	public function onLink(\SplFileInfo $link): void {
		
	}

	public function onStart(): void {
		$this->startTime = hrtime(true);
	}
}
