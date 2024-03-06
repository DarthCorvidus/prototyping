<?php
namespace Examples\Server;
interface StreamHandler {
	function rcvData(string $data);
	/*
	 * hasData must not change the internal state, as it may be called by
	 * Stream several times, if the stream is not ready to write.
	 */
	function hasData(): bool;
	function getData(): string;
	/**
	 * true if the handler is still active, false if not
	 * @return bool
	 */
	function isActive(): bool;
	function getBlocksize(): int;
	function onDisconnect(): void;
	function onTerminate(): void;
}
