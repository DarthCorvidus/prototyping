<?php
class DirectoryLinear {
	private string $path;
	private array $stack;
	private int $count = 0;
	private ?DirectoryObserver $do = null;
	function __construct(string $path) {
		$this->path = $path;
		$this->stack[] = $path;
	}
	
	function addDirectoryObserver(DirectoryObserver $observer) {
		$this->do = $observer;
	}

	private function handleEntry(SplFileInfo $entry) {
		try {
			$type = $entry->getType();
		} catch(\RuntimeException $e) {
			echo $this->do->onError($e);
			return;
		}
		if($type==="file") {
			$this->do->onFile($entry);
			return;
		}
		if($type==="dir") {
			$this->do->onDirectory($entry);
			return;
		}
		if($type==="link") {
			$this->do->onLink($entry);
			return;
		}
	}
	
	private function iterate(DirectoryIterator $iterator) {
		foreach($iterator as $fileInfo) {
			if($fileInfo->isDot()) {
				continue;
			}
			if($this->do!==null) {
				$this->handleEntry($fileInfo);
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
		while($path = array_pop($this->stack)) {
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
