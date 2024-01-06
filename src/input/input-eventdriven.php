#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
class InputEventDriven {
	private \input\StreamHub $hub;
	function __construct() {
		$this->hub = new \input\StreamHub(new EventExample());
	}
	
	function run() {
		$this->hub->listen();
	}
}

$input = new InputEventDriven();
$input->run();