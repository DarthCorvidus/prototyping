#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
class Server {
	private StreamHub $streamHub;
	function __construct() {
		$this->streamHub = new StreamHub();
		$server = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);
		$this->streamHub->setServer($server, new FileServerListener());
	}
	
	function run() {
		$this->streamHub->listen();
	}
}

$server = new Server();
$server->run();