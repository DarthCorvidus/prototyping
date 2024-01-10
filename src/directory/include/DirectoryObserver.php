<?php
interface DirectoryObserver {
	function onDirectory(SplFileInfo $directory): void;
	function onFile(SplFileInfo $file): void;
	function onLink(SplFileInfo $link): void;
	function onError(\RuntimeException $e): void;
}