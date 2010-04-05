<?php

class PEIP_Gearman_Worker_Gateway 
	extends PEIA_Simple_Messaging_Gateway {

	public $job;	
		
		
	public function send($content){
		$this->job = $content;
		return $res =($this->requestChannel->send($this->buildMessage(unserialize($content->workload()))));
	}

	public function getJob(){
		return $this->job;
	}
	
	public function setReplyChannel(PEIA_INF_Channel $replyChannel){
		if(!($replyChannel instanceof PEIA_INF_Subscribable_Channel)){
			//throw new InvalidArgumentException('reply channel must be instance of PEIA_INF_Subscribable_Channel.');
		}		
		$this->replyChannel = $replyChannel;
		$gateway = $this;
		$handler = new PEIA_Callable_Handler(function($event) use($gateway){
			if($gateway->getJob()){
				$message = $event->getHeader('MESSAGE');
				echo "\ncallback job";
				$gateway->getJob()->sendData(serialize($message->getContent()));
				$gateway->getJob()->sendComplete(serialize($message->getContent()));	
			}	
		});
		$this->replyChannel->connect('preSend', $handler);
	}
	
	
}

