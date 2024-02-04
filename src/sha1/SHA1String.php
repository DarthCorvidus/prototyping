<?php
class SHA1String extends SHA1 implements Timeshared {
	private string $message;
	private ?HashStringObserver $hashObserver = null;
	function __construct(string $message) {
		$this->message = $message;
		parent::setSize(strlen($message));
	}
	
	public function setHashObserver(HashStringObserver $hashObserver) {
		$this->hashObserver = $hashObserver;
	}
	
	public function stop(): void {
		if($this->hashObserver!=null) {
			$this->hashObserver->onHashed($this, $this->result);
		}
	}
	
	function getData(int $chunk): string {
		return substr($this->message, $chunk*64, 64);
	}
}
