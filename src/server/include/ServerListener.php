<?php
interface ServerListener {
	function onConnect(int $clientId): ClientListener;
}
