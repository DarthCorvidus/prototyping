<?php
namespace Examples\Server;
interface StreamListener {
	function onTerminate();
	function onDisconnect();
}
