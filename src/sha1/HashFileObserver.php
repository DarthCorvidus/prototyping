<?php
interface HashFileObserver {
	function onHashed(string $path, $hash);
}
