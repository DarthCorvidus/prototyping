<?php
class FileServerListener implements ServerListener {
	private StreamHub $hub;
	public function __construct(StreamHub $hub) {
		$this->hub = $hub;
	}
	public function onConnect(int $clientId): \ClientListener {
		echo "Accepted connection with id ".$clientId.".".PHP_EOL;
		$fileReceiver = new FileReceiver($this->hub);
	return $fileReceiver;
	}
}
