<?php
namespace Examples\Server;
use \plibv4\process\Task;
use \plibv4\process\TimeshareObserver;
use \plibv4\process\Timeshare;
use \plibv4\process\Scheduler;
class ServerProcess implements Task, TimeshareObserver {
	private mixed $server;
	private mixed $clientId = 0;
	private \plibv4\process\Timeshare $sched;
	private \plibv4\process\Timeshare $parent;
	private $terminated = false;
	private int $connected = 0;
	function __construct(Scheduler $timeshare) {
		$this->server = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);
		$this->sched = new Timeshare();
		$this->parent = $timeshare;
	}
	public function __tsFinish(): void {
		
	}

	public function __tsKill(): void {
		
	}

	public function __tsLoop(): bool {
		if($this->terminated) {
			return $this->sched->__tsLoop();
		}
		$this->sched->__tsLoop();
		$read = array($this->server);
		$write = array();
		if(stream_select($read, $write, $except, 0) < 1) {
			return true;
		}
		$client = stream_socket_accept($this->server);
		$clientStream = new StreamBinary($client, new \Examples\Server\Mode\Main());
		$this->sched->addTimeshareObserver($this);
		$this->sched->addTimeshared($clientStream);
		echo "Client accepted, ".$this->sched->getProcessCount()." processes.".PHP_EOL;
	return true;
	}
	
	public function getClientCount(): int {
		return $this->connected;
	}
	
	public function __tsPause(): void {
		
	}

	public function __tsResume(): void {
		
	}

	public function __tsStart(): void {
		
	}

	public function __tsTerminate(): bool {
		$this->terminated = true;
		return $this->sched->__tsTerminate();
	}

	public function onAdd(Scheduler $sched, Task $task): void {
		
	}

	public function onRemove(Scheduler $sched, Task $task, int $status): void {
		if($task instanceof \Examples\Server\StreamBinary) {
			$this->connected--;
			echo "Client disconnected, ".$this->connected." client(s).".PHP_EOL;
			echo "Client processes: ".$this->sched->getProcessCount().PHP_EOL;
		}
	}

	public function onStart(Scheduler $sched, Task $task): void {
		if($task instanceof \Examples\Server\StreamBinary) {
			$this->connected++;
			$this->clientId++;
			echo "Client accepted as ".$this->clientId.", ".$this->connected." client(s).".PHP_EOL;
			echo "Client processes: ".$this->sched->getProcessCount().PHP_EOL;
		}
	}

	public function __tsError(\Exception $e, int $step): void {
		
	}

	public function onError(Scheduler $scheduler, Task $task, \Exception $e, int $step): void {
		
	}

	public function onPause(Scheduler $scheduler, Task $task): void {
		
	}

	public function onResume(Scheduler $scheduler, Task $task): void {
		
	}
}
