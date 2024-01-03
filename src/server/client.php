#!/usr/bin/env php
<?php
class Client {
	private $client;
	private $stdin;
	private $blocksize = 1024*1;
	private bool $binary = false;
	function __construct(array $argv) {
		$this->client = stream_socket_client("tcp://0.0.0.0:8000", $errno, $errstr);
		stream_set_blocking($this->client, true);
		$this->stdin = STDIN;
		$this->path = $argv[1];
	}
	function listen() {
		$fh = fopen($this->path, "r");
		$size = filesize($this->path);
		$left = $size;
		$sent = 0;
		$start = hrtime(true);
		while(TRUE) {
			#$read = array($this->stdin);
			$write = array($this->client);
			if(@stream_select($read, $write, $except, $tv_sec = 5) < 1) {
				echo "No activity".PHP_EOL;
				continue;
			}
			#echo "Activity.".PHP_EOL;
			#foreach($read as $value) {
				#if($value===$this->stdin) {
				#	$input = trim(fgets($this->stdin));
				#	if($input==="") {
				#		continue;
				#	}
				#	echo $input.PHP_EOL;
				#}
			#}
			foreach($write as $socket) {
				$data = fread($fh, $this->blocksize);
				fwrite($socket, str_pad($data, $this->blocksize, "\0"));
				$left -= $this->blocksize;
				$sent += $this->blocksize;
				if($sent % 1024*16 == 0) {
					echo "Sent ".$sent.", left ".$left.PHP_EOL;
				}
				if($left<=0) {
					$end = hrtime(true);
					fclose($socket);
					fclose($fh);
					echo "Time: ".round(($end-$start)/1000000000, 2).PHP_EOL;
					exit();
				}
			}
		}
	}
}

$client = new Client($argv);
$client->listen();