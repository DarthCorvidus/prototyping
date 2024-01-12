<?php

class Timeshare {
	private array $timeshared = array();
	function __construct() {
		;
	}
	
	function addTimeshared(Timeshared $timeshared) {
		$this->timeshared[] = $timeshared;
	}
	
	function run() {
		while(!empty($this->timeshared)) {
			foreach($this->timeshared as $key => $value) {
				if(!$value->step()) {
					unset($this->timeshared[$key]);
				}
			}
		}
	}
}
