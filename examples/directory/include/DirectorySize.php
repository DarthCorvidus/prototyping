<?php
class DirectorySize implements DirectoryObserver {
	private int $size = 0;
	private int $dirCount = 0;
	private int $fileCount = 0;
	public function onDirectory(\SplFileInfo $directory): void {
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
}