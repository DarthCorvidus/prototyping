<?php
interface HashStringObserver {
	function onHashed(SHA1String $sha1, string $hash);
}
