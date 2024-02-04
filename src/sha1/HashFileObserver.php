<?php
interface HashFileObserver {
	function onHashed(SHA1File $sha1, string $hash): void;
}
