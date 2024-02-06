<?php
class SHA1File extends SHA1 {
	private ?SplFileObject $file;
	private ?HashFileObserver $hashObserver = null;
	public function __construct(\SplFileObject $file) {
		$this->file = $file;
		parent::setSize($this->file->getSize());
	}
	
	public function setHashObserver(HashFileObserver $hashObserver) {
		$this->hashObserver = $hashObserver;
	}
	
	public function getData(int $chunk): string {
		return $this->file->fread(64);
	}

	public function finish(): void {
		$this->file = null;
		if($this->hashObserver!=null) {
			$this->hashObserver->onHashed($this, $this->result);
		}
	}
}
