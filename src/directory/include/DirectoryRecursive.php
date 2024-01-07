<?php
class DirectoryRecursive {
	private string $path;
	private int $count = 0;
	function __construct(string $path) {
		$this->path = $path;
	}
	
	private function recurse(DirectoryIterator $iterator) {
		foreach($iterator as $fileInfo) {
			if($fileInfo->isDot()) {
				continue;
			}
			if($fileInfo->isLink()) {
				continue;
			}
			if($fileInfo->isDir()) {
				$path = $fileInfo->getRealPath();
				$this->recurse(new DirectoryIterator($path));
			}
		}
	}
	
	function run() {
		$iterator = new DirectoryIterator($this->path);
		$this->recurse($iterator);
	}
}
