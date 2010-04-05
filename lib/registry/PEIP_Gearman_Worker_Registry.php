<?php

class PEIP_Gearman_Worker_Registry {

	protected 
		$worker,
		$handlers;
	
	public function __construct(GearmanWorker $worker){
		$this->worker = $worker;
	}
	
	public function set($name, $handler){
		$this->handlers[$name] = $handler;
		$this->worker->addFunction($name, $handler); 		
	}

	public function get($name){
		return $this->handlers[$name];
	}
	
}

