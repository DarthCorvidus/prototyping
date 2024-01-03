<?php
class FileServerListener implements ServerListener {
	public function onConnect(int $clientId): \ClientListener {
		echo "Accepted connection with id ".$clientId.".".PHP_EOL;
		$fileReceiver = new FileReceiver();
	return $fileReceiver;
	}
}
