<?php
interface ClientListener {
	function getBlocksize(): int;
	function onRead(string $data): void;
	function hasWrite(): bool;
	function getWrite(): string;
}