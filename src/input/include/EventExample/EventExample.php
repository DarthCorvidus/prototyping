<?php
class EventExample implements \input\ReadListener {
	public function onRead(\input\ReadEvent $re) {
		$data = $re->getData();
		echo $data.PHP_EOL;
	}
}
