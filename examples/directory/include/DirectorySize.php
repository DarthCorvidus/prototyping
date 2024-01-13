<?php
class DirectorySize implements DirectoryObserver {
	private int $size = 0;
	private int $dirCount = 0;
	private int $fileCount = 0;
	private int $startTime;
	public function onDirectory(\SplFileInfo $directory): void {
		$this->dirCount++;
		$this->size += $directory->getSize();
	}

	public function onFile(\SplFileInfo $file): void {
		$this->fileCount++;
		$this->size += ceil($file->getSize()/4096)*4096;
	}
	
	public function onError(\RuntimeException $e): void {
		echo $e->getMessage().PHP_EOL;
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
		echo "Directories: ".$this->dirCount.PHP_EOL;
		echo "Files:       ".$this->fileCount.PHP_EOL;
		echo "Size:        ".$this->size.PHP_EOL;
		echo "Time spent:  ".round((hrtime(true)-$this->startTime)/1000000000, 2).PHP_EOL;
	}

	public function onStart(): void {
		$this->startTime = hrtime(true);
	}
}