<?php
namespace Examples\Server;
interface StreamListener {
	function onData(string $data);
	function hasData(): bool;
	function getData(): string;
	function onConnect();
	function onDisconnect();
	function loop(): bool;
}
