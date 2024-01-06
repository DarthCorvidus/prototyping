<?php
class EventExample implements \input\InputListener {
	public function onInput(\input\ReadEvent $re) {
		echo $re->getData().PHP_EOL;
	}
}
