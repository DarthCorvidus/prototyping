<?php

class Timeshare {
	static array $timeshared = array();
	private function __construct() {
		;
	}
	
	static function addTimeshared(Timeshared $timeshared) {
		self::$timeshared[] = $timeshared;
		echo "Added ".get_class($timeshared).PHP_EOL;
	}
	
	static function run() {
		while(!empty(self::$timeshared)) {
			foreach(self::$timeshared as $key => $value) {
				$running = $value->step();
				if(!$running) {
					echo "end of ".$key.PHP_EOL;
					unset(self::$timeshared[$key]);
				}
			}
		}
	}
	
	static function stop() {
		self::$timeshared = array();
		#foreach(self::$timeshared as $key => $value) {
		#	
		#}
	}
}
