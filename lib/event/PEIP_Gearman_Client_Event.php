<?php

class PEIP_Gearman_Client_Event
	extends PEIA_Event {

	public function getTask(){
		return $this->getHeader('TASK');
	}	
			
}