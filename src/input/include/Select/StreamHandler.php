<?php
interface StreamHandler {
	function isBinary(): bool;
	function getBlockSize(): int;
	function handleData(string $data): void;
	function hasData(): bool;
	function getData(): string;
	function hasEnded(): bool;
}
