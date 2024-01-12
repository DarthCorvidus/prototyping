<?php
class ReadInput implements Timeshared {
	function __construct() {
		stream_set_blocking(STDIN, false);
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
			echo "exit!!!".PHP_EOL;
			return false;
		}
	return true;
	}
}