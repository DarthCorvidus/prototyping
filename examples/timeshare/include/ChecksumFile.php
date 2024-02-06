<?php
namespace Examples\Timeshared;
class ChecksumFile implements \Timeshared, \HashFileObserver {
	private \SHA1File $sha;
	private string $path;
	private \TermIO $termio;
	function __construct(\SplFileInfo $file, \TermIO $termio) {
		$this->path = $file->getRealPath();
		$this->sha = new \SHA1File(new \SplFileObject($this->path));
		$this->sha->setHashObserver($this);
		$this->termio = $termio;
	}
	public function loop(): bool {
		return $this->sha->loop();
	}

	public function start(): void {
		$this->sha->start();
	}

	public function onHashed(\SHA1File $sha1, string $hash): void {
		$this->termio->addBuffer($this->path." checksummed");
	}

	public function finish(): void {
		$this->sha->finish();
	}

	public function kill(): void {
		$this->sha->kill();
	}

	public function pause(): void {
		$this->sha->pause();
	}

	public function resume(): void {
		$this->sha->resume();
	}

	public function terminate(): void {
		$this->sha->terminate();
	}
}
