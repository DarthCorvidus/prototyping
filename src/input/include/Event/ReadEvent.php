<?php
namespace input;
class ReadEvent {
	private string $data;
	function __construct(string $data) {
		$this->data = $data;
	}
	
	function getData(): string {
		return $this->data;
	}
}
