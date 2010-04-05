<?php

class PEIP_Gearman_Client_Activator 
	extends PEIP_ABS_Gearman_Client_Activator {
	
	protected $taskMethod = 'addTask';	

	protected function setTaskCallbacks(){
		$events = array(
			'complete',
			'created',
			'data',
			'exception',
			'fail',
			'status',
			'warning',
			'workload'
		);
		$activator = $this;
		foreach($events as $event){
			$method = 'set'.ucfirst($event).'Callback';
			$this->client->{$method}(function($task) use ($event, $activator){
				$activator->createAndFireEvent($event, $task);	
			});
		}
		$this->connectCall('complete', array($this, 'onComplete'));
	}

	public function onComplete(PEIP_Gearman_Client_Event $event){
		$res = unserialize($event->getTask()->data());
		if($res && $this->getOutputChannel()){					
			$this->replyMessage($this->getOutputChannel(), $res);
		}	
	}	
	
	
}

