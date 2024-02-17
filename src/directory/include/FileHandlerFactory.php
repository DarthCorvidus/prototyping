<?php
interface FileHandlerFactory {
	function onFile(SplFileInfo $info): \plibv4\process\Timeshared;
}
