<?php
class OutputBuffer {
	private bool $hasContent = true;
	private array $buffer = array();
	function __construct() {
	}
	
	function add(string $string): void {
		$this->buffer[] = $string;
	}
	
	function addln(string $string): void {
		$this->buffer[] = $string.PHP_EOL;
	}
	
	function addlnArray(array $array): void {
		foreach($array as $value) {
			$this->addln($value);
		}
	}
	
	function isEmpty(): bool {
		return count($this->buffer)===0;
	}
	
	function getNext(): string {
		return array_shift($this->buffer);
	}
}
