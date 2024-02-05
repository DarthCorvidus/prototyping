<?php
interface FileHandlerFactory {
	function onFile(SplFileInfo $info): Timeshared;
}
