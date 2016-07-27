<?php

class profiler{
	protected $startTime = NULL;
	protected $stopWatch = array();
	public $accuracy = 3;

	function __construct(){
		$this->startTime = microtime(true);
	}

	function stopWatch($name){
		$bagtrack = array_pop(debug_backtrace());
		$nowTime = $this->getTime();
		$betweenTime = (isset($this->stopWatch[count($this->stopWatch)-1])) ? $nowTime-$this->stopWatch[count($this->stopWatch)-1]['time'] : 0;
		
		$this->stopWatch[] = array(	'name'=>$name,
									'betweenTime'=>$betweenTime,
									'time'=>$nowTime,
									'line'=>$bagtrack['line'],
									'file'=>$bagtrack['file'],
									'function'=>$bagtrack['function'],
							);
	}
	
	function getTime(){
		return round(microtime(true) - $this->startTime, $this->accuracy);
	}
	
	function getStopWatch(){
		$this->stopWatch('End');
		return $this->stopWatch;
	}	
	
	function simple(){
		$this->stopWatch('End');
		foreach($this->stopWatch as $stops){
			echo "------------------------------------ <br>Метка ".$stops['name'].": <br>  файл: ".$stops['file']." (".$stops['line'].")<br>  время выполнения: ".$stops['betweenTime']."(".$stops['time'].")<br>------------------------------------ <br>";
		}
	}
}