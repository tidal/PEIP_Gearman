<?php

class PEIP_Gearman_Gateway 
	extends PEIA_Simple_Messaging_Gateway {

	public function send($content){
		return $this->requestChannel->send($this->build($content->workload()));
	}

}

