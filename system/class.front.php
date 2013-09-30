<?php
final class Front  extends Load{
	protected $registry;
	protected $pre_action = array();
	protected $error;
	
	public function __construct($registry) {
		$this->registry = $registry;
		parent::__construct($registry);
		$this->path_to_system($registry->get('path_to_system'));
		
	}
	
	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}
	
  	public function dispatch($action, $error) {	
		$this->error = $error;			
		foreach ($this->pre_action as $pre_action) {
			$result = $this->execute($pre_action);
					
			if ($result) {
				$action = $result;
				
				break;
			}
		}
				
		while ($action) {
			$action = $this->execute($action);
		}
  	}
    
	private function execute($action) {
		$file   = $action->getFile();
		$class  = $action->getClass();
		$method = $action->getMethod();
		$args   = $action->getArgs();
		$action = '';

		if (file_exists($file)) {
			 if (!$this->load_controller($class, $method, $args)) $action = $this->error;
		} else {
			$action = $this->error;
			$this->error = '';
		}
		
		return $action;
	}
}
?>