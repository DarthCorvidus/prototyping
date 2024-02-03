<?php
namespace Examples\Timeshared;
interface TimerListener {
	function onEnd(Timer $timer);
}
