<?php

class PEIP_Gearman_Client_Event
	extends PEIP_Event {

	public function getTask(){
		return $this->getHeader('TASK');
	}	
			
}