<?php
namespace Examples\Server;
interface StreamListener {
	function onData(string $data);
	/*
	 * hasData must not change the internal state, as it may be called by
	 * Stream several times, if the stream is not ready to write.
	 */
	function hasData(): bool;
	function getData(): string;
	function onConnect();
	function onDisconnect();
	/**
	 * To be called if Stream::terminate() was called.
	 */
	function onTerminate();
	function loop(): bool;
	function getBlocksize(): int;
}
