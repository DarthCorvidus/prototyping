#!/usr/bin/env php
<?php
require __DIR__.'/../../vendor/autoload.php';
class Client {
	private StreamHub $streamHub;
	function __construct(array $argv) {
		$client = stream_socket_client("tcp://0.0.0.0:8000", $errno, $errstr);
		stream_set_blocking($client, false);
		$this->streamHub = new StreamHub();
		$this->streamHub->addClient($client, new FileSender($argv[1], $this->streamHub));
	}
	function run() {
		$this->streamHub->listen();
	}
}

$client = new Client($argv);
$client->run();