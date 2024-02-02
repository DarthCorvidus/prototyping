<?php
interface Timeshared {
	function start(): void;
	function loop(): bool;
	function stop(): void;
}
