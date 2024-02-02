<?php
class Timeshare implements Timeshared {
	private array $timeshared = array();
	private $pointer = 0;
	private $count = 0;
	function __construct() {
		;
	}
	
	function addTimeshared(Timeshared $timeshared) {
		$this->timeshared[] = $timeshared;
		$this->count = count($this->timeshared);
	}
	
	function stop(): void {
		foreach($this->timeshared as $value) {
			$this->timeshared->stop();
		}
		$this->timeshared = array();
	}

	public function start(): void {
		
	}
	
	private function remove(Timeshared $timeshared) {
		$new = array();
		$i = 0;
		foreach($this->timeshared as $key => $value) {
			if($value==$timeshared) {
				$this->pointer = -1;
				$value->stop();
				continue;
			}
			$new[] = $value;
			if($this->pointer<0) {
				$this->pointer = $i;
			}
			$i++;
		}
		if($this->pointer<0) {
			$this->pointer = 0;
		}
		$this->timeshared = $new;
		$this->count = count($this->timeshared);
	}
	
	public function loop(): bool {
		if(empty($this->timeshared)) {
			return false;
		}
		if($this->timeshared[$this->pointer]->loop()) {
			$this->pointer++;
		} else {
			$this->remove($this->timeshared[$this->pointer]);
		}
		if($this->pointer==$this->count) {
			$this->pointer = 0;
		}
	return true;
	}
}
