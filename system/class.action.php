<?php
final class Action {
	protected $file;
	protected $class;
	protected $method;
	protected $args = array();
	protected $path_to_system = SITE;

	public function __construct($registry,$route, $args = array()) {
		$this->path_to_system = $registry->get('path_to_system');
		$path = '';
		$parts = explode('/', str_replace('../', '', $route));
		
		foreach ($parts as $part) { 
			$path .= $part;
			
			if (is_dir($this->path_to_system . 'controller/' . $path)) {
				$path .= '/';
				
				array_shift($parts);
				
				continue;
			}
			if (is_file($this->path_to_system . 'controllers/' . str_replace('../', '', $path) . '.php')) {
				$this->file = $this->path_to_system . 'controllers/' . str_replace('../', '', $path) . '.php';
				
				$this->class = preg_replace('/[^a-zA-Z0-9]/', '', $path);

				array_shift($parts);
				
				break;
			}
			
			if ($args) {
				$this->args = $args;
			}
		}		
		$method = array_shift($parts);

		foreach ($parts as $part) $this->args[] = $part;
				
		if ($method) {
			$this->method = $method;
		} else {
			$this->method = 'index';
		}		
	}
	

	
	public function getFile() {
		return $this->file;
	}
	
	public function getClass() {
		return $this->class;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getArgs() {
		return $this->args;
	}
}
?>