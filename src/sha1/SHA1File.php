<?php
class SHA1File extends SHA1 {
	private SplFileObject $file;
	public function __construct(\SplFileObject $file) {
		$this->file = $file;
		$this->length = $this->file->getSize();
		$this->chunks = ($this->length>>6);
		$mod = $this->length % 64;
		if($mod >= 56) {
			$this->overpad = true;
		}
	}
	public function getData(int $chunk): string {
		return $this->file->fread(64);
	}

	public function start(): void {
		
	}

	public function stop(): void {
		
	}
}
