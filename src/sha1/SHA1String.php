<?php
class SHA1String extends SHA1 implements Timeshared {
	private string $message;
	function __construct(string $message) {
		$this->message = $message;
		parent::setSize(strlen($message));
	}
	
	function getData(int $chunk): string {
		return substr($this->message, $chunk*64, 64);
	}
}
