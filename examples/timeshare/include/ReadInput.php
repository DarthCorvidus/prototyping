<?php
class ReadInput implements Timeshared {
	private Timeshare $timeshare;
	function __construct(Timeshare $timeshare) {
		stream_set_blocking(STDIN, false);
		$this->timeshare = $timeshare;
	}

	private function handleCommand(string $command): void {
		$explode = explode(" ", $command);
		if(count($explode)==1) {
			$this->handleOne($command);
		return;
		}
		if(count($explode) == 2) {
			$this->handleTwo($explode);
		return;
		}
	}
	
	private function handleOne($command): void {
		if($command === "help") {
			echo "du <dir>      disk usage".PHP_EOL;
			echo "exit          exit program".PHP_EOL;
		}
	}
	
	private function handleTwo(array $command) {
		if($command[0]=="du") {
			if(!is_dir($command[1])) {
				echo "Path ".$command[1]." does not exist or is no directory".PHP_EOL;
			return;
			}
			$dir = new DirectoryLinear($command[1]);
			$dir->addDirectoryObserver(new DirectorySize());
			$this->timeshare->addTimeshared($dir);
		}
		
		if($command[0] == "sha1") {
			if(!is_dir($command[1])) {
				echo "Path ".$command[1]." does not exist or is no directory".PHP_EOL;
			return;
			}
			$dir = new DirectoryLinear($command[1]);
			$dir->addDirectoryObserver(new DirectorySHA());
			$this->timeshare->addTimeshared($dir);
		}
	}
	
	public function step(): bool {
		$input = fgets(STDIN);
		if($input === false) {
			return true;
		}
		$trimmed = trim($input);
		if($trimmed === "") {
			return true;
		}
		if($trimmed === "exit") {
			return false;
		}
		$this->handleCommand($trimmed);
	return true;
	}
}