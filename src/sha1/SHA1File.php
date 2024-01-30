<?php
class SHA1File extends SHA1 {
	public function __construct(\SplFileObject $file) {
		parent::__construct($file);
	}
	public function getData(int $chunk): string {
		
	}

	public function start(): void {
		
	}

	public function stop(): void {
		
	}
}
