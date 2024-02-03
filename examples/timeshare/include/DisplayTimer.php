<?php
namespace Examples\Timeshared;
class DisplayTimer implements TimerListener {
	private \TermIO $termio;
	function __construct(\TermIO $termio) {
		$this->termio = $termio;
	}
	
	public function onEnd(\Examples\Timeshared\Timer $timer) {
		echo "x".PHP_EOL;
		$this->termio->addBuffer("Timer with ".($timer->getMicroseconds()/10000)." seconds stopped after ".(($timer->getSpent())/10000));
	}
}
