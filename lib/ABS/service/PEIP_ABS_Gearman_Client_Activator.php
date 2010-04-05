<?php

abstract class PEIP_ABS_Gearman_Client_Activator 
	extends PEIP_Pipe {
	
	protected 
		$client,
		$taskName,
		$taskMethod;
	
	public function __construct(GearmanClient $client, $taskName, PEIP_INF_Channel $inputChannel, PEIP_INF_Channel $outputChannel = NULL){
		$this->client = $client;
		$this->setTaskName($taskName);
		$this->setTaskCallbacks();
		$this->setInputChannel($inputChannel);
		if(is_object($outputChannel)){
			$this->setOutputChannel($outputChannel);	
		}	
	}	

	abstract protected function setTaskCallbacks();
	
	public function setTaskName($taskName){
		$this->taskName = $taskName;
	}	

	protected function runTasks(){
		$this->client->runTasks();	
	}				

	public function doReply(PEIP_INF_Message $message){
		if(!$this->taskName){
			throw new RuntimeException('No Task set.');
		}		
		if(!$this->taskMethod){
			throw new RuntimeException('No TaskMethod set.');
		}	
		$this->client->{$this->taskMethod}($this->taskName, serialize($message->getContent()), $message);
		$this->runTasks();
	}	
	
	public function createAndFireEvent($eventName, $task){
		$this->doFireEvent($eventName, array('TASK'=>$task), 'PEIP_Gearman_Client_Event');	
	}

	
}
