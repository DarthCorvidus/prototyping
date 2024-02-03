<?php
class DirectorySize implements DirectoryObserver {
	private int $size = 0;
	private int $dirCount = 0;
	private int $fileCount = 0;
	private int $startTime;
	private TermIO $termio;
	function __construct(TermIO $termio) {
		$this->termio = $termio;
	}
	
	public function onDirectory(\SplFileInfo $directory): void {
		$this->dirCount++;
		$this->size += $directory->getSize();
	}

	public function onFile(\SplFileInfo $file): void {
		$this->fileCount++;
		$this->size += ceil($file->getSize()/4096)*4096;
	}
	
	public function onError(\RuntimeException $e): void {
		$this->termio->addBuffer($e->getMessage());
	}

	public function onLink(\SplFileInfo $link): void {
		
	}
	
	public function getFileCount(): int {
		return $this->fileCount;
	}
	
	public function getDirectoryCount(): int {
		
	}
	
	public function getSize(): int {
		return $this->size;
	}

	public function onEnd(): void {
		$this->termio->addBuffer("Directories: ".$this->dirCount);
		$this->termio->addBuffer("Files:       ".$this->fileCount);
		$this->termio->addBuffer("Size:        ".$this->size);
		$this->termio->addBuffer("Time spent:  ".round((hrtime(true)-$this->startTime)/1000000000, 2));
	}

	public function onStart(): void {
		$this->startTime = hrtime(true);
	}
}