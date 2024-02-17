<?php
namespace Examples\Server;
interface StreamListener {
	function onData(string $data);
	function hasData(): bool;
	function getData(): string;
	function onConnect();
	function onDisconnect();
	/**
	 * To be called if Stream::terminate() was called.
	 */
	function onTerminate();
	function loop(): bool;
}
