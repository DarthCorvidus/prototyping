<?php
class ReadInput implements TermIOListener {
	private Timeshare $timeshare;
	private TermIO $termio;
	function __construct(Timeshare $timeshare) {
		stream_set_blocking(STDIN, false);
		$this->timeshare = $timeshare;
	}

	function onInput(TermIO $termio, string $command): void {
		$explode = explode(" ", $command);
		if(count($explode)==1) {
			$this->handleOne($termio, $command);
		return;
		}
		if(count($explode) == 2) {
			$this->handleTwo($termio, $explode);
		return;
		}
	}
	
	private function handleOne(TermIO $termio, $command): void {
		if($command==="exit" or $command === "x") {
			exit();
		}
		if($command === "help") {
			$termio->addBuffer("du <dir>      disk usage");
			$termio->addBuffer("sha1 <dir>    create SHA1 sum for each file in <dir>");
			$termio->addBuffer("rnd <int>     creates <int> amount of strings and their SHA1 sum");
			$termio->addBuffer("t <int>       Run timer for <int> seconds");
			$termio->addBuffer("exit or x     exit program");
		}
	}
	
	private function handleTwo(Termio $termio, array $command) {
		if($command[0]=="du") {
			if(!is_dir($command[1])) {
				echo "Path ".$command[1]." does not exist or is no directory".PHP_EOL;
			return;
			}
			$dir = new DirectoryLinear($command[1]);
			$dir->addDirectoryObserver(new DirectorySize());
			Timeshare::addTimeshared($dir);
		}
		
		if($command[0] == "sha1") {
			if(!is_dir($command[1])) {
				echo "Path ".$command[1]." does not exist or is no directory".PHP_EOL;
			return;
			}
			$dir = new DirectoryLinear($command[1]);
			$dir->addDirectoryObserver(new DirectorySHA());
			Timeshare::addTimeshared($dir);
		}
		
		if($command[0] == "rnd") {
			$rnd = new Randomizer((int)$command[1]);
			$this->timeshare->addTimeshared($rnd);
		}
		
		if($command[0] == "t") {
			$timer = new \Examples\Timeshared\Timer((int)$command[1], new Examples\Timeshared\DisplayTimer($termio));
			$this->timeshare->addTimeshared($timer);
		}
	}
}