<?php

class PEIA_Gearman_Context_Plugin 
	extends PEIA_ABS_Context_Plugin {

	protected static $builders = array(
		'gearman_worker' => 'createGearmanWorker',
		'gearman_client' => 'createGearmanClient',
		'gearman_client_activator' => 'createClientActivator',
		'gearman_worker_gateway' => 'createWorkerGateway'
	);
	
	public function createGearmanWorker($config){
		$config['class'] = 'GearmanWorker';
		return $this->createGearmanService($config);
	}
		
	public function createGearmanClient($config){
		$config['class'] = 'GearmanClient';
		return $this->createGearmanService($config);
	}	

	protected function createGearmanService($config){
		
		$service = $this->context->createService($config);
		if($service->server){
			foreach($service->server as $server){
				$service->addServer((string)$server['name']);	
			}
		}else{
			$service->addServer();	
		}
		return $service;	
	}
	
	public function createClientActivator($config){
		$client = $this->context->getService((string) $config['client']);
		$task = (string)$config['task'];
		if($client && $task != ''){
			$args = array(
				$client, 
				$task, 
				$this->context->doGetChannel('input', $config), 
				$this->context->doGetChannel('output', $config)
			);
			return $this->context->buildAndModify($config, $args, 'PEIP_Gearman_Client_Activator');
		}	
	}
	
	public function createWorkerGateway($config){
		return $this->context->createGateway($config, 'PEIP_Gearman_Worker_Gateway');
	}
}
