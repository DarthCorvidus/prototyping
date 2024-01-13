<?php
class DirectoryLinear implements Timeshared {
	private string $path;
	private array $stack;
	private int $count = 0;
	private ?DirectoryObserver $do = null;
	private ?DirectoryIterator $current = null;
	private bool $started = false;
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
	
	function run() {
		while($this->step()) {
			
		}
	}
	
	private function stepStack(): bool {
		if(!$this->started) {
			$this->started = true;
			if($this->do !== null) {
				$this->do->onStart();
			}
		}
		$nextpath = array_pop($this->stack);
		if($nextpath !== null) {
			try {
				$this->current = new DirectoryIterator($nextpath);
			} catch(UnexpectedValueException $e) {
				echo $e->getMessage().PHP_EOL;
				return true;
			}
			return true;
		}
		if($this->do  !== null) {
			$this->do->onEnd();
		}
	return false;
	}
	
	private function stepEntry(): bool {
		if($this->current === null) {
			return false;
		}

		if(!$this->current->valid()) {
			$this->current = null;
			return false;
		}
		$current = $this->current->current();
		if($this->do !== null) {
			$this->handleEntry($current);
		}
		if(!$current->isLink() && $current->isDir() && !$current->isDot()) {
			$this->stack[] = $current->getRealPath();
		}
		$this->current->next();
	return true;
	}
	
	function step(): bool {
		if($this->stepEntry()) {
			return true;
		}
	return $this->stepStack();
	}
}
