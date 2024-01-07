<?php
class DirectoryLinear {
	private string $path;
	private array $stack;
	private int $count = 0;
	function __construct(string $path) {
		$this->path = $path;
		$this->stack[] = $path;
	}
	
	private function iterate(DirectoryIterator $iterator) {
		foreach($iterator as $fileInfo) {
			if($fileInfo->isDot()) {
				continue;
			}
			if($fileInfo->isLink()) {
				continue;
			}
			if($fileInfo->isDir()) {
				$newpath = $fileInfo->getRealPath();
				//echo $newpath.PHP_EOL;
				if($newpath === "" or $newpath === false) {
					continue;
				}
				$this->stack[] = $newpath;
			}
		}
	}
	
	function run(): bool {
		while($path = array_shift($this->stack)) {
			$this->count++;
			try {
				$iterator = new DirectoryIterator($path);
			} catch(UnexpectedValueException $e) {
				continue;
			}
			$this->iterate($iterator);
		}
	return false;
	}
}
